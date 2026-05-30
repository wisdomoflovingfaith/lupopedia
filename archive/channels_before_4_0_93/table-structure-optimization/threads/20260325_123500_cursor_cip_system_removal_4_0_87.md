---
lupopedia.headers:
  file_path_from_root: channels/table-structure-optimization/threads/20260325_123500_cursor_cip_system_removal_4_0_87.md
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  artifact_type: completion
  artifact_kind: cip_removal
  purpose: Complete removal of CIP system surfaces from 4.0.87 active runtime and install schema
  when_updated: '20260325123121'
  web_path: http://www.lupopedia.com/channels/table-structure-optimization/threads/20260325_123500_cursor_cip_system_removal_4_0_87.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260325123121'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
  next_action:
  - run install SQL smoke check on fresh database
  - refresh generated validation issue report
---

# CIP System Removal - 4.0.87

## Scope completed

CIP chain was removed from active runtime and schema surfaces after ATHENA review confirmed orphaned topology and no canonical event anchor.

## Completed changes

1. Removed CIP install DDL blocks from canonical installer:
- lupo_calibration_impacts
- lupo_emotional_geometry_calibrations
- lupo_multi_agent_critique_sync

2. Removed same CIP DDL blocks from backup installer snapshot:
- install_new_lupopedia_backup.sql

3. Removed CIP runtime query surface from tooling:
- deleted select_one_from_lupo_calibration_impacts in scripts/wolfie_orms.py

4. Removed active CIP docs and generated artifacts:
- active table docs for calibration/cip/emotional_geometry/multi_agent_critique
- architecture docs: CIP analytics/doctrine/emotional geometry interoperability
- cip json/csv generated surfaces

5. Removed CIP TOON and JSON surfaces previously linked to active schema:
- lupo_cip_analytics
- lupo_cip_propagation_tracking
- lupo_cip_trends
- lupo_calibration_impacts
- lupo_emotional_geometry_calibrations
- lupo_multi_agent_critique_sync

## Validation evidence

- Installer scan now returns zero CIP CREATE TABLE blocks in install_new_lupopedia.sql.
- Installer backup scan now returns zero CIP CREATE TABLE blocks in install_new_lupopedia_backup.sql.
- Table index planning entry for lupo_calibration_impacts removed.
- Workspace scan for CIP runtime symbols (excluding archived/backup surfaces) returned no active hits.

## Notes

- emotional_geometry_baseline in lupo_actor_collections was retained intentionally; it is actor profile metadata and not part of CIP tables.
- flare_validate_issues.json remains a generated report artifact and can be refreshed in a later validation pass.
