CREATE TABLE IF NOT EXISTS lupo_channel_boot_lifecycle (
    lifecycle_id bigint NOT NULL AUTO_INCREMENT,
    channel_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    session_id varchar(128) NOT NULL,
    lifecycle_start_time bigint NOT NULL,
    lifecycle_end_time bigint DEFAULT NULL,
    lifecycle_status varchar(64) NOT NULL DEFAULT 'started',
    lifecycle_type varchar(64) NOT NULL,
    total_channels int NOT NULL DEFAULT 0,
    channels_processed int NOT NULL DEFAULT 0,
    channels_successful int NOT NULL DEFAULT 0,
    channels_failed int NOT NULL DEFAULT 0,
    lifecycle_duration_ms int DEFAULT NULL,
    error_details text DEFAULT NULL,
    performance_metrics text DEFAULT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    PRIMARY KEY (lifecycle_id)
);
