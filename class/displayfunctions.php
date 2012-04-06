<?php
require_once "/var/www/gravity/class/vars.php";
require_once "$php_dir/class/connection.php";
//require_once "$php_dir/class/inputfunctions.php";
//require_once "$php_dir/class/outputfunctions.php";

function displaySearch() {
$display = <<< EOF
    <div id="search">
        <form name="form" action="/search/" method="get">
            <input type="text" name="q" value="" size="20" />
            <button id="search_button" type="submit" value="Search">Search</button> 
        </form>
    </div>
EOF;
return $display;
}

function displayLogo() {
$display = <<< EOF
    <div id="logo"><a href="/gravity/">Gravity</a></div>
EOF;
return $display;
}

function displayLoginNav() {
    if ($_SESSION['user_logged']) {
        $user = $_SESSION['user_logged'];
$display = <<< EOF
    <div id="login-nav">
    <script language=javascript>
    function submitProfileLink()
    {
        document.profilelink.submit();
    }
    </script>
    <script language=javascript>
    function submitLogoutLink()
    {
        document.logoutlink.submit();
    }
    </script>
    <form action="/gravity/" name="profilelink" method="post">
            <input type="hidden" name="page" value="profile-$user">
            </form>
    <form action="/gravity/" name="logoutlink" method="post">
            <input type="hidden" name="page" value="logout">
            </form>
        <ul>
            <li><a href=# onclick="submitProfileLink()">$user</a></li>
            <li><a href=# onclick="submitLogoutLink()">Logout</a></li>
            <li>Help</li>
        </ul>
    </div>
EOF;
    } else {
$display = <<< EOF
    <div id="login-nav">
    <script language=javascript>
    function submitLoginLink()
    {
        document.loginlink.submit();
    }
    </script>
    <script language=javascript>
    function submitRegisterLink()
    {
        document.registerlink.submit();
    }
    </script>
    <form action="/gravity/" name="loginlink" method="post">
            <input type="hidden" name="page" value="login">
            </form>
    <form action="/gravity/" name="registerlink" method="post">
            <input type="hidden" name="page" value="register">
            </form>
        <ul>
            <li><a href=# onclick="submitLoginLink()">Login</a></li>
            <li><a href=# onclick="submitRegisterLink()">Register</a></li>
            <li>Help</li>
        </ul>
    </div>
EOF;
    }
return $display;
}

function displayNavigationArea() {
$display = <<< EOF
    <div id="navigationarea">
        <ul>
            <li>Item 1</li>
            <li>Item 2</li>
            <li>Item 3</li>
            <li>Item 4</li>
            <li>Item 5</li>
        </ul>
    </div>
EOF;
return $display;
}

function displaySubNav() {
$display = <<< EOF
    <div id="subnav"></div>
EOF;
return $display;
}

function displayPageLeft($page) {
$display = <<< EOF
    <div id="page-left">
        $page
    </div>
EOF;
return $display;
}

function displayPageRight() {
$display = <<< EOF
    <div id="page-right">
        
    </div>
EOF;
return $display;
}

function displayLogin() {
    //<td>Remember me:</td><td><input type="checkbox" name="remember"></td>
$display = <<< EOF
    <div id="login">
        <table>
        <form name="form1" method="post" action="/gravity/">
        <tr>
            <td>Username:</td><td><input name="name" type="text" id="name" value="$name" /></td>
        </tr>
        <tr>
            <td>Password:</td><td><input name="password" type="password" id="password" /></td>
        </tr>
            <input name="login_pressed" type="hidden" value="1">
        <tr>
        <td></td><td align="right"><input type="submit" name="submit" value="Login" /></td>
        </tr>
        </form>
        </table>
    </div>
EOF;
return $display;
}

function displayRegister() {
    //require_once('/var/www/class/recaptchalib.php');
    //$publickey = "6Lf-0roSAAAAABs6gkWoTG45HfeCdjy1blUj9Nqv"; // you got this from the signup page
    //echo recaptcha_get_html($publickey);
    //tr><td>reCaptcha</td><td><?php</td></tr>
$display = <<< EOF
    <div id="register_form">
        <form name="form1" method="post" action="/gravity/">
        <table>
            <tr><td>Username:</td><td><input name="name" type="text" id="name" value="$name" /></td></tr>
            <tr><td>Password:</td><td><input name="password" type="password" id="password" value="$password" /></td></tr>
            <tr><td>email:</td><td><input name="email" type="text" id="email" value="$email" /></td></tr>
            <input name="register_pressed" type="hidden" value="1">
            <tr><td>I Agree to the <br /><a class="iframe_ajax" href="/class/terms_of_use.php">Terms of Use</a> and <br /><a class="iframe_ajax" href="/class/privacy_policy.php">Privacy Policy</a></td><td><input type="submit" name="submit" value="Register" /></td></tr>
        </table>
        </form>
    </div>
EOF;
return $display;
}

function displayRegisterSuccess() {

$display = <<< EOF
    <p>
        Successfully Registered!
    </p>
    <p>
        <a href="/gravity/">Go to the Homepage</a>
    </p>
    <p>
        <a href="/gravity/">Go to your profile</a>
    </p>
    
EOF;
return $display;
}

function displayProfileLogged($name) {
    
    $photo = varProfilePhoto($name);
$display = <<< EOF
    <div id="profile">
        <div id="profile-left">
            <div id="user_photo">
                <img src="/gravity/photos/$photo" />
            </div>
        </div>
        <div id="profile-right">
            <h2>$name</h2>
        </div>
    </div>
EOF;
return $display;
}

function displayProduct($product_id) {    
    $photo = varProductPhoto($product_id);
    $title = varProductTitle($product_id);
    $description = varProductDescription($product_id);
    $company = varProductCompany($product_id);
    $price = varProductPrice($product_id);
    $stars = displayStars($product_id);
    $reviews = displayReviews($product_id);

$display = <<< EOF
    <div id="product">
        <div id="product-photo">
            <img src="/gravity/photos/$photo" />
        </div>
        <div id="title">
            <p>$title</p>
        </div>
        <div id="description">
            <p>$description</p>
        </div>
        <div id="company">
            <p>$company</p>
        </div>
        <div id="price">
            <p>$price</p>
        </div>
        $stars
        <div id="reviews">
        
        </div>
        
    </div>
EOF;
return $display;
}

function displayStars($product_id) {
    $average = varProductRating($product_id);
    if ($average == 0) {
        //no stars write a review
    } else {
        $ave = round($average);
        if ($ave == 1) {
            $stars = '<img src="1-star.png" />';
        } elseif ($ave == 2) {
            $stars = '<img src="2-star.png" />';
        } elseif ($ave == 3) {
            $stars = '<img src="3-star.png" />';
        } elseif ($ave == 4) {
            $stars = '<img src="4-star.png" />';
        } elseif ($ave == 5) {
            $stars = '<img src="5-star.png" />';
        }
    }
    
$display = <<< EOF
    <div id="stars">
        $stars
    </div>
EOF;
return $display;
}

function displayReviews($product_id) {
    
}

?>