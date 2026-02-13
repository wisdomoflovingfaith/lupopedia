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
// This file has been migrated from legacy/craftysyntax/functions.php
// to Lupopedia structure under HERITAGE-SAFE MODE.
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md

// TOON ANALYTICS INTEGRATION
// This file now includes TOON event logging for behavioral analytics
// while preserving all legacy functionality.
// Reference: ACTOR_IDENTITY_DOCTRINE.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Crafty Syntax → Lupopedia: session/operator row (per livehelp_sessions_migration.md, livehelp_users_migration.md)
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

/**
 * Get operator/visitor "people" row from {prefix}sessions by session_id (PDO_DB, no legacy tables).
 * Replaces reads from dropped livehelp_users; session state is in lupo_sessions (actor_id, session_data).
 *
 * @param string $session_id Session ID (e.g. $identity['SESSIONID'])
 * @return array|null Assoc with user_id (actor_id), onchannel, isadmin, show_arrival, user_alert, greeting, photo, etc.
 */
function crafty_get_session_people($session_id) {
    global $mydatabase;
    if (!$session_id || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
        return null;
    }
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $sessions_table = $table_prefix . 'sessions';
    $row = $mydatabase->fetchRow(
        "SELECT actor_id, session_data FROM {$sessions_table} WHERE session_id = :sid",
        ['sid' => $session_id]
    );
    if (!$row) {
        return null;
    }
    $session_data = !empty($row['session_data']) ? json_decode($row['session_data'], true) : [];
    if (!is_array($session_data)) {
        $session_data = [];
    }
    return [
        'user_id' => (int) $row['actor_id'],
        'onchannel' => isset($session_data['onchannel']) ? (int) $session_data['onchannel'] : 0,
        'isadmin' => isset($session_data['isadmin']) ? (int) $session_data['isadmin'] : 0,
        'show_arrival' => isset($session_data['show_arrival']) ? (int) $session_data['show_arrival'] : 0,
        'user_alert' => isset($session_data['user_alert']) ? (int) $session_data['user_alert'] : 0,
        'greeting' => $session_data['greeting'] ?? '',
        'photo' => $session_data['photo'] ?? '',
        'showtype' => $session_data['showtype'] ?? 0,
        'externalchats' => isset($session_data['externalchats']) ? (int) $session_data['externalchats'] : 0,
        'chattype' => $session_data['chattype'] ?? '',
        'username' => $session_data['username'] ?? '',
    ];
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// TOON Analytics Integration
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

/**
 * Log TOON event for behavioral analytics with world context support
 * 
 * @param string $event_type Event type identifier
 * @param array $event_data Event data payload
 * @param int|null $actor_id Actor ID (optional)
 * @param string|null $session_id Session ID (optional)
 * @param int|null $tab_id Tab ID (optional)
 * @param int|null $world_id World ID (optional)
 * @param string|null $world_key World key (optional)
 * @param string|null $world_type World type (optional)
 * @return bool Success status
 */
function log_toon_event($event_type, $event_data, $actor_id = null, $session_id = null, $tab_id = null, $world_id = null, $world_key = null, $world_type = null) {
    global $mydatabase;
    if (!$mydatabase || !($mydatabase instanceof PDO_DB)) {
        return false;
    }
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $timestamp = (int) date('YmdHis');
    $event_data_json = json_encode($event_data);
    try {
        $mydatabase->insert($table_prefix . 'event_log', [
            'event_type' => $event_type,
            'event_data' => $event_data_json,
            'created_ymdhis' => $timestamp,
        ]);
        if ($world_id) {
            $mydatabase->insert($table_prefix . 'world_events', [
                'world_id' => $world_id,
                'world_key' => $world_key,
                'world_type' => $world_type,
                'actor_id' => $actor_id,
                'session_id' => $session_id,
                'tab_id' => $tab_id,
                'event_type' => $event_type,
                'event_data' => $event_data_json,
                'created_ymdhis' => $timestamp,
            ]);
        }
        if ($actor_id && in_array($event_type, ['user_login', 'user_logout', 'operator_login', 'operator_logout', 'operator_presence_change', 'operator_console_session_start', 'admin_chat_bot_access', 'admin_options_access', 'admin_users_redirect'])) {
            $mydatabase->insert($table_prefix . 'actor_events', [
                'actor_id' => $actor_id,
                'session_id' => $session_id,
                'tab_id' => $tab_id,
                'world_id' => $world_id,
                'world_key' => $world_key,
                'world_type' => $world_type,
                'event_type' => $event_type,
                'event_data' => $event_data_json,
                'created_ymdhis' => $timestamp,
            ]);
        }
        if ($session_id && in_array($event_type, ['session_created', 'session_updated', 'session_destroyed', 'language_changed', 'session_creation'])) {
            $mydatabase->insert($table_prefix . 'session_events', [
                'session_id' => $session_id,
                'actor_id' => $actor_id,
                'tab_id' => $tab_id,
                'world_id' => $world_id,
                'world_key' => $world_key,
                'world_type' => $world_type,
                'event_type' => $event_type,
                'event_data' => $event_data_json,
                'created_ymdhis' => $timestamp,
            ]);
        }
        if ($tab_id && in_array($event_type, ['tab_created', 'tab_updated', 'tab_closed', 'tab_focus', 'tab_blur'])) {
            $mydatabase->insert($table_prefix . 'tab_events', [
                'tab_id' => $tab_id,
                'session_id' => $session_id,
                'actor_id' => $actor_id,
                'world_id' => $world_id,
                'world_key' => $world_key,
                'world_type' => $world_type,
                'event_type' => $event_type,
                'event_data' => $event_data_json,
                'created_ymdhis' => $timestamp,
            ]);
        }
        if (in_array($event_type, ['message_sent', 'message_received', 'chat_flush', 'chat_refresh', 'content_interaction'])) {
            $content_id = $event_data['content_id'] ?? null;
            if ($content_id) {
                $mydatabase->insert($table_prefix . 'content_events', [
                    'content_id' => $content_id,
                    'actor_id' => $actor_id,
                    'session_id' => $session_id,
                    'tab_id' => $tab_id,
                    'world_id' => $world_id,
                    'world_key' => $world_key,
                    'world_type' => $world_type,
                    'event_type' => $event_type,
                    'event_data' => $event_data_json,
                    'created_ymdhis' => $timestamp,
                ]);
            }
        }
        return true;
    } catch (Exception $e) {
        error_log("TOON Event Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Resolve actor ID from session actor_id or lupo_actors (per livehelp_users_migration.md → lupo_auth_users).
 *
 * @param int $user_id Actor ID from session (lupo_sessions.actor_id) or auth_user_id
 * @return int|null Actor ID
 */
function resolve_actor_from_lupo_user($user_id) {
    global $mydatabase;
    if (!$mydatabase || !($mydatabase instanceof PDO_DB)) {
        return null;
    }
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $actors_table = $table_prefix . 'actors';
    $row = $mydatabase->fetchRow(
        "SELECT actor_id FROM {$actors_table} WHERE actor_source_id = :uid AND actor_source_type = :tbl",
        ['uid' => $user_id, 'tbl' => 'lupo_auth_users']
    );
    if ($row && isset($row['actor_id'])) {
        return (int) $row['actor_id'];
    }
    try {
        $timestamp = (int) date('YmdHis');
        $slug = 'actor_' . $user_id . '_' . $timestamp;
        $actor_id = function_exists('lupo_findpuka') ? lupo_findpuka($mydatabase, $actors_table, 'actor_id', 1, null) : null;
        if ($actor_id === null) {
            return null;
        }
        $mydatabase->insert($actors_table, array(
            'actor_id' => $actor_id,
            'actor_type' => 'legacy_user',
            'slug' => $slug,
            'name' => '',
            'created_ymdhis' => $timestamp,
            'updated_ymdhis' => $timestamp,
            'actor_source_id' => $user_id,
            'actor_source_type' => 'lupo_auth_users',
        ));
        return $actor_id;
    } catch (Exception $e) {
        error_log("Actor Resolution Error: " . $e->getMessage());
        return (int) $user_id;
    }
}

/**
 * Get current actor ID from {prefix}sessions (per livehelp_sessions_migration.md).
 *
 * @return int|null Actor ID
 */
function get_current_actor_id() {
    global $identity, $mydatabase;
    if (!$identity || !isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
        return null;
    }
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $sessions_table = $table_prefix . 'sessions';
    $row = $mydatabase->fetchRow(
        "SELECT actor_id FROM {$sessions_table} WHERE session_id = :sid",
        ['sid' => $identity['SESSIONID']]
    );
    return ($row && isset($row['actor_id'])) ? (int) $row['actor_id'] : null;
}

/**
 * Get current session ID
 * 
 * @return string Session ID
 */
function get_current_session_id() {
    return session_id();
}

/**
 * Generate unique tab identifier
 * 
 * @return string Tab ID
 */
function get_current_tab_id() {
    return 'tab_' . md5(uniqid() . $_SERVER['HTTP_USER_AGENT'] . microtime(true));
}

/**
 * Validate TOON event data
 * 
 * @param string $event_type Event type
 * @param array $event_data Event data
 * @param int|null $actor_id Actor ID
 * @param string|null $session_id Session ID
 * @param int|null $content_id Content ID
 * @return bool Validation result
 */
function validate_toon_event($event_type, $event_data, $actor_id, $session_id, $content_id) {
    // Validate actor_id for actor and content events
    if (in_array($event_type, ['actor_action', 'content_action']) && !$actor_id) {
        error_log("TOON Event Error: Missing actor_id for event type: $event_type");
        return false;
    }
    
    // Validate session_id for session events
    if ($event_type == 'session_event' && !$session_id) {
        error_log("TOON Event Error: Missing session_id for session event");
        return false;
    }
    
    // Validate content_id for content events
    if ($event_type == 'content_action' && !$content_id) {
        error_log("TOON Event Error: Missing content_id for content event");
        return false;
    }
    
    // Validate event_data structure
    if (!is_array($event_data)) {
        error_log("TOON Event Error: event_data must be array");
        return false;
    }
    
    return true;
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// LEGACY FUNCTIONS WITH TOON ANALYTICS INTEGRATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

  * finds user identity : hostname, ip, referer, user agent and starts session 
  *
  * @param string $PHPSESSID session Id 
  * @param string $sessionname - the name of this session..   
  * @param bool   $allow_ip_host_sessions -  finding session id based on ip and host (identity)
  * @param bool   $serversession - create a server session using built in PHP $_SESSION 
  * @param bool   $cookiesession - cookie the session 
  * @param bool   $ghost_session - create a ghost session (no database storage)
  *
  * @global string  $username - username of user passed in query string.  
  * @global object $mydatabase mysql database object.
 * @global int    $department department id.
 * @global array  $REMOTE_ADDR  
 * @global array  $_SERVER  
 * @global array  $_ENV              
 * @global string  $HTTP_USER_AGENT      
 * @global string  $HTTP_REFERER   
  *
 * @return array an associative array with the following keys:
 *  +   HOSTNAME:  Database backend used in PHP (mysql, odbc etc.)
 *  +    IP_ADDR: Database used with regards to SQL syntax etc.
 *  * USER_AGENT: Communication protocol to use (tcp, unix etc.)
 *  +    REFERER: Host specification (hostname[:port])
 *  +  SESSIONID: Database to use on the DBMS server
 *  +   IDENTITY: User name for login
 *  +     HANDLE: Current handle or username
 *  +NEW_SESSION: enum (Y,N) Y if this is a new session N if this is not a new session.
 *  + COOKIE_SET: enum (Y,N) Y if session is set in a cookie N if this is not a new session.
 *  + COOKIE_SET: enum (Y,N) Y if session is set in a cookie N if this is not a new session.
 *  */ 
function identity($PHPSESSID="",$sessionname="PHPSESSID",$allow_ip_host_sessions=false,$serversession=false,$cookiesession=true,$ghost_session=false){
   global $isOPERATOR,$sess,$CSLH_Config,$username,$mydatabase,$REMOTE_ADDR,$HTTP_USER_AGENT,$HTTP_REFERER;

   if(empty($isOPERATOR)){$isOPERATOR=false;}
   if(empty($_COOKIE))
      $_COOKIE = array();  
   if(empty($REMOTE_ADDR))
    $REMOTE_ADDR = "";
   if(empty($HTTP_USER_AGENT))
    $HTTP_USER_AGENT = "";
   if(empty($HTTP_REFERER))
       $HTTP_REFERER = "";
       
    // Set Sessions name:
    $sessionname = str_replace(" ","",$sessionname);
    if($serversession){
    	$autostart = @ini_get('session.auto_start');
      if($autostart==0){  
    	  if(!(empty($sessionname)) { 
          session_name($sessionname);
        }
      }        
      $sessionname = session_name();
    }

   $mysession_id = "";   
   $client_ip = get_ipaddress();
   $client_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ( ( !empty($_ENV['HTTP_USER_AGENT']) ? $_ENV['HTTP_USER_AGENT'] : $HTTP_USER_AGENT );
   $client_referer = ( !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ( !empty($_ENV['HTTP_REFERER']) ? $_ENV['HTTP_REFERER'] : $HTTP_REFERER );
   if($CSLH_Config['gethostnames']=="Y"){
     $hostname = gethostbyaddr($client_ip);
   } else {
     $hostname = "host_lookup_not_enabled";  
   }
   $identitystring = get_identitystring($client_ip,$sessionname);
   $mysession_id = detectID($sessionname,$allow_ip_host_sessions,$identitystring);
   $mysession_id = str_replace("'","",$mysession_id);
   // if a cookie has been successfuly set:
   if(isset($_COOKIE[$sessionname])){
    	 if($mysession_id==$_COOKIE[$sessionname]) 
    	   $cookie_set = "Y";
    	 else
    	   $cookie_set = "N";  
   } else {
    	 $cookie_set = "N";
   }
   if(empty($mysession_id))
      $newsession = "Y";
    else
      $newsession = "N";  
  
   // if a sessionID does not exist make one up:
   $unique = $client_ip . date("MdHis");
   if(empty($mysession_id)) 
      $mysession_id = strtolower(md5(uniqid($unique)));
   
   // TOON ANALYTICS: Log session creation with world context
   if (!$ghost_session && $newsession == "Y") {
       require_once("WorldGraphHelper.php");
       
       $actor_id = get_current_actor_id();
       $session_id = get_current_session_id();
       $tab_id = get_current_tab_id();
       
       // Resolve world context
       $world_context = WorldGraphHelper::auto_resolve_world_context();
       $world_id = $world_context ? $world_context['world_id'] : null;
       $world_key = $world_context ? $world_context['world_key'] : null;
       $world_type = $world_context ? $world_context['world_type'] : null;
       
       log_toon_event('session_creation', [
           'client_ip' => $client_ip,
           'client_agent' => $client_agent,
           'client_referer' => $client_referer,
           'hostname' => $hostname,
           'cookie_set' => $cookie_set,
           'world_context' => $world_context
       ], $actor_id, $session_id, $tab_id, $world_id, $world_key, $world_type);
       
       log_toon_event('session_created', [
           'ip' => $client_ip,
           'hostname' => $hostname,
           'user_agent' => $client_agent,
           'referer' => $client_referer,
           'identity_string' => $identitystring,
           'world_context' => $world_context
       ], $actor_id, $session_id, $tab_id, $world_id, $world_key, $world_type);
   }
   
   // if a sessionID does not exist make one up:
   $unique = $client_ip . date("MdHis");
   if(empty($mysession_id)) 
      $mysession_id = strtolower(md5(uniqid($unique)));
   
   // Set the session cookie:
   if($cookiesession && $newsession == "Y"){
    setcookie($sessionname,$mysession_id,time()+($CSLH_Config['sessiontimeout']),"/","",0);
    $cookie_set = "Y";
   }

   // Set the session cookie:
   if($serversession){
     session_name($sessionname);
     session_start();
   }

   return array(
     "HOSTNAME"=>$hostname,
     "IP_ADDR"=>$client_ip,
     "USER_AGENT"=>$client_agent,
     "REFERER"=>$client_referer,
     "SESSIONID"=>$mysession_id,
     "IDENTITY"=>$identitystring,
     "HANDLE"=>$username,
     "NEW_SESSION"=>$newsession,
     "COOKIE_SET"=>$cookie_set
   );
}
