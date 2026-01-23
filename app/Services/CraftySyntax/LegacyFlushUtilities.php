<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Flush Utilities            ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Flush Utilities - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
?>
<body bgcolor=<?php echo $color_background;?>>
<?php
for($l=0;$l<20;$l++){
     	   // load the buffer 
 for ($i=0; $i<300; $i++) print ("  "); 
  print ("\n");
     	   ?>
     	   <!-- BUFFER -->
     	 <?php
     	   flush(); 
           if (function_exists('ob_flush')) {
           if(ob_get_contents()) ob_flush(); }  
     	 ?>  
     	 test<br><bR>
       	   <!-- BUFFER -->
     	   <?php
     	   // load the buffer 
for ($i=0; $i<300; $i++) print ("  ");
print ("\n");
          ?>
           <!-- BUFFER -->
          <?php
     	   flush(); 
           if (function_exists('ob_flush')) {
           if(ob_get_contents()) ob_flush(); } 
sleep(2);
} 
?>
