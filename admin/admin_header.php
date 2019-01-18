<?php

// Автор: andrey3761
// Копирайт: xoops.ws

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once dirname(dirname(dirname(__DIR__))) . '/class/xoopsformloader.php';

$moduleDirName = basename(dirname(__DIR__));
/** @var \XoopsModules\Chat\Helper $helper */
$helper      = \XoopsModules\Chat\Helper::getInstance();
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

global $xoopsModule;
if ($xoopsUser) {
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    if (!$grouppermHandler->checkRight('module_admin', $xoopsModule->getVar('mid'), $xoopsUser->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

// Define Stylesheet and JScript
//$xoTheme->addStylesheet( XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") . "/css/admin.css" );
