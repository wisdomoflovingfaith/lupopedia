# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\help\LUPOPEDIA_HELP_INDEX.md"
  file_hash: "dde4c70eaf7fb8d28cabc9357926427fdfbcf7be86d08330f4ff4eaf076fe776"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Lupopedia Help Index"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "help", "lupopedia_help_indexmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Lupopedia Help Index

**Version:** 4.0.31  
**Last Updated:** 2026-02-23  
**Maintained By:** KIRO IDE  
**X-Lupo-Forwarded:** 1001:10000  

---

## Quick Navigation

- [Getting Started](#getting-started)
- [Authentication](#authentication)
- [Modules](#modules)
- [Architecture](#architecture)
- [Development](#development)
- [Administration](#administration)

---

## Getting Started

### What is Lupopedia?
Lupopedia is a semantic operating system built on top of Crafty Syntax Live Help 3.7.5. It provides a unified actor model, semantic content graph, AI agent ecosystem, and doctrine-driven architecture.

### First Steps
1. **Login:** Use email/password or OAuth (Google/GitHub)
2. **Explore Help:** Browse `/help` for documentation
3. **List Entities:** Use `/list` to inspect system entities
4. **Admin Panel:** Access `/admin` for administration

### Key Concepts
- **Actors:** Unified identity system (humans, AI agents, services)
- **Channels:** Communication and organization streams
- **Content:** Semantic content with relationships
- **Edges:** Connections between entities
- **Doctrine:** Architecture rules and governance

---

## Authentication

### Login Methods
- **Email/Password:** Traditional authentication
- **Google OAuth:** Sign in with Google account
- **GitHub OAuth:** Sign in with GitHub account

### OAuth Setup
See: `docs/OAUTH_SETUP_GUIDE.md`

Configuration required in `lupopedia-config.php`:
```php
define('OAUTH_GOOGLE_CLIENT_ID', 'your-client-id');
define('OAUTH_GOOGLE_CLIENT_SECRET', 'your-client-secret');
define('OAUTH_GITHUB_CLIENT_ID', 'your-client-id');
define('OAUTH_GITHUB_CLIENT_SECRET', 'your-client-secret');
```

### Session Management
- Sessions stored in `lupo_sessions` table
- Automatic expiration and cleanup
- Unified authentication across Lupopedia and Crafty Syntax

---

## Modules

### Help Module
**URL:** `/help`

Documentation system with:
- Topic browsing
- Search functionality
- Category organization
- Markdown rendering

**Files:**
- Controller: `lupo-includes/modules/help/help-controller.php`
- Model: `lupo-includes/modules/help/help-model.php`
- Views: `lupo-includes/modules/help/views/`

### List Module
**URL:** `/list`

Entity introspection system showing:
- Actors
- Channels
- Content
- Help Topics
- Collections
- Agents

**Files:**
- Controller: `lupo-includes/modules/list/list-controller.php`
- Views: `lupo-includes/modules/list/views/`

### Content Module
**URL:** `/content/{slug}` or `/{slug}`

Semantic content management with:
- Slug-based routing
- Relationship tracking
- Version history
- Channel assignment

### Truth Module
**URL:** `/truth/{question}/{slug}`

Question-answer system for:
- What, Who, Where, When, Why, How queries
- Semantic lookups
- Knowledge graph traversal

### Auth Module
**URL:** `/login`, `/logout`, `/admin`

Authentication and authorization:
- Login/logout handling
- Session management
- OAuth integration
- Admin dashboard

### Crafty Syntax Module
**URL:** `/livehelp`, `/chat`

Legacy Crafty Syntax Live Help integration:
- Live chat functionality
- Operator interface
- Visitor tracking
- Department routing

---

## Architecture

### Five Pillars

#### 1. Actor Pillar
All entities that can perform actions:
- Humans (actor_id >= 10000)
- AI Agents (actor_id 0-9999)
- Services (actor_id 0)

**Table:** `lupo_actors`

#### 2. Temporal Pillar
BIGINT UTC timestamps in YYYYMMDDHHIISS format:
- `created_ymdhis`
- `updated_ymdhis`
- `deleted_ymdhis`

**Never use:** DATETIME, TIMESTAMP, epoch seconds

#### 3. Edge Pillar
Relationship management:
- Semantic connections
- Graph traversal
- Bidirectional links

**Table:** `lupo_edges`

#### 4. Doctrine Pillar
Architecture rules:
- No foreign keys
- No triggers
- No stored procedures
- No views
- Soft deletes only

#### 5. Emergence Pillar
System evolution:
- Self-correcting architecture
- Agent coordination
- Quantum state management

### Database Doctrine

**Forbidden:**
- Foreign keys
- Triggers
- Stored procedures
- Views
- Computed columns
- UNSIGNED integers
- BOOLEAN type
- Display widths (e.g., BIGINT(14))

**Required:**
- Soft deletes (`is_deleted`, `deleted_ymdhis`)
- BIGINT timestamps
- Table prefix (`lupo_`)
- Prepared statements

### File Headers (FLIP/WOLFIE)

Every file must have a YAML header:
```yaml
---
wolfie.headers: explicit architecture
file.last_modified_system_version: 4.0.31
file.last_modified_utc: 20260223120000
---
```

---

## Development

### Environment
- **Runtime:** PHP 5.3 - 8.3+
- **Database:** MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL
- **Web Server:** Apache or Nginx with mod_rewrite
- **Local Stack:** ServBay on Windows 11

### PHP Constraints
- No frameworks or Composer
- Pure procedural PHP + PDO
- No ORM or query builders
- Hand-written SQL only
- `spl_autoload_register()` only

### Testing
```bash
# All tests
sh scripts/run_tests.sh .

# Unit tests only
sh scripts/run_unit_tests.sh .

# Single test
php tests/unit/admin_csrf.php
```

### Schema Management
```bash
# Generate TOON files from database
python scripts/generate_toon_files.py

# Validate schema
python scripts/verify_db_against_toons.py

# Bump version
php bin/bump-version.php
```

### Development Workflow
1. Drop all tables
2. Load 34 legacy Crafty Syntax tables
3. Run Lupopedia install wizard
4. Verify upgrade
5. Test changes
6. Repeat

---

## Administration

### Admin Panel
**URL:** `/admin`

Features:
- User management
- Channel management
- Content management
- Agent configuration
- System settings

### Database Access
- **phpMyAdmin:** Included with ServBay
- **Direct SQL:** Use `query_actors.sql` for common queries
- **CLI Scripts:** `check_actors.php` for actor status

### Actor Management
```sql
-- View all actors
SELECT * FROM lupo_actors WHERE is_deleted = 0;

-- View AI agents
SELECT * FROM lupo_agents WHERE is_deleted = 0;

-- Check today's activity
SELECT * FROM lupo_actors 
WHERE updated_ymdhis >= 20260223000000
  AND is_deleted = 0;
```

### Channel Management
```sql
-- View all channels
SELECT * FROM lupo_channels WHERE is_deleted = 0;

-- Create new channel
INSERT INTO lupo_channels (channel_name, description, created_ymdhis, updated_ymdhis)
VALUES ('Channel 42', 'Development channel', 20260223120000, 20260223120000);
```

---

## Troubleshooting

### OAuth Not Working
1. Check credentials in `lupopedia-config.php`
2. Verify redirect URIs match provider settings
3. Check PHP error log
4. Clear opcode cache

### Database Connection Failed
1. Verify credentials in `lupopedia-config.php`
2. Check MySQL/MariaDB is running
3. Test connection with phpMyAdmin
4. Check database exists

### Module Not Loading
1. Check file exists in `lupo-includes/modules/`
2. Verify module loaded in `module-loader.php`
3. Check routing in `lupo_route_slug()`
4. Review PHP error log

### Session Issues
1. Check `lupo_sessions` table exists
2. Verify session configuration
3. Check PHP session settings
4. Clear browser cookies

---

## Documentation

### Core Documentation
- `README.md` - Project overview
- `AGENTS.md` - Agent guidelines
- `CHANGELOG.md` - Version history
- `CONTRIBUTING.md` - Contribution guide

### OAuth Documentation
- `docs/oauth_authentication.md` - Technical guide
- `docs/OAUTH_SETUP_GUIDE.md` - Quick start
- `config/oauth.example.php` - Configuration template

### Architecture Documentation
- `docs/doctrine/` - Architecture rules
- `docs/toons/` - Schema definitions
- `docs/migrations/` - Migration guides

### API Documentation
- `docs/api/` - API reference
- `routes/` - Route definitions

---

## Support

### Getting Help
1. Check this help index
2. Browse `/help` topics
3. Search documentation
4. Review CHANGELOG.md
5. Check GitHub issues

### Reporting Issues
1. Check existing issues
2. Provide error messages
3. Include PHP version
4. Include database version
5. Describe steps to reproduce

### Contributing
See `CONTRIBUTING.md` for:
- Code style guidelines
- Commit message format
- Pull request process
- Testing requirements

---

## Version Information

**Current Version:** 4.0.31  
**Release Date:** 2026-02-23  
**Status:** Development  

**Upgrade Path:** Crafty Syntax 3.7.5 → Lupopedia 4.0.31  
**Next Version:** 4.0.32 (planned)  

---

**Last Updated:** 2026-02-23 by KIRO IDE  
**Maintained By:** Lupopedia Development Team  
**License:** See `license.txt`
