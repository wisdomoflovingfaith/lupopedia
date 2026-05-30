-- Password Resets Table for LUPOPEDIA
-- Stores password reset tokens and expiry information

CREATE TABLE IF NOT EXISTS `lupo_password_resets` (
  `password_reset_id` bigint NOT NULL,
  `auth_user_id` bigint NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiry_ymdhis` bigint(20) NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `created_ymdhis` bigint(20) NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `updated_ymdhis` bigint(20) DEFAULT NULL COMMENT 'YYYYMMDDHHIISS format',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`password_reset_id`),
  UNIQUE KEY `token` (`token`),
  KEY `auth_user_id` (`auth_user_id`),
  KEY `expiry_ymdhis` (`expiry_ymdhis`),
  KEY `created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Password reset tokens for user account recovery';
