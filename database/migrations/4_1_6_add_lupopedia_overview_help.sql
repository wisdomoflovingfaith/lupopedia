---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119042629
file.name: "4_1_6_add_lupopedia_overview_help.sql"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF00"
  message: "Created migration to add Lupopedia Overview help topic - the Creation Myth as canonical documentation."
tags:
  categories: ["migration", "help-system"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Add Lupopedia Overview Help Topic"
  description: "Migration to add/update the Lupopedia Creation Myth as canonical overview help topic"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

-- Migration: Add/Update Lupopedia Overview help topic
-- Version: 4.1.6
-- Date: 2026-01-19
-- Module: Help System
-- Purpose: Add the "Lupopedia Creation Myth" as the canonical overview help topic

-- Insert or update Lupopedia Overview help topic (UPSERT - safe to run multiple times)
INSERT INTO `lupo_help_topics` (`slug`, `title`, `content_html`, `category`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`) VALUES
('lupopedia-overview', 'What is Lupopedia?', '<h1 id="lupopedia-overview">What is Lupopedia?</h1>\n\n<p><strong>Lupopedia is a semantic operating system (not a CMS or framework). It records meaning; it doesn''t impose it.</strong></p>\n\n<h2 id="the-five-pillars">The Five Pillars</h2>\n<p>Lupopedia is built on five inviolable pillars:</p>\n<ol>\n  <li><strong>Actor Pillar</strong> - Identity is primary (email = login)</li>\n  <li><strong>Temporal Pillar</strong> - Time is the spine (BIGINT UTC timestamps)</li>\n  <li><strong>Edge Pillar</strong> - Relationships are meaning (no foreign keys, app-managed)</li>\n  <li><strong>Doctrine Pillar</strong> - Law prevents drift (rules in text files)</li>\n  <li><strong>Emergence Pillar</strong> - Roles are discovered, not assigned</li>\n</ol>\n\n<h2 id="how-it-works">How It Works</h2>\n<p>Lupopedia operates through three layers of meaning:</p>\n<ul>\n  <li><strong>Collections</strong> = Navigation universes (each has its own tabs)</li>\n  <li><strong>Tabs</strong> = User-defined semantic categories (you choose the names)</li>\n  <li><strong>Content</strong> = Stored in <code>lupo_content</code> table</li>\n  <li><strong>Meaning</strong> = Created when content is placed under tabs</li>\n</ul>\n\n<h2 id="what-you-dont-build">What You Don''t Build</h2>\n<p>Lupopedia does not impose meaning. You don''t build:</p>\n<ul>\n  <li>Every system</li>\n  <li>Tabs for users</li>\n  <li>Predefined categories</li>\n  <li>Imposed structure</li>\n</ul>\n<p><strong>You record what users define.</strong></p>\n\n<h2 id="what-you-do-build">What You Do Build</h2>\n<p>You build the infrastructure:</p>\n<ul>\n  <li><strong>The infrastructure</strong> - Database, routing, modules</li>\n  <li><strong>The tools</strong> - Tab editor, content editor</li>\n  <li><strong>The doctrine</strong> - Rules in text files</li>\n</ul>\n\n<h2 id="current-status">Current Status (4.1.6)</h2>\n<p>Lupopedia 4.1.6 includes:</p>\n<ul>\n  <li><strong>LABS-001</strong> - Actor baseline state (10 declarations required)</li>\n  <li><strong>GOV-AD-PROHIBIT-001</strong> - No ads in system output</li>\n  <li><strong>WOLFIE Headers</strong> - Metadata on every file (version tracking)</li>\n  <li><strong>UTC_TIMEKEEPER</strong> - Single source of truth for timestamps</li>\n  <li><strong>Help System</strong> - Documentation module</li>\n  <li><strong>List System</strong> - Browse entities</li>\n  <li><strong>TL;DR System</strong> - Quick reference summaries</li>\n  <li><strong>Governance Registry</strong> - Central registry of all governance artifacts</li>\n</ul>\n\n<h2 id="the-core-truth">The Core Truth</h2>\n<blockquote>\n  <p><strong>Lupopedia is a semantic OS that records user-defined meaning.</strong></p>\n  <p>You build the infrastructure; users define their own collections, tabs, and content.</p>\n  <p>The system records it, doesn''t impose it.</p>\n</blockquote>\n\n<h2 id="related-topics">Related Topics</h2>\n<ul>\n  <li>See <a href="/help/getting-started">Getting Started</a> for practical steps</li>\n  <li>Read <a href="/help/collection-doctrine">Collection Doctrine</a> to understand navigation universes</li>\n  <li>Visit <a href="/help/labs-001">LABS-001</a> for actor baseline state requirements</li>\n  <li>Check <a href="/tldnr/lupopedia-overview">TL;DR: Lupopedia Overview</a> for quick reference</li>\n</ul>', 'Core', 20260119041400, 20260119041400, 0)
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `content_html` = VALUES(`content_html`),
    `category` = VALUES(`category`),
    `updated_ymdhis` = VALUES(`updated_ymdhis`);
