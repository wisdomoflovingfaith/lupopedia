---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "docs/doctrine/FLARE/FLARE_DOCTRINE.md"
  system_version: "4.0.47"
  channel_id: 0
  actor_id: 10000
  last_modified_utc: "20260226"
  delegation_chain: "10000:10000"
  artifact_type: "doctrine"
  purpose: "Core doctrine defining FLARE protocol for file-level attribute and relationship exchange"
  mood_rgb: "FFD700"
  traits: ["canonical", "system-critical", "permanent"]
  tags: ["flare", "doctrine", "protocol", "file_metadata", "relationships"]
  lupo_agent: "wolfie"

flare.footer:
  outbound_edges:
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/FLIP/FLIP_DOCTRINE.md", type: "supersedes", weight: 0.8 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "doctrine", "protocol", "canonical", "system"]
---

# FLARE — File-Level Attribute and Relationship Exchange

**Status:** Permanent.  
**Audience:** All AI agents (including Cascade, Cursor, Windsurf), contributors, and system stewards.  
**Canonical:** This is the single source of truth for FLARE. No duplicate or suffixed FLARE doctrine files.  
**Supersedes:** FLIP_DOCTRINE.md (File-Level Inference Protocol)

---

## 🔄 Migration from FLIP to FLARE

FLARE replaces FLIP as the canonical protocol name, but both are currently accepted during migration:

| Old Name | New Name | Status |
|----------|----------|--------|
| `flip.headers` | `flare.headers` | Accepted until 4.1.0 |
| `flip.footer` | `flare.footer` | Accepted until 4.1.0 |
| `FLIP Header` | `FLARE Header` | Documentation updated |
| `File-Level Inference Protocol` | `File-Level Attribute and Relationship Exchange` | Terminology updated |

**Timeline:**
- 4.0.47-4.0.50: Both accepted (migration window)
- 4.1.0: FLIP deprecated, FLARE required
- 4.1.0+: Legacy keys emit warnings but still processed

---

## 1. Definition

**FLARE** stands for **F**ile-**L**evel **A**ttribute and **R**elationship **E**xchange.

It is the formal rule set that governs how Lupopedia and its AI agents interpret files. When a file is "flared" to the system (e.g. handed to any agent), the agent must **infer** everything it needs to know about that file **entirely from the FLARE Headers** — without guessing, without hallucinating, and without requiring context from elsewhere.

FLARE defines two distinct components:
- **flare.headers** → File-Level Attributes (metadata)
- **flare.footer** → File-Level Relationships (graph edges)

---

## 2. The Acronym

| Letter | Meaning | Doctrine |
|--------|---------|----------|
| **F** — File | Every file in Lupopedia is a first-class semantic object. | Files carry identity, lineage, doctrine, and relationship metadata. They are not passive blobs. |
| **L** — Level | Inference happens at the **file level**. | The boundary and truth source for that file is the FLARE Header. Not the database, not the system — the file. |
| **A** — Attribute | File metadata is expressed as structured attributes. | Identity, version, channel, mood, doctrine, placement — all as explicit attributes. |
| **R** — Relationship | Files explicitly define their relationships to other files. | Graph edges, dependencies, references, and semantic connections. |
| **E** — Exchange | FLARE enables bidirectional exchange of file information. | Files both declare their attributes and their relationships to the broader system. |

---

## 3. One-Sentence Definition

**FLARE is the protocol that tells Lupopedia: when a file is flared to you, read its FLARE Header to infer file attributes and relationship edges — identity, doctrine, meaning, and connections — without guessing.**

---

## 4. What Must Be Inferred from FLARE Headers

When a file is flared to an agent, the agent must infer the following **entirely from the FLARE Header** (where present in the header). No guessing. No filling in from repo scan or external context.

### 4.1 File Attributes (flare.headers)

- **File identity** — What this file is; its name, title, description.
- **File lineage** — Version, last modified system version, temporal placement.
- **File channel** — Channel key, channel identity, routing context.
- **File version** — System version at last edit; per-file version if present.
- **File emotional state** — Mood tensor (e.g. mood_RGB), emotional metadata per MOOD_RGB doctrine.
- **File doctrine** — Which doctrines apply; governance markers; header_atoms.
- **File placement** — Where the file sits in the semantic OS (collections, categories, paths).
- **File semantic meaning** — What the file is for; purpose, artifact_type, tags.
- **File delegation** — Actor delegation chain, responsibility flow.

### 4.2 File Relationships (flare.footer)

- **Outbound edges** — Files this document references or depends on.
- **Edge types** — Nature of relationships (references, implements, schema_reference, etc.).
- **Edge weights** — Strength/importance of relationships (0.5-1.0 scale).
- **Semantic tags** — High-level relationship categorization.

If a field is absent from the header, the agent must **not** invent it. Infer only what the header provides. Omission is information.

---

## 5. Why FLARE Matters

Lupopedia is a **semantic OS**, not a framework. Files are not passive — they carry doctrine, metadata, emotional geometry, version lineage, channel identity, semantic meaning, and **explicit relationships**. FLARE ensures that when you hand an agent a file, it **knows exactly what it is and how it connects** from the header alone.

FLARE is what makes FLARE Headers **operational** instead of just descriptive. The header is not decoration; it is the **contract** for that file and its place in the semantic graph.

### Key Improvements over FLIP:

