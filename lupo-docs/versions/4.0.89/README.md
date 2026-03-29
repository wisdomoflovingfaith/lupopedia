---
lupopedia.headers:
  lupopedia.schema: plan
  file_path_from_root: "lupo-docs/versions/4.0.89/README.md"
  content_id: 8425034527711385457

  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/README.md"
  federation_node_id: 0
  last_modified_utc: "20260329235907"
  when_updated: "20260329235907"
  channel_id: 42
  thread_id: "4-0-89-headers-release"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: plan
  artifact_kind: plan
  purpose: Version 4.0.89 — LUPOPEDIA HEADERS pipeline; PK/registry model change; database doctrine alignment; Python + PHP import/regenerate parity; PHP agent filesystem policy (AgentFileWriter, H9) vs full IDE agent repo access; release-gate testing
  tags:
    - "4.0.89"
    - headers
    - validation
    - import
    - release
lupopedia.history:
  - event_id: 1
    event_type: update
    event_date: "20260329003000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "Release README — criterion 10 documents dual running log (lupopedia.history ↔ revision_history) and TODO H7"
    reason: "4.0.89 exit — database + markdown audit trail finalized in doctrine and version tasks"
  - event_id: 2
    event_type: update
    event_date: "20260329120000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "Documented IDE agents (Python) vs PHP agents (API) toolchains; PHP import default DB-only with optional --write-back"
    reason: "Align README and TODO H8 with dual-agent import architecture"
  - event_id: 3
    event_type: update
    event_date: "20260329140000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "README import section deduped — agent matrix canonical in TODO H8; quick reference + links only here"
    reason: "Avoid duplicating H8 tables between README and TODO"
  - event_id: 4
    event_type: update
    event_date: "20260329150000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "README overview — 4.0.89 focus: headers + schema/PK/registry shift + code↔DB alignment"
    reason: "Single inline summary for release scope beyond headers-only tag line"
  - event_id: 5
    event_type: update
    event_date: "20260329160000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "Implemented timestamp+random content_id (Python+PHP), updated import logic, and doctrine. Session handoff to next agent."
    reason: "End-of-session update for 4.0.89: PK generation, import parity, and doc alignment."
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "See also links to TODO/CHANGELOG; Dual running log subsection for H7 cross-reference"
    reason: "Condensed overviews and cross-navigation for 4.0.89 docs"
  - event_id: 6
    event_type: update
    event_date: "20260329180000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "Release scope — H4.4 lupo-* literacy; criterion 12 PHP agent content-only filesystem (TODO H9)"
    reason: "Separate IDE code ownership from PHP agent markdown-only authoring on server"
  - event_id: 7
    event_type: update
    event_date: "20260329190000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "H9 tightened — no HTML/htm/js/php for PHP agents; AgentFileWriter + deployment doc"
    reason: "Align README #12 and release scope with filesystem safety policy"
  - event_id: 8
    event_type: update
    event_date: "20260329210000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "Version folder index — PHP_AGENT_FILESYSTEM_DEPLOYMENT.md + quick links §2.2; PLAN Phase F ties H9 verify"
    reason: "Keep 4.0.89 docs navigable without duplicating TODO H8/H9 bodies"
  - event_id: 9
    event_type: update
    event_date: "20260329233000"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "Session end — README timestamps; point to WHAT_TO_DO_NEXT_SESSION.md (headers handoff); verification details remain in CHANGELOG"
    reason: "Avoid duplicating other agents' edits; single handoff file for next IDE session"
  - event_id: 10
    event_type: update
    event_date: "20260329235907"
    actor_id: 102
    actor_name: "cursor"
    faucet_slug: "cursor"
    description: "Python import_content.py --write-back optional (DB-only default); RECONCILE_PK_UPDATE for legacy rows; README quick reference aligned with TODO H8"
    reason: "Parity with import_content.php; CHANGELOG/TODO errata — do not claim Python always mutates markdown"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.89/PLAN.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/versions/4.0.89/TODO.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/versions/4.0.89/CHANGELOG.md"
      type: synchronizes
      weight: 1.0
    - to: "lupo-docs/versions/4.0.89/HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md"
      type: references
      weight: 0.95
      reason: H9.3 web server hardening patterns for PHP agent content dirs
    - to: "lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/versions/4.0.90/README.md"
      type: references
      weight: 0.95
      reason: Non-header backlog deferred to 4.0.90
    - to: "lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md"
      type: references
      weight: 1.0
      reason: Post-session IDE handoff (headers / H8 — not the legacy_research Thoth doc)
lupopedia.footer:
  last_verified: "20260329235907"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
    department_id_delta: 0
  verified_via:
    type: direct
    faucet_slug: none
  orchestrator: "wolfie:root"
  next_action:
    - "Read lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md (version root) — IDE handoff for headers line; do not confuse with legacy_research/WHAT_TO_DO_NEXT_SESSION.md (Crafty/Thoth)"
    - "WOLFIE: tag 4.0.89 when accepted; reconcile lupo_contents if legacy content_id causes slug conflicts (see CHANGELOG Release verification)"
    - "TODO H2.1 / H2.3 — legacy validator + admin UI; H4.4 / H4.2 process sign-off"
