<?php
require_once 'vars.php';
require_once 'connection.php';

$sql = <<<EOS
CREATE TABLE IF NOT EXISTS users (
    user_id int(11) NOT NULL auto_increment,
    email varchar(255) NOT NULL default '',
    passwd varchar(255) NOT NULL default '',
    name varchar(100) NOT NULL default '',
    date_acitve datetime NOT NULL default '0000-00-00 00:00:00',
    about varchar(255) NOT NULL default '',
    facebook varchar(255) NOT NULL default '',
    twitter varchar(255) NOT NULL default '',
    website varchar(255) NOT NULL default '',
    photo varchar(255) NOT NULL default '',
    emails tinyint(1) NOT NULL default '1',
    refer int(11) NOT NULL default '0',
    quota int(11) NOT NULL default '5120',
    quota_used int(11) NOT NULL default '0',
    PRIMARY KEY (user_id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());
    
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS messages (
    message_id int(11) NOT NULL auto_increment,
    message_str varchar(255) NOT NULL default '',
    from_user_id int(11),
    to_user_id int(11),
    date_submitted datetime NOT NULL default '0000-00-00 00:00:00',
    title varchar(255) NOT NULL default '',
    body mediumtext,
    reply_to_messid int(11) default '0',
    tag varchar(255) NOT NULL default '',
    been_read tinyint(1) NOT NULL default '0',
    PRIMARY KEY (message_id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());

$sql = <<<EOS
CREATE TABLE IF NOT EXISTS comments (
    comment_id int(11) NOT NULL auto_increment,
    item_id int(11) NOT NULL default '0',
    user_id int(11),
    comment_votes int(11) NOT NULL default '0',
    date_submitted datetime NOT NULL default '0000-00-00 00:00:00',
    level int(11) NOT NULL default '0',
    body mediumtext,
    reply_to int(11) NOT NULL default '0',
    PRIMARY KEY (comment_id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());

$sql = <<<EOS
CREATE TABLE IF NOT EXISTS reg_codes (
    id int(11) NOT NULL AUTO_INCREMENT,
    code varchar(255) NOT NULL default '',
    count int(11) NOT NULL DEFAULT  '0',
    PRIMARY KEY (id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());
    
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS block (
    id int(11) NOT NULL AUTO_INCREMENT,
    site varchar(255) NOT NULL default '',
    PRIMARY KEY (id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());
    
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS blog (
    id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL default '',
    body longblob NOT NULL default '',
    tag varchar(255) NOT NULL default '',
    date datetime NOT NULL default '0000-00-00 00:00:00',
    approved tinyint(1) NOT NULL default '0',
    PRIMARY KEY (id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());
    
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS blog_comments (
    comment_id int(11) NOT NULL AUTO_INCREMENT,
    blod_id int(11) NOT NULL default '0',
    user_id int(11),
    comment_votes int(11) NOT NULL default '0',
    date_submitted datetime NOT NULL default '0000-00-00 00:00:00',
    body mediumtext,
    PRIMARY KEY (comment_id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());

$sql = <<<EOS
CREATE TABLE IF NOT EXISTS items (
    item_id int(11) NOT NULL auto_increment,
    user_id int(11),
    front_paged tinyint(1) NOT NULL default '0',
    date_submitted datetime NOT NULL default '0000-00-00 00:00:00',
    date_frontpaged datetime NOT NULL default '0000-00-00 00:00:00',
    title varchar(255) NOT NULL default '',
    link varchar(255) NOT NULL default '',
    price decimal(12,2) NOT NULL default '0.00',
    body mediumtext,
    subject varchar(255),
    votes int(11) NOT NULL default '0',
    rating1 int(11) NOT NULL default '0',
    rating2 int(11) NOT NULL default '0',
    rating3 int(11) NOT NULL default '0',
    rating4 int(11) NOT NULL default '0',
    rating5 int(11) NOT NULL default '0',
    category varchar(255),
    PRIMARY KEY (item_id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());
    
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS reviews (
    review_id int(11) NOT NULL auto_increment,
    item_id int(11),
    user_id int(11),
    title varchar(255) NOT NULL default '',
    review mediumtext NOT NULL,
    date_submitted datetime NOT NULL default '0000-00-00 00:00:00',
    rating int(11) NOT NULL default '0',
    votes int(11) NOT NULL default '0',
    own tinyint(1) NOT NULL default '0',
    PRIMARY KEY (review_id)
) ENGINE=InnoDB
EOS;
$result = mysql_query($sql)
    or die(mysql_error());

?>