<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------
// Please check https://lupopedia.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is CRAFTY SYNTAX Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================

if(!(isset($_REQUEST['removesetup']))){ $_REQUEST['removesetup'] = 0; }
 
// option to remove setup.php program after upgrade / installation:
if($_REQUEST['removesetup']==1){
  if(unlink("setup.php")){
     print "setup removed .. <a href=index.php>CLICK HERE</a>";	
     exit;
  } else {
     print "setup remove failed .. The server does not run as user so delete it manually and then re-visit index.php file";
     print "<br><br>";
     print "<br>After removing file <a href=index.php>click here</a>";  	
     exit;
  }  
}

// this file is only called as an include.. everything else is a hack:
if (!(defined('IS_SECURE'))){
	print "Hacking attempt . Exiting..";
	exit;
}

// path separator:
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
   define('C_DIR', "\\");
  else 
   define('C_DIR', "/");
// backwards compatability:
if($dbtype == "mysql_options.php"){ $dbtype = "mysql"; }
if($dbtype == "txt-db-api.php"){ $dbtype = "txt-db-api"; }

$CSLH_Config = array();
$CSLH_Config['version'] = "";
$CSLH_Config['site_title'] = "";
$CSLH_Config['use_flush'] = "";
$CSLH_Config['membernum'] = "";
$CSLH_Config['show_typing'] = "";
$CSLH_Config['webpath'] = "";
$CSLH_Config['s_webpath'] = "";
$CSLH_Config['speaklanguage'] = "";
$CSLH_Config['s_webpath'] = "";
$CSLH_Config['smtp_host'] = "";
$CSLH_Config['smtp_username'] = "";
$CSLH_Config['smtp_password']="";
$CSLH_Config['owner_email']="";

if($installed == "true"){
// CONNECT to database:
if ($dbtype == "mysql"){
if (function_exists('mysqli_query')) {
 require "class/mysqli_db.php";
} else {
	require "class/mysql_db.php";
}
 $mydatabase = new MySQL_DB;
// $dsn = "mysql://$datausername:$password@$server/$database";
// $mydatabase->connect($dsn);	
 $mydatabase->connectdb($server,$datausername,$password);
 $mydatabase->selectdb($database);
   
 if(!($mydatabase->CONN)){ 
   // oh hell the mysql database is down.
   exit;	
 }
} 
if ($dbtype == "txt-db-api"){	
 require "txt-db-api".C_DIR."txt-db-api.php";
 $mydatabase = new Database("livehelp");
 $dsn = "txt-db-api://$datausername:$password@$server/$database";
 $mydatabase->connect($dsn); 	
}
if(!(empty($_REQUEST['repair']))){
	$query = "REPAIR TABLE livehelp_users";
  $mydatabase->query($query);
	$query = "REPAIR TABLE livehelp_sessions";
  $mydatabase->query($query);
}
// LOAD CONFIG:
 // Use table_prefix from config.php, fallback to 'craftysyntax' if not set
 $table_prefix = isset($table_prefix) ? $table_prefix : 'craftysyntax';
 $query = "SELECT * FROM {$table_prefix}_config";
 $result = $mydatabase->query($query);
 // if this is not the setup and it exists re-direct:
    if ( (file_exists("setup.php")) && (!preg_match("/setup.php/", $_SERVER['PHP_SELF'])) ){
      header("Location: setup.php",TRUE,307);
      exit;
    } 
    $row = $result->fetchRow(DB_FETCHMODE_ASSOC);
    $CSLH_Config = $row;
    if (!isset($CSLH_Config['offset']) && isset($CSLH_Config['offest'])) {
        $CSLH_Config['offset'] = $CSLH_Config['offest'];
    }
}   
//backwards compat.
if($CSLH_Config['speaklanguage'] == "eng"){ $CSLH_Config['speaklanguage'] = "English"; }  
if($CSLH_Config['speaklanguage'] == "frn"){ $CSLH_Config['speaklanguage'] = "French"; } 
if($CSLH_Config['speaklanguage'] == "ger"){ $CSLH_Config['speaklanguage'] = "German"; } 
if($CSLH_Config['speaklanguage'] == "ita"){ $CSLH_Config['speaklanguage'] = "Italian"; } 
if($CSLH_Config['speaklanguage'] == "por"){ $CSLH_Config['speaklanguage'] = "Portuguese"; } 
if($CSLH_Config['speaklanguage'] == "spn"){ $CSLH_Config['speaklanguage'] = "Spanish"; } 

// Set Time Date:
$timezone_identifier = '';
if (isset($CSLH_Config['offset']) && $CSLH_Config['offset'] !== '') {
    if (function_exists('set_tz_by_offset')) {
        $timezone_identifier = set_tz_by_offset($CSLH_Config['offset']);
    }
}
if ($timezone_identifier === '' && isset($CSLH_Config['offest']) && $CSLH_Config['offest'] !== '') {
    if (function_exists('set_tz_by_offset')) {
        $timezone_identifier = set_tz_by_offset($CSLH_Config['offest']);
    }
}
if ($timezone_identifier === '' && function_exists('date_default_timezone_get')) {
    $timezone_identifier = date_default_timezone_get();
}
if ($timezone_identifier !== '' && function_exists('date_default_timezone_set')){ 
    date_default_timezone_set($timezone_identifier);
}

// Sessions:
$autostart = @ini_get('session.auto_start');
if($autostart==0){
 require_once("class/sessionmanager.php");
 $sess = new SessionManager();
}
if(empty($CSLH_Config['smtp_host'])){ $CSLH_Config['smtp_host'] = ""; }

if($CSLH_Config['smtp_host']!=""){
 require_once("class/smtp.php");
}
 
$languagefile = "lang".C_DIR."lang-" . $CSLH_Config['speaklanguage'] . ".php";
if(!(file_exists($languagefile))){
	$languagefile = "lang".C_DIR."lang-.php";
}	
include($languagefile);

?>
