<?php
/**
 * Schema Federation Configuration
 * 
 * Defines schema names for Phase A Schema Federation.
 * Provides constants and helper functions for schema-qualified table names.
 * 
 * @package Lupopedia
 * @version 4.0.3
 * @author Captain Wolfie
 */

// Schema Names
define('LUPO_SCHEMA_CORE', 'lupopedia');
define('LUPO_SCHEMA_ORCHESTRATION', 'lupopedia_orchestration');
define('LUPO_SCHEMA_EPHEMERAL', 'lupopedia_ephemeral');

// Table Classification Maps
// These maps define which schema each table belongs to after Phase A migration

// Orchestration Tables (22 tables)
$GLOBALS['lupo_orchestration_tables'] = [
    'lupo_audit_log',
    'lupo_search_rebuild_log',
    'lupo_memory_debug_log',
    'lupo_interpretation_log',
    'lupo_system_events',
    'lupo_system_config',
    'lupo_agent_context_snapshots',
    'lupo_agent_dependencies',
    'lupo_agent_external_events',
    'lupo_agent_tool_calls',
    'lupo_agent_versions',
    'lupo_memory_events',
    'lupo_memory_rollups',
    'lupo_anibus_events',
    'lupo_anibus_orphans',
    'lupo_anibus_redirects',
    'lupo_api_rate_limits',
    'lupo_api_token_logs',
    'lupo_notifications',
    'lupo_governance_overrides',
];

// Ephemeral Tables (12 tables)
$GLOBALS['lupo_ephemeral_tables'] = [
    'lupo_sessions',
    'lupo_analytics_campaign_vars_daily',
    'lupo_analytics_paths_daily',
    'lupo_analytics_referers_daily',
    'lupo_analytics_visits_daily',
    'lupo_analytics_visits',
    'lupo_api_tokens',
    'lupo_api_clients',
    'lupo_api_webhooks',
    'lupo_narrative_fragments',
    'lupo_document_chunks',
];

/**
 * Get schema-qualified table name
 * 
 * @param string $table Table name (without schema prefix)
 * @return string Schema-qualified table name
 */
function lupo_table($table) {
    global $lupo_orchestration_tables, $lupo_ephemeral_tables;
    
    // Check if table is orchestration
    if (in_array($table, $GLOBALS['lupo_orchestration_tables'])) {
        return LUPO_SCHEMA_ORCHESTRATION . '.' . $table;
    }
    
    // Check if table is ephemeral
    if (in_array($table, $GLOBALS['lupo_ephemeral_tables'])) {
        return LUPO_SCHEMA_EPHEMERAL . '.' . $table;
    }
    
    // Default to core schema
    return LUPO_SCHEMA_CORE . '.' . $table;
}

/**
 * Get schema name for a table
 * 
 * @param string $table Table name (without schema prefix)
 * @return string Schema name
 */
function lupo_table_schema($table) {
    global $lupo_orchestration_tables, $lupo_ephemeral_tables;
    
    if (in_array($table, $GLOBALS['lupo_orchestration_tables'])) {
        return LUPO_SCHEMA_ORCHESTRATION;
    }
    
    if (in_array($table, $GLOBALS['lupo_ephemeral_tables'])) {
        return LUPO_SCHEMA_EPHEMERAL;
    }
    
    return LUPO_SCHEMA_CORE;
}

?>
