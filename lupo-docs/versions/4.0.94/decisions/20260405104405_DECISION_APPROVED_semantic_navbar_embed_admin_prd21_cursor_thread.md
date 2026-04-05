---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260405104405"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260405104405_DECISION_APPROVED_semantic_navbar_embed_admin_prd21_cursor_thread.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260405104405_DECISION_APPROVED_semantic_navbar_embed_admin_prd21_cursor_thread.md"
  last_modified_utc: "20260405104405"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-decisions"
  actor_id: 102
  actor_name: cursor
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: decision
  purpose: "APPROVED receipt — semantic navbar cross-origin embed gate, Admin web provisioning, PRD 21 (this Cursor thread only)"
lupopedia.footer:
  last_verified: "20260405104405"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
---

# file: DECISION semantic_navbar_embed_admin_prd21 — delegation: cursor:root

# DECISION (APPROVED): Semantic navbar external embed — federation + trust + PRD 21 + Admin forms (code + documentation receipt)

## 5W1H

| Element | Record |
|---------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), orchestration `cursor:root`. LILITH audit cited in thread for PRD 21 edges (reviewer; non-interfering). |
| **WHAT** | **(1)** **`lupo-includes/classes/SemanticNavbarEmbedContext.php`** — cross-origin embed gate: **`lupo_federation_nodes.node_base_url`** (normalized origin), **`lupo_federated_trust`** hub→target with **`trust_type = semantic_widget`**, **`lupo_federation_discovery`** upsert on **`unknown_node`** / **`no_trust`**; CORS helpers; explicit PK patterns for discovery (no auto-create of federation nodes for attackers). **(2)** **`lupo-includes/modules/api/semantic-navbar-api.php`** — **403** JSON **`embed_not_trusted`**; operator-facing **`message`** points to **Admin → Semantic widget** (not raw SQL instructions). **(3)** **`lupo-includes/classes/AdminSemanticWidgetHandler.php`** — authenticated **Admin** forms: register embedder origin → **`lupo_federation_nodes`** (`node_type` **`remote`**, explicit **`federation_node_id`**); grant / refresh **`lupo_federated_trust`** (explicit **`trust_id`**); summary table of nodes vs trust; **CSRF** on POST; form **`action`** includes **`?section=semantic-widget`** so **`admin.php`** routes on POST; **page order**: intro + slug picker, **relative** script snippet first, **absolute** snippet second, then **External sites (federation + trust)** block. **(4)** **`lupo-includes/lang/lupo-en.php`** — operator strings: no “add a row in SQL”; **`admin.semantic.intro`** same-host-first narrative; flash + form label keys. **(5)** **`admin.php`** — semantic-widget section description mentions register + trust + snippet. **(6)** **`lupo-docs/prd/21_semantic_navbar.md`** — sections **5.x**: allowlist, tracking, discovery, client params, **why slug ≠ embedder path** (namespace, collision, security, intent); **operators must use web admin** for steps 1–2; content step 3 via **artifacts / headers / content workflow**; outbound edges to PRD **11**, **34**, **`SILENT_HARVEST_DOCTRINE.md`**, **`SEMANTIC_MONITORING_DOCTRINE.md`**, **`SemanticNavbarEmbedContext.php`**, **`AdminSemanticWidgetHandler.php`**. **(7)** LILITH follow-up in thread: added PRD **34** and **`SILENT_HARVEST_DOCTRINE.md`** edges; clarified PRD **11** edge reason (visitor embed fingerprint). |
| **WHERE** | Paths above; version receipt **`lupo-docs/versions/4.0.94/`** — **`CHANGELOG`**, **`PLAN`** Phase **M**, **`TODO`**, **`edges`**, **`WHAT_TO_WORK_ON_NEXT_SESSION`**, **`decisions/`** + **`comments/`** indexes, this file, paired **COMMENT** `20260405104405_…`. |
| **WHEN** | Evidence batch UTC **`20260405104405`** (`python lupo-bin/tick.py` this documentation batch). |
| **WHY** | Third-party embeds must not rely on operators running DDL/SQL; trust and origin must be explicit; path-on-foreign-site must not be mistaken for **`lupo_contents`** key; discovery queue for review without auto-node creation. |
| **HOW** | **`PDO_DB`** insert/update with explicit IDs; **`normalizeEmbedOrigin()`**; **`TRUST_TYPE_SEMANTIC_WIDGET`** constant; PRD and Admin copy aligned. |

## APPROVED scope (thread-verified)

- Runtime gate + discovery behavior documented in PRD 21 and implemented in **`SemanticNavbarEmbedContext`** (as committed).
- Admin **Semantic widget** page provisions **federation node** + **trust** without SQL runbooks; snippets ordered **relative** then **absolute** then remote section.
- PRD 21 documents slug contract, admin obligation, and cross-links agreed in thread.

## WHAT NOT claimed (this thread)

- **No** PRD **16 / 26 / 30 / 31** rewrites, **COUNTERMEASURE** validator threads, or universal header validator edits as part of **this** receipt — those are separate evidenced commits if present elsewhere.
- **No** dedicated Admin wizard that creates **`lupo_contents`** rows per **`federation_node_id`** in this batch — step 3 remains **content / artifact / header** workflow until a future APPROVED decision.
- **No** guarantee every other file in the same **`git push`** was authored in this chat — see **`CHANGELOG`** co-commit note and **`git show`** for full diff.

## Outcome

**APPROVED** as accurate **version-folder receipt** for the **semantic navbar embed + Admin provisioning + PRD 21** thread. Human handoff: next session — work through **Crafty Syntax 3.7.5** feature parity list (**easy → hard**); see **`WHAT_TO_WORK_ON_NEXT_SESSION.md`**.

This output complies with Lupopedia Constitutional Root Rules.
