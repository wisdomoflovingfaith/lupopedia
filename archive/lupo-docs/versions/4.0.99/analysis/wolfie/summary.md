---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260411170424"
  file_path_from_root: "lupo-docs/versions/4.0.99/analysis/wolfie/SUMMARY.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/analysis/wolfie/SUMMARY.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "lupo-memory/development/canonical/1026/04/analysis-wolfie-summary.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: "wolfie-crafty-analysis"
  content_id: null
  pk_id: null
  pk_slug: "crafty-sql-summary"
  title: "AI WOLFIE — Crafty SQL analysis summary"
  status: "active"
  parent_pk_id: ""
  summary: "Key findings, recommendations, registry pattern map; points to crafty_data_analysis.md and BREAKTHROUGH_REGISTRY."
  module: null
  dialog_transcript: "0/development/wolfie-crafty-analysis"
---
# AI WOLFIE — Crafty Syntax SQL analysis (summary)

**Full detail:** [`crafty_data_analysis.md`](crafty_data_analysis.md)  
**Registry:** [`../../BREAKTHROUGH_REGISTRY.md`](../../BREAKTHROUGH_REGISTRY.md) (new **§2.14**, **§3.4**, **§9.1** lessons **#5–#6**, **§13.1** Q9–Q13, doc **#10**)

## Key findings

1. **Repository sample dump is small** — e.g. **`livehelp_visit_track`** has only **4** rows here; **`livehelp_paths_*`** has on the order of **1k+** rows each. Treat this file as a **structural** reference, not a volume benchmark for “25 years of paths.”
2. **`import_from_old_crafty_syntax.sql` is explicit and destructive in places** — `TRUNCATE` on `lupo_visits`, `lupo_paths`, dialog tables, etc., then `INSERT ... SELECT`. No dry-run mode inside SQL.
3. **Operator identity mapping is deterministic** — **`auth_user_id = 10000 + livehelp_users.user_id`**; Crafty operators become **`lupo_actors`** with **`actor_id = auth_user_id`** (see import comments).
4. **Timestamp normalization is already coded** for `livehelp_visit_track.whendone` (14 / 13 / 8 / fallback `LPAD` cases) and for rollup `dateof` fields (`CONCAT` with noon or month anchors).
5. **Several legacy tables exist as DDL only** in this dump (no rows): e.g. **`livehelp_channels`**, **`livehelp_operator_channels`**, **`livehelp_messages`**, keyword rollups — import behavior must be validated when a **full** customer DB is attached.

## Data quality observations

| Observation | Impact | Recommendation |
|-------------|--------|----------------|
| Sample vs production volume mismatch | High for performance planning | Run row-count queries on **real** 3.7.5 export before sizing batch imports. |
| Transcripts default to **actor 1** / **channel 1** | Med | Post-import reconciliation or channel policy before treating threads as production-accurate. |
| **`CRC32(sessionid)`** for visits | Low–Med | Document collision semantics; do not assume global uniqueness. |

## Breakthrough Registry — which patterns touch Crafty import?

| Pattern | Applies? | Notes |
|---------|----------|-------|
| **#1** Install-time / graph seeding | **Yes** | Crafty → Lupo import is the **operational** analog of “seed graph + data” for upgrades. |
| **#2** Peel-first headers | **No** | SQL import; header peel is Markdown path. |
| **#3** Ghost sync | **Partial** | After import, **PRD 38** graph vs files still applies to **content** artifacts. |
| **#4** Header field order | **No** | SQL path. |
| **#5** Graph-first | **Partial** | Import writes **relational** SoT first; **memory graph** backfill is separate (Pattern **#1** / **#7** narrative). |
| **#6** Orphan detection | **Yes** | Run **`detect_memory_graph_orphans.py`** after any post-import header work. |
| **#7** Reconciliation | **Yes** | Imported rows vs mirrors / inference (**PRD 51**) may need a pass. |
| **#8** Self-documenting | **Yes** | This analysis package is the registry-aligned doc trail. |
| **#9** Edge verification | **Yes** | After memory nodes exist for imported content, **KAIROS** checks apply. |
| **#10** Registry self-seed | **N/A** | Markdown registry only; import is DB. |

**Proposals (not scored — see registry §2.14 / §3.4):** import-time path aggregation discipline, transcript threading policy, explicit **import timestamp normalization** pattern proposal.

## Recommendations

| Priority | Recommendation | Owner |
|----------|----------------|-------|
| High | Validate import on **clone DB**; capture **before/after row counts** per table. | WOLFIE / ANUBIS |
| High | After import + any header migration, run **Pattern #6** and **Pattern #9** where DB is up. | ANUBIS / KAIROS |
| Medium | Add **application-level** or **scripted dry-run** (count-only) wrapper — SQL file alone cannot dry-run safely. | Cursor **102** (when tasked) |
| Medium | Decide policy for **historical path volume** (full import vs windowed) — open in registry **§13.1**. | WOLFIE |

## Next actions

- [ ] Run importer against **test** MySQL with **full** Crafty export (not only this repo sample).
- [ ] Spot-check **`whendone` / `dateof`** normalization on edge cases (NULL, zero, pre-2000).
- [ ] After **`lupo_memory_nodes`** exist for migrated docs, run **`kairos_edge_verification.py --test`** on sampled paths.
- [ ] Review **§2.14 / §3.4** in **BREAKTHROUGH_REGISTRY.md** and accept or reject proposed patterns with **WOLFIE**.

## References

- [`old_crafty_syntax_3_7_5_start.sql`](../../../../../lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql)
- [`import_from_old_crafty_syntax.sql`](../../../../../lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql)
- [`../../BREAKTHROUGH_REGISTRY.md`](../../BREAKTHROUGH_REGISTRY.md)

This output complies with Lupopedia Constitutional Root Rules.
