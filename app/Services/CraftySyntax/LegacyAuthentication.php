<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Authentication Entry Point  ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Authentication Entry Point - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once("admin_common.php");

if( (mobi_detect()) && empty($UNTRUSTED['fullsite']) ){
	  Header("Location: mobile/");
	  exit;
	}

// error number:
if(!(isset($UNTRUSTED['err']))){ $err = 0; } else { $err = intval($UNTRUSTED['err']); }

// proccess login:
if(!(isset($UNTRUSTED['proccess']))){ $UNTRUSTED['proccess'] = "no"; }
if($UNTRUSTED['proccess'] == "yes"){
      if(validate_user($UNTRUSTED['myusername'],$UNTRUSTED['mypassword'],$identity)){
      	// TEMP :----
      	// In version 3.1.0 of CSLH I am going to separate out sessions database
      	// table from users. This session swapping is just a temporary thing till
      	// i finish the user database mapping feature in version 3.1.0 
      	$query = "DELETE FROM livehelp_users WHERE password='' AND sessionid='".$identity['SESSIONID']."'";	
      	$mydatabase->query($query);
      	$twentyminutes  = date("YmdHis", mktime(date("H"), date("i")+20,date("s"), date("m")  , date("d"), date("Y")));
        $query = "UPDATE livehelp_users 
?>
