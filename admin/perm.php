<?php

// Автор: andrey3761
// Копирайт: xoops.ws
 
include 'admin_header.php';
// Admin Gui
$indexAdmin = new ModuleAdmin();

// Подключаем форму прав
include_once $GLOBALS['xoops']->path( 'class/xoopsform/grouppermform.php' );

// Заголовок страницы
xoops_cp_header();
// Меню страницы
echo $indexAdmin->addNavigation('perm.php');

echo '<br /><br /><br />';

$formTitle = _AM_CHAT_FORM_PERM;
$permName = "chat_perm";
$permDsc = _AM_CHAT_PERM_DSC;
$perms_array = array( 1 => _AM_CHAT_PERM_1 , 2 => _AM_CHAT_PERM_2 );

$moduleId = $xoopsModule->getVar( 'mid' );

// Права
$permForm = new XoopsGroupPermForm( $formTitle, $moduleId, $permName, $permDsc, 'admin/perm.php');
// Забиваем все права
foreach( $perms_array as $perm_id => $perm_name ) {
    $permForm->addItem($perm_id , $perm_name) ;
}

echo $permForm->render();
echo "<br /><br /><br /><br />\n";
unset ( $permForm );

// Текст в подвале админки
include "admin_footer.php";
//
xoops_cp_footer();
