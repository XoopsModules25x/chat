<?php

// Автор: andrey3761
// Копирайт: xoops.ws

use XoopsModules\Chat;

//require_once  dirname(__DIR__) . '/include/common.php';
/** @var \XoopsModules\Chat\Helper $helper */
$helper = \XoopsModules\Chat\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    // Административное меню
    'title' => _MI_CHAT_ADMIN_HOME,
    'link'  => 'admin/index.php',
    'desc'  => _MI_CHAT_ADMIN_HOME_DESC,
    'icon'  => '../../' . $pathIcon32 . '/home.png',
];

// История сообщений
//'title' =>  _MI_CHAT_ADMIN_HISTORY,
//'link' =>  "admin/history.php",
//$adminmenu[$i]["desc"] = _MI_CHAT_ADMIN_HISTORY_DESC;
//$adminmenu[$i]["icon"] =  '../../'.$pathIcon32.'/home.png';
//++$i;

// Права доступа
$adminmenu[] = [
    'title' => _MI_CHAT_ADMIN_PERM,
    'link'  => 'admin/perm.php',
    'desc'  => _MI_CHAT_ADMIN_PERM_DESC,
    'icon'  => '../../' . $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title' => _MI_CHAT_ADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'desc'  => _MI_CHAT_ADMIN_ABOUT_DESC,
    'icon'  => '../../' . $pathIcon32 . '/about.png',
];
