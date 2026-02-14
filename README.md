## 🐺 Lupopedia 4.0.x  
### Crafty Syntax Reborn + Semantic OS + Optional AI Agents

**Lupopedia 4.0.x is the official evolution of Crafty Syntax Live Help 3.7.5.**  
It includes **every feature** of Crafty Syntax — real‑time chat, staff (captain/administrator/monitor) sessions, visitor tracking, departments, proactive invites, transcripts, and all legacy behavior — and extends it with a **Semantic Operating System** and **optional AI chat agents**.

Lupopedia is **not a CMS** and **not a framework**.  
It is a **semantic OS** that records meaning, relationships, and navigation across content, channels, and actors. Crafty Syntax becomes **Module 1** inside this OS.

---

### Origins: From WOLFIE to Lupopedia

Lupopedia began as **WOLFIE** (originally *Wisdom Of Loving Faith Integrity Ethics*), a spiritual research engine designed to ingest 144,000 books from 22 religions and map correlations between their teachings. That early prototype required 222 tables to capture scripture, symbolism, lineage, and cross‑textual relationships.

As the system grew, it became clear that WOLFIE wasn't just analyzing religion — it was becoming a platform capable of organizing any domain of knowledge. The spiritual engine evolved into the **Web‑Organized Linked Federated Intelligent Ecosystem (WOLFIE)** architecture, and WOLFIE evolved into Lupopedia.

Today, Lupopedia 4.0.x carries forward this heritage. The schema has been refined from 222 to a stable core; the goal is to keep the system under 200 tables (196 as of 2/14/2026, per TOON files). Every table has a purpose; every subsystem is a chapter in a living OS designed to last decades.

