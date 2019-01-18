<?php
// Автор: andrey3761

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$adminObject->displayNavigation(basename(__FILE__));
$adminObject::setPaypal('xoopsfoundation@gmail.com');
$adminObject->displayAbout(false);

require_once __DIR__ . '/admin_footer.php';
