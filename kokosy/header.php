<?php
require_once 'functions.php';
$pages = [
    'Home' => 'index.php',
    'About' => 'about.php',
    'Rules' => 'rules.php',
    'Forum' => '#',
    'Walkthrough' => '#',
    'Downloads' => '#'
];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Silent Hill Forum</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div id="wrapper">
    <div id="nav">
        <h1>Silent Hill Forum</h1>
        <ul id="menu">
            <?php echo get_menu($pages); ?>
        </ul>
    </div>
