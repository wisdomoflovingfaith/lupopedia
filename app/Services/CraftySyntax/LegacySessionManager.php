<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Session Manager                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// Doctrine: All DB access via PDO_DB (fetchRow, insert, update, delete) with bound parameters.
// Table: {prefix}sessions per livehelp_sessions_migration.md (livehelp_sessions DROPPED -> lupo_sessions).

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Custom Session Handler - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

class SessionManager {

   var $life_time;

   function SessionManager() {

      // Read the maxlifetime setting from PHP
      $this->life_time = get_cfg_var("session.gc_maxlifetime");

      // Register this object as the session handler
      session_set_save_handler(
        array( &$this, "open" ),
        array( &$this, "close" ),
        array( &$this, "read" ),
        array( &$this, "write"),
        array( &$this, "destroy"),
        array( &$this, "gc" )
      );

   }

   function open( $save_path, $session_name ) {
      global $sess_save_path;
      $sess_save_path = $save_path;
      return true;
   }

   function close() {
      global $mydatabase;
      if (isset($mydatabase) && method_exists($mydatabase, 'close_connect')) {
          $mydatabase->close_connect();
      }
      return true;
   }

   function read( $id ) {
      global $mydatabase;
      $data = '';
      if (!isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
          return $data;
      }
      $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
      $sessions_table = $table_prefix . 'sessions';
      $now_ymdhis = (int) gmdate('YmdHis');
      $row = $mydatabase->fetchRow(
          "SELECT session_data FROM {$sessions_table} WHERE session_id = :sid AND (expires_ymdhis IS NULL OR expires_ymdhis > :now)",
          array('sid' => $id, 'now' => $now_ymdhis)
      );
      if ($row && isset($row['session_data'])) {
          $data = (string) $row['session_data'];
      }
      return $data;
   }

   function write( $id, $data ) {
      global $mydatabase;
      if (!isset($mydatabase) || !($mydatabase instanceof PDO_DB)) {
          return true;
      }
      $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
      $sessions_table = $table_prefix . 'sessions';
      $time = time() + $this->life_time;
      $now_ymdhis = (int) gmdate('YmdHis');
      $expires_ymdhis = (int) gmdate('YmdHis', $time);
      $existing = $mydatabase->fetchRow(
          "SELECT session_id FROM {$sessions_table} WHERE session_id = :sid",
          array('sid' => $id)
      );
      if ($existing) {
          $mydatabase->update(
              $sessions_table,
              array(
                  'session_data' => $data,
                  'last_seen_ymdhis' => $now_ymdhis,
                  'expires_ymdhis' => $expires_ymdhis,
                  'updated_ymdhis' => $now_ymdhis,
              ),
              'session_id = :sid',
              array('sid' => $id)
          );
      } else {
          $mydatabase->insert($sessions_table, array(
              'session_id' => $id,
              'federation_node_id' => 1,
              'actor_id' => 0,
              'ip_address' => '',
              'user_agent' => '',
              'session_data' => $data,
              'last_seen_ymdhis' => $now_ymdhis,
              'expires_ymdhis' => $expires_ymdhis,
              'created_ymdhis' => $now_ymdhis,
              'updated_ymdhis' => $now_ymdhis,
          ));
      }
      return true;
   }

   function destroy( $id ) {
      global $mydatabase;
      if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
          $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
          $sessions_table = $table_prefix . 'sessions';
          $mydatabase->delete($sessions_table, 'session_id = :sid', array('sid' => $id));
      }
      return true;
   }

   function gc() {
      global $mydatabase;
      if (isset($mydatabase) && $mydatabase instanceof PDO_DB) {
          $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
          $sessions_table = $table_prefix . 'sessions';
          $now_ymdhis = (int) gmdate('YmdHis');
          $mydatabase->delete($sessions_table, 'expires_ymdhis IS NOT NULL AND expires_ymdhis < :now', array('now' => $now_ymdhis));
      }
      return true;
   }

}
?>
