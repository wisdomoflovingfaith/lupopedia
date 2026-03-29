---
lupopedia.headers:
  when_updated: "20260329200000"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/ORGANIZATION.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/ORGANIZATION.md"
  last_modified_utc: "20260329200000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "organization_guide"
  purpose: "Detailed map of lupo-docs structure, doctrine locations, version folders, and documentation authority boundaries"
  tags: ["documentation", "organization", "4.0.89", "doctrine", "versions"]
  namespace: "documentation"
lupopedia.footer:
  last_verified: "20260329200000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Keep this map aligned with root ORGANIZATION.md"
    - "Mark stale sections when doctrine diverges from repository reality"
---

# lupo-docs Organization (4.0.89)

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

1. `lupo-docs/doctrine/` for binding doctrine
2. `lupo-docs/versions/<version>/` for version-scoped planning/execution history
3. `lupo-docs/database/` for database/table documentation surfaces

Supporting or historical surfaces:

- `lupo-docs/archive/` and `lupo-docs/archived/`
- older standalone docs that still carry FLARE-era metadata style

### 2.1 Agent types and LUPOPEDIA HEADERS import toolchains (4.0.89+)

Markdown artifacts with **LUPOPEDIA HEADERS** (`lupo-rules/`, `lupo-docs/`, `lupo-channels/`, …) are written by **both** IDE-side agents and server-side PHP agents. **Import** pushes the same canonical state into **`lupo_contents`** (plus header sync tables); **both** toolchains must agree on **`content_id`** and sync semantics.

| Agent type | Examples | Typical runtime | Import / validate / regenerate |
|------------|----------|-----------------|----------------------------------|
| **IDE agents** | Cursor, Windsurf, Kiro | Developer machine | **Python:** `import_content.py`, `validate_lupopedia_headers.py`, `generate_headers_from_db.py` |
| **PHP agents** | DeepSeek, OpenAI, Grok (LLM via HTTP API on PHP) | Shared hosting / app server | **PHP:** `import_content.php`, `validate_lupopedia_headers.php`, `generate_headers_from_db.php` |

**Policy:** PHP `import_content.php` defaults to **database import only** (no file mutation); use **`--write-back`** when the environment may rewrite the markdown file (Python `import_content.py` always write-backs `content_id`). Details: **`lupo-docs/versions/4.0.89/TODO.md` H8**, **`lupo-docs/versions/4.0.89/README.md`**.

### 2.2 Filesystem boundaries by agent type (4.0.89+)

Lupopedia distinguishes **IDE agents** (developer tools used with a human on a full checkout) from **PHP agents** (LLM content authors on shared hosting / PHP runtime). **Filesystem write policy applies only to PHP agents** at the guarded write layer; IDE agents are not limited by **`AgentFileWriter`**.

#### IDE agents (Cursor, Windsurf, Kiro, …)

**Role:** Developers building and maintaining the system (with a human orchestrator).

**Write access:** **Full repository access** — any path the workflow needs, including:

- **`lupo-includes/`** — PHP classes and includes
- **`app/`** — application services
- **`lupo-scripts/`** — Python and PHP tooling
- **`lupo-bin/`** — CLI utilities
- **`lupo-api/`**, **`lupo-routes/`**, **`lupo-views/`**, **`lupo-admin/`** — application structure
- Root PHP entry points (**`index.php`**, **`admin.php`**, **`install.php`**, …)
- Any **`.php`**, **`.py`**, **`.sh`**, **`.js`**, … as required for the product
- Documentation and content trees (**`lupo-docs/`**, **`lupo-channels/`**, **`lupo-rules/`**, …)

**Rationale:** IDE agents are trusted developer surfaces; they must be able to edit code, refactor, and ship changes across the repo. Project rules (e.g. LUPOPEDIA HEADERS, naming) still apply as **documentation and process**, not as a PHP **`AgentFileWriter`** gate.

#### PHP agents (DeepSeek, OpenAI, Grok via API, …)

**Role:** **Content-only** authors (LLM-powered, server-side).

**Write access:** **Strictly restricted**

- **Allowed directories:** only **`lupo-rules/`**, **`lupo-docs/`**, **`lupo-channels/`**, **`lupo-content/`**
- **Allowed extensions:** only **`.md`**, **`.txt`**, **`.yaml`**, **`.yml`**, **`.json`**, **`.csv`**, **`.xml`**
- **Forbidden directories (examples):** **`lupo-includes/`**, **`app/`**, **`lupo-scripts/`**, **`lupo-bin/`**, **`lupo-api/`**, **`lupo-routes/`**, **`lupo-views/`**, **`lupo-admin/`**, **`lupo-install/`**, web / repo root for entrypoint PHP
- **Forbidden extensions (examples):** **`.php`**, **`.phtml`**, **`.phar`**, **`.sh`**, **`.bash`**, **`.py`**, **`.js`**, **`.html`**, **`.htm`**, **`.pl`**, **`.cgi`**, …
- **Content scanning:** in **agent** context, writes are scanned for executable / active-content patterns (e.g. **`<?php`**, **`<script>`**, …) via **`AgentFileWriter`**

**Rationale:** PHP agents can be prompt-injected; restricting paths and types reduces remote code execution, XSS/HTML abuse, and shell-style tooling writes. They **invoke** existing CLI scripts to import/validate; they do **not** replace maintainers for runtime code.

