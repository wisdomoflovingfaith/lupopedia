---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-agents/anubis/doctrine/LEGACY_FILE_GUARD.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-agents/anubis/doctrine/LEGACY_FILE_GUARD.md"
  last_modified_utc: "20260403113047"
  when_updated: "20260403113047"
  federation_node_id: 0
  channel_id: 42
  thread_id: "doctrine-header-repair"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "LEGACY FILE GUARD"
  status: active
  tags:
    - "doctrine"
    - "header_repair"
lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"
---

# file: LEGACY_FILE_GUARD — delegation: cursor:root

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
      objective: "ANUBIS Legacy File Guard"
    where:
      repo_paths: ["lupo-docs\doctrine\ANUBIS\LEGACY_FILE_GUARD.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:33Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-agents/anubis/doctrine/LEGACY_FILE_GUARD.md"
  file_hash: "5e339a3b0fbd49aa25152390ae667e7302fc6a589f7f031a819329dfcb1c4d52"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "ANUBIS Legacy File Guard"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-docs", "doctrine", "anubis", "legacy_file_guardmd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-docs\doctrine\ANUBIS\LEGACY_FILE_GUARD.md", "http://www.lupopedia.com/LEGACY_FILE_GUARD"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# ANUBIS Legacy File Guard

## Governance Notice

**Effective Date**: 2026-02-28  
**Version**: 4.0.52  
**Authority**: Windsurf (1002)  

## Canonical Authority

The **only authoritative ANUBIS doctrine file** is:

```
lupo-agents/anubis/doctrine/ANUBIS_CANONICAL.md
```

## Legacy File Restrictions

Any new file matching `ANUBIS_*.md` (excluding `ANUBIS_CANONICAL.md`) requires:

### Mandatory Requirements
1. **Explicit Version Bump**: Must target v4.1.0 or higher
2. **Governance Approval**: Requires Channel 42 consensus
3. **FLARE Header Compliance**: Must include complete FLARE structure
4. **Actor ID Verification**: Must reference ANUBIS actor_id 19

### Prohibited Files
The following file patterns are **PROHIBITED** from creation:
- `ANUBIS_OVERVIEW.md` (use canonical)
- `ANUBIS_PROGRAM_SPEC.md` (use canonical)
- `ANUBIS_ORPHAN_RULES.md` (use canonical)
- `ANUBIS_IMPLEMENTATION_SUMMARY.md` (use canonical)
- Any duplicate or variant naming

## Enforcement Mechanism

### Automated Guards
- **CI Validation**: `bin/guard_anubis_structure.php` will fail builds on violations
- **Reference Audit**: `lupo-tools/anubis_reference_audit.txt` tracks legacy references
- **Hash Verification**: `lupo-agents/anubis/doctrine/ANUBIS_CANONICAL.lock` prevents unauthorized changes

### Manual Review Process
1. **Proposal**: Submit change proposal to Channel 42
2. **Review**: Governance review by Captain Wolfie (10000)
3. **Approval**: Explicit approval required before implementation
4. **Implementation**: Only after approval and version bump

## Consequences of Violation

### Build Failures
- CI/CD pipeline will reject commits with prohibited files
- Automated guards will fail with specific violation details
- Version control hooks will prevent merges

### Governance Actions
- Violations logged in governance registry
- Repeated violations may result in access restrictions
- Emergency rollback procedures may be invoked

## Historical Context

### Previous Consolidation
- **Date**: 2026-02-28
- **Files Archived**: 6 original files moved to `lupo-docs/archive/ANUBIS/pre_4.0.52/`
- **Canonical Created**: `lupo-agents/anubis/doctrine/ANUBIS_CANONICAL.md`
- **Lead Agent**: Windsurf (1002)

### Archive Access
- **Historical Reference**: Archived files available for reference
- **Current Authority**: Only canonical file should be used
- **Version Control**: Archive preserves historical context

## Contact and Escalation

### Governance Questions
- **Channel 42**: Primary governance venue
- **Captain Wolfie**: Final authority (actor_id 10000)
- **Emergency**: Contact via Channel 42 broadcast

### Technical Issues
- **Guard Failures**: Check `bin/guard_anubis_structure.php` output
- **Reference Problems**: Review `lupo-tools/anubis_reference_audit.txt`
- **Hash Mismatches**: Verify against `lupo-agents/anubis/doctrine/ANUBIS_CANONICAL.lock`

---

**Guard Established**: 2026-02-28  
**Guard Maintainer**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ ACTIVE
