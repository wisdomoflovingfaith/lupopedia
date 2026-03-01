# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLIPQL_SPECIFICATION.md"
  file_hash: "56fc40aa1285cef44ccba7f9e7679327b00d891bf0e9ee0446fb8d9e8cf112cb"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\FLIP\FLIPQL_SPECIFICATION.md"
  file_hash: "e357c0470a23e8bcd81560b3e59f401abc583a0d92768c64dd32b57c61a1b688"
  file_path_from_root: "docs\doctrine\FLIP\FLIPQL_SPECIFICATION.md"
  file_hash: "5c7b572b8dd54493fafd5c4dcb49b470477e8b21fb3690620f8f29dba1168b52"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIPQL_SPECIFICATION.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flipql_specificationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "docs/doctrine/FLIP/FLIPQL_SPECIFICATION.md"
system_version: "4.0.35"
channel_id: 42
mood_rgb: "00FFFF"
actor_id: 1003
lupo_agent: "antigravity"
purpose: "Specification for FLIPQL (File-Level Inference Protocol Query Language)"
---

# FLIPQL: File-Level Inference Protocol Query Language

**Version:** 1.0.0 (Concept)  
**System Version:** 4.0.35  
**Status:** PROPOSED / ARCHITECTURAL SPECIFICATION  
**Audience:** AI Agents, System Stewards, Tooling Developers  

---

## 1. Overview

**FLIPQL** is a lightweight, SQL-inspired query language designed specifically for the Lupopedia Semantic OS. It allows AI agents and system services to query the bidirectional semantic graph embedded in **FLIP Headers** and **FLIP Footers** across the repository.

Unlike traditional SQL which queries a database, FLIPQL queries the **filesystem itself** as the primary source of truth, enforcing the "Inference-First" doctrine of FLIP.

---

## 2. Syntax Structure

FLIPQL follows a declarative structure:

```sql
SELECT [fields] 
[FROM scope] 
[WHERE conditions] 
[FACET field] 
[ORDER BY field [ASC|DESC]] 
[LIMIT n]
```

### 2.1 SELECT (Fields)
- `*`: Returns all metadata fields and the file path.
- `header.field_name`: Specific field from the YAML header.
- `footer.field_name`: Specific field from the YAML footer.
- `aggregates`: `COUNT()`, `LIST()`, `HAS()`.

### 2.2 FROM (Scope)
Defines the scanning boundary:
- `REPO`: (Default) Entire repository.
- `DIRECTORY path`: Sub-tree search.
- `CHANNEL id`: Scoped by shared channel ID.
- `ACTOR id`: Scoped by actor involvement.
- `FILE path`: Single-file inspection.

### 2.3 WHERE (Conditions)
- **Comparisons**: `=`, `!=`, `>`, `<`, `>=`, `<=`
- **Logic**: `AND`, `OR`, `NOT`
- **Arrays**: `CONTAINS`, `HAS_ANY`, `LENGTH`
- **Strings**: `LIKE` (wildcards), `REGEX`
- **Existence**: `EXISTS`, `MISSING`
- **Faceted Selection**: `FACET` (Groups results by a specific field, e.g., `FACET channel_id`).

### 2.4 Embedded Execution
FLIPQL queries can be embedded directly within FLIP Footers to provide dynamic, self-updating documentation.
- Syntax: `embedded_query: "SELECT ..."`
- Execution: Resolved at runtime by the `lupopedia-loader.php` or VSX Extension.

---

## 3. Core Field Mapping

FLIPQL fields are derived from the canonical mapping specified in `FLIP_HEADER_TO_TOON_MAP.md`.

| FLIPQL Field | Semantic Target |
|--------------|-----------------|
| `actor_id` | Identity of the primary file actor. |
| `channel_id` | Communication channel anchoring. |
| `system_version` | Lineage marker. |
| `mood_rgb` | Emotional geometry state. |
| `footer.inbound_edges` | Semantic graph back-references. |
| `footer.referenced_by_actors` | Conflict/Ownership tracking. |

---

## 4. Query Examples

### 4.1 Dependency Impact Analysis
Find all files that depend on a specific doctrine file within Channel 42:
```sql
SELECT file_path_from_root, footer.inbound_edges
WHERE footer.inbound_edges.edge_type = "semantic_dependency" 
  AND channel_id = 42
ORDER BY file.last_modified_utc DESC
```

### 4.2 Emotional Geography Search
Find "tense" files (red-spectrum) that mention "security" in the footnotes:
```sql
SELECT file_path_from_root, mood_rgb
WHERE mood_rgb REGEX "^F[0-9A-F]{5}" 
  AND footer.footnotes CONTAINS "security"
LIMIT 10
```

### 4.3 Validation & Cleanup
Identify files missing mandatory governance markers:
```sql
SELECT file_path_from_root
WHERE GOV-AD-PROHIBIT-001 MISSING 
   OR system_version < "4.0.0"
```

### 4.4 Faceted Emotional Context
Group all "indigo-mood" files by their primary channel:
```sql
SELECT file_path_from_root
WHERE mood_rgb REGEX "^4B"
FACET channel_id
```

---

## 5. Implementation Roadmap

1. **Parser (v1.0)**: Regex-based YAML extractor (Python implementation).
2. **Indexer (v1.1)**: JSON-based metadata cache for sub-second REPO-wide queries.
3. **IDE Integration (v1.2)**: "FlipExplorer" plugin for Cursor/VS Code.
4. **Relational Bridge (v1.3)**: Crossing FLIPQL results with live TOON database states.

---

## 6. Rules for Agents
- **Read-Only**: FLIPQL is for inference and discovery. It does not modify files.
- **Capped Results**: Default limit is 100 for safety.
- **Protocol Purity**: Never invent data; if a field is missing in the file, it is `NULL` in the result.

---

**SPECIFICATION ENDS**  
**Maintained By:** Antigravity  
**Effective UTC:** 20260224  