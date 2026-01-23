<?php 
//===========================================================================
//* --    ~~                CRAFTY SYNTAX CRM                       ~~    -- *
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
require_once("admin_common.php");

validate_session($identity);

if(!($serversession))
  $mydatabase->close_connect();

if(!(isset($UNTRUSTED['help']))){ $UNTRUSTED['help'] = ""; }
if(!(isset($UNTRUSTED['page']))){ $UNTRUSTED['page'] = "scratch.php"; }
if(!(isset($UNTRUSTED['tab']))){ $UNTRUSTED['tab'] = ""; }
if(!(empty($UNTRUSTED['alttab']))){ $alttab = "&tab=".$UNTRUSTED['alttab']; } else {  $alttab ="";  }

$page = "scratch.php";
if($UNTRUSTED['page']=="scratch.php"){ $page = "scratch.php"; }
if($UNTRUSTED['page']=="mastersettings.php"){ $page = "mastersettings.php"; }
if($UNTRUSTED['page']=="help.php"){ $page = "help.php"; }
if($UNTRUSTED['page']=="edit_layer.php"){ $page = "edit_layer.php"; }
if($UNTRUSTED['page']=="edit_smile.php"){ $page = "edit_smile.php"; }
if($UNTRUSTED['page']=="operators.php"){ $page = "operators.php"; }
if($UNTRUSTED['page']=="departments.php"){ $page = "departments.php"; }
if($UNTRUSTED['page']=="data.php"){ $page = "data.php"; }
if($UNTRUSTED['page']=="modules.php"){ $page = "modules.php"; }
if($UNTRUSTED['page']=="leads.php"){ $page = "leads.php"; }

?>
<title>CRAFTY SYNTAX CRM  ADMIN</title>
<frameset rows="52,*" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
 <frame src="admin_options.php?sound=off&tab=<?php echo intval($UNTRUSTED['tab']); ?>" name="topofit" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
 <frameset cols="220,*" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
 <frame src="navigation.php" name="navigation" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
<frame src="<?php echo $page; ?>?help=<?php echo htmlspecialchars($UNTRUSTED['help'], ENT_QUOTES, 'UTF-8') . htmlspecialchars($alttab, ENT_QUOTES, 'UTF-8'); ?>" name="contents" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE>
</frameset>
