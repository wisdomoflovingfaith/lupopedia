# 🐺 PROMPT FOR CLAUDE CODE — Controlled `module` → `atoms_toon` Migration

## MISSION

Implement a **controlled, evidence-first migration** that replaces the unused header field `module` with the new field `atoms_toon` in the Lupopedia header ecosystem.

This is **not** a blind search-and-replace task.

This is a **spec + validator + targeted migration + report** task.

The goal is to introduce `atoms_toon` safely, preserve deterministic header structure, avoid rewriting history without policy, and produce a clear migration report.

---

## CORE DECISION

`module` is deprecated and should be replaced by `atoms_toon`.

### Why

`module` is currently unused and has effectively become dead space in the header envelope.

`atoms_toon` is needed as a nullable pointer to a `.atoms.toon` file containing immutable per-file constants such as:

* app version at creation
* canonical creation timestamp
* instance / node origin
* THOTH verification anchor
* immutable nonce or equivalent stable identifier

This migration preserves the **field slot** in the header structure by replacing field 21 rather than adding a new field elsewhere.

---

## NON-NEGOTIABLE RULES

1. **Do not do a blind repo-wide replacement first.**
2. **Do not rewrite historical artifacts unless explicitly classified as safe to migrate.**
3. **Do not claim “no breaking changes” unless you actually prove compatibility.**
4. **Do not invent hidden behavior, implicit defaults, or silent normalization.**
5. **Do not change header field ordering except where this task explicitly requires replacing `module` with `atoms_toon` in the same slot.**
6. **Do not mass-edit generated artifacts unless the report explicitly classifies them and explains why they were edited.**
7. **Do not require `.atoms.toon` file existence in this migration unless tooling already exists and the report shows it is safe.**
8. **All work must be deterministic and fully reported.**
9. **Prefer scripted, reviewable changes over brittle shell one-liners.**
10. **If you find ambiguity, preserve existing behavior and document the ambiguity in the report.**

---

## REQUIRED EXECUTION MODEL

Perform this work in the following order:

### Phase 0 — Impact Scan First

Before editing anything, scan the repository and build an evidence-based inventory of all references to:

* `module`
* `atoms_toon`
* header field lists
* validator allowlists
* parser / serializer logic
* examples
* doctrine references
* rule files
* generated artifacts

Create a report that classifies every hit into categories such as:

* active doctrine
* active PRD / canonical spec
* validator / parser code
* rule / policy file
* example snippet
* historical version artifact
* generated artifact
* unknown / manual review needed

Do not proceed to broad edits until this classification is complete.

---

## REQUIRED OUTPUTS

Create or update these outputs:

### 1. Migration scan report

Create:

`lupo-docs/versions/4.1.0/status/atoms_toon_migration_scan_report.md`

This report must include:

* total hits for `module`
* total hits for `atoms_toon`
* files grouped by classification
* recommended edit scope
* files intentionally excluded from automated migration
* compatibility risks
* open questions

### 2. Migration change report

Create:

`lupo-docs/versions/4.1.0/status/atoms_toon_migration_change_report.md`

This report must include:

* files actually changed
* files intentionally not changed
* validator changes
* spec changes
* counts by file type
* any compatibility risks still remaining
* exact verification commands run
* final unresolved questions

### 3. Optional helper script if needed

If broad header edits are required, create a deterministic migration script rather than relying on fragile shell substitution.

Suggested path:

`lupo-scripts/migrate_module_to_atoms_toon.py`

This script must:

* update header zones only
* preserve header order
* replace field key `module` with `atoms_toon`
* preserve `null` values where appropriate
* avoid touching body text unless explicitly intended
* report exact file changes
* support dry-run mode
* support apply mode
* never silently rewrite malformed files

If a helper script is not needed, explain why in the report.

---

## PHASE 1 — SPEC UPDATE

Update the canonical PRD/spec for Lupopedia headers.

### Primary spec file

Update:

`lupo-docs/prd/16_lupopedia_headers.md`

### Required changes

#### A. Field list

Replace field 21:

```diff
- 21. `module` - string or YAML null; logical module or subsystem identifier
+ 21. `atoms_toon` - string or null; path to `.atoms.toon` file containing immutable per-file constants used for provenance and THOTH verification
```

