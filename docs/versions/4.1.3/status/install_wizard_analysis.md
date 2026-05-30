---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.3/status/install_wizard_analysis.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/status/install_wizard_analysis.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/registry/status/1026/04/install-wizard-analysis.toon
  atoms_toon: null
  transcript_jsonl: 0/registry/install-wizard-analysis
  artifact_type: status
  artifact_kind: report
  channel_key: registry
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: status
  prd_cluster: null
  title: Install Wizard Analysis - Current State and Issues
  summary: Analysis of install.php and wizard components identifying gaps, outdated references, and missing features for 4.1.3
---

# Install Wizard Analysis - Current State and Issues

## Executive Summary

The current installer (install.php) is a comprehensive wizard that handles both new installs and upgrades from Crafty Syntax 3.7.5. However, several critical gaps exist for 4.1.3 requirements:

1. **Missing Actor Registration Flow**: No step to register filesystem actors from actors/ directory
2. **Incomplete API Key Management**: Supports 6 providers but missing several found in filesystem
3. **No Red-Team Auth User Support**: Cannot register auth_user_id 420 (red team)
4. **Outdated Seed References**: References seed_4.1.0.sql but 4.1.3 needs updated actors
5. **Missing Channel Key Assignment**: No mechanism to assign channel_keys to actors

## Current Installer Flow

### Step Sequence (New Install)
1. **welcome** - Introduction and requirements check
2. **credentials** - Database connection and install mode selection
3. **confirm** - Summary and Run button
4. **run** - SQL execution (install + seed + reserved channels)
5. **config** - Site configuration and admin user creation
6. **api_keys** - API key collection for 6 providers
7. **complete** - Success screen

### Step Sequence (Upgrade)
1. **welcome** - Introduction
2. **credentials** - Database and mode detection
3. **bootstrap** - Install + seed + reserved channels
4. **normalize** - Identity normalization for Crafty users
5. **confirm** - Summary
6. **run** - Import + personal channels + drop tables
7. **config** - Site configuration
8. **api_keys** - API key collection
9. **complete** - Success

## Critical Issues Identified

### 1. Seed File References
- **Current**: References `seed_4.1.0.sql` (line 94-104 in install.php)
- **Issue**: 4.1.3 requires updated actor definitions with channel_keys
- **Impact**: New actors and channel key assignments not installed

### 2. Missing Actor Registration
- **Current**: No step to scan `actors/` directory
- **Issue**: Filesystem actors not registered in database
- **Impact**: Actors exist in filesystem but not in lupo_actors table

### 3. API Key Provider Gaps
- **Current Supports**: OpenAI, DeepSeek, Gemini, Grok, Groq, Anthropic
- **Missing Providers**: 
  - claude (found in filesystem agents)
  - perplexity (found in filesystem agents)
  - custom providers (limited to 3 in UI)
- **Impact**: Cannot configure all available LLM providers

### 4. Auth User Registration Limitations
- **Current**: Creates main admin auth_user (auth_user_id 100000)
- **Missing**: 
  - Optional red-team auth_user (auth_user_id 420)
  - No mechanism to register additional system users
- **Impact**: Red-team testing capabilities not available

### 5. Channel Key Assignment Missing
- **Current**: Actors created without channel_key assignments
- **Issue**: New 4.1.3 channel-based coordination requires channel_keys
- **Impact**: Actors cannot participate in channel-based routing

### 6. Memory/Handoff Path Configuration
- **Current**: No configuration of memory paths for actors
- **Issue**: Actors need memory_path and handoff_path for 4.1.3
- **Impact**: Runtime memory storage not configured

## Database Schema Analysis

### Tables Referenced by Installer
1. **lupo_auth_users** - Created/updated in config step
2. **lupo_actors** - Seeded from seed_4.1.0.sql
3. **lupo_agents** - Seeded from seed_4.1.0.sql
4. **lupo_departments** - Created in install schema
5. **lupo_channels** - Reserved channels created
6. **lupo_federation_nodes** - Basic federation setup

### Missing Schema Updates
- **actor_registry**: No table for actor registration tracking
- **channel_key assignments**: No mechanism to assign channel_keys
- **memory_path configuration**: No storage of memory paths

## Configuration File Generation

### Current lupopedia-config.php Output
- Site configuration (name, URL, email, etc.)
- API provider configuration (6 providers only)
- Database credentials
- Memory and channels paths (hardcoded)

### Missing Configuration
- Channel key assignments for actors
- Memory/handoff paths per actor
- Red-team user configuration
- Extended API provider list

## Filesystem Integration Gaps

### actors/ Directory
- **Contains**: 60+ actor directories with configurations
- **Problem**: No integration with installer
- **Needed**: Scan and registration mechanism

### agents/ Directory  
- **Contains**: Agent definitions and configurations
- **Problem**: Limited integration (only via seed file)
- **Needed**: Dynamic agent registration

### memory/ Directory
- **Contains**: Memory storage structure
- **Problem**: No configuration during install
- **Needed**: Path setup and permissions

## Security Considerations

### Current Security Features
- CSRF token protection
- Config file protection (0600 + .htaccess)
- SQL injection protection (prepared statements)

### Missing Security Features
- Red-team user isolation
- API key encryption (stored in plaintext)
- Channel-based access control setup

## Recommendations for 4.1.3

### Immediate Required Changes
1. **Update seed file reference** to seed_4.1.3.sql
2. **Add actor registration step** after run step
3. **Extend API key provider list** to match filesystem
4. **Add red-team auth user option** in config step
5. **Implement channel key assignment** mechanism

### Structural Improvements
1. **Add memory path configuration** step
2. **Implement filesystem actor scanning**
3. **Create actor metadata JSON templates**
4. **Add channel-based coordination setup**

### Future Enhancements
1. **Dynamic provider discovery** from filesystem
2. **Actor dependency resolution**
3. **Channel topology configuration**
4. **Memory quota management**

## Conclusion

The current installer is functional for basic 4.0.x installations but requires significant updates to support 4.1.3 features including channel-based coordination, expanded actor support, and enhanced API provider management. The missing actor registration flow is the most critical gap that prevents proper system initialization.
