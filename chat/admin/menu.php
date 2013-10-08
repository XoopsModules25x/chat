<?php

// Автор: andrey3761
// Копирайт: xoops.ws

$module_handler =& xoops_gethandler('module');
$xoopsModule =& XoopsModule::getByDirname('chat');
$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
//$pathImageAdmin = XOOPS_URL .'/'. $moduleInfo->getInfo('dirmoduleadmin').'/images/admin';
$pathImageAdmin = $moduleInfo->getInfo('icons32');

$adminmenu = array();

$i = 1;
// Административное меню
$adminmenu[$i]['title'] = _MI_CHAT_ADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["desc"] = _MI_CHAT_ADMIN_HOME_DESC;
$adminmenu[$i]["icon"] =  '../../'.$pathImageAdmin.'/home.png';
$i++;


// История сообщений
//$adminmenu[$i]['title'] = _MI_CHAT_ADMIN_HISTORY;
//$adminmenu[$i]['link'] = "admin/history.php";
//$adminmenu[$i]["desc"] = _MI_CHAT_ADMIN_HISTORY_DESC;
//$adminmenu[$i]["icon"] =  '../../'.$pathImageAdmin.'/home.png';
//$i++;

// Права доступа
$adminmenu[$i]['title'] = _MI_CHAT_ADMIN_PERM;
$adminmenu[$i]['link'] = "admin/perm.php";
$adminmenu[$i]["desc"] = _MI_CHAT_ADMIN_PERM_DESC;
$adminmenu[$i]['icon'] = '../../'.$pathImageAdmin.'/permissions.png';
$i++;


//
$adminmenu[$i]["title"] = _MI_CHAT_ADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["desc"] = _MI_CHAT_ADMIN_ABOUT_DESC;
$adminmenu[$i]["icon"] =  '../../'.$pathImageAdmin.'/about.png';
$i++;



?>