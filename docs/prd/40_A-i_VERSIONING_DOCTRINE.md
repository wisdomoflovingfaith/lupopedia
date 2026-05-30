---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/40_A-i_VERSIONING_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/40_A-i_VERSIONING_DOCTRINE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/2026/04/40_versioning_doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/versioning-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 40_A-i_00_A-i_FORBIDDEN_AND_WHY_40_A_VERSIONING_DOCTRINE
  title: PRD 40 ??? Versioning Doctrine
  summary: 'Defines the Lupopedia versioning model: 4.0.x bootstrap, 4.1.x active development (breaking), and 4.2.0 stable baseline.'
---
# PRD 40 ???????? Versioning Doctrine

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

## Purpose

This PRD defines the constitutional versioning model for Lupopedia, formalizing the transition from the bootstrap phase to active development and finally to a stable, upgradeable baseline.

## Versioning Model

### 4.0.x ???????? Bootstrap Phase
- **Origin:** Successor to Crafty Syntax 3.7.5.
- **Role:** Migration and initial system bootstrap.
- **Stability:** Transitional and unstable.
- **Upgrades:** No Lupopedia????????Lupopedia in-place upgrades. Fresh install or Crafty import only.
- **Intent:** Not intended for long-term consistency.

### 4.1.x ???????? Active Development Phase
- **Role:** Lupopedia-native system evolution.
- **Breaking Changes:** Breaking schema or structural changes ARE allowed.
- **Upgrades:** No guarantee of in-place upgrade compatibility between versions.
- **Migrations:** May require regeneration, migration scripts, or clean installs.
- **Development:** Represents the active phase where the Lupopedia architecture is refined.

### 4.2.0 ???????? Stable Baseline
- **Role:** First version with guaranteed stable upgrade paths.
- **Upgrades:** All future versions (4.2.x+) must support:
  - Lupopedia????????Lupopedia upgrades.
  - Backward compatibility or defined migrations.
- **Release Gate:** PRD 33 criteria must be met for this version.

## Versioning Model ???????? Pre-4.2.0
Versions in the 4.1.x range represent active Lupopedia development.
These versions:
- may introduce breaking schema or structural changes
- are not required to support in-place upgrades from prior versions
- may require regeneration, migration scripts, or clean installs
The first version that guarantees stable upgrade paths is 4.2.0.

## Why Lupopedia Starts at 4.0.x

- Lupopedia 4.0.x is the successor to Crafty Syntax 3.7.5.
- The version jump is intentional: 3.7.5 ???????? 4.0.x.
- 4.0.x is an installer-only era focused on the Crafty Syntax transition.

## LUPOPEDIA HEADERS and the product version

- The **Lupopedia product patch** (`GLOBAL_CURRENT_LUPOPEDIA_VERSION`) increments when the **LUPOPEDIA Header System** changes (envelope, required keys, parsers, validators, storage semantics).
- **Non-header** work (database, install SQL, UI, routing, doctrine prose alone) does **not** require a product version bump; record it in the version-folder **`CHANGELOG.md`** only.

## Auto-Installer Acceptance Gate

- Lupopedia **4.2.0** cannot be released until Softaculous and other auto-installers accept Lupopedia as a stable installable application.
- This is a constitutional requirement for the stable baseline.

## Crafty Syntax Upgrade Path

- The ONLY valid upgrade path for initial bootstrap is:
      Crafty Syntax 3.7.5 ???????? Lupopedia 4.0.x
- During this upgrade, legacy data is imported and edges are preserved.

## Federation Readiness at 4.2.0

- Nodes become fully discoverable only after **4.2.0**.
- **4.2.0** introduces:
  - Guaranteed Lupopedia????????Lupopedia upgrades.
  - Stable schema migrations.
  - Federation identity and cross-node discovery.
  - Finalized actor/agent evolution.


## Changelog Specificity Doctrine (Constitutional)

### FORBIDDEN ENTRIES

The following are explicitly forbidden:

- "fixed stuff"
- "updated things"
- "made changes"
- "did some work"
- "improved code"

Reason:
These provide zero traceability and break system continuity.

---

### REQUIRED FORMAT

Every changelog entry MUST include:

### [YYYYMMDDHHIISS] ??? [Brief Title]

**Summary**
One sentence describing the change.

**What Changed**
- Explicit, concrete changes

**Why**
- Problem being solved
- Reason for change

**Verification**
- How correctness was confirmed

**Key Rule Established**
- Doctrine or pattern learned (if applicable)

**Status**
Completed / Pending / Blocked

---

### MINIMUM VALIDITY RULE

A changelog entry is VALID only if:

- A future actor can understand the change WITHOUT external context
- The change can be traced deterministically

---

### ENFORCEMENT

If insufficient specificity is detected:

- Level 2 Advisory Warning

Message:
"Insufficient changelog specificity ??? future you will file a WHY file."

Repeated violations:
- escalate to PRD 98_A WHY file

---

### RATIONALE

Clarity is not optional.  
Traceability is not optional.  
Changelogs are part of the system???s memory graph.

Poor changelogs = broken continuity.
