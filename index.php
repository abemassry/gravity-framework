<?php session_start();
require_once "/var/www/class/vars.php";

if ($_POST['emailpressed']) {
    $email = mysql_real_escape_string(trim(stripslashes($_POST['email'])));
    $subject = mysql_real_escape_string(trim(stripslashes($_POST['subject'])));
    $message = mysql_real_escape_string(trim(stripslashes($_POST['message'])));
    $success = contactCheckSend($email, $subject, $message);
}
if ($_GET['page']) {
    $page = mysql_real_escape_string(stripslashes($_GET['page']));
    $page_content = displayContent($page);
} else {
    $page_content = displayMain();
}
$mastHead = displayMasthead();
$footer = displayFooter();
$head = displayHTMLHead();
//head
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo $head;
$body = <<< EOF
    $mastHead
    $message
    <div class="clear"></div>
    $page_content
    $footer
    </div>
    </body>
    </html>
EOF;
echo $body;
?>