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
 
 
 // populate the leads datbase:
 $sql = "select email,deliminated,dateof from livehelp_leavemessage ";
 $data_questions = $mydatabase->query($sql);
 while($row_questions = $data_questions->fetchRow(DB_FETCHMODE_ASSOC)){ 
   $q = "select * from livehelp_leads where email='".$row_questions['email']."'	AND source='contact form'";
   $test = $mydatabase->query($q);
   
   if($test->numrows()==0){   	
   	$date = substr($row_questions['dateof'],0,8);   	
   	$insert = "INSERT INTO livehelp_leads (email,source,status,data,date_entered) values ('".$row_questions['email']."','contact form','NEW','".$row_questions['deliminated']."','$date')";
    $mydatabase->query($insert);
     
   }
 }
 
  $sql = "select email,who,transcript,endtime from livehelp_transcripts ";
 $data_questions = $mydatabase->query($sql);
 while($row_questions = $data_questions->fetchRow(DB_FETCHMODE_ASSOC)){ 
   $q = "select * from livehelp_leads where email='".$row_questions['email']."'	AND source='transcript'";
   $test = $mydatabase->query($q);
 
   if($test->numrows()==0){   	
   	$date = substr($row_questions['endtime'],0,8);   	
   	$insert = "INSERT INTO livehelp_leads (email,firstname,source,status,data,date_entered) values ('".$row_questions['email']."','".$row_questions['who']."','transcript','NEW','".$row_questions['transcript']."','$date')";
    $mydatabase->query($insert);
   }
 }
 /*
 $mydatabase_users = new MySQL_DB;
 $mydatabase_users->connectdb('localhost','otherdatabase','password');
 $mydatabase_users->selectdb('mymwqp_office');
 
   $sql = "select email,firstname,lastname,email,membertype,active,createdate from members ";
 $data_questions = $mydatabase_users->query($sql);
 while($row_questions = $data_questions->fetchRow(DB_FETCHMODE_ASSOC)){ 
   $q = "select * from livehelp_leads where email='".$row_questions['email']."'	AND source='user database'";
   $test = $mydatabase->query($q);
 
   if($test->numrows()==0){   	
   	$date = substr($row_questions['createdate'],0,8);   	
    $transcript = "Active:". $row_questions['active'] . " member type:". $row_questions['membertype'];
   	$insert = "INSERT INTO livehelp_leads (email,firstname,lastname,source,status,data,date_entered) values ('".$row_questions['email']."','".$row_questions['firstname']."','".$row_questions['lastname']."','user database','NEW','".$transcript."','$date')";
    $mydatabase->query($insert);
   }
 }
 */
 
 
?>
<h2>Leads and Clients Data:</h2>
<form action=leads.php method=post>
 show: <select name=status>
 	          <option value=NEW>NEW leads</option>
 	          <option value=exported>exported leads</option>
 	          <option value=emailed>emailed leads</option>
 	          <option value=closed>closed leads</option>
 	        </select> from the source of:
 	        <select name=source>
 	          <option value=ALL>ALL sources</option>
 	          <option value="user database">user database</option>
 	          <option value="transcript">transcript database </option>
 	          <option value="contact form">contact form database  </option>
	          <option value="proactive invite">proactive invite  </option>
 	        </select> 
 	        <input type=submit value=GO>
</form>
<table border=1>
	<tr><td bgcolor=#FFFF00><b>Client email</b></td><td bgcolor=#FFFF00><b>Client phone</b></td><td bgcolor=#FFFF00><b>Source</b></td><td bgcolor=#FFFF00><b>Lead status</b></td><td bgcolor=#FFFF00><b>First name</b></td><td bgcolor=#FFFF00><b>Last name</b></td><td bgcolor=#FFFF00><b>data:</b></td></tr>
	<?php
	 $bgcolor = "AAAAAA";
	
	  $sql = "select * FROM livehelp_leads order by date_entered DESC";
 $data_questions = $mydatabase->query($sql);
 while($row_questions = $data_questions->fetchRow(DB_FETCHMODE_ASSOC)){ 
 	if($bgcolor == "AAAAAA"){ $bgcolor = "FFFFFF"; } else { $bgcolor = "AAAAAA";  }
  print "<tr bgcolor=#".$bgcolor."><td>".$row_questions['email']."</td><td>".$row_questions['phone']."</td><td>".$row_questions['source']."</td><td>".$row_questions['status']."</td><td>".$row_questions['firstname']."</td><td>".$row_questions['lastname']."</td><td>".substr($row_questions['data'],0,255)."</td></tr>";
  
}
 	
	?>
</table>	
 With above data:
 <a href=sendemail.php>Send email to all above and mark as emailed leads</a>  |   <a href=export.php>Export all above into a csv file and mark as exported leads</a>
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
