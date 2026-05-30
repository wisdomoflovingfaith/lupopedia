# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/channels/channel_id/0/tasks/active/registry_lock.md"
  file_hash: "c8bf87723cb288893f9f07ef175b8e16465bda09f359f190836c770e894e29b9"
  last_updated_utc: "20260228155738"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "channels\0\tasks\active\registry_lock.md"
  file_hash: "52554dc83cc4c1b92c3145304bd827cb76b32082cdd3572e1477ab1b0e0bd590"
  file_path_from_root: "channels\0\tasks\active\registry_lock.md"
  file_hash: "f41226fe4f79f6a0bb03dc598331f53e20c1acc31ae54b60ab2b576d689e1d17"
  last_updated_utc: "20260228"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for registry_lock.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["channels", "0", "tasks", "active", "registry_lockmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.73"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: CH0-20260225-003
channel_id: 0
owner_actor_id: 10000
assigned_to:
  - 1
  - 10000
status: active
priority: high
created_utc: "2026-02-25T08:40:00Z"
depends_on:
  - CH0-20260225-001
blocks: []
task_type: governance
estimated_duration: "1 hour"
---

# Task: Registry Lock and Validation

## Objective

Lock the actor and channel registries to prevent unauthorized ID allocation and ensure all references use canonical IDs.

## Context

The registry seeding SQL files define reserved IDs for:
- **Actors:** 0-5 (system agents), 1000-1005 (IDE agents), 10000 (root captain)
- **Channels:** 0 (system), 1 (admin), 42 (dev), 51 (reserved), 666 (quarantine)
- **Agents:** 0-5 (system agents)

All other IDs are marked as "open" in the registry tables. We need to ensure no code or content references non-existent IDs.

## Steps

1. **Verify registry seeding**
   - Check `lupo_registry_actors` table
   - Check `lupo_registry_channels` table
   - Check `lupo_registry_agents` table
   - Verify reserved IDs match SQL files

2. **Scan codebase for hardcoded IDs**
   - Search for actor ID references
   - Search for channel ID references
   - Verify all references use registry lookups

3. **Scan content for invalid IDs**
   - Check all broadcasts for actor IDs
   - Check all directives for actor IDs
   - Check all tasks for actor IDs
   - Verify all IDs exist in registry

4. **Create registry validation script**
   - Script: `scripts/validate_registry_references.php`
   - Check all content files
   - Report invalid references

5. **Document registry policy**
   - Create: `docs/doctrine/registry_allocation_policy.md`
   - Define ID ranges
   - Define allocation process
   - Define validation requirements

## Success Criteria

- ✅ All reserved IDs seeded in database
- ✅ All content references valid IDs
- ✅ No hardcoded IDs in codebase
- ✅ Validation script created
- ✅ Registry policy documented

## Risks

- **Invalid references:** Content may reference non-existent actors
- **Legacy IDs:** Old content may use deprecated IDs (e.g., 2035 for Antigravity)
- **Hardcoded IDs:** Code may bypass registry lookups

## Notes

This task ensures referential integrity across the entire system. Critical for multi-agent coordination.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "database/migrations/seed_registry_open_4.0.45.sql",
    "actors/registry.json",
    "channels/registry.json"
  ],
  "implements": "registry_integrity_enforcement",
  "depends_on": "CH0-20260225-001",
  "blocks": [],
  "task_category": "governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