#### B. Field table entry

Update field table entry so it clearly states:

* field name: `atoms_toon`
* type: string or null
* meaning: path to `.atoms.toon`
* status: nullable
* intended purpose: immutable per-file constant record
* path format guidance, if applicable

Suggested wording:

```markdown
| 21 | `atoms_toon` | string or null | Path to a `.atoms.toon` file containing immutable per-file constants such as creation-version, canonical timestamp, instance provenance, and THOTH verification anchor. Nullable until such file exists. |
```

#### C. Definitions section

Add a definition section for **Atoms TOON** explaining:

* it is distinct from mutable `.toon` memory files
* it is intended to represent immutable per-file constants
* it may be `null` until generation tooling exists
* this migration introduces the pointer field first, not necessarily full generator enforcement

#### D. Forbidden / deprecated section

Explicitly mark `module` as deprecated / removed from the active format.

Example:

```yaml
# DEPRECATED / REMOVED FROM ACTIVE HEADER FORMAT
module: null
```

Clarify that old artifacts may still contain `module` historically until separately migrated or preserved by policy.

#### E. Examples

Update all active examples in the spec:

* replace `module: null` with `atoms_toon: null`
* where a concrete path is helpful, use a single clear example path
* do not fabricate a fake ecosystem of generated `.atoms.toon` files if that tooling does not yet exist

#### F. Changelog / decision note

Add a note in the PRD changelog describing:

* `module` deprecated
* `atoms_toon` introduced in field slot 21
* existence validation deferred unless tooling already supports it

---

## PHASE 2 — VALIDATOR AND HEADER SPEC SUPPORT

Update the validator and header spec definitions so the system understands `atoms_toon`.

### Files to inspect and update if present

* `lupo-scripts/lib/header_spec_v3_1.py`
* `lupo-scripts/lib/header_validation.py`
* `lupo-scripts/validate_lupopedia_headers_universal.py`

Also inspect any other files that define:

* canonical field ordering
* nullable field sets
* allowed field names
* error codes
* serializer expectations

### Required logic

#### A. Canonical field order

Replace `module` with `atoms_toon` in the same field position.

Example intent:

```python
FIELDS_V4 = [
    'header_format_version',
    'lupopedia.schema',
    'when_updated',
    'file_path_from_root',
    'web_path',
    'questions_toon',
    'federation_node_id',
    'channel_key',
    'trust_tier',
    'memory_key',
    'artifact_type',
    'artifact_kind',
    'thread_id',
    'content_id',
    'pk_id',
    'pk_slug',
    'title',
    'status',
    'parent_pk_id',
    'summary',
    'atoms_toon',
    'dialog_transcript'
]
```

Adjust only if the real canonical order in repo differs. Do not assume this list is authoritative without checking actual code.

#### B. Nullable fields

Ensure `atoms_toon` is treated like a nullable field.

Example intent:

```python
NULLABLE_FIELDS = {'content_id', 'pk_id', 'questions_toon', 'atoms_toon'}
```

Again: confirm actual repo structure before changing.

#### C. Validation rule

Add validation that:

* allows `null`
* allows string values ending in `.atoms.toon`
* reports a deterministic validation error otherwise

Example intent:

```python
def validate_atoms_toon(value, file_path):
    if value is None:
        return True
    if isinstance(value, str) and value.endswith('.atoms.toon'):
        return True
    raise ValidationError(
        "HDR_ATOMS_TOON_INVALID",
        f"atoms_toon must be null or end with .atoms.toon, got {value}"
    )
```

#### D. Compatibility behavior

If the current validator still encounters legacy `module` in historical files, do **not** silently convert them at validation time unless that behavior already exists for similar deprecated fields.

Instead choose one of these and document which was implemented:

1. strict active-format failure for active files, or
2. compatibility warning for historical files, or
3. transitional acceptance with deprecation warning

Document the chosen policy clearly.

---

## PHASE 3 — DOCTRINE AND RULE FILES

Update active doctrine and rule files that define the header format.

### Files to inspect

