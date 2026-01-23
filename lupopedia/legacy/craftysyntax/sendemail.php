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
 

 
?>
<h2>email message list:</h2>
 
<table border=1>
	<tr><td bgcolor=#FFFF00><b>subject</b></td><td bgcolor=#FFFF00><b>fromemail</b></td><td bgcolor=#FFFF00><b>notes</b></td><td>action:</td></tr>
	<?php
	 $bgcolor = "AAAAAA";
	
	  $sql = "select * FROM livehelp_emails order by subject";
 $data_questions = $mydatabase->query($sql);
 while($row_questions = $data_questions->fetchRow(DB_FETCHMODE_ASSOC)){ 
 	if($bgcolor == "AAAAAA"){ $bgcolor = "FFFFFF"; } else { $bgcolor = "AAAAAA";  }
  print "<tr bgcolor=#".$bgcolor."><td>".$row_questions['subject']."</td><td>".$row_questions['fromemail']."</td><td>".$row_questions['notes']."</td><td> <a href=send.php?messageid=".$row_questions['id'].">Send this email to selected.</a>   </td> </tr>";
  
}
 	
	?>
</table>	
<a href=createemail.php>Create a new email </a>
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
