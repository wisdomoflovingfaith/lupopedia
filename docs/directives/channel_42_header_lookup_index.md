---
wolfie.headers:
  file_path_from_root: "docs/directives/channel_42_header_lookup_index.md"
  system_version: "4.0.34"
  channel_id: 42
  mood_rgb: "2244FF"
  purpose: "Directive to KIRO (1001) + Antigravity (1003) to design and implement a file-based header lookup/index for FLIP metadata (no-DB)"
  last_modified: "20260223"
  x_lupo_forwarded: "10000:1001"
  actor_id: 10000
  lupo_agent: "human|captain"

flip.footer:
  referenced_by_files:
    - "docs/doctrine/FLIP_FOOTER_DOCTRINE.md"
    - "docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md"
    - "docs/AGENT_INVENTORY.md"
    - "CHANGELOG.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 1003
    - 10000
  inbound_edges:
    - "header_lookup"
    - "metadata_index"
    - "x_lupo_forwarded_trace"
  footnotes:
    - "Goal: fast lookup of FLIP headers/footers across repo without DB dependency"
    - "Index must be regeneratable from repo state"
    - "UTC date format is canonical: YYYYMMDD"
  version: "4.0.34"
  last_verified: "20260223"
  last_verified_by: "10000"
---

# CHANNEL 42 DIRECTIVE — HEADER LOOKUP / INDEX (v4.0.34)

**Issued By:** Captain Wolfie (actor_id 10000)  
**Assigned To:**  
- KIRO IDE (actor_id 1001)  
- Antigravity IDE (actor_id 1003)  
**Canonical Date (UTC):** 20260223

You are to collaboratively design and implement a **file-based header lookup system** for Lupopedia.

This is the answer to:  
“Do we have a lookup on headers like docs/x_lupo_forwarded?”

We want a deterministic, scan-generated index that supports queries like:
- Find all files with `x_lupo_forwarded == 1003:10000`
- Find all artifacts referencing actor_id 1001
- Find latest activity for each agent by `last_modified`
- Find orphan/missing headers or footers

---

## 1) REQUIREMENTS

### ✅ Must
- No database access
- Index is derived strictly from:
  - wolfie.headers
  - flip.footer
- Supports MD and TOON artifacts (where applicable)
- Regeneratable from repo state (no manual edits required)
- Canonical date format: `YYYYMMDD`
- Stores **evidence paths** (file_path_from_root) for every record

### ❌ Must Not
- Introduce new single points of failure
- Require network services to function
- Depend on system clock time beyond YYYYMMDD comparisons

---

## 2) PROPOSED OUTPUT ARTIFACTS

Generate at least one of the following (you choose the best approach):

### Option A (Simple): Single Index File
- `docs/index/flip_index.json`
or
- `docs/index/flip_index.yaml`

### Option B (Queryable): Split Indices
- `docs/index/by_actor/1001.json`
- `docs/index/by_forward/1003_10000.json`
- `docs/index/by_channel/42.json`
- `docs/index/orphans.json`

---

## 3) INDEX SCHEMA (MINIMUM FIELDS)

Each index entry must include:

- file_path_from_root
- actor_id (if present)
- lupo_agent
- x_lupo_forwarded
- channel_id
- last_modified (YYYYMMDD)
- referenced_by_actors
- inbound_edges
- header_present (true/false)
- footer_present (true/false)

---

## 4) BUILD PROCESS

Implement a tool/script (language of your choice) that:

1. Recursively scans:
   - docs/
   - prompts/
   - channels/
2. Extracts YAML blocks:
   - wolfie.headers
   - flip.footer
3. Validates minimal schema
4. Writes the index files under:
   - `docs/index/`
5. Produces a report:
   - `docs/status/header_lookup_build_report_20260223.md`

---

## 5) COLLABORATION RULE

KIRO and Antigravity must:
- Propose the schema together
- Agree on one index strategy (A or B, or hybrid)
- Split implementation tasks:
  - One builds parser/scanner
  - One builds schema + output + validation rules
- Reconcile differences in a single merged PR/commit

---

## 6) ACCEPTANCE TESTS (MANDATORY)

Your output must be able to answer:

1. “Show all files with x_lupo_forwarded = 10000:1001”
2. “Show all artifacts that mention actor_id 1003”
3. “Show all artifacts missing flip.footer”
4. “Show latest last_modified per actor_id”
5. “Show all inbound_edges containing ‘header_lookup’”

Include these as example queries in the build report.

---

## END OF DIRECTIVE
