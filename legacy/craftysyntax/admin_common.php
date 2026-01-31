<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// --------------------------------------------------------------------------
// $    Please check https://lupopedia.com/ or REGISTER your program for updates  $
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
// --------------------------------------------------------------------------  

// BIG NOTE:
//     At the time of the release of this version of CSLH, Version 3.1.0 
//     which is a more modular, extendable , “skinable” version of CSLH
//     was being developed.. please visit https://lupopedia.com to see if it was released! 
//===========================================================================

$isOPERATOR=true;
require_once("functions.php");
require_once("security.php");
require_once("config.php");
require_once("config_cslh.php");

// this should be obsolete we are up to php 8+ now: 
//require_once("file_get_contents.php");
require_once("class/operator.php");
$colorfile = "images".C_DIR. $CSLH_Config['colorscheme'] .C_DIR."color.php";
if(file_exists($colorfile)){
  require_once($colorfile);
} else {
	$color_background="FAFAFA";
  $color_alt1 = "E4E4E4";
  $color_alt2 = "D9D9D9";
  $color_alt3 = "ECECEC";
  $color_alt4 = "CACACA";
}

// The two sessions operator and visitor sessions:
if(empty($UNTRUSTED['cslhVISITOR'])) $UNTRUSTED['cslhVISITOR'] = "";
if(empty($UNTRUSTED['cslhOPERATOR'])) $UNTRUSTED['cslhOPERATOR'] = "";
if(!(empty($_COOKIE['cslhVISITOR']))) $UNTRUSTED['cslhVISITOR'] = $_COOKIE['cslhVISITOR'];
if(!(empty($_COOKIE['cslhOPERATOR']))) $UNTRUSTED['cslhOPERATOR'] = $_COOKIE['cslhOPERATOR'];
if(!(empty($_COOKIE['speaklanguage']))){ $CSLH_Config['speaklanguage']  = $_COOKIE['speaklanguage']; }
if(!(empty($UNTRUSTED['speak']))){ $CSLH_Config['speaklanguage'] = $UNTRUSTED['speak'];  setcookie("speaklanguage", $UNTRUSTED['speak'], time()+9600);  }
 
// settings for server session/host ip logins, cookies:
$allow_ip_host_sessions=false; // for the operator this should NEVER be set to true.
$cookiesession=true;           // in most cases this is needed to be True. unles trans-id
$serversession=true;

$identity = identity($UNTRUSTED['cslhOPERATOR'],"cslhOPERATOR",$allow_ip_host_sessions,$serversession,$cookiesession);
$isavisitor = false;

// --- BEGIN LUPOPEDIA AUTH BRIDGE ---
if (file_exists(dirname(dirname(__DIR__)) . '/lupo-includes/bootstrap.php')) {
    require_once(dirname(dirname(__DIR__)) . '/lupo-includes/bootstrap.php');
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log("LEGACY AUTH BRIDGE: bootstrap loaded. Session ID: " . session_id());
    }
    $lupo_user = current_user();
    if ($lupo_user) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("LEGACY AUTH BRIDGE: Lupo User: " . $lupo_user['email']);
        }
        $lupo_operator_id = lupo_get_operator_id_from_auth_user_id($lupo_user['auth_user_id']);
        if ($lupo_operator_id) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("LEGACY AUTH BRIDGE: Lupo Operator ID: " . $lupo_operator_id);
            }
            // Ensure this operator exists in legacy livehelp_users and is authenticated with current session
            $lupo_db = $GLOBALS['mydatabase'];
            $lupo_session_id = $identity['SESSIONID'];
            
            // Check if user already exists in legacy
            $check_sql = "SELECT user_id FROM livehelp_users WHERE username = :username OR email = :email LIMIT 1";
            $check_stmt = $lupo_db->prepare($check_sql);
            $check_stmt->execute([':username' => $lupo_user['username'], ':email' => $lupo_user['email']]);
            $legacy_user = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($legacy_user) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log("LEGACY AUTH BRIDGE: Updating legacy user ID: " . $legacy_user['user_id'] . " with session: " . $lupo_session_id);
                }
                // Update existing legacy user to be authenticated with current session
                $update_sql = "UPDATE livehelp_users SET 
                    sessionid = :sessionid, 
                    authenticated = 'Y', 
                    isoperator = 'Y', 
                    isonline = 'Y', 
                    status = 'online',
                    isadmin = :isadmin
                    WHERE user_id = :user_id";
                $update_stmt = $lupo_db->prepare($update_sql);
                $update_stmt->execute([
                    ':sessionid' => $lupo_session_id,
                    ':isadmin' => $lupo_user['is_admin'] ? 'Y' : 'N',
                    ':user_id' => $legacy_user['user_id']
                ]);
            } else {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log("LEGACY AUTH BRIDGE: Creating new legacy user for: " . $lupo_user['email']);
                }
                // Create new legacy user
                $insert_sql = "INSERT INTO livehelp_users (
                    username, password, email, sessionid, authenticated, isoperator, isonline, status, isadmin
                ) VALUES (
                    :username, 'LEGACY_SYNCED', :email, :sessionid, 'Y', 'Y', 'Y', 'online', :isadmin
                )";
                $insert_stmt = $lupo_db->prepare($insert_sql);
                $insert_stmt->execute([
                    ':username' => $lupo_user['username'],
                    ':email' => $lupo_user['email'],
                    ':sessionid' => $lupo_session_id,
                    ':isadmin' => $lupo_user['is_admin'] ? 'Y' : 'N'
                ]);
            }
        }
    } else {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("LEGACY AUTH BRIDGE: No Lupo User found.");
        }
    }
}
// --- END LUPOPEDIA AUTH BRIDGE ---

update_session($identity);

// get the info on the admin user:
$query = "SELECT user_id,onchannel,show_arrival,user_alert,externalchats,istyping,isadmin,alertchat,alerttyping,alertinsite FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$people = $mydatabase->query($query);

if($people->numrows() != 0){
  $operator_row = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $operator_row['user_id'];
  $channel = $operator_row['onchannel'];
  $show_arrival = $operator_row['show_arrival']; 
  $user_alert = $operator_row['user_alert'];
  $externalchats = $operator_row['externalchats'];
  $defaultshowvisitors = $operator_row['istyping'];
  $isadminsetting = $operator_row['isadmin'];
  $alertchat = $operator_row['alertchat'];
  $alerttyping = $operator_row['alerttyping'];
  $alertinsite = $operator_row['alertinsite'];
}
 

if(empty($alertchat)){ $alertchat = "new_chats.wav"; }
if(empty($alerttyping)){ $alerttyping = "click_x.wav"; }
if(empty($alertinsite)){ $alertinsite = "youve_got_visitors.wav"; }
 

// Change Language if department Language is not the same as default language:
$languagefile = "lang/lang-" . $CSLH_Config['speaklanguage'] . ".php";
 if(!(file_exists($languagefile))){
 	$languagefile = "lang/lang-.php";
 }	

 include($languagefile);

?>