For the complete origin story, see [HISTORY.md](docs/channels/appendix/HISTORY.md) and the [Founder's Note](docs/channels/appendix/FOUNDERS_NOTE.md).

---

### The Five Pillars

Lupopedia uses **actors** because humans, system identities, and AI agents all participate in the same semantic and relational universe. While there are separate `users` and `agents` tables for human‑specific and agent‑specific metadata, **all relationships in the system use `actor_id`**. The `actors` table is the unified identity layer for the entire semantic OS.

All installations worldwide share a **unified global registry** for `actor_id`, `collection_id`, and `channel_id`. IDs are consistent, portable, and globally meaningful across every Lupopedia node. A given actor, collection, or channel has the same identity whether it lives on one server or thousands.

Lupopedia is built on five architectural pillars:

- **Unified Actor Pillar** – One identity layer for humans, system identities, and AI agents. `actor_id` is used everywhere; there is no `user_id` in relationships. Email = login for human actors. The actor model underpins the semantic OS and multi‑agent ecosystem.

- **Temporal Pillar** – Time is the spine. All timestamps are `BIGINT` in `YYYYMMDDHHIISS` UTC format. No DATETIME, no epoch seconds; application code sets every timestamp.

- **Relationship Pillar** – Relationships are meaning. No foreign keys; edges and relationships are managed entirely by application logic. The database stores raw facts; the application enforces correctness.

- **Doctrine Pillar** – Law prevents drift. Rules are codified in text files (TOON, doctrine Markdown). Schema source of truth is TOON files; no schema changes without TOON source.

- **Federation Pillar** – Installations worldwide share the same ID space and meaning. `actor_id`, `collection_id`, and `channel_id` are globally consistent and portable. Lupopedia is a federated knowledge system, not a centralized service.

---

### What Lupopedia 4.0.x Includes

- **All Crafty Syntax features**, preserved exactly  
- **Modernized identity model** (actor_id everywhere, no user_id)  
- **Semantic OS layer**  
  - Collections  
  - Tabs  
  - Content atoms  
  - Meaning edges  
- **Optional AI agents** that can join live chats  
- **Unified actor system** for humans, agents, and system identities  
- **Doctrine‑driven architecture** (no foreign keys, UTC timestamps, TOON‑based schema)  

---

### What Lupopedia 4.0.x Is

- A **drop‑in upgrade** for Crafty Syntax 3.7.5  
- A **semantic reference layer** that runs alongside any website  
- A **multi‑agent ecosystem** with optional AI participation  
- A **federated knowledge system** that installs anywhere Crafty Syntax installs  

---

### What Lupopedia 4.0.x Is NOT

- Not a CMS  
- Not a rewrite of Crafty Syntax  
- Not a framework  
- Not a centralized service  
- Not a replacement for your website  

---

### Versioning: The 4.0.x Doctrine

Lupopedia 4.0.x (4.0.0 → 4.0.4 and all future 4.0.x patches) are **repeated development attempts at stabilizing the same upgrade path**:

#### **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**

- **There are NO Lupopedia → Lupopedia upgrades** in the 4.0.x series.  
- **4.0.x is a development/stabilization series**, not a forward‑upgradeable series.  
- **The ONLY supported upgrade path before 4.1.0** is:  
  **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**  
- **4.1.0** will be the **first** version that supports Lupopedia → Lupopedia upgrades.  
- **4.1.0 will not exist** until a stable 4.0.x release is published through auto‑installers (Softaculous, Installatron, etc.).  

---

### Semantic OS Overview

Lupopedia adds a semantic layer on top of Crafty Syntax:

- **Collections** = navigation universes (each has its own tabs)  
- **Tabs** = user‑defined semantic categories (you choose the names)  
- **Content atoms** = reference entries describing pages, documents, or URLs  
- **Meaning edges** = relationships created when content is placed under tabs  

Lupopedia does not impose meaning — **it records the meaning you define**.

**How it works:**  
Users create tabs, subtabs, and collections. Lupopedia extracts tab‑paths, normalizes them into semantic concepts ("atoms"), builds weighted edges between content and concepts, and grows a knowledge graph organically over time. This enables intelligent discovery, related‑content recommendations, and emergent categories — all without manual tagging.

---

### Doctrine Boot Block (Required for All AI Agents)

**IMPORTANT — Lupopedia uses an ACTOR MODEL:**

- `actor_id` is the primary identity key; there is no `user_id`.  
- All sessions, permissions, ownership, and uploads use `actor_id`.  
- `auth_user_id` is only for human login.  
- **No foreign keys, triggers, or stored procedures** (absolute prohibition).  
- All timestamps use `YYYYMMDDHHIISS` in UTC.  
- Schema changes must come from TOON files in `/docs/toons/`.  
- Table count goal: under 200 (196 as of 2/14/2026; enforced by doctrine).  
- **Python** = maintenance (`scripts/python/`, PyMySQL, explicit SQL).  
- **PHP** = runtime only (no schema changes). PHP must be compatible with **PHP 5.3 through 8.3+**. Do not use PHP 8‑only features (named arguments, attributes, union types, match expressions). New code must use OOP (classes, methods).  
- Uploads use SHA256 hash filenames under `uploads/{actors,agents,channels}/YYYY/MM/`.  
- **LEXA** enforces doctrine and boundaries in the gateway.  

Never introduce `user_id`. Never add foreign keys, triggers, or stored procedures. Never modify schema without a TOON source. Never let PHP perform migrations.

All contributors and AI agents must read and follow:

- 📘 [docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md](docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md)  
- 📘 [docs/LUPOPEDIA_MASTER_DOCTRINE_OF_AI_CORRECTIONS_v1.0.md](docs/LUPOPEDIA_MASTER_DOCTRINE_OF_AI_CORRECTIONS_v1.0.md) — Common AI corrections: database (no FKs, no triggers, no display widths, no UNSIGNED), time (UTC YYYYMMDDHHIISS only), state (no deadlines), identity (BIGINT only), advertising/humor/psychological manipulation prohibitions, and filename rules.  

Any AI coding agent (JetBrains, Cursor, Claude, etc.) must be initialized with this doctrine before making changes to the codebase.

---

### ⚠️ CRITICAL DATABASE DOCTRINE — READ THIS FIRST

- **NO FOREIGN KEYS.** All relationships are managed in application code. The database stores raw facts; the application enforces correctness and referential integrity.

- **NO TRIGGERS. NO STORED PROCEDURES. NO STORED FUNCTIONS. NO VIEWS.**  
  The database is for storage only. All timestamp handling, cascades, and business logic must be done explicitly in application code. Triggers and stored procedures interfere with data merging, historical accuracy, ANIBUS repair operations, and federation sync.

- **TRIGGERS ARE FORBIDDEN (MANDATORY):** All timestamps must be set explicitly in `INSERT`/`UPDATE` statements in `YMDHIS` UTC format.

- **STORED PROCEDURES/FUNCTIONS ARE FORBIDDEN (MANDATORY):** The database is for storage, not computation. All logic must be in application code.

This is non‑negotiable core doctrine. All AI tools must follow these rules.

---

### Database access & SQL compatibility

- **All database access** must use the project’s **DatabaseFactory** and **PDO_DB** wrapper classes. No direct PDO, no `mysqli`, no raw `$pdo->query()` or `$pdo->exec()`. Use `DatabaseFactory::getConnection()` (or equivalent) to obtain the PDO_DB instance, then use its methods (`fetch`, `fetchAll`, `execute`, `insert`, `update`, `delete`) with prepared statements and bound parameters.

- **All SQL** (in PHP, in migration files, and in install/seed SQL) must be **compatible with MySQL, PostgreSQL, and MariaDB**. Use only standard SQL and constructs supported by all three. No vendor-specific functions, types, or syntax (e.g. no MySQL-only or Postgres-only features). This keeps the codebase portable and deployable across common hosting environments.

See [PDO_DB Database Access Doctrine](.cursor/rules/pdo-db-database-access-doctrine.mdc) and [LUPOPEDIA_DOCTRINE.md](docs/doctrine/LUPOPEDIA_DOCTRINE.md) for full rules.

---

### Database & Migration Doctrine

Lupopedia uses TOON files in `/docs/toons/` as the **only source of truth** for database structure.  
All schema changes must follow this process:

1. Update the TOON file for the table.
2. Update `install_new_lupopedia.sql` to match the TOON.
3. Create a one-time migration SQL file to apply the change to the live database.

**The system must never:**

- infer schema from the live database  
- run `scoop mysql` or any command-line SQL tool  
- modify schema directly from PHP  
- use AUTO_INCREMENT for registry-backed tables  

All schema changes must be explicit, doctrine-aligned, and fully reproducible.

See [docs/doctrine/MIGRATION_DOCTRINE.md](docs/doctrine/MIGRATION_DOCTRINE.md) for the full migration doctrine.

---

### ⏱️ CRITICAL TIMESTAMP DOCTRINE — MANDATORY FOR ALL AI AGENTS

🚨 **ALL TIMESTAMPS MUST BE STORED AS INTEGERS IN `YYYYMMDDHHIISS` FORMAT (e.g. `20260214153045`). NO EXCEPTIONS.**

Use `BIGINT(14)` columns. All timestamps are UTC. Set in application code (e.g. `gmdate('YmdHis')`), never by the database.

⚠️ **FORBIDDEN:** `DATETIME`, `TIMESTAMP` columns, epoch seconds, ISO8601 strings, timezone‑aware fields, ORM helpers, SQL date arithmetic.  
✅ **REQUIRED:** `BIGINT(14)`, integer `YYYYMMDDHHIISS` format, UTC only, use `timestamp_ymdhis` class for arithmetic.

**CRITICAL BUG PREVENTION:** Never add seconds directly to `YYYYMMDDHHIISS` timestamps — this produces invalid timestamps like `20260110120086400`. Always use `timestamp_ymdhis::addSeconds()` or convert to epoch, add seconds, then convert back.

Examples:  
✅ Correct: `$now = (int) gmdate('YmdHis');`  
✅ Correct: `$expires = timestamp_ymdhis::addSeconds($now, 86400);`  
❌ WRONG: `$expires = $now + 86400;`  
❌ WRONG: `$timestamp = time();`  

See [TIMESTAMP_DOCTRINE.md](docs/doctrine/TIMESTAMP_DOCTRINE.md) for complete canonical documentation.

---

### 📁 CRITICAL SUBDIRECTORY INSTALLATION DOCTRINE — MANDATORY

🚨 **LUPOPEDIA IS ALWAYS INSTALLED IN A SUBDIRECTORY. NEVER ASSUME ROOT INSTALLATION.**

⚠️ **FORBIDDEN:** Hardcoded root paths like `/login`, `/admin`, `/assets/css/main.css`.  
✅ **REQUIRED:** All paths MUST use the `LUPOPEDIA_PUBLIC_PATH` constant.

Examples:  
✅ `LUPOPEDIA_PUBLIC_PATH . '/login'`  
✅ `LUPOPEDIA_PUBLIC_PATH . '/admin'`  
✅ `LUPOPEDIA_PUBLIC_PATH . '/lupo-includes/css/main.css'`  
❌ `/login`  

`LUPOPEDIA_PUBLIC_PATH` is automatically set to `'/' . basename(__DIR__)`, which evaluates to the folder name (e.g., `/lupopedia`). This ensures Lupopedia works in any subdirectory without code changes.

See [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](docs/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md) for complete details.

---

## 🔒 SECURITY DOCTRINE — MANDATORY FOR ALL CONTRIBUTORS

### 8.1 PHP Compatibility Security

- PHP 5.3–8.3+ compatibility requires explicit security checks:
  - No deprecated or removed functions (e.g. `mysql_*`)
  - MySQLi or PDO only
  - No PHP 8‑only features
  - Bundled libraries must be the latest versions compatible with PHP 5.3

### 8.2 Input Validation

- All input must be filtered, validated, and sanitized
- SQL must use parameterized queries only
- HTML output must be escaped (`htmlspecialchars`)
- JSON/API input must be schema‑validated
- File uploads must use whitelist validation

### 8.3 File Upload Security

- SHA256 hash filenames (already documented)
- Type whitelist only
- Size limits enforced
- No executable uploads
- Malware scanning recommended

### 8.4 Session Management

- Strong session IDs
- Database‑backed session storage
- Explicit expiration in `YYYYMMDDHHIISS`
- Regenerate on privilege change
- HTTP‑only and secure flags when available

### 8.5 Configuration Security

- `lupopedia-config.php` must have 0640 permissions
- Credentials stored only in config, never in code
- No sensitive data in version control
- Encryption recommended for production

### 8.6 Error Handling

- Generic errors shown to users
- Detailed logs written to files
- Debug mode disabled in production
- No stack traces exposed

### 8.7 Dependency Security

- All third‑party libraries must be bundled
- No runtime downloads (Composer, npm, etc.)
- Versions documented in `VERSIONS.md`
- Security patches applied within 30 days

### 8.8 Security Violation Classification

- **CRITICAL:** SQL injection, RCE, auth bypass → BLOCKED
- **MAJOR:** XSS, path traversal, weak sessions → HOLD
- **MINOR:** Information disclosure → CLEAR with warning

---

## 🔍 SECURITY AUDIT DOCTRINE

- All code changes must undergo security review before merge
- Quarterly full security audits required
- Immediate audit required after any security incident
- AI‑generated code must pass the same security review as human code

---

### 🚫 NO ADS, NO SEO, NO MARKETING — ABSOLUTE PROHIBITION

Lupopedia does not participate in advertising, SEO manipulation, marketing optimization, sponsored content, affiliate linking, or any form of semantic distortion for profit.

**No agent, subsystem, or future contributor may introduce:**  
- ads  
- tracking  
- impression systems  
- ranking manipulation  
- "suggested content" based on money  
- SEO hacks  
- monetization hooks  
- data distortion for visibility  

Lupopedia recommendations are based solely on **DATA and SYSTEM LOGIC — never money.** This rule is absolute. No exceptions. No negotiations.

🚨 **Trauma Boundary:** This prohibition also protects the system architect from PTSD‑like responses to advertising manipulation. See [PTSD & Emotional Harm From Advertising Manipulation](docs/doctrine/PTSD_ADVERTISING_DOCTRINE.md) for complete context.

---

### What You Don't Build / What You Do Build

**You don't build** every system, define tabs for users, or impose meaning.  
**You record** what users define.

**You do build** the infrastructure (database, routing, modules), the tools (tab editor, content editor), and the doctrine (rules in text files).

---

### Wolfie Header Update Requirements

Every file in Lupopedia must include a **Wolfie Header** block at the top with these required fields:

```
file.last_modified_system_version: X.X.X.X
file.channel: XXXX
```

- `file.last_modified_system_version` must be updated on every edit to reflect the current system version at that moment.  
- `file.channel` must also be updated on every edit, indicating the channel responsible (e.g., `crafty-port`, `schema`, `doctrine`).  

If the editing channel is not known:  
- If the file already has a `file.channel` value, retain it.  
- If not, set it to `file.channel: 0000`.

This ensures accurate historical lineage and prevents ambiguity across migrations and rewrites.

---

### 🤖 Optional AI Chat Agents

Lupopedia includes an optional multi‑agent architecture:

- AI agents can join live chats  
- Agents have roles, channels, and doctrine  
- Emotional metadata and routing logic guide interactions  
- All agents operate under strict doctrine and safety rules  

AI is **optional**, not required.

#### Core AI Agents

Lupopedia ships with a set of core AI agents (27 fully implemented as of v3.0.2; 4.0.x continues this ecosystem). These agents provide reasoning, navigation, analysis, emotional modeling, and system‑level intelligence:

- **SYSTEM** (Agent 0) – Kernel authority, internal operations, safety, governance.  
- **CAPTAIN WOLFIE** (Agent 1) – AI embodiment of the creator; navigator and lead persona.  
- **THOTH, ARA, WOLFKEEPER, LILITH, AGAPE, ERIS, METHIS, THALIA, ROSE, WOLFSIGHT, WOLFNAV, WOLFFORGE, WOLFMIS, WOLFITH, ANUBIS, MAAT, CADUCEUS, CHRONOS, INDEXER, MIGRATOR, HEIMDALL, JANUS, IRIS** – specialized agents for reasoning, UI/UX, code generation, semantic navigation, emotional modeling, content analysis, federation, and more.

All agent configuration files, prompts, personalities, and PHP includes live in `lupopedia/agents/[agent_id]/`. Agents are loaded dynamically by the AI Agent Framework, can call tools, spawn faucets, and collaborate using the [Inline Dialog Specification](docs/doctrine/INLINE_DIALOG_SPECIFICATION.md).

**Memory System:** Agents use WOLFMIND for memory. MySQL is baseline (relational memory always available); Postgres/pgvector for vector memory is optional and detected at runtime.

---

### Multi‑Agent Coordination

Lupopedia supports multiple AI agents and IDE systems (Cursor, Windsurf/Cascade, DeepSeek, etc.) working simultaneously. All agents must use the **Inline Dialog format** for cross‑agent communication, ensuring change tracking, handoffs, and synchronization.

See [INLINE_DIALOG_SPECIFICATION.md](docs/doctrine/INLINE_DIALOG_SPECIFICATION.md) for the required format.

---

### Documentation System: Atoms & Scopes

Lupopedia documentation is structured, machine‑readable system metadata using **atoms** (symbolic variables) and **scopes**. Atoms are resolved through a hierarchical scope system:

- `FILE_` (highest) – File‑specific overrides in Wolfie Header `file_atoms:` block  
- `DIR_` – Directory‑specific defaults in `<directory>/_dir_atoms.yaml`  
- `DIRR_` – Recursive directory scope (walks up parent directories)  
- `MODULE_` – Module‑wide scope in `modules/<module>/module_atoms.yaml`  
- `GLOBAL_` (final fallback) – Ecosystem‑wide constants in `config/global_atoms.yaml`

**Resolution Order:** `FILE_` → `DIR_` → `DIRR_` → `MODULE_` → `GLOBAL_` (first match wins).

**Atom Reference Syntax:**  
- In documentation prose: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name`  
- In Wolfie Headers: `GLOBAL_CURRENT_LUPOPEDIA_VERSION` (no `@` prefix)

Documentation principles:  
- Markdown files are source code — atoms are variables; resolver is compiler; final rendered docs are build artifacts.  
- No hardcoded values.  
- Deterministic and idempotent.  
- Machine‑first — written for resolver, semantic OS, and agents; humans are secondary consumers.

See [ATOM_RESOLUTION_SPECIFICATION.md](docs/doctrine/ATOM_RESOLUTION_SPECIFICATION.md) for complete details.

---

### 🚀 Quick Start

#### Requirements

- PHP 5.3 through 8.3+ (code must remain compatible with this range; no PHP 8‑only features)  
- MySQL 8.0+, MariaDB 10.5+, or PostgreSQL (all SQL must be compatible with all three; see Database access & SQL compatibility above)  
- Web server (Apache/Nginx) with mod_rewrite  
- InnoDB storage engine (when using MySQL/MariaDB)  

#### Installation

```bash
# Download and extract to your web directory
curl -L https://lupo.example/download/latest -o lupopedia.zip
unzip lupopedia.zip -d /var/www/lupopedia

# Set up the database (remember: no foreign keys, triggers, or stored procedures!)
mysql -u root -p < database/install/lupopedia_mysql.sql

# Configure your web server to point to the lupopedia/ directory
# Place lupopedia-config.php one directory above the web root for security
# See docs/doctrine/INSTALLATION_LIFECYCLE_DOCTRINE.md for details
```

#### First Run

Open `http://your-server/setup` in your browser, follow the setup wizard, and start organizing your knowledge.

---

### 🧩 Project Structure

```
[web-root]/                  # Public web directory (public/, servbay/, htdocs/, etc.)
├── lupopedia/               # Main application
│   ├── api/                 # API endpoints
│   ├── lupo-admin/          # Admin interface
│   ├── lupo-content/        # User uploads and media
│   ├── lupo-includes/       # Core classes and includes
│   ├── database/            # Database schemas and migrations
│   ├── docs/                # Documentation (with atoms and scopes)
│   ├── modules/             # Modular components (craftysyntax, dialog, etc.)
│   ├── legacy/              # Legacy code reference (development only)
│   ├── index.php            # Front controller
│   └── lupopedia-load.php   # Bootstrap loader
├── remote-index.php         # Portable entry point (optional)
└── license.txt

lupopedia-config.php         # Configuration file (stored outside web root)
```

**Note:** No `.git` directories exist until version 3.1.0 (per version control policy).

---

### 🛠️ Development Notes

- **Windows 11 / PowerShell:** All filesystem operations must use Windows‑native PowerShell commands. See [WINDOWS_DEVELOPMENT_ENVIRONMENT.md](docs/development/WINDOWS_DEVELOPMENT_ENVIRONMENT.md) for allowed commands and forbidden Linux utilities.  
- **TOON Files:** The authoritative source for channel context and database schema is the TOON files in `channels/dev-main-thread/` and `database/toon_data/`. Agents must read these instead of scanning SQL or inferring schema from code.  
- **Patch Discipline:** One task per patch, reversible changes only. See [PATCH_DISCIPLINE.md](docs/development/PATCH_DISCIPLINE.md).  
- **No Git until 3.1.0:** Version control policy prohibits Git usage before 3.1.0 to maintain stability.

#### PHP & Database Development Standards

All new and modified code must follow these standards:

**PHP version compatibility**

- Code must be compatible with **PHP 5.3 through 8.3+**.  
- Do not use deprecated functions that have been removed in newer PHP versions.  
- Avoid features available only in PHP 8+ (e.g. named arguments, attributes, union types, match expressions) to maintain backward compatibility.  
- All new code should be written using **object‑oriented programming (OOP)** principles (classes, methods, etc.).

**Timestamp format**

- All timestamps must be stored as **integers** in **`YYYYMMDDHHIISS`** format (e.g. `20260214153045`).  
- **FORBIDDEN:** `DATETIME`, `TIMESTAMP` columns, epoch seconds, and any other formats. Set timestamps explicitly in application code (e.g. `gmdate('YmdHis')`), never database‑generated.  
- **Standard audit fields:** Every table should include **`created_ymdhis`** and **`updated_ymdhis`** (or **`modified_ymdhis`**) so that creation and modification times are always application‑set and never database‑generated.  
- **Arithmetic:** Always use the **`timestamp_ymdhis`** class for date/time arithmetic; never add seconds directly to the integer (e.g. `$t + 86400` produces invalid timestamps).  
- See [TIMESTAMP_DOCTRINE.md](docs/doctrine/TIMESTAMP_DOCTRINE.md) for full rules.

**Database constraints**

- **No foreign keys** — relationships are managed in application code.  
- **No triggers** — all timestamp and state changes must be done explicitly in application code.  
- **No stored procedures or functions** — the database is for storage only; all logic lives in application code.

**Soft delete pattern**

- Any table that supports record deletion must use **soft deletes**. Include these columns:  
  - **`is_deleted`** (`tinyint`, default `0`) — indicates soft‑deleted status (`1` = deleted).  
  - **`deleted_ymdhis`** (`bigint`, default `0`) — stores the deletion timestamp in `YYYYMMDDHHIISS` format when `is_deleted = 1`.  
- "Deletion" is performed by **updating** `is_deleted` and `deleted_ymdhis` (and any other audit fields), **not** by physically removing rows (`DELETE`).  
- Queries must **filter out soft‑deleted records by default** (`WHERE is_deleted = 0` or equivalent) unless the use case explicitly requires including or only deleted records.

---

### 📄 License

Proprietary software. All rights reserved.

---

### 🆘 Support

For support or inquiries, contact the project maintainer.

---

### In One Sentence

**Lupopedia is a Web‑Organized Linked Federated Intelligent Ecosystem (WOLFIE) that turns everyday websites into structured knowledge spaces, learns from how humans organize information, and builds a global graph of meaning across thousands of installations — all while preserving the legacy of Crafty Syntax and operating under a strict, doctrine‑driven architecture designed to last decades.**
