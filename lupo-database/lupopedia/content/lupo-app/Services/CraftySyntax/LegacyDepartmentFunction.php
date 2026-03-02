<?php
/**************************************************************************
 * Shows the details of a department as a <TR> row in HTML.
 * 
 * @param  array $row     associative array of values for the department.
 * @param  int   $update  flag for if this is an update or not set to 1 if it is an update
 *
 * @global array  $CSLH_Config array of configuration vars.
 * @global string $bgcolor     current background color.
 * @global array  $UNTRUSTED 
 * @global array  $lang        array of language words. 
 */

// LEGACY PRESERVATION NOTICE:
// This file has been migrated from legacy/craftysyntax/department_function.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_CHAT_ENGINE_DOCTRINE.md

function  showdetails($row,$update){
 Global $color_background,$color_alt1,$color_alt2,$CSLH_Config,$bgcolor,$mydatabase,$UNTRUSTED,$lang;
 
if($row['requirename'] == "N"){
 $needname_s_y  = ""; 
 $needname_s_n  = " CHECKED ";  
} else {
 $needname_s_y  = " CHECKED "; 
 $needname_s_n  = "";  	
}

if($row['emailfun'] == "N"){
 $emailfun_s_y  = ""; 
 $emailfun_s_n  = " CHECKED ";  
} else {
 $emailfun_s_y  = " CHECKED "; 
 $emailfun_s_n  = "";  	
}

if($row['dbfun'] == "N"){
 $dbfun_s_y  = ""; 
 $dbfun_s_n  = " CHECKED ";  
} else {
 $dbfun_s_y  = " CHECKED "; 
 $dbfun_s_n  = "";  	
}




if($row['leaveamessage'] == "no"){
$leaveamessage_s_n = " CHECKED ";
$leaveamessage_s_y = "";
} else {
$leaveamessage_s_n = "";
$leaveamessage_s_y = " CHECKED ";	
}

?>
