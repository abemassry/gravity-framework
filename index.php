<?php session_start();
require_once "/var/www/gravity/class/vars.php";
require_once "$php_dir/class/displayfunctions.php";
require_once "$php_dir/class/inputfunctions.php";
require_once "$php_dir/class/outputfunctions.php";

if ($_SESSION['user_logged']) {
    $user = $_SESSION['user_logged'];
}

if ($_POST['page']) {
    $page = mysql_real_escape_string(stripslashes($_POST['page']));
    if ($page == 'login') {
        $page_content = displayLogin();
    } elseif ($page == 'register') {
        $page_content = displayRegister();
    } elseif ($page == "profile-$user") {
        $page_content = displayProfileLogged($user);
    } elseif ($page == 'logout') {
        $_SESSION['user_logged']='';
        session_destroy();
        $message = 'You have been logged out';
        //if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
        //    setcookie("cookname", "", time()-60*60*24*60, "/");
        //    setcookie("cookpass", "", time()-60*60*24*60, "/");
        //}
    }
} else {
    $page_content = 'Homepage';
}
if ($_POST['login_pressed']) {
    $name = mysql_real_escape_string(stripslashes($_POST['name']));
    $passwd = mysql_real_escape_string(stripslashes($_POST['password']));
    $success = checkLogin($name, $passwd);
    if ($success == 2) {
        //redirect back to previous page
    } else {
        $login_message = 'Wrong username or password';
    }
}
if ($_POST['register_pressed']) {
    $name = mysql_real_escape_string(stripslashes($_POST['name']));
    $passwd = mysql_real_escape_string(stripslashes($_POST['password']));
    $email = mysql_real_escape_string(stripslashes($_POST['email']));
    $success = register($name, $passwd, $email);
    if ($success == 2) {
        $page_content = displayRegisterSuccess();
    } elseif ($success == 1) {
        $register_message = 'Username already in use';
    } elseif ($success == 4) {
        $register_message = 'Email address not valid';
    } elseif ($success == 7) {
        $register_message = 'There is a space in your username';
    } elseif ($success == 8) {
        $register_message = 'Email address already in use';
    }
    //elseif($success==5) {
    //    echo 'reCaptcha not correct';
    //}
    
}

$search = displaySearch();
$logo = displayLogo();
$login_nav = displayLoginNav();
$navigation_area = displayNavigationArea();
$subnav = displaySubNav();
$page_left = displayPageLeft($page_content);
$page_right = displayPageRight();
//head
echo '<?xml version="1.0" encoding="UTF-8"?>';
$head = <<< EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>gravity</title>
    <link rel='stylesheet' type='text/css' href='$html_dir/design/style.css' />
    <!--[if lt IE 9]>
    <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
    <![endif]-->
    <!--[if IE 7]>
    <link rel='stylesheet' type='text/css' href='$html_dir/design/ie7.css' />
    <![endif]-->
</head>
<body>
EOF;
echo $head;
$body = <<< EOF
<div id="content">
    <div id="masthead">
        <div id="upper-masthead">
            $logo
            $search
            $login_nav
        </div>
        <div id="lower-masthead">
            $navigation_area
            $subnav
        </div>
    </div>
    <div id="page">
        $message
        $login_message
        $register_message
        $page_left
        $page_right
    </div>
    <div id="footer">
        <ul>
            <li>Item 1</li>
            <li>Item 2</li>
            <li>Item 3</li>
            <li>Item 4</li>
            <li>Item 5</li>
        </ul>
    </div>
</div>
EOF;
echo $body;

?>
</body>
</html>