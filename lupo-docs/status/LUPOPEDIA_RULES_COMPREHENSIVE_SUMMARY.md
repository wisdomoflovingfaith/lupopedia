# Lupopedia Rules Summary

---

**file_path_from_root:** lupo-docs/status/LUPOPEDIA_RULES_COMPREHENSIVE_SUMMARY.md  
**web_path:** http://www.lupopedia.com/lupo-docs/status/LUPOPEDIA_RULES_COMPREHENSIVE_SUMMARY.md  
**last_modified_utc:** 20260327220000  
**channel_id:** 42  
**actor_id:** 1  
**artifact_type:** status_report  
**artifact_kind:** rules_summary  

---

# Lupopedia Rules & Doctrines - Comprehensive Summary

**Date:** 2026-03-27  
**Status:** Complete and Active  
**Version:** 4.0.89+  
**Maintainer:** WOLFIE (actor_id 1)

---

## 📋 Executive Summary

Lupopedia operates under a comprehensive set of rules and doctrines that ensure:

- **PHP 5.6+ shared hosting compatibility**
- **No external dependencies (Composer, frameworks)**
- **Deterministic multi-agent coordination**
- **Database integrity without foreign keys**
- **Channel-based communication**
- **Self-contained architecture**

---

## 🧱 Constitutional Rules (Highest Authority)

### [LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md](../../lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md)

**Absolute, non-negotiable rules that override all others:**

#### Database Doctrine
- ❌ **No Foreign Keys** - All relationships in application code
- ❌ **No Triggers** - No hidden database side effects
- ❌ **No Stored Procedures** - No vendor-specific procedural code
- ❌ **No AUTO_INCREMENT** - Application-layer ID generation only
- ✅ **Primary Key Naming** - Must be `[tablename_singular]_id`
- ✅ **Timestamp Format** - BIGINT UTC in YYYYMMDDHHIISS

---

## 🚫 Development Constraints (Critical)

### PHP Compatibility
**[PHP_VERSION_COMPATIBILITY.md](../../lupo-rules/root/PHP_VERSION_COMPATIBILITY.md)**
- ✅ PHP 5.6.0+ minimum requirement
- ❌ No PHP 7+ features: `??`, `<=>`, type hints, anonymous classes
- ✅ Polyfills provided for `random_bytes()`, `random_int()`

### No Composer Doctrine
**[NO_COMPOSER_DOCTRINE.md](../../lupo-rules/root/NO_COMPOSER_DOCTRINE.md)**
- ❌ No `composer.json` or `composer.lock`
- ❌ No `vendor/` directory
- ❌ No `require __DIR__ . '/vendor/autoload.php'`
- ✅ Self-contained libraries permitted per EXTERNAL_LIBRARIES_DOCTRINE

### No Framework Doctrine
**[NO_FRAMEWORK_DOCTRINE.md](../../lupo-rules/root/NO_FRAMEWORK_DOCTRINE.md)**
- ❌ No Laravel, Symfony, CodeIgniter
- ❌ No Blade templates: `@extends`, `@section`, `{{ }}`
- ✅ Pure PHP with manual includes

### Shared Hosting Doctrine
**[SHARED_HOSTING_DOCTRINE.md](../../lupo-rules/root/SHARED_HOSTING_DOCTRINE.md)**
- ✅ Works in subdirectories
- ✅ Uses `LUPOPEDIA_PUBLIC_PATH` for URLs
- ❌ No `shell_exec()`, `system()`
- ✅ 64MB memory limit compliance

### External Libraries Doctrine
**[EXTERNAL_LIBRARIES_DOCTRINE.md](../../lupo-rules/root/EXTERNAL_LIBRARIES_DOCTRINE.md)**
- ✅ Self-contained libraries in `lupo-includes/{library-name}/`
- ✅ Manual inclusion with `require_once`
- ✅ PHPMailer (approved), TCPDF, SimplePie
- ❌ No Composer-based libraries

---

## 🤝 Multi-Agent Coordination

### [MULTI_AGENT_COORDINATION_DOCTRINE.md](../../lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)

**108+ registered agents, 11 primary personas:**

#### Primary Personas
1. **WOLFIE** (1) - Main Orchestrator
2. **LEXA** (24) - Security Enforcement  
3. **ANUBIS** (59) - Custodian/Integrity
4. **HEIMDALL** (22) - Security Guardian
5. **SESHAT** (21) - Content Review
6. **ATHENA** (12) - Wisdom & Strategy
7. **MAAT** (7) - Truth & Justice
8. **THEMIS** (9) - Law & Order
9. **THOTH** (26) - Knowledge & Records
10. **JANUS** (23) - Transitions & Gateways
11. **ROSE** (3) - Emotional Dialogue

