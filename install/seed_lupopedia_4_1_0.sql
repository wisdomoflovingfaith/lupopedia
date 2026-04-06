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
    node_base_url,
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

UPDATE {{prefix}}actors SET adversarial_role = 'red_team', adversarial_oversight_actor_id = 2, updated_ymdhis = 20260328120000 WHERE actor_name = 'countermeasure' AND (is_deleted = 0 OR is_deleted IS NULL);

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

-- {{prefix}}agents: system agents with is_internal_only=1 (never listed in AuthSessionManager::getAvailableAgents / "create actor from agent").
-- agent_id values match lupo-database/lupopedia/actors/actor_id/registry.json agents map.
INSERT INTO {{prefix}}agents (
    agent_id,
    agent_key,
    agent_name,
    archetype,
    description,
    version,
    is_global_authority,
    is_internal_only,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES
(3, 'rose', 'ROSE', 'coordination', 'System agent — dialogue tooling; PHP-first; not a user actor template.', '1.0', 0, 1, 20260328120000, 20260328120000, 0),
(15, 'hermes', 'HERMES', 'routing', 'System agent — event routing and messaging; PHP-first.', '1.0', 0, 1, 20260328120000, 20260328120000, 0),
(16, 'iris', 'IRIS', 'integration', 'System agent — interface routing and integration; PHP-first.', '1.0', 0, 1, 20260328120000, 20260328120000, 0),
(19, 'anubis', 'ANUBIS', 'custodian', 'System agent — orphan and header custodian; PHP-first.', '1.0', 0, 1, 20260328120000, 20260328120000, 0),
(108, 'heimdall', 'HEIMDALL', 'security', 'System agent — security guardian; PHP-first.', '1.0', 0, 1, 20260328120000, 20260328120000, 0),
(115, 'kairos', 'KAIROS', 'knowledge', 'System agent — memory consolidation; PHP-first.', '1.0', 0, 1, 20260328120000, 20260328120000, 0)
ON DUPLICATE KEY UPDATE
    is_internal_only = 1,
    updated_ymdhis = VALUES(updated_ymdhis),
    agent_name = VALUES(agent_name),
    archetype = VALUES(archetype),
    description = VALUES(description);

-- <<< END FILE: seed_4.1.0.sql

-- >>> BEGIN FILE: seed_online_help_and_content.sql

-- =============================================================================
-- Lupopedia Online Help and Content System Seed Data
-- =============================================================================
-- Purpose: Seed user-facing help content, questions, answers, and edges
-- Version: 4.0.89
-- Created: 2026-04-05
-- 
-- This seed populates:
-- 1. {{prefix}}contents - Help articles and documentation
-- 2. {{prefix}}questions - User questions
-- 3. {{prefix}}answers - Answers to questions  
-- 4. {{prefix}}edges - Relationships between content, questions, answers
-- 
-- All content is also mirrored in lupo-content/federation_node_id/0/
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
    'lupo-content/0/help_documentation/1000001_getting-started-guide.md'
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
    'lupo-content/0/help_documentation/1000002_actors-agents-overview.md'
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
    'lupo-content/0/help_documentation/1000003_channels-discussions-guide.md'
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
    'lupo-content/0/help_documentation/1000004_content-documentation-guide.md'
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
    'lupo-content/0/help_documentation/1000005_edges-relationships-guide.md'
);

-- =============================================================================
-- QUESTIONS: Common User Questions
-- =============================================================================

INSERT INTO {{prefix}}questions (
    question_id, slug, question_text, actor_id, 
    created_ymdhis, updated_ymdhis
) VALUES 
(
    2000001, 'what-is-lupopedia',
    'What is Lupopedia and how does it work?',
    10000, 20260405120000, 20260405120000
),
(
    2000002, 'how-to-create-actor',
    'How do I create my actor profile?',
    10000, 20260405120000, 20260405120000
),
(
    2000003, 'which-agent-to-use',
    'Which agent should I use for my task?',
    10000, 20260405120000, 20260405120000
),
(
    2000004, 'how-to-find-content',
    'How do I find specific content or documentation?',
    10000, 20260405120000, 20260405120000
),
(
    2000005, 'what-are-edges',
    'What are edges and how do I use them?',
    10000, 20260405120000, 20260405120000
),
(
    2000006, 'how-to-ask-questions',
    'What is the best way to ask questions in channels?',
    10000, 20260405120000, 20260405120000
),
(
    2000007, 'decision-making-process',
    'How are decisions made and documented?',
    10000, 20260405120000, 20260405120000
),
(
    2000008, 'agent-capabilities',
    'What can agents do and what are their limitations?',
    10000, 20260405120000, 20260405120000
);

-- =============================================================================
-- ANSWERS: Responses to Questions
-- =============================================================================

