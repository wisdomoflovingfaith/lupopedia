<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Authentication Entry Point  ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Authentication Entry Point - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once("admin_common.php");

if( (mobi_detect()) && empty($UNTRUSTED['fullsite']) ){
	  Header("Location: mobile/");
	  exit;
	}

// error number:
if(!(isset($UNTRUSTED['err']))){ $err = 0; } else { $err = intval($UNTRUSTED['err']); }

// proccess login:
if(!(isset($UNTRUSTED['proccess']))){ $UNTRUSTED['proccess'] = "no"; }
if($UNTRUSTED['proccess'] == "yes"){
      if (validate_user($UNTRUSTED['myusername'], $UNTRUSTED['mypassword'], $identity)) {
        if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
          $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
          $sessions_table = $table_prefix . 'sessions';
          $twentyminutes = (int) date('YmdHis', mktime(date('H'), date('i') + 20, date('s'), date('m'), date('d'), date('Y')));
          $mydatabase->update($sessions_table, ['last_seen_ymdhis' => $twentyminutes, 'updated_ymdhis' => $twentyminutes], 'session_id = :sid', ['sid' => $identity['SESSIONID']]);
        }
?>