#### Key Rules
- Agent registration required for all operations
- Channel-based communication in `lupo-channels/42/`
- Single task ownership in root `TODO.md`
- UTC filename format: `YYYYMMDD_HHMMSS_ACTOR_purpose_TITLE.md`

### [CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md](../../lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md)

**Communication structure:**
```
lupo-channels/
├── 42/
│   ├── broadcasts/     # System-wide announcements
│   ├── threads/         # Threaded discussions
│   ├── direct/          # Direct messages
│   ├── rules/           # Rule definitions
│   ├── tasks/           # Task assignments
│   └── content/         # Content artifacts
```

---

## 📊 Database & Data Rules

### [CONVERGENCE_DOCTRINE.md](../../lupo-rules/root/CONVERGENCE_DOCTRINE.md)
- Single canonical system state
- No variant actors
- Banned entities remain addressable by `actor_id`

### [TIMESTAMP_DOCTRINE.md](../../lupo-rules/root/TIMESTAMP_DOCTRINE.md)
- UTC BIGINT in YYYYMMDDHHIISS format
- Hours must be 00-23
- Invalid timestamps rejected

### [TOON_SOURCE_OF_TRUTH.md](../../lupo-rules/root/TOON_SOURCE_OF_TRUTH.md)
- Database is authoritative
- TOON files are read-only reflections
- Generated via `python lupo-scripts/generate_toon_files.py`

### [SAFE_DATABASE_OPERATIONS_DOCTRINE.md](../../lupo-rules/root/SAFE_DATABASE_OPERATIONS_DOCTRINE.md)
- Use PDO_DB wrapper only
- Proper transaction patterns
- Parameter binding required

---

## 🏗️ Architecture & Validation

### [LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md](../../lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md)
- All artifacts need complete LUPOPEDIA headers
- Headers < 4.0.84 must be rewritten
- Structured header format required

### [FILE_BOUNDARY_VALIDATION_RULE.md](../../lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md)
- Header completeness validation
- Timestamp validity checks
- Content requirements enforcement

---

## 🔄 Migration & Versioning

### [MIGRATION_DOCTRINE.md](../../lupo-rules/root/MIGRATION_DOCTRINE.md)
- Single upgrade path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x
- No 4.0.x → 4.0.x upgrades until 4.1.0
- All schema in single install file

### [VERSIONING_DOCTRINE_SINGLE_SOURCE.md](../../lupo-rules/root/VERSIONING_DOCTRINE_SINGLE_SOURCE.md)
- Single source of version truth
- Location: `lupo-docs/versions/{version}/`
- Version-driven development

### [SINGLE_INSTALL_NO_4.0_UPGRADE_DOCTRINE.md](../../lupo-rules/root/SINGLE_INSTALL_NO_4.0_UPGRADE_DOCTRINE.md)
- No in-place upgrades between 4.0.x versions
- Fresh install required for major changes

---

## 📋 Task Planning

### [TASK_PLANNING_DOCTRINE.md](../../lupo-rules/root/TASK_PLANNING_DOCTRINE.md)
- Root `TODO.md` is single source of task truth
- Each task has single owner
- Status updated in owning file only

---

## 🔧 Technical Implementation

### [PDO_DB_DATABASE_ACCESS_DOCTRINE.md](../../lupo-rules/root/PDO_DB_DATABASE_ACCESS_DOCTRINE.md)
- Use PDO_DB wrapper class only
- `DatabaseFactory::getConnection()` required
- No direct PDO access

### [RESERVED_ID_DOCTRINE.md](../../lupo-rules/root/RESERVED_ID_DOCTRINE.md)
- System IDs: 1-999
- Human actors: 1000+
- Agent IDs: Various ranges
- Registry-based allocation

### [PK_REFERENCE_NAMING_DOCTRINE.md](../../lupo-rules/root/PK_REFERENCE_NAMING_DOCTRINE.md)
- Foreign key fields reference `[table]_id`
- Consistent naming convention
- Examples: `actor_id`, `session_id`, `channel_id`

---

## 🚨 Security & Validation

### [LILITH_NONINTERFERENCE_DOCTRINE.md](../../lupo-rules/root/LILITH_NONINTERFERENCE_DOCTRINE.md)
- LILITH as non-interfering reviewer
- Review only, no execution interference
- Artifact types: review, gap analysis, audit