INSERT INTO {{prefix}}answers (
    answer_id, question_id, answer_text, actor_id,
    created_ymdhis, updated_ymdhis
) VALUES 
(
    3000001, 2000001,
    'Lupopedia is a multi-agent coordination system that combines human intelligence with AI agents to manage documentation, make decisions, and organize knowledge. It uses channels for discussions, tracks decisions with formal processes, and connects everything through a relationship system called edges.',
    10000, 20260405120000, 20260405120000
),
(
    3000002, 2000002,
    'Your actor profile is created automatically when you first access the system. You can customize it by adding your preferences, skills, and areas of interest. Your actor ID uniquely identifies you in all system activities.',
    10000, 20260405120000, 20260405120000
),
(
    3000003, 2000003,
    'Choose agents based on their specialties: Use Cursor for code development, Windsurf for writing, Kiro for data analysis, Lilith for auditing, and Anubis for data recovery. Each agent has specific capabilities and works within defined boundaries.',
    10000, 20260405120000, 20260405120000
),
(
    3000004, 2000004,
    'Use the search function with keywords, browse by categories, follow hashtag links, or explore edges from related content. The system also suggests relevant content based on your current context and relationships.',
    10000, 20260405120000, 20260405120000
),
(
    3000005, 2000005,
    'Edges are relationships that connect content, questions, answers, and decisions. They help you navigate between related items, discover relevant information, and understand how different pieces of knowledge connect.',
    10000, 20260405120000, 20260405120000
),
(
    3000006, 2000006,
    'Ask clear, specific questions in relevant channels. Use descriptive titles, provide context, and include relevant hashtags. The system will help route your question to the right people and agents.',
    10000, 20260405120000, 20260405120000
),
(
    3000007, 2000007,
    'Decisions are made through a structured process: questions are raised, discussed, answered, and then formalized as decisions with specific statuses (APPROVED, PENDING, etc.). All decisions are documented and linked to related content.',
    10000, 20260405120000, 20260405120000
),
(
    3000008, 2000008,
    'Agents can perform tasks within their defined capabilities: write code, create documentation, analyze data, audit systems, and more. They follow system rules, cannot access unauthorized areas, and always work within their assigned boundaries.',
    10000, 20260405120000, 20260405120000
);

-- =============================================================================
-- EDGES: Relationships Between Content, Questions, and Answers
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
    4000011, 'question', 2000001, 'answer', 3000001,
    'has_answer', 'qa', 'What is Lupopedia answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000012, 'question', 2000002, 'answer', 3000002,
    'has_answer', 'qa', 'Actor creation question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000013, 'question', 2000003, 'answer', 3000003,
    'has_answer', 'qa', 'Agent selection question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000014, 'question', 2000004, 'answer', 3000004,
    'has_answer', 'qa', 'Content search question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000015, 'question', 2000005, 'answer', 3000005,
    'has_answer', 'qa', 'Edges explanation question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000016, 'question', 2000006, 'answer', 3000006,
    'has_answer', 'qa', 'Question asking best practices answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000017, 'question', 2000007, 'answer', 3000007,
    'has_answer', 'qa', 'Decision process question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),
(
    4000018, 'question', 2000008, 'answer', 3000008,
    'has_answer', 'qa', 'Agent capabilities question answered',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Direct question-answer pair', 1
),

-- Answer to Question Relationships (bidirectional)
(
    4000021, 'answer', 3000001, 'question', 2000001,
    'answers', 'qa', 'Answer about Lupopedia',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Answer to question link', 1
),
(
    4000022, 'answer', 3000002, 'question', 2000002,
    'answers', 'qa', 'Answer about actor creation',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Answer to question link', 1
),
(
    4000023, 'answer', 3000003, 'question', 2000003,
    'answers', 'qa', 'Answer about agent selection',
    10000, 20260405120000, 20260405120000, 100, 1.00,
    0.95, 'Answer to question link', 1
),

-- Content to Question Relationships
(
    4000031, 'content', 1000001, 'question', 2000001,
    'related_to', 'help_content', 'Getting started relates to basic Lupopedia question',
    10000, 20260405120000, 20260405120000, 60, 0.70,
    0.65, 'Content-question relationship', 1
),
(
    4000032, 'content', 1000002, 'question', 2000003,
    'related_to', 'help_content', 'Actors guide relates to agent selection question',
    10000, 20260405120000, 20260405120000, 70, 0.75,
    0.70, 'Content-question relationship', 1
),
(
    4000033, 'content', 1000004, 'question', 2000004,
    'related_to', 'help_content', 'Content guide relates to search question',
    10000, 20260405120000, 20260405120000, 80, 0.85,
    0.75, 'Content-question relationship', 1
),
(
    4000034, 'content', 1000005, 'question', 2000005,
    'related_to', 'help_content', 'Edges guide directly relates to edges question',
    10000, 20260405120000, 20260405120000, 95, 0.95,
    0.88, 'Direct content-question match', 1
);

-- =============================================================================
-- INDEX UPDATES FOR PERFORMANCE
-- =============================================================================

-- Update content search indexes
UPDATE {{prefix}}contents SET tags = JSON_SET(tags, '$[0]', 'help_system') WHERE content_id BETWEEN 1000001 AND 1000005;

-- Update hashtag indexes for better search
UPDATE {{prefix}}contents SET hashtags = JSON_ARRAY_APPEND(hashtags, '$', '#help-system') WHERE content_id BETWEEN 1000001 AND 1000005;

-- =============================================================================
-- COMPLETION MESSAGE
-- =============================================================================

SELECT 'Online Help and Content System seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}contents WHERE content_id BETWEEN 1000001 AND 1000005
UNION ALL
SELECT 'Questions seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}questions WHERE question_id BETWEEN 2000001 AND 2000008
UNION ALL
SELECT 'Answers seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}answers WHERE answer_id BETWEEN 3000001 AND 3000008
UNION ALL
SELECT 'Edges seeded successfully' AS status,
       COUNT(*) AS content_items FROM {{prefix}}edges WHERE edge_id BETWEEN 4000001 AND 4000034;

-- <<< END FILE: seed_online_help_and_content.sql
