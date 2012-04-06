<?php session_start();
require_once "/var/www/class/vars.php";
require_once "$php_dir/class/displayfunctions.php";
require_once "$php_dir/class/inputfunctions.php";
require_once "$php_dir/class/outputfunctions.php";


if ($_POST['emailpressed']) {
    $email = mysql_real_escape_string(trim(stripslashes($_POST['email'])));
    $subject = mysql_real_escape_string(trim(stripslashes($_POST['subject'])));
    $message = mysql_real_escape_string(trim(stripslashes($_POST['message'])));
    $success = contactCheckSend($email, $subject, $message);
    if ($success == '2') {
        $message = '<div class="grid_6 push_9 message-box"><div class="form-msg-success"><h3>Successfully Sent</h3></div></div>';
    } else {
        $message = '<div class="grid_6 push_9 message-box"><div class="form-msg-error"><h3>Submission Unsuccessful</h3></div></div>';
    }
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