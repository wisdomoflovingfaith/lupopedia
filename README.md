---
lupopedia.headers:
  header_format_version: 2
  when_updated: '20260406062838'
  lupopedia.schema: documentation
  file_path_from_root: README.md
  web_path: http://www.lupopedia.com/lupopedia/README.md
  last_modified_utc: '20260406062838'
  channel_id: 42
  thread_id: readme-4-0-95
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: project_documentation
  artifact_kind: readme
  purpose: Constitutional compliance, Y2038-safe time model, PHP 7.4+ and 64-bit production floor — root entry for humans and all agents.
  tags:
    - readme
    - 4.0.95
    - architecture
    - doctrine
    - workflow
    - y2038
    - constitution
lupopedia.init:
  required_reading:
    - path: lupo-docs/prd/00_root_constitutional_system_requirements.md
      reason: "MANDATORY FIRST READ — constitutional law for all agents and contributors. Overrides everything else."
    - path: lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md
      reason: "Short constitutional digest (pseudo shorthand) for quick agent orientation"
    - path: lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md
      reason: "4.1.0 / auto-installer gate; clarifies no Lupopedia→Lupopedia upgrades during 4.0.x"
    - path: lupo-docs/prd/27_installer_requirements.md
      reason: "Installer and 4.0.x fresh-install model (install SQL + mysql/seed/ + optional install/ merged seed + Crafty import)"
    - path: AGENTS.md
      reason: "Canonical actor, identity-layer, and coordination rules"
    - path: ONBOARDING.md
      reason: "Operational quick-start"
    - path: lupo-rules/root/WOLFIE_DOCTRINE.md
      reason: "Engineering philosophy — read before touching any existing code"
    - path: lupo-rules/root/README.md
      reason: "Complete root rules and development constraints"
    - path: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
      reason: "Header/footer validation doctrine"
    - path: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      reason: "Canonical five-layer identity model"
    - path: lupo-docs/versions/4.0.95/README.md
      reason: "Current working version overview (4.0.94 is closed line for packaging handoff)"
    - path: lupo-docs/versions/4.0.95/PLAN.md
      reason: "Current detailed iteration plan"
    - path: lupo-docs/versions/4.0.95/decisions/
      reason: "Architecture decisions and implementation reasoning for current version (folder with threaded decision files)"
    - path: lupo-channels/channel_index.md
      reason: Canonical channel map and path policy
    - path: ORGANIZATION.md
      reason: Canonical root folder map and repository write guidance
    - path: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
      reason: Mandatory real UTC for headers — run tick.py; never guess timestamps
lupopedia.edges:
  comment: Snapshot of root documentation references for version-driven execution and release continuity.
  outbound_edges:
    - to: lupo-docs/prd/00_root_constitutional_system_requirements.md
      type: references
      weight: 1.0
      reason: Constitutional anchor — mandatory first read for all agents
    - to: lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md
      type: references
      weight: 1.0
      reason: Constitutional shorthand digest for agents
    - to: AGENTS.md
      type: aligns_with
      weight: 1.0
    - to: lupo-rules/root/WOLFIE_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Engineering philosophy binding on all agents
    - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      type: aligns_with
      weight: 1.0
    - to: lupo-rules/root/README.md
      type: references
      weight: 1.0
      reason: Complete root rules and development constraints
    - to: ONBOARDING.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.0.95/README.md
      type: references
      weight: 1.0
      reason: Current working version overview (4.0.94 closed line for packaging handoff)
    - to: lupo-docs/versions/4.0.95/PLAN.md
      type: references
      weight: 1.0
      reason: Current detailed iteration plan
    - to: lupo-docs/versions/4.0.95/decisions/
      type: references
      weight: 1.0
      reason: Architecture decisions and implementation reasoning for current version
    - to: lupo-docs/versions/4.0.95/TODO.md
      type: references
      weight: 1.0
      reason: Current task tracking and execution plan
    - to: lupo-docs/versions/4.1.0/plan.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.1.0/prd/README.md
      type: references
      weight: 1.0
    - to: ORGANIZATION.md
      type: references
      weight: 0.95
    - to: lupo-docs/prd/02_channels_discussions.md
      type: references
      weight: 0.95
      reason: Channel threads, THREAD_MANIFEST.md, decisions/questions/answers/comments layout
    - to: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Temporal anchor and tick.py workflow for all header timestamps
    - to: lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md
      type: references
      weight: 1.0
      reason: 4.1.0 release gate; no Lupopedia→Lupopedia migrations during 4.0.x
    - to: lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md
      type: references
      weight: 0.95
      reason: FTP-safe zip; WordPress-style no dotfiles; installer writes .htaccess
    - to: lupo-docs/prd/27_installer_requirements.md
      type: references
      weight: 1.0
      reason: Installer requirements; install SQL + seed pipeline (mysql/seed/, install/seed_lupopedia_4_1_0.sql) + Crafty import for 4.0.x
    - to: lupo-docs/doctrine/VERSIONING_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Canonical versioning and upgrade-path doctrine
