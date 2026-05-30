# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/registry_lock

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
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/registry_lock.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:31Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/registry_lock.md"
  file_hash: "8f789fc9ee1e97c9da590bdb950d80dba1b0b7743490bf8ca516b400569de55f"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "threads"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/registry_lock.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/registry_lock"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\tasks\active\registry_lock.md"
  file_hash: "52554dc83cc4c1b92c3145304bd827cb76b32082cdd3572e1477ab1b0e0bd590"
  file_path_from_root: "lupo-channels\0\tasks\active\registry_lock.md"
  file_hash: "f41226fe4f79f6a0bb03dc598331f53e20c1acc31ae54b60ab2b576d689e1d17"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1003
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for registry_lock.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "tasks", "active", "registry_lockmd"]
  lupo_agent: "cursor"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: CH0-20260225-003
channel_id: 42
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
   - Script: `lupo-scripts/validate_registry_references.php`
   - Check all content files
   - Report invalid references

5. **Document registry policy**
   - Create: `lupo-docs/doctrine/registry_allocation_policy.md`
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
    "lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "lupo-database/migrations/seed_registry_open_4.0.45.sql",
    "lupo-actors/registry.json",
    "lupo-channels/registry.json"
  ],
  "implements": "registry_integrity_enforcement",
  "depends_on": "CH0-20260225-001",
  "blocks": [],
  "task_category": "governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
