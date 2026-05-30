---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/doctrine/PRD_GAPS.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/PRD_GAPS.md"
  status: "active"
  when_updated: "20260418213712"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/prd_gaps.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/doctrine/prd-gaps"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "prd-gaps"
  default_collection_id: null
  lupopedia.schema: "doctrine"
  title: "PRD Number Gaps and Reserves (00-99)"
  summary: "Documentation of which PRD numbers have no files and why"
---
**Location:** `lupo-docs/doctrine/PRD_GAPS.md`  
**Linked from:** `lupo-docs/prd/PRD_INDEX.md` (via cross-reference)

# PRD number gaps and reserves (00-99)

## Purpose

PRD filenames use a **two-digit numeric prefix** (`NN_*.md`) as a **group identifier** (see **`lupo-docs/prd/PRD_INDEX.md`**). **Gaps** (no file yet) are not automatically errors: some ranges are **reserved**, some are **deferred**, and some await drafting.

## Numbers with no primary PRD file today

The following **NN** values have **no** `NN_*.md` in `lupo-docs/prd/` at the time of this document (inventory aligns with the generator scan and manual review):

**39, 46, 47, 48, 49, 55, 57, 59, 62, 63, 64, 65, 66, 67, 68, 69, 81, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98**

## Classification (normative intent, not a registry lock)

| Category | Numbers (examples) | Intent |
|----------|-------------------|--------|
| **Adjacent / roadmap hold** | 46-49, 55, 57, 59, 62-69, 83-98 | Space for future PRDs without renumbering shipped work; **do not** reuse for unrelated topics without orchestration. |
| **Known relocated / superseded** | **81** | Superseded. Original material (`81_agent_orchestration_chat.md`) moved to `lupo-docs/prd/archive/`. Do not reuse 81 for new topics. |
| **Hermes / routing band** | **82** in use; **80** band | Keep **80-82** band coherent for data model / Hermes / install-seed family where possible. |
| **39** | Single gap | Reserved for cross-cutting constitutional topics (e.g., multi-tenant doctrine, audit specification). Do not assign without WOLFIE directive. |

## How to add a new PRD

1. Pick the lowest **free** number that fits the **topic band** (see **PRD 29** project structure and **PRD 26** layering).

   **Number selection priority:**
   - Within a reserved band (e.g., 80-82 for data model/Hermes) → use lowest free number in that band
   - Adjacent to related PRDs (e.g., PRD 37 exists → consider 38 or 39)
   - Numbers 90-99 are reserved for late-binding or emergency PRDs → do not use unless no other free number exists in relevant band
   - When in doubt → consult WOLFIE (Actor 1) or LILITH (Actor 2) before claiming a gap number

2. Add **`NN_topic.md`** under **`lupo-docs/prd/`** with a complete **`lupopedia.headers`** block (**PRD 16** freeze: **`header_format_version: "4.1.3"`**).

3. Run **`python lupo-scripts/generate_prd_index.py`** (do **not** hand-edit **`PRD_INDEX.md`**).

4. Update this file if the gap list changes materially.

## How to deprecate or relocate a PRD

1. **Do NOT delete the PRD file** — It becomes a historical record.

2. **Move to archive** — `mv lupo-docs/prd/NN_*.md lupo-docs/prd/archive/`

3. **Update header** — Change `status: "deprecated"` and add `deprecated_by: "MM_new_topic.md"`

4. **Add redirect note** — At the top of the archived file (after header):

   > This PRD has been superseded by PRD MM. See `lupo-docs/prd/MM_*.md`.

5. **Regenerate index** — `python lupo-scripts/generate_prd_index.py`

6. **Update this gaps file** — Add the number to the "Known relocated / superseded" category with a note.

**Do NOT reuse a deprecated PRD number for a new topic.** Once a number is used, it remains associated with that topic historically.

## Related

- **PRD 00** — Root constitutional system requirements (ultimate authority for documentation structure and PRD numbering)
- **PRD 26** — Five-layer documentation architecture (where doctrine and PRDs sit).
- **PRD 29** — Project structure and channel paths.
- **PRD 33** — Softaculous certification and 4.2.0 release gate (may reserve numbers for release-specific PRDs)
- **`lupo-docs/prd/PRD_INDEX.md`** — Auto-generated index (cross-link from index body to this file).
