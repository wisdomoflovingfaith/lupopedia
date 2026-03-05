-- 4.0.41_fair_harmonization.sql
-- Purpose: Seeds FAIR compliance metadata and harmonization markers for 4.0.41 evolution.

-- Insert FAIR compliance registry entries for core tables
INSERT INTO `lupo_registry` (`entity_type`, `entity_index`, `entity_table`, `is_kernel`, `metadata`)
VALUES 
('fair_metric', 1, 'lupo_registry', 1, '{"metric": "findable", "status": "active", "description": "Global entity lookup capability"}'),
('fair_metric', 2, 'lupo_registry', 1, '{"metric": "accessible", "status": "active", "description": "Channel-based permission enforcement"}'),
('fair_metric', 3, 'lupo_registry', 1, '{"metric": "interoperable", "status": "active", "description": "TOON schema alignment index"}'),
('fair_metric', 4, 'lupo_registry', 1, '{"metric": "reusable", "status": "active", "description": "Version-locked historical states"}');

-- Register Relation Types from RELATION_REGISTRY.md
INSERT INTO `lupo_registry` (`entity_type`, `entity_index`, `entity_table`, `is_kernel`, `metadata`)
VALUES 
('relation_type', 1, 'lupo_edges', 1, '{"slug": "consumes", "weight": 1.0, "category": "structural"}'),
('relation_type', 2, 'lupo_edges', 1, '{"slug": "references", "weight": 0.5, "category": "structural"}'),
('relation_type', 3, 'lupo_edges', 1, '{"slug": "emotional_dependency", "weight": 0.8, "category": "contextual"}'),
('relation_type', 4, 'lupo_edges', 1, '{"slug": "block_reference", "weight": 0.9, "category": "high_fidelity"}');

-- Audit Log entry
INSERT INTO `lupo_audit_log` (`actor_id`, `action_type`, `description`, `created_at`)
VALUES (1003, 'SYSTEM_UPDATE', 'Seeded FAIR compliance and relation type registry for v4.0.41', NOW());
