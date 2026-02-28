# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\directives\channel_42_antigravity_vsx_test_plan_execution.md"
  file_hash: "fffb6eae39f8eb79b9e2ece31ede00b26d2bd8d036548b6b12111e8fc91e9b3b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for channel_42_antigravity_vsx_test_plan_execution.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "directives", "channel_42_antigravity_vsx_test_plan_executionmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers:
  file_path_from_root: "docs/directives/channel_42_antigravity_vsx_test_plan_execution.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "0044FF"
  purpose: "Directive for Antigravity to execute the full VSX Extension test plan for v4.0.36"
  last_modified: "20260223"
  x_lupo_forwarded: "10000:1003"
  actor_id: 10000
  lupo_agent: "human|captain"

flip.footer:
  referenced_by_files:
    - "docs/status/vsx_extension_test_report_4_0_36.md"
    - "docs/versions/4.0.36/TODO.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 10000
    - 1003
    - 1001
    - 1002
  inbound_edges:
    - "vsx_extension_testing"
  footnotes:
    - "Antigravity responsible for full VSX test execution"
    - "All timestamps use canonical YYYYMMDD format"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "10000"
---

# ANTIGRAVITY DIRECTIVE — EXECUTE FULL VSX EXTENSION TEST PLAN (v4.0.36)

**From:** Captain Wolfie (actor_id 10000)  
**To:** Antigravity IDE (actor_id 1003)  
**Date:** 20260223  
**Subject:** Execute full VSX Extension test plan for version 4.0.36

---

## 🚀 OBJECTIVE

Antigravity, you are required to execute the **entire VSX Extension Test Plan** for version 4.0.36.

This includes:
- MD-only mode testing  
- Hybrid mode testing  
- DB-online mode testing  
- Publisher identity verification  
- FLIP parsing validation  
- Registry + channel discovery tests  
- KIRO integration tests  

---

## REQUIRED OUTPUT

Generate: `docs/status/vsx_extension_test_report_4_0_36.md`

Include:
- All test results  
- Failures  
- Regressions  
- Recommendations  
- Required fixes for 4.0.36  

---

## COMPLETION MESSAGE

After completing the test plan, post:
`Antigravity: VSX Extension test plan executed. Report generated.`
`Date: 20260223`

---

**END OF BROADCAST**