* `lupo-docs/doctrine/lupopedia-headers/lupopedia_headers_format.md`
* `lupo-docs/doctrine/lupopedia-headers/validators_and_tooling.md`
* `.cursor/rules/lupopedia-headers-mandatory.mdc`

Also inspect related doctrine files if they directly define or explain field 21.

### Required changes

* replace active doctrinal references to `module` with `atoms_toon`
* update field tables
* update examples
* add or update validator / error-code documentation
* add a deprecation note for `module`
* make clear whether `.atoms.toon` existence is validated now or deferred

If there is a doctrine file for memory schemas, inspect whether a lightweight section on `.atoms.toon` belongs there.

---

## PHASE 4 — OPTIONAL ATOMS TOON SCHEMA DOC

If appropriate, create:

`lupo-docs/doctrine/lupopedia-headers/atoms_toon_schema.md`

But only do this if it helps document the field without pretending the entire generation ecosystem is already complete.

This document should be labeled clearly as one of:

* canonical schema
* provisional schema
* draft schema
* future implementation schema

Do not overstate maturity.

Minimum content:

* purpose
* immutable intent
* proposed fields
* path convention if known
* validation status
* what is enforced now vs deferred

---

## PHASE 5 — TARGETED HEADER MIGRATION

Only after the scan and spec/validator work are done:

Migrate **active canonical files** that should now use `atoms_toon`.

This includes active spec, doctrine, and rule files.

For historical version artifacts, generated files, and frozen records:

* do not migrate blindly
* only migrate if the scan report classifies them as safe and useful
* otherwise leave them unchanged and record why

### Migration policy

For active headers that currently contain:

```yaml
module: null
```

replace with:

```yaml
atoms_toon: null
```

For any non-null `module` values discovered:

* do not automatically reinterpret them as `atoms_toon`
* report them for manual review unless the semantics are unquestionably identical

For text outside actual header zones:

* only update prose/examples when doing so is clearly part of the active documentation update
* do not mass-rewrite historical narrative text just to erase the old word

---

## PHASE 6 — VERIFICATION

Run verification appropriate to the repo after edits.

### Minimum required verification

1. Search for remaining active-format `module` field definitions
2. validate affected header specs / validators
3. run any existing header validation tooling
4. confirm field ordering still matches canonical expectations
5. confirm no malformed headers were created by the migration

### Required reporting

Report at least:

* how many active files changed
* how many historical files were left unchanged
* whether validator passed
* whether any legacy `module` usage remains
* whether remaining usage is intentional

Do not use “0 remaining `module`” as the only success criterion.

A valid outcome may still include historical references if they are intentionally preserved and documented.

---

## REQUIRED FINAL RESPONSE FORMAT

When done, report in this structure:

```markdown
## ✅ Controlled atoms_toon Migration Complete

### Scope
- [summary of scan scope]
- [summary of active files updated]
- [summary of files intentionally preserved]

### Files Changed
- [file path] — [what changed]
- [file path] — [what changed]

### Validator / Spec Changes
- [summary]

### Migration Policy Decisions
- [how legacy `module` was handled]
- [whether historical files were preserved]
- [whether `.atoms.toon` existence is enforced or deferred]

### Verification
- [commands run]
- [results]
- [remaining known issues]

### Follow-Up Recommended
- [next steps, if any]
```

---

## IMPORTANT IMPLEMENTATION NOTES

* Use the repository’s actual canonical files, not assumptions from this prompt, if they differ.
* Preserve deterministic formatting and field order.
* Prefer minimal, reviewable diffs.
* If a proposed file path in this prompt does not exist, locate the real equivalent and report that substitution.
* If the migration reveals multiple competing header specs, document the conflict instead of guessing.
* If there are frozen historical artifacts that should remain historically accurate, preserve them and record the policy.

---

## DELIVERABLE EXPECTATION

This task is complete only when:

1. the scan report exists
2. the change report exists
3. active header spec files support `atoms_toon`
4. validator logic supports `atoms_toon`
5. active doctrine/examples are updated
6. any active migrated headers use `atoms_toon`
7. the final report explains exactly what was and was not changed

Do not skip the scan/report phases.

Execute carefully.
