---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/03_A-i_GOALS_AND_SUCCESS_CRITERIA.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/03_A-i_GOALS_AND_SUCCESS_CRITERIA.md
  status: active
  when_updated: '20260817045219'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/03_goals_and_success_criteria.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/goals-and-success-criteria
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 03_A-i_00_A-i_FORBIDDEN_AND_WHY_03_A_GOALS_AND_SUCCESS_CRITERIA
  title: 'PRD 03: Goals and Success Criteria'
  summary: Defines project goals, success metrics, and acceptance criteria for Lupopedia releases, including name-alteration and PRD filename organization criteria (PRD number unassigned)
---
# PRD 03: Goals and Success Criteria

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

## 1. Project Goals

### 1.1 Primary Goals (4.x Series)

| Goal | Priority | Target Version | Measurement |
|------|----------|----------------|-------------|
| Constitutional compliance | P0 | 4.0.93 | All PRDs audited by LILITH |
| Multi-agent coordination | P0 | 4.0.95 | Channel handoff system operational |
| Documentation completeness | P1 | 4.1.0 | All PRDs have LUPOPEDIA_HEADERS |
| Softaculous certification | P0 | 4.2.0 | Pass certification gate (PRD 33) |
| Multi-language parity | P1 | 4.2.0 | Support all 14 Crafty Syntax locales |

### 1.2 Stretch Goals

| Goal | Priority | Target Version |
|------|----------|----------------|
| Memory graph unification | P2 | 4.3.0 |
| Federation node networking | P2 | 4.4.0 |

### 1.3 Name alteration and organization (PRD number unassigned)

**This is criteria only.** It is not a minted PRD. Candidate group **93** is vacant in `docs/prd/prd_index.md` and sits in PRD 84 block 90-97 (governance). Captain has not assigned the number. Do not create `docs/prd/93_*.md` until the number is assigned.

**Do not confuse with** PRD 00 section 9.21 **RULE 93.FILE_NAMING** (character and case rules for filenames). That rule number is not a PRD group. Candidate PRD 93 would be the rename-and-organization process; RULE 93.FILE_NAMING would remain the character law.

**Goal:** One governed process for changing a name -- PRD filenames first, then any other identity surface -- so organization stays lockstep and silent drift is a fail.

## 2. Success Criteria

### 2.1 Release Acceptance Criteria

For a release to be marked STABLE:

- [ ] All P0 goals for that version are COMPLETE
- [ ] Zero CRITICAL validation errors in PRD_INDEX.md
- [ ] LILITH audit passes for all modified PRDs
- [ ] Changelog reflects all changes
- [ ] Softaculous gate passes (for 4.2.0+)

### 2.2 Quality Metrics

| Metric | Target | Measurement Method |
|--------|--------|--------------------|
| PRD header compliance | 100% | `generate_prd_index.py --strict` |
| Doctrine linkage | >90% | `DOCTRINE_PRD_LINKAGE_AUDIT.md` |
| Encoding correctness | 100% | No `????????` or similar corruption |

## 3. Historical Context

This PRD supersedes the goals defined in Crafty Syntax 3.7.x and early Lupopedia 4.0.x releases (4.0.88 and earlier). For historical requirements, see `docs/archive/4.0.88_goals.md`.

## 4. Name alteration and organization -- goals and success criteria

Normative for planning. Binding as acceptance criteria when Captain assigns the PRD number and mints the file. Until then, agents MUST NOT treat this section as permission to rename the corpus.

### 4.1 Problem

A name in Lupopedia is not a label you can retitle in one file. Filename, header path, title signature, memory sidecar, implementation folder, inbound links, and (for non-PRD surfaces) table/column/actor/KEY/ColorName identities are one organization. Changing one without the rest is drift.

PRD 00 dream-rule: for timestamped artifact PKs, the filename is the canonical PK -- do not rename, edit, or repurpose that class of file. PRD filenames in `docs/prd/` are a different class: they are group documents (`NN_LETTER-roman_SLUG.md`). Changing them is allowed only as an explicit, complete operation -- not as bulk cleanup, not as `_v2` / `_updated` / `_final` variants (canonical-file doctrine).

