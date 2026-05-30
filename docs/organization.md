---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/organization.md
  web_path: https://www.lupopedia.com/lupopedia/docs/organization.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: organization_guide
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# docs Organization (4.0.89)

## 1. Purpose

This file is the documentation-side structure guide.

It explains:

- where canonical doctrine lives
- where version-specific planning lives
- where database table docs live
- where status/reports/reference/archives live
- which documentation surfaces are active vs stale vs archival

## 2. Documentation Authority Model

Primary documentation authority surfaces:

1. `docs/doctrine/` for binding doctrine
2. `docs/versions/<version>/` for version-scoped planning/execution history
3. `docs/database/` for database/table documentation surfaces

Supporting or historical surfaces:

- `docs/archive/` and `docs/archived/`
- older standalone docs that still carry FLARE-era metadata style

### 2.1 Agent types and LUPOPEDIA HEADERS import toolchains (4.0.89+)

Markdown artifacts with **LUPOPEDIA HEADERS** (`rules/`, `docs/`, `channels/`, …) are written by **both** IDE-side agents and server-side PHP agents. **Import** pushes the same canonical state into **`lupo_contents`** (plus header sync tables); **both** toolchains must agree on **`content_id`** and sync semantics.

| Agent type | Examples | Typical runtime | Import / validate / regenerate |
|------------|----------|-----------------|----------------------------------|
| **IDE agents** | Cursor, Windsurf, Kiro | Developer machine | **Python:** `import_content.py`, `validate_lupopedia_headers.py`, `generate_headers_from_db.py` |
| **PHP agents** | DeepSeek, OpenAI, Grok (LLM via HTTP API on PHP) | Shared hosting / app server | **PHP:** `import_content.php`, `validate_lupopedia_headers.php`, `generate_headers_from_db.php` |

**Policy:** PHP `import_content.php` defaults to **database import only** (no file mutation); use **`--write-back`** when the environment may rewrite the markdown file (Python `import_content.py` always write-backs `content_id`). Details: **`docs/versions/4.0.89/TODO.md` H8**, **`docs/versions/4.0.89/README.md`**.

### 2.2 Filesystem boundaries by agent type (4.0.89+)

Lupopedia distinguishes **IDE agents** (developer tools used with a human on a full checkout) from **PHP agents** (LLM content authors on shared hosting / PHP runtime). **Filesystem write policy applies only to PHP agents** at the guarded write layer; IDE agents are not limited by **`AgentFileWriter`**.

#### IDE agents (Cursor, Windsurf, Kiro, …)

**Role:** Developers building and maintaining the system (with a human orchestrator).

**Write access:** **Full repository access** — any path the workflow needs, including:

- **`includes/`** — PHP classes and includes
- **`app/`** — application services
- **`scripts/`** — Python and PHP tooling
- **`bin/`** — CLI utilities
- **`api/`**, **`routes/`**, **`views/`**, **`admin/`** — application structure
- Root PHP entry points (**`index.php`**, **`admin.php`**, **`install.php`**, …)
- Any **`.php`**, **`.py`**, **`.sh`**, **`.js`**, … as required for the product
- Documentation and content trees (**`docs/`**, **`channels/`**, **`rules/`**, …)

**Rationale:** IDE agents are trusted developer surfaces; they must be able to edit code, refactor, and ship changes across the repo. Project rules (e.g. LUPOPEDIA HEADERS, naming) still apply as **documentation and process**, not as a PHP **`AgentFileWriter`** gate.

#### PHP agents (DeepSeek, OpenAI, Grok via API, …)

**Role:** **Content-only** authors (LLM-powered, server-side).

**Write access:** **Strictly restricted**

- **Allowed directories:** only **`rules/`**, **`docs/`**, **`channels/`**, **`content/`**
- **Allowed extensions:** only **`.md`**, **`.txt`**, **`.yaml`**, **`.yml`**, **`.json`**, **`.csv`**, **`.xml`**
- **Forbidden directories (examples):** **`includes/`**, **`app/`**, **`scripts/`**, **`bin/`**, **`api/`**, **`routes/`**, **`views/`**, **`admin/`**, **`install/`**, web / repo root for entrypoint PHP
- **Forbidden extensions (examples):** **`.php`**, **`.phtml`**, **`.phar`**, **`.sh`**, **`.bash`**, **`.py`**, **`.js`**, **`.html`**, **`.htm`**, **`.pl`**, **`.cgi`**, …
- **Content scanning:** in **agent** context, writes are scanned for executable / active-content patterns (e.g. **`<?php`**, **`<script>`**, …) via **`AgentFileWriter`**

