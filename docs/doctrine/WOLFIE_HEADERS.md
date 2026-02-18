---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/WOLFIE_HEADERS.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
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

   file_path_from_root: docs/lupopedia/core/identity/semantic_os.md

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
