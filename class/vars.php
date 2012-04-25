<?php


//SQL vars
$sql_host = 'localhost';
$sql_user = 'gravity-user';
$sql_pass = 'gravity-pass';
$sql_db = 'gravity';

//directory vars
$php_dir = '/var/www/gravity';
$html_dir = '/gravity';

function getPHPDir() {
    $php_dir = '/var/www/gravity';
    return $php_dir;
}

function getHTMLDir() {
    $html_dir = '/gravity';
    return $html_dir;
}

require_once "$php_dir/class/connection.php";
require_once "$php_dir/class/dbq.php";
require_once "$php_dir/class/inputfunctions.php";
require_once "$php_dir/class/outputfunctions.php";
require_once "$php_dir/class/displayfunctions.php";
?>