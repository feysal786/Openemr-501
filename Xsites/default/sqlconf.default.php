<?php
//  OpenEMR
//  MySQL Config

$host	= 'db774714814.hosting-data.io';
$port	= '3306';
//$login	= 'openemr_demo';
//$pass	= 'Pmr123456';
//$dbase	= 'openemr_demo';

$login	= 'dbo774714814';
//$pass	= 'Pmr123456';
$pass	= 'Server%2018';
//$dbase	= 'openemr_zeest';

$dbase	= 'db774714814';

//Added ability to disable
//utf8 encoding - bm 05-2009
global $disable_utf8_flag;
$disable_utf8_flag = false;

$sqlconf = array();
global $sqlconf;
$sqlconf["host"]= $host;
$sqlconf["port"] = $port;
$sqlconf["login"] = $login;
$sqlconf["pass"] = $pass;
$sqlconf["dbase"] = $dbase;
//////////////////////////
//////////////////////////
//////////////////////////
//////DO NOT TOUCH THIS///
$config = 1; /////////////
//////////////////////////
//////////////////////////
//////////////////////////
?>
