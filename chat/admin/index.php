<?php

// Автор: andrey3761
// Копирайт: xoops.ws

require_once '../../../include/cp_header.php';
include 'admin_header.php';

xoops_cp_header();

    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation('index.php');
    echo $indexAdmin->renderIndex();

// Выводим шаблон
$GLOBALS['xoopsTpl']->display("db:chat_admin_index.html");

include "admin_footer.php";
//
xoops_cp_footer();

/*
//
include 'header.php';
// Заголовок админки
xoops_cp_header();
// Меню
loadModuleAdminMenu(0, "");

//
$GLOBALS['xoopsTpl']->assign('world', 'мир');




// Выводим шаблон
$GLOBALS['xoopsTpl']->display("db:chat_admin_index.html");
// Подвал админки
xoops_cp_footer();

*/
?>