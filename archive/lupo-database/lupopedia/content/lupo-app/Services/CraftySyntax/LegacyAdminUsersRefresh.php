<?php 
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// --------------------------------------------------------------------------
// $                                                                                              $
// $    Please check https://lupopedia.com/ or REGISTER your program for updates  $
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
// --------------------------------------------------------------------------
// FILE NOTES:
//     This file controls the list of active users on the site. 
//===========================================================================

// LEGACY PRESERVATION NOTICE:
// This file has been migrated from legacy/craftysyntax/admin_users_refresh.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_OPERATOR_CONSOLE_DOCTRINE.md

require_once("admin_common.php");
validate_session($identity);
 
$timeof = rightnowtime();
$insitewav = "";
$soundwav = "";
//
// Start initial var setup
//
if(empty($UNTRUSTED['autoans'])){ $UNTRUSTED['autoans'] = ""; }
if(empty($UNTRUSTED['autoanswer'])){ $UNTRUSTED['autoanswer'] = "N"; }
if(empty($UNTRUSTED['action'])){ $UNTRUSTED['action'] = ""; }
if(empty($UNTRUSTED['clearchannel'])){ $UNTRUSTED['clearchannel'] = "N"; }
// comma deliminated list of people to ignore chat requests for..
if(empty($UNTRUSTED['ignorelist'])){ $UNTRUSTED['ignorelist'] = "0"; }
$UNTRUSTED['autoanswer'] = ($UNTRUSTED['autoanswer'] === "Y") ? "Y" : "N";
$UNTRUSTED['showvisitors'] = intval($UNTRUSTED['showvisitors']);
$UNTRUSTED['ignorelist'] = preg_replace('/[^0-9,]/', '', $UNTRUSTED['ignorelist']);

// TOON Analytics: Log operator console session start with world context
require_once("WorldGraphHelper.php");

$operator_id = resolve_actor_from_lupo_user($identity['USERID']);
$session_id = get_current_session_id();
$tab_id = get_current_tab_id();

// Resolve world context for operator console
$world_context = WorldGraphHelper::resolve_world_from_console_context($identity['USERID']);
$world_id = $world_context ? $world_context['world_id'] : null;
$world_key = $world_context ? $world_context['world_key'] : null;
$world_type = $world_context ? $world_context['world_type'] : null;

log_toon_event('operator_console_session_start', [
    'operator_id' => $operator_id,
    'session_id' => $session_id,
    'tab_id' => $tab_id,
    'event_context' => 'admin_users_refresh',
    'autoans' => $UNTRUSTED['autoans'],
    'autoanswer' => $UNTRUSTED['autoanswer'],
    'action' => $UNTRUSTED['action'],
    'clearchannel' => $UNTRUSTED['clearchannel'],
    'ignorelist' => $UNTRUSTED['ignorelist'],
    'showvisitors' => $UNTRUSTED['showvisitors'],
    'world_context' => $world_context
], $operator_id, $session_id, $tab_id, $world_id, $world_key, $world_type);

// Existing legacy code continues below
// ...
