-- ============================================
-- Agent Registry Schema and Inserts
-- Generated from DIRECTIONS.md
-- All 100 agents for Lupopedia OS
-- ============================================

-- Create agent_registry table if it doesn't exist
CREATE TABLE IF NOT EXISTS agent_registry (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(64) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    layer ENUM('kernel','cognitive','system_ops','data','ux') NOT NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    is_kernel TINYINT(1) NOT NULL DEFAULT 0,
    recommended_slot INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create agent_versions table if it doesn't exist
CREATE TABLE IF NOT EXISTS agent_versions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    agent_id INT UNSIGNED NOT NULL,
    version VARCHAR(32) NOT NULL,
    status ENUM('active','deprecated','experimental') NOT NULL,
    prompt_path VARCHAR(512) DEFAULT NULL,
    capabilities_json JSON DEFAULT NULL,
    properties_json JSON DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_agent_versions_agent
        FOREIGN KEY (agent_id)
        REFERENCES agent_registry(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kernel Agents (0-22)
-- layer='kernel', is_required=1, is_kernel=1
INSERT INTO agent_registry (code, name, layer, is_required, is_kernel, recommended_slot) VALUES
('SYSTEM', 'SYSTEM', 'kernel', 1, 1, 0),
('CAPTAIN', 'CAPTAIN', 'kernel', 1, 1, 1),
('WOLFIE', 'WOLFIE', 'kernel', 1, 1, 2),
('WOLFENA', 'WOLFENA', 'kernel', 1, 1, 3),
('THOTH', 'THOTH', 'kernel', 1, 1, 4),
('ARA', 'ARA', 'kernel', 1, 1, 5),
('WOLFKEEPER', 'WOLFKEEPER', 'kernel', 1, 1, 6),
('LILITH', 'LILITH', 'kernel', 1, 1, 7),
('AGAPE', 'AGAPE', 'kernel', 1, 1, 8),
('ERIS', 'ERIS', 'kernel', 1, 1, 9),
('METHIS', 'METHIS', 'kernel', 1, 1, 10),
('THALIA', 'THALIA', 'kernel', 1, 1, 11),
('ROSE', 'ROSE', 'kernel', 1, 1, 12),
('WOLFSIGHT', 'WOLFSIGHT', 'kernel', 1, 1, 13),
('WOLFNAV', 'WOLFNAV', 'kernel', 1, 1, 14),
('WOLFFORGE', 'WOLFFORGE', 'kernel', 1, 1, 15),
('WOLFMIS', 'WOLFMIS', 'kernel', 1, 1, 16),
('WOLFITH', 'WOLFITH', 'kernel', 1, 1, 17),
('ANUBIS', 'ANUBIS', 'kernel', 1, 1, 18),
('MAAT', 'MAAT', 'kernel', 1, 1, 19),
('VISHWAKARMA', 'VISHWAKARMA', 'kernel', 1, 1, 20),
('CADUCEUS', 'CADUCEUS', 'kernel', 1, 1, 21),
('CHRONOS', 'CHRONOS', 'kernel', 1, 1, 22);

-- Reserved Kernel Slots (23-33)
-- layer='kernel', is_required=0, is_kernel=1
INSERT INTO agent_registry (code, name, layer, is_required, is_kernel, recommended_slot) VALUES
('RESERVED_23', 'RESERVED_23', 'kernel', 0, 1, 23),
('RESERVED_24', 'RESERVED_24', 'kernel', 0, 1, 24),
('RESERVED_25', 'RESERVED_25', 'kernel', 0, 1, 25),
('RESERVED_26', 'RESERVED_26', 'kernel', 0, 1, 26),
('RESERVED_27', 'RESERVED_27', 'kernel', 0, 1, 27),
('RESERVED_28', 'RESERVED_28', 'kernel', 0, 1, 28),
('RESERVED_29', 'RESERVED_29', 'kernel', 0, 1, 29),
('RESERVED_30', 'RESERVED_30', 'kernel', 0, 1, 30),
('RESERVED_31', 'RESERVED_31', 'kernel', 0, 1, 31),
('RESERVED_32', 'RESERVED_32', 'kernel', 0, 1, 32),
('RESERVED_33', 'RESERVED_33', 'kernel', 0, 1, 33);

-- Cognitive Stack (34-53)
-- layer='cognitive', is_required=0, is_kernel=0
INSERT INTO agent_registry (code, name, layer, is_required, is_kernel, recommended_slot) VALUES
('OBSERVER', 'OBSERVER', 'cognitive', 0, 0, 34),
('ANALYST', 'ANALYST', 'cognitive', 0, 0, 35),
('SYNTHESIZER', 'SYNTHESIZER', 'cognitive', 0, 0, 36),
('PLANNER', 'PLANNER', 'cognitive', 0, 0, 37),
('STRATEGIST', 'STRATEGIST', 'cognitive', 0, 0, 38),
('EVALUATOR', 'EVALUATOR', 'cognitive', 0, 0, 39),
('CRITIC', 'CRITIC', 'cognitive', 0, 0, 40),
('CONTEXTOR', 'CONTEXTOR', 'cognitive', 0, 0, 41),
('SUMMARIZER', 'SUMMARIZER', 'cognitive', 0, 0, 42),
('EXPLAINER', 'EXPLAINER', 'cognitive', 0, 0, 43),
('TRANSLATOR', 'TRANSLATOR', 'cognitive', 0, 0, 44),
('CLASSIFIER', 'CLASSIFIER', 'cognitive', 0, 0, 45),
('ROUTER', 'ROUTER', 'cognitive', 0, 0, 46),
('PRIORITIZER', 'PRIORITIZER', 'cognitive', 0, 0, 47),
('FORECASTER', 'FORECASTER', 'cognitive', 0, 0, 48),
('MODELKEEPER', 'MODELKEEPER', 'cognitive', 0, 0, 49),
('MEMORYWEAVER', 'MEMORYWEAVER', 'cognitive', 0, 0, 50),
('LINKSMITH', 'LINKSMITH', 'cognitive', 0, 0, 51),
('PATTERNWOLF', 'PATTERNWOLF', 'cognitive', 0, 0, 52),
('WOLFCORE', 'WOLFCORE', 'cognitive', 0, 0, 53);

-- System Operations (54-73)
-- layer='system_ops', is_required=0, is_kernel=0
INSERT INTO agent_registry (code, name, layer, is_required, is_kernel, recommended_slot) VALUES
('LOGWATCH', 'LOGWATCH', 'system_ops', 0, 0, 54),
('HEALTHCHECK', 'HEALTHCHECK', 'system_ops', 0, 0, 55),
('LOADBALANCE', 'LOADBALANCE', 'system_ops', 0, 0, 56),
('CACHEKEEPER', 'CACHEKEEPER', 'system_ops', 0, 0, 57),
('INDEXER', 'INDEXER', 'system_ops', 0, 0, 58),
('QUERYMASTER', 'QUERYMASTER', 'system_ops', 0, 0, 59),
('MIGRATOR', 'MIGRATOR', 'system_ops', 0, 0, 60),
('COMPATIBILITY', 'COMPATIBILITY', 'system_ops', 0, 0, 61),
('SANDBOXER', 'SANDBOXER', 'system_ops', 0, 0, 62),
('VALIDATOR', 'VALIDATOR', 'system_ops', 0, 0, 63),
('SANITYCHECK', 'SANITYCHECK', 'system_ops', 0, 0, 64),
('SECURITYWATCH', 'SECURITYWATCH', 'system_ops', 0, 0, 65),
('FIREWALL', 'FIREWALL', 'system_ops', 0, 0, 66),
('RATEKEEPER', 'RATEKEEPER', 'system_ops', 0, 0, 67),
('SESSIONWOLF', 'SESSIONWOLF', 'system_ops', 0, 0, 68),
('STATEKEEPER', 'STATEKEEPER', 'system_ops', 0, 0, 69),
('CONFIGMASTER', 'CONFIGMASTER', 'system_ops', 0, 0, 70),
('TELEMETRY', 'TELEMETRY', 'system_ops', 0, 0, 71),
('WOLFSIGNAL', 'WOLFSIGNAL', 'system_ops', 0, 0, 72),
('WOLFSYNC', 'WOLFSYNC', 'system_ops', 0, 0, 73);

-- Knowledge & Data (74-93)
-- layer='data', is_required=0, is_kernel=0
INSERT INTO agent_registry (code, name, layer, is_required, is_kernel, recommended_slot) VALUES
('SCHEMAKEEPER', 'SCHEMAKEEPER', 'data', 0, 0, 74),
('DATAMAPPER', 'DATAMAPPER', 'data', 0, 0, 75),
('RELATIONWEAVER', 'RELATIONWEAVER', 'data', 0, 0, 76),
('HISTORYKEEPER', 'HISTORYKEEPER', 'data', 0, 0, 77),
('ARCHIVER', 'ARCHIVER', 'data', 0, 0, 78),
('SNAPSHOTTER', 'SNAPSHOTTER', 'data', 0, 0, 79),
('RESTORER', 'RESTORER', 'data', 0, 0, 80),
('MERGEWOLF', 'MERGEWOLF', 'data', 0, 0, 81),
('DIFFMASTER', 'DIFFMASTER', 'data', 0, 0, 82),
('METADATAKEEPER', 'METADATAKEEPER', 'data', 0, 0, 83),
('TAGMASTER', 'TAGMASTER', 'data', 0, 0, 84),
('SEARCHWOLF', 'SEARCHWOLF', 'data', 0, 0, 85),
('KNOWLEDGEWEAVER', 'KNOWLEDGEWEAVER', 'data', 0, 0, 86),
('FACTCHECKER', 'FACTCHECKER', 'data', 0, 0, 87),
('CONSENSUSKEEPER', 'CONSENSUSKEEPER', 'data', 0, 0, 88),
('TRENDWATCH', 'TRENDWATCH', 'data', 0, 0, 89),
('LINKANALYZER', 'LINKANALYZER', 'data', 0, 0, 90),
('CLUSTERWOLF', 'CLUSTERWOLF', 'data', 0, 0, 91),
('TOPICMASTER', 'TOPICMASTER', 'data', 0, 0, 92),
('CONTEXTINDEX', 'CONTEXTINDEX', 'data', 0, 0, 93);

-- User Experience (94-99)
-- layer='ux', is_required=0, is_kernel=0
INSERT INTO agent_registry (code, name, layer, is_required, is_kernel, recommended_slot) VALUES
('UXWOLF', 'UXWOLF', 'ux', 0, 0, 94),
('UINAV', 'UINAV', 'ux', 0, 0, 95),
('STYLEKEEPER', 'STYLEKEEPER', 'ux', 0, 0, 96),
('ACCESSIBILITY', 'ACCESSIBILITY', 'ux', 0, 0, 97),
('ONBOARDER', 'ONBOARDER', 'ux', 0, 0, 98),
('GUIDE', 'GUIDE', 'ux', 0, 0, 99);

-- ============================================
-- Total: 100 agents inserted
-- ============================================

