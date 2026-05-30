---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260330120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Consolidated_Seed_File.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Consolidated_Seed_File.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-72"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
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
