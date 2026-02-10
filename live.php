<?php
/* ⧉ WOLFIE v2.6 ⧉
   nav: mech | myth | rel | docs
   
   ## NAV
   pkg: misc
   mod: helpers
   asp: utility
   pur: Live help interface entry point for Crafty Syntax system.
   
   ## META
   cre: 2026-02-01T15:48:00Z
   mod: 2026-02-01T15:48:00Z
   upd: cascade#1
   tax: wolfie.header.taxonomy@2.3
   
   ## MYTH
   epo: wolfie-winter-2026
   sig: live-help-entry
   
   ## REL
   → 
   ← 
   ↔ 
   
   ## DOCS
   */
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

// LEGACY PRESERVATION NOTICE:
// This file has been migrated from legacy/craftysyntax/live.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_ROUTING_DOCTRINE.md

require_once("admin_common.php");
validate_session($identity);

if(!(empty($UNTRUSTED['speak']))){ 
	$_COOKIE['speaklanguage'] = $UNTRUSTED['speak']; 
	print "Language changed to " . $UNTRUSTED['speak'];
	print "<SCRIPT type=\"text/javascript\"> window.location.replace(\"live.php\");</script>";
	print "<a href=live.php>click here</a>";
	exit;
} 

// Session/operator row from lupo_sessions (per livehelp_sessions_migration.md, livehelp_users_migration.md)
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sessions_table = $table_prefix . 'sessions';
$people = null;
if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
  $row = $mydatabase->fetchRow(
    "SELECT session_id, actor_id, session_data FROM {$sessions_table} WHERE session_id = :sid",
    ['sid' => $identity['SESSIONID']]
  );
  if ($row) {
    $session_data = !empty($row['session_data']) ? json_decode($row['session_data'], true) : [];
    $people = [
      'user_id' => (int) $row['actor_id'],
      'onchannel' => isset($session_data['onchannel']) ? (int) $session_data['onchannel'] : 0,
      'isadmin' => isset($session_data['isadmin']) ? (int) $session_data['isadmin'] : 0,
    ];
  }
}
$myid = $people ? (int) $people['user_id'] : 0;
$channel = $people ? (int) $people['onchannel'] : 0;
$isadminsetting = $people ? (int) $people['isadmin'] : 0;

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
 
?>
