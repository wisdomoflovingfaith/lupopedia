<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Admin Chat Flush          ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Admin Chat Flush - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once("admin_common.php");

validate_session($identity);

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
if ($people) {
  $UNTRUSTED['myid'] = $people['user_id'];
  $operator_id = $UNTRUSTED['myid'];
  $channel = 0;
} else {
  $people = null;
}  
   
$jsrn = get_jsrn($identity);

if(!(isset($UNTRUSTED['offset']))){ $UNTRUSTED['offset'] = ""; }
if(!(isset($UNTRUSTED['clear']))){ $UNTRUSTED['clear'] = ""; }
if(!(isset($UNTRUSTED['cleartonow']))){ $UNTRUSTED['cleartonow'] = ""; }
if(!(isset($UNTRUSTED['channel']))){ $UNTRUSTED['channel'] = 0; }
if(!(isset($UNTRUSTED['myid']))){ 
	 $UNTRUSTED['myid'] = 0; 
} else {
	 $myid = intval($UNTRUSTED['myid']);
}
if(!(isset($UNTRUSTED['see']))){ 
	 $UNTRUSTED['see'] = 0; 
} else {
  $see = intval($UNTRUSTED['see']);	
}
if(!(isset($UNTRUSTED['starttimeof']))){ $UNTRUSTED['starttimeof'] = 0; }

if(!(empty($UNTRUSTED['setchattype'])){
 // Session state in lupo_sessions.session_data; no chattype column - no-op or extend session_data if needed
}

$timeof = date("YmdHis");
$timeof_load = $timeof;
$prev = mktime ( date("H"), date("i")-30, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);

if($UNTRUSTED['offset'] != ""){ $timeof = $oldtime; }

if (($UNTRUSTED['clear'] == 'now') || ($UNTRUSTED['cleartonow'] == 1)) {
  $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
  $messages_table = $table_prefix . 'dialog_messages';
  if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
    $message = $mydatabase->fetchRow(
      "SELECT created_ymdhis AS timeof FROM {$messages_table} WHERE is_deleted = 0 ORDER BY created_ymdhis DESC LIMIT 1"
    );
    if ($message && isset($message['timeof'])) {
      $timeof = $message['timeof'] - 2;
      $offset = $message['timeof'] - 2;
      $starttimeof = $message['timeof'] - 2;
    }
  }
} 
if(isset($starttimeof)){
  $timeof = $starttimeof;
}
  
//turn off max execution timeout if not refreshing..
// unset max execution time
@ini_set("max_execution_time",0);

?>
