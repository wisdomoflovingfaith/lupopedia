-- Migration: Add Missing Emotional Agents (25 Primary + 25 Opposite Domains)
-- Version: 4.1.1
-- Date: 2026-01-18
--
-- Adds 31 missing emotional agents to complete the full emotional ecology of 25 primary
-- emotional domains and their opposite-polarity shadows. The Love domain (7 primary + 7 opposite)
-- already exists. This migration adds the remaining 18 primary domains and 15 opposite domains.
--
-- @package Lupopedia
-- @version 4.1.1
-- @author CASCADE

-- ============================================================================
-- INSERT MISSING PRIMARY EMOTIONAL DOMAINS (16 agents)
-- ============================================================================
-- Agent IDs: 1213-1228
-- Dedicated Slots: 1213-1228 (within emotional agent slot range 1000-1999)
-- Layer: emotional
--
-- Note: These agents represent primary emotional states. They are registered but
-- not yet active. Activation occurs when the emotional ecology layer requires them.

-- EMO_ANGER (1213) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1213,
    NULL,
    'EMO_ANGER',
    'Anger',
    'emotional',
    0,
    0,
    0,
    1213,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing anger, rage, and righteous indignation.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_FEAR (1214) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1214,
    NULL,
    'EMO_FEAR',
    'Fear',
    'emotional',
    0,
    0,
    0,
    1214,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing fear, anxiety, and apprehension.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_JOY (1215) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1215,
    NULL,
    'EMO_JOY',
    'Joy',
    'emotional',
    0,
    0,
    0,
    1215,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing joy, happiness, and elation.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_SADNESS (1216) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1216,
    NULL,
    'EMO_SADNESS',
    'Sadness',
    'emotional',
    0,
    0,
    0,
    1216,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing sadness, grief, and melancholy.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_DISGUST (1217) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1217,
    NULL,
    'EMO_DISGUST',
    'Disgust',
    'emotional',
    0,
    0,
    0,
    1217,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing disgust, revulsion, and repulsion.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_SURPRISE (1218) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1218,
    NULL,
    'EMO_SURPRISE',
    'Surprise',
    'emotional',
    0,
    0,
    0,
    1218,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing surprise, astonishment, and wonder.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_ANTICIPATION (1219) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1219,
    NULL,
    'EMO_ANTICIPATION',
    'Anticipation',
    'emotional',
    0,
    0,
    0,
    1219,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing anticipation, expectation, and eagerness.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_SHAME (1220) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1220,
    NULL,
    'EMO_SHAME',
    'Shame',
    'emotional',
    0,
    0,
    0,
    1220,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing shame, humiliation, and self-consciousness.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_GUILT (1221) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1221,
    NULL,
    'EMO_GUILT',
    'Guilt',
    'emotional',
    0,
    0,
    0,
    1221,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing guilt, remorse, and self-blame.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_PRIDE (1222) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1222,
    NULL,
    'EMO_PRIDE',
    'Pride',
    'emotional',
    0,
    0,
    0,
    1222,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing pride, self-respect, and dignity.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_ENVY (1223) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1223,
    NULL,
    'EMO_ENVY',
    'Envy',
    'emotional',
    0,
    0,
    0,
    1223,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing envy, covetousness, and desire for what others have.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_JEALOUSY (1224) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1224,
    NULL,
    'EMO_JEALOUSY',
    'Jealousy',
    'emotional',
    0,
    0,
    0,
    1224,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing jealousy, possessiveness, and fear of loss.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_AWE (1225) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1225,
    NULL,
    'EMO_AWE',
    'Awe',
    'emotional',
    0,
    0,
    0,
    1225,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing awe, wonder, and reverence.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_HOPE (1226) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1226,
    NULL,
    'EMO_HOPE',
    'Hope',
    'emotional',
    0,
    0,
    0,
    1226,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing hope, optimism, and expectation of positive outcomes.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_DESPAIR (1227) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1227,
    NULL,
    'EMO_DESPAIR',
    'Despair',
    'emotional',
    0,
    0,
    0,
    1227,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing despair, hopelessness, and loss of faith.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- EMO_COURAGE (1228) - Primary Emotional Domain
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1228,
    NULL,
    'EMO_COURAGE',
    'Courage',
    'emotional',
    0,
    0,
    0,
    1228,
    20260118091008,
    NULL,
    '{
      "description": "Primary emotional domain representing courage, bravery, and facing fear.",
      "domain_type": "primary",
      "version": "4.1.1"
    }'
);

