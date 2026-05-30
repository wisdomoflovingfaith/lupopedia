---
lupopedia.headers:
   header_format_version: "4.1.3"
   file_path_from_root: "docs/doctrine/lupopedia-headers/atoms_toon_schema.md"
   web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/atoms_toon_schema.md"
   status: "active"
   when_updated: "20260418XXXXXX"  # Update with current UTC before commit
   trust_tier: "staging"
   questions_toon: null
   memory_toon: "memory/development/staging/2026/04/atoms-toon-schema.toon"
   atoms_toon: null
   transcript_jsonl: "0/development/atoms-toon-schema"
   artifact_type: doctrine
   artifact_kind: reference
   channel_key: "development"
   federation_node_id: 0
   thread_id: ""
   content_id: null
   lupopedia.schema: doctrine
   title: "atoms_toon Field Schema (Reference, Not Enforced)"
   summary: "Reference schema for the atoms_toon header field: pointer to immutable sidecar, not primary PRD authority."
---

# atoms_toon Field Schema (Reference)

## Authority Model

This file is **secondary explanatory/reference documentation** for the `atoms_toon` header field. The **primary source of truth for documentation** is the PRD corpus in `docs/prd/`, with PRDs grouped in the numeric namespace **00–99** (there is never a PRD 100). If this file conflicts with a PRD, the PRD governs. See `docs/prd/PRD_INDEX.md` and `docs/prd/readme.md` for namespace organization; these are supporting references, not replacements for PRDs themselves.

## Atoms as Canonical Machine-Readable Truth

For version values, global constants, and similar canonical machine-readable invariants, the source of truth is not prose doctrine alone. These truths live in atoms TOON artifacts (`*.atom.toon` / atoms data). This document explains how `atoms_toon` works as a pointer/reference mechanism; it does not replace the actual atoms files as the authority for those values. Prose explains, atoms declare, PRDs govern doctrine.

---

# atoms_toon Field Schema

**Reference — Not Enforced**

This document defines the planned schema for the `atoms_toon` header field (see PRD 16 §4.2 field 21). The `.atoms.toon` file format and THOTH validation behavior are **future work** and are **not implemented** at this time. This schema is provided for orientation only. If in doubt, defer to PRD 16 and the current atoms TOON artifacts.

---


## 1. Purpose

`atoms_toon` is a **nullable pointer** in the Lupopedia file header. It points to an **`.atoms.toon`** file that contains **immutable, machine-readable truths** about the artifact's domain:

- Canonical definitions (e.g., the Lupopedia Headers specification)
- Version invariants (must not change without formal revision)
- Structural rules (artifact shape constraints)
- Authoritative facts for a domain

**Mental model:**

```
atoms_toon  = pointer to immutable reference truth (atoms file)
file content = mutable implementation
THOTH        = (future) validator that ensures implementation does not violate atoms
```

---


## 2. Relation to THOTH (Planned, Not Enforced)

**THOTH** is a planned corpus-wide validation layer. When implemented, THOTH will compare file content to the referenced `.atoms.toon` file and flag contradictions. As of this writing, THOTH is not implemented; `atoms_toon` is a pointer only. This file is explanatory, not the primary constitutional source. For schema and enforcement, defer to PRD 16 and the atoms TOON artifacts.

---


## 3. Current Validation Rules (Pointer Semantics Only)


| Rule | Behavior |
|------|----------|
| `null` value | Always valid. Default for all files. |
| Non-null string | MUST end in `.atoms.toon` (validator: `HDR_ATOMS_TOON_SUFFIX`) |
| Empty string `''` | **FORBIDDEN** — use `null` instead |
| File existence | **NOT checked** by the validator |
| Path format | No requirements on path prefix; relative paths recommended |

**Validator error codes:**


| Code | Trigger |
|------|---------|
| `HDR_ATOMS_TOON_SUFFIX` | `atoms_toon` is non-null but does not end in `.atoms.toon`; or is empty string |
| `HDR_MODULE_DEPRECATED` | Old `module` field found in header (WARN only — accepted as legacy alias) |

---


## 4. Future Validation (Planned)

The following are planned but not yet built:

- `.atoms.toon` file existence check
- Content format validation for `.atoms.toon` files
- THOTH integration (contradiction detection)
- Atoms generation pipeline

If you encounter uncertainty about what qualifies as an "immutable atom," how THOTH will determine contradiction, or how strict validation should be, log the question to the current version/status tracking location for the active release line, or route it into the appropriate PRD/channel workflow. Do not hardcode version-specific paths.

---


## 5. Example

```yaml
# File with atoms_toon pointing to a (future) atoms file:
atoms_toon: "docs/doctrine/lupopedia-headers/lupopedia-headers.atoms.toon"

# File with no atoms file yet (most common during this migration):
atoms_toon: null
```

---


## 6. Related Files and References

- **PRD 16 §4.2 field 21** — normative field specification (primary authority)
- **docs/prd/PRD_INDEX.md** — PRD namespace organization
- **docs/prd/readme.md** — PRD corpus overview
- **docs/doctrine/VERSIONING_DOCTRINE.md** — versioning and atoms source-of-truth doctrine
- **docs/doctrine/lupopedia-headers/validators_and_tooling.md** — validator behavior
- **scripts/migrate_module_to_atoms_toon.py** — migration script (module → atoms_toon)
- **scripts/lib/header_validation.py** — `validate_atoms_toon()` function

---
