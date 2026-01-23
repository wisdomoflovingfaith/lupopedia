-- ============================================================================
-- Crafty Syntax 3.8.0 - Database Migration
-- ============================================================================
-- Migration: 0004
-- Description: Create craftysyntax application AI agent
-- Date: 2025-11-20
-- Author: Captain WOLFIE (Eric Robin Gerdes)
-- ============================================================================
-- PURPOSE:
-- - Create craftysyntax application AI agent
-- - This is an APPLICATION AGENT (has PHP code that comes with the agent)
-- - Home project: C:\WOLFIE_Ontology\GITHUB_LUPOPEDIA\craftysyntax-3.8.0
-- - Agent ID: 360 (chosen because 360 is a circle, representing craftysyntax)
-- - Primary Channel ID: 360 (Agent ID = Channel Number)
-- - Also operates on channels: 007, 360, 380, 911, 001, 999, 008
-- ============================================================================
-- CRITICAL: This is an APPLICATION AGENT, not a regular agent
-- It represents the Crafty Syntax application itself
-- Agent ID 360 chosen because 360 is a circle (full rotation, completeness)
-- ============================================================================

-- ============================================================================
-- STEP 1: Insert craftysyntax Application Agent
-- ============================================================================

INSERT INTO `livehelp_agents` (
  `livehelp_id`,
  `agent_id`,
  `agent_name`,
  `channel_id`,
  `agent_type`,
  `status`,
  `dna_string`,
  `capabilities`,
  `metadata`
) VALUES (
  1,  -- Default instance
  380,  -- Agent ID: 380 (for version 3.8.0)
  'craftysyntax',  -- Agent name
  380,  -- Channel ID: 380 (Agent ID = Channel Number)
  'primary',  -- Agent type: primary (this is the main application)
  'active',  -- Status: active
  '380-craftysyntax-ATCG',  -- Default DNA string
  JSON_OBJECT(
    'language', 'PHP',
    'codebase_type', 'application',
    'has_php_code', true,
    'version', '3.8.0',
    'capabilities', JSON_ARRAY(
      'live_chat',
      'multi_instance',
      'dna_system',
      'channel_coordination',
      'agent_management',
      'database_operations',
      'session_management',
      'admin_interface',
      'user_interface',
      'ajax_handlers',
      'crm_integration',
      'routing',
      'tracking',
      'ui_customization'
    ),
    'file_types', JSON_ARRAY('php', 'sql', 'js', 'css', 'html'),
    'framework', 'none',
    'dependencies', JSON_ARRAY('MySQL', 'PHP 7.4+')
  ),
  JSON_OBJECT(
    'home_project', 'C:\\WOLFIE_Ontology\\GITHUB_LUPOPEDIA\\craftysyntax-3.8.0',
    'home_project_unix', '/WOLFIE_Ontology/GITHUB_LUPOPEDIA/craftysyntax-3.8.0',
    'agent_category', 'application',
    'is_application_agent', true,
    'has_embedded_code', true,
    'code_language', 'PHP',
    'application_type', 'live_chat_system',
    'description', 'Crafty Syntax Live Help 3.8.0 - The Living Application',
    'version', '3.8.0',
    'release_date', '2025-11-20',
    'status', 'in_development',
    'license', 'GPL v3.0',
    'copyright', '© 2003-2025 Eric Robin Gerdes / LUPOPEDIA LLC',
    'total_tables', 40,
    'database_structure', JSON_OBJECT(
      'master_table', 'livehelp',
      'dna_tables', 4,
      'original_tables', 34,
      'agents_table', 1
    ),
    'key_features', JSON_ARRAY(
      'Multi-instance support',
      'DNA system (computer genetics)',
      'Channel-based architecture',
      'Agent coordination',
      'Living application',
      'Self-evolving behavior'
    ),
    'project_structure', JSON_OBJECT(
      'public_dir', 'public/',
      'database_dir', 'public/database/',
      'migrations_dir', 'public/database/migrations/',
      'docs_dir', 'docs/',
      'scripts_dir', 'scripts/'
    )
  )
)
ON DUPLICATE KEY UPDATE
  `agent_name` = 'craftysyntax',
  `channel_id` = 380,
  `agent_type` = 'primary',
  `status` = 'active',
  `dna_string` = '380-craftysyntax-ATCG',
  `capabilities` = VALUES(`capabilities`),
  `metadata` = VALUES(`metadata`),
  `updated_at` = CURRENT_TIMESTAMP;

-- ============================================================================
-- STEP 2: Create Initial DNA Entry for craftysyntax Agent
-- ============================================================================
-- This creates the initial DNA profile for the application agent

INSERT INTO `livehelp_dna` (
  `livehelp_id`,
  `channel_id`,
  `agent_id`,
  `agent_name`,
  `dna_base`,
  `is_active`,
  `metadata`
) VALUES
  (1, 380, 380, 'craftysyntax', 'A', 1, JSON_OBJECT(
    'action_type', 'application_operations',
    'description', 'Crafty Syntax application actions: live chat, routing, CRM, tracking',
    'priority', 100,
    'scope', 'application_wide'
  )),
  (1, 380, 380, 'craftysyntax', 'T', 1, JSON_OBJECT(
    'tactic_type', 'systematic',
    'description', 'Methodical approach to live chat operations and multi-instance management',
    'efficiency_score', 0.95,
    'approach', 'structured'
  )),
  (1, 380, 380, 'craftysyntax', 'C', 1, JSON_OBJECT(
    'context_type', 'application',
    'description', 'Crafty Syntax 3.8.0 application context: multi-instance, channel-based, DNA-enabled',
    'scope', 'full_application',
    'environment', 'production_ready'
  )),
  (1, 380, 380, 'craftysyntax', 'G', 1, JSON_OBJECT(
    'governance_type', 'application_rules',
    'description', 'Application-level governance: GPL v3.0, multi-instance isolation, data integrity',
    'rule_level', 'strict',
    'compliance', 'gpl_v3'
  ))
ON DUPLICATE KEY UPDATE
  `metadata` = VALUES(`metadata`),
  `updated_at` = CURRENT_TIMESTAMP;

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- Verification:
-- 1. Check that 'craftysyntax' agent exists in livehelp_agents
-- 2. Verify agent_id = 380, channel_id = 380
-- 3. Verify DNA entries exist for all bases (A, T, C, G)
-- 4. Check metadata contains home_project path
-- 5. Verify capabilities include PHP codebase information
--
-- ============================================================================