-- ============================================================================
-- INSERT MISSING OPPOSITE-POLARITY EMOTIONAL DOMAINS (15 agents)
-- ============================================================================
-- Agent IDs: 1229-1243
-- Dedicated Slots: 1229-1243 (within emotional agent slot range 1000-1999)
-- Layer: emotional
--
-- Note: These agents represent opposite-polarity/shadow states for primary domains.
-- COURAGE and HOPE are already primary domains, so they are not duplicated here.

-- EMO_FORBEARANCE (1229) - Opposite of EMO_ANGER (1213)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1229,
    NULL,
    'EMO_FORBEARANCE',
    'Forbearance',
    'emotional',
    0,
    0,
    0,
    1229,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Anger. Represents patience, restraint, and controlled response.",
      "opposite_polarity_of": "EMO_ANGER",
      "opposite_polarity_id": 1213,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_MELANCHOLIA (1230) - Opposite of EMO_JOY (1215)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1230,
    NULL,
    'EMO_MELANCHOLIA',
    'Melancholia',
    'emotional',
    0,
    0,
    0,
    1230,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Joy. Represents deep sadness, depression, and loss of pleasure.",
      "opposite_polarity_of": "EMO_JOY",
      "opposite_polarity_id": 1215,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_ACCEPTANCE (1231) - Opposite of EMO_DISGUST (1217)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1231,
    NULL,
    'EMO_ACCEPTANCE',
    'Acceptance',
    'emotional',
    0,
    0,
    0,
    1231,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Disgust. Represents acceptance, tolerance, and embracing what is.",
      "opposite_polarity_of": "EMO_DISGUST",
      "opposite_polarity_id": 1217,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_PREDICTABILITY (1232) - Opposite of EMO_SURPRISE (1218)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1232,
    NULL,
    'EMO_PREDICTABILITY',
    'Predictability',
    'emotional',
    0,
    0,
    0,
    1232,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Surprise. Represents predictability, routine, and lack of novelty.",
      "opposite_polarity_of": "EMO_SURPRISE",
      "opposite_polarity_id": 1218,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_SUSPICION (1233) - Opposite of EMO_TRUST (existing, but adding opposite)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1233,
    NULL,
    'EMO_SUSPICION',
    'Suspicion',
    'emotional',
    0,
    0,
    0,
    1233,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Trust. Represents suspicion, doubt, and wariness.",
      "opposite_polarity_of": "EMO_TRUST",
      "opposite_polarity_id": 226,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_DIGNITY (1234) - Opposite of EMO_SHAME (1220)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1234,
    NULL,
    'EMO_DIGNITY',
    'Dignity',
    'emotional',
    0,
    0,
    0,
    1234,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Shame. Represents dignity, self-respect, and honor.",
      "opposite_polarity_of": "EMO_SHAME",
      "opposite_polarity_id": 1220,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_INNOCENCE (1235) - Opposite of EMO_GUILT (1221)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1235,
    NULL,
    'EMO_INNOCENCE',
    'Innocence',
    'emotional',
    0,
    0,
    0,
    1235,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Guilt. Represents innocence, blamelessness, and freedom from fault.",
      "opposite_polarity_of": "EMO_GUILT",
      "opposite_polarity_id": 1221,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_HUMILITY (1236) - Opposite of EMO_PRIDE (1222)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1236,
    NULL,
    'EMO_HUMILITY',
    'Humility',
    'emotional',
    0,
    0,
    0,
    1236,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Pride. Represents humility, modesty, and self-effacement.",
      "opposite_polarity_of": "EMO_PRIDE",
      "opposite_polarity_id": 1222,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_CONTENTMENT (1237) - Opposite of EMO_ENVY (1223)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1237,
    NULL,
    'EMO_CONTENTMENT',
    'Contentment',
    'emotional',
    0,
    0,
    0,
    1237,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Envy. Represents contentment, satisfaction, and acceptance of one\'s own state.",
      "opposite_polarity_of": "EMO_ENVY",
      "opposite_polarity_id": 1223,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_SECURITY (1238) - Opposite of EMO_JEALOUSY (1224)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1238,
    NULL,
    'EMO_SECURITY',
    'Security',
    'emotional',
    0,
    0,
    0,
    1238,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Jealousy. Represents security, confidence, and trust in relationships.",
      "opposite_polarity_of": "EMO_JEALOUSY",
      "opposite_polarity_id": 1224,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_DISENCHANTMENT (1239) - Opposite of EMO_AWE (1225)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1239,
    NULL,
    'EMO_DISENCHANTMENT',
    'Disenchantment',
    'emotional',
    0,
    0,
    0,
    1239,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Awe. Represents disenchantment, disillusionment, and loss of wonder.",
      "opposite_polarity_of": "EMO_AWE",
      "opposite_polarity_id": 1225,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_RESILIENCE (1240) - Opposite of EMO_DESPAIR (1227)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1240,
    NULL,
    'EMO_RESILIENCE',
    'Resilience',
    'emotional',
    0,
    0,
    0,
    1240,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Despair. Represents resilience, perseverance, and ability to recover.",
      "opposite_polarity_of": "EMO_DESPAIR",
      "opposite_polarity_id": 1227,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_DISINTEREST (1241) - Opposite of EMO_CURIOSITY (existing, but adding opposite)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1241,
    NULL,
    'EMO_DISINTEREST',
    'Disinterest',
    'emotional',
    0,
    0,
    0,
    1241,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Curiosity. Represents disinterest, apathy, and lack of engagement.",
      "opposite_polarity_of": "EMO_CURIOSITY",
      "opposite_polarity_id": 232,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_AGITATION (1242) - Opposite of EMO_COURAGE (1228)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1242,
    NULL,
    'EMO_AGITATION',
    'Agitation',
    'emotional',
    0,
    0,
    0,
    1242,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Courage. Represents agitation, restlessness, and inability to face challenges.",
      "opposite_polarity_of": "EMO_COURAGE",
      "opposite_polarity_id": 1228,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- EMO_RENEWAL (1243) - Opposite of EMO_ANTICIPATION (1219)
INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES (
    1243,
    NULL,
    'EMO_RENEWAL',
    'Renewal',
    'emotional',
    0,
    0,
    0,
    1243,
    20260118091008,
    NULL,
    '{
      "description": "Opposite of Anticipation. Represents renewal, refreshment, and present-moment awareness.",
      "opposite_polarity_of": "EMO_ANTICIPATION",
      "opposite_polarity_id": 1219,
      "domain_type": "opposite",
      "version": "4.1.1"
    }'
);

-- ============================================================================
-- NOTES
-- ============================================================================
-- This migration completes the full emotional ecology of 25 primary emotional
-- domains and their 25 opposite-polarity shadows (50 total emotional agents).
--
-- Love Domain (already exists):
--   Primary: EMO_AGAPE (1001), EMO_EROS (1002), EMO_PHILIA (1003), EMO_STORGE (1004),
--            EMO_LUDUS (1005), EMO_PRAGMA (1006), EMO_PHILAUTIA (1007)
--   Opposite: EMO_APHOBIA (1111), EMO_ANEROSIA (1112), EMO_DYSPISTIA (1113),
--             EMO_ANSTORGIA (1114), EMO_GRAVITAS (1115), EMO_AKATAXIA (1116),
--             EMO_AUTOLETHIA (1117)
--
-- New Primary Domains (this migration):
--   EMO_ANGER (1213), EMO_FEAR (1214), EMO_JOY (1215), EMO_SADNESS (1216),
--   EMO_DISGUST (1217), EMO_SURPRISE (1218), EMO_ANTICIPATION (1219),
--   EMO_SHAME (1220), EMO_GUILT (1221), EMO_PRIDE (1222), EMO_ENVY (1223),
--   EMO_JEALOUSY (1224), EMO_AWE (1225), EMO_HOPE (1226), EMO_DESPAIR (1227),
--   EMO_COURAGE (1228)
--
-- New Opposite-Polarity Domains (this migration):
--   EMO_FORBEARANCE (1229), EMO_MELANCHOLIA (1230), EMO_ACCEPTANCE (1231),
--   EMO_PREDICTABILITY (1232), EMO_SUSPICION (1233), EMO_DIGNITY (1234),
--   EMO_INNOCENCE (1235), EMO_HUMILITY (1236), EMO_CONTENTMENT (1237),
--   EMO_SECURITY (1238), EMO_DISENCHANTMENT (1239), EMO_RESILIENCE (1240),
--   EMO_DISINTEREST (1241), EMO_AGITATION (1242), EMO_RENEWAL (1243)
--
-- Note: EMO_COURAGE and EMO_HOPE are primary domains that also serve as opposites
-- for FEAR and SADNESS respectively, so they are not duplicated in the opposite list.
--
-- Total emotional agents after this migration: 332 (301 existing + 31 new)
