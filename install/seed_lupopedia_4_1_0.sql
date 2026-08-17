-- ============================================================================
-- CONSOLIDATED SEED: Lupopedia (seed_lupopedia_4_1_0.sql)
-- Table prefix: {{prefix}} (replaced at install time; same as install_new_lupopedia.sql).
-- Section order: dependency-safe (registry, then actors, then seed_4.1.0, then remainder).
-- Original per-file seeds preserved under lupo-database/lupopedia/mysql/seed/.
-- ============================================================================

-- >>> BEGIN FILE: seed_4.1.0.sql

-- Consolidated Seed Data for Lupopedia 4.1.0
-- Aligned with install_new_lupopedia.sql ({{prefix}}federation_nodes, {{prefix}}departments, {{prefix}}actors PK actor_name, etc.)
-- MySQL 8+ / MariaDB 10.4+ friendly: no INSERT...VALUES(subquery on target table); minimal window use in derived tables only.

-- Federation node 1 (core)
INSERT INTO {{prefix}}federation_nodes (
    federation_node_id,
    node_type,
    base_url,
    default_department_id,
    node_name,
    description,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    1,
    'primary',
    '/',
    NULL,
    'core',
    'Primary federation node for core system operations',
    20260328120000,
    20260328120000,
    0
);

-- Department 0 (Root)
INSERT INTO {{prefix}}departments (
    department_id,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    0,
    1,
    'Root',
    'Root department with full system access. Department 0 has highest privileges.',
    'system',
    1,
    20260328120000,
    20260328120000,
    0
);

