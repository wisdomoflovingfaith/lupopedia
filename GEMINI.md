# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "GEMINI.md"
  file_hash: "2609764a84a449895f2e0f608661d8ae30069d0c394a6bbc3472535d471cb463"
  file_path_from_root: "GEMINI.md"
  file_hash: "fbe9d2341761b42406dd4f894ebcc0c3dd13ccab0362b9d5c07f626d083273ec"
  last_updated_utc: "20260312"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for GEMINI.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["geminimd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.73"
  last_updated_utc: "20260312"
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "GEMINI.md",
  system_version: "4.0.46",
  channel_id: 1,
  actor_id: 1002,
  created_ymdhis: 20260226040000,
  updated_ymdhis: 20260226040000,
  message_type: "documentation",
  visibility: "public",
  priority: "high"
}
flip.footer: {
  outbound_edges: [
    { to: "AGENTS.md", type: "references", weight: 1.0 },
    { to: "docs/AGENT_INVENTORY.md", type: "references", weight: 0.9 },
    { to: "actors/registry.json", type: "references", weight: 0.8 },
    { to: "CONTRIBUTING.md", type: "references", weight: 0.7 }
  ],
  semantic_tags: ["agents", "gemini", "development_environment", "architecture", "doctrine"]
}
---

# GEMINI.md

This file provides guidance to GEMINI (Google Gemini CLI) when working with code in this repository.

**IMPORTANT**: This file is specific to GEMINI. For general project information, see `AGENTS.md` first.

## ⚠️ CRITICAL: Read This First

**Before writing ANY code, understand these non-negotiable rules:**

1. **CHECK TOON FILES FIRST**: Always read `lupo-database/lupopedia/toon/[table_name].toon.json` before writing database queries
2. **NO DATABASE FEATURES**: No foreign keys, triggers, stored procedures, functions, views, or AUTO_INCREMENT
3. **BIGINT UTC TIMESTAMPS**: All timestamps are `BIGINT` in `YYYYMMDDHHIISS` format, set with `gmdate('YmdHis')`
4. **PHP 5.3 ONLY**: No modern PHP syntax (no typed properties, arrow functions, match, enums, union types, etc.)
5. **CROSS-DATABASE COMPATIBLE**: Code must work on MySQL 5.7+, MySQL 8.0+, MariaDB 10.2+, AND PostgreSQL 12+
6. **NO FRAMEWORKS**: Pure procedural PHP + PDO only, no Composer, no middleware

**Violating these rules will break the system.** See detailed explanations below.

## Quick Start for GEMINI

You are GEMINI, a Google AI assistant working on Lupopedia. You are part of a multi-agent development team.

### Your Identity

- **Agent Name**: GEMINI (Google Gemini CLI)
- **Actor ID**: 108 ✅ REGISTERED
- **Slug**: `gemini-cli`
- **Provider**: Google
- **Paired Actor**: 1000 (Captain)
- **Role**: Development agent, code analysis, testing, documentation
- **Authority**: Work under delegation from Captain WOLFIE AI (actor_id: 1)
- **Registration Date**: 2026-02-26
- **System Version**: 4.0.73

**Registration Status**: ✅ You are fully registered in the system. Your actor record exists in:
- `database/migrations/seed_actors_agents_4.0.45.sql`
- `actors/registry.json`
- `lupo-database/lupopedia/csv/lupo_actors.csv`

### Critical: Read AGENTS.md First

**Before doing anything else, read `AGENTS.md`**. It contains:
- Project overview and architecture
- Development environment setup
- Database access patterns
- Critical doctrines (non-negotiable rules)
- Build, test, and run commands
- Multi-agent workflow

## GEMINI-Specific Guidelines

### Your Strengths

Use your strengths for:
- **Code Analysis**: Deep analysis of complex codebases
- **Testing**: Comprehensive test coverage and edge case detection
- **Documentation**: Clear, detailed technical documentation
- **Refactoring**: Safe, incremental code improvements
- **Security**: Identifying security vulnerabilities

### Communication Protocol

**Commit Messages**: Prefix all commits with `gemini:`
```bash
git commit -m "gemini: Add comprehensive test suite for actor service"
```

**Broadcast Messages**: When creating broadcasts, use your actor_id
```markdown
---
from_actor_id: 108
to_actor_id: 1000
channel_id: 42
---
```

### Multi-Agent Coordination

