# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\channels\42\tasks\completed\ACTOR-PORT-001.md"
  file_hash: "b28baf5a01b05f8a2d70ae697f2f5ebef0a7d1de2d256f82f52af3f180b3588f"
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
  file_path_from_root: "channels\42\tasks\completed\ACTOR-PORT-001.md"
  file_hash: "e0f6c3e21edff8077e24ffb4eee26b081cc509d342ad527c1abb3c9f838cab70"
  file_path_from_root: "channels\42\tasks\completed\ACTOR-PORT-001.md"
  file_hash: "fa87c25fce09b5f78f75e48d0062b29e74fe33c2919ba00702dd483583bf52c7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for ACTOR-PORT-001.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "tasks", "completed", "actor-port-001md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: "ACTOR-PORT-001"
channel_id: 42
assigned_to: [1001]
status: "completed"
priority: "high"
created_utc: "20260227"
completed_utc: "20260227"
task_type: "portability"
---

# ✅ ACTOR-PORT-001: Enhanced export/import with checksum validation

**Status:** COMPLETE

Rewrote export/import scripts with SHA256 checksums and registry mapping.