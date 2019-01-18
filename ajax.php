<?php

// Автор: andrey3761
// Копирайт: xoops.ws

include_once __DIR__ . '/header.php';
// Отключаем дебугер
$GLOBALS['xoopsLogger']->activated = false;

$myts = \MyTextSanitizer::getInstance();
$act  = isset($_POST['act']) ? $myts->addSlashes($_POST['act']) : '';

// Данные о пользователе
$uid    = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
$uname  = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uname') : $GLOBALS['xoopsConfig']['anonymous'];
$time   = time();

// Имя папки
$dirname = 'chat';
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');

// Временно: Гостям доступ закрыт
if (!is_object($GLOBALS['xoopsUser'])) {
    exit();
}

// Права на чтение
$perm_view = $grouppermHandler->checkRight('chat_perm', 1, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
if (!$perm_view) {
    exit();
}

$ip = xoops_getenv('REMOTE_ADDR');
// Константы
$cache_lm_key = 'chat_lm_id';

// Выбранное действие
switch ($act) {
    // Если мы отправляем данны
    case 'send':

        $text = isset($_POST['text']) ? $myts->addSlashes(trim($_POST['text'])) : '';
        if ($text && '\\\\' !== $text) {
            // Цензурим мессагу
            $text = $myts->censorString($text);
            // добавляем новую запись в таблицу messages
            $sql = sprintf("INSERT INTO `%s` (uid, uname, TIME, ip, message) VALUES(%u, '%s', %u, '%s', '%s')", $GLOBALS['xoopsDB']->prefix('chat_message'), $uid, $uname, $time, $ip, $text);
            $GLOBALS['xoopsDB']->query($sql);
            // Получаем ID сгенерированной записи
            $messid = $GLOBALS['xoopsDB']->getInsertId();
            // Заносим в кеш
            XoopsCache::write($cache_lm_key, $messid);
        }

        break;
    // Если мы получаем список месаг
    case 'load':

        $start = 0;
        $limit = $GLOBALS['xoopsModuleConfig']['startlimitmsg'];

        // 02.03.2011
        // Массив выходных параметров
        $ret = [];

        // ===================
        // == Список онлайн ==
        // ===================

        // Тайаут на удаления пользователя из списка онлайн
        $time_upd = $time - $GLOBALS['xoopsModuleConfig']['ttlonline'];
        // Удаляем всех, кто не проявлял активность N сек
        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('chat_online') . ' WHERE updated < ' . $time_upd;
        $GLOBALS['xoopsDB']->query($sql);

        // Смотрим, есть ли текущий пользователь в онлайне
        $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('chat_online') . ' WHERE uid = ' . $uid;
        list($numrows) = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->query($sql));
        // Если такой пользователь найден в списке онлайн
        if ($numrows) {
            // Обновляем время
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('chat_online') . ' SET `updated` = ' . $time . ' WHERE uid = ' . $uid;
        } else {
            // Вставляем пользователя
            $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('chat_online') . " ( uid, uname, updated ) VALUES ( $uid, '$uname', $time )";
        }
        // Выполняем запрос
        $GLOBALS['xoopsDB']->query($sql);

        // Находим всех юзеров из онлайна
        $sql    = 'SELECT uid, uname, sleepdate FROM ' . $GLOBALS['xoopsDB']->prefix('chat_online') . ' WHERE updated > ' . $time_upd . ' ORDER BY uname';
        $result = $GLOBALS['xoopsDB']->query($sql);
        // Список, кто в чате

        // Таймаут для неактивных пользовтелей
        $time_sleep = $time - $GLOBALS['xoopsModuleConfig']['timesleep'];
        // Счётчик
        $i = 0;
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            // Если юзер уснул...
            if (($row['sleepdate'] < $time_sleep) && $row['sleepdate']) {
                $userrank_class = 'chat-sleep';
            } else {
                $userrank_class = 'chat-user';
            }

            // забиваем массив данных
            // ID пользователя
            $ret['online'][$i]['uid'] = $row['uid'];
            // Имя пользователя
            $ret['online'][$i]['uname'] = $myts->htmlSpecialChars($row['uname']);
            // Класс/Статус пользователя
            $ret['online'][$i]['uclass'] = $userrank_class;

            // Инкремент
            ++$i;
        }

        // ==========================================

        // Заносим пользователя в список онлайн
        /** @var \XoopsOnlineHandler $onlineHandler */
        $onlineHandler = xoops_getHandler('online');
        $onlineHandler->write($uid, $uname, $time, $xoopsModule->getVar('mid'), $ip);

        // Посылается JS, содержит ID последней записи, которая есть у пользователя
        $last_message_id = \Xmf\Request::getInt('last', 0, 'POST');
        // Считываем из кеша ID последней месаги
        $messid = (int)XoopsCache::read($cache_lm_key);

        // Если у клиента ID месаги меньше того, что есть в БД, то достаём остальные
        if ($last_message_id < $messid || 0 == $messid || 0 == $last_message_id) {
            // выполняем запрос к базе данных для получения 10 сообщений последних сообщений с номером большим чем $last_message_id
            $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('chat_message') . " WHERE messid > $last_message_id AND deleted = 0 ORDER BY messid DESC";
            $result = $GLOBALS['xoopsDB']->query($sql, $limit, $start);

            // проверяем есть ли какие-нибудь новые сообщения
            if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
                // Получаем массив сообщений
                $messages = [];
                while ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                    $messages[] = $row;
                }

                // записываем номер последнего сообщения
                // [0] - это вернёт нам первый элемент в массиве $messages, но так как мы выполнили запрос с параметром "DESC" (в обратном порядке),
                // то это получается номер последнего сообщения в базе данных
                $last_message_id = $messages[0]['messid'];

                // переворачиваем массив (теперь он в правильном порядке)
                $messages = array_reverse($messages);

                // идём по всем этементам массива $messages
                $i = 0;
                foreach ($messages as $value) {
                    $userrank_class = 'chat-user';
                    // Время с учётом часового пояса
                    $thistime = xoops_getUserTimestamp($value['time']);
                    // Вынести в конфиг шаблон времени
                    $thistime = date('H:i:s', $thistime);
                    // Подсветка мессаг
                    if ($value['uid'] == $uid) {
                        $bg_msg = 'chat-bg-mymsg';
                    } elseif (chat_memsg($value['message'], $uname)) {
                        $bg_msg = 'chat-bg-memsg';
                    } else {
                        $bg_msg = '';
                    }

                    // Фон сообщения
                    $ret['message'][$i]['bg'] = $bg_msg;
                    // Время сообщения
                    $ret['message'][$i]['time'] = $thistime;
                    // Цвет ника
                    $ret['message'][$i]['uclass'] = $userrank_class;
                    // Имя пользователя
                    $ret['message'][$i]['uname'] = $myts->htmlSpecialChars($value['uname']);
                    // Сообщение
                    $ret['message'][$i]['message'] = $myts->displayTarea($value['message'], 0, 1, 0, 0, 0);
                    // ID сообщения
                    $ret['message'][$i]['messid'] = $value['messid'];
                    // UID
                    $ret['message'][$i]['uid'] = $value['uid'];

                    // Инкримент
                    ++$i;
                }

                // Номер последнего сообщения, чтобы в следующий раз начать загрузку со следующего сообщения
                $ret['lastmessid'] = $last_message_id;
            }
        }

        // ==========================================
        // ==== Если есть сообщения для удаления ====
        // ==========================================

        $sql    = 'SELECT messid FROM ' . $GLOBALS['xoopsDB']->prefix('chat_delmsg') . ' WHERE uid = ' . $uid;
        $result = $GLOBALS['xoopsDB']->query($sql);

        if ($result && ($GLOBALS['xoopsDB']->getRowsNum($result) > 0)) {
            // Перебираем всё
            $i = 0;
            while ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                $ret['delmess'][$i] = $row['messid'];

                ++$i;
            }
            // Удаляем из БД записи для текущего пользователя
            $sql_delete = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('chat_delmsg') . ' WHERE uid = ' . $uid;
            $GLOBALS['xoopsDB']->query($sql_delete);
        }

        // Возвращаем ответ скрипту через JSON
        echo json_encode($ret);

        break;
    // Событие фокуса
    case 'focus':

        $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('chat_online') . ' SET `sleepdate` = 0 WHERE uid = ' . $uid;
        $result = $GLOBALS['xoopsDB']->query($sql);

        break;
    // Событие неактивности
    case 'blur':

        $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('chat_online') . ' SET `sleepdate` = ' . $time . ' WHERE uid = ' . $uid;
        $result = $GLOBALS['xoopsDB']->query($sql);

        break;
    // Удаляем сообщение
    case 'remove':

        // ID сообщения для удаления
        $messid = \Xmf\Request::getInt('messid', 0, 'POST');
        // Проверка на существование messid
        // Если нет такого сообщения отправить ответ "Не найдено сообщение для удаления"

        // Сассив выходных данных
        $ret = [];

        $ret['err'] = 0;
        // Можно ли удалить
        $allow_remove = false;
        // Права на удаление сообщений
        $perm_remove = $grouppermHandler->checkRight('chat_perm', 2, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;

        // Если есть права на удаление
        if ($perm_remove) {
            // Разрешаем удалить
            $allow_remove = true;
            // Если нет прав на удаление, смотрим, моё ли это сообщение и не истёк ли срок удаления
        } else {
            $time_delmsg = $time - $GLOBALS['xoopsModuleConfig']['timedelmsg'];

            $sql    = 'SELECT `uid`, `time`, `deleted` FROM ' . $GLOBALS['xoopsDB']->prefix('chat_message') . " WHERE messid =  '" . $messid . "'";
            $result = $GLOBALS['xoopsDB']->query($sql);
            list($delmsg_uid, $delmsg_time, $delmsg_deleted) = $GLOBALS['xoopsDB']->fetchRow($result);
            // Моё ли это сообщение, и не вышло ли время редактирования
            if ($delmsg_uid == $uid && $delmsg_time >= $time_delmsg) {
                // Разрешаем удалить
                $allow_remove = true;

                // Если это не моё сообщение
            } elseif ($delmsg_uid != $uid) {
                // Вы не можете удалять чужие сообщения
                $ret['err'] = 3;

                // Если истекло время для удаления
            } elseif ($delmsg_time < $time_delmsg) {
                // Время для удаления сообщения истекло
                $ret['err'] = 4;
            }
        }

        // Если разрешено удалять
        if ($allow_remove) {
            // Помечаем сообщение как удалённое
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('chat_message') . ' SET `deleted` = 1, `deleted_uid` = ' . $uid . ' WHERE `messid` = ' . $messid;

            // Если удалось удалить
            if ($GLOBALS['xoopsDB']->query($sql)) {
                // Находим всех пользователей в онлайне, кроме текущего
                $sql    = 'SELECT uid FROM ' . $GLOBALS['xoopsDB']->prefix('chat_online') . ' WHERE uid <> ' . $uid;
                $result = $GLOBALS['xoopsDB']->query($sql);
                // Каждому пользователю заносим messid для удаления
                while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
                    // Вставляем в БД
                    $sql_insert = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('chat_delmsg') . ' ( messid, uid ) VALUES ( ' . $messid . ', ' . $row['uid'] . ' )';
                    $GLOBALS['xoopsDB']->query($sql_insert);
                }

                // Удалить удалось
                $ret['err'] = 0;
            } else {
                // Не удалось удалить из БД
                $ret['err'] = 2;
            }
        }

        // Возвращаем ответ скрипту через JSON
        echo json_encode($ret);

        break;
    // Если ничего нет - выходим
    default:
        exit();
        break;
}
