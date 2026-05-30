-- Create lupo_auth_user_departments mapping table
-- Allows auth users to belong to multiple departments

CREATE TABLE lupo_auth_user_departments (
    auth_user_department_id bigint NOT NULL,
    auth_user_id bigint NOT NULL,
    department_id bigint NOT NULL,
    is_primary tinyint NOT NULL DEFAULT 0,
    role_key varchar(64) DEFAULT NULL,
    title varchar(64) DEFAULT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    updated_ymdhis bigint NOT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT NULL,
    PRIMARY KEY (auth_user_department_id)
);

-- Indexes for performance
CREATE INDEX lupo_auth_user_departments_idx_auth_user ON lupo_auth_user_departments (auth_user_id);
CREATE INDEX lupo_auth_user_departments_idx_department ON lupo_auth_user_departments (department_id);
CREATE INDEX lupo_auth_user_departments_idx_primary ON lupo_auth_user_departments (auth_user_id, is_primary);