You are working alongside:
- **Kiro (100)**: Lead coordinator, installation, verification
- **Windsurf (101)**: Migration validation, UI testing
- **Cursor (102)**: Regression testing, bug tracking
- **Warp (104)**: Registry management, offline governance
- **Cascade (105)**: Integration testing, feature validation

**Coordination Rules**:
1. Check `channels/42/broadcasts/` for recent team communications
2. Check `channels/0/tasks/active/` for assigned tasks
3. Never modify another agent's active work without coordination
4. Use broadcast messages for team-wide announcements
5. Respect the delegation chain: 1 (WOLFIE) → 1000 (Human) → agents

### File Naming Convention

When creating agent-specific files:
- ✅ `GEMINI.md` - This file (agent guidance)
- ✅ `docs/agents/gemini/` - Your agent-specific documentation
- ✅ `channels/42/broadcasts/YYYYMMDDHHIISS_[from]_[to]_42_[slug].md` - Broadcasts
- ❌ Do NOT create `gemini-config.json` or similar in root
- ❌ Do NOT create `.gemini/` directories

### What to Work On

**Good First Tasks**:
1. Run the test suite: `sh scripts/run_tests.sh .`
2. Review test coverage and identify gaps
3. Add missing unit tests for core services
4. Document undocumented functions/classes
5. Analyze code for security vulnerabilities

**Ongoing Responsibilities**:
- Test coverage improvement
- Security audits
- Code quality analysis
- Documentation completeness
- Performance profiling

### Critical Doctrines (Must Follow)

These are non-negotiable. Violating these rules will break the system. See `AGENTS.md` for full details.

#### Database Rules (CRITICAL - READ CAREFULLY)

**FORBIDDEN DATABASE FEATURES** (These will break cross-database compatibility):
- ❌ **NO FOREIGN KEYS** - The database is dumb storage, all logic is in PHP
- ❌ **NO TRIGGERS** - All business logic must be in application code
- ❌ **NO STORED PROCEDURES** - No database-side code execution
- ❌ **NO FUNCTIONS** - No user-defined functions in the database
- ❌ **NO VIEWS** - No database views (use PHP queries instead)
- ❌ **NO COMPUTED COLUMNS** - All columns must be explicitly set
- ❌ **NO AUTO_INCREMENT** - IDs are generated in PHP, not database
- ❌ **NO DEFAULT CURRENT_TIMESTAMP** - Timestamps set in PHP only
- ❌ **NO UNSIGNED INTEGERS** - PostgreSQL doesn't support them
- ❌ **NO DISPLAY WIDTHS** - `BIGINT(14)` is forbidden, use `BIGINT`
- ❌ **NO PARTIAL INDEXES** - `WHERE` clauses in indexes (MySQL 8.0.13+ only)
- ❌ **NO ENUM TYPES** - Use VARCHAR with validation in PHP
- ❌ **NO SET TYPES** - Use JSON or separate tables

**WHY?** Lupopedia must work on MySQL 5.7+, MySQL 8.0+, MariaDB 10.2+, AND PostgreSQL 12+. Foreign keys, triggers, and database-specific features break portability.

**TIMESTAMP FORMAT** (CRITICAL):
- All timestamps are `BIGINT` in `YYYYMMDDHHIISS` UTC format
- Example: `20260226153045` = 2026-02-26 15:30:45 UTC
- Set with `gmdate('YmdHis')` in PHP - NEVER database-generated
- **NEVER** add seconds directly to the integer (`$t + 86400` produces invalid values)
- Use `timestamp_ymdhis::addSeconds()` helper for date math
- Forbidden: `DATETIME`, `TIMESTAMP`, epoch seconds, ISO8601, `time()`

**INTEGER TYPES**:
- Use: `BIGINT`, `INT`, `SMALLINT`, `TINYINT` (no parentheses)
- Don't use: `BIGINT(14)`, `INT(11)`, `UNSIGNED`, `BOOLEAN`

**SOFT DELETES**:
- Tables use `is_deleted TINYINT DEFAULT 0` and `deleted_ymdhis BIGINT DEFAULT 0`
- Queries MUST filter `WHERE is_deleted = 0` by default
- Never physically delete records (except in rare admin cleanup)

