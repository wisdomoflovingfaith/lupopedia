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
// This file is a PUBLIC LEGACY ENDPOINT and MUST NOT be renamed, moved, or reorganized.
// The filename MUST remain exactly: livehelp_js.php
// This file MUST remain accessible at the same URL path it always had.
// DO NOT break external embeds that reference /livehelp_js.php
// Reference: CRAFTY_SYNTAX_PUBLIC_API_DOCTRINE.md

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
    ?>var WEBPATH = "<?php echo str_replace($domainname,"",$CSLH_Config['webpath']); ?>";<?php
    $WEBPATH = str_replace($domainname,"",$CSLH_Config['webpath']);
   }
} else {
   ?>var WEBPATH = "<?php echo $CSLH_Config['webpath']; ?>";<?php
   $WEBPATH = $CSLH_Config['webpath'];
}

?>var livehelp_PATH = "<?php echo $CSLH_Config['webpath']; ?>";
var livehelp_SCRIPT = "<?php echo $CSLH_Config['webpath']; ?>livehelp_js.php";
var livehelp_IMAGE = "<?php echo $CSLH_Config['s_webpath']; ?>image.php";
var livehelp_SEND = "<?php echo $CSLH_Config['webpath']; ?>livehelp_js.php";
var livehelp_PAGE = "<?php echo $CSLH_Config['webpath']; ?>live.php";
var livehelp_BASE_URL = "<?php echo $CSLH_Config['webpath']; ?>";
var livehelp_SESSION = "<?php echo $identity['SESSIONID']; ?>";
var livehelp_DEPARTMENT = "<?php echo $department; ?>";
var livehelp_USERID = "<?php echo $myid; ?>";
var livehelp_CHANNEL = "<?php echo $channel; ?>";
var livehelp_ISADMIN = "<?php echo $isadmin; ?>";
var livehelp_ISONLINE = "<?php echo $isonline; ?>";
var livehelp_ISAWAY = "<?php echo $isaway; ?>";
var livehelp_SHOWTYPE = "<?php echo $showtype; ?>";
var livehelp_CHATTYPE = "<?php echo $chattype; ?>";
var livehelp_SOUND = "<?php echo $sound; ?>";
var livehelp_REFRESH = "<?php echo $CSLH_Config['refreshrate']; ?>";
var livehelp_ADMIN_REFRESH = "<?php echo $CSLH_Config['admin_refresh']; ?>";
var livehelp_MAXREQUESTS = "<?php echo $CSLH_Config['maxrequests']; ?>";
var livehelp_SESSIONTIMEOUT = "<?php echo $CSLH_Config['sessiontimeout']; ?>";
var livehelp_OPERATORSTIMEOUT = "<?php echo $CSLH_Config['operatorstimeout']; ?>";
var livehelp_OPERATORSSSESSIONOUT = "<?php echo $CSLH_Config['operatorssessionout']; ?>";
var livehelp_USECOOKIES = "<?php echo $CSLH_Config['usecookies']; ?>";
var livehelp_IGNOREIPS = "<?php echo $CSLH_Config['ignoreips']; ?>";
var livehelp_GETHOSTNAMES = "<?php echo $CSLH_Config['gethostnames']; ?>";
var livehelp_MATCHIP = "<?php echo $CSLH_Config['matchip']; ?>";
var livehelp_MAXRECORDS = "<?php echo $CSLH_Config['maxrecords']; ?>";
var livehelp_MAXREFERERS = "<?php echo $CSLH_Config['maxreferers']; ?>";
var livehelp_MAXVISITS = "<?php echo $CSLH_Config['maxvisits']; ?>";
var livehelp_MAXMONTHS = "<?php echo $CSLH_Config['maxmonths']; ?>";
var livehelp_MAXOLDHITS = "<?php echo $CSLH_Config['maxoldhits']; ?>";
var livehelp_SHOWGAMES = "<?php echo $CSLH_Config['showgames']; ?>";
var livehelp_SHOWSEARCH = "<?php echo $CSLH_Config['showsearch']; ?>";
var livehelp_SHOWDIRECTORY = "<?php echo $CSLH_Config['showdirectory']; ?>";
var livehelp_USERTRACKING = "<?php echo $CSLH_Config['usertracking']; ?>";
var livehelp_RESETBUTTON = "<?php echo $CSLH_Config['resetbutton']; ?>";
var livehelp_KEYWORDTRACK = "<?php echo $CSLH_Config['keywordtrack']; ?>";
var livehelp_REFTRACKING = "<?php echo $CSLH_Config['reftracking']; ?>";
var livehelp_TOPKEYWORDS = "<?php echo $CSLH_Config['topkeywords']; ?>";
var livehelp_EVERYTHINGELSE = "<?php echo $CSLH_Config['everythingelse']; ?>";
var livehelp_REMEMBERUSERS = "<?php echo $CSLH_Config['rememberusers']; ?>";
var livehelp_SMTP_HOST = "<?php echo $CSLH_Config['smtp_host']; ?>";
var livehelp_SMTP_USERNAME = "<?php echo $CSLH_Config['smtp_username']; ?>";
var livehelp_SMTP_PASSWORD = "<?php echo $CSLH_Config['smtp_password']; ?>";
var livehelp_OWNER_EMAIL = "<?php echo $CSLH_Config['owner_email']; ?>";
var livehelp_TOPFRAMEHEIGHT = "<?php echo $CSLH_Config['topframeheight']; ?>";
var livehelp_TOPBACKGROUND = "<?php echo $CSLH_Config['topbackground']; ?>";
var livehelp_USECOOKIES = "<?php echo $CSLH_Config['usecookies']; ?>";
var livehelp_SMTP_PORTNUM = "<?php echo $CSLH_Config['smtp_portnum']; ?>";
var livehelp_SHOWOPERATOR = "<?php echo $CSLH_Config['showoperator']; ?>";
var livehelp_CHATCOLORS = "<?php echo $CSLH_Config['chatcolors']; ?>";
var livehelp_FLOATXY = "<?php echo $CSLH_Config['floatxy']; ?>";
var livehelp_SESSIONTIMEOUT = "<?php echo $CSLH_Config['sessiontimeout']; ?>";
var livehelp_THEME = "<?php echo $CSLH_Config['theme']; ?>";
var livehelp_OPERATORSTIMEOUT = "<?php echo $CSLH_Config['operatorstimeout']; ?>";
var livehelp_OPERATORSSSESSIONOUT = "<?php echo $CSLH_Config['operatorssessionout']; ?>";
var livehelp_MAXREQUESTS = "<?php echo $CSLH_Config['maxrequests']; ?>";
var livehelp_IGNOREAGENT = "<?php echo $CSLH_Config['ignoreagent']; ?>";
var livehelp_VERSION = "<?php echo $CSLH_Config['version']; ?>";
var livehelp_SITETITLE = "<?php echo $CSLH_Config['site_title']; ?>";
var livehelp_USE_FLUSH = "<?php echo $CSLH_Config['use_flush']; ?>";
var livehelp_MEMBERNUM = "<?php echo $CSLH_Config['membernum']; ?>";
var livehelp_SHOW_TYPING = "<?php echo $CSLH_Config['show_typing']; ?>";
var livehelp_WEBPATH = "<?php echo $CSLH_Config['webpath']; ?>";
var livehelp_S_WEBPATH = "<?php echo $CSLH_Config['s_webpath']; ?>";
var livehelp_SPEAKLANGUAGE = "<?php echo $CSLH_Config['speaklanguage']; ?>";
var livehelp_SCRATCH_SPACE = "<?php echo $CSLH_Config['scratch_space']; ?>";
var livehelp_ADMIN_REFRESH = "<?php echo $CSLH_Config['admin_refresh']; ?>";
var livehelp_MAXEXE = "<?php echo $CSLH_Config['maxexe']; ?>";
var livehelp_REFRESHRATE = "<?php echo $CSLH_Config['refreshrate']; ?>";
var livehelp_CHATMODE = "<?php echo $CSLH_Config['chatmode']; ?>";
var livehelp_ADMINSESSION = "<?php echo $CSLH_Config['adminsession']; ?>";
var livehelp_IGNOREIPS = "<?php echo $CSLH_Config['ignoreips']; ?>";
var livehelp_DIRECTORYID = "<?php echo $CSLH_Config['directoryid']; ?>";
var livehelp_TRACKING = "<?php echo $CSLH_Config['tracking']; ?>";
var livehelp_COLORSCHEME = "<?php echo $CSLH_Config['colorscheme']; ?>";
var livehelp_MATCHIP = "<?php echo $CSLH_Config['matchip']; ?>";
var livehelp_GETHOSTNAMES = "<?php echo $CSLH_Config['gethostnames']; ?>";
var livehelp_MAXRECORDS = "<?php echo $CSLH_Config['maxrecords']; ?>";
var livehelp_MAXREFERERS = "<?php echo $CSLH_Config['maxreferers']; ?>";
var livehelp_MAXVISITS = "<?php echo $CSLH_Config['maxvisits']; ?>";
var livehelp_MAXMONTHS = "<?php echo $CSLH_Config['maxmonths']; ?>";
var livehelp_MAXOLDHITS = "<?php echo $CSLH_Config['maxoldhits']; ?>";
var livehelp_SHOWGAMES = "<?php echo $CSLH_Config['showgames']; ?>";
var livehelp_SHOWSEARCH = "<?php echo $CSLH_Config['showsearch']; ?>";
var livehelp_SHOWDIRECTORY = "<?php echo $CSLH_Config['showdirectory']; ?>";
var livehelp_USERTRACKING = "<?php echo $CSLH_Config['userusertracking']; ?>";
var livehelp_RESETBUTTON = "<?php echo $CSLH_Config['resetbutton']; ?>";
var livehelp_KEYWORDTRACK = "<?php echo $CSLH_Config['keywordtrack']; ?>";
var livehelp_REFTRACKING = "<?php echo $CSLH_Config['reftracking']; ?>";
var livehelp_TOPKEYWORDS = "<?php echo $CSLH_Config['topkeywords']; ?>";
var livehelp_EVERYTHINGELSE = "<?php echo $CSLH_Config['everythingelse']; ?>";
var livehelp_REMEMBERUSERS = "<?php echo $CSLH_Config['rememberusers']; ?>";
var livehelp_SMTP_HOST = "<?php echo $CSLH_Config['smtp_host']; ?>";
var livehelp_SMTP_USERNAME = "<?php echo $CSLH_Config['smtp_username']; ?>";
var livehelp_SMTP_PASSWORD = "<?php echo $CSLH_Config['smtp_password']; ?>";
var livehelp_OWNER_EMAIL = "<?php echo $CSLH_Config['owner_email']; ?>";
var livehelp_TOPFRAMEHEIGHT = "<?php echo $CSLH_Config['topframeheight']; ?>";
var livehelp_TOPBACKGROUND = "<?php echo $CSLH_Config['topbackground']; ?>";
var livehelp_USECOOKIES = "<?php echo $CSLH_Config['usecookies']; ?>";
var livehelp_SMTP_PORTNUM = "<?php echo $CSLH_Config['smtp_portnum']; ?>";
var livehelp_SHOWOPERATOR = "<?php echo $CSLH_Config['showoperator']; ?>";
var livehelp_CHATCOLORS = "<?php echo $CSLH_Config['chatcolors']; ?>";
var livehelp_FLOATXY = "<?php echo $CSLH_Config['floatxy']; ?>";
var livehelp_SESSIONTIMEOUT = "<?php echo $CSLH_Config['sessiontimeout']; ?>";
var livehelp_THEME = "<?php echo $CSLH_Config['theme']; ?>";
var livehelp_OPERATORSTIMEOUT = "<?php echo $CSLH_Config['operatorstimeout']; ?>";
var livehelp_OPERATORSSSESSIONOUT = "<?php echo $CSLH_Config['operatorssessionout']; ?>";
var livehelp_MAXREQUESTS = "<?php echo $CSLH_Config['maxrequests']; ?>";
var livehelp_IGNOREAGENT = "<?php echo $CSLH_Config['ignoreagent']; ?>";