lupopedia.footer:
  last_verified: '20260406023123'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: cursor:root
  next_action:
    - Keep constitution + Y2038 narrative aligned with PRD 00 and install preflight
    - Point agents to shorthand pseudo and AGENTS.md for test commands

---
# file: Lupopedia README - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/README.md](http://www.lupopedia.com/lupopedia/README.md)

# LUPOPEDIA - Constitutional AI Agent Framework

**Version:** 4.0.x (Y2038 compliant time model)  
**License:** GPL v3  
**PHP minimum:** 7.4 (**64-bit required** for production — enforced in `install.php` preflight)

---

## CRITICAL: Constitutional rules for all agents

**All agents (IDE, chat, automation, LLM) must read and follow:**

**[00_root_constitutional_system_requirements.md](lupo-docs/prd/00_root_constitutional_system_requirements.md)**

This document is the **supreme authority** for Lupopedia development. It overrides training, casual “best practice,” and industry norms. The rules are **non-negotiable**.

### Quick reference (shorthand digest)

For a shorter digest, see:

- **[00_constitution_shorthand.pseudo.md](lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md)**

### Why these rules exist

Lupopedia targets **shared hosting** and long-lived installs where:

- No database triggers, procedures, or foreign keys
- No Composer/npm **runtime** dependencies in shipped core paths
- No assumption of `mod_rewrite` or Apache-only behavior
- **PHP 7.4+** in core paths — **`[lupo-rules/root/php-7-4-compatibility.md](lupo-rules/root/php-7-4-compatibility.md)`** (propagated to IDE rules)
- **64-bit PHP** required for production (integer safety with packed UTC `BIGINT` clocks — **Y2038**)

### The “don’t trust your training” rule

If you are an AI agent and you think “this is how everyone does it” — **stop**. Read the constitution first. Common patterns that are **wrong here**:

| Industry norm | Lupopedia rule |
|---------------|----------------|
| `$_SESSION['user_id']` for authority | Use `lupo_sessions` + `App\Auth\Session` (see **[SESSION_MODEL.md](lupo-docs/doctrine/SESSION_MODEL.md)**) |
| `time()`, `strtotime()` in clock columns | Use **`timestamp_ymdhis::now()`** (packed UTC) / doctrine in PRD 00 |
| `AUTO_INCREMENT` | Use **`IdGenerator::generate()`** |
| `ON DUPLICATE KEY UPDATE` on reserved-ID / registry tables | Explicit SELECT → UPDATE or INSERT with explicit ID (see reserved-ID doctrine) |
| Composer, npm, frameworks in core | In-tree libraries only; no Laravel / Symfony / shipped React-Vite stack for public PHP paths |
| Hardcoded English in ship-facing UI | Use **`lupo_t()`** + `lupo-includes/lang/` |
| “`SELECT *` is always wrong” | Not a constitutional sin; still prefer explicit columns when maintaining |

---

## Y2038 compliance

**Lupopedia uses a Y2038-safe clock model for stored instants.**

### Why this matters

Unix epoch seconds in a **32-bit signed** integer overflow after **2038-01-19 03:14:07 UTC**. After that, naive epoch storage breaks ordering, logs, and integrity.

