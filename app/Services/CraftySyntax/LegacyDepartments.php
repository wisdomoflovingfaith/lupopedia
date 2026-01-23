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
// This file has been migrated from legacy/craftysyntax/departments.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_CHAT_ENGINE_DOCTRINE.md

require_once("admin_common.php");
require_once("department_function.php");
validate_session($identity);
// get the info of this user.. 
// Updated to use Lupopedia table mapping: livehelp_users → lupo_users
$sqlquery = "SELECT user_id,onchannel,show_arrival,user_alert,isadmin FROM lupo_users WHERE sessionid='".$identity['SESSIONID']."'";	
$res = $mydatabase->query($sqlquery);
$row = $res->fetchRow(DB_FETCHMODE_ORDERED);
$myid = $row[0];
$channel = $row[1];
$show_arrival = $row[2]; 
$user_alert = $row[3];
$isadminsetting = $row[4];
if(!(isset($UNTRUSTED['createnew']))) { $UNTRUSTED['createnew'] = ""; }
if(!(isset($UNTRUSTED['createit']))) { $UNTRUSTED['createit'] = ""; }
if(!(isset($UNTRUSTED['removeit']))) { $UNTRUSTED['removeit'] = ""; }
if(!(isset($UNTRUSTED['updateit']))) { $UNTRUSTED['updateit'] = 0; } 
if(!(isset($UNTRUSTED['edit']))) { $UNTRUSTED['edit'] = ""; }
if(!(isset($UNTRUSTED['html']))) { $UNTRUSTED['html'] = ""; }
if(!(isset($UNTRUSTED['help']))) { $UNTRUSTED['help'] = ""; }
if(!(isset($UNTRUSTED['whattodo']))){ $UNTRUSTED['whattodo'] = ""; }
if( ($isadminsetting != "Y") &&  ($isadminsetting != "N") ){
 print $lang['txt41'];

?>