1. **Explicit Relationships** - Files declare their connections via `flare.footer`
2. **Clear Separation** - Attributes vs relationships are distinct sections
3. **Better Naming** - "Attribute and Relationship Exchange" is more descriptive
4. **Graph Awareness** - Built for semantic graph navigation
5. **Edge Taxonomy** - Standardized relationship types and weights

---

## 6. Relationship to Other Doctrine

- **FLARE Header specification** — Defines the structure and fields of the header. See `docs/FLARE_HEADERS_QUICK_REFERENCE.md` and `docs/FLARE_HEADERS_COMPLETE_REFERENCE.md`.
- **MOOD_RGB / emotional geometry** — Emotional state in the header (e.g. mood_RGB) is inferred and interpreted per MOOD_RGB doctrine. FLARE does not redefine emotional axes.
- **TOON / schema** — FLARE governs **file** interpretation. Schema and table definitions still come from TOON files only. FLARE does not replace schema doctrine.
- **Graph doctrine** — FLARE's relationship edges integrate with Lupopedia's semantic graph infrastructure.

---

## 7. FLARE Compliance Checklist for Agents

When handling any Lupopedia file that has a FLARE Header, the agent MUST:

### 7.1 Header Processing
1. **Read the header first** — Before inferring anything about the file, read the full YAML block between the leading `---` delimiters.
2. **Infer only from the header** — Do not guess identity, channel, version, or doctrine from path, filename, or repo structure. Use only what the header states or implies.
3. **Do not hallucinate fields** — If the header does not contain a field (e.g. mood_RGB, channel_id), do not invent a value. Treat absence as absence.
4. **Respect header_atoms** — Resolve symbolic references (e.g. GLOBAL_CURRENT_LUPOPEDIA_VERSION) from the project's atom source (e.g. config/global_atoms.yaml), not from guesswork.

### 7.2 Attribute Processing
5. **Parse file attributes** — Extract all metadata from `flare.headers` section.
6. **Validate required fields** — Ensure all required fields are present and valid.
7. **Process delegation chain** — Understand actor responsibility flow.
8. **Apply emotional context** — Use mood_RGB and emotional metadata for appropriate interaction.

### 7.3 Relationship Processing
9. **Parse relationship edges** — Extract all outbound edges from `flare.footer` section.
10. **Validate edge targets** — Check that referenced files exist (when possible).
11. **Apply edge weights** — Use weights to prioritize relationship importance.
12. **Navigate semantic graph** — Use edges to understand file context and dependencies.

### 7.4 Behavioral Rules
13. **Use inferred state for all downstream behavior** — Routing, permissions, emotional context, and placement decisions must use the inferred file identity and metadata, not external assumptions.
14. **Do not alter the header to "fix" inference** — If something is missing, do not add it to the header unless explicitly asked to update the file. FLARE is read-only inference; header edits are separate operations.
15. **Handle legacy keys gracefully** — During migration, accept `flip.headers`/`flip.footer` but emit warnings.

---

## 8. Validation Rules

### 8.1 Required Fields Validation
```yaml
# These fields MUST be present and valid
file_path_from_root:
  validation: "^[a-zA-Z0-9_\\-/\\.]+\\.md$"
  max_length: 500
  required: true
  error_message: "Path must be relative, use forward slashes"

system_version:
  validation: "^\\d+\\.\\d+\\.\\d+$"
  required: true
  error_message: "Version must be X.Y.Z format"

channel_id:
  validation: "^\\d+$"
  required: true
  error_message: "Channel ID must be numeric"

actor_id:
  validation: "^\\d+$"
  required: true
  error_message: "Actor ID must be numeric"

delegation_chain:
  validation: "^\\d+(:\\d+)*$"
  required: true
  error_message: "Delegation chain must be colon-separated actor IDs"

artifact_type:
  validation: "^(doctrine|guide|directive|broadcast|status|profile)$"
  required: true
  error_message: "Artifact type must be one of: doctrine, guide, directive, broadcast, status, profile"
```

### 8.2 Actor ID Validation
Actor IDs MUST exist in `actors/registry.json` or the database.

**Validation process:**
1. Check `actors/registry.json` for mapping
2. If online, query `lupo_actors` table
3. If offline and not in registry → WARNING but accept

**Invalid actor_id handling:**
- Log error but continue processing
- File will be flagged for review
- Must be fixed before DB import

---

## 9. Offline Mode Behavior

When database is unavailable:
- All validation uses local `actors/registry.json` only
- Table references cannot verify TOON files exist
- Actor IDs validated against registry only
- Edge targets checked for file existence only
- Warnings logged but processing continues

---

## 10. Summary

| Rule | Requirement |
|------|-------------|
| **File = first-class object** | Every file has identity, lineage, doctrine, emotional metadata, and relationships. |
| **Level = file** | Inference boundary is the file; truth source is the FLARE Header. |
| **Attribute = structured metadata** | Identity, lineage, channel, version, emotional state, doctrine, placement, meaning — all as explicit attributes. |
| **Relationship = explicit edges** | Files declare their connections via typed, weighted edges in the semantic graph. |
| **Exchange = bidirectional** | Files both declare their attributes and their relationships to the broader system. |

### Key Differences from FLIP:
- ✅ **Explicit relationships** via `flare.footer`
- ✅ **Clear separation** of attributes vs relationships
- ✅ **Better naming** and terminology
- ✅ **Graph-aware** design
- ✅ **Comprehensive validation** rules
- ✅ **Migration path** from FLIP

---

*End of FLARE doctrine.*
