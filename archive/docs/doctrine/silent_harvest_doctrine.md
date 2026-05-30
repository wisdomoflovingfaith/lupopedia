---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/SILENT_HARVEST_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/SILENT_HARVEST_DOCTRINE.md"
  status: "active"
  when_updated: "20260403125504"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: analytics_foundation
  channel_key: null
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: SILENT_HARVEST_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/SILENT_HARVEST_DOCTRINE.md

# Silent harvest doctrine (4.0.x)

## Principle

**Long-horizon, aggregated path and visit data** from the Crafty Syntax lineage — carried forward in Lupopedia’s **`lupo_visits`**, **`lupo_paths`**, **`lupo_visits_daily`**, **`lupo_referers_daily`**, and related tables — is a **first-party analytics foundation** for each **site operator’s** install. Properly used, it supports **inferring real navigation behavior** (entry/exit, flows, referrers) for **navigation compilation**, **semantic structure**, and **federation discovery** — without turning the product into covert surveillance.

This doctrine names the **strategic value** of that foundation and the **non-negotiable obligations**: consent, minimization, opt-out, jurisdiction-appropriate disclosure, and honest product communication.

## Historical context and scale (operating assumptions vs evidence)

Crafty Syntax was widely deployed for many years as live-help software. In-repo lineage text (e.g. **[WOLFIE_DOCTRINE.md](../../rules/root/WOLFIE_DOCTRINE.md)** — *on the order of **1.2 million installations** over multi-decade operation*) describes **historical reach**; it is **not** a substitute for an independently audited census in this repository.

**LILITH audit (2026-04) operating assumptions** (for planning **only** — confirm against your own telemetry and legal review before publishing):

| Bucket | Stated order of magnitude | Notes |
|--------|---------------------------|--------|
| **Lifetime installs (total)** | **1,000,000+** | Cumulative installs over the product lifetime; many sites later removed or idle. |
| **Active / reporting (callback-era)** | **~144,000** | Installations that **periodically check in** (updates, license/telemetry — exact mechanism is **not** specified in this doctrine). |
| **Dormant / unknown** | Remainder | No central access to their **local** databases from this repo. |

**Critical:** **Callback or registration metadata** (that a node existed, version, last seen) is **not** the same as **full behavioral path tables**. **Path, visit, and rollup data** for a site live **in that site’s database** until **imported** into Lupopedia through the **operator-controlled** upgrade path. See **[CRAFTY_NODE_REACTIVATION_STRATEGY.md](CRAFTY_NODE_REACTIVATION_STRATEGY.md)**.

### Callback / central telemetry vs local behavioral data

| Data class | Typical locus | Federated by default? |
|------------|---------------|------------------------|
| Install existence, version, last check-in | Vendor-side logs (if any) | **No** — not Lupopedia’s unless explicitly designed and disclosed |
| **`lupo_visits`**, **`lupo_paths`**, daily rollups | **Local** DB per install | **No** — stays local until operator **imports** and later **opts in** to PRD 34 semantics |

## What exists in schema (canonical)

Authoritative DDL: **`database/lupopedia/mysql/install/install_new_lupopedia.sql`**.

| Area | Tables (prefix `lupo_`) | Role |
|------|---------------------------|------|
| Raw events | **`visits`** | Per-event navigation logs (`path_url`, enter/exit content, `session_id`, `created_ymdhis`, `is_processed` for aggregation). |
| Aggregated flows | **`paths`** | Aggregated navigation flows with **`year_num` / `month_num` / `day_num`**, counts, enter/exit — populated per install policy (e.g. gc aggregation from visits). |
| Path rollups | **`paths_summary`** | Summary counts per `path_id`. |
| Daily rollups | **`visits_daily`**, **`referers_daily`** | Daily totals and referer domains. |

There is **no** `lupo_visits_monthly` table name in the canonical install; monthly-style reporting is expressed via **`paths`** date components and/or application rollups — **verify column names in TOON/SQL before writing queries.**

## Capabilities (when data exists)

For a **given install** that has collected data lawfully:

1. **Infer popular entry and exit patterns** from **`paths`** and **`visits`**.
2. **Build navigation hypotheses** from **observed** flows rather than only static menus.
3. **Support federation discovery** (e.g. **`lupo_federation_discovery`**) with **aggregates** already sketched in PRD 34.
4. **Feed a navigation compiler** (planned **PRD 34** deliverable): deterministic transforms from aggregates to proposed nav / collection structures — **application code**, not DB triggers.

## Privacy, consent, and disclosure

- **Operator responsibility:** Each site’s **privacy policy**, **consent**, and **opt-out** for analytics are the **operator’s** obligation. Lupopedia must **document** configurable tracking and support **disabling** collection where the product allows it.
- **No “silent” breach of law:** “Silent harvest” here means **long-running, low-attention accumulation of first-party analytics** — **not** hiding processing from the **site owner** or end users where law requires transparency.
- **Rollout communications:** Product marketing and public claims about historical breadth of data must be **accurate** and **law-reviewed**. **Do not** promise cross-site datasets the product does not centrally hold.

## Strategic note (internal)

Aggregated **path intelligence** over years — **per operator database** — can be a **strong differentiator** for on-site help and semantic navigation **when** paired with honest positioning and strong privacy tooling. That is **competitive advantage**, not an excuse to bypass ethics.

## Relation to PRD 34

**[PRD 34 — Federation node semantic network](../prd/34_federation_node_semantic_network.md)** carries the **navigation compiler** and federation-facing uses of this foundation. This doctrine does **not** implement features; it **frames** them.

This output complies with Lupopedia Constitutional Root Rules.
