<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Admin Common                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Admin Common Functions - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$isOPERATOR=true;
require_once("functions.php");
require_once("security.php");
require_once("config.php");
require_once("config_cslh.php");

// this should be obsolete we are up to php 8+ now: 
//require_once("file_get_contents.php");
require_once("class/operator.php");
$colorfile = "images".C_DIR. $CSLH_Config['colorscheme'] .C_DIR."color.php";
if(file_exists($colorfile)){
  require_once($colorfile);
} else {
	$color_background="FAFAFA";
  $color_alt1 = "E4E4E4";
}

$cookiesession=true;           // in most cases this is needed to be True. unles trans-id
$serversession=true;

$identity = identity($UNTRUSTED['cslhOPERATOR'],"cslhOPERATOR",$allow_ip_host_sessions,$serversession,$cookiesession);
$isavisitor = false;

update_session($identity);

// get the info on this admin user:
$query = "SELECT user_id,onchannel,show_arrival,user_alert,externalchats,istyping,isadmin,alertchat,alerttyping,alertinsite FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$people = $mydatabase->query($query);

?>
