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
require_once("data_functions.php");
require_once("ctabbox.php");
require_once("gc.php");

validate_session($identity);

if(empty($UNTRUSTED['tab'])) $UNTRUSTED['tab'] = 0;
if(empty($UNTRUSTED['whattodo'])) $UNTRUSTED['tab'] = "nothing";
 
if($_POST['whattodo']=="save"){
	$sql = "insert into livehelp_emails (fromemail,subject,notes,bodyof) values ('".$_POST['fromemail']."','".$_POST['subject']."','".$_POST['notes']."','".$_POST['bodyof']."')";
	$mydatabase->query($sql);
	?><script>window.location.replace('sendemail.php');</script><?php
}
 
?>
<h2>New Email message:</h2>
 
<form action=createemail.php method=post>
<table>
		<tr><td>internal Note on email type:</td><td><input type=text name=notes size=40></td></tr>
	 <tr><td>From email:</td><td><input type=text name=fromemail size=40></td></tr>
	 <tr><td>Subject line:</td><td><input type=text name=subject size=40></td></tr>
	 <tr><td colspan=2>Message body:<br><textarea cols=45 rows=10 name=bodyof></textarea> </td></tr>
	</table>
	<br>
	<input type=hidden name=whattodo value=save>
	<input type=submit value=CREATE>
</form>


<?php
if(!($serversession))
  $mydatabase->close_connect();

?>
<pre>





</pre>
<center>

<font size=-2>

<!-- Note if you remove this line you will be violating the license even if you have modified the program -->

<a href=https://lupopedia.com target=_blank>CRAFTY SYNTAX Live Help</a> 2003 - 2025 ( a product of Lupopedia LLC ) 

<br>

CRAFTY SYNTAX is  Software released 

under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>

</font>
</center>
