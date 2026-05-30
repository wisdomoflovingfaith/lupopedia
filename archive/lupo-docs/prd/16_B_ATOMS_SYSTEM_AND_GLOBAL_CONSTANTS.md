---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/headers/canonical/1026/04/lupopedia-headers.toon
  atoms_toon: lupo-memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
  artifact_type: prd
  artifact_kind: specification
  channel_key: headers
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_16_B_16_C
  title: "PRD 16: Atoms System and Global Constants (Core Doctrine)"
  summary: "Core doctrine for atoms and global constants in PRD 16 identity layer. Defines header examples and atom system usage."
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->

<!-- HUMAN_SEMANTIC -->
This file belongs to:
??? PRD Group 16 (Identity Layer ??? Headers, Atoms, Migration)
??? Cluster 16ABCD
??? Channel: headers
??? No default collection yet

See also:
??? 00_A_FORBIDDEN_AND_WHY.md
??? 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
??? PRD 86 ??? Immune system (no drift, no entropy)
<!-- /HUMAN_SEMANTIC -->

# PRD 16: Atoms System and Global Constants

**Grouping:** This file is a **secondary** `artifact_kind: specification` companion in the **PRD 16** family. The **primary** normative envelope specification remains **`16_lupopedia_headers.md`**. This document does not redefine header field order, counts, or validator mechanics.

**Companion reference:** Field-level pointer semantics for **`atoms_toon`** remain in **`lupo-docs/doctrine/lupopedia-headers/atoms_toon_schema.md`** (reference, not a PRD).

---

## 1. Definition: atoms

**Atoms** are **canonical, immutable, machine-readable truths** for a bounded domain (for example global constants, header field order, numeric limits).

- **Not** narrative doctrine (no explanatory prose as the authority surface).
- **Not** mutable operational state (no session counters, caches, or live counters).
- **Not** user-owned data rows.

Atoms are **declared data**: versioned or generation-stamped artifacts intended for tools, validators, and future corpus checks to read without interpreting Markdown.

---

## 2. Scope

**In scope (examples):**

- Product and schema **version values** when published as atoms (for example `constants.versioning` in the global atoms artifact).
- **Global constants** (actor id maps, trust tier ordinals, constitutional numeric caps when mirrored in atoms).
- **Invariants** that must hold across the tree (for example fixed header field count and key order when mirrored under `constants.header_fields` in atoms).
- **Structural rules** that can be expressed as data (allowed enumerations, required segments in a slug pattern) when stored as atoms rather than prose.

**Out of scope:**

- **User data** (`lupo_*` row payloads, channel messages, uploads metadata as operational truth).
- **Runtime state** (current request, session blobs, ephemeral flags).
- **Transient configuration** (per-host overrides that are expected to change without a formal atoms revision). Those belong in config files and PRD-described procedures, not in immutable atoms unless explicitly promoted.

---

## 3. Relationship to PRD 16 (headers)

- **`16_lupopedia_headers.md`** defines the **header envelope**: the **22** YAML keys under **`lupopedia.headers`**, order, presence rules, and validation policy (**Header freeze rule (4.1.4)** applies).
- **This file** defines the **atoms system**: what atoms are, how they relate to doctrine, and how headers **reference** them.
- Headers link to atoms **only** through **`atoms_toon`** (nullable path to an **`.atoms.toon`** sidecar, or repository convention for the global constants artifact path used today). Atoms **are not** embedded inside the header grid; the header carries a **pointer**, not the atom payload.

---

## 4. Relationship to PRDs (precedence)

| Surface | Role |
|---------|------|
| **PRD (Markdown)** | Human-readable **meaning**, policy, exceptions, and process. |
| **Atoms (TOON / structured export)** | Machine-readable **current declared values** and invariants for tools. |

**Conflict rule:**

- **Truth Stack hierarchy applies** (see PRD 00_A ??13): Atoms override PRDs when conflicts exist
- If **meaning** or **policy** conflicts: the **PRD** defines authoritative **intent** and must be corrected in prose (or the atom must be regenerated to match after a deliberate decision).
- If a **value** in atoms disagrees with **ship reality** (for example wrong version string): treat the atom as **wrong** until regenerated from the approved source; PRDs describe **what** the value means; atoms hold the **declared** value for automation.

**See also:** **[PRD 40](40_versioning_doctrine.md)** ??? product version bands, header **`header_format_version`** vs ship version, and upgrade policy. Values under **`constants.versioning`** in the global atoms bundle **MUST** stay aligned with **PRD 40** and **`lupo-config/global_atoms.yaml`**.

---

## 5. Atoms TOON structure and linkage

**Naming**

