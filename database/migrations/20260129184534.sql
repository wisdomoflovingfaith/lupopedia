-- Migration: Crafty Syntax escalation fields for dialog threads
-- Purpose: Store escalation assignments and timestamps for AI -> human handoff.
-- Doctrine: No foreign keys, no unsigned ints, no triggers, MySQL 8 safe.

ALTER TABLE lupo_dialog_threads
    ADD COLUMN IF NOT EXISTS escalated_to_operator_id bigint
    COMMENT 'Operator assigned during escalation (Crafty Syntax handoff)';

ALTER TABLE lupo_dialog_threads
    ADD COLUMN IF NOT EXISTS escalation_reason varchar(255)
    COMMENT 'Reason for escalation (confusion, conflict, policy, etc.)';

ALTER TABLE lupo_dialog_threads
    ADD COLUMN IF NOT EXISTS escalation_timestamp bigint
    COMMENT 'UTC YYYYMMDDHHMMSS when escalation was triggered';