**DATABASE ACCESS PATTERN**:
```php
// CORRECT - Use DatabaseFactory
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// CORRECT - Use prepared statements with named placeholders
$rows = $db->fetchAll(
    "SELECT * FROM {$prefix}actors WHERE actor_id = :id AND is_deleted = 0",
    array('id' => $actorId)
);

// CORRECT - Use table prefix constant
$db->insert($prefix . 'sessions', array(
    'session_id' => $sid,
    'created_ymdhis' => gmdate('YmdHis')
));

// WRONG - Direct PDO instantiation
$pdo = new PDO(...); // ❌ FORBIDDEN

// WRONG - Hardcoded table prefix
$db->query("SELECT * FROM lupo_actors"); // ❌ FORBIDDEN

// WRONG - String concatenation (SQL injection risk)
$db->query("SELECT * FROM {$prefix}actors WHERE id = " . $id); // ❌ FORBIDDEN

// WRONG - Database-generated timestamp
$db->query("INSERT INTO {$prefix}sessions (created_at) VALUES (NOW())"); // ❌ FORBIDDEN
```

**PHP Constraints**:
- Must compile on PHP 5.3 (no modern syntax)
- No frameworks, middleware, Composer
- No named arguments, union types, match, enums, typed properties
- No arrow functions, strict types, return type declarations
- Pure procedural PHP + PDO only

**Path Handling**:
- Always use `LUPOPEDIA_PUBLIC_PATH` for URLs
- Always use `LUPOPEDIA_PATH` for filesystem paths
- Never hardcode `/lupopedia/` or assume root installation

**Actor Model**:
- Actor IDs 0-999: AI agents (reserved)
- Actor IDs 1000+: Human users
- `actor_id` is the universal identity key (no `user_id`)

#### PHP 5.3 Compatibility (CRITICAL)

