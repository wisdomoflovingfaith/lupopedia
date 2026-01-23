<?php 
/*****************************************************************************************
  * This file should be "include_once" at the top of EVERY php page..
  * 
  * Examples of secure SQL input:
  * $query = "SELECT * FROM table where recno=". filter_sql($UNTRUSTED['recno'],true);
  * $query = "SELECT * FROM table where username='". filter_sql($UNTRUSTED['username'],false,255) . "'";
  * $query = "INSERT INTO table (message) VALUES ('". filter_sql(cslh_escape($UNTRUSTED['username']),false) . "'";
  *
  * Example of Text data:
  * print cslh_escape($UNTRUSTED);
  *
  * Notes:
  *   NEVER use urldecode() on a $_GET variable
  *   NEVER Trust input in a include , require, include_once.
  *   Try hard NOT to use system, exec, backquote, etc..
  */

// This is set here to tell the rest of the program that the security checks have been run:

define('IS_SECURE', true);

//Error Reporting:
//error_reporting(0);
//@error_reporting(E_ALL);
//error_reporting  (E_ERROR | E_WARNING | E_PARSE);  
@error_reporting(0);

// Display errors 
// @ini_set('display_errors', '1');
// do NOT Display errors
@ini_set('display_errors', '0');

// PHP5 with register_long_arrays off?
if (!isset($HTTP_POST_VARS) && (isset($_POST))){
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;
    
	// _SESSION is the only superglobal which is conditionally set
	if (isset($HTTP_SESSION_VARS)){
		$_SESSION = $HTTP_SESSION_VARS;
	} else {
	  $_SESSION = array();
	}
}

if (!isset($_REQUEST))
  $_REQUEST = array_merge( $_GET, $_POST, $_COOKIE );  

// Request data is placed in the untrusted array "as is" no
// slashes added or string validation is done. validation is 
// done later depending on what that data value is used for
// SEE : security_functions: filter_sql , cslh_escape, filter_what
$addslashes = false;
//$UNTRUSTED = parse_incoming($addslashes);
$UNTRUSTED = array_merge($_GET, $_POST, $_COOKIE, $_SESSION);

// _SESSION is the only superglobal which is conditionally set
if (!(isset($_SESSION ))){ 
	  $_SESSION = array();
}

// Delete Globals goes here except php 8 does not allow it :(  
//

?>