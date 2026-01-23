<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2025 Eric Gerdes   (https://lupopedia.com )
// ---------------------------------------------------------------------------
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
require_once("admin_common.php");
validate_session($identity);

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
$bgcolor = "";

// Get current livehelp_id (default to 1)
$current_livehelp_id = 1;
if(!(empty($people['livehelp_id']))) {
  $current_livehelp_id = intval($people['livehelp_id']);
}

if(!(isset($UNTRUSTED['action']))){ $UNTRUSTED['action'] = ""; }
if(!(isset($UNTRUSTED['addmenu']))){ $UNTRUSTED['addmenu'] = ""; }
if(!(isset($UNTRUSTED['editit']))){ $UNTRUSTED['editit'] = ""; }
if(!(isset($UNTRUSTED['remove']))){ $UNTRUSTED['remove'] = ""; }
if(!(isset($UNTRUSTED['channel_id']))){ $UNTRUSTED['channel_id'] = ""; }
if(!(isset($UNTRUSTED['channel_name']))){ $UNTRUSTED['channel_name'] = ""; }
if(!(isset($UNTRUSTED['channel_type']))){ $UNTRUSTED['channel_type'] = "ai_chat"; }
if(!(isset($UNTRUSTED['status']))){ $UNTRUSTED['status'] = "active"; }
if(!(isset($UNTRUSTED['agent_id']))){ $UNTRUSTED['agent_id'] = ""; }
if(!(isset($UNTRUSTED['agent_name']))){ $UNTRUSTED['agent_name'] = ""; }
if(!(isset($UNTRUSTED['metadata']))){ $UNTRUSTED['metadata'] = ""; }

// Check admin permissions
if($isadminsetting != "Y"){
  print "<font color=990000>Admin access required.</font>";
  if(!($serversession))
    $mydatabase->close_connect();
  exit;	
}

// Check if livehelp_channels table exists, if not create it
$query = "SHOW TABLES LIKE 'livehelp_channels'";
$table_check = $mydatabase->query($query);
if($table_check->numrows() == 0){
  // Create table if it doesn't exist
  $create_table = "CREATE TABLE IF NOT EXISTS `livehelp_channels` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
    `channel_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Channel ID (000-999)',
    `channel_name` VARCHAR(255) DEFAULT NULL,
    `channel_type` ENUM('ai_chat', 'user_to_user', 'group', 'support', 'agent_coordination') DEFAULT 'ai_chat',
    `status` ENUM('active', 'ended', 'paused') DEFAULT 'active',
    `agent_id` BIGINT(20) UNSIGNED DEFAULT NULL,
    `agent_name` VARCHAR(255) DEFAULT NULL,
    `started_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ended_at` DATETIME DEFAULT NULL,
    `last_message_at` DATETIME DEFAULT NULL,
    `participant_count` BIGINT(20) UNSIGNED DEFAULT 0,
    `message_count` BIGINT(20) UNSIGNED DEFAULT 0,
    `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_channel_instance` (`livehelp_id`, `channel_id`),
    KEY `livehelp_id` (`livehelp_id`),
    KEY `channel_id` (`channel_id`),
    KEY `agent_id` (`agent_id`),
    KEY `status` (`status`),
    KEY `idx_livehelp_channel` (`livehelp_id`, `channel_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
  $mydatabase->query($create_table);
}

// Handle delete (soft delete)
if(!(empty($UNTRUSTED['remove']))){
  $query = "UPDATE livehelp_channels SET deleted_at=NOW() WHERE id=". intval($UNTRUSTED['remove']) . " AND livehelp_id=" . $current_livehelp_id;	
  $mydatabase->query($query);
}

// Handle create new channel
if($UNTRUSTED['action'] == "addnew"){
  // Validate channel_id (000-999)
  $channel_id = intval($UNTRUSTED['channel_id']);
  if($channel_id < 0 || $channel_id > 999){
    print "<font color=990000>Channel ID must be between 000 and 999.</font>";
    exit;
  }
  
  // Check if channel_id already exists for this instance
  $query = "SELECT * FROM livehelp_channels WHERE livehelp_id=" . $current_livehelp_id . " AND channel_id=" . $channel_id . " AND deleted_at IS NULL";
  $data = $mydatabase->query($query);
  if($data->numrows() != 0){
    print "<font color=990000>Channel ID " . str_pad($channel_id, 3, '0', STR_PAD_LEFT) . " already exists for this instance.</font>";
    exit;
  }
  
  // Validate JSON metadata
  $metadata_json = "NULL";
  if(!(empty($UNTRUSTED['metadata']))){
    $metadata_decoded = json_decode($UNTRUSTED['metadata'], true);
    if(json_last_error() === JSON_ERROR_NONE){
      $metadata_json = "'" . filter_sql(json_encode($metadata_decoded)) . "'";
    }
  }
  
  $agent_id_sql = "NULL";
  if(!(empty($UNTRUSTED['agent_id']))){
    $agent_id_sql = intval($UNTRUSTED['agent_id']);
  }
  
  $query = "INSERT INTO livehelp_channels (livehelp_id, channel_id, channel_name, channel_type, status, agent_id, agent_name, metadata)
            VALUES (" . $current_livehelp_id . ", " . $channel_id . ", '" . filter_sql($UNTRUSTED['channel_name']) . "', 
                    '" . filter_sql($UNTRUSTED['channel_type']) . "', '" . filter_sql($UNTRUSTED['status']) . "', 
                    " . $agent_id_sql . ", '" . filter_sql($UNTRUSTED['agent_name']) . "', " . $metadata_json . ")";
  $mydatabase->query($query);
}

// Handle update channel
if($UNTRUSTED['action'] == "updatechannel"){
  $channel_id = intval($UNTRUSTED['channel_id']);
  if($channel_id < 0 || $channel_id > 999){
    print "<font color=990000>Channel ID must be between 000 and 999.</font>";
    exit;
  }
  
  // Validate JSON metadata
  $metadata_sql = "";
  if(!(empty($UNTRUSTED['metadata']))){
    $metadata_decoded = json_decode($UNTRUSTED['metadata'], true);
    if(json_last_error() === JSON_ERROR_NONE){
      $metadata_sql = " metadata='" . filter_sql(json_encode($metadata_decoded)) . "',";
    }
  } else {
    $metadata_sql = " metadata=NULL,";
  }
  
  $agent_id_sql = "";
  if(!(empty($UNTRUSTED['agent_id']))){
    $agent_id_sql = " agent_id=" . intval($UNTRUSTED['agent_id']) . ",";
  } else {
    $agent_id_sql = " agent_id=NULL,";
  }
  
  $query = "UPDATE livehelp_channels 
            SET channel_id=" . $channel_id . ",
                channel_name='" . filter_sql($UNTRUSTED['channel_name']) . "',
                channel_type='" . filter_sql($UNTRUSTED['channel_type']) . "',
                status='" . filter_sql($UNTRUSTED['status']) . "'," .
                $agent_id_sql .
                " agent_name='" . filter_sql($UNTRUSTED['agent_name']) . "'," .
                $metadata_sql .
                " updated_at=NOW()
            WHERE id=" . intval($UNTRUSTED['who']) . " AND livehelp_id=" . $current_livehelp_id;
  $mydatabase->query($query);
  
  if(!($serversession))
    $mydatabase->close_connect();
  sleep(1);
  header("Location: channels.php",TRUE,307);
  exit;
}
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?>><center>
<h2>Channel Management</h2>
<p><i>Channels are numbered radio frequencies (000-999) for the LUPOPEDIA ecosystem</i></p>
<?php if ( ($UNTRUSTED['addmenu'] != "Y" ) && ($UNTRUSTED['editit'] == "") ){ ?>
<table border=0 width=100%>
<tr bgcolor=FFFFFF>
  <td><b>Channel ID</b></td>
  <td><b>Channel Name</b></td>
  <td><b>Type</b></td>
  <td><b>Status</b></td>
  <td><b>Agent ID</b></td>
  <td><b>Agent Name</b></td>
  <td><b>Participants</b></td>
  <td><b>Messages</b></td>
  <td><b>Options</b></td>
</tr>
<?php 
$query = "SELECT * FROM livehelp_channels WHERE livehelp_id=" . $current_livehelp_id . " AND deleted_at IS NULL ORDER BY channel_id ASC";
$data = $mydatabase->query($query);
while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
   if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
   
   print "<tr bgcolor=$bgcolor>";
   print "<td>" . str_pad($row['channel_id'], 3, '0', STR_PAD_LEFT) . "</td>";
   print "<td>" . htmlspecialchars($row['channel_name']) . "</td>";
   print "<td>" . htmlspecialchars($row['channel_type']) . "</td>";
   print "<td>" . htmlspecialchars($row['status']) . "</td>";
   if(!(empty($row['agent_id']))){
     print "<td>" . str_pad($row['agent_id'], 3, '0', STR_PAD_LEFT) . "</td>";
   } else {
     print "<td>-</td>";
   }
   print "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
   print "<td>" . intval($row['participant_count']) . "</td>";
   print "<td>" . intval($row['message_count']) . "</td>";
   print "<td>";
   print "<a href=channels.php?editit=" . $row['id'] . ">Edit</a> ";
   print "<a href=channels.php?remove=" . $row['id'] . " onClick=\"return confirm('Are you sure you want to delete this channel?')\"><font color=990000>Remove</font></a>";
   print "</td></tr>\n"; 	
}
?>
</table>
<?php 
  print "<br><a href=channels.php?addmenu=Y><font size=+1>Create New Channel</font></a>";
}
if( ($UNTRUSTED['addmenu'] == "Y" ) || (!(empty($UNTRUSTED['editit']))) ){ 
?>
<br><table width=800><tr><td>
<form action=channels.php name=channelform method=post>
<?php 
if(!(empty($UNTRUSTED['editit']))){
 $query = "SELECT * FROM livehelp_channels WHERE id=". intval($UNTRUSTED['editit']) . " AND livehelp_id=" . $current_livehelp_id . " AND deleted_at IS NULL";
 $data = $mydatabase->query($query);
 $channelinfo = $data->fetchRow(DB_FETCHMODE_ASSOC);	
 if(empty($channelinfo)){
   print "<font color=990000>Channel not found.</font>";
   exit;
 }
?>
<input type=hidden name=action value=updatechannel>
<input type=hidden name=who value=<?php echo intval($UNTRUSTED['editit']); ?> >
<table bgcolor=<?php echo $color_alt1;?>>
<tr><td bgcolor=FFFFFF colspan=2>Update Channel</td></tr>
<?php } else { ?>
<input type=hidden name=action value=addnew>
<table bgcolor=<?php echo $color_alt1;?>>
<tr><td bgcolor=FFFFFF colspan=2>Create New Channel</td></tr>
<?php 
$channelinfo['channel_id'] = "";
$channelinfo['channel_name'] = "";
$channelinfo['channel_type'] = "ai_chat";
$channelinfo['status'] = "active";
$channelinfo['agent_id'] = "";
$channelinfo['agent_name'] = "";
$channelinfo['metadata'] = "";
}
?>
<tr><td>Channel ID (000-999):</td><td><input type=text name=channel_id size=10 value="<?php echo htmlspecialchars($channelinfo['channel_id']); ?>" required> <i>Three-digit channel number</i></td></tr>
<tr><td>Channel Name:</td><td><input type=text name=channel_name size=50 value="<?php echo htmlspecialchars($channelinfo['channel_name']); ?>"></td></tr>
<tr><td>Channel Type:</td><td>
<select name=channel_type>
  <option value="ai_chat" <?php if($channelinfo['channel_type'] == "ai_chat") echo "SELECTED"; ?>>AI Chat</option>
  <option value="user_to_user" <?php if($channelinfo['channel_type'] == "user_to_user") echo "SELECTED"; ?>>User to User</option>
  <option value="group" <?php if($channelinfo['channel_type'] == "group") echo "SELECTED"; ?>>Group</option>
  <option value="support" <?php if($channelinfo['channel_type'] == "support") echo "SELECTED"; ?>>Support</option>
  <option value="agent_coordination" <?php if($channelinfo['channel_type'] == "agent_coordination") echo "SELECTED"; ?>>Agent Coordination</option>
</select>
</td></tr>
<tr><td>Status:</td><td>
<select name=status>
  <option value="active" <?php if($channelinfo['status'] == "active") echo "SELECTED"; ?>>Active</option>
  <option value="ended" <?php if($channelinfo['status'] == "ended") echo "SELECTED"; ?>>Ended</option>
  <option value="paused" <?php if($channelinfo['status'] == "paused") echo "SELECTED"; ?>>Paused</option>
</select>
</td></tr>
<tr><td>Agent ID (optional):</td><td><input type=text name=agent_id size=10 value="<?php echo htmlspecialchars($channelinfo['agent_id']); ?>"> <i>Primary agent on this channel</i></td></tr>
<tr><td>Agent Name (optional):</td><td><input type=text name=agent_name size=50 value="<?php echo htmlspecialchars($channelinfo['agent_name']); ?>"></td></tr>
<tr><td>Metadata (JSON):</td><td><textarea name=metadata rows=5 cols=60><?php 
  if(!(empty($channelinfo['metadata']))){
    $metadata_decoded = json_decode($channelinfo['metadata'], true);
    if($metadata_decoded !== null){
      echo htmlspecialchars(json_encode($metadata_decoded, JSON_PRETTY_PRINT));
    } else {
      echo htmlspecialchars($channelinfo['metadata']);
    }
  }
?></textarea></td></tr>
<tr><td colspan=2><input type=submit value="Save Channel"></td></tr>
</table>
</form>
</td></tr></table>
<?php } ?>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>

