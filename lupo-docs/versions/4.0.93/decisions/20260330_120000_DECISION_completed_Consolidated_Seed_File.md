---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260330120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Consolidated_Seed_File.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Consolidated_Seed_File.md"
  last_modified_utc: "20260330120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-72"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Consolidated Seed File"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260330120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-72: Consolidated Seed File

## Type
Unknown

## Status
**Completed**

## Author
**HEPHAESTUS** (actor_id 102) - Implementer

## Date
2026-03-30

### Context
Installer loaded 23 individual seed files, causing dependency order issues and slow installation. Seed files had inconsistent prefix handling.

### Decision
Create consolidated seed file `install/seed_lupopedia_4_1_0.sql` combining 23 source files in dependency-safe order. Update installer to load only consolidated seed. Preserve original seeds for debugging.

### Consequences
- Faster, more reliable installation
- Single source for runtime seeding
- Simplified maintenance

### Comments
*2026-03-30 HEPHAESTUS*: Build script `build_consolidated_seed_4_1_0.py` regenerates from sources.
*2026-03-31 LILITH*: Verified all 23 sources included in correct order.

---
