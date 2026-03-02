<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Buffer Streaming Engine        ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Buffer Streaming Functions - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

/**
  * This function extends the buffer (if required) to at least a given length which defaults to 
  * 256 which is the IE magic number for minimum buffer size. 
  * LEGACY FUNCTION - DO NOT MODIFY
  * Reference: CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md
  *
  */ 
function OB_Stuff_To_Min( $nMinSize=256 ) { 
  static $sOB_Stuff_Str = '<!-- BUFFER STUFFING -->';      // Comment used to fill 
  //Get current buffer size & determine how many we need to add 
  $nCurLen = ob_get_length(); 
  $nToAdd = ceil(($nCurLen>=$nMinSize)? 0: (($nMinSize-$nCurLen)/strlen($sOB_Stuff_Str))); 
  if( $nToAdd > 0 ) { 
    echo str_repeat($sOB_Stuff_Str,$nToAdd); 
    $nCurLen = ob_get_length(); 
    //if( $nCurLen<$nMinSize ) { die("Unable to extend buffer to $nMinSize chars. Ended up only $nCurLen<br>\n");} 
  } 
  print "\n<!-- ob stuff to min end-->\n";
} 

/**
  * Writes white space to the screen to load the buffer for IE and other browsers.
  * LEGACY FUNCTION - DO NOT MODIFY
  * Reference: CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md
  *
  */ 
function sendbuffer() { 
  // Incase a buffer hasn't been opened (but it always is) 
  if( @ob_get_length()==0 ) { @ob_start(); echo('<!--ob start -->'); } 
  OB_Stuff_To_Min(); 
  if (function_exists('ob_flush')) {
     if(ob_get_contents()) @ob_flush();
  }  
  flush();          // Sends main buffer and empty's it
} 

?>