<?php

// get the info of this user.. 
// Updated to use Lupopedia table mapping: livehelp_users → lupo_users
$query = "SELECT * FROM lupo_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$department = $people['department'];
$isadmin = $people['isadmin'];
$isonline = $people['isonline'];
$isaway = $people['status'];
$showtype = $people['showtype'];
$chattype = $people['chattype'];
$sound = $people['sound'];

?>var livehelp_USERID = "<?php echo $myid; ?>";
var livehelp_CHANNEL = "<?php echo $channel; ?>";
var livehelp_DEPARTMENT = "<?php echo $department; ?>";
var livehelp_ISADMIN = "<?php echo $isadmin; ?>";
var livehelp_ISONLINE = "<?php echo $isonline; ?>";
var livehelp_ISAWAY = "<?php echo $isaway; ?>";
var livehelp_SHOWTYPE = "<?php echo $showtype; ?>";
var livehelp_CHATTYPE = "<?php echo $chattype; ?>";
var livehelp_SOUND = "<?php echo $sound; ?>";

<?php

if(!(empty($UNTRUSTED['what']))){ $UNTRUSTED['what'] = $UNTRUSTED['what']; }

switch($UNTRUSTED['what']){

case "ping":
   echo "OK";
   break;

case "get_jsrn":
   echo get_jsrn($identity);
   break;

case "get_department":
   echo get_department($department);
   break;

case "get_channel":
   echo get_channel($channel);
   break;

case "get_user":
   echo get_user($myid);
   break;

case "get_admin":
   echo get_admin($myid);
   break;

case "get_config":
   echo get_config();
   break;

case "get_version":
   echo $CSLH_Config['version'];
   break;

case "get_theme":
   echo $CSLH_Config['theme'];
   break;

case "get_colorscheme":
   echo $CSLH_Config['colorscheme'];
   break;

case "get_webpath":
   echo $CSLH_Config['webpath'];
   break;

case "get_s_webpath":
   echo $CSLH_Config['s_webpath'];
   break;

case "get_speaklanguage":
   echo $CSLH_Config['speaklanguage'];
   break;

case "get_site_title":
   echo $CSLH_Config['site_title'];
   break;

case "get_use_flush":
   echo $CSLH_Config['use_flush'];
   break;

case "get_membernum":
   echo $CSLH_Config['membernum'];
   break;

case "get_show_typing":
   echo $CSLH_Config['show_typing'];
   break;

case "get_admin_refresh":
   echo $CSLH_Config['admin_refresh'];
   break;

case "get_maxexe":
   echo $CSLH_Config['maxexe'];
   break;

case "get_refreshrate":
   echo $CSLH_Config['refreshrate'];
   break;

case "get_chatmode":
   echo $CSLH_Config['chatmode'];
   break;

case "get_adminsession":
   echo $CSLH_Config['adminsession'];
   break;

case "get_ignoreips":
   echo $CSLH_Config['ignoreips'];
   break;

case "get_directoryid":
   echo $CSLH_Config['directoryid'];
   break;

case "get_tracking":
   echo $CSLH_Config['tracking'];
   break;

case "get_colorscheme":
   echo $CSLH_Config['colorscheme'];
   break;

case "get_matchip":
   echo $CSLH_Config['matchip'];
   break;

case "get_gethostnames":
   echo $CSLH_Config['gethostnames'];
   break;

case "get_maxrecords":
   echo $CSLH_Config['maxrecords'];
   break;

case "get_maxreferers":
   echo $CSLH_Config['maxreferers'];
 break;

case "get_maxvisits":
   echo $CSLH_Config['maxvisits'];
   break;

case "get_maxmonths":
   echo $CSLH_Config['maxmonths'];
   break;

case "get_maxoldhits":
   echo $CSLH_Config['maxoldhits'];
   break;

case "get_showgames":
   echo $CSLH_Config['showgames'];
   break;

case "get_showsearch":
   echo $CSLH_Config['showsearch'];
   break;

case "get_showdirectory":
   echo $CSLH_Config['showdirectory'];
   break;

case "get_usertracking":
   echo $CSLH_Config['usertracking'];
   break;

case "get_resetbutton":
   echo $CSLH_Config['resetbutton'];
   break;

case "get_keywordtrack":
   echo $CSLH_Config['keywordtrack'];
   break;

case "get_reftracking":
   echo $CSLH_Config['reftracking'];
   break;

case "get_topkeywords":
   echo $CSLH_Config['topkeywords'];
   break;

case "get_everythingelse":
   echo $CSLH_Config['everythingelse'];
   break;

case "get_rememberusers":
   echo $CSLH_Config['rememberusers'];
   break;

case "get_smtp_host":
   echo $CSLH_Config['smtp_host'];
   break;

case "get_smtp_username":
   echo $CSLH_Config['smtp_username'];
   break;

case "get_smtp_password":
   echo $CSLH_Config['smtp_password'];
   break;

case "get_owner_email":
   echo $CSLH_Config['owner_email'];
   break;

case "get_topframeheight":
   echo $CSLH_Config['topframeheight'];
   break;

case "get_topbackground":
   echo $CSLH_Config['topbackground'];
   break;

case "get_usecookies":
   echo $CSLH_Config['usecookies'];
   break;

case "get_smtp_portnum":
   echo $CSLH_Config['smtp_portnum'];
   break;

case "get_showoperator":
   echo $CSLH_Config['showoperator'];
   break;

case "get_chatcolors":
   echo $CSLH_Config['chatcolors'];
   break;

case "get_floatxy":
   echo $CSLH_Config['floatxy'];
   break;

case "get_sessiontimeout":
   echo $CSLH_Config['sessiontimeout'];
   break;

case "get_theme":
   echo $CSLH_Config['theme'];
   break;

case "get_operatorstimeout":
   echo $CSLH_Config['operatorstimeout'];
   break;

case "get_operatorssessionout":
   echo $CSLH_Config['operatorssessionout'];
   break;

case "get_maxrequests":
   echo $CSLH_Config['maxrequests'];
   break;

case "get_ignoreagent":
   echo $CSLH_Config['ignoreagent'];
   break;

case "get_version":
   echo $CSLH_Config['version'];
   break;

case "get_site_title":
   echo $CSLH_Config['site_title'];
   break;

case "get_use_flush":
   echo $CSLH_Config['use_flush'];
   break;

case "get_membernum":
   echo $CSLH_Config['membernum'];
   break;

case "get_show_typing":
   echo $CSLH_Config['show_typing'];
   break;

case "get_admin_refresh":
   echo $CSLH_Config['admin_refresh'];
   break;

case "get_maxexe":
   echo $CSLH_Config['maxexe'];
   break;

case "get_refreshrate":
   echo $CSLH_Config['refreshrate'];
   break;

case "get_chatmode":
   echo $CSLH_Config['chatmode'];
   break;

case "get_adminsession":
   echo $CSLH_Config['adminsession'];
   break;

case "get_ignoreips":
   echo $CSLH_Config['ignoreips'];
   break;

case "get_directoryid":
   echo $CSLH_Config['directoryid'];
   break;

case "get_tracking":
   echo $CSLH_Config['tracking'];
   break;

case "get_colorscheme":
   echo $CSLH_Config['colorscheme'];
   break;

case "get_matchip":
   echo $CSLH_Config['matchip'];
   break;

case "get_gethostnames":
   echo $CSLH_Config['gethostnames'];
   break;

case "get_maxrecords":
   echo $CSLH_Config['maxrecords'];
   break;

case "get_maxreferers":
   echo $CSLH_Config['maxreferers'];
   break;

case "get_maxvisits":
   echo $CSLH_Config['maxvisits'];
   break;

case "get_maxmonths":
   echo $CSLH_Config['maxmonths'];
   break;

case "get_maxoldhits":
   echo $CSLH_Config['maxoldhits'];
   break;

case "get_showgames":
   echo $CSLH_Config['showgames'];
   break;

case "get_showsearch":
   echo $CSLH_Config['showsearch'];
   break;

case "get_showdirectory":
   echo $CSLH_Config['showdirectory'];
   break;

case "get_usertracking":
   echo $CSLH_Config['usertracking'];
   break;

case "get_resetbutton":
   echo $CSLH_Config['resetbutton'];
   break;

case "get_keywordtrack":
   echo $CSLH_Config['keywordtrack'];
   break;

case "get_reftracking":
   echo $CSLH_Config['reftracking'];
   break;

case "get_topkeywords":
   echo $CSLH_Config['topkeywords'];
   break;

case "get_everythingelse":
   echo $CSLH_Config['everythingelse'];
   break;

case "get_rememberusers":
   echo $CSLH_Config['rememberusers'];
   break;

case "get_smtp_host":
   echo $CSLH_Config['smtp_host'];
   break;

case "get_smtp_username":
   echo $CSLH_Config['smtp_username'];
   break;

case "get_smtp_password":
   echo $CSLH_Config['smtp_password'];
   break;

case "get_owner_email":
   echo $CSLH_Config['owner_email'];
   break;

case "get_topframeheight":
   echo $CSLH_Config['topframeheight'];
   break;

case "get_topbackground":
   echo $CSLH_Config['topbackground'];
   break;

case "get_usecookies":
   echo $CSLH_Config['usecookies'];
   break;

case "get_smtp_portnum":
   echo $CSLH_Config['smtp_portnum'];
   break;

case "get_showoperator":
   echo $CSLH_Config['showoperator'];
   break;

case "get_chatcolors":
   echo $CSLH_Config['chatcolors'];
   break;

case "get_floatxy":
   echo $CSLH_Config['floatxy'];
   break;

case "get_sessiontimeout":
   echo $CSLH_Config['sessiontimeout'];
   break;

case "get_theme":
   echo $CSLH_Config['theme'];
   break;

case "get_operatorstimeout":
   echo $CSLH_Config['operatorstimeout'];
   break;

case "get_operatorssessionout":
   echo $CSLH_Config['operatorssessionout'];
   break;

case "get_maxrequests":
   echo $CSLH_Config['maxrequests'];
   break;

case "get_ignoreagent":
   echo $CSLH_Config['ignoreagent'];
   break;

case "get_version":
   echo $CSLH_Config['version'];
   break;

case "get_site_title":
   echo $CSLH_Config['site_title'];
   break;

case "get_use_flush":
   echo $CSLH_Config['use_flush'];
   break;

case "get_membernum":
   echo $CSLH_Config['membernum'];
   break;

case "get_show_typing":
   echo $CSLH_Config['show_typing'];
   break;

case "get_admin_refresh":
   echo $CSLH_Config['admin_refresh'];
   break;

case "get_maxexe":
   echo $CSLH_Config['maxexe'];
   break;

case "get_refreshrate":
   echo $CSLH_Config['refreshrate'];
   break;

case "get_chatmode":
   echo $CSLH_Config['chatmode'];
   break;

case "get_adminsession":
   echo $CSLH_Config['adminsession'];
   break;

case "get_ignoreips":
   echo $CSLH_Config['ignoreips'];
   break;

case "get_directoryid":
   echo $CSLH_Config['directoryid'];
   break;

case "get_tracking":
   echo $CSLH_Config['tracking'];
   break;

case "get_colorscheme":
   echo $CSLH_Config['colorscheme'];
   break;

case "get_matchip":
   echo $CSLH_Config['matchip'];
   break;

case "get_gethostnames":
   echo $CSLH_Config['gethostnames'];
   break;

case "get_maxrecords":
   echo $CSLH_Config['maxrecords'];
   break;

case "get_maxreferers":
   echo $CSLH_Config['maxreferers'];
   break;

case "get_maxvisits":
   echo $CSLH_Config['maxvisits'];
   break;

case "get_maxmonths":
   echo $CSLH_Config['maxmonths'];
   break;

case "get_maxoldhits":
   echo $CSLH_Config['maxoldhits'];
   break;

case "get_showgames":
   echo $CSLH_Config['showgames'];
   break;

case "get_showsearch":
   echo $CSLH_Config['showsearch'];
   break;

case "get_showdirectory":
   echo $CSLH_Config['showdirectory'];
   break;

case "get_usertracking":
   echo $CSLH_Config['usertracking'];
   break;

case "get_resetbutton":
   echo $CSLH_Config['resetbutton'];
   break;

case "get_keywordtrack":
   echo $CSLH_Config['keywordtrack'];
   break;

case "get_reftracking":
   echo $CSLH_Config['reftracking'];
   break;

case "get_topkeywords":
   echo $CSLH_Config['topkeywords'];
   break;

case "get_everythingelse":
   echo $CSLH_Config['everythingelse'];
   break;

case "get_rememberusers":
   echo $CSLH_Config['rememberusers'];
   break;

case "get_smtp_host":
   echo $CSLH_Config['smtp_host'];
   break;

case "get_smtp_username":
   echo $CSLH_Config['smtp_username'];
   break;

case "get_smtp_password":
   echo $CSLH_Config['smtp_password'];
   break;

case "get_owner_email":
   echo $CSLH_Config['owner_email'];
   break;

case "get_topframeheight":
   echo $CSLH_Config['topframeheight'];
   break;

case "get_topbackground":
   echo $CSLH_Config['topbackground'];
   break;

case "get_usecookies":
   echo $CSLH_Config['usecookies'];
   break;

case "get_smtp_portnum":
   echo $CSLH_Config['smtp_portnum'];
   break;

case "get_showoperator":
   echo $CSLH_Config['showoperator'];
   break;

case "get_chatcolors":
   echo $CSLH_Config['chatcolors'];
   break;

case "get_floatxy":
   echo $CSLH_Config['floatxy'];
   break;

case "get_sessiontimeout":
   echo $CSLH_Config['sessiontimeout'];
   break;

case "get_theme":
   echo $CSLH_Config['theme'];
   break;

case "get_operatorstimeout":
   echo $CSLH_Config['operatorstimeout'];
   break;

case "get_operatorssessionout":
   echo $CSLH_Config['operatorssessionout'];
   break;

case "get_maxrequests":
   echo $CSLH_Config['maxrequests'];
   break;

case "get_ignoreagent":
   echo $CSLH_Config['ignoreagent'];
   break;

case "get_version":
   echo $CSLH_Config['version'];
   break;

case "get_site_title":
   echo $CSLH_Config['site_title'];
   break;

case "get_use_flush":
   echo $CSLH_Config['use_flush'];
   break;

case "get_membernum":
   echo $CSLH_Config['membernum'];
   break;

case "get_show_typing":
   echo $CSLH_Config['show_typing'];
   break;

case "get_admin_refresh":
   echo $CSLH_Config['admin_refresh'];
   break;

case "get_maxexe":
   echo $CSLH_Config['maxexe'];
   break;

case "get_refreshrate":
   echo $CSLH_Config['refreshrate'];
   break;

case "get_chatmode":
   echo $CSLH_Config['chatmode'];
   break;

case "get_adminsession":
   echo $CSLH_Config['adminsession'];
   break;

case "get_ignoreips":
   echo $CSLH_Config['ignoreips'];
   break;

case "get_directoryid":
   echo $CSLH_Config['directoryid'];
   break;

case "get_tracking":
   echo $CSLH_Config['tracking'];
   break;

case "get_colorscheme":
   echo $CSLH_Config['colorscheme'];
   break;

case "get_matchip":
   echo $CSLH_Config['matchip'];
   break;

case "get_gethostnames":
   echo $CSLH_Config['gethostnames'];
   break;

case "get_maxrecords":
   echo $CSLH_Config['maxrecords'];
   break;

case "get_maxreferers":
   echo $CSLH_Config['maxreferers'];
   break;

case "get_maxvisits":
   echo $CSLH_Config['maxvisits'];
   break;

case "get_maxmonths":
   echo $CSLH_Config['maxmonths'];
   break;

case "get_maxoldhits":
   echo $CSLH_Config['maxoldhits'];
   break;

case "get_showgames":
   echo $CSLH_Config['showgames'];
   break;

case "get_showsearch":
   echo $CSLH_Config['showsearch'];
   break;

case "get_showdirectory":
   echo $CSLH_Config['showdirectory'];
   break;

case "get_usertracking":
   echo $CSLH_Config['usertracking'];
   break;

case "get_resetbutton":
   echo $CSLH_Config['resetbutton'];
   break;

case "get_keywordtrack":
   echo $CSLH_Config['keywordtrack'];
   break;

case "get_reftracking":
   echo $CSLH_Config['reftracking'];
   break;

case "get_topkeywords":
   echo $CSLH_Config['topkeywords'];
   break;

case "get_everythingelse":
   echo $CSLH_Config['everythingelse'];
   break;

case "get_rememberusers":
   echo $CSLH_Config['rememberusers'];
   break;

case "get_smtp_host":
   echo $CSLH_Config['smtp_host'];
   break;

case "get_smtp_username":
   echo $CSLH_Config['smtp_username'];
   break;

case "get_smtp_password":
   echo $CSLH_Config['smtp_password'];
   break;

case "get_owner_email":
   echo $CSLH_Config['owner_email'];
   break;

case "get_topframeheight":
   echo $CSLH_Config['topframeheight'];
   break;

case "get_topbackground":
   echo $CSLH_Config['topbackground'];
   break;

case "get_usecookies":
   echo $CSLH_Config['usecookies'];
   break;

case "get_smtp_portnum":
   echo $CSLH_Config['smtp_portnum'];
   break;

case "get_showoperator":
   echo $CSLH_Config['showoperator'];
   break;

case "get_chatcolors":
   echo $CSLH_Config['chatcolors'];
   break;

case "get_floatxy":
   echo $CSLH_Config['floatxy'];
   break;

case "get_sessiontimeout":
   echo $CSLH_Config['sessiontimeout'];
   break;

case "get_theme":
   echo $CSLH_Config['theme'];
   break;

case "get_operatorstimeout":
   echo $CSLH_Config['operatorstimeout'];
   break;

case "get_operatorssessionout":
   echo $CSLH_Config['operatorssessionout'];
   break;

case "get_maxrequests":
   echo $CSLH_Config['maxrequests'];
   break;

case "get_ignoreagent":
   echo $CSLH_Config['ignoreagent'];
   break;

default:
   echo "Unknown command: " . $UNTRUSTED['what'];
   break;
}

?>
