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
// This file has been migrated from legacy/craftysyntax/user_chat_refresh.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_CHAT_ENGINE_DOCTRINE.md

require_once("visitor_common.php");
  
$people = crafty_get_session_people($identity['SESSIONID']);
$myid = $people ? (int) $people['user_id'] : 0;
$onchannel = createchannel($myid);

if (empty($UNTRUSTED['clear'])) { $clear = ""; } else { $clear = $UNTRUSTED['clear']; }
if (empty($UNTRUSTED['starttimeof'])) { $starttimeof = ""; } else { $starttimeof = $UNTRUSTED['starttimeof']; }
if (empty($UNTRUSTED['offset'])) { $offset = 2; } else { $offset = intval($UNTRUSTED['offset']); }
if (empty($UNTRUSTED['department'])) { $department = 0; } else { $department = intval($UNTRUSTED['department']); }
if (empty($UNTRUSTED['printit'])) { $printit = ""; } else { $printit = $UNTRUSTED['printit']; }
if (empty($UNTRUSTED['tab'])) { $tab = 0; } else { $tab = intval($UNTRUSTED['tab']); }
$message_test = date("YmdHis") - 1;

$department_row = null;
if ($department != 0 && isset($mydatabase) && $mydatabase instanceof PDO_DB) {
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $departments_table = $table_prefix . 'departments';
    $department_row = $mydatabase->fetchRow(
        "SELECT * FROM {$departments_table} WHERE department_id = :did AND (is_deleted = 0 OR is_deleted IS NULL)",
        ['did' => $department]
    );
}

?>