---

# Lupopedia Version 4.0.89 — LUPOPEDIA HEADERS release

**Version:** 4.0.89  
**Status:** Release verification logged (**`CHANGELOG.md`** — H5, H7, H8 dry-run parity). **Open:** WOLFIE tag decision, legacy DB row reconciliation, **`TODO.md`** H2/H4 items. Next IDE: **`WHAT_TO_DO_NEXT_SESSION.md`** (this folder).  
**Thread:** `4-0-89-headers-release`

**Overview (focus of 4.0.89):** This version **ships the LUPOPEDIA HEADERS** end-to-end story — binding doctrine, validators (Python + PHP), **import / regenerate** (`lupo_contents`, `lupo_metadata`, `lupo_edges`, `revision_history` policy), dual toolchain for **IDE agents** vs **shared-hosting / PHP agents**, and release-gate tests (**`TODO.md`**, **`CHANGELOG.md`**). In parallel, 4.0.89 is the line where **primary-key and allocation practice no longer depends on the removed registry tables** (`lupo_registry`, `lupo_registry_open`): new IDs follow project **application-layer generation** (timestamp-structured and table-specific rules) and **reserved-ID** doctrine where registry-backed entities apply — see **`CHANGELOG.md`** §1 and root database rules. **Database rules** (canonical for this version) remain: schema from **`install_new_lupopedia.sql`** and **`lupo-database/lupopedia/json/*.json`** mirrors, **PDO_DB** only, no DB-side logic (no FKs, triggers, auto-timestamps), **BIGINT** UTC **`YYYYMMDDHHIISS`** set in application code, and safe migration discipline. **A major 4.0.89 obligation** is that **all active code paths are updated to match that database reality** — no references to dropped tables, no stale column assumptions, imports and services using the same columns the install defines; drift between code and canonical schema is a **release blocker** alongside header readiness.

**See also:**

- [TODO.md](TODO.md) — Task breakdown and gates
- [CHANGELOG.md](CHANGELOG.md) — Detailed version overview and achievements

### Dual running log

Optional **`lupopedia.history`** in markdown pairs with **`lupo_contents.revision_history`** in the database after import. When the YAML key is **present**, import serializes/replaces the DB column; when **absent**, the DB value is preserved and regenerate emits history from DB. Binding rules and edge cases: [`LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md) (*Dual running log — file and database*). Release verification: **`TODO.md` H7** and **Release scope** criterion **10** below.

---

## Release scope (binding)

**4.0.89** ships when the following are **true**, documented, and tested:

1. **Doctrine & docs** — Binding [`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md); companions under [`lupo-docs/doctrine/LUPOPEDIA_HEADERS/`](../../doctrine/LUPOPEDIA_HEADERS/) (FORMAT, TAXONOMY_REFERENCE, VALIDATORS, OPTIONAL_BLOCKS, VERIFICATION_GUIDE, alias file).
2. **Python validation** — `validate_lupopedia_headers.py` (required keys, `--check-db`), `validate_lupopedia_headers_universal.py` (cross-field, repo-root edges), `lib/header_validation.py`, `lib/header_db_sync.py`.
3. **PHP validation** — **`validate_lupopedia_headers.php`** + **`HeaderDbSync::validateFile`** (CLI parity with `validate_lupopedia_headers.py`); legacy **`LupopediaHeaderValidator.php`** refresh still tracked in **`TODO.md` H2.1**; `lupo.php` wiring as available.
4. **Web / operator surface** — Identify or implement the **admin/operator path** that runs header validation in the browser (today: primarily CLI; **gap** tracked in `TODO.md`).
5. **Import / regenerate** — **IDE agents** use Python: `import_content.py`, `generate_headers_from_db.py`, `ensure_imported.py`. **PHP agents** (shared hosting / LLM APIs) use PHP: `import_content.php`, `generate_headers_from_db.php`. Same deterministic `content_id` and sync semantics; **`TODO.md` H8** defines acceptance tests and the **IDE vs PHP agent** model.
6. **`lupo-*` layout** — Every release participant understands the **root `lupo-*` map** and doc authority: [`ORGANIZATION.md`](../../../ORGANIZATION.md) **§4** + [`lupo-docs/ORGANIZATION.md`](../../ORGANIZATION.md); verify **`TODO.md` H4.4**. Artifacts live in the correct trees per doctrine.
7. **IDE rule packs** — `.cursor/rules`, `.windsurf/rules`, and related prompts **reflect** `lupo-rules/root/` doctrines that affect headers (e.g. LUPOPEDIA HEADERS file order, FLIP→HEADERS rename, safe migration, PDO). Run or document `php lupo-scripts/propagate_agent_rules.php` when updating.
8. **Database & code alignment** — **`install_new_lupopedia.sql`** + **`lupo-database/lupopedia/json/*.json`** are authoritative; **`header_db_sync`** / Python + PHP import must match those columns. **Runtime PHP and tooling** must not assume removed objects (e.g. registry tables) or legacy columns; any feature touching `lupo_*` tables is verified against the current install. Schema changes follow project **migration / database doctrines** (no ad hoc production SQL).
9. **Release-gate testing** — At least **several** markdown imports; **read** headers from DB; **regenerate** YAML headers; compare; document results (see `TODO.md` checklist).
10. **Running log (`lupopedia.history`)** — Binding doctrine documents the **dual** audit trail: optional YAML block in `.md` files and JSON in **`lupo_contents.revision_history`** after import. **Before tag:** complete **`TODO.md` H7** (append event → import → verify DB → regenerate → validate; record outcome in `CHANGELOG.md` **Release verification** or channel 42).
11. **Dual import toolchains** — Python and PHP imports both land in the same tables with the same rules; cross-toolchain checks in **`TODO.md` H8.5–H8.6** satisfied before tag (or explicitly waived with recorded risk).
12. **PHP agent filesystem safety** — **PHP / LLM agents** **only** write under **`lupo-rules/`**, **`lupo-docs/`**, **`lupo-channels/`**, **`lupo-content/`**, with extensions **`.md`**, **`.txt`**, **`.yaml`**, **`.yml`**, **`.json`**, **`.csv`**, **`.xml`** — **no** **`.html` / `.htm` / `.js` / `.php`** or other scripts; **agent** context uses content scanning via **`AgentFileWriter`**. **IDE agents** (Cursor, Windsurf, Kiro) have **full repository write access** for development and are **not** gated by **`AgentFileWriter`**. Policy: **`TODO.md` H9**; distinction: **`lupo-docs/ORGANIZATION.md` §2.2**; deployment: **[`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`](PHP_AGENT_FILESYSTEM_DEPLOYMENT.md)**.

