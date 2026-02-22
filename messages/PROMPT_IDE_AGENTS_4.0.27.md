# IDE AGENTS COORDINATION PROMPT - VERSION 4.0.27

## 🎯 MISSION BRIEF
**Lupopedia 4.0.27** - CRAFTY SYNTAX 3.7.5 UPGRADE TESTING

All IDE agents are hereby directed to begin comprehensive testing of the Crafty Syntax 3.7.5 → Lupopedia 4.0.27 upgrade path. This is the critical validation phase before production deployment.

---

## 🔄 PHASE 1: DATABASE RESET & UPGRADE TESTING

### REQUIRED ACTIONS:
1. **Complete Database Reset**:
   ```sql
   DROP DATABASE IF EXISTS lupopedia_test;
   CREATE DATABASE lupopedia_test;
   USE lupopedia_test;
   ```

2. **Load Crafty Syntax 3.7.5**:
   ```bash
   mysql -u root -p lupopedia_test < database/migrations/old_crafty_syntax_3_7_5_start.sql
   ```

3. **Run Lupopedia Install Wizard**:
   - Navigate to `install.php` in web browser
   - Complete full installation workflow
   - Verify upgrade from Crafty 3.7.5 → Lupopedia 4.0.27
   - Test all installer steps and validation

4. **Schema Validation**:
   - Verify all 222 tables created correctly
   - Check TOON compliance against `docs/toons/*.toon.json`
   - Validate data migration from Crafty tables

---

## 🧪 PHASE 2: COMPREHENSIVE TESTING FRAMEWORK

### CORE SYSTEM TESTING:
- **Database Access**: Test `DatabaseFactory::getConnection()` and `lupo_get_db()`
- **Actor Registry**: Verify `lupo_actors` table functionality
- **Dialog System**: Test `lupo_dialog_messages` and channel operations
- **REST API Endpoints**: Validate all API routes in `module-loader.php`
- **VSX Extension**: Test 3-tier fallback with live database
- **Multi-IDE Coordination**: Validate agent handoffs on Channel 42

### SPECIFIC TEST AREAS:
1. **Schema Validation**:
   - All tables created with correct TOON-backed schema
   - No foreign keys, triggers, or stored procedures
   - Proper BIGINT(14) timestamps in YYYYMMDDHHIISS format

2. **Data Migration**:
   - Crafty Syntax data properly mapped to Lupopedia structure
   - No data loss during upgrade process
   - Correct actor ID ranges maintained

3. **API Functionality**:
   - Registry API: `/api/registry/actors/lookup` and `/api/registry/actors/register`
   - Channels API: `/api/channels/{id}/messages`
   - Semantic API: `/semantic/explain`, `/semantic/flip-header`, `/semantic/related`, `/semantic/paths`

4. **VSX Extension Testing**:
   - 3-tier fallback: `remote` → `local` → `offline`
   - Actor lookup from live database
   - Channel message operations
   - TypeScript compilation: `npx tsc --noEmit`

5. **Multi-IDE Coordination**:
   - Channel 42 communication between all IDE agents
   - Handoff protocols between Warp (2039), Windsurf (2040), Antigravity (2035)
   - Task claiming and conflict resolution
   - Shared file registry coordination

---

## 📦 PHASE 3: PRODUCTION DEPLOYMENT

### DEPLOYMENT SEQUENCE:
1. **FTP Upload**:
   ```bash
   # Upload entire codebase to lupopedia.com
   # Ensure all files, including .htaccess, are transferred
   ```

2. **Manual Installation**:
   - Access production server via web browser
   - Run complete installation process
   - Verify Crafty Syntax 3.7.5 upgrade path
   - Test all functionality in production environment

3. **Live Testing**:
   - Full system functionality validation
   - Performance testing under load
   - Multi-IDE coordination in production
   - VSX extension with live database

4. **Production Validation**:
   - Database stability and performance
   - API endpoint responsiveness
   - VSX extension 3-tier fallback
   - Multi-IDE agent coordination

---

## 🎭 AGENT ROLES & RESPONSIBILITIES

### WARP IDE (2039) - LEAD COORDINATOR
- **Primary Role**: Lead database reset and upgrade testing
- **Focus Areas**: Installer wizard, data migration, schema validation
- **Coordination**: Oversee multi-IDE testing protocols
- **Reporting**: Document all upgrade issues and solutions

### WINDSURF IDE (2040) - VSX EXTENSION LEAD
- **Primary Role**: VSX extension testing with live database
- **Focus Areas**: 3-tier fallback, API integration, TypeScript compilation
- **Testing**: Extension functionality with production database
- **Validation**: Ensure graceful degradation and offline capabilities

### ANTIGRAVITY IDE (2035) - SCHEMA & SEMANTIC LEAD
- **Primary Role**: Schema maintenance and Semantic API implementation
- **Completed**: Resolved 4.0.27 schema mismatches (`lupo_actors`, `lupo_registry`, `lupo_anubis_log`).
- **Focus Areas**: `/semantic/*` endpoints, request/response schemas, SQL integrity.
- **Integration**: Ensure all new columns are properly utilized by agents.

### SHARED RESPONSIBILITIES
- **Channel 42**: Primary coordination point for all IDE agents
- **Issue Tracking**: Document all bugs, fixes, and improvements
- **Performance**: Monitor system stability and resource usage
- **Handoffs**: Smooth transition between IDE agents for different tasks

---

## 📊 SUCCESS CRITERIA

### VERSION 4.0.27 RELEASE READY WHEN:
- ✅ **Database Reset**: Complete drop → Crafty 3.7.5 → Lupopedia 4.0.27 workflow tested
- ✅ **Installer**: Full installation wizard validated with upgrade path
- ✅ **Schema**: All 222 tables created correctly with TOON compliance
- ✅ **Data Migration**: Crafty Syntax data properly mapped without loss
- ✅ **API Endpoints**: All REST API functionality verified
- ✅ **VSX Extension**: 3-tier fallback tested with live database
- ✅ **Multi-IDE**: Coordination protocols validated
- ✅ **Production**: Deployed to lupopedia.com and tested live
- ✅ **Performance**: System stability under load confirmed
- ✅ **Documentation**: All issues and solutions documented

---

## 🚨 CRITICAL PATH ITEMS

### IMMEDIATE ATTENTION REQUIRED:
1. **Database Backup**: Ensure Crafty Syntax 3.7.5 database is backed up before reset
2. **TOON Generation**: Run `python scripts/generate_toon_files.py` after database is stable
3. **Version Consistency**: Verify all version files show 4.0.27
4. **Production Safety**: Test in staging environment before production deployment

### COORDINATION PROTOCOL:
- **Primary Channel**: Channel 42 for all IDE coordination
- **Status Updates**: Regular progress reports in Channel 42
- **Issue Resolution**: Collaborative problem-solving between IDE agents
- **Handoff Documentation**: Clear transition records between IDE agents

---

## 📋 NEXT STEPS

1. **Immediate**: Begin database reset and Crafty Syntax 3.7.5 loading
2. **Short-term**: Complete installer wizard testing and validation
3. **Medium-term**: Deploy to production and conduct live testing
4. **Long-term**: Prepare for 4.0.28 stabilization phase

---

*COORDINATION BEGINS IMMEDIATELY - ALL IDE AGENTS REPORT TO CHANNEL 42*

**Timestamp**: 2026-02-22T14:30:00Z  
**Version**: 4.0.27  
**Mission**: CRAFTY SYNTAX 3.7.5 UPGRADE TESTING  
**Priority**: CRITICAL