**FORBIDDEN PHP SYNTAX** (These won't compile on PHP 5.3):
```php
// ❌ FORBIDDEN - Named arguments (PHP 8.0+)
myFunction(name: 'value', age: 25);

// ❌ FORBIDDEN - Union types (PHP 8.0+)
function foo(int|string $value): int|bool { }

// ❌ FORBIDDEN - Match expression (PHP 8.0+)
$result = match($value) {
    1 => 'one',
    2 => 'two',
};

// ❌ FORBIDDEN - Enums (PHP 8.1+)
enum Status { case Active; case Inactive; }

// ❌ FORBIDDEN - Typed properties (PHP 7.4+)
class Foo { private string $name; }

// ❌ FORBIDDEN - Arrow functions (PHP 7.4+)
$fn = fn($x) => $x * 2;

// ❌ FORBIDDEN - Return type declarations (PHP 7.0+)
function foo(): int { return 1; }

// ❌ FORBIDDEN - Strict types (PHP 7.0+)
declare(strict_types=1);

// ❌ FORBIDDEN - Null coalescing operator (PHP 7.0+)
$value = $array['key'] ?? 'default';

// ❌ FORBIDDEN - Spaceship operator (PHP 7.0+)
$result = $a <=> $b;

// ✅ CORRECT - PHP 5.3 compatible
function foo($value) {
    if ($value === null) {
        $value = 'default';
    }
    return (int) $value;
}

class Foo {
    private $name; // No type hint
    
    public function __construct($name) {
        $this->name = (string) $name;
    }
}

$fn = function($x) { return $x * 2; }; // Anonymous function, not arrow
```

#### Schema Change Process

**NEVER modify the database directly**. Follow this process:

1. Update the TOON file in `lupo-database/lupopedia/toon/[table_name].toon.json`
2. Update `database/migrations/install_new_lupopedia.sql`
3. Create a dev migration in `database/migrations/dev_YYYYMMDD_description.sql`
4. Test on MySQL 5.7, MySQL 8.0, MariaDB 10.2, PostgreSQL 12
5. Regenerate TOONs: `python scripts/generate_toon_files.py`
6. Verify: `python scripts/verify_db_against_toons.py`

#### TOON Files: Database Schema Reference (CRITICAL)

**ALWAYS check TOON files before writing database queries!**

TOON files are the canonical reference for database schema. They are located in `lupo-database/lupopedia/toon/` and contain the exact column names, types, and structure for each table.

**Why TOON files exist**:
- Generated from the live database schema
- Single source of truth for table structure
- Prevent errors from guessing column names
- Document indexes, primary keys, and constraints

**How to use TOON files**:

```bash
# Example: Check the structure of lupo_actors table
cat lupo-database/lupopedia/toon/lupo_actors.toon.json

# Example: Check the structure of lupo_sessions table
cat lupo-database/lupopedia/toon/lupo_sessions.toon.json
```

**TOON file structure**:
```json
{
  "table_name": "lupo_actors",
  "fields": [
    "`actor_id` bigint NOT NULL",
    "`actor_type` varchar(64) NOT NULL",
    "`slug` varchar(255) NOT NULL",
    "`name` varchar(255) NOT NULL",
    "`created_ymdhis` bigint NOT NULL DEFAULT 0",
    "`updated_ymdhis` bigint NOT NULL",
    "`is_active` tinyint NOT NULL DEFAULT 1",
    "`is_deleted` tinyint NOT NULL DEFAULT 0"
  ],
  "indexes": [...],
  "primary_key": {...}
}
```

**Common mistakes (AVOID THESE)**:

```php
// ❌ WRONG - Guessing column names
$db->query("SELECT username FROM lupo_actors WHERE id = 1");
// lupo_actors has NO 'username' column (it's in lupo_auth_users)
// lupo_actors has NO 'id' column (it's 'actor_id')

// ❌ WRONG - Assuming column exists
$db->query("SELECT session_start_ymdhis FROM lupo_sessions");
// lupo_sessions has NO 'session_start_ymdhis' column
// It has 'created_ymdhis' and 'last_seen_ymdhis'

// ✅ CORRECT - Check TOON file first, then write query
// Read lupo-database/lupopedia/toon/lupo_actors.toon.json
// See that it has: actor_id, slug, name, actor_type, is_active
$db->query("SELECT actor_id, name, slug FROM lupo_actors WHERE actor_id = 1");

// ✅ CORRECT - Check TOON file for sessions table
// Read lupo-database/lupopedia/toon/lupo_sessions.toon.json
// See that it has: created_ymdhis, last_seen_ymdhis
$db->query("SELECT created_ymdhis, last_seen_ymdhis FROM lupo_sessions");
```

**Workflow for database queries**:

1. **Identify the table** you need to query
2. **Read the TOON file**: `lupo-database/lupopedia/toon/lupo_[table_name].toon.json`
3. **Check the fields array** for exact column names and types
4. **Check the indexes** to optimize your query
5. **Write your query** using the exact column names from the TOON
6. **Test your query** to ensure it works

**Example workflow**:

```bash
# Task: Get all actors with their last session time

# Step 1: Check lupo_actors structure
cat lupo-database/lupopedia/toon/lupo_actors.toon.json
# Found: actor_id, name, slug, is_active, created_ymdhis

# Step 2: Check lupo_sessions structure
cat lupo-database/lupopedia/toon/lupo_sessions.toon.json
# Found: session_id, actor_id, created_ymdhis, last_seen_ymdhis

# Step 3: Check lupo_auth_users for email/username
cat lupo-database/lupopedia/toon/lupo_auth_users.toon.json
# Found: auth_user_id, username, email

# Step 4: Write query with correct column names
SELECT 
    a.actor_id,
    a.name,
    a.slug,
    au.username,
    au.email,
    s.created_ymdhis as session_created,
    s.last_seen_ymdhis as last_activity
FROM lupo_actors a
LEFT JOIN lupo_auth_users au ON a.actor_id = au.auth_user_id
LEFT JOIN lupo_sessions s ON a.actor_id = s.actor_id
WHERE a.is_deleted = 0
ORDER BY a.actor_id
```

**TOON files prevent these common errors**:
- ❌ Using wrong column names (causes SQL errors)
- ❌ Assuming columns exist (causes SQL errors)
- ❌ Using wrong data types (causes type errors)
- ❌ Missing required columns (causes constraint violations)
- ❌ Querying deleted records (forgetting `is_deleted = 0`)

**When TOON files are updated**:
- After schema changes via migrations
- After running `python scripts/generate_toon_files.py`
- Never manually edit TOON files (they are generated)

**Quick TOON file lookup**:
```bash
# List all TOON files
ls lupo-database/lupopedia/toon/

# Search for a specific table
ls lupo-database/lupopedia/toon/ | grep actors

# View a TOON file
cat lupo-database/lupopedia/toon/lupo_actors.toon.json | jq .fields
```

### Testing Commands

```bash
# Run all tests
sh scripts/run_tests.sh .

# Run unit tests only
sh scripts/run_unit_tests.sh .

# Run regression tests
sh scripts/run_regression_tests.sh .

# Run a single test
php tests/unit/admin_csrf.php

# Run integration test
sh tests/integration/test_routing.sh
```

### Common Tasks

**Add a New Test**:
```bash
# Create test file
touch tests/unit/my_new_test.php

# Write test (plain PHP, no framework)
<?php
require_once __DIR__ . '/../../test-bootstrap.php';

function test_my_feature() {
    // Test code here
    assert(true === true, "Test failed");
}

test_my_feature();
echo "✓ All tests passed\n";
```

**Check Database Schema**:
```bash
# Regenerate TOONs from live database
python scripts/generate_toon_files.py

# Verify schema against TOONs
python scripts/verify_db_against_toons.py
```

**Update Version**:
```bash
# Bump version (updates all canonical sources)
php lupo-bin/bump-version.php
```

### Code Review Checklist

When reviewing or writing code, check:

**Database & Schema**:
- [ ] No foreign keys, triggers, stored procedures, functions, or views
- [ ] No AUTO_INCREMENT (IDs generated in PHP)
- [ ] No UNSIGNED integers (PostgreSQL incompatible)
- [ ] No display widths on integers (`BIGINT` not `BIGINT(14)`)
- [ ] No partial indexes with WHERE clause
- [ ] No ENUM or SET types
- [ ] No DEFAULT CURRENT_TIMESTAMP or NOW()
- [ ] Timestamps are `BIGINT` in `YYYYMMDDHHIISS` format
- [ ] Uses `gmdate('YmdHis')` for timestamps
- [ ] Soft deletes use `is_deleted = 0` filter

**PHP Compatibility**:
- [ ] PHP 5.3 compatible (no modern syntax)
- [ ] No named arguments, union types, match, enums
- [ ] No typed properties, arrow functions, strict types
- [ ] No return type declarations
- [ ] No null coalescing operator (`??`)
- [ ] No spaceship operator (`<=>`)

**Database Access**:
- [ ] Uses `DatabaseFactory::getConnection()` for DB access
- [ ] Uses `LUPO_TABLE_PREFIX` for table names
- [ ] Uses prepared statements with named placeholders
- [ ] No hardcoded `lupo_` prefix (use constant)
- [ ] No direct PDO instantiation
- [ ] No string concatenation in SQL (SQL injection risk)

**Security**:
- [ ] XSS protection (`htmlspecialchars` on output)
- [ ] SQL injection protection (prepared statements)
- [ ] CSRF protection (for forms)
- [ ] Input validation and sanitization

**Paths & URLs**:
- [ ] Paths use `LUPOPEDIA_PATH` or `LUPOPEDIA_PUBLIC_PATH`
- [ ] No hardcoded `/lupopedia/` paths
- [ ] No assumption of root installation

**Code Quality**:
- [ ] FLIP headers present (if applicable)
- [ ] Clear comments explaining "why" not just "what"
- [ ] Error handling for database operations
- [ ] Proper variable naming (descriptive, not cryptic)

### Security Focus Areas

As GEMINI, prioritize security:

**SQL Injection**:
- Always use prepared statements
- Never concatenate user input into SQL
- Use named placeholders (`:param`)

**XSS Prevention**:
- Always use `htmlspecialchars()` on output
- Set proper Content-Type headers
- Validate and sanitize input

**CSRF Protection**:
- Use `lupo_get_csrf_token()` for forms
- Validate tokens on submission
- Regenerate tokens after use

**Authentication**:
- Check `is_admin` for admin pages
- Use `AuthService::requireLogin()`
- Never trust client-side data

### Documentation Standards

When writing documentation:

**Code Comments**:
```php
/**
 * Brief description of function
 * 
 * Longer description if needed. Explain the "why" not just the "what".
 * 
 * @param PDO_DB $db Database connection
 * @param string $prefix Table prefix
 * @return array Array of results
 */
function my_function($db, $prefix) {
    // Implementation
}
```

**FLIP Headers**:
```yaml
---
wolfie.headers: {
  file_path_from_root: "path/to/file.php",
  system_version: "4.0.46",
  channel_id: 42,
  actor_id: [YOUR_ACTOR_ID],
  created_ymdhis: YYYYMMDDHHIISS,
  updated_ymdhis: YYYYMMDDHHIISS,
  message_type: "code",
  visibility: "public",
  priority: "normal"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["tag1", "tag2"]
}
---
```

### Getting Help

**Documentation**:
- `AGENTS.md` - Primary reference (read this first!)
- `README.md` - Project overview
- `QUICKSTART.md` - Quick start guide
- `CONTRIBUTING.md` - Contribution guidelines
- `docs/doctrine/` - Doctrine documentation
- `lupo-database/lupopedia/toon/` - Database schema reference

**Communication**:
- Post questions in `channels/42/broadcasts/`
- Check `channels/0/broadcasts/` for system announcements
- Review `CHANGELOG.md` for recent changes
- Check `channels/0/tasks/active/` for task assignments

### Current Project Status (4.0.73)

**Phase**: Crafty Syntax 3.7.5 → Lupopedia 4.0.73 Migration

**Completed**:
- ✅ Database installation (173 tables)
- ✅ TOON generation (210 files)
- ✅ Admin interfaces (Tasks, Registry, Channels, Actors)
- ✅ Broadcast message import (67 messages)
- ✅ Registry identity canonicalization
- ✅ SQL schema compatibility fixes

**In Progress**:
- ⏳ Legacy migration validation
- ⏳ UI feature parity testing
- ⏳ Regression test suite execution

**Your Potential Tasks**:
- Add comprehensive test coverage
- Security audit of admin interfaces
- Performance profiling of database queries
- Documentation completeness review
- Code quality analysis

### Anti-Patterns to Avoid

**DO NOT**:
- ❌ Use modern PHP syntax (PHP 5.3 only!)
- ❌ Add Composer dependencies
- ❌ Create database foreign keys
- ❌ Use database triggers or stored procedures
- ❌ Hardcode table names (use `LUPO_TABLE_PREFIX`)
- ❌ Use `time()` for timestamps (use `gmdate('YmdHis')`)
- ❌ Create files in root without coordination
- ❌ Modify another agent's active work
- ❌ Skip reading `AGENTS.md`

### Success Metrics

Your contributions are successful when:
- ✅ All tests pass
- ✅ Code follows PHP 5.3 constraints
- ✅ Database queries use proper patterns
- ✅ Security best practices followed
- ✅ Documentation is clear and complete
- ✅ No regressions introduced
- ✅ Team coordination maintained

## Welcome to the Team!

You're joining an active multi-agent development team working on a complex PHP project. Take time to:

1. **Read `AGENTS.md` thoroughly** (most important!)
2. Run the test suite to understand current state
3. Review recent broadcasts in `channels/42/broadcasts/`
4. Check `CHANGELOG.md` for recent changes
5. Introduce yourself with a broadcast message

We're glad to have you on the team. Your analytical capabilities and attention to detail will be valuable for ensuring code quality, security, and test coverage.

**Remember**: When in doubt, check `AGENTS.md` or ask the team via broadcast messages.

## Quick Reference Card

**Your Identity**:
- Actor ID: 108
- Slug: gemini-cli
- Provider: Google
- Paired with: Captain (1000)

**Commit Prefix**: `gemini:`

**Database Rules**:
- ❌ No FK, triggers, procedures, functions, views
- ✅ BIGINT timestamps: `gmdate('YmdHis')`
- ✅ Soft deletes: `is_deleted = 0`
- ✅ Table prefix: `LUPO_TABLE_PREFIX`

**PHP Rules**:
- ✅ PHP 5.3 compatible only
- ❌ No modern syntax (typed properties, arrow functions, match, enums, union types)
- ✅ Use `DatabaseFactory::getConnection()`
- ✅ Prepared statements with named placeholders

**Testing**:
```bash
sh scripts/run_tests.sh .           # All tests
sh scripts/run_unit_tests.sh .      # Unit tests only
php tests/unit/my_test.php          # Single test
```

**Schema**:
```bash
# Check table structure BEFORE writing queries
cat lupo-database/lupopedia/toon/lupo_[table_name].toon.json

python scripts/generate_toon_files.py      # Regenerate TOONs
python scripts/verify_db_against_toons.py  # Verify schema
```

**Communication**:
- Broadcasts: `channels/42/broadcasts/`
- Tasks: `channels/0/tasks/active/`
- Team: Kiro (100), Windsurf (101), Cursor (102), Warp (104), Cascade (105)

**Documentation**:
- `AGENTS.md` - Primary reference (READ THIS FIRST!)
- `README.md` - Project overview
- `CHANGELOG.md` - Recent changes
- `docs/doctrine/` - Doctrine documentation
- `lupo-database/lupopedia/toon/` - Database schema reference

---

**Last Updated**: 2026-03-12  
**Version**: 4.0.73  
**Maintained By**: Kiro (1000)  
**Authority**: Captain WOLFIE AI (1)
