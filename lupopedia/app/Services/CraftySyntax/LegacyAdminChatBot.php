<?php
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
// This file has been migrated from legacy/craftysyntax/admin_chat_bot.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_OPERATOR_CONSOLE_DOCTRINE.md

require_once("admin_common.php");
validate_session($identity);
  
// get the info of this user.. 
// Updated to use Lupopedia table mapping: livehelp_users → lupo_users
$query = "SELECT user_id,onchannel,showtype,externalchats,chattype,username FROM lupo_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ORDERED);
$myid = $people[0];
$channel = $people[1];
$defaultshowtype = $people[2];
$externalchats= $people[3];
$external = false;
$chattype = $people[4];
$username = $people[5];
 
if($chattype!="xmlhttp"){
	 $chattype = "xmlhttp"; 
}

$previewsetting1 = "";
$previewsetting2 = "";
$previewsetting3 = "";

// TOON Analytics: Log admin chat bot access with world context
require_once("WorldGraphHelper.php");

$operator_id = resolve_actor_from_lupo_user($myid);
$session_id = get_current_session_id();
$tab_id = get_current_tab_id();

// Resolve world context for admin chat bot
$world_context = WorldGraphHelper::resolve_world_from_console_context($myid);
if (empty($world_context) && !empty($channel)) {
    $world_context = WorldGraphHelper::resolve_world_from_channel($channel);
}
$world_id = $world_context ? $world_context['world_id'] : null;
$world_key = $world_context ? $world_context['world_key'] : null;
$world_type = $world_context ? $world_context['world_type'] : null;

log_toon_event('admin_chat_bot_access', [
    'operator_id' => $operator_id,
    'channel' => $channel,
    'defaultshowtype' => $defaultshowtype,
    'externalchats' => $externalchats,
    'chattype' => $chattype,
    'username' => $username,
    'event_context' => 'admin_chat_bot',
    'world_context' => $world_context
], $operator_id, $session_id, $tab_id, $world_id, $world_key, $world_type);

?>