**Enforcement:** **`lupo-includes/classes/AgentFileWriter.php`** (**`TODO.md` H9**). Automated PHP agent outputs must use **`CONTEXT_AGENT`**. Human/CI **`import_content.php --write-back`** / **`generate_headers_from_db.php`** use **`CONTEXT_OPERATOR`** (path + extension still enforced; content scan skipped for legitimate doc examples). Web hardening: **`lupo-docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`**.

#### Summary: write targets

| Write target | IDE agents | PHP agents |
|--------------|------------|------------|
| Code dirs (`lupo-includes/`, `app/`, `lupo-scripts/`, …) | Allowed | Forbidden |
| Root PHP entry points | Allowed | Forbidden |
| **`.php`**, **`.py`**, **`.sh`**, **`.js`** | Allowed | Forbidden |
| **`.html`**, **`.htm`** | Allowed | Forbidden (XSS / active markup) |
| **`lupo-rules/`**, **`lupo-docs/`**, **`lupo-channels/`**, **`lupo-content/`** + safe extensions | Allowed | Allowed |
| **`.md`**, **`.txt`**, **`.yaml`**, **`.json`**, **`.csv`**, **`.xml`** (under allowed dirs) | Allowed | Allowed |

## 3. Main `lupo-docs/` Sections

| Path | Primary use in 4.0.88 | Status |
|---|---|---|
| `lupo-docs/doctrine/` | Canonical doctrine and policy constraints | Active canonical |
| `lupo-docs/versions/` | Version-by-version planning and changelog/todo/report surfaces | Active canonical |
| `lupo-docs/database/` | Database docs and per-table markdown docs | Active, mixed freshness |
| `lupo-docs/architecture/` | Architecture explanation docs | Active reference |
| `lupo-docs/status/` | Status snapshots/reports | Active but mixed freshness |
| `lupo-docs/reports/` | Report artifacts | Active reference |
| `lupo-docs/reference/` | Reference docs | Active reference |
| `lupo-docs/prompts/` | Prompt documentation references | Active support |
| `lupo-docs/channels/` | Channel-related documentation appendices/indexes | Active, mixed freshness |
| `lupo-docs/archive/`, `lupo-docs/archived/` | Historical/deprecated snapshots | Archive |

## 4. Version Documentation Structure

Version folders are at:

- `lupo-docs/versions/4.0.80/`
- `lupo-docs/versions/4.0.84/`
- `lupo-docs/versions/4.0.85/`
- `lupo-docs/versions/4.0.86/`
- `lupo-docs/versions/4.0.87/`
- `lupo-docs/versions/4.0.88/`
- `lupo-docs/versions/4.0.89/` — **LUPOPEDIA HEADERS** release scope (validation, import, `lupo-*` / IDE rules, header DB, **`lupopedia.history` ↔ `revision_history` running log** per `TODO.md` H7, **Python + PHP** dual toolchain for import/validate/regenerate per `TODO.md` H8, release tests)
- `lupo-docs/versions/4.0.90/` — backlog for context model, Crafty Syntax execution, doc-clarity tasks deferred from 4.0.89
- `lupo-docs/versions/4.1.0/`

**Current focus:** **4.0.89** (headers path to tag, including documented **dual audit trail** in binding doctrine); **4.0.90** holds non-header product work previously listed under 4.0.89.

Typical version surfaces:

- `README.md`
- `PLAN.md`
- `TODO.md`
- `CHANGELOG.md`
- supplemental reports/PRD docs

## 5. Database Documentation Surfaces

Documentation-facing DB map:

- `lupo-docs/database/lupopedia/tables/active/` = per-table docs
- `lupo-docs/database/lupopedia/` = database-level docs/indexes

Schema authority relationship for readers:

- authoritative install DDL: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- derived snapshots: `lupo-database/lupopedia/toon/` and `lupo-database/lupopedia/json/`
- docs should describe, not override, install DDL authority

## 6. Channel and Session Documentation Surfaces

Documentation about channel/session systems appears in:

- `lupo-docs/channels/`
- doctrine files under `lupo-docs/doctrine/`
- version documents under `lupo-docs/versions/`

Operational file artifacts themselves live outside `lupo-docs`:

- `lupo-channels/`
- `lupo-database/sessions/`
- `lupo-sessions/`

## 7. What Belongs at Root vs `lupo-docs/`

Root should contain orientation entrypoints only:

- `README.md`
- `ORGANIZATION.md`
- high-level plan/todo/report summary files

Detailed docs should live under `lupo-docs/`:

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

- `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md`

## 9. Developer Reading Path for Structure Understanding

1. `README.md`
2. `ORGANIZATION.md`
3. `lupo-docs/ORGANIZATION.md`
4. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
5. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`
6. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md`
7. `lupo-docs/versions/4.0.88/README.md`
8. `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md`

## 10. 4.0.88 Organization Pass Note

This file was rewritten in the 4.0.88 documentation organization pass to separate:

- canonical documentation surfaces
- version execution surfaces
- database reference docs
- archive/historical documentation surfaces

The goal is faster onboarding and less doctrine/path ambiguity for IDE agents and human contributors.
