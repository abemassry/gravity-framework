<?php

define('SQL_HOST',$sql_host);
//fvote.db.6279086.hostedresource.com
define('SQL_USER',$sql_user);
define('SQL_PASS',$sql_pass);
define('SQL_DB',$sql_db);

$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
    or die('Could not connect to the database; '. mysql_error());

mysql_select_db(SQL_DB, $conn)
    or die('Could not select database; ' . mysql_error());

?>