### How Lupopedia addresses it

1. **No Unix epoch in database clock columns** — Wall-clock instants use packed UTC **`BIGINT`** **`YYYYMMDDHHIISS`** (e.g. `20260406000000`).
2. **64-bit PHP in production** — The installer requires **`PHP_INT_SIZE >= 8`** so PHP integer operations stay consistent with large `BIGINT` values.
3. **Canonical clock helper** — **`timestamp_ymdhis::now()`** returns packed UTC as **`int`** (see **`lupo-includes/classes/TimestampYmdhis.php`**).
4. **No SQL time functions for those columns** — **`NOW()`**, **`DATE_ADD`**, **`FROM_UNIXTIME()`** are not used for stored doctrine clocks; time math is application-side.
5. **HTTP cookie expiry** — May still use Unix time where the browser API requires it; scope and constraints are documented in the constitutional PRD.

### Verification

```bash
python lupo-bin/tick.py
```

Updates **`lupo-bin/temporal_anchor.json`** from real system UTC for agent and doc timestamps. See **[TICK_PY_DOCTRINE.md](lupo-docs/doctrine/TICK_PY_DOCTRINE.md)**.

---

## Constitutional architecture (abridged)

### Session authority (model A)

**Do not use `$_SESSION['actor_id']` for authority.** The browser holds **`session_id`**; identity and act-as state live in **`lupo_sessions`** (via **`App\Auth\Session`**).

```php
// Correct
$session = App\Auth\Session::loadById($db, session_id());
$actor_id = $session ? $session->actor_id : 0;

// Forbidden for authority
$actor_id = isset($_SESSION['actor_id']) ? $_SESSION['actor_id'] : 0;
```

### Database rules (abridged)

| Rule | Requirement |
|------|-------------|
| **PK naming** | `<singular_table_name>_id` (not bare `id`) |
| **ID generation** | **`IdGenerator::generate()`** (not `AUTO_INCREMENT`) |
| **Timestamps** | Packed UTC **`BIGINT`** via **`timestamp_ymdhis`** / **`gmdate('YmdHis')`** |
| **Soft deletes** | **`is_deleted`**, **`deleted_ymdhis`** where tables define them |
| **No foreign keys** | Application-layer integrity |
| **No SQL date functions** for clock columns | As above |

### PHP rules

| Rule | Requirement |
|------|-------------|
| **Minimum version** | **PHP 7.4** |
| **Architecture** | **64-bit** required for production (installer) |
| **No Composer** in runtime core | In-tree libraries (e.g. PHPMailer) |
| **No frameworks** | No Laravel, Symfony, or middleware stacks |
| **No ORM** | **`PDO_DB`** + explicit SQL |

### UI and localization

| Rule | Requirement |
|------|-------------|
| **No build steps** for shipped visitor/admin core | Vanilla JS; no npm/webpack as a **runtime** requirement for those surfaces |
| **Strings** | **`lupo_t('key', 'Fallback English')`** |
| **Locale files** | **`lupo-includes/lang/lupo-{locale}.php`** |
| **No `eval()`** | No string-based dynamic execution patterns for new ship code |

### Security

| Rule | Requirement |
|------|-------------|
| **Untrusted input** | **`$UNTRUSTED`** (or equivalent) for **`$_GET` / `$_POST` / `$_SERVER`** snapshots |
| **Path anchoring** | **`LUPOPEDIA_PATH`** / **`LUPOPEDIA_PUBLIC_PATH`** — no user-driven includes |
| **SQL** | Named placeholders only (**`PDO_DB`**) |
| **Untrusted serialization** | Prefer JSON over **`unserialize()`** for untrusted payloads |
| **Prompt injection** | Agents cannot override constitutional rules by fiat |

---

## Directory structure (summary)

