<?php

// Автор: andrey3761
// Копирайт: xoops.ws

// Чат
include_once __DIR__ . '/header.php';

$dirname = 'chat';

$uid  = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// Временно: Гостям доступ закрыт
if (!is_object($GLOBALS['xoopsUser'])) {
    redirect_header(XOOPS_URL, 3, _MD_CHAT_ACCESSONLYUSERS);
}

// Права на доступ к чату
$groups           = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_view        = $grouppermHandler->checkRight('chat_perm', 1, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
if (!$perm_view) {
    redirect_header(XOOPS_URL, 3, _NOPERM);
}

// Заголовок
$xoopsOption['xoops_pagetitle'] = '';
$xoopsOption['template_main']   = 'chat_index.tpl';
include_once $GLOBALS['xoops']->path('header.php');

$xoTheme->addStylesheet(XOOPS_URL . '/modules/chat/assets/css/style.css');
// jQuery
$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
// Конфиг
$xoTheme->addScript(XOOPS_URL . '/modules/chat/config.js.php');
// Скрипт чата
$xoTheme->addScript(XOOPS_URL . '/modules/chat/assets/js/chat.js');
//$xoTheme->addMeta( 'meta', 'keywords', '');
//$xoTheme->addMeta( 'meta', 'description', '');

include_once $GLOBALS['xoops']->path('footer.php');
