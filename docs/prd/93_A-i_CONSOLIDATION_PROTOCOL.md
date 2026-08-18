---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/93_A-i_CONSOLIDATION_PROTOCOL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/93_A-i_CONSOLIDATION_PROTOCOL.md
  status: active
  when_updated: '20260817142700'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/08/93_a_consolidation_protocol.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd-93-consolidation-protocol
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 93_A_00_A_84_A_16_A_61_A_80_A
  title: 'PRD 93: Consolidation Protocol'
  summary: 'Universal procedure for merging, compacting, and reconciling Lupopedia artifacts: PRDs, clusters, doctrine, subsystems, schemas, and lineage records. Freeze windows. Merge or deprecate, never delete. Cluster Map, Consolidation Report, audit trail. Governs how consolidation happens; does not perform it. Not PRD 91. Not PRD 61. No install SQL. Out of scope: runtime federation, color identity display, live help operations, widget rendering.'
---
# PRD 93: Consolidation Protocol

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Number assignment (validated against `docs/prd/`)

Copilot proposed **PRD 91**. That number is already assigned.

| Proposed | Actual file | Why rejected |
|----------|-------------|--------------|
| 91 | `91_A-i_INTENT_ENCODING.md` | Intent Encoding. PRD numbers MUST NOT be reused (PRD 84). |
| 91 as `91_CONSOLIDATION_PROTOCOL.md` | would collide with 91_A | Filename MUST follow `NN_L-i_TITLE.md`. |

**This PRD is group 93 (block 90-97: Governance, Audit, and Compliance).** Next free group after 90, 91, 92. Groups 94-96 remain unused. That gap is allowed (PRD 84 Anti-Normalization).

**Not 61.** `61_A-i_DOCTRINE_CONSOLIDATION_SHORTHAND_COMPILER.md` is the shorthand/TOON compiler and invariant checklist. PRD 93 is the freeze/merge/reconcile procedure. They MUST remain separate. This PRD does not absorb PRD 61.

**Not 82.** Group 82 is HERMES.

This PRD adds **no install SQL**. `install_new_lupopedia.sql` remains schema authority (PRD 80). Completing this file does not start a consolidation freeze window. Captain must still declare one.

PRD 93 governs **how** consolidation happens. It does not perform it. Future merges MUST reference this PRD as the controlling protocol.

## 1. Purpose

Establish the universal procedure for merging, compacting, and reconciling Lupopedia artifacts.

Consolidation applies to anything that can drift: PRD files, clusters, doctrine fragments, subsystems, specifications, conceptual schemas, and lineage records. It is not a PRD-only mop-up.

Consolidation exists to keep:

- structural integrity
- lineage preservation
- doctrinal consistency across clusters

It is required because consolidation is not trivial. Informal merges corrupt numbers, orphan links, and invent a second grammar.

## 2. Scope

Applies to:

- PRD files and clusters
- Doctrine and subsystem merges
- Specification compaction
- Schema alignment (spec-level; not a license to rewrite install SQL from this PRD)
- Artifact lineage reconciliation (records and edges; not widget UI)
- Installer and federation **harmonization** (docs and contracts; not live node traffic)
- Any Lupopedia entity requiring merge or deprecation

Does NOT apply to:

- Runtime federation (live node sync, handshake packets, node traffic)
- Color identity **display** (PRD 90 POWERED_BY and ColorLex Appendix 20 skins)
- Live help operations
- Widget rendering (The Eye, artifact lineage player, navbar chrome)

PRD 90 remains Color Identity authority. This protocol may later compact the PRD 90 **file** under a declared freeze. It MUST NOT change HEX6 rules, guess colors, or alter the ColorLex display skin as a side effect of a merge.

## 3. Core principles

1. **Doctrine First** -- Senior doctrine (PRD 00) overrides conflicts. Read `00_A-i_FORBIDDEN_AND_WHY.md` first. `00_C-i` wins for implementation and installer detail. `00_F-i` is the unified narrative.
2. **No Deletion** -- Artifacts may be merged or deprecated, never erased. Paths stay. Numbers stay. History stays.
3. **Cluster Awareness** -- Consolidation occurs by cluster, not by file count. `prd_cluster` shorthand (PRD 84 / PRD 16_A-iii) is the unit of work.
4. **Lineage Preservation** -- Parent/child relationships MUST survive merges. A surviving file inherits the absorbed file's inbound and outbound edges. Deprecated files keep their identity as historical parents.
5. **Freeze Discipline** -- Consolidation only during a declared freeze window. Agents MUST NOT start one.
6. **Transparency** -- Every merge is logged in a Consolidation Report.
7. **Integrity** -- No orphaned references, no broken links, no duplicate IDs, no reused PRD numbers, no guessed HEX6.
8. **Auditability** -- Each merge MUST produce a traceable diff and a lineage map.

