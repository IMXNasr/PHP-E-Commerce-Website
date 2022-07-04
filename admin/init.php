<?php
    include './connect.php';
    $temp = './includes/templates/';
    $lang = './includes/languages/';
    $func = './includes/functions/';
    $css = './layout/css/';
    $js = './layout/js/';

    // INCLUDES:
    include $func . 'functions.php';
    include $lang . 'english.php';
    include $temp . 'header.php';
    $noNavbar = ['index.php'];
    $url = explode('/', $_SERVER['PHP_SELF']);
    $file = end($url);
    if(!in_array($file, $noNavbar)){include $temp . 'navbar.php';}