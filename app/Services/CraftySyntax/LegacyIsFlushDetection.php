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

// Get department information. First found if no specific department assigned
$qQry = "SELECT recno,messageemail,colorscheme,leavetxt,creditline,onlineimage,leaveamessage,offlineimage,speaklanguage FROM livehelp_departments "
      . (($department==0)? 'LIMIT 1': "WHERE recno=$department");
$qRes = $mydatabase->query($qQry);
$qRow = $qRes->fetchRow(DB_FETCHMODE_ORDERED); 
$department   = $qRow[0];         
$messageemail = $qRow[1];          
$colorscheme  = $qRow[2];           
$leavetxt     = $qRow[3];           
$creditline   = $qRow[4];            
$onlineimage  = $qRow[5];
$leaveamessage = $qRow[6];
$offlineimage = $qRow[7];
$speaklanguage = $qRow[8];

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
<body background=images/<?php echo $colorscheme; ?>/mid_bk.gif>
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
