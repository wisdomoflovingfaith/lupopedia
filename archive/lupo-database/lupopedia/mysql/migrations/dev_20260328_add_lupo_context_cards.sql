-- One-time migration: Add lupo_context_cards table (2026-03-28)

CREATE TABLE IF NOT EXISTS lupo_context_cards (
  context_card_id bigint NOT NULL AUTO_INCREMENT,
  context_id int NOT NULL,
  card_title varchar(255) NOT NULL,
  instruction_text varchar(280) NOT NULL,
  card_type varchar(50) DEFAULT 'instruction',
  display_order int DEFAULT 0,
  metadata json,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  INDEX idx_context_id (context_id),
  INDEX idx_card_type (card_type),
  INDEX idx_display_order (display_order),
  PRIMARY KEY (context_card_id)
);
