<?php
ini_set('display_errors', '0');
define("MSSQL_HOST","desktop-4scb355\sqlexpress");
define("MSSQL_USER","sa");
define("MSSQL_PASS","142536As");
define("MSSQL_DB","FLYFF_EoCRM");
define("MSSQL_ACDB","ACCOUNT_DBF");
define("MSSQL_CHDB","CHARACTER_01_DBF");
define("SYSTEM_VER","1.2");

include_once("function.php");

// Don't Edit This
$sql = new PDO("sqlsrv:server=".MSSQL_HOST."; Database=".MSSQL_DB."",MSSQL_USER,MSSQL_PASS);
$api = new API(true);
$ip = $_SERVER['REMOTE_ADDR'];

// You can edit under this.
$auth_key	=	0;	// Account Auth that can login.
$login_fail =	3;	// Log in failed time.
$blockip_time = 600; // Block IP Time.

?>