## 4. Requirements

- Captain (or authorized ALII) declares the freeze window before any merge.
- No new PRD groups may be created during a consolidation freeze. This protocol already exists.
- New product ideas during idea freeze go to `wolfie_toys_for_later/`. They are not PRDs.
- Number collisions MUST be resolved by PRD 84. Keep the occupying file. Reject the colliding proposal.
- Cluster strings MUST use shorthand selectors only (`NN_X` tokens). No alias mapping.
- Deprecated artifacts MUST retain their file path and a DEPRECATED header.
- A freeze window lifts only after verification (section 5.8).
- Schema alignment MUST NOT invent tables, AUTO_INCREMENT, foreign keys, or triggers. Planning docs may be compacted; live DDL waits for an authorized install-SQL change under PRD 80.

## 5. Procedure

1. Declare a freeze window.
2. Identify clusters and their dependencies.
3. Detect duplicates, overlaps, and drift.
4. Merge within each cluster according to hierarchy.
5. Resolve conflicts using PRD 00 doctrine.
6. Update cross-references and lineage tables.
7. Generate a Consolidation Report.
8. Lift freeze window only after verification.

### 5.1 Declare a freeze window

Captain (or authorized ALII) records:

- start timestamp (`YYYYMMDDHHIISS`)
- scope (groups, clusters, doctrine trees, schema-planning docs, lineage records)
- reason
- whether idea freeze is also in effect (it SHOULD be)

Until that declaration exists, this PRD is idle. Completing the spec is not a freeze.

### 5.2 Identify clusters and dependencies

- Expand every in-scope `prd_cluster` deterministically (PRD 84).
- List inbound and outbound references (PRDs, doctrine files, installer docs, conceptual schema notes, lineage edges).
- Do not treat numeric order as importance. Left-to-right cluster position is read order (PRD 16_A-iii).

### 5.3 Detect duplicates, overlaps, and drift

Record:

- same group, different letter (siblings; usually keep both)
- same topic, different groups (candidate merge)
- Copilot/agent number collisions (keep the real file)
- superseded paths already marked (do not delete)
- duplicate conceptual tables or field names across planning PRDs
- lineage edges that point at vanished titles or guessed IDs
- display-grammar drift (route to PRD 90; do not "fix" color display inside a merge)

### 5.4 Merge according to cluster hierarchy

- Surviving artifact keeps its number and path.
- Absorbed rules move into the surviving artifact.
- The absorbed artifact is deprecated in place.
- Parent/child edges retarget to the survivor and keep a historical edge to the deprecated path.
- Do not merge KEY grammar into Color Identity, or Color Identity into KEY grammar.

### 5.5 Resolve conflicts using PRD 00

When two surviving texts disagree:

1. PRD 00 (forbidden wall / installer detail as in section 3.1)
2. The PRD that already owns that surface (16_C for KEY, 90 for color, 80 for schema, 84 for numbers)
3. Captain / ALII

HEX5 (multi-agent conflict) means stop. Preserve every proposal. Do not average.

### 5.6 Update cross-references and lineage tables

Every inbound link, index row, `prd_cluster` selector, and lineage parent/child pointer that named absorbed material MUST:

- point at the surviving artifact for implementation
- still name the deprecated path as historical

No orphaned references. No broken links. No silent retitle that drops the old path.

Lineage tables here means the **documented** parent/child map and any existing lineage records. This step does not render The Eye or the artifact widget.

### 5.7 Generate a Consolidation Report

Required sections:

- freeze window timestamps
- artifacts merged (PRDs, doctrine, specs, schema-planning docs, lineage records)
- artifacts deprecated (path retained)
- conflicts resolved by PRD 00 (and which 00 file won)
- number assignments refused (with the occupying file)
- Cluster Map
- lineage map (parent/child before and after)
- traceable diff list (paths changed)
- remaining gaps (intentional; PRD 84)
- verification checklist (section 5.8)

The report is a timestamped document. This PRD does not add DDL for it.

### 5.8 Lift freeze window only after verification

Verification MUST confirm:

- no deleted paths
- no reused PRD numbers
- no orphaned links
- no duplicate IDs in the surviving map
- KEY grammar unchanged unless 16_C was the in-scope survivor
- HEX6 not guessed
- widgets and live help not rewritten as a merge side effect
- Cluster Map matches the files on disk
- Consolidation Report is complete

Only Captain lifts the freeze. Parked toys in `wolfie_toys_for_later/` are not automatically promoted.

## 6. Deprecation header

Deprecated artifacts keep their path. After YAML, the body MUST open with:

```text
# DEPRECATED

Superseded by: <surviving path from lupopedia root>
Deprecated during: PRD 93 freeze window YYYYMMDDHHIISS
Do not implement from this file. Historical text is retained.
Lineage: this path remains a historical parent/child identity.
```

The original filename, group number, and letter remain. PRD 84 forbids reuse of that number for a new topic.

## 7. Required outputs

A completed freeze window produces:

| Output | What it is |
|--------|------------|
| Compact corpus | Doctrine-aligned surviving artifacts |
| Cluster Map | Group, letter, file, status, surviving authority |
| Consolidation Report | Timestamped log of every merge |
| Audit trail | Traceable diff list plus lineage map |

### 7.1 Cluster Map

Example row (this file):

| Group | Letter | File | Status | Surviving authority |
|-------|--------|------|--------|---------------------|
| 93 | A | `93_A-i_CONSOLIDATION_PROTOCOL.md` | active | this PRD |

The map is a document, not a database table.

### 7.2 Lineage map

For each merge:

```text
deprecated_path -> surviving_path
parents_kept: <list>
children_retargeted: <list>
historical_edge: deprecated_path remains addressable
```

## 8. Schema and ID integrity

- Schema **alignment** means one conceptual model wins; duplicate planning prose is deprecated in place.
- Live database changes are out of this PRD. PRD 80 and `install_new_lupopedia.sql` own DDL.
- IDs stay 18-digit IdGenerator values where rows exist. No AUTO_INCREMENT. No invented IDs to "fill gaps."
- Duplicate ID in a surviving map is a verification failure. Stop. HEX5. Preserve proposals.

## 9. Installer and federation harmonization

In scope: making installer docs, node-registration docs, and federation **contracts** say the same thing (nested `/lupopedia/` vs vhost, Node 1 category vs registered >= 2, ColorLex as node 3).

Out of scope: running federation, changing live node IDs, rewriting handshake packets, or operating live help.

## 10. Idea freeze vs consolidation freeze

| Mode | What it stops | Where new ideas go |
|------|---------------|--------------------|
| Consolidation freeze (this PRD) | Merges and deprecations of existing artifacts | No new PRD groups |
| Idea freeze | New product PRDs and new grammar proposals | `wolfie_toys_for_later/` |

An idea freeze MAY exist without a consolidation freeze. A consolidation freeze SHOULD include an idea freeze.

Idea freeze after 2026-08-17: all new ideas go to `wolfie_toys_for_later/`. Existing PRDs are the work. Completing PRD 93 is existing-file work, not a new group.

## 11. What this is not

- Not a merge run. Completing this spec does not compact the corpus.
- Not PRD 61 (shorthand compiler).
- Not PRD 91 (Intent Encoding).
- Not runtime memory consolidation (PRD 37 KAIROS).
- Not Portable Semantic Collections import (PRD 73).
- Not ColorLex display (PRD 90 Appendix 20).
- Not The Eye (PRD 28) and not the artifact lineage widget (PRD 92).
- Not a license to delete files, reuse numbers, guess HEX6, or write install SQL.

## 12. Implementation posture

- PHP 7.4+ if tooling is later added. No install SQL from this PRD.
- ASCII in this PRD.
- No foreign keys, no triggers, no guessed columns.
- Future tooling MAY emit a Consolidation Report file. It MUST NOT auto-delete, auto-renumber, or auto-guess HEX6.

## 13. Cross-references

- PRD 00 -- senior doctrine for conflicts (`00_A-i`, `00_C-i`, `00_F-i`)
- PRD 16_A-iii -- cluster positional architecture
- PRD 16_C -- LUP KEY grammar (not modified by a generic merge)
- PRD 61 -- shorthand compiler (related, not this protocol)
- PRD 80 -- database design doctrine; schema authority
- PRD 84 -- PRD number allocation; no reuse; gaps allowed
- PRD 90 -- Color Identity; display out of scope
- PRD 91 -- Intent Encoding (occupies 91)
- PRD 92 -- Artifact Lineage Widget (rendering out of scope; lineage records in scope)
- `docs/prd/prd_index.md` -- index must reflect assignments
- `wolfie_toys_for_later/` -- parked ideas after 2026-08-17
