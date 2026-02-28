# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\scripts\bulk_update_headers_4_1_6.md"
  file_hash: "31c66c8548f3817fbc399d476b995afdbc9de70f570e0a45c6bba741e9a662d3"
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
  file_path_from_root: "scripts\bulk_update_headers_4_1_6.md"
  file_hash: "4aadf0edddeca78544728a6c7eb94a37bb5f60e9066f7265a046bb4963d2af5f"
  file_path_from_root: "scripts\bulk_update_headers_4_1_6.md"
  file_hash: "b75138907534b40d7a89ba17b66f76b4a3e199df9531272c966cdde555c39fce"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Bulk Header Update to 3.1.6"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["scripts", "bulk_update_headers_4_1_6md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Bulk Header Update to 3.1.6

## What This Does

Updates all files with old WOLFIE header versions to 3.1.6 and adds required fields.

## Required Updates

1. **Version:** `file.last_modified_system_version: 3.1.6`
2. **Header Atoms:** Must include:
   - `GLOBAL_CURRENT_LUPOPEDIA_VERSION`
   - `GLOBAL_CURRENT_AUTHORS`
3. **Dialog Block:** Must use `mood_RGB:` (not `mood:`)
4. **Simplify optional metadata:** Remove deprecated complexity, keep it simple

## Files to Update

Found 112 doctrine files with old versions. Update them systematically:

1. Update version number
2. Fix `mood:` → `mood_RGB:`
3. Ensure header_atoms are present
4. Simplify optional metadata references

## Script

See `scripts/update_headers_to_4_1_6.php` for automated update script.

## Manual Updates Completed

- ✅ VERSION_DOCTRINE.md (3.0.35 → 3.1.6)
- ✅ AGENT_RUNTIME.md (3.0.14 → 3.1.6)
- ✅ PATCH_DISCIPLINE.md (3.0.14 → 3.1.6)
- ✅ METADATA_GOVERNANCE.md (3.0.14 → 3.1.6)
- ✅ AI_UNCERTAINTY_EXPRESSION_DOCTRINE.md (3.0.14 → 3.1.6, mood → mood_RGB)
- ✅ AI_INTEGRATION_SAFETY_DOCTRINE.md (3.0.14 → 3.1.6, mood → mood_RGB)
- ✅ DIALOG_FILE_ORDERING_DOCTRINE.md (3.0.15 → 3.1.6, mood → mood_RGB)

## Remaining Files

~105 doctrine files still need updates. Run the PHP script or continue manual updates.