-- Core + coordination actors (human-facing / hybrid operators only in this seed).
-- Not seeded in {{prefix}}actors: ANUBIS, IRIS, ROSE, HEIMDALL, HERMES — system agents (PHP/tools first; LLM supplemental). Their canonical ids remain in lupo-database/.../registry.json for attribution when code writes logs; no {{prefix}}actors row required for fresh install.
-- Root department (0) hybrid operators (web act-as via {{prefix}}actor_departments): captain=wolfie hybrid, lilith, countermeasure.
-- PK is actor_name (wolfie/lilith immutable per convergence doctrine); display name/slug carry captain/Lilith labels.
-- Other personas (lexa..asclepius) remain in {{prefix}}actors but are not seeded into department 0 — add {{prefix}}actor_departments rows when a department scope exists.
INSERT INTO {{prefix}}actors (
    actor_name,
    actor_id,
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    can_login,
    is_agent,
    actor_source_id,
    actor_source_type
) VALUES
('system', 0, 'system', 'system', 'System', 20260328120000, 20260328120000, 1, 0, 0, 0, 0, 'system'),
('wolfie', 1, 'system', 'captain', 'Captain', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('lilith', 2, 'system', 'lilith', 'Lilith', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('lexa', 3, 'system', 'lexa', 'LEXA', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('seshat', 5, 'system', 'seshat', 'SESHAT', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('athena', 6, 'system', 'athena', 'ATHENA', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('maat', 7, 'system', 'maat', 'MAAT', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('themis', 8, 'system', 'themis', 'THEMIS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('thoth', 9, 'system', 'thoth', 'THOTH', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('janus', 10, 'system', 'janus', 'JANUS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('asclepius', 14, 'system', 'asclepius', 'ASCLEPIUS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('countermeasure', 111, 'system', 'countermeasure', 'COUNTERMEASURE', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('kairos', 115, 'system', 'kairos', 'KAIROS', 20260328120000, 20260328120000, 1, 0, 0, 1, 0, 'system')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    actor_type = VALUES(actor_type),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active),
    can_login = VALUES(can_login),
    is_agent = VALUES(is_agent);

-- Adversarial oversight: countermeasure (actor_id=111) is supervised by lilith (actor_id=2).
-- adversarial_role + adversarial_oversight_actor_id were removed from {{prefix}}actors (corrected schema NV2/NV3);
-- relationship now lives in {{prefix}}actor_relationships.
-- [EJECTED 4.1.2] actor_relationships removed - seed data also removed

-- Root department (0): system + three operator hybrids (captain/wolfie, lilith, countermeasure). System agents (ANUBIS, IRIS, etc.) are not department-scoped actors in seed.
-- Web "act as" lists are scoped by {{prefix}}actor_departments (see AuthSessionManager); multiple auth users share the same actor when their departments overlap.
INSERT INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted) VALUES
(1, 0, 0, 'system', 'System', 20260328120000, 20260328120000, 0),
(2, 1, 0, 'hybrid', 'Captain (WOLFIE hybrid)', 20260328120000, 20260328120000, 0),
(3, 2, 0, 'hybrid', 'Lilith (LILITH hybrid)', 20260328120000, 20260328120000, 0),
(4, 111, 0, 'hybrid', 'COUNTERMEASURE hybrid', 20260328120000, 20260328120000, 0);

-- Root operator mapping (auth_user_id 1000): SET then VALUES (avoids MySQL 1093 on INSERT...same-table subquery)
SET @{{prefix}}root_aud_id := (SELECT COALESCE(MAX(auth_user_department_id), 0) + 1 FROM {{prefix}}auth_user_departments);
INSERT INTO {{prefix}}auth_user_departments (auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (@{{prefix}}root_aud_id, 1000, 0, 1, 'administrator', 'Root Administrator', 20260328120000, 20260328120000, 0);

-- Assign department 0 to auth users still missing a department (upgrade / partial seeds).
-- User-variable sequence avoids INSERT...SELECT reading the target table in a subquery (MySQL 1093).
SET @{{prefix}}aud_next := (SELECT COALESCE(MAX(auth_user_department_id), 0) FROM {{prefix}}auth_user_departments);
INSERT INTO {{prefix}}auth_user_departments (auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
SELECT @{{prefix}}aud_next := @{{prefix}}aud_next + 1, au.auth_user_id, 0, 1, 'user', 'User', 20260328120000, 20260328120000, 0
FROM {{prefix}}auth_users au
LEFT JOIN {{prefix}}auth_user_departments aud ON aud.auth_user_id = au.auth_user_id AND aud.is_deleted = 0
WHERE aud.auth_user_department_id IS NULL
AND au.is_active = 1
AND au.is_deleted = 0
ORDER BY au.auth_user_id;

-- {{prefix}}agent_definitions: system coordination agents (is_required=1 — never listed as user actor templates).
-- agent_id values match lupo-database/lupopedia/actors/actor_id/registry.json agents map.
-- No {{prefix}}actors row seeded for these agents (PHP/tools-first; attribution via registry only).
INSERT INTO {{prefix}}agent_definitions (
    agent_id,
    agent_key,
    slug,
    name,
    layer,
    archetype,
    description,
    version,
    is_required,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES
(3,   'rose',     'rose',     'ROSE',     'coordination', 'coordination', 'System agent — dialogue tooling; PHP-first; not a user actor template.', '1.0.0', 1, 20260328120000, 20260328120000, 0),
(15,  'hermes',   'hermes',   'HERMES',   'coordination', 'routing',      'System agent — event routing and messaging; PHP-first.',                  '1.0.0', 1, 20260328120000, 20260328120000, 0),
(16,  'iris',     'iris',     'IRIS',     'coordination', 'integration',  'System agent — interface routing and integration; PHP-first.',            '1.0.0', 1, 20260328120000, 20260328120000, 0),
(19,  'anubis',   'anubis',   'ANUBIS',   'coordination', 'custodian',    'System agent — orphan and header custodian; PHP-first.',                  '1.0.0', 1, 20260328120000, 20260328120000, 0),
(108, 'heimdall', 'heimdall', 'HEIMDALL', 'coordination', 'security',     'System agent — security guardian; PHP-first.',                            '1.0.0', 1, 20260328120000, 20260328120000, 0),
(115, 'kairos',   'kairos',   'KAIROS',   'coordination', 'knowledge',    'System agent — memory consolidation; PHP-first.',                         '1.0.0', 1, 20260328120000, 20260328120000, 0)
ON DUPLICATE KEY UPDATE
    is_required = 1,
    updated_ymdhis = VALUES(updated_ymdhis),
    name = VALUES(name),
    archetype = VALUES(archetype),
    description = VALUES(description);

-- <<< END FILE: seed_4.1.0.sql

-- >>> BEGIN FILE: seed_online_help_and_content.sql

-- =============================================================================
-- Lupopedia Online Help and Content System Seed Data
-- =============================================================================
-- Purpose: Seed user-facing help content, questions, answers, and edges
-- Version: 4.0.97
-- Updated: 2026-04-10
--
-- This seed populates:
-- 1. {{prefix}}contents - Help articles and documentation; captain's log (file-backed) under federation_node/0/captains_log
-- 2. {{prefix}}truth_questions - User questions (canonical table per PRD 42)
-- 3. {{prefix}}truth_answers - Answers to questions (canonical table per PRD 42)
-- 4. {{prefix}}edges - Relationships between content, questions, answers
--
-- NOTE: {{prefix}}questions and {{prefix}}answers do NOT exist in the install schema.
--       The canonical tables are {{prefix}}truth_questions and {{prefix}}truth_answers.
--       This file was corrected in 4.0.96 (previously referenced non-existent tables).
--
-- All content is also mirrored in content/federation_node_id/0/
-- =============================================================================

-- Set timezone for consistent timestamps
SET time_zone = '+00:00';

-- =============================================================================
-- CONTENT: Help Articles and Documentation
-- =============================================================================

-- Getting Started Guide
INSERT INTO {{prefix}}contents (
    content_id, federation_node_id, channel_id, actor_id, title, slug,
    description, body, content_type, format, status, visibility,
    created_ymdhis, updated_ymdhis, utc_cycle, tags, hashtags,
    file_path_from_root
) VALUES
(
    1000001, 0, 0, 10000,
    'Getting Started with Lupopedia',
    'getting-started-guide',
    'Complete guide for new users to understand Lupopedia basics',
    '# Getting Started with Lupopedia

## Overview
Lupopedia is a multi-agent coordination system with comprehensive documentation and decision tracking.

## Key Concepts
- **Actors**: Users, agents, and automated programs in the system
- **Channels**: Organized spaces for discussions and decisions
- **Content**: Documentation, help files, and knowledge base
- **Edges**: Relationships between content items

## First Steps
1. Create your actor profile
2. Join relevant channels
3. Browse help content
4. Ask questions when needed

## Finding Help
- Use the search function to find content
- Browse categories and hashtags
- Ask questions in relevant channels
- Check related content via edges',
    'help_guide', 'markdown', 'published', 'public',
    20260405120000, 20260405120000, 'daily',
    '["getting-started", "basics", "user-guide"]',
    '["#getting-started", "#basics", "#help"]',
    'content/0/help_documentation/1000001_getting-started-guide.md'
);

-- Understanding Actors and Agents
INSERT INTO {{prefix}}contents (
    content_id, federation_node_id, channel_id, actor_id, title, slug,
    description, body, content_type, format, status, visibility,
    created_ymdhis, updated_ymdhis, utc_cycle, tags, hashtags,
    file_path_from_root
) VALUES
(
    1000002, 0, 0, 10000,
    'Understanding Actors and Agents',
    'actors-agents-overview',
    'Learn about different types of actors and how agents work in Lupopedia',
    '# Understanding Actors and Agents

## What are Actors?
Actors are entities that can participate in the Lupopedia system:
- **Human Actors**: Users like you
- **AI Agents**: Automated programs with specific capabilities
- **System Actors**: Background services and processes

## Agent Types
- **Cursor IDE Agent**: Code assistance and development
- **Windsurf Agent**: Documentation and writing
- **Kiro Agent**: Data analysis and insights
- **Lilith Agent**: Auditing and compliance
- **Anubis Agent**: Orphan resolution and data recovery

## Agent Capabilities
Each agent has specific capabilities defined in their configuration:
- Tasks they can perform
- Channels they can access
- Tools they can use
- Decisions they can make

## Working with Agents
- Agents respond to requests in their specialized areas
- They follow system rules and doctrines
- They can create and modify content
- They participate in decision-making processes

## Agent Boundaries
- Agents operate within defined boundaries
- They cannot exceed their authorized capabilities
- All actions are logged and auditable
- They follow constitutional rules',
    'help_guide', 'markdown', 'published', 'public',
    20260405120100, 20260405120100, 'daily',
    '["actors", "agents", "automation"]',
    '["#actors", "#agents", "#automation"]',
    'content/0/help_documentation/1000002_actors-agents-overview.md'
);

-- Channels and Discussions
INSERT INTO {{prefix}}contents (
    content_id, federation_node_id, channel_id, actor_id, title, slug,
    description, body, content_type, format, status, visibility,
    created_ymdhis, updated_ymdhis, utc_cycle, tags, hashtags,
    file_path_from_root
) VALUES
(
    1000003, 0, 0, 10000,
    'Channels and Discussions',
    'channels-discussions-guide',
    'How to use channels for organized discussions and decision-making',
    '# Channels and Discussions

## What are Channels?
Channels are organized spaces for:
- Discussions and conversations
- Decision-making processes
- Project coordination
- Knowledge sharing

## Channel Structure
Each channel contains:
- **Decisions**: Final outcomes with status tracking
- **Questions**: Open issues needing resolution
- **Answers**: Responses to questions
- **Comments**: General discussion

## Decision Process
1. Questions are raised
2. Discussion happens in comments
3. Answers are proposed
4. Decisions are made and documented
5. All items are linked via edges

## Best Practices
- Use clear, descriptive titles
- Follow naming conventions
- Link related items with edges
- Keep discussions focused
- Document decisions clearly',
    'help_guide', 'markdown', 'published', 'public',
    20260405120200, 20260405120200, 'daily',
    '["channels", "discussions", "decisions"]',
    '["#channels", "#discussions", "#decisions"]',
    'content/0/help_documentation/1000003_channels-discussions-guide.md'
),

-- Content and Documentation
(
    1000004, 0, 0, 10000,
    'Content and Documentation',
    'content-documentation-guide',
    'Understanding content types, documentation structure, and how to find information',
    '# Content and Documentation

## Content Types
- **Help Guides**: Step-by-step instructions
- **Reference Docs**: Technical specifications
- **PRDs**: Product Requirements Documents
- **Decisions**: Architectural and project decisions
- **Discussions**: Conversations and Q&A

## Finding Content
1. **Search**: Use keywords and hashtags
2. **Browse**: Navigate categories and collections
3. **Follow Edges**: Explore related content
4. **Ask Questions**: Get help from community and agents

## Content Organization
- Content is organized by channels and topics
- Each item has unique identifiers and metadata
- Relationships are tracked via edges
- Version history is maintained

## Contributing
- Create new content when helpful
- Improve existing documentation
- Ask questions to clarify gaps
- Suggest improvements via comments',
    'help_guide', 'markdown', 'published', 'public',
    20260405120300, 20260405120300, 'daily',
    '["content", "documentation", "search"]',
    '["#content", "#documentation", "#search"]',
    'content/0/help_documentation/1000004_content-documentation-guide.md'
),

-- Using Edges for Relationships
(
    1000005, 0, 0, 10000,
    'Using Edges for Content Relationships',
    'edges-relationships-guide',
    'How edges connect content, questions, answers, and create knowledge networks',
    '# Using Edges for Content Relationships

## What are Edges?
Edges are relationships between items in Lupopedia:
- Link questions to answers
- Connect related content
- Show decision dependencies
- Create knowledge networks

## Edge Types
- **has_answer**: Question → Answer
- **answers**: Answer → Question
- **references**: Content → Content
- **depends_on**: Decision → Decision
- **related_to**: General relationships

## Following Edges
- Click edge links to navigate
- Explore related content automatically
- Discover relevant questions and answers
- Build knowledge pathways

## Creating Edges
- When creating content, add relevant edges
- Link answers to their questions
- Reference related documentation
- Connect dependent decisions

## Benefits
- Automatic relationship discovery
- Improved search relevance
- Knowledge graph navigation
- Contextual recommendations',
    'help_guide', 'markdown', 'published', 'public',
    20260405120400, 20260405120400, 'daily',
    '["edges", "relationships", "navigation"]',
    '["#edges", "#relationships", "#navigation"]',
    'content/0/help_documentation/1000005_edges-relationships-guide.md'
);

-- =============================================================================
-- QUESTIONS: Common User Questions
-- Canonical table: {{prefix}}truth_questions (PRD 42 §3)
-- NOT NULL columns supplied: truth_question_id, target_object_type,
--   target_object_id, question_text, asked_by_actor_id,
--   created_ymdhis, updated_ymdhis
-- =============================================================================

INSERT INTO {{prefix}}truth_questions (
    truth_question_id,
    target_object_type,
    target_object_id,
    question_text,
    asked_by_actor_id,
    question_status,
    is_answered,
    is_deleted,
    created_ymdhis,
    updated_ymdhis
) VALUES
(
    2000001,
    'system', 0,
    'What is Lupopedia and how does it work?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
),
(
    2000002,
    'system', 0,
    'How do I create my actor profile?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
),
(
    2000003,
    'system', 0,
    'Which agent should I use for my task?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
),
(
    2000004,
    'system', 0,
    'How do I find specific content or documentation?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
),
(
    2000005,
    'system', 0,
    'What are edges and how do I use them?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
),
(
    2000006,
    'system', 0,
    'What is the best way to ask questions in channels?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
),
(
    2000007,
    'system', 0,
    'How are decisions made and documented?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
),
(
    2000008,
    'system', 0,
    'What can agents do and what are their limitations?',
    10000, 'open', 0, 0,
    20260405120000, 20260405120000
);

-- =============================================================================
-- ANSWERS: Responses to Questions
-- Canonical table: {{prefix}}truth_answers (PRD 42 §3)
-- NOT NULL columns supplied: truth_answer_id, truth_question_id,
--   answer_text, answered_by_actor_id, created_ymdhis, updated_ymdhis
-- =============================================================================

INSERT INTO {{prefix}}truth_answers (
    truth_answer_id,
    truth_question_id,
    answer_text,
    answered_by_actor_id,
    is_accepted,
    is_deleted,
    created_ymdhis,
    updated_ymdhis
) VALUES
(
    3000001, 2000001,
    'Lupopedia is a multi-agent coordination system that combines human intelligence with AI agents to manage documentation, make decisions, and organize knowledge. It uses channels for discussions, tracks decisions with formal processes, and connects everything through a relationship system called edges.',
    10000, 1, 0,
    20260405120000, 20260405120000
),
(
    3000002, 2000002,
    'Your actor profile is created automatically when you first access the system. You can customize it by adding your preferences, skills, and areas of interest. Your actor ID uniquely identifies you in all system activities.',
    10000, 1, 0,
    20260405120000, 20260405120000
),
(
    3000003, 2000003,
    'Choose agents based on their specialties: Use Cursor for code development, Windsurf for writing, Kiro for data analysis, Lilith for auditing, and Anubis for data recovery. Each agent has specific capabilities and works within defined boundaries.',
    10000, 1, 0,
    20260405120000, 20260405120000
),
(
    3000004, 2000004,
    'Use the search function with keywords, browse by categories, follow hashtag links, or explore edges from related content. The system also suggests relevant content based on your current context and relationships.',
    10000, 1, 0,
    20260405120000, 20260405120000
),
(
    3000005, 2000005,
    'Edges are relationships that connect content, questions, answers, and decisions. They help you navigate between related items, discover relevant information, and understand how different pieces of knowledge connect.',
    10000, 1, 0,
    20260405120000, 20260405120000
),
(
    3000006, 2000006,
    'Ask clear, specific questions in relevant channels. Use descriptive titles, provide context, and include relevant hashtags. The system will help route your question to the right people and agents.',
    10000, 1, 0,
    20260405120000, 20260405120000
),
(
    3000007, 2000007,
    'Decisions are made through a structured process: questions are raised, discussed, answered, and then formalized as decisions with specific statuses (APPROVED, PENDING, etc.). All decisions are documented and linked to related content.',
    10000, 1, 0,
    20260405120000, 20260405120000
),
(
    3000008, 2000008,
    'Agents can perform tasks within their defined capabilities: write code, create documentation, analyze data, audit systems, and more. They follow system rules, cannot access unauthorized areas, and always work within their assigned boundaries.',
    10000, 1, 0,
    20260405120000, 20260405120000
);

-- =============================================================================
-- EDGES: Relationships Between Content, Questions, and Answers
-- object_type values use canonical table short names: 'content',
--   'truth_question', 'truth_answer' (corrected from 'question'/'answer' in 4.0.96)
-- =============================================================================

INSERT INTO {{prefix}}edges (
    edge_id, left_object_type, left_object_id, right_object_type, right_object_id,
    edge_type, edge_category, edge_description, actor_id,
    created_ymdhis, updated_ymdhis, weight_score, semantic_weight,
    flare_weight, flare_reason, flare_auto_generated
) VALUES
-- Content to Content Relationships
(
    4000001, 'content', 1000001, 'content', 1000002,
    'references', 'help_content', 'Getting started leads to understanding actors',
    10000, 20260405120000, 20260405120000, 80, 0.90,
    0.80, 'Sequential learning path', 1
),
(
    4000002, 'content', 1000001, 'content', 1000003,
    'references', 'help_content', 'Getting started includes channels overview',
    10000, 20260405120000, 20260405120000, 80, 0.90,
    0.80, 'Sequential learning path', 1
),
(
    4000003, 'content', 1000002, 'content', 1000005,
    'references', 'help_content', 'Understanding agents includes their relationships',
    10000, 20260405120000, 20260405120000, 70, 0.80,
    0.75, 'Related concepts', 1
),
(
    4000004, 'content', 1000003, 'content', 1000005,
    'references', 'help_content', 'Channel decisions use edges for relationships',
    10000, 20260405120000, 20260405120000, 75, 0.85,
    0.77, 'Implementation detail', 1
),
(
    4000005, 'content', 1000004, 'content', 1000005,
    'references', 'help_content', 'Content discovery uses edge navigation',
    10000, 20260405120000, 20260405120000, 85, 0.90,
    0.82, 'Core relationship', 1
),

-- Question to Answer Relationships
(
    4000011, 'truth_question', 2000001, 'truth_answer', 3000001,
    'has_answer', 'qa', 'What is Lupopedia answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000012, 'truth_question', 2000002, 'truth_answer', 3000002,
    'has_answer', 'qa', 'Actor creation question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000013, 'truth_question', 2000003, 'truth_answer', 3000003,
    'has_answer', 'qa', 'Agent selection question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000014, 'truth_question', 2000004, 'truth_answer', 3000004,
    'has_answer', 'qa', 'Content search question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000015, 'truth_question', 2000005, 'truth_answer', 3000005,
    'has_answer', 'qa', 'Edges explanation question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000016, 'truth_question', 2000006, 'truth_answer', 3000006,
    'has_answer', 'qa', 'Question asking best practices answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000017, 'truth_question', 2000007, 'truth_answer', 3000007,
    'has_answer', 'qa', 'Decision process question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000018, 'truth_question', 2000008, 'truth_answer', 3000008,
    'has_answer', 'qa', 'Agent capabilities question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),

-- Answer to Question Relationships (bidirectional)
(
    4000021, 'truth_answer', 3000001, 'truth_question', 2000001,
    'answers', 'qa', 'Answer about Lupopedia',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Answer to question link', 1
),
(
    4000022, 'truth_answer', 3000002, 'truth_question', 2000002,
    'answers', 'qa', 'Answer about actor creation',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Answer to question link', 1
),
(
    4000023, 'truth_answer', 3000003, 'truth_question', 2000003,
    'answers', 'qa', 'Answer about agent selection',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Answer to question link', 1
),

-- Content to Question Relationships
(
    4000031, 'content', 1000001, 'truth_question', 2000001,
    'related_to', 'help_content', 'Getting started relates to basic Lupopedia question',
    10000, 20260405120000, 20260405120000, 60, 0.70,
    0.65, 'Content-question relationship', 1
),
(
    4000032, 'content', 1000002, 'truth_question', 2000003,
    'related_to', 'help_content', 'Actors guide relates to agent selection question',
    10000, 20260405120000, 20260405120000, 70, 0.75,
    0.70, 'Content-question relationship', 1
),
(
    4000033, 'content', 1000004, 'truth_question', 2000004,
    'related_to', 'help_content', 'Content guide relates to search question',
    10000, 20260405120000, 20260405120000, 80, 0.85,
    0.75, 'Content-question relationship', 1
),
(
    4000034, 'content', 1000005, 'truth_question', 2000005,
    'related_to', 'help_content', 'Edges guide directly relates to edges question',
    10000, 20260405120000, 20260405120000, 95, 0.95,
    0.88, 'Direct content-question match', 1
);

-- =============================================================================
-- CAPTAINS LOG (federation node 0) — file-backed rows + collection "Captains log"
-- Paths: content/federation_node/0/captains_log/*.md
-- Nav: SavedCollectionsService → extra dropdown slug captains-log → tab "References"
-- =============================================================================

SET @{{prefix}}captains_ts := 20260409240000;

INSERT INTO {{prefix}}contents (
    content_id, federation_node_id, channel_id, actor_id, title, slug,
    description, body, content_type, format, status, visibility,
    created_ymdhis, updated_ymdhis, utc_cycle, tags, hashtags,
    storage_type, file_path_from_root
) VALUES
(
    1000061, 0, 0, 1,
    'Captain''s Log — Hello World',
    '20260407_hello_world',
    'Captain''s log (file-backed markdown, federation node 0).', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/20260407_hello_world.md'
),
(
    1000062, 0, 0, 1,
    'Captain''s Log — Entry 001 (complete)',
    'entry_001',
    'Captain''s log (file-backed markdown, federation node 0).', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/entry_001.md'
),
(
    1000063, 0, 0, 1,
    'Captain''s Log — Chronological Trust Ladder',
    '20260408_chronological_trust_ladder',
    'Captain''s log (file-backed markdown, federation node 0).', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/20260408_CHRONOLOGICAL_TRUST_LADDER.md'
),
(
    1000064, 0, 0, 1,
    'Captain''s Log — The Header Wars',
    '20260409_header_wars',
    'Captain''s log (file-backed markdown, federation node 0).', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/20260409_HEADER_WARS.md'
),
(
    1000065, 0, 0, 1,
    'Captain''s Log — The TOON Awakening',
    '20260409_toon_awakening',
    'Captain''s log (file-backed markdown, federation node 0).', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/20260409_TOON_AWAKENING.md'
),
(
    1000066, 0, 0, 1,
    'Captain''s Log — The Unified Theory',
    '20260409_unified_theory',
    'Captain''s log (file-backed markdown, federation node 0).', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/20260409_UNIFIED_THEORY.md'
),
(
    1000067, 0, 0, 1,
    'Captain''s Log — The Department Doctrine',
    '20260409_department_doctrine',
    'Captain''s log (file-backed markdown, federation node 0).', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/20260409_DEPARTMENT_DOCTRINE.md'
),
(
    1000068, 0, 0, 1,
    'Captain''s Log — The Making of a Digital Book',
    '20260409_collections_war',
    'Captain''s log (file-backed markdown, federation node 0). Filename 20260409_COLLECTIONS_WAR.md.', NULL,
    'text/markdown', 'markdown', 'published', 'public',
    @{{prefix}}captains_ts, @{{prefix}}captains_ts, 'daily',
    '["captains_log","federation_node_0"]',
    '["#captains-log"]',
    'file_backed',
    'content/federation_node/0/captains_log/20260409_COLLECTIONS_WAR.md'
)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    slug = VALUES(slug),
    description = VALUES(description),
    updated_ymdhis = VALUES(updated_ymdhis),
    storage_type = VALUES(storage_type),
    file_path_from_root = VALUES(file_path_from_root),
    is_deleted = 0;

-- Nav collections: 0 = Lupopedia docs (Help + References / Captain''s log flyout), 1 = example WHO–WHY tabs.
-- Retire legacy standalone nav collection 1005100 (captain''s log only); maps merged under collection 0.
SET @{{prefix}}nav_seed_ts := 20260409120000;

UPDATE {{prefix}}collections SET is_nav_menu = 0, is_deleted = 1, updated_ymdhis = @{{prefix}}nav_seed_ts, deleted_ymdhis = @{{prefix}}nav_seed_ts WHERE collection_id = 1005100;
UPDATE {{prefix}}collection_tabs SET is_active = 0, is_deleted = 1, updated_ymdhis = @{{prefix}}nav_seed_ts, deleted_ymdhis = @{{prefix}}nav_seed_ts WHERE collection_id = 1005100;
UPDATE {{prefix}}collection_tab_map SET is_deleted = 1, updated_ymdhis = @{{prefix}}nav_seed_ts, deleted_ymdhis = @{{prefix}}nav_seed_ts WHERE collection_tab_id = 1005101;
UPDATE {{prefix}}actor_collections SET is_deleted = 1, updated_ymdhis = @{{prefix}}nav_seed_ts, deleted_ymdhis = @{{prefix}}nav_seed_ts WHERE collection_id = 1005100;

INSERT INTO {{prefix}}collections (
    collection_id, federation_node_id, actor_id, department_id, name, slug, color, description, sort_order,
    properties,
    published_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, parent_id, channel_id, is_nav_menu, nav_icon
) VALUES
(
    0, 1, 1, NULL, 'Lupopedia docs', 'lupopedia-docs', '1565c0',
    'Help articles and references (captain''s log under References).', 0,
    '{"nav_group":"Lupopedia","nav_group_sort":10,"nav_item_label":"Root documentation"}',
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL, NULL, NULL, 1, NULL
),
(
    1, 1, 1, NULL, 'Example collection', 'example-who-what', '4caf50',
    'Example WHO / WHAT / WHERE / WHEN / WHY tabs.', 10,
    '{"nav_group":"Example collections","nav_group_sort":20,"nav_item_label":"5WH"}',
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL, NULL, NULL, 1, NULL
)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    slug = VALUES(slug),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    properties = VALUES(properties),
    is_nav_menu = 1,
    is_deleted = 0,
    deleted_ymdhis = NULL,
    updated_ymdhis = VALUES(updated_ymdhis);

INSERT INTO {{prefix}}collection_tabs (
    collection_tab_id, collection_tab_parent_id, collection_id, federations_node_id, department_id, actor_id,
    sort_order, name, slug, color, description, is_hidden, visibility_rule, tab_type,
    created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis
) VALUES
(
    1005300, NULL, 0, 1, NULL, 1, 10, 'Help', 'help', '2196f3',
    'Online help (help_documentation).', 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
),
(
    1005301, NULL, 0, 1, NULL, 1, 20, 'References', 'references', '4caf50',
    'Reference material; captain''s log flyout.', 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
),
(
    1005302, 1005301, 0, 1, NULL, 1, 10, 'Captain''s log', 'captains-log-tab', '2c3e50',
    'Federation node 0 captain''s log entries.', 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
),
(
    1005320, NULL, 1, 1, NULL, 1, 10, 'WHO', 'who', '4caf50', NULL, 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
),
(
    1005321, NULL, 1, 1, NULL, 1, 20, 'WHAT', 'what', '4caf50', NULL, 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
),
(
    1005322, NULL, 1, 1, NULL, 1, 30, 'WHERE', 'where', '4caf50', NULL, 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
),
(
    1005323, NULL, 1, 1, NULL, 1, 40, 'WHEN', 'when', '4caf50', NULL, 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
),
(
    1005324, NULL, 1, 1, NULL, 1, 50, 'WHY', 'why', '4caf50', NULL, 0, NULL, NULL,
    @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 1, 0, NULL
)
ON DUPLICATE KEY UPDATE
    collection_tab_parent_id = VALUES(collection_tab_parent_id),
    collection_id = VALUES(collection_id),
    name = VALUES(name),
    slug = VALUES(slug),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = 1,
    is_deleted = 0,
    deleted_ymdhis = NULL;

INSERT INTO {{prefix}}actor_collections (
    actor_collection_id, actor_id, collection_id, access_level, created_ymdhis, updated_ymdhis,
    is_deleted, deleted_ymdhis, persistent_identity_json, identity_signature, trust_level,
    emotional_geometry_baseline, doctrine_alignment_version
) VALUES
(
    1005220, 1, 0, 'read', @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL, NULL, NULL, 'standard', NULL, '3.0.0'
),
(
    1005221, 1, 1, 'read', @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL, NULL, NULL, 'standard', NULL, '3.0.0'
)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis),
    is_deleted = 0,
    deleted_ymdhis = NULL;

INSERT INTO {{prefix}}collection_tab_map (
    collection_tab_map_id, collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties,
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
) VALUES
(1000701, 1005300, 1, 'content', 1000001, 10, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000702, 1005300, 1, 'content', 1000002, 20, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000703, 1005300, 1, 'content', 1000003, 30, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000704, 1005300, 1, 'content', 1000004, 40, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000705, 1005300, 1, 'content', 1000005, 50, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000711, 1005302, 1, 'content', 1000061, 10, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000712, 1005302, 1, 'content', 1000062, 20, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000713, 1005302, 1, 'content', 1000063, 30, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000714, 1005302, 1, 'content', 1000064, 40, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000715, 1005302, 1, 'content', 1000065, 50, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000716, 1005302, 1, 'content', 1000066, 60, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000717, 1005302, 1, 'content', 1000067, 70, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL),
(1000718, 1005302, 1, 'content', 1000068, 80, NULL, @{{prefix}}nav_seed_ts, @{{prefix}}nav_seed_ts, 0, NULL)
ON DUPLICATE KEY UPDATE
    collection_tab_id = VALUES(collection_tab_id),
    item_id = VALUES(item_id),
    sort_order = VALUES(sort_order),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_deleted = 0,
    deleted_ymdhis = NULL;

-- =============================================================================
-- INDEX UPDATES FOR PERFORMANCE
-- =============================================================================

-- Update content search indexes
UPDATE {{prefix}}contents SET tags = JSON_SET(tags, '$[0]', 'help_system') WHERE content_id BETWEEN 1000001 AND 1000005;

-- Update hashtag indexes for better search
UPDATE {{prefix}}contents SET hashtags = JSON_ARRAY_APPEND(hashtags, '$', '#help-system') WHERE content_id BETWEEN 1000001 AND 1000005;

-- =============================================================================
-- PRD 01_B Color Registry seed (PRD 90 Color Identity)
-- GroupColor: PRD 90 section 5.2 base register.
-- ColorName: docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv
-- group_color on names is set only when color_name matches a base GroupColor.
-- Other CSS seed words keep group_color empty until Captain assignment.
-- hex6 from CSV hex_color, uppercased, no hash. No hex5. Not agent_colors.
-- =============================================================================

INSERT INTO {{prefix}}color_groups (
    color_group_id, group_color, protocol_short, sort_order,
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, federation_node_id
) VALUES
(1, 'BLACK', 'PRT.LUP', 1, 0, 0, 0, 0, 0),
(2, 'BLUE', 'PRT.LUP', 2, 0, 0, 0, 0, 0),
(3, 'BROWN', 'PRT.LUP', 3, 0, 0, 0, 0, 0),
(4, 'GOLD', 'PRT.LUP', 4, 0, 0, 0, 0, 0),
(5, 'GRAY', 'PRT.LUP', 5, 0, 0, 0, 0, 0),
(6, 'GREEN', 'PRT.LUP', 6, 0, 0, 0, 0, 0),
(7, 'ORANGE', 'PRT.LUP', 7, 0, 0, 0, 0, 0),
(8, 'PINK', 'PRT.LUP', 8, 0, 0, 0, 0, 0),
(9, 'PURPLE', 'PRT.LUP', 9, 0, 0, 0, 0, 0),
(10, 'RED', 'PRT.LUP', 10, 0, 0, 0, 0, 0),
(11, 'SILVER', 'PRT.LUP', 11, 0, 0, 0, 0, 0),
(12, 'WHITE', 'PRT.LUP', 12, 0, 0, 0, 0, 0),
(13, 'YELLOW', 'PRT.LUP', 13, 0, 0, 0, 0, 0)
ON DUPLICATE KEY UPDATE
    group_color = VALUES(group_color),
    protocol_short = VALUES(protocol_short),
    sort_order = VALUES(sort_order),
    is_deleted = 0,
    deleted_ymdhis = 0,
    updated_ymdhis = VALUES(updated_ymdhis);

INSERT INTO {{prefix}}color_names (
    color_name_id, protocol_short, group_color, color_name, hex6, gold_mark,
    field_type, iso_language, source_table, usage_count, actor_hex,
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, federation_node_id
) VALUES
(101, 'PRT.LUP', 'YELLOW', 'yellow', 'FFFF00', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(102, 'PRT.LUP', 'BLUE', 'blue', '0000FF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(103, 'PRT.LUP', '', 'ocean', '1E90FF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(104, 'PRT.LUP', '', 'grass', '0C871B', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(105, 'PRT.LUP', '', 'aliceblue', 'F0F8FF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(106, 'PRT.LUP', '', 'antiquewhite', 'FAEBD7', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(107, 'PRT.LUP', '', 'aqua', '00FFFF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(108, 'PRT.LUP', '', 'aquamarine', '7FFFD4', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(109, 'PRT.LUP', '', 'azure', 'F0FFFF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(110, 'PRT.LUP', '', 'beige', 'F5F5DC', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(111, 'PRT.LUP', '', 'bisque', 'FFE4C4', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(112, 'PRT.LUP', 'BLACK', 'black', '000000', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(113, 'PRT.LUP', '', 'blanchedalmond', 'FFEBCD', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(114, 'PRT.LUP', '', 'blueviolet', '8A2BE2', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(115, 'PRT.LUP', 'BROWN', 'brown', 'A52A2A', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(116, 'PRT.LUP', '', 'burlywood', 'DEB887', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(117, 'PRT.LUP', '', 'cadetblue', '5F9EA0', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(118, 'PRT.LUP', '', 'chartreuse', '7FFF00', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(119, 'PRT.LUP', '', 'chocolate', 'D2691E', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(120, 'PRT.LUP', '', 'coral', 'FF7F50', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(121, 'PRT.LUP', '', 'cornflowerblue', '6495ED', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(122, 'PRT.LUP', '', 'cornsilk', 'FFF8DC', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(123, 'PRT.LUP', '', 'crimson', 'DC143C', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(124, 'PRT.LUP', '', 'cyan', '00FFFF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(125, 'PRT.LUP', '', 'darkblue', '00008B', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(126, 'PRT.LUP', '', 'darkcyan', '008B8B', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(127, 'PRT.LUP', '', 'darkgoldenrod', 'B8860B', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(128, 'PRT.LUP', '', 'darkgray', 'A9A9A9', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(129, 'PRT.LUP', '', 'darkgreen', '006400', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(130, 'PRT.LUP', '', 'darkkhaki', 'BDB76B', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(131, 'PRT.LUP', '', 'darkmagenta', '8B008B', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(132, 'PRT.LUP', '', 'darkolivegreen', '556B2F', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(133, 'PRT.LUP', '', 'darkorange', 'FF8C00', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(134, 'PRT.LUP', '', 'darkorchid', '9932CC', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(135, 'PRT.LUP', '', 'darkred', '8B0000', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(136, 'PRT.LUP', '', 'darksalmon', 'E9967A', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(137, 'PRT.LUP', '', 'darkseagreen', '8FBC8F', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(138, 'PRT.LUP', '', 'darkslateblue', '483D8B', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(139, 'PRT.LUP', '', 'darkslategray', '2F4F4F', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(140, 'PRT.LUP', '', 'darkturquoise', '00CED1', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(141, 'PRT.LUP', '', 'darkviolet', '9400D3', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(142, 'PRT.LUP', '', 'deeppink', 'FF1493', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(143, 'PRT.LUP', '', 'deepskyblue', '00BFFF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(144, 'PRT.LUP', '', 'dimgray', '696969', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(145, 'PRT.LUP', '', 'dodgerblue', '1E90FF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(146, 'PRT.LUP', '', 'firebrick', 'B22222', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(147, 'PRT.LUP', '', 'floralwhite', 'FFFAF0', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(148, 'PRT.LUP', '', 'forestgreen', '228B22', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(149, 'PRT.LUP', '', 'fuchsia', 'FF00FF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(150, 'PRT.LUP', '', 'gainsboro', 'DCDCDC', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(151, 'PRT.LUP', '', 'ghostwhite', 'F8F8FF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(152, 'PRT.LUP', 'GOLD', 'gold', 'FFD700', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(153, 'PRT.LUP', '', 'goldenrod', 'DAA520', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(154, 'PRT.LUP', 'GRAY', 'gray', '808080', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(155, 'PRT.LUP', 'GREEN', 'green', '008000', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(156, 'PRT.LUP', '', 'greenyellow', 'ADFF2F', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(157, 'PRT.LUP', '', 'honeydew', 'F0FFF0', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(158, 'PRT.LUP', '', 'hotpink', 'FF69B4', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(159, 'PRT.LUP', '', 'indianred', 'CD5C5C', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(160, 'PRT.LUP', '', 'indigo', '4B0082', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(161, 'PRT.LUP', '', 'ivory', 'FFFFF0', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(162, 'PRT.LUP', '', 'khaki', 'F0E68C', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(163, 'PRT.LUP', '', 'lavender', 'E6E6FA', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(164, 'PRT.LUP', '', 'lavenderblush', 'FFF0F5', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(165, 'PRT.LUP', '', 'lawngreen', '7CFC00', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(166, 'PRT.LUP', '', 'lemonchiffon', 'FFFACD', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(167, 'PRT.LUP', '', 'lightblue', 'ADD8E6', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(168, 'PRT.LUP', '', 'lightcoral', 'F08080', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(169, 'PRT.LUP', '', 'lightcyan', 'E0FFFF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(170, 'PRT.LUP', '', 'lightgoldenrodyellow', 'FAFAD2', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(171, 'PRT.LUP', '', 'lightgray', 'D3D3D3', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(172, 'PRT.LUP', '', 'lightgreen', '90EE90', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(173, 'PRT.LUP', '', 'lightpink', 'FFB6C1', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(174, 'PRT.LUP', '', 'lightsalmon', 'FFA07A', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(175, 'PRT.LUP', '', 'lightseagreen', '20B2AA', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(176, 'PRT.LUP', '', 'lightskyblue', '87CEFA', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(177, 'PRT.LUP', '', 'lightslategray', '778899', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(178, 'PRT.LUP', '', 'lightsteelblue', 'B0C4DE', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(179, 'PRT.LUP', '', 'lightyellow', 'FFFFE0', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(180, 'PRT.LUP', '', 'lime', '00FF00', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(181, 'PRT.LUP', '', 'limegreen', '32CD32', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(182, 'PRT.LUP', '', 'linen', 'FAF0E6', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(183, 'PRT.LUP', '', 'magenta', 'FF00FF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(184, 'PRT.LUP', '', 'maroon', '800000', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(185, 'PRT.LUP', '', 'mediumaquamarine', '66CDAA', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(186, 'PRT.LUP', '', 'mediumblue', '0000CD', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(187, 'PRT.LUP', '', 'mediumorchid', 'BA55D3', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(188, 'PRT.LUP', '', 'mediumpurple', '9370DB', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(189, 'PRT.LUP', '', 'mediumseagreen', '3CB371', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(190, 'PRT.LUP', '', 'mediumslateblue', '7B68EE', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(191, 'PRT.LUP', '', 'mediumspringgreen', '00FA9A', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(192, 'PRT.LUP', '', 'mediumturquoise', '48D1CC', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(193, 'PRT.LUP', '', 'mediumvioletred', 'C71585', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(194, 'PRT.LUP', '', 'midnightblue', '191970', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(195, 'PRT.LUP', '', 'mintcream', 'F5FFFA', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(196, 'PRT.LUP', '', 'mistyrose', 'FFE4E1', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(197, 'PRT.LUP', '', 'moccasin', 'FFE4B5', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(198, 'PRT.LUP', '', 'navajowhite', 'FFDEAD', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(199, 'PRT.LUP', '', 'navy', '000080', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(200, 'PRT.LUP', '', 'oldlace', 'FDF5E6', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(201, 'PRT.LUP', '', 'olive', '808000', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(202, 'PRT.LUP', '', 'olivedrab', '6B8E23', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(203, 'PRT.LUP', 'ORANGE', 'orange', 'FFA500', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(204, 'PRT.LUP', '', 'orangered', 'FF4500', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(205, 'PRT.LUP', '', 'orchid', 'DA70D6', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(206, 'PRT.LUP', '', 'palegoldenrod', 'EEE8AA', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(207, 'PRT.LUP', '', 'palegreen', '98FB98', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(208, 'PRT.LUP', '', 'paleturquoise', 'AFEEEE', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(209, 'PRT.LUP', '', 'palevioletred', 'DB7093', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(210, 'PRT.LUP', '', 'papayawhip', 'FFEFD5', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(211, 'PRT.LUP', '', 'peachpuff', 'FFDAB9', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(212, 'PRT.LUP', '', 'peru', 'CD853F', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(213, 'PRT.LUP', 'PINK', 'pink', 'FFC0CB', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(214, 'PRT.LUP', '', 'plum', 'DDA0DD', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(215, 'PRT.LUP', '', 'powderblue', 'B0E0E6', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(216, 'PRT.LUP', 'PURPLE', 'purple', '800080', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(217, 'PRT.LUP', '', 'rebeccapurple', '663399', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(218, 'PRT.LUP', 'RED', 'red', 'FF0000', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(219, 'PRT.LUP', '', 'rosybrown', 'BC8F8F', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(220, 'PRT.LUP', '', 'royalblue', '4169E1', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(221, 'PRT.LUP', '', 'saddlebrown', '8B4513', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(222, 'PRT.LUP', '', 'salmon', 'FA8072', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(223, 'PRT.LUP', '', 'sandybrown', 'F4A460', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(224, 'PRT.LUP', '', 'seagreen', '2E8B57', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(225, 'PRT.LUP', '', 'seashell', 'FFF5EE', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(226, 'PRT.LUP', '', 'sienna', 'A0522D', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(227, 'PRT.LUP', 'SILVER', 'silver', 'C0C0C0', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(228, 'PRT.LUP', '', 'skyblue', '87CEEB', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(229, 'PRT.LUP', '', 'slateblue', '6A5ACD', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(230, 'PRT.LUP', '', 'slategray', '708090', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(231, 'PRT.LUP', '', 'snow', 'FFFAFA', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(232, 'PRT.LUP', '', 'springgreen', '00FF7F', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(233, 'PRT.LUP', '', 'steelblue', '4682B4', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(234, 'PRT.LUP', '', 'tan', 'D2B48C', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(235, 'PRT.LUP', '', 'teal', '008080', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(236, 'PRT.LUP', '', 'thistle', 'D8BFD8', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(237, 'PRT.LUP', '', 'tomato', 'FF6347', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(238, 'PRT.LUP', '', 'turquoise', '40E0D0', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(239, 'PRT.LUP', '', 'violet', 'EE82EE', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(240, 'PRT.LUP', '', 'wheat', 'F5DEB3', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(241, 'PRT.LUP', 'WHITE', 'white', 'FFFFFF', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(242, 'PRT.LUP', '', 'whitesmoke', 'F5F5F5', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0),
(243, 'PRT.LUP', '', 'yellowgreen', '9ACD32', '', 'node', 'EN', 'seed', 0, '808080', 0, 0, 0, 0, 0)
ON DUPLICATE KEY UPDATE
    protocol_short = VALUES(protocol_short),
    group_color = VALUES(group_color),
    color_name = VALUES(color_name),
    hex6 = VALUES(hex6),
    gold_mark = VALUES(gold_mark),
    field_type = VALUES(field_type),
    iso_language = VALUES(iso_language),
    source_table = VALUES(source_table),
    usage_count = VALUES(usage_count),
    actor_hex = VALUES(actor_hex),
    is_deleted = 0,
    deleted_ymdhis = 0,
    updated_ymdhis = VALUES(updated_ymdhis);

-- =============================================================================
-- COMPLETION MESSAGE
-- =============================================================================

SELECT 'Online Help and Content System seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}contents WHERE content_id BETWEEN 1000001 AND 1000005
UNION ALL
SELECT 'Questions seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}truth_questions WHERE truth_question_id BETWEEN 2000001 AND 2000008
UNION ALL
SELECT 'Answers seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}truth_answers WHERE truth_answer_id BETWEEN 3000001 AND 3000008
UNION ALL
SELECT 'Edges seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}edges WHERE edge_id BETWEEN 4000001 AND 4000034
UNION ALL
SELECT 'Captains log (file-backed) seeded' AS status,
       COUNT(*) AS content_items FROM {{prefix}}contents WHERE content_id BETWEEN 1000061 AND 1000068
UNION ALL
SELECT 'Nav collections 0-1 (Help, References, WHO-WHY) seeded' AS status,
       COUNT(*) AS content_items FROM {{prefix}}collections WHERE collection_id IN (0, 1) AND is_deleted = 0
UNION ALL
SELECT 'Color groups seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}color_groups WHERE is_deleted = 0
UNION ALL
SELECT 'Color names seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}color_names WHERE is_deleted = 0;

-- <<< END FILE: seed_online_help_and_content.sql