**Rationale:** PHP agents can be prompt-injected; restricting paths and types reduces remote code execution, XSS/HTML abuse, and shell-style tooling writes. They **invoke** existing CLI scripts to import/validate; they do **not** replace maintainers for runtime code.

**Enforcement:** **`includes/classes/AgentFileWriter.php`** (**`TODO.md` H9**). Automated PHP agent outputs must use **`CONTEXT_AGENT`**. Human/CI **`import_content.php --write-back`** / **`generate_headers_from_db.php`** use **`CONTEXT_OPERATOR`** (path + extension still enforced; content scan skipped for legitimate doc examples). Web hardening: **`docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`**.

#### Summary: write targets

| Write target | IDE agents | PHP agents |
|--------------|------------|------------|
| Code dirs (`includes/`, `app/`, `scripts/`, …) | Allowed | Forbidden |
| Root PHP entry points | Allowed | Forbidden |
| **`.php`**, **`.py`**, **`.sh`**, **`.js`** | Allowed | Forbidden |
| **`.html`**, **`.htm`** | Allowed | Forbidden (XSS / active markup) |
| **`rules/`**, **`docs/`**, **`channels/`**, **`content/`** + safe extensions | Allowed | Allowed |
| **`.md`**, **`.txt`**, **`.yaml`**, **`.json`**, **`.csv`**, **`.xml`** (under allowed dirs) | Allowed | Allowed |

## 3. Main `docs/` Sections

| Path | Primary use in 4.0.88 | Status |
|---|---|---|
| `docs/doctrine/` | Canonical doctrine and policy constraints | Active canonical |
| `docs/versions/` | Version-by-version planning and changelog/todo/report surfaces | Active canonical |
| `docs/database/` | Database docs and per-table markdown docs | Active, mixed freshness |
| `docs/architecture/` | Architecture explanation docs | Active reference |
| `docs/status/` | Status snapshots/reports | Active but mixed freshness |
| `docs/reports/` | Report artifacts | Active reference |
| `docs/reference/` | Reference docs | Active reference |
| `docs/prompts/` | Prompt documentation references | Active support |
| `docs/channels/` | Channel-related documentation appendices/indexes | Active, mixed freshness |
| `docs/archive/`, `docs/archived/` | Historical/deprecated snapshots | Archive |

## 4. Version Documentation Structure

Version folders are at:

- `docs/versions/4.0.80/`
- `docs/versions/4.0.84/`
- `docs/versions/4.0.85/`
- `docs/versions/4.0.86/`
- `docs/versions/4.0.87/`
- `docs/versions/4.0.88/`
- `docs/versions/4.0.89/` — **LUPOPEDIA HEADERS** release scope (validation, import, `*` / IDE rules, header DB, **`lupopedia.history` ↔ `revision_history` running log** per `TODO.md` H7, **Python + PHP** dual toolchain for import/validate/regenerate per `TODO.md` H8, release tests)
- `docs/versions/4.0.90/` — backlog for context model, Crafty Syntax execution, doc-clarity tasks deferred from 4.0.89
- `docs/versions/4.1.0/`

**Current focus:** **4.0.89** (headers path to tag, including documented **dual audit trail** in binding doctrine); **4.0.90** holds non-header product work previously listed under 4.0.89.

Typical version surfaces:

- `README.md`
- `PLAN.md`
- `TODO.md`
- `CHANGELOG.md`
- supplemental reports/PRD docs

## 5. Database Documentation Surfaces

Documentation-facing DB map:

- `docs/database/lupopedia/tables/active/` = per-table docs
- `docs/database/lupopedia/` = database-level docs/indexes

Schema authority relationship for readers:

