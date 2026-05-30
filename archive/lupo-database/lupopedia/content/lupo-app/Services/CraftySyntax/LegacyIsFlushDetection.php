<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Is Flush Detection      ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Is Flush Detection - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once("functions.php");
require_once("security.php");
require_once("config.php");
require_once("config_cslh.php");  

if(empty($UNTRUSTED['scriptname'])){
    print "error: no script name provided.. is_flush.php?scriptname=[scriptname] ";
    exit;	
}

if(empty($UNTRUSTED['department'])){ $department=0; } else { $department = intval($UNTRUSTED['department']); }

// lupo_departments per livehelp_departments_migration.md
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$departments_table = $table_prefix . 'departments';
if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
  if ($department == 0) {
    $qRow = $mydatabase->fetchRow("SELECT department_id, name FROM {$departments_table} WHERE is_deleted = 0 LIMIT 1");
  } else {
    $qRow = $mydatabase->fetchRow("SELECT department_id, name FROM {$departments_table} WHERE department_id = :did AND is_deleted = 0", ['did' => $department]);
  }
} else {
  $qRow = null;
}
$department    = $qRow ? (int) $qRow['department_id'] : 0;
$messageemail  = $qRow ? ($qRow['name'] ?? '') : '';
$colorscheme   = '';
$leavetxt      = '';
$creditline    = 'N';
$onlineimage   = '';
$leaveamessage = '0';
$offlineimage  = '';
$speaklanguage = $CSLH_Config['speaklanguage'] ?? '';
if ($qRow && isset($qRow[1])) { /* name in $qRow[1] */ }

// Change Language if department Language is not the same as default language:
if(($CSLH_Config['speaklanguage'] != $speaklanguage) && !(empty($speaklanguage)) ){
 $languagefile = "lang/lang-" . $speaklanguage . ".php";
 if(!(file_exists($languagefile))){
	$languagefile = "lang/lang-.php";
 }	
 include($languagefile);
}

// get chatmode:
if(empty($CSLH_Config['chatmode'])) 
   $CSLH_Config['chatmode'] = "xmlhttp-refresh";
   
if(empty($_REQUEST['try'])) 
  $try = 2;
else 
  $try = intval($_REQUEST['try']);
    
$chatmodes = explode('-',$CSLH_Config['chatmode']);

if(empty($chatmodes[$try])) 
   $chatmodes[$try] = "refresh";

$success = $UNTRUSTED['scriptname'] . "_flush.php";
$fail = $page;

$_REQUEST['try'] = $_REQUEST['try'] + 1; 
reset($_REQUEST);

$querystring="";
while (list($key, $val) = each($_POST)) {
	if(!(is_array($key)) && !(is_array($val)))
     $querystring .= "&" . urlencode($key) . "=". urlencode($val);
}	

while (list($key, $val) = each($_GET)) {
	if(!(is_array($key)) && !(is_array($val)))
     $querystring .= "&" . urlencode($key) . "=". urlencode($val);
}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN"> 
<html> 
<head> 
<title>Detect FLUSH</title> 
</head>  
<SCRIPT type="text/javascript">
function flushworks(){
   window.location.replace("<?php print $success . "?setchattype=1&" . $querystring; ?>");
}

function flushdoesnotwork(){
   window.location.replace("<?php print $fail . "?" . $querystring; ?>");
}
</SCRIPT>
<body background=lupo-images/<?php echo $colorscheme; ?>/mid_bk.gif>
<?php echo $lang['txt92']; ?>     

<?php
// load the buffer 
sendbuffer();  
sleep(1);
print " . ";
?>
<SCRIPT type="text/javascript">
   setTimeout('flushworks()', 3000);
</SCRIPT>
<?php
// load buffer 
sendbuffer();  
sleep(3);
print " . ";
?>
<SCRIPT type="text/javascript">
   setTimeout('flushdoesnotwork()', 1000);
</SCRIPT>
</body> 
?>
