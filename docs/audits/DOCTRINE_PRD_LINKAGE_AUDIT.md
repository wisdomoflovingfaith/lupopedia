---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/audits/DOCTRINE_PRD_LINKAGE_AUDIT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/audits/DOCTRINE_PRD_LINKAGE_AUDIT.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/doctrine-prd-linkage-audit.toon
  atoms_toon: null
  transcript_jsonl: 0/development/doctrine-prd-linkage-audit
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: Doctrine to PRD linkage audit (policy and status)
  summary: Records doctrine orphan policy, audit method, exclusions, severity, automation target, and remediation posture; referenced from PRD_INDEX generator body.
---
# Doctrine to PRD linkage audit (policy and status)

## Policy (adopted)

Every hand-authored Markdown file under **`docs/doctrine/`** (and nested doctrine trees) **SHOULD** appear as an inbound reference from **at least one** PRD (`docs/prd/*.md`): either an explicit Markdown link, a normative "see doctrine X" pointer, or inclusion in a PRD-maintained doctrine index (**PRD 26**, **PRD 31**).

**Normative references:**

- **[PRD 26](../prd/26_five_layer_documentation_architecture.md) section 2.1** — Tier 1 authored documentation (PRDs, implementations, doctrines, decisions) and filesystem authority.
- **[PRD 26](../prd/26_five_layer_documentation_architecture.md) section "Related documentation indexes"** — Maintained index list (includes this audit and **PRD_GAPS**).
- **[PRD 31](../prd/31_implementation_folder_guidelines.md) section "Cross-Linking Requirements"** — Cross-references and edge linking expectations for Tier 1 artifacts.

**Orphans** (no inbound PRD pointer found) must be **linked**, **merged**, **archived under `docs/archive/`** with justification, or **deleted** only when superseded and recorded per **[CHANGELOG_BUFFER_ARCHITECTURE.md](../doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md)** (buffer entries under **`changelog-pending/`** with justification; reviewer approval where policy requires).

### Orphan severity levels

| Severity | Definition | Action required |
|----------|------------|-----------------|
| **CRITICAL** | Constitutional doctrine file (`*_constitution*`, core `*_doctrine*` tied to PRD 00 / install path) with no PRD link | Immediate remediation |
| **HIGH** | Normative reference or specification used by runtime or install | Remediate before the next tagged release that touches dependents |
| **MEDIUM** | Guide, how-to, or explanatory doctrine | Remediate within two release cycles or link from an index PRD |
| **LOW** | Example, template, or historical note (or path listed under **Exclusions** below) | May remain orphaned with justification and optional **`EXEMPTIONS.md`** row |

Until each file is classified, treat reported orphans as **MEDIUM** by default.

## Audit method (repeatable)

1. Enumerate doctrine Markdown: `docs/doctrine/**/*.md` (exclude generated vendor trees if any).
2. Apply **Exclusions** below; do not flag exempt paths as orphans.
3. For each remaining basename, search PRD corpus: `rg -l "FILENAME|stem" docs/prd` (manual review for renamed stems).
4. Record **linked** / **orphan** / **needs split** in a table below each audit run.

### Exclusions

The following paths are **exempt** from the default linkage requirement (normative doctrine still may be linked for discoverability):

- **`docs/doctrine/templates/**/*.md`** — template examples, not normative (path reserved; may be absent).
- **`docs/doctrine/archive/**/*.md`** — historical, read-only (path reserved; may be absent).
- **`docs/doctrine/EXEMPTIONS.md`** — when present, machine-readable / human registry of additional exemptions (self-describing; not required until exemptions are centralized).

To add a new exemption, extend this subsection with **justification** and **LILITH** (Actor 2) approval (or WOLFIE-delegated reviewer recorded in channel artifact).

### How to verify a doctrine file is linked

```bash
# Example: search PRD directory for references to a doctrine file
rg -l "TOON_ORDERING_SPEC.md" docs/prd/
```

Expected (illustrative): hits can include **`PRD_INDEX.md`**, **`16_lupopedia_headers.md`**, **`38_memory_unification.md`**, **`51_memory_graph_as_source_of_truth.md`**, depending on corpus state. If **no** PRD path references the basename or an accepted alias, treat as **orphan** (subject to **Exclusions** and **severity**).

### Automation

A script **`scripts/audit_doctrine_orphans.py`** SHOULD:

1. Scan **`docs/doctrine/**/*.md`** minus **Exclusions**.
2. Search the PRD corpus (and optionally **`PRD_INDEX.md`**) for each basename or stem.
3. Emit a CSV (or Markdown table) of orphans with suggested **severity**.
4. Support CI mode: **fail** the job if any **CRITICAL** orphan remains.

Until that script ships, manual runs of the **Audit method** steps above are required.

## Status (2026-04-18)

| Scope | Result |
|-------|--------|
| **Full automated zero-orphan proof** | **Not completed** in this pass (high file count; risk of false negatives on indirect references). |
| **PRD_INDEX integration** | Generator body now links this audit and **[PRD_GAPS.md](../doctrine/PRD_GAPS.md)**. |
| **Next step** | **Cadence:** weekly audit (Mondays). **Owner:** LILITH (Actor 2) or WOLFIE-delegated reviewer. **Automation:** add CI job invoking **`scripts/audit_doctrine_orphans.py`** once implemented; until then run the manual method before **PRD 4.2.0** release packaging. Open remediation rows for true orphans. |

## Examples already linked from PRDs

- **`TOON_ORDERING_SPEC.md`** — cited from **PRD_INDEX** cross-cutting block and **PRD 16 / 38 / 51** (see index and those PRDs). Verify with **`rg -l "TOON_ORDERING_SPEC.md" docs/prd/`** as above.
