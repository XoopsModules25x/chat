<?php

// Автор: andrey3761
// Копирайт: xoops.ws

// Чат
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

$dirname = 'chat';

$uid = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
$time = time();

// Временно: Гостям доступ закрыт
if( ! is_object( $GLOBALS['xoopsUser'] ) ) redirect_header( XOOPS_URL, 3, _MD_CHAT_ACCESSONLYUSERS );

// Права на доступ к чату
$groups = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler =& xoops_gethandler('groupperm');
$perm_view = ( $gperm_handler->checkRight( 'chat_perm', 1, $groups, $GLOBALS['xoopsModule']->getVar('mid') ) ) ? true : false ;
if( ! $perm_view ) redirect_header( XOOPS_URL, 3, _NOPERM );

// Заголовок
$xoopsOption['xoops_pagetitle']= '';
$xoopsOption['template_main'] = 'chat_index.html';
include $GLOBALS['xoops']->path('header.php');


$xoTheme->addStylesheet(XOOPS_URL . '/modules/chat/css/style.css');
// jQuery
$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
// Конфиг
$xoTheme->addScript(XOOPS_URL . '/modules/chat/config.js.php');
// Скрипт чата
$xoTheme->addScript(XOOPS_URL . '/modules/chat/js/chat.js');
//$xoTheme->addMeta( 'meta', 'keywords', '');
//$xoTheme->addMeta( 'meta', 'description', '');

include $GLOBALS['xoops']->path('footer.php');

?>