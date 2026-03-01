<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX User Chat Flush           ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// User Chat Flush - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once("visitor_common.php");

if($serversession){
  $autostart = @ini_get('session.auto_start');
  if($autostart!=0){		
     session_write_close();
  }
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sessions_table = $table_prefix . 'sessions';
if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
  $people = $mydatabase->fetchRow(
    "SELECT session_id AS sessionid, actor_id AS user_id, last_seen_ymdhis AS lastaction FROM {$sessions_table} WHERE session_id = :sid",
    ['sid' => $identity['SESSIONID']]
  );
} else {
  $people = null;
}
$myid = $people ? (int) $people['user_id'] : 0;

// get a channel for this user:
$onchannel = createchannel($myid);

if(empty($UNTRUSTED['offset'])){ $UNTRUSTED['offset'] = 0; } else { $UNTRUSTED['offset'] = intval($UNTRUSTED['offset']); }
if(empty($UNTRUSTED['department'])){ $department = 0; } else { $department = intval($UNTRUSTED['department']); }
if(empty($UNTRUSTED['printit'])){ $UNTRUSTED['printit'] = ""; } 
if(empty($UNTRUSTED['timeof'])){ $UNTRUSTED['timeof'] = 0; } 

?>
