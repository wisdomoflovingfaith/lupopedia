<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Session Identity System                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Session Identity Functions - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

/**
  * Builds session identity string from IP address and session name
  * LEGACY FUNCTION - DO NOT MODIFY
  * Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md
  *
  * @param string $ipaddress the ipaddress
  *
  * @return string the identity string.
  */
function get_identitystring($ipaddress,$sessionname="SESSIONID"){

   $hostip_array = explode(".",$ipaddress); 
   $identitystring = "";
   if(!(empty($hostip_array[0]))){ $identitystring .= $hostip_array[0] . "."; }
   if(!(empty($hostip_array[1]))){ $identitystring .= $hostip_array[1] . "."; }
   if(!(empty($hostip_array[2]))){ $identitystring .= $hostip_array[2]; } 
   $identitystring .= "-" . $sessionname;
   return $identitystring;
}

/**
  * finds user identity : hostname, ip, referer, user agent and starts session 
  * LEGACY FUNCTION - DO NOT MODIFY
  * Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md
  *
  * @param string $PHPSESSID session Id 
  * @param string $sessionname - name of this session..   
  * @param bool   $allow_ip_host_sessions -  finding session id based on ip and host (identity)
  * @param bool   $serversession - create a server session using built in PHP $_SESSION 
  * @param bool   $cookiesession - cookie based session 
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
  *  + USER_AGENT: Communication protocol to use (tcp, unix etc.)
  *  +    REFERER: Host specification (hostname[:port])
  *  +  SESSIONID: Database to use on DBMS server
  *  +   IDENTITY: User name for login
  *  +     HANDLE: Current handle or username
  *  +NEW_SESSION: enum (Y,N) Y if this is a new session N if this is not a new session.
  *  + COOKIE_SET: enum (Y,N) Y if session is set in a cookie N if this is not.
  */ 
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
    	  if(!(empty($sessionname))){ 
          session_name($sessionname);
        }
      }        
      $sessionname = session_name();
    }

   $mysession_id = "";   
   $client_ip = get_ipaddress();
   $client_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : ( ( !empty($_ENV['HTTP_USER_AGENT']) ) ? $_ENV['HTTP_USER_AGENT'] : $HTTP_USER_AGENT );
   $client_referer = ( !empty($_SERVER['HTTP_REFERER']) ) ? $_SERVER['HTTP_REFERER'] : ( ( !empty($_ENV['HTTP_REFERER']) ) ? $_ENV['HTTP_REFERER'] : $HTTP_REFERER );
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
   if(!(empty($hostname)))
     $hostname = "host_lookup_failed";
   
   // NEW SESSION DETECTION
   $new_session = "Y";
   if($mysession_id == $PHPSESSID){
     $new_session = "N";
   }
   
   // If this is a ghost session then do not write anything to the database.
   if($ghost_session){
     $new_session = "N";
   }
   
   // Set the session ID and start the session
   if($serversession){
     session_id($mysession_id);
     session_start();  
   }
   
   // If this is a new session write the session to the database
   if(($new_session=="Y") && ($ghost_session==false)){
     $query = "INSERT INTO livehelp_users (sessionid,identitystring,ipaddress,hostname,user_agent,referer,username,onchannel,visits,lastaction) VALUES ('$mysession_id','$identitystring','$client_ip','$hostname','$client_agent','$client_referer','$username','0','1',".time().")";
     $mydatabase->query($query);
   } else {
     // update the session heartbeat.
     $query = "UPDATE livehelp_users SET lastaction=".time()." WHERE sessionid='$mysession_id'";
     $mydatabase->query($query);
   }
   
   // Set the cookie if needed
   if(($cookiesession==true) && ($cookie_set=="Y")){
     // set cookie for 1 year:
     $cookietime = time() + 31536000;
     setcookie($sessionname,$mysession_id,$cookietime);
   }
   
   // Set globals for use by other functions
   $identity = array();
   $identity['HOSTNAME'] = $hostname;
   $identity['IP_ADDR'] = $client_ip;
   $identity['USER_AGENT'] = $client_agent;
   $identity['REFERER'] = $client_referer;
   $identity['SESSIONID'] = $mysession_id;
   $identity['IDENTITY'] = $identitystring;
   $identity['HANDLE'] = $username;
   $identity['NEW_SESSION'] = $new_session;
   $identity['COOKIE_SET'] = $cookie_set;
   $identity['is_operator'] = $isOPERATOR;
   $identity['sessionname'] = $sessionname;
   $identity['serversession'] = $serversession;
   $identity['cookiesession'] = $cookiesession;
   $identity['ghost_session'] = $ghost_session;
   $REMOTE_ADDR = $client_ip;
   $HTTP_USER_AGENT = $client_agent;
   $HTTP_REFERER = $client_referer;
   return $identity;
}

?>