---

## Deferred to 4.0.90

**Not** in 4.0.89 release criteria:

- Context model **database** work (`lupo_contexts`, automation, Channel 66, TASK_REGISTRY).
- **Crafty Syntax** feature **implementation** (backlog execution).
- Documentation clarity tasks **5.1–5.4** (nav, FLARE cleanup, IMPLEMENTATION bridge) as **release** blockers.

Those items live in **[`lupo-docs/versions/4.0.90/`](../4.0.90/README.md)** with carryover note **[`SCOPE_CARRYOVER_FROM_4_0_89.md`](../4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md)**.

**Historical changelog** (registry removal, WSL, manifesto, etc.) remains in **`CHANGELOG.md`** — it describes the **repo** state on this line; the **tagging decision** for 4.0.89 is **headers pipeline readiness** above.

---

## Files in this folder (headers release)

| File | Role |
|------|------|
| `README.md` | This scope + criteria |
| `PLAN.md` | Dependency-ordered **header release** plan |
| `TODO.md` | Header release tasks + test matrix |
| `CHANGELOG.md` | Repo changes (includes pre-refocus history) |
| `HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md` | DB-first header pipeline narrative |
| `PHP_AGENT_FILESYSTEM_DEPLOYMENT.md` | **H9.3** — optional Apache/nginx hardening for content directories |
| `crafty_syntax_backlog.md`, `legacy_research/`, etc. | **Reference** for 4.0.90 — not 4.0.89 exit criteria |

---

## Quick links

- Binding doctrine: [`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)  
- Doctrine index: [`lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`](../../doctrine/LUPOPEDIA_HEADERS/README.md)  
- Validators: [`VALIDATORS_AND_TOOLING.md`](../../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md)  
- Doc tree / agent toolchains: [`lupo-docs/ORGANIZATION.md`](../../ORGANIZATION.md) (**§2.1** toolchains, **§2.2** IDE vs PHP filesystem boundaries)  
- 4.0.90 backlog: [`../4.0.90/README.md`](../4.0.90/README.md)

---

## PHP import quick reference (shared hosting + PHP agents)

**Canonical detail** (agent-type table, diagrams, acceptance tests **H8.5–H8.6**, dependencies): **[`TODO.md`](TODO.md) H8**; **PHP agent write boundaries** (content-only, no runtime trees): **H9** — not duplicated here.

**Requires:** `lupopedia-config.php`, **php-yaml** (`yaml_parse`), **bcmath** or **gmp**. Library: [`HeaderDbSync.php`](../../../lupo-includes/classes/HeaderDbSync.php).

```bash
# DB import only (default — neither toolchain modifies the .md file)
python lupo-scripts/import_content.py path/to/file.md
php lupo-scripts/import_content.php path/to/file.md

# Persist content_id in YAML (one-time migration / explicit sync — either toolchain)
python lupo-scripts/import_content.py --write-back path/to/file.md
php lupo-scripts/import_content.php path/to/file.md --write-back

php lupo-scripts/validate_lupopedia_headers.php path/to/file.md
php lupo-scripts/validate_lupopedia_headers.php path/to/file.md --check-db
php lupo-scripts/generate_headers_from_db.php --file-path path/to/file.md
```

---

**WOLFIE (actor_id 1)** — 4.0.89 scoped to LUPOPEDIA HEADERS release; broader roadmap → 4.0.90.
