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
if(!(isset($UNTRUSTED['agent_id']))){ $UNTRUSTED['agent_id'] = ""; }
if(!(isset($UNTRUSTED['agent_name']))){ $UNTRUSTED['agent_name'] = ""; }
if(!(isset($UNTRUSTED['channel_id']))){ $UNTRUSTED['channel_id'] = ""; }
if(!(isset($UNTRUSTED['agent_type']))){ $UNTRUSTED['agent_type'] = "primary"; }
if(!(isset($UNTRUSTED['status']))){ $UNTRUSTED['status'] = "active"; }
if(!(isset($UNTRUSTED['dna_string']))){ $UNTRUSTED['dna_string'] = ""; }
if(!(isset($UNTRUSTED['capabilities']))){ $UNTRUSTED['capabilities'] = ""; }
if(!(isset($UNTRUSTED['metadata']))){ $UNTRUSTED['metadata'] = ""; }

// Check admin permissions
if($isadminsetting != "Y"){
  print "<font color=990000>Admin access required.</font>";
  if(!($serversession))
    $mydatabase->close_connect();
  exit;	
}

// Handle delete
if(!(empty($UNTRUSTED['remove']))){
  $query = "UPDATE livehelp_agents SET deleted_at=NOW() WHERE id=". intval($UNTRUSTED['remove']) . " AND livehelp_id=" . $current_livehelp_id;	
  $mydatabase->query($query);
}

// Handle create new agent
if($UNTRUSTED['action'] == "addnew"){
  // Validate agent_id (000-999)
  $agent_id = intval($UNTRUSTED['agent_id']);
  if($agent_id < 0 || $agent_id > 999){
    print "<font color=990000>Agent ID must be between 000 and 999.</font>";
    exit;
  }
  
  // Check if agent_id already exists for this instance
  $query = "SELECT * FROM livehelp_agents WHERE livehelp_id=" . $current_livehelp_id . " AND agent_id=" . $agent_id . " AND deleted_at IS NULL";
  $data = $mydatabase->query($query);
  if($data->numrows() != 0){
    print "<font color=990000>Agent ID " . $agent_id . " already exists for this instance.</font>";
    exit;
  }
  
  // Channel ID defaults to agent_id (direct mapping)
  $channel_id = intval($UNTRUSTED['channel_id']);
  if(empty($channel_id)){
    $channel_id = $agent_id;
  }
  
  // Validate JSON fields
  $capabilities_json = "NULL";
  if(!(empty($UNTRUSTED['capabilities']))){
    $capabilities_decoded = json_decode($UNTRUSTED['capabilities'], true);
    if(json_last_error() === JSON_ERROR_NONE){
      $capabilities_json = "'" . filter_sql(json_encode($capabilities_decoded)) . "'";
    }
  }
  
  $metadata_json = "NULL";
  if(!(empty($UNTRUSTED['metadata']))){
    $metadata_decoded = json_decode($UNTRUSTED['metadata'], true);
    if(json_last_error() === JSON_ERROR_NONE){
      $metadata_json = "'" . filter_sql(json_encode($metadata_decoded)) . "'";
    }
  }
  
  $query = "INSERT INTO livehelp_agents (livehelp_id, agent_id, agent_name, channel_id, agent_type, status, dna_string, capabilities, metadata)
            VALUES (" . $current_livehelp_id . ", " . $agent_id . ", '" . filter_sql($UNTRUSTED['agent_name']) . "', " . $channel_id . ", 
                    '" . filter_sql($UNTRUSTED['agent_type']) . "', '" . filter_sql($UNTRUSTED['status']) . "', 
                    '" . filter_sql($UNTRUSTED['dna_string']) . "', " . $capabilities_json . ", " . $metadata_json . ")";
  $mydatabase->query($query);
}

