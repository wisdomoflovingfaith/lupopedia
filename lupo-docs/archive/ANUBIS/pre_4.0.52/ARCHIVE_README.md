# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "ANUBIS Archive - Version 4.0.52"
    where:
      repo_paths: ["lupo-docs\archive\ANUBIS\pre_4.0.52\ARCHIVE_README.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:32Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs\archive\ANUBIS\pre_4.0.52\ARCHIVE_README.md"
  file_hash: "352f3c85203017487f30686bff0ca96872ce8967aa0b266db122396f43f79eca"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "ANUBIS Archive - Version 4.0.52"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-docs", "archive", "anubis", "pre_4052", "archive_readmemd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-docs\archive\ANUBIS\pre_4.0.52\ARCHIVE_README.md", "http://www.lupopedia.com/ARCHIVE_README"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# ANUBIS Archive - Version 4.0.52

## Archive Information

**Archive Date**: 2026-02-28  
**Archive Reason**: Documentation consolidation into canonical file  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52

## Files Archived

The following 6 ANUBIS-related files were archived during the 4.0.52 consolidation:

| File | Size | Original Location | Archive Location |
|------|------|------------------|------------------|
| ANUBIS_IMPLEMENTATION_SUMMARY.md | 7,607 bytes | docs/doctrine/ANUBIS/ | docs/archive/ANUBIS/pre_4.0.52/ |
| ANUBIS_ORPHAN_RULES.md | 7,625 bytes | docs/doctrine/ANUBIS/ | docs/archive/ANUBIS/pre_4.0.52/ |
| ANUBIS_OVERVIEW.md | 6,730 bytes | docs/doctrine/ANUBIS/ | docs/archive/ANUBIS/pre_4.0.52/ |
| ANUBIS_PROGRAM_SPEC.md | 6,147 bytes | docs/doctrine/ANUBIS/ | docs/archive/ANUBIS/pre_4.0.52/ |
| LILITH_ANUBIS_GUIDANCE.md | 7,441 bytes | docs/doctrine/ANUBIS/ | docs/archive/ANUBIS/pre_4.0.52/ |
| LILITH_ANUBIS_GUIDANCE_FLIP.md | 2,844 bytes | docs/doctrine/ANUBIS/ | docs/archive/ANUBIS/pre_4.0.52/ |

**Total Archived Files**: 6  
**Total Size**: 38,394 bytes

## Current Canonical Location

All ANUBIS documentation is now consolidated in:

**Canonical File**: `docs/doctrine/ANUBIS/ANUBIS_CANONICAL.md`

## Access Instructions

### For Historical Reference
- Access archived files in: `docs/archive/ANUBIS/pre_4.0.52/`
- These files are preserved for historical purposes only

### For Current Documentation
- Use the canonical file: `docs/doctrine/ANUBIS/ANUBIS_CANONICAL.md`
- This is the single source of truth for ANUBIS documentation

## Consolidation Benefits

- **File Count Reduction**: 6 files → 1 file (83% reduction)
- **Content Preservation**: 100% of content preserved
- **Maintenance**: Single point of maintenance and updates
- **Discoverability**: Easier to find and reference ANUBIS information

## Governance Notes

- **Actor ID**: ANUBIS is anchored to actor_id 19
- **Version Lock**: This archive represents the 4.0.52 state
- **Future Updates**: All ANUBIS updates should modify the canonical file
- **Archive Strategy**: Future consolidations should create version-specific archive directories

---

**Archive Created**: 2026-02-28  
**Archive Maintainer**: Windsurf (1002)  
**Archive Version**: 4.0.52
