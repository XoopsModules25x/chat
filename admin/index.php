<?php

// Автор: andrey3761
// Копирайт: xoops.ws

require_once __DIR__ . '/admin_header.php';
// Display Admin header
xoops_cp_header();

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

$GLOBALS['xoopsTpl']->assign('world', 'мир');

// Выводим шаблон
$GLOBALS['xoopsTpl']->display('db:chat_admin_index.tpl');

require_once __DIR__ . '/admin_footer.php';
