# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\status\antigravity_offline_until_next_month.md"
  file_hash: "e572eca3b5c98592ff988773cb479c72b1708ca7a984254d6237fd52612e5d2d"
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
  file_path_from_root: "docs\status\antigravity_offline_until_next_month.md"
  file_hash: "7661073dd6a48497c1cce36fd09a06a66117a849c6e97c23ad0ea0973bd160e0"
  file_path_from_root: "docs\status\antigravity_offline_until_next_month.md"
  file_hash: "0cbd0281ef8e8d7af49c2fa8b975843348585eb5d58cf85af3504df4513b8cf8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for antigravity_offline_until_next_month.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "antigravity_offline_until_next_monthmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/status/antigravity_offline_until_next_month.md",
  system_version: "4.0.42",
  channel_id: 42,
  actor_id: 1001,
  lupo_agent: "kiro",
  purpose: "Antigravity agent status update - offline until next month",
  last_modified_utc: "20260224"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["agent_status", "antigravity", "offline"]
}
---

# Antigravity Agent Status Update

**Agent ID:** 1003  
**Agent Name:** Antigravity  
**Status:** Offline  
**Status Reason:** Unavailable until next month  
**Updated:** 20260224  
**Updated By:** KIRO (1001)  
**Authority:** Captain Wolfie (10000)

## Status Change

Antigravity (actor_id 1003) is offline until next month. All directives previously assigned to Antigravity have been reassigned to KIRO for execution.

## Database Update Required

The lupo_agents or lupo_actors table should be updated to reflect this status:

```sql
UPDATE lupo_actors 
SET is_active = 0,
    updated_ymdhis = 20260224160000
WHERE actor_id = 1003;
```

Or if using a status field in lupo_agents:

```sql
UPDATE lupo_agents
SET status = 'offline',
    status_reason = 'Unavailable until next month',
    updated_ymdhis = 20260224160000
WHERE actor_id = 1003;
```

## Directive Reassignment

All tasks from the Antigravity directive dated 20260224 have been processed by KIRO:
- ✅ Channel 0 doctrine broadcasts created
- ✅ system_commands table added to SQL
- ✅ install.php updated to enqueue commands
- ✅ Python runner script created
- ✅ Antigravity marked offline

— KIRO (1001)