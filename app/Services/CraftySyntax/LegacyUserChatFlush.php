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

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];

// get a channel for this user:
$onchannel = createchannel($myid);

if(empty($UNTRUSTED['offset'])){ $UNTRUSTED['offset'] = 0; } else { $UNTRUSTED['offset'] = intval($UNTRUSTED['offset']); }
if(empty($UNTRUSTED['department'])){ $department = 0; } else { $department = intval($UNTRUSTED['department']); }
if(empty($UNTRUSTED['printit'])){ $UNTRUSTED['printit'] = ""; } 
if(empty($UNTRUSTED['timeof'])){ $UNTRUSTED['timeof'] = 0; } 

?>
