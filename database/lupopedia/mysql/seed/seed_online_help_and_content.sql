-- =============================================================================
-- Lupopedia Online Help and Content System Seed Data
-- =============================================================================
-- Purpose: Seed user-facing help content, questions, answers, and edges
-- Version: 4.0.97
-- Updated: 2026-04-10
--
-- This seed populates:
-- 1. lupo_contents - Help articles and documentation; captain's log (file-backed) under federation_node/0/captains_log
-- 2. lupo_truth_questions - User questions (canonical table per PRD 42)
-- 3. lupo_truth_answers - Answers to questions (canonical table per PRD 42)
-- 4. lupo_edges - Relationships between content, questions, answers
--
-- NOTE: lupo_questions and lupo_answers do NOT exist in the install schema.
--       The canonical tables are lupo_truth_questions and lupo_truth_answers.
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
INSERT INTO lupo_contents (
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
INSERT INTO lupo_contents (
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
INSERT INTO lupo_contents (
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
-- Canonical table: lupo_truth_questions (PRD 42 §3)
-- NOT NULL columns supplied: truth_question_id, target_object_type,
--   target_object_id, question_text, asked_by_actor_id,
--   created_ymdhis, updated_ymdhis
-- =============================================================================

INSERT INTO lupo_truth_questions (
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
-- Canonical table: lupo_truth_answers (PRD 42 §3)
-- NOT NULL columns supplied: truth_answer_id, truth_question_id,
--   answer_text, answered_by_actor_id, created_ymdhis, updated_ymdhis
-- =============================================================================

INSERT INTO lupo_truth_answers (
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

INSERT INTO lupo_edges (
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

SET @lupo_captains_ts := 20260409240000;

INSERT INTO lupo_contents (
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
    @lupo_captains_ts, @lupo_captains_ts, 'daily',
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
SET @lupo_nav_seed_ts := 20260409120000;

UPDATE lupo_collections SET is_nav_menu = 0, is_deleted = 1, updated_ymdhis = @lupo_nav_seed_ts, deleted_ymdhis = @lupo_nav_seed_ts WHERE collection_id = 1005100;
UPDATE lupo_collection_tabs SET is_active = 0, is_deleted = 1, updated_ymdhis = @lupo_nav_seed_ts, deleted_ymdhis = @lupo_nav_seed_ts WHERE collection_id = 1005100;
UPDATE lupo_collection_tab_map SET is_deleted = 1, updated_ymdhis = @lupo_nav_seed_ts, deleted_ymdhis = @lupo_nav_seed_ts WHERE collection_tab_id = 1005101;
UPDATE lupo_actor_collections SET is_deleted = 1, updated_ymdhis = @lupo_nav_seed_ts, deleted_ymdhis = @lupo_nav_seed_ts WHERE collection_id = 1005100;

INSERT INTO lupo_collections (
    collection_id, federation_node_id, actor_id, department_id, name, slug, color, description, sort_order,
    properties,
    published_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, parent_id, channel_id, is_nav_menu, nav_icon
) VALUES
(
    0, 1, 1, NULL, 'Lupopedia docs', 'lupopedia-docs', '1565c0',
    'Help articles and references (captain''s log under References).', 0,
    '{"nav_group":"Lupopedia","nav_group_sort":10,"nav_item_label":"Root documentation"}',
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL, NULL, NULL, 1, NULL
),
(
    1, 1, 1, NULL, 'Example collection', 'example-who-what', '4caf50',
    'Example WHO / WHAT / WHERE / WHEN / WHY tabs.', 10,
    '{"nav_group":"Example collections","nav_group_sort":20,"nav_item_label":"5WH"}',
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL, NULL, NULL, 1, NULL
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

INSERT INTO lupo_collection_tabs (
    collection_tab_id, collection_tab_parent_id, collection_id, federations_node_id, department_id, actor_id,
    sort_order, name, slug, color, description, is_hidden, visibility_rule, tab_type,
    created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis
) VALUES
(
    1005300, NULL, 0, 1, NULL, 1, 10, 'Help', 'help', '2196f3',
    'Online help (help_documentation).', 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
),
(
    1005301, NULL, 0, 1, NULL, 1, 20, 'References', 'references', '4caf50',
    'Reference material; captain''s log flyout.', 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
),
(
    1005302, 1005301, 0, 1, NULL, 1, 10, 'Captain''s log', 'captains-log-tab', '2c3e50',
    'Federation node 0 captain''s log entries.', 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
),
(
    1005320, NULL, 1, 1, NULL, 1, 10, 'WHO', 'who', '4caf50', NULL, 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
),
(
    1005321, NULL, 1, 1, NULL, 1, 20, 'WHAT', 'what', '4caf50', NULL, 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
),
(
    1005322, NULL, 1, 1, NULL, 1, 30, 'WHERE', 'where', '4caf50', NULL, 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
),
(
    1005323, NULL, 1, 1, NULL, 1, 40, 'WHEN', 'when', '4caf50', NULL, 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
),
(
    1005324, NULL, 1, 1, NULL, 1, 50, 'WHY', 'why', '4caf50', NULL, 0, NULL, NULL,
    @lupo_nav_seed_ts, @lupo_nav_seed_ts, 1, 0, NULL
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

INSERT INTO lupo_actor_collections (
    actor_collection_id, actor_id, collection_id, access_level, created_ymdhis, updated_ymdhis,
    is_deleted, deleted_ymdhis, persistent_identity_json, identity_signature, trust_level,
    emotional_geometry_baseline, doctrine_alignment_version
) VALUES
(
    1005220, 1, 0, 'read', @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL, NULL, NULL, 'standard', NULL, '3.0.0'
),
(
    1005221, 1, 1, 'read', @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL, NULL, NULL, 'standard', NULL, '3.0.0'
)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis),
    is_deleted = 0,
    deleted_ymdhis = NULL;

INSERT INTO lupo_collection_tab_map (
    collection_tab_map_id, collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties,
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
) VALUES
(1000701, 1005300, 1, 'content', 1000001, 10, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000702, 1005300, 1, 'content', 1000002, 20, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000703, 1005300, 1, 'content', 1000003, 30, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000704, 1005300, 1, 'content', 1000004, 40, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000705, 1005300, 1, 'content', 1000005, 50, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000711, 1005302, 1, 'content', 1000061, 10, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000712, 1005302, 1, 'content', 1000062, 20, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000713, 1005302, 1, 'content', 1000063, 30, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000714, 1005302, 1, 'content', 1000064, 40, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000715, 1005302, 1, 'content', 1000065, 50, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000716, 1005302, 1, 'content', 1000066, 60, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000717, 1005302, 1, 'content', 1000067, 70, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL),
(1000718, 1005302, 1, 'content', 1000068, 80, NULL, @lupo_nav_seed_ts, @lupo_nav_seed_ts, 0, NULL)
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
UPDATE lupo_contents SET tags = JSON_SET(tags, '$[0]', 'help_system') WHERE content_id BETWEEN 1000001 AND 1000005;

-- Update hashtag indexes for better search
UPDATE lupo_contents SET hashtags = JSON_ARRAY_APPEND(hashtags, '$', '#help-system') WHERE content_id BETWEEN 1000001 AND 1000005;

-- =============================================================================
-- COMPLETION MESSAGE
-- =============================================================================

SELECT 'Online Help and Content System seeded successfully' AS status,
       COUNT(*) AS content_items FROM lupo_contents WHERE content_id BETWEEN 1000001 AND 1000005
UNION ALL
SELECT 'Questions seeded successfully' AS status,
       COUNT(*) AS content_items FROM lupo_truth_questions WHERE truth_question_id BETWEEN 2000001 AND 2000008
UNION ALL
SELECT 'Answers seeded successfully' AS status,
       COUNT(*) AS content_items FROM lupo_truth_answers WHERE truth_answer_id BETWEEN 3000001 AND 3000008
UNION ALL
SELECT 'Edges seeded successfully' AS status,
       COUNT(*) AS content_items FROM lupo_edges WHERE edge_id BETWEEN 4000001 AND 4000034
UNION ALL
SELECT 'Captains log (file-backed) seeded' AS status,
       COUNT(*) AS content_items FROM lupo_contents WHERE content_id BETWEEN 1000061 AND 1000068
UNION ALL
SELECT 'Nav collections 0-1 (Help, References, WHO-WHY) seeded' AS status,
       COUNT(*) AS content_items FROM lupo_collections WHERE collection_id IN (0, 1) AND is_deleted = 0;
