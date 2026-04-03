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
require_once("admin_common.php");
validate_session($identity);
// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];
?>
<body bgcolor=<?php echo $color_background;?> marginheight=0 marginwidth=0 topmargin=0 leftmargin=0 bottommargin=0>
<center>
<SCRIPT type="text/javascript">
// onmouseovers
r_about = new Image;
h_about = new Image;
r_about.src = 'images/admin_arr.gif';
h_about.src = 'images/blank.gif';

function q_a(topic){
 url = 'https://lupopedia.com/lh/cslh.php?m=knowlegebase&a=index'
 window.open(url, 'chat540872', 'width=540,height=390,menubar=no,scrollbars=0,resizable=1');
}
</SCRIPT>
<table border=0 cellpadding=0 cellspacing=0 width=580 bgcolor=<?php echo $color_background;?>><tr><td>
Your Are Here: <b><a href=scratch.php>Overview Page</a> :: Help Page</b><br>
</td></tr></table>
<table border=0 cellpadding=0 cellspacing=0 width=580>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td height=1 bgcolor=<?php echo $color_alt2; ?>> <b>CRAFTY SYNTAX Help Page:</b></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td bgcolor=<?php echo $color_alt1;?> > <img src=images/blank.gif width=22 height=21 name=one5><a href="https://lupopedia.com/salessyntax_docs/howto.php" target=_blank>Getting Started.(How to setup and use CRAFTY SYNTAX)</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt1;?> > <img src=images/blank.gif width=22 height=21 name=one5><a href="https://lupopedia.com/salessyntax_docs/qa.php"  target=_blank>General Questions & Answers</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt1;?> > <img src=images/blank.gif width=22 height=21 name=one1><a href=https://lupopedia.com/support/ target=_blank>CRAFTY SYNTAX Support Pages</a></td></tr>		
<tr><td bgcolor=<?php echo $color_alt3;?> > <img src=images/blank.gif width=22 height=21 name=i2a><a href="https://lupopedia.com/salessyntax_docs/updates.php" target=_blank>News and Updates</a></td></tr>	
<tr><td bgcolor=<?php echo $color_alt1;?>><br></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
</table>
<br><br>
<font size=-2>
<a href=https://lupopedia.com/ target=_blank>CRAFTY SYNTAX Live Help</a> 2003 - 2025 ( a product of Lupopedia LLC ) 
CRAFTY SYNTAX is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> 
</font><br><br><br><br><br><br>
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>
