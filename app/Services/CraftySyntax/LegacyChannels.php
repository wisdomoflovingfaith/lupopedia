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

// LEGACY PRESERVATION NOTICE:
// This file has been migrated from legacy/craftysyntax/channels.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_CHAT_ENGINE_DOCTRINE.md

require_once("admin_common.php");
validate_session($identity);

// Operator/visitor row from {prefix}sessions (per migration: livehelp_users → lupo_auth_users; session in lupo_sessions)
$people = crafty_get_session_people($identity['SESSIONID']);
$myid = $people ? (int) $people['user_id'] : 0;
$channel = $people ? (int) $people['onchannel'] : 0;
$isadminsetting = $people ? (int) $people['isadmin'] : 0;
$lastaction = date("Ymdhis");
$startdate = date("Ymd");
$bgcolor = "";
$current_livehelp_id = 1;

if(!(isset($UNTRUSTED['action']))){ $UNTRUSTED['action'] = ""; }
if(!(isset($UNTRUSTED['addmenu']))){ $UNTRUSTED['addmenu'] = ""; }

?>
