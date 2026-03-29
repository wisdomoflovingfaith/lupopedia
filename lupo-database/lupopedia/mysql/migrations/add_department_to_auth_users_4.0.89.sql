-- Migration: Add department_id to lupo_auth_users table
-- Version: 4.0.89
-- Purpose: Enable department-based access control for auth users

-- Add department_id column to existing table
ALTER TABLE lupo_auth_users 
ADD COLUMN department_id BIGINT NOT NULL DEFAULT 0;

-- Add index for performance
CREATE INDEX lupo_auth_users_idx_department ON lupo_auth_users (department_id);

-- Set root user (auth_user_id 1000) to department 0
UPDATE lupo_auth_users 
SET department_id = 0 
WHERE auth_user_id = 1000;

-- Set any existing users without department to department 0
UPDATE lupo_auth_users 
SET department_id = 0 
WHERE department_id IS NULL OR department_id = 0;
