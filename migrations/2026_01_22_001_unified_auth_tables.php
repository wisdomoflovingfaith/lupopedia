<?php

/**
 * DEPRECATED: Laravel migration. This project does not use Laravel or Illuminate.
 *
 * Schema for auth/sessions is defined in:
 *   - database/migrations/install_new_lupopedia.sql (lupo_sessions, lupo_crafty_user_mapping, lupo_auth_audit_log)
 *   - database/migrations/add_system_context_to_lupo_sessions.sql
 *
 * Do not run this file. Use plain SQL in database/migrations/ and run via PDO.
 */

trigger_error('Laravel migrations are deprecated; use database/migrations/*.sql and PDO', E_USER_DEPRECATED);
