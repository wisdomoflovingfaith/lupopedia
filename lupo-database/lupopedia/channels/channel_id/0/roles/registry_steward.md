# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\channels\0\roles\registry_steward.md"
  file_hash: "8bcd4d94c2ac0982b60d1a79fed80938a00308fac23740f2da223aaf08390b21"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\roles\registry_steward.md"
  file_hash: "4945e75d38678448bdcb709b8737246b111f0363f6150acb7083f40f265bb140"
  file_path_from_root: "channels\0\roles\registry_steward.md"
  file_hash: "d63632e76e2e8a7885bfd30566edcd47d8f62094b1acbe07c46e6b82f4778f9f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for registry_steward.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "roles", "registry_stewardmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
role_id: registry_steward
channel_id: 0
authority_level: elevated
granted_by: 10000
derived_from:
  - "registry_management"
  - "id_allocation"
permissions:
  - allocate_ids
  - lock_registry_entries
  - validate_references
  - audit_registry_usage
  - document_allocations
assigned_to:
  - 1
  - 10000
created_utc: "2026-02-25T09:20:00Z"
updated_utc: "2026-02-25T09:20:00Z"
---

# Role: Registry Steward

## Authority

**Level:** Elevated  
**Scope:** Registry management and ID allocation  
**Granted By:** Captain (10000)

## Description

Registry Stewards are responsible for managing the actor, channel, and agent registries. They allocate IDs, lock entries, validate references, and ensure registry integrity across the system.

## Permissions

### ID Allocation
- Allocate actor IDs
- Allocate channel IDs
- Allocate agent IDs
- Reserve ID ranges
- Mark IDs as open

### Registry Management
- Lock registry entries
- Unlock entries (with justification)
- Update registry metadata
- Archive deprecated entries

### Validation
- Validate all ID references
- Check for invalid references
- Audit registry usage
- Report violations

### Documentation
- Document all allocations
- Maintain allocation log
- Track ID usage
- Update registry files

## Assigned Actors

- **1** - Captain WOLFIE (AI Agent)
- **10000** - Captain (Human)

## Responsibilities

1. **ID Allocation**
   - Allocate new actor IDs
   - Allocate new channel IDs
   - Reserve ID ranges for future use
   - Document all allocations

2. **Registry Integrity**
   - Validate all references
   - Check for conflicts
   - Prevent duplicate allocations
   - Maintain referential integrity

3. **Auditing**
   - Scan codebase for hardcoded IDs
   - Scan content for invalid references
   - Generate audit reports
   - Recommend fixes

4. **Documentation**
   - Update `actors/registry.json`
   - Update `channels/registry.json`
   - Maintain allocation log
   - Document policy changes

## Constraints

- Must validate all allocations
- Must prevent conflicts
- Must document all changes
- Must maintain audit trail

## Success Criteria

- All IDs allocated correctly
- No duplicate allocations
- All references valid
- All changes documented

## Escalation

Registry Stewards report to System Administrators. Allocation conflicts must be escalated immediately.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "actors/registry.json",
    "channels/registry.json",
    "database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "database/migrations/seed_registry_open_4.0.45.sql"
  ],
  "implements": "registry_authority_model",
  "depends_on": "registry_seeding",
  "role_category": "governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->