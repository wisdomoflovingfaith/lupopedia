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
function arrayFromCSV($file, $hasFieldNames = false, $delimiter = ',', $enclosure='') {
    $result = Array();
    $size = filesize($file) +1;
    $file = fopen($file, 'r');
    #TO DO: There must be a better way of finding out the size of the longest row... until then
    if ($hasFieldNames){ $keys = fgetcsv($file, $size, $delimiter, $enclosure);  $result[] = $keys; }
    while ($row = fgetcsv($file, $size, $delimiter, $enclosure)) {       
        $result[] = $row;
    }
    fclose($file);
    return $result;
  } 
 
 /*
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
  */
 if(empty($_POST['step'])){
?>
<h2>Import Leads Leads :</h2>
<form action=importleads.php method=post id="uploadfile" name="uploadfile" method="post" enctype="multipart/form-data">
<input type=hidden name=step value=two>
<table>
<tr><td>Upload csv of leads:</td><td>   <input name="fileToUpload" type="file"  > </td></tr>	
<tr><td>Lead Source code:</td><td><input type=text name=source size=30></td></tr>
</table>
<input type=submit value=UPLOAD>
</form>
 <?php
} else {
if($_POST['step']=="two"){
	  $target_dir = "uploads/";
      $target_file = $target_dir . str_replace(" ","_",basename($_FILES["fileToUpload"]["name"]));
      move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
      $exif = exif_read_data($target_file);
      $filename = "";
  	 	for($i=0;$i<7;$i++){
  	  	srand((double)microtime()*1000000);
        $seed = rand(1000000,9999999);
        srand ((double)microtime()*$seed);
        $yadda = rand (1,26); 
        $myabcs = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','7');
  	  	$filename .= $myabcs[$yadda];
       }
  	    $target_file2 = "uploads/" . $filename . ".csv";
        $photolabel = $filename;
        rename ($target_file, $target_file2);
       $bigarray =  arrayFromCSV($target_file2,true,',');
       $toprow = $bigarray[0];
       print "<form action=importleads.php method=post><input type=hidden name=targetfile value=$target_file2><input type=hidden name=step value=three><input type=hidden name=source value=\"".$_POST['source']."\">";
       for($i=0; $i< count($toprow); $i++){
         print "Match ". $toprow[$i] ." with : <select name=col__$i><option value=email>email</option></select>	<br>";         
      }
      print "<input type=submit value=IMPORT></form>";
}
if($_POST['step']=="three"){
	  $bigarray =  arrayFromCSV($targetfile,true,',');
	  $template = "insert into livehelp_leads (source,status,date_entered,email,phone,firstname,lastname) VALUES ";
	  $template .= "('".$_POST['source']."','NEW',". date('Ymd') .",'INPUT-EMAILADDRESS','INPUT-PHONE','INPUT-FIRST','INPUT-LAST');";
	  
	  for($row=0; $row< count($bigarray); $row++){
	  	 $insert = $template;
	  	 $thisrow = $bigarray[$row];
	  	 for($x=0; $x < count($thisrow); $x++){
	  	 	$index = 'col__'.$x;
  	  	 if($_POST[$index]=="email"){ $insert = str_replace('INPUT-EMAILADDRESS',$thisrow[$x],$insert); $email =$thisrow[$x]; }
  	  	 if($_POST[$index]=="phone"){  $insert = str_replace('INPUT-PHONE',$thisrow[$x],$insert); }
  	  	 if($_POST[$index]=="firstname"){  $insert = str_replace('INPUT-FIRST',$thisrow[$x],$insert); }	  	  
  	  	 if($_POST[$index]=="lastname"){  $insert = str_replace('INPUT-LAST',$thisrow[$x],$insert); }	  	  
	    }
	   $mydatabase->query($insert); 
	   print "imported $email <br>\n"; 
	  }
    }}

if(!($serversession))
  $mydatabase->close_connect();
?>
<pre>





</pre>
<center>
<font size=-2>

<!-- Note if you remove this line you will be violating the license even if you have modified the program --><a href=https://lupopedia.com target=_blank>CRAFTY SYNTAX Live Help</a> 2003 - 2025 ( a product of Lupopedia LLC )  <br>CRAFTY SYNTAX is  Software released under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>
</font>
</center>
