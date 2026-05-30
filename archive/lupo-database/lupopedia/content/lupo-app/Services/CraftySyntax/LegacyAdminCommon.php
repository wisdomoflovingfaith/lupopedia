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
$colorfile = "lupo-images".C_DIR. $CSLH_Config['colorscheme'] .C_DIR."color.php";
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

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sessions_table = $table_prefix . 'sessions';
if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
  $people = $mydatabase->fetchRow(
    "SELECT session_id AS sessionid, actor_id AS user_id, last_seen_ymdhis AS lastaction, 0 AS onchannel, 0 AS show_arrival, 0 AS user_alert, 0 AS externalchats, 0 AS istyping, 0 AS isadmin, 0 AS alertchat, 0 AS alerttyping, 0 AS alertinsite FROM {$sessions_table} WHERE session_id = :sid",
    ['sid' => $identity['SESSIONID']]
  );
} else {
  $people = null;
}

?>