- authoritative install DDL: `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- derived snapshots: `database/lupopedia/toon/` and `database/lupopedia/json/`
- docs should describe, not override, install DDL authority

## 6. Channel and Session Documentation Surfaces

Documentation about channel/session systems appears in:

- `docs/channels/`
- doctrine files under `docs/doctrine/`
- version documents under `docs/versions/`

### **Channel-to-Context Lifecycle**

**Hybrid-Mirror Architecture (Option B+)**:
- **Truth Layer**: Live state in database (`lupo_channels`, `lupo_dialog_threads`, `lupo_dialog_messages`)
- **Memory Layer**: Archival mirrors in filesystem (`channels/42/threads/`, `broadcasts/`, `content/`)
- **Mirror Process**: DB → FS export when threads close/canonize; FS → DB only during legacy import

**Discussion Phase (Database-First)**:
- Live coordination occurs in database tables
- Web interface reads **only** from database
- Real-time chat, concurrency, search all database-driven
- Headers include: `channel_id`, `thread_id` (required), `context_id` (null during discussion)

**Finalization Phase (Context Promotion)**:
- Artifacts gain `context_id` reference for permanent knowledge base
- Headers retain `channel_id` and `thread_id` for provenance
- Thread closure triggers export to filesystem for archival
- Git lineage reads **only** from filesystem mirrors

**Database Architecture**:
```
Live System (Database) → Cold Archive (Filesystem)
        ↓                           ↓
   lupo_channels              channels/42/threads/
   lupo_dialog_threads       channels/42/broadcasts/
   lupo_dialog_messages      channels/42/content/
        ↓                           ↓
   lupo_edges (polymorphic relationships)
```

**Boundary Definitions**:
- **Web UI**: Reads **only** from database tables
- **Git Lineage**: Reads **only** from filesystem mirrors  
- **Doctrine**: Lives in both database and filesystem (mirrored)
- **Legacy Filesystem**: Marked as "pre-canonical" - no longer source of truth

**Context Features**:
- **Context Storage**: `lupo_contexts` table (main knowledge storage)
- **Context Cards**: `lupo_context_cards` table (context metadata)
- **Context Mapping**: `lupo_contexts_map` table (context relationships)
- **Truth Context**: `lupo_truth_context_map` table (truth relationships)
- **Questions/Answers**: Implemented via `lupo_edges` with `edge_type = 'context_question_answer'`
- **Navigation**: Semantic search through context relationships via `lupo_edges`

**Import Status**:
- **Current State**: Filesystem contains coordination work not yet imported to database
- **Migration Required**: Execute `SyncChannelsToDb.php --commit` after new install
- **Pre-Canonical**: All existing filesystem artifacts marked as legacy pending import
- **Future State**: Database-first with filesystem mirrors for archival

**Channel Cleanup**:
Channels can be deleted after discussion finalization:
- **Artifacts**: Preserved via `context_id` reference in database
- **Knowledge**: Maintained in `lupo_contexts` table
- **Provenance**: Retained through filesystem mirrors and edge relationships
- **Archive**: Complete history available in filesystem for Git lineage

## 7. What Belongs at Root vs `docs/`

Root should contain orientation entrypoints only:

- `README.md`
- `ORGANIZATION.md`
- high-level plan/todo/report summary files

Detailed docs should live under `docs/`:

- doctrine and policy details
- version execution details
- table-level DB documentation
- specialized architecture/reference reports

## 8. Documentation Gaps and Drift (Current)

Observed issues in current docs set:

- multiple files still use deprecated header/version metadata patterns
- several docs still reference old paths and FLARE-era terminology as if current
- some directory-structure docs contradict actual repository layout
- database docs include stale counts/statements not aligned to current generated TOON/JSON state

For explicit file-by-file contradictions and recommended corrections, see:

- `docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md`

## 9. Developer Reading Path for Structure Understanding

1. `README.md`
2. `ORGANIZATION.md`
3. `docs/ORGANIZATION.md`
4. `docs/doctrine/LUPOPEDIA_HEADERS/README.md`
5. `docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`
6. `docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md`
7. `docs/versions/4.0.88/README.md`
8. `docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md`

## 10. 4.0.88 Organization Pass Note

This file was rewritten in the 4.0.88 documentation organization pass to separate:

- canonical documentation surfaces
- version execution surfaces
- database reference docs
- archive/historical documentation surfaces

The goal is faster onboarding and less doctrine/path ambiguity for IDE agents and human contributors.
