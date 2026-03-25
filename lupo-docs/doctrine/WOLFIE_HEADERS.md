# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/WOLFIE_HEADERS.md"
  file_hash: "61898466b5055667252e66971aab3c18ef5e785a961e2f0791bce70af2212134"
  last_updated_utc: "20260228155738"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
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
  last_verified_by: "cursor"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\WOLFIE_HEADERS.md"
  file_hash: "01cb34a428493f0d07f9367cb3ace1ef28fe31e0428cab89766087c2b7a1f106"
  file_path_from_root: "lupo-docs\doctrine\WOLFIE_HEADERS.md"
  file_hash: "25cab989494df7a668bf2ef4e1a720771def938f6263ccb1020e18c6695732bd"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for WOLFIE_HEADERS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "wolfie_headersmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/WOLFIE_HEADERS.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/WOLFIE_HEADERS.md
---

# WOLFIE HEADER DOCTRINE v4.2
### Read-Only • Database-Generated • Projection-Only

## 1. PURPOSE
Wolfie Headers v4.2 are not metadata containers and not writable.  
They are read-only projections generated from the Lupopedia database.

Headers exist solely for:

- grep support
- human readability
- quick navigation
- debugging
- editor convenience

All authoritative metadata lives in:

- lupo_contents
- lupo_edges

The header is a view, not a source of truth.

## 2. REQUIRED FIELD

Every file must contain exactly one required field:

```
file_path_from_root: <relative_path_from_repo_root>
```

**Rules:**
- Lowercase only
- Must follow filename doctrine (a–z, 0–9, _)
- Must match the actual filesystem path
- Must be updated automatically when files move
- Must never be edited manually

## 3. OPTIONAL FIELDS (EDITOR / GREP SUPPORT)

These fields are generated from the database and content body.  
They are NOT semantic metadata and must NOT be written by agents or humans.

```
content_sections:
  - title: "<section title>"
    anchor: "<markdown anchor>"

version_number: <integer_update_count>

dialog_notes: <short note about last agent action>

status: <freeform text>

tags: [tag1, tag2, tag3]
```

The Python generator determines which optional fields appear.

## 4. EDGE PROJECTION FORMAT (FROM lupo_edges)

Edges are projected into the header in a normalized, grep-friendly format.

Each edge is represented as:

```
- type: <relationship_type>
  edge_type: <edge_type>
  left: <left_object_type>:<left_object_id>
  right: <right_object_type>:<right_object_id>
  bidirectional: <true|false>
  context: <context_scope>
  semantic_weight: <0.00-1.00>
```

Only fields present in the database row should be included.  
The Python generator controls the exact formatting.

## 5. READ-ONLY WARNING BLOCK

Every header must begin with:

```
# This header is generated from the Lupopedia database.
# Do not edit this header manually.
# All metadata and relationships are maintained in the database.
# This header is a read-only projection for grep and human reference.
```

## 6. EXAMPLE v4.2 HEADER

```
/* wolfie_header v4.2
   # This header is generated from the Lupopedia database.
   # Do not edit this header manually.
   # All metadata and relationships are maintained in the database.
   # This header is a read-only projection for grep and human reference.

   file_path_from_root: lupo-docs/lupopedia/core/identity/semantic_os.md

   content_sections:
     - title: "Overview"
       anchor: "#overview"
     - title: "What Is Lupopedia?"
       anchor: "#what-is-lupopedia"

   version_number: 12
   dialog_notes: updated section anchors and normalized spacing
   status: draft
   tags: [core, identity, review]

   edges:
     - type: dependency
       edge_type: imports
       left: content:1234
       right: content:5678
       bidirectional: false
       context: runtime
       semantic_weight: 0.80
*/
```

---

**END OF WOLFIE HEADER DOCTRINE v4.2**