- Sidecar convention: **`*`.atoms.toon`** (suffix enforced for non-null **`atoms_toon`** in headers per validators and **PRD 16**).
- Global bundle example: **`lupo-memory/atoms/lupopedia_global_constants.atom.toon`** (authoritative path referenced by multiple PRD 16 family headers today).

**File structure**

- Atoms files are **structured** documents (JSON-shaped exports or TOON-encoded equivalents per project tooling). They MUST remain **diff-friendly** and **ordered** per **[Canonical TOON Ordering Specification v1.0.0](../doctrine/TOON_ORDERING_SPEC.md)** where TOON applies.
- Typical top-level keys (illustrative, not exhaustive): **`atom_version`**, **`atom_type`**, **`description`**, **`generated`**, **`generator`**, **`constants`** (nested object), optional **`edges`** for traceability to PRDs and SQL.

**Location conventions**

- **`lupo-memory/atoms/`** for cross-cutting global constants and similar **immutable** bundles.
- Future domain-specific atoms MAY live under **`lupo-memory/{channel_key}/...`** when header **`trust_tier`** and routing require it; path MUST be stable and documented before use.

**Linkage via `atoms_toon`**

- **`atoms_toon: null`** ??? no atom sidecar for this artifact (default).
- **`atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"`** ??? this file participates in header-field and global-constant truth carried in that bundle.

**Path conventions:** `memory_toon` paths typically use **kebab-case**. `atoms_toon` paths for global bundles use **snake_case** with the **`.atom.toon`** suffix.

### 5.1 Atom versioning policy

`atom_version` uses semantic versioning:

- **Major** ??? Breaking change to atom structure.
- **Minor** ??? New fields or non-breaking expansion; old readers SHOULD ignore unknown keys.
- **Patch** ??? Value correction only; structure unchanged.

---

## 6. THOTH integration (future)

**THOTH** (actor **26**) will read referenced atoms and flag contradictions between declared invariants and actual implementation artifacts.

Until THOTH is live, humans and IDE agents treat atoms as authoritative inputs for validation where tools exist.

---

## 7. Validation rules (normative posture)

- **Immutability:** An atoms file tagged as canonical MUST NOT be hand-edited (directly or via script) to fix drift. Always edit the source (YAML master or PRD) and regenerate the atom.
- **Explicit changes only:** Any change to declared constants MUST be reviewable.
- **No silent mutation:** CI and agents MUST NOT rewrite atom files without an auditable trail from the approved source.

---

## 8. Generating and updating atoms

Atoms are generated from approved sources, never edited ad hoc.

| Atom bundle | Primary inputs / tooling | Notes |
|-------------|-------------------------|--------|
| **`lupo-memory/atoms/lupopedia_global_constants.atom.toon`** | **`lupo-config/global_atoms.yaml`** + **`lupo-scripts/update_atom.py`** | Keep YAML, JSON, and `.atom.toon` in lockstep. |

**Workflow:**

1. Change the **source** (YAML master or PRD), never the generated atom directly.
2. Regenerate the atom from the source.
3. Commit source + regenerated atom together with a changelog buffer entry.
4. Never hand-edit or script-overwrite the atom file to "fix" it.

---

## 9. Examples

### 9.1 Sample excerpt (global constants atom)

```json
{
  "atom_version": "1.0.0",
  "atom_type": "global_constants",
  "description": "Single source of truth for Lupopedia global constants.",
  "constants": {
    "versioning": {
      "current_lupopedia_version": "4.1.4"
    },
    "header_fields": {
      "count": 22,
      "order": [
        "header_format_version",
        "file_path_from_root",
        "web_path",
        "status",
        "when_updated",
        "trust_tier",
        "questions_toon",
        "memory_toon",
        "atoms_toon",
        "transcript_jsonl",
        "artifact_type",
        "artifact_kind",
        "channel_key",
        "federation_node_id",
        "thread_id",
        "content_id",
        "content_parent_id",
        "default_collection_id",
        "lupopedia.schema",
        "prd_cluster",
        "title",
        "summary"
      ]
    }
  }
}
```

### 9.2 Sample header referencing atoms

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS.md"
  status: "active"
  when_updated: "20260421120000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/headers/canonical/1026/04/lupopedia-headers.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/headers/lupopedia-headers"
  artifact_type: prd
  artifact_kind: specification
  channel_key: "headers"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "00_A_16_B_16_C"
  title: "PRD 16: Atoms System and Global Constants (Core Doctrine)"
  summary: "Core doctrine for atoms and global constants in PRD 16 identity layer."
---
```

### 9.3 When atoms_toon is null

```yaml
atoms_toon: null   # No machine-readable atom counterpart for this artifact
```

---

## Revision note

| Date (UTC) | Summary |
|------------|---------|
| 20260418200126 | Initial secondary PRD 16 specification for atoms system and global constants. |
| 20260418215705 | thread_id null; PRD 38/51/40 cross-refs; path-convention note; atom versioning; generation workflow; examples renumbered. |
| 20260421130000 | Updated to 22 fields including prd_cluster, removed content_slug field from header structure. |
