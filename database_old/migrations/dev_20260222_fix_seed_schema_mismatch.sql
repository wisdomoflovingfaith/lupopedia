-- FILE: database/migrations/dev_20260222_fix_seed_schema_mismatch.sql
-- TYPE: sql
-- Purpose: Fix schema mismatch between install_new_lupopedia.sql and seed_lupopedia.sql
-- This migration adds missing columns to support the seed data format

-- Fix lupo_registry table - add registry_id and entity_index columns
ALTER TABLE lupo_registry 
ADD COLUMN registry_id bigint NOT NULL DEFAULT 0 AFTER registry_id,
ADD COLUMN entity_index bigint NOT NULL DEFAULT 0 AFTER entity_index_id;

-- Fix lupo_actor_channels table - add created_by_actor_id column
ALTER TABLE lupo_actor_channels 
ADD COLUMN created_by_actor_id bigint NOT NULL DEFAULT 0 AFTER actor_id;

-- Fix lupo_actor_departments table - add role_key column
ALTER TABLE lupo_actor_departments 
ADD COLUMN role_key varchar(64) DEFAULT NULL AFTER department_id;

-- Fix lupo_dialog_threads table - add thread_id column
ALTER TABLE lupo_dialog_threads 
ADD COLUMN thread_id bigint NOT NULL DEFAULT 0 AFTER dialog_thread_id;

-- Fix lupo_dialog_doctrine table - add message_id column
ALTER TABLE lupo_dialog_doctrine 
ADD COLUMN message_id bigint NOT NULL DEFAULT 0 AFTER dialog_message_id;

-- Fix lupo_actor_meta table - add meta_id column
ALTER TABLE lupo_actor_meta 
ADD COLUMN meta_id bigint NOT NULL DEFAULT 0 AFTER actor_meta_id;

-- Fix lupo_system_events table - add event_id column
ALTER TABLE lupo_system_events 
ADD COLUMN event_id bigint NOT NULL DEFAULT 0 AFTER system_event_id;

-- Fix lupo_contents table - add content column
ALTER TABLE lupo_contents 
ADD COLUMN content text AFTER body;

-- lupo_dialog_channels already has file_source column, no fix needed
