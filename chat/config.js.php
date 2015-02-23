<?php

// Автор: andrey3761
// Копирайт: xoops.ws

//
include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';
// Отключаем дебугер
$GLOBALS['xoopsLogger']->activated = false;
// Заголовок
header('Content-type: application/x-javascript');

$dirname = 'chat';

$js = '';

$js .= '
// Интервал обновления чата
var chat_config_interval = ' . xoops_getModuleOption( 'interval', $dirname ) . ';
';

// ===============
// ID пользователя
// ===============
$uid = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$js .= '
// UID текущего пользователя
var chat_config_uid = ' . $uid . ';
';

// ===========================
// Права на удаление сообщений
// ===========================
$groups = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
$perm_remove = ( $gperm_handler->checkRight( 'chat_perm', 2, $groups, $GLOBALS['xoopsModule']->getVar('mid') ) ) ? 1 : 0 ;
$js .= '
// Может ли текущий польователь удалять все сообщения
var chat_config_isremove = ' . $perm_remove . ';
';

// ==================
// Языковые константы
// ==================
$js .= '
// Языковые константы
var chat_lang_confirmdelete = "' . _MD_CHAT_CONFIRMDELETE . '";
var chat_lang_delete = "' . _MD_CHAT_DELETE . '";
';

// =========================
// Языковые константы ошибок
// =========================
$errors = include $GLOBALS['xoops']->path( 'modules/' . $dirname . '/include/error.inc.php' );
// Объявляем JS массив
$js .= '
// Языковые константы ошибок
var chat_lang_errors = [];
';
// Перебираем все ошибки
foreach( $errors as $err_key => $err_val ){
	
	$js .= 'chat_lang_errors[' . $err_key . '] = "' . $err_val . '";
';
	
}

//
echo $js;

?>