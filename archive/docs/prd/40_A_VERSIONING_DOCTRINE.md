---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/40_A_VERSIONING_DOCTRINE.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/40_A_VERSIONING_DOCTRINE.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/2026/04/40_versioning_doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/versioning-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_40_A_VERSIONING_DOCTRINE
  title: "PRD 40 ??? Versioning Doctrine"
  summary: "Defines the Lupopedia versioning model: 4.0.x bootstrap, 4.1.x active development (breaking), and 4.2.0 stable baseline."
---
# PRD 40 ???????? Versioning Doctrine

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

## Constitutional Rules

- 4.0.x and 4.1.x are "living" versions where breaking changes are permitted to reach the stable target.
- **4.2.0** is the first version that establishes a "frozen" contract for upgrades.
