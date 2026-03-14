# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_EMOTIONAL_AGGREGATION.md"
  file_hash: "2692cc87e038b9f5d55fcdaf4706beb13703908df7da339f378921adfd8932a0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\FLIP\FLP_EMOTIONAL_AGGREGATION.md"
  file_hash: "0f1f28fe81e04b17bf06776731ce368bc3f8343d750dcec267636dc9563b0ec6"
  file_path_from_root: "lupo-docs\doctrine\FLIP\FLP_EMOTIONAL_AGGREGATION.md"
  file_hash: "28f8e45391cefaaf27988f3be92beb1e0f3e132fbe111583376ecb94249fd98d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_EMOTIONAL_AGGREGATION.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_emotional_aggregationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/FLIP/FLP_EMOTIONAL_AGGREGATION.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/FLIP/FLP_EMOTIONAL_AGGREGATION.md
---
# FLP — Emotional Aggregation

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Related:** [FLP_EMOTIONAL_GEOMETRY.md](FLP_EMOTIONAL_GEOMETRY.md), [MOOD_RGB_DOCTRINE.md](../../channels/doctrine/MOOD_RGB_DOCTRINE.md).

---

## 1. Aggregation performed entirely in application code

All emotional aggregation in the FLP (blending across councils, rollups over time, derived mood tensors, or any composite emotional state) is performed **entirely in application code**. The database does not compute aggregates, averages, or derived emotional values.

---

## 2. Aggregates stored as plain data

When the application computes an aggregate (e.g. a blended mood for a set of councils, or a council’s summarized emotional state), the result is stored as **plain data** written explicitly by the application. Examples:

- Writing a mood_rgb or emotional-state value into channel metadata.
- Writing a row or record into an existing table with the aggregate value in a column.
- Writing to content or logs with timestamp and value.

The database stores only what the application writes. It does not maintain running totals, running averages, or any automatic derivation.

---

## 3. No DB-side computation

- **No triggers** to update aggregates when source data changes.
- **No stored procedures or functions** that compute emotional aggregates.
- **No views** that compute emotional aggregates.
- **No generated or computed columns** that derive emotional state from other columns.

Any formula or algorithm for aggregation is implemented in application code and may be documented in doctrine or design docs; it is not implemented in the database.

---

*End of FLP emotional aggregation. No schema, no SQL, no implementation in this document.*
