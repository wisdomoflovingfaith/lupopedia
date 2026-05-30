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
// This file has been migrated from legacy/craftysyntax/livehelp_js.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_CHAT_ENGINE_DOCTRINE.md

// Requests directly to livehelp_js.php so not create a session.
// sessions are created with requests to image.php:
$ghost_session = true;
require_once("visitor_common.php");
 
// backwards compatability (renamed because of modsecurity)
if(!(empty($UNTRUSTED['cmd']))){ $UNTRUSTED['what'] = $UNTRUSTED['cmd']; }

// if we are using a relative path to the domain we want the path to
// be somthing like WEBPATH = '/livehelp/'; 
// we do this by getting the full domain and then removing that from the 
// webpath so for example http://www.yourwebsite.com/livehelp/ becomes /livehelp/
if(!(empty($UNTRUSTED['relative']))){
	 // get the domain..
	 $domainname_a = explode("/",$CSLH_Config['webpath']);
	 if(empty($domainname_a[2])) $domainname_a[2] = $domainname_a[0];
   $domainname = "https://" . $domainname_a[2];
   if ( isset($UNTRUSTED['secure']) || ((isset($_SERVER["HTTPS"] ) && stristr($_SERVER["HTTPS"], "on"))) ){
    ?>var WEBPATH = "<?php echo str_replace($domainname,"",$CSLH_Config['s_webpath']); ?>";<?php
    $WEBPATH = str_replace($domainname,"",$CSLH_Config['s_webpath']);
   } else {

?>
