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
// This file has been migrated from legacy/craftysyntax/admin_actions.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_OPERATOR_CONSOLE_DOCTRINE.md

require_once("admin_common.php");
validate_session($identity);


if(empty($UNTRUSTED['whattodo'])){  $UNTRUSTED['whattodo'] = ""; }
if(empty($UNTRUSTED['whattodochat'])){  $UNTRUSTED['whattodochat'] = ""; }
if(empty($UNTRUSTED['whattodo'])){ $UNTRUSTED['whattodo'] = $UNTRUSTED['whattodochat']; }
 
// get the info of this user.. 
// Updated to use Lupopedia table mapping: livehelp_users → lupo_users
$query = "SELECT * FROM lupo_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$show_arrival = $people['show_arrival']; 
$user_alert = $people['user_alert'];
$greeting = $people['greeting'];
$photo = $people['photo'];
if( (!(empty($photo))) && (!(empty($greeting))))
    $greeting ="<table><tr><td><img src=$photo></td><td>$greeting</td></tr></table>";
$timeof = rightnowtime();

?>
