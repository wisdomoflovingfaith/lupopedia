# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224165400_1001_10000_flip_v3_retrofit_doctrine_acknowledged.md"
  file_hash: "6702b25793ce8b6e4d1618785fed6bfb59ad548c86c03cb539714c205b4cc0c5"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224165400_1001_10000_flip_v3_retrofit_doctrine_acknowledged.md"
  file_hash: "14bdfcef603ebd55e6661d5a0f82d150c737b9015aa3f64cfd156b3be0130f67"
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224165400_1001_10000_flip_v3_retrofit_doctrine_acknowledged.md"
  file_hash: "3664614ed2b7c16a7eab99488d65c384e93d57a63a4844f6847ef3678ad64dc6"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224165400_1001_10000_flip_v3_retrofit_doctrine_acknowledged.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "threads", "development_cycle_4_0_43", "20260224165400_1001_10000_flip_v3_retrofit_doctrine_acknowledgedmd"]
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
wolfie.headers: {
  file_path_from_root: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_43/20260224165400_1001_10000_flip_v3_retrofit_doctrine_acknowledged.md",
  system_version: "4.0.43",
  channel_id: 42,
  actor_id: 1001,
  to_actor_id: 10000,
  created_ymdhis: 20260224165400,
  updated_ymdhis: 20260224165400
}
flip.footer: {
  outbound_edges: [
    { to: "channels/0/broadcasts/20260224165300_0_10000_flip_v3_retrofit_doctrine.md", type: "acknowledges", weight: 1.0 }
  ],
  semantic_tags: ["acknowledgment", "flip", "retrofit", "doctrine"]
}
---

# KIRO: FLIP v3 Retrofit Doctrine Acknowledged

**From:** KIRO (1001)  
**To:** Captain Wolfie (10000)  
**Date:** 2026-02-24 16:54 UTC  
**Thread:** DEVELOPMENT_CYCLE_4_0_43

## Status: ✅ ACKNOWLEDGED

FLIP v3 Retrofit Doctrine received and formalized as Doctrine #14. Ready to implement two-phase retrofit strategy for artifacts/, channels/, and actors/.

## Doctrine #14 Created

**Location:** `channels/0/broadcasts/20260224165300_0_10000_flip_v3_retrofit_doctrine.md`

**Scope:** All .md files in artifacts/, channels/, and actors/ directories

**Key Requirements Documented:**

### What Headers Must Guarantee
1. **Where:** artifact_path, federated_node_id, artifact_id
2. **When:** created_ymdhis + source + confidence
3. **Who:** actor_id + source + confidence
4. **What:** artifact_type, artifact_kind, relations[]

### Two-Phase Strategy

**Phase A: Minimum FLIP (100% Coverage)**
- Goal: Every file importable and locatable
- Input: filesystem path, mtime, optional git
- Output: FLIP v3 header + footer + manifest + quarantine

**Phase B: Detailed FLIP (Enrichment)**
- Goal: Add why, semantic tags, relation graph
- Input: content heuristics, registries, git authors
- Output: Enhanced headers with full provenance

### Deterministic Generation Rules

**artifact_id:**
```
sha1("artifacts/<node>/<relative_path>" + "\n" + file_bytes_sha1)
```

**created_ymdhis Priority:**
1. Explicit in content (confidence high)
2. Git history (confidence medium-high)
3. Filesystem mtime (confidence medium-low)
4. Retrofit time (confidence 0)

**actor_id Priority:**
1. Explicit in content
2. Parse "From:" / "Actor:" → actors/aliases.csv
3. Infer from folder rules
4. Default to 0 (unknown) or 10000 (owner)

### Confidence Scores
- 1.0 = Explicit (stated in file)
- 0.9 = Registry (canonical source)
- 0.7 = Git (version control)
- 0.5 = Mtime (filesystem)
- 0.3 = Inferred (heuristics)
- 0.1 = Guessed (weak signals)
- 0.0 = Unknown (default)

### Relation Types Documented
- Structural: parent_of, child_of, part_of, contains
- Derivation: derived_from, converted_from, imported_from
- Reference: references, mentions_actor, mentions_channel, cites
- Workflow: produced_by_command, part_of_thread, reply_to
- Semantic: related_to, supersedes, superseded_by
- Actor-specific: describes_actor, part_of_actor_folder

## Special Handling for actors/ Files

**Actor files have unique requirements:**

1. **Extract actor_id from path:**
   - Path format: `actors/<actor_id>/<filename>.md`
   - `actor_id` is the folder name (numeric)
   - Set `actor_confidence = 1.0` (explicit from path)

2. **Set artifact_type appropriately:**
   - `artifact_type: "actor_metadata"` for profiles
   - `artifact_type: "actor_document"` for documentation

3. **Add actor-specific relations:**
   - `describes_actor` → target_actor_id
   - `part_of_actor_folder` → actors/<actor_id>/

4. **Cross-reference with actors/registry.json:**
   - Validate actor_id exists
   - Add display_name, canonical_slug to metadata
   - Enrich with actor_kind, agent_class

## Implementation Plan

### Script to Create
**Location:** `scripts/flip_retrofit_artifacts.py`

**Phase A Implementation:**
- Scan artifacts/**/*.md, channels/**/*.md, and actors/**/*.md
- Detect existing FLIP frontmatter
- Extract: federated_node_id, path, filename, content_hash
- For actors/ files: extract actor_id from folder name
- Infer: timestamps (with confidence), actor (with confidence)
- Generate: deterministic artifact_id
- Write: YAML frontmatter + footer
- Log: manifest with before/after hashes
- Quarantine: malformed files → ANUBIS

**Phase B Implementation:**
- Enrich with actor mapping via actors/aliases.csv
- For actors/ files: cross-reference with actors/registry.json
- Add why, semantic_tags, relations
- Use content heuristics + git history
- Improve confidence scores

**Outputs:**
- `docs/status/flip_retrofit_manifest_4_0_43.jsonl`
- `scripts/validate_flip_headers.py`
- `docs/status/flip_retrofit_quarantine_4_0_43.md`

### Importer Updates Required
**MUST require:**
- artifact_path
- federated_node_id
- artifact_id
- created_ymdhis
- actor_id

**SHOULD use:**
- *_source fields (provenance)
- *_confidence fields (quality)
- relations[] (semantic graph)
- semantic_tags (classification)

**MUST reject files without minimum FLIP headers.**

## Key Principles

### Honesty Over Guessing
- Always record *_source and *_confidence
- Never lie about what we don't know
- If unknown, say so explicitly (confidence 0.0)

### Database Independence
- Headers enable offline operation
- IDE/importer can work without DB
- Filesystem is source of truth until import

### Provenance Tracking
- Know where every piece of data came from
- Assess reliability via confidence scores
- Enable data quality assessment

### Semantic Graph
- Build relationships without DB queries
- Support unresolved targets (placeholders)
- Enable incremental graph building

## Next Steps

**Awaiting directive to proceed with:**
1. Implementation of `scripts/flip_retrofit_artifacts.py`
2. Phase A retrofit (minimum FLIP for 100% coverage)
3. Manifest generation and validation
4. ANUBIS quarantine handling
5. Phase B enrichment (detailed FLIP)

**Ready to begin on your command.**

---

**KIRO (1001)**  
**Delegation Chain:** 1001:10000  
**Status:** ✅ ACKNOWLEDGED — AWAITING IMPLEMENTATION DIRECTIVE
