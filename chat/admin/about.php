<?php
// Автор: andrey3761

include '../../../include/cp_header.php';
include '../../../class/xoopsformloader.php';
include 'admin_header.php';

xoops_cp_header();

//$module_info =& $module_handler->get($xoopsModule->getVar("mid"));

$aboutAdmin = new ModuleAdmin();
echo $aboutAdmin->addNavigation('about.php');
echo $aboutAdmin->renderabout('', false);

include 'admin_footer.php';
//
xoops_cp_footer();

?>