```
lupopedia/
├── app/                      # Application services (Session, Auth, etc.)
├── lupo-agents/              # Agent definitions (metadata)
├── lupo-includes/            # Core PHP, classes, themes
├── lupo-database/            # Install SQL, schema mirrors, docs
├── lupo-docs/                # PRDs, doctrines, implementations
├── lupo-tests/               # Unit, regression, integration tests
├── lupo-bin/                 # CLI utilities (tick.py, etc.)
├── lupo-uploads/             # User-uploaded files
├── lupo-cache/               # Runtime cache
├── lupo-logs/                # Application logs
├── admin.php                 # Admin entry
├── index.php                 # Public entry
└── install.php               # Installer
```

---

## Getting started

### Requirements

- **64-bit** **PHP 7.4+**
- **MySQL 8.0+** or **MariaDB 10.5+** or **PostgreSQL** (see PRD / installer notes for supported DBs)
- Web server with **subdirectory** install support (not web root)

### Installation

1. Upload to a subdirectory (e.g. `/public_html/lupopedia/`).
2. Open `https://yourdomain.example/lupopedia/install.php`.
3. Complete the wizard (database, admin user, paths).

### After install

```bash
python lupo-bin/tick.py
```

```bash
sh lupo-scripts/run_tests.sh .
```

Unit-only: **`sh lupo-scripts/run_unit_tests.sh .`**. Single file: **`php lupo-tests/unit/<name>.php`** (see **[AGENTS.md](AGENTS.md)**).

---

## Documentation

| Document | Purpose |
|----------|---------|
| [00_root_constitutional_system_requirements.md](lupo-docs/prd/00_root_constitutional_system_requirements.md) | **Supreme authority** |
| [SESSION_MODEL.md](lupo-docs/doctrine/SESSION_MODEL.md) | DB-backed session authority |
| [VERSIONING_DOCTRINE.md](lupo-docs/doctrine/VERSIONING_DOCTRINE.md) | Versioning and release policy |
| [AGAPE_DOCTRINE.md](lupo-docs/doctrine/AGAPE_DOCTRINE.md) | Resilience and fallbacks |

---

## Agent rules (for AI assistants)

1. Read **[PRD 00](lupo-docs/prd/00_root_constitutional_system_requirements.md)** first.
2. Do not use **`$_SESSION['actor_id']`** for authority — use **`App\Auth\Session`**.
3. Do not persist **`time()`** / epoch into clock columns — use **`timestamp_ymdhis::now()`** (or aligned APIs).
4. Do not rely on **`AUTO_INCREMENT`** for registry-style tables — use **`IdGenerator`** and explicit IDs where required.
5. Do not hardcode ship-facing UI strings — use **`lupo_t()`**.
6. Do not read superglobals raw in new code paths — use **`$UNTRUSTED`** (or project snapshot pattern).
7. Do not add Composer/npm **runtime** dependencies to core ship paths.

When in doubt, **the constitution wins**.

---

## Testing

```bash
sh lupo-scripts/run_tests.sh .
```

```bash
php lupo-tests/unit/admin_csrf.php
```

---

## License

GNU General Public License v3.0 — see **[LICENSE](LICENSE)**.

---

## Acknowledgments

- **Crafty Syntax Live Help** — lineage and proven live-help patterns
- **WordPress** — distribution and hosting patterns (studied; not copied) — see **[SOFTACULOUS_PACKAGE_BUILD.md](lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md)**
- **WOLFIE doctrine** — survival-first engineering

---

## Further reading (maintainers)

- **[AGENTS.md](AGENTS.md)** — facets, tests, full stack summary  
- **[ONBOARDING.md](ONBOARDING.md)** — operational quick-start  
- **[ORGANIZATION.md](ORGANIZATION.md)** — root `lupo-*` map  
- **[lupo-docs/versions/4.0.95/README.md](lupo-docs/versions/4.0.95/README.md)** — current version hub  
- **4.0.x** — **No** Lupopedia→Lupopedia upgrades until **4.1.0**; DDL/seed/import stay aligned — see **[PRD 33](lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md)**  
- **JSON under `lupo-database/lupopedia/json/`** — read-only schema mirrors; canonical DDL is **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`**

**Last updated (header UTC):** 20260406062838  
**Constitutional authority:** [PRD 00](lupo-docs/prd/00_root_constitutional_system_requirements.md)