// Handle update agent
if($UNTRUSTED['action'] == "updateagent"){
  $agent_id = intval($UNTRUSTED['agent_id']);
  if($agent_id < 0 || $agent_id > 999){
    print "<font color=990000>Agent ID must be between 000 and 999.</font>";
    exit;
  }
  
  $channel_id = intval($UNTRUSTED['channel_id']);
  if(empty($channel_id)){
    $channel_id = $agent_id;
  }
  
  // Validate JSON fields
  $capabilities_sql = "";
  if(!(empty($UNTRUSTED['capabilities']))){
    $capabilities_decoded = json_decode($UNTRUSTED['capabilities'], true);
    if(json_last_error() === JSON_ERROR_NONE){
      $capabilities_sql = " capabilities='" . filter_sql(json_encode($capabilities_decoded)) . "',";
    }
  } else {
    $capabilities_sql = " capabilities=NULL,";
  }
  
  $metadata_sql = "";
  if(!(empty($UNTRUSTED['metadata']))){
    $metadata_decoded = json_decode($UNTRUSTED['metadata'], true);
    if(json_last_error() === JSON_ERROR_NONE){
      $metadata_sql = " metadata='" . filter_sql(json_encode($metadata_decoded)) . "',";
    }
  } else {
    $metadata_sql = " metadata=NULL,";
  }
  
  $query = "UPDATE livehelp_agents 
            SET agent_id=" . $agent_id . ",
                agent_name='" . filter_sql($UNTRUSTED['agent_name']) . "',
                channel_id=" . $channel_id . ",
                agent_type='" . filter_sql($UNTRUSTED['agent_type']) . "',
                status='" . filter_sql($UNTRUSTED['status']) . "',
                dna_string='" . filter_sql($UNTRUSTED['dna_string']) . "'," .
                $capabilities_sql .
                $metadata_sql .
                " updated_at=NOW()
            WHERE id=" . intval($UNTRUSTED['who']) . " AND livehelp_id=" . $current_livehelp_id;
  $mydatabase->query($query);
  
  if(!($serversession))
    $mydatabase->close_connect();
  sleep(1);
  header("Location: agents.php",TRUE,307);
  exit;
}
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?>><center>
<?php if ( ($UNTRUSTED['addmenu'] != "Y" ) && ($UNTRUSTED['editit'] == "") ){ ?>
<table border=0 width=100%>
<tr bgcolor=FFFFFF>
  <td><b>Agent ID</b></td>
  <td><b>Agent Name</b></td>
  <td><b>Channel ID</b></td>
  <td><b>Type</b></td>
  <td><b>Status</b></td>
  <td><b>DNA String</b></td>
  <td><b>Options</b></td>
</tr>
<?php 
$query = "SELECT * FROM livehelp_agents WHERE livehelp_id=" . $current_livehelp_id . " AND deleted_at IS NULL ORDER BY agent_id ASC";
$data = $mydatabase->query($query);
while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
   if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
   $dna_display = htmlspecialchars(substr($row['dna_string'], 0, 30));
   if(strlen($row['dna_string']) > 30) $dna_display .= "...";
   
   print "<tr bgcolor=$bgcolor>";
   print "<td>" . str_pad($row['agent_id'], 3, '0', STR_PAD_LEFT) . "</td>";
   print "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
   print "<td>" . str_pad($row['channel_id'], 3, '0', STR_PAD_LEFT) . "</td>";
   print "<td>" . htmlspecialchars($row['agent_type']) . "</td>";
   print "<td>" . htmlspecialchars($row['status']) . "</td>";
   print "<td>" . $dna_display . "</td>";
   print "<td>";
   print "<a href=agents.php?editit=" . $row['id'] . ">Edit</a> ";
   print "<a href=agents.php?remove=" . $row['id'] . " onClick=\"return confirm('Are you sure you want to delete this agent?')\"><font color=990000>Remove</font></a>";
   print "</td></tr>\n"; 	
}
?>
</table>
<?php 
  print "<br><a href=agents.php?addmenu=Y><font size=+1>Create New Agent</font></a>";
}
if( ($UNTRUSTED['addmenu'] == "Y" ) || (!(empty($UNTRUSTED['editit']))) ){ 
?>
<br><table width=800><tr><td>
<form action=agents.php name=agentform method=post>
<?php 
if(!(empty($UNTRUSTED['editit']))){
 $query = "SELECT * FROM livehelp_agents WHERE id=". intval($UNTRUSTED['editit']) . " AND livehelp_id=" . $current_livehelp_id . " AND deleted_at IS NULL";
 $data = $mydatabase->query($query);
 $agentinfo = $data->fetchRow(DB_FETCHMODE_ASSOC);	
 if(empty($agentinfo)){
   print "<font color=990000>Agent not found.</font>";
   exit;
 }
?>
<input type=hidden name=action value=updateagent>
<input type=hidden name=who value=<?php echo intval($UNTRUSTED['editit']); ?> >
<table bgcolor=<?php echo $color_alt1;?>>
<tr><td bgcolor=FFFFFF colspan=2>Update Agent</td></tr>
<?php } else { ?>
<input type=hidden name=action value=addnew>
<table bgcolor=<?php echo $color_alt1;?>>
<tr><td bgcolor=FFFFFF colspan=2>Create New Agent</td></tr>
<?php 
$agentinfo['agent_id'] = "";
$agentinfo['agent_name'] = "";
$agentinfo['channel_id'] = "";
$agentinfo['agent_type'] = "primary";
$agentinfo['status'] = "active";
$agentinfo['dna_string'] = "";
$agentinfo['capabilities'] = "";
$agentinfo['metadata'] = "";
}
?>
<tr><td>Agent ID (000-999):</td><td><input type=text name=agent_id size=10 value="<?php echo htmlspecialchars($agentinfo['agent_id']); ?>" required> <i>Direct mapping to channel number</i></td></tr>
<tr><td>Agent Name:</td><td><input type=text name=agent_name size=50 value="<?php echo htmlspecialchars($agentinfo['agent_name']); ?>" required></td></tr>
<tr><td>Channel ID (000-999):</td><td><input type=text name=channel_id size=10 value="<?php echo htmlspecialchars($agentinfo['channel_id']); ?>"> <i>Defaults to Agent ID if empty</i></td></tr>
<tr><td>Agent Type:</td><td>
<select name=agent_type>
  <option value="primary" <?php if($agentinfo['agent_type'] == "primary") echo "SELECTED"; ?>>Primary</option>
  <option value="secondary" <?php if($agentinfo['agent_type'] == "secondary") echo "SELECTED"; ?>>Secondary</option>
  <option value="coordinator" <?php if($agentinfo['agent_type'] == "coordinator") echo "SELECTED"; ?>>Coordinator</option>
  <option value="specialized" <?php if($agentinfo['agent_type'] == "specialized") echo "SELECTED"; ?>>Specialized</option>
</select>
</td></tr>
<tr><td>Status:</td><td>
<select name=status>
  <option value="active" <?php if($agentinfo['status'] == "active") echo "SELECTED"; ?>>Active</option>
  <option value="inactive" <?php if($agentinfo['status'] == "inactive") echo "SELECTED"; ?>>Inactive</option>
  <option value="maintenance" <?php if($agentinfo['status'] == "maintenance") echo "SELECTED"; ?>>Maintenance</option>
</select>
</td></tr>
<tr><td>DNA String:</td><td><input type=text name=dna_string size=80 value="<?php echo htmlspecialchars($agentinfo['dna_string']); ?>" placeholder="007-unknown-ATCG 001-unknown-TTAA"> <i>Format: channel-agent_name-DNA_bases</i></td></tr>
<tr><td>Capabilities (JSON):</td><td><textarea name=capabilities rows=5 cols=60><?php 
  if(!(empty($agentinfo['capabilities']))){
    $capabilities_decoded = json_decode($agentinfo['capabilities'], true);
    if($capabilities_decoded !== null){
      echo htmlspecialchars(json_encode($capabilities_decoded, JSON_PRETTY_PRINT));
    } else {
      echo htmlspecialchars($agentinfo['capabilities']);
    }
  }
?></textarea></td></tr>
<tr><td>Metadata (JSON):</td><td><textarea name=metadata rows=5 cols=60><?php 
  if(!(empty($agentinfo['metadata']))){
    $metadata_decoded = json_decode($agentinfo['metadata'], true);
    if($metadata_decoded !== null){
      echo htmlspecialchars(json_encode($metadata_decoded, JSON_PRETTY_PRINT));
    } else {
      echo htmlspecialchars($agentinfo['metadata']);
    }
  }
?></textarea></td></tr>
<tr><td colspan=2><input type=submit value="Save Agent"></td></tr>
</table>
</form>
</td></tr></table>
<?php } ?>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>

