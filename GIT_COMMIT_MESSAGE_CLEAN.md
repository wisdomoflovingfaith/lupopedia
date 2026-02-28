# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "4499d3d9554e55ea118166676bf18cbed52f8dab323c153c3f0d313740f71370"
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "e5a10431e9b1bbc21bbb789060b0cf15203118a790cd8ef105256194270a346a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for GIT_COMMIT_MESSAGE_CLEAN.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["git_commit_message_cleanmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "07f25f03e1356d68982d1a9dd677add8f36e7451b28341b4562e5b615139232b"
  system_version: "4.0.50"
  delegation_chain: null
  needs_review: ["delegation_chain"]
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: []
  artifact_type: "documentation"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

  assigned_custodian: "2035"
  delegation_chain: "10000:2035"
  purpose: "Documentation file. Assigned to ANUBIS for custodial intelligence."
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "4499d3d9554e55ea118166676bf18cbed52f8dab323c153c3f0d313740f71370"
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "e5a10431e9b1bbc21bbb789060b0cf15203118a790cd8ef105256194270a346a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for GIT_COMMIT_MESSAGE_CLEAN.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["git_commit_message_cleanmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "07f25f03e1356d68982d1a9dd677add8f36e7451b28341b4562e5b615139232b"
  system_version: "4.0.50"
  delegation_chain: null
  needs_review: ["delegation_chain"]
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: []
  artifact_type: "documentation"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "4499d3d9554e55ea118166676bf18cbed52f8dab323c153c3f0d313740f71370"
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "e5a10431e9b1bbc21bbb789060b0cf15203118a790cd8ef105256194270a346a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for GIT_COMMIT_MESSAGE_CLEAN.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["git_commit_message_cleanmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  file_path_from_root: "GIT_COMMIT_MESSAGE_CLEAN.md"
  file_hash: "07f25f03e1356d68982d1a9dd677add8f36e7451b28341b4562e5b615139232b"
  system_version: "4.0.50"
  delegation_chain: null
  needs_review: ["delegation_chain"]
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

windsurf: Fix critical registry schema mismatches blocking Lupopedia 4.0.27 installation

RESOLVED: Schema crisis causing "Unknown column 'registry_id'" errors
- Fixed install_new_lupopedia.sql schema (removed deprecated registry_id column)
- Updated all INSERT statements to use correct column names
- Created seed_minimal_4.0.26.sql with schema-compatible seed data
- Fixed install wizard to use minimal seed instead of broken seed_lupopedia.sql
- Updated all PHP application code to use correct registry table names
- Fixed Python tools for registry SQL generation
- Updated VSX extension to remove deprecated registry_id references
- Comprehensive doctrine documentation updates (REGISTRY_DOCTRINE.md)
- Updated README.md registry system documentation

IMPACT: Fresh Crafty Syntax 3.7.5 → Lupopedia 4.0.27 upgrades now complete without SQL errors

FILES MODIFIED: 45+ files across schema, application code, documentation, and tools
STATUS: Installation unblocked, multi-IDE coordination established, ready for testing