PRD 84: PRD **numbers** stay stable once assigned. Renumbering is discouraged. Gaps are valid. This section does not authorize filling 93 because it is empty.

### 4.2 Goals (name alteration PRD, when minted)

| Goal | Priority | Measurement |
|------|----------|-------------|
| Single rename process for PRD files | P0 | One checklist covers filename, headers, title/H1 letter, index, memory, implementations, links |
| Single rename process for anything else | P0 | Same process family: identity vs display; blast radius listed before the first move |
| Organization lockstep | P0 | `prd_file_stem` == implementation folder == header `path_from_lupopedia_root` stem |
| No variant filenames | P0 | Zero new `_updated`, `_final`, `_rewrite`, `_copy`, `_v2` files for the same topic |
| No silent bulk rename | P0 | No repo-wide rename scripts unless Captain names the exact set |
| Number stability | P0 | PRD group numbers not reused or swapped (PRD 84) |
| Letter honesty | P0 | Title `PRD NN_LETTER` matches filename letter; no title wildcard `NN_Z` unless the file letter is Z |
| ASCII and char law | P0 | Docs remain `lowercase_with_underscores` plus the PRD `NN_LETTER-roman_` prefix; no spaces, hyphens in new filenames (PRD 00 RULE 93.FILE_NAMING) |
| Code files stay put | P0 | Existing `.php` / runtime names are not force-normalized (PRD 00 9.21.2) |
| Display vs identity | P1 | UI copy and human titles may change without renaming identity keys; identity keys never change "for readability" alone |

### 4.3 Success criteria -- a rename is COMPLETE only when

Use this as the acceptance gate. Incomplete = fail. Partial moves are not success.

**A. Before any path changes**

- [ ] The old name and the new name are both written (ASCII).
- [ ] The surface is classified: **identity** (must lockstep) or **display** (label only).
- [ ] If identity: blast-radius list exists (files, headers, sidecars, folders, SQL/PHP/docs links).
- [ ] If a PRD number change is proposed: refuse unless Captain explicitly assigns a new unused group; never reuse a retired number.
- [ ] If the file is a timestamped artifact PK (YYYYMMDDHHIISS class): refuse rename; that PK is frozen (PRD 00).
- [ ] Captain (or named orchestrator) approves the exact old->new pair.

**B. PRD filename change (`docs/prd/NN_LETTER-roman_SLUG.md`)**

- [ ] New stem matches grammar: two-digit group, underscore, letter, hyphen, roman (`i`/`ii`/...), underscore, slug, `.md`.
- [ ] File is moved once. No leftover file at the old path. No duplicate sibling with a variant suffix.
- [ ] Header `path_from_lupopedia_root` and `web_path` equal the new path.
- [ ] `title` and H1 use the same `NN_LETTER` as the filename.
- [ ] `prd_cluster` selectors still expand to real files (PRD 84 shorthand).
- [ ] `memory_toon` path updated or a documented keep-old sidecar with an outbound edge to the new stem (no orphan pointer).
- [ ] `docs/implementations/{prd_file_stem}/` renamed to the new stem, or an APPROVED decision records a deliberate exception (PRD 00 / PRD 31).
- [ ] Inbound links, `prd_index.md` (regenerated), channel threads, and doctrine citations that used the old basename are updated or listed as remaining with owners.
- [ ] `python scripts/generate_prd_index.py --strict` (or current strict index command) reports zero CRITICAL errors for that group.
- [ ] LILITH (or designated reviewer) signs the modified PRD headers as matching the new path.

**C. Changing the name of anything else**

Classify first, then apply the matching row. Do not use a PRD-file move as a model for code autoload paths.

| Surface | Identity or display | Involved in a complete rename | Success |
|---------|---------------------|-------------------------------|---------|
| PRD file / stem | Identity | Filename, headers, title letter, index, memory, implementations, citations | Section 4.3 B |
| Doctrine / guide `.md` | Identity | Path, header paths, memory_toon, inbound links | Old path gone; headers match; no variant duplicate |
| Implementation folder | Identity | Must equal PRD stem | Folder name == `prd_file_stem` |
| Database table / column | Identity | Install SQL, seed, PHP repositories, TOON regenerate after live schema, docs. No JSON schema hand-edit. No ALTER until 4.1.0 (fresh install) | Code and install SQL use only the new name; no compatibility alias |
| Actor name / slug | Identity | Registry, seed, headers `actor_id` (id does not change), display name may | `actor_id` unchanged; slug/name lockstep; no variant actor (`*_banned`, `*_v2`) |
| Channel key / thread key | Identity | Filesystem `lupo-channels/{node}/{channel_key}/{thread_key}/`, DB rows, index | Path and keys match; no mixed old/new keys in one thread |
| LUP KEY token | Identity | Eight-token grammar; HEX fill; header identity block | KEY not used as a place to stuff Color or renamed slugs; VERSION packed per contract |
| ColorName / GroupColor | Identity | `color_names` / `color_groups`, CSV backup, HEX6 mapping | New name is a new registry row or an explicit Captain-approved replace; HEX6 not guessed |
| UI string / title prose | Display | Locale catalogs (`lupo_t`), mockups | Identity keys unchanged; copy updated |
| PHP class / runtime file | Identity (exempt from forced case law) | Autoload, requires, docs | Rename only if broken or Captain-ordered; not for style |

**D. Organization success (the corpus after the change)**

- [ ] One canonical file per topic.
- [ ] PRD_INDEX lists the new basename and does not list the old one.
- [ ] No agent-facing instruction still tells readers to open the old path as current.
- [ ] Encoding: ASCII filenames; no `????????` in the changed paths.
- [ ] Changelog names the old->new pair (why, not a file dump).

### 4.4 Failure conditions (not success)

- Renaming "to match modern style" with no Captain old->new pair.
- Creating `*_v2.md` beside the original.
- Changing the H1 letter without changing the filename letter (or the reverse).
- Moving the Markdown and leaving `path_from_lupopedia_root` on the old path.
- Renaming a PRD and leaving `docs/implementations/` on the old stem.
- Renumbering PRDs to close gaps (PRD 84 anti-normalization).
- Treating RULE 93.FILE_NAMING as this PRD, or treating candidate 93 as assigned.
- Bulk filesystem rename of `.php` to snake_case.
- Compatibility shims (`old_name` plus `new_name`) -- zero-installations doctrine: rename cleanly.

### 4.5 Out of scope until the PRD is minted

- Creating the PRD file.
- Executing corpus renames.
- Changing LUP KEY grammar.
- Changing PRD 84 block assignments.

### 4.6 References for this envelope

- [PRD 00_C](00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md) -- RULE 93.FILE_NAMING; implementation folder = `prd_file_stem`
- [PRD 00_A](00_A-i_FORBIDDEN_AND_WHY.md) -- timestamped filename PK; do not rename that class
- [PRD 16_C](16_C-i_LUPOPEDIA_HEADERS.md) -- header path fields
- [PRD 31](31_A-i_IMPLEMENTATION_FOLDER_GUIDELINES.md) -- implementation mirrors
- [PRD 84](84_A-i_PRD_NUMBER_ALLOCATION_DOCTRINE.md) -- numbers stable; gaps valid; block 90-97
- [PRD 15_B](15_B-i_ACTOR_IDENTITY_AND_NAMING_REGISTRY.md) -- actor naming
- [Letter collision audit](audits/prd_letter_collision_warning.md) -- filename letter vs cluster grade

## 5. References

- [PRD 00](00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md) -- Constitutional foundation
- [PRD 33](33_A-i_SOFTACULOUS_CERTIFICATION_4_1_0_GATE.md) -- Release gate requirements
- [PRD 38](38_A-i_MEMORY_UNIFICATION.md) -- Memory graph goals
- Section 4 of this file -- name alteration criteria (PRD number unassigned)

