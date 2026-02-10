-- FILE: database/migrations/dev_20260205_doctrine_alignment_phase2.sql
-- TYPE: sql
-- Purpose: One-time dev migration. Align remaining DB columns with doctrine so TOONs pass doctrine check.
-- Doctrine: no UNSIGNED (SQL), no display widths (tinyint(1)->tinyint), no timestamp/datetime (Temporal §5).
-- Run this after dev_20260204_fix_schema_alignment.sql, then regenerate TOONs.
--
-- 1. PK columns: drop UNSIGNED, keep AUTO_INCREMENT
-- 2. Display width: tinyint(1) -> tinyint (crafty_syntax tables)
-- 3. timestamp/CURRENT_TIMESTAMP -> bigint (lupo_crafty_user_mapping)
--

-- PK: drop UNSIGNED, keep AUTO_INCREMENT
ALTER TABLE lupo_actor_meta MODIFY COLUMN `actor_meta_id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_aliases MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_anubis_deletion_log MODIFY COLUMN `anubis_deletion_id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_calibration_impacts MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_cip_analytics MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_cip_propagation_tracking MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_cip_trends MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_contexts MODIFY COLUMN `context_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_dialog_channels MODIFY COLUMN `channel_id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_doctrine_evolution_audit MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_doctrine_refinements MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_emotional_geometry_calibrations MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_human_history_meta MODIFY COLUMN `meta_id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_metrics_archive_legacy MODIFY COLUMN `metric_id` int NOT NULL;
ALTER TABLE lupo_multi_agent_critique_sync MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_pack_role_registry MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_semantic_overlays MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_semantic_paths MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_truth_questions MODIFY COLUMN `truth_question_id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_unified_visits MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE lupo_unified_websites MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT;

-- Display width: tinyint(1) -> tinyint
ALTER TABLE lupo_crafty_syntax_chat_mod_departments MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
ALTER TABLE lupo_crafty_syntax_chat_mod_departments MODIFY COLUMN `is_default` tinyint NOT NULL DEFAULT 0;
ALTER TABLE lupo_crafty_syntax_chat_questions MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Temporal §5: timestamp/CURRENT_TIMESTAMP -> bigint (existing rows get 0; backfill from backup if needed)
ALTER TABLE lupo_crafty_user_mapping MODIFY COLUMN `created_at` bigint NOT NULL DEFAULT 0;
ALTER TABLE lupo_crafty_user_mapping MODIFY COLUMN `updated_at` bigint NOT NULL DEFAULT 0;
