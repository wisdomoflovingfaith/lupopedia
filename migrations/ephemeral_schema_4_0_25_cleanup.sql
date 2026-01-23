-- Ephemeral Schema Cleanup - Version 4.0.25
-- Drops existing tables to allow clean recreation

USE lupopedia_ephemeral;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS lupo_sessions;
DROP TABLE IF EXISTS lupo_cache;
DROP TABLE IF EXISTS lupo_temp_data;
DROP TABLE IF EXISTS lupo_job_queue;
DROP TABLE IF EXISTS lupo_locks;