### [VALIDATION_RULES/](../../lupo-rules/root/validation_rules/)
- Input validation functions
- Output validation patterns
- XSS and SQL injection prevention

---

## 📚 Documentation Standards

### LUPOPEDIA Headers Format
**Required fields:**
- `lupopedia.headers`
- `lupopedia.footer`
- `lupopedia.edges`

**Version:** 4.0.84+ format required

---

## 📋 Quick Checklist Before Coding

```bash
# Check PHP compatibility
php -l your_file.php

# Check for Composer violations
find . -name "composer.json" -o -name "vendor" -type d

# Check for framework violations  
grep -r "@extends\|@section\|{{ " --include="*.php"

# Verify external libraries follow EXTERNAL_LIBRARIES_DOCTRINE
ls lupo-includes/  # Should show self-contained libraries only
```

### Before Adding Libraries
- [ ] Place in `lupo-includes/{library}/`
- [ ] No Composer files
- [ ] Manual inclusion only
- [ ] PHP 5.6+ compatible
- [ ] Follows EXTERNAL_LIBRARIES_DOCTRINE rules

### Before Database Changes
- [ ] No foreign keys
- [ ] No auto-increment
- [ ] Primary key: `[table]_id`
- [ ] Use BIGINT for IDs
- [ ] Application-layer ID generation

---

## 🚀 Enforcement

### Automated Checks
- PHP syntax validation
- Composer violation detection
- Framework violation detection
- Header validation

### Manual Review
- **LEXA:** Security and boundary enforcement
- **ANUBIS:** Data integrity and orphan resolution
- **SESHAT:** Content review and validation
- **LILITH:** Audit and gap analysis

---

## 📚 Documentation Structure

```
lupo-rules/root/
├── README.md                           # This comprehensive index
├── LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md
├── PHP_VERSION_COMPATIBILITY.md
├── NO_COMPOSER_DOCTRINE.md
├── NO_FRAMEWORK_DOCTRINE.md
├── SHARED_HOSTING_DOCTRINE.md
├── EXTERNAL_LIBRARIES_DOCTRINE.md
├── MULTI_AGENT_COORDINATION_DOCTRINE.md
├── CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md
├── CONVERGENCE_DOCTRINE.md
├── TIMESTAMP_DOCTRINE.md
├── TOON_SOURCE_OF_TRUTH.md
├── SAFE_DATABASE_OPERATIONS_DOCTRINE.md
├── LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md
├── FILE_BOUNDARY_VALIDATION_RULE.md
├── MIGRATION_DOCTRINE.md
├── VERSIONING_DOCTRINE_SINGLE_SOURCE.md
├── SINGLE_INSTALL_NO_4.0_UPGRADE_DOCTRINE.md
├── TASK_PLANNING_DOCTRINE.md
├── PDO_DB_DATABASE_ACCESS_DOCTRINE.md
├── RESERVED_ID_DOCTRINE.md
├── PK_REFERENCE_NAMING_DOCTRINE.md
├── LILITH_NONINTERFERENCE_DOCTRINE.md
├── actor_rules/
├── execution_rules/
├── facet_rules/
├── system_rules/
└── validation_rules/
```

---

## 🔄 Integration Points

### Required Reading
1. **[AGENTS.md](../../AGENTS.md)** - Agent identities and coordination
2. **[ONBOARDING.md](../../ONBOARDING.md)** - Development quick-start
3. **[lupo-docs/doctrine/](../../lupo-docs/doctrine/)** - Complete doctrine collection
4. **[lupo-channels/](../../lupo-channels/)** - Channel-based communication

### Version-Specific
- **[lupo-docs/versions/4.0.89/](../../lupo-docs/versions/4.0.89/)** - Current version
- **[TODO.md](../../TODO.md)** - Task tracking
- **[PLAN.md](../../PLAN.md)** - Iteration plan

---

## ✅ Compliance Status

**All rules documented and active as of 2026-03-27**

- ✅ Constitutional rules defined
- ✅ Development constraints established
- ✅ Multi-agent coordination framework in place
- ✅ Database and data rules enforced
- ✅ Architecture validation implemented
- ✅ Migration and versioning paths defined
- ✅ Security and validation procedures active

---

**Next Actions:**
1. Propagate rules to all IDE agents
2. Ensure all new development follows these rules
3. Maintain documentation as system evolves

---

**lupo_schema:** status_report  
**tags:** rules, doctrine, constraints, compliance, summary
