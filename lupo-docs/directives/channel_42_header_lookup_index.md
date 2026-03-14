# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\directives\channel_42_header_lookup_index.md"
  file_hash: "cc6ee1564b805013461a7eb4518742e9737fba5cf7fca7609f7abfc893c76414"
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
  file_path_from_root: "lupo-docs\directives\channel_42_header_lookup_index.md"
  file_hash: "d4a715e837bbdaf83b185fce2ece70ce5ce0ee248d6b3a2926e31a8182dc5017"
  file_path_from_root: "lupo-docs\directives\channel_42_header_lookup_index.md"
  file_hash: "782ef9dccc5b98e6e86ca10d9fd7f6251b6b4713d45b0090e6a7ddbb933f9a4c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for channel_42_header_lookup_index.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "directives", "channel_42_header_lookup_indexmd"]
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
wolfie.headers:
  file_path_from_root: "lupo-docs/directives/channel_42_header_lookup_index.md"
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
    - "lupo-docs/doctrine/FLIP_FOOTER_DOCTRINE.md"
    - "lupo-docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md"
    - "lupo-docs/AGENT_INVENTORY.md"
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
“Do we have a lookup on headers like lupo-docs/x_lupo_forwarded?”

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
- `lupo-docs/index/flip_index.json`
or
- `lupo-docs/index/flip_index.yaml`

### Option B (Queryable): Split Indices
- `lupo-docs/index/by_actor/1001.json`
- `lupo-docs/index/by_forward/1003_10000.json`
- `lupo-docs/index/by_channel/42.json`
- `lupo-docs/index/orphans.json`

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
   - lupo-docs/
   - lupo-prompts/
   - lupo-channels/
2. Extracts YAML blocks:
   - wolfie.headers
   - flip.footer
3. Validates minimal schema
4. Writes the index files under:
   - `lupo-docs/index/`
5. Produces a report:
   - `lupo-docs/status/header_lookup_build_report_20260223.md`

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
