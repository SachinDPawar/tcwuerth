<?php
define('ROOT', dirname(__FILE__));
$config=include(ROOT.'/../admin/config.php');
// db constants
define("mysql_host",'localhost'); // database host
define("db_name",$config['dbname']); // database name
define("db_user",$config['dbuser']); // database user
define("db_password",$config['dbpassword']); // database password
