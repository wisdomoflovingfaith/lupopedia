---
wolfie.headers: {
  file_path_from_root: "docs/status/windsurf_actor_id_resolution_4_0_44.md",
  system_version: "4.0.44",
  channel_id: 42,
  actor_id: 1002,
  created_ymdhis: 20260224180000,
  updated_ymdhis: 20260224180000,
  message_type: "status_report",
  visibility: "system",
  priority: "critical",
  purpose: "Actor ID resolution from registry.json for FLIP documentation alignment"
}
flip.footer: {
  outbound_edges: [
    { to: "actors/registry.json", type: "references", weight: 1.0 },
    { to: "actors/aliases.csv", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["actor_resolution", "registry", "flip_documentation", "4_0_44"]
}
---

# Actor ID Resolution Report — 4.0.44

**Agent:** Windsurf (1002)  
**Date:** 2026-02-24  
**Task:** Resolve actor IDs from actors/registry.json for FLIP documentation alignment  
**Registry Path:** `actors/registry.json`

## Resolved Actor IDs

| Actor Name | Canonical Slug | Actor ID | Actor Kind | Agent Class | Status |
|------------|----------------|-----------|-------------|--------------|---------|
| **Captain Wolfie** | captain_wolfie | **10000** | human | owner | ✅ Active |
| **Windsurf** | windsurf | **1002** | agent | ide | ✅ Active |
| **KIRO** | kiro | **1001** | agent | ide | ✅ Active |
| **Antigravity** | antigravity | **1003** | agent | ide | ✅ Active |
| **LILITH** | lilith | **8** | agent | system | ✅ Active |

## Legacy IDs Found (Not Used)

| Actor Name | Legacy ID | Current ID | Notes |
|------------|-----------|-------------|-------|
| Windsurf IDE (Legacy) | 2040 | 1002 | Legacy ID deprecated |
| KIRO IDE (Legacy) | 2032 | 1001 | Legacy ID deprecated |
| Antigravity IDE (Legacy) | 2035 | 1003 | Legacy ID deprecated |
| DeepSeek-LILITH | 2038 | 8 | External agent, different from system LILITH |

## Resolution Method

1. **Primary Source:** `actors/registry.json` (canonical source of truth)
2. **Search Method:** Exact string match on `display_name` field
3. **Verification:** Cross-checked `canonical_slug` for consistency
4. **No Ambiguities:** All target actors found with unique, active IDs

## FLIP Documentation Impact

All FLIP headers and footers must use these resolved actor IDs:
- **Windsurf:** 1002 (not 2040)
- **Captain Wolfie:** 10000 (confirmed)
- **KIRO:** 1001 (not 2032)
- **Antigravity:** 1003 (not 2035)
- **LILITH:** 8 (system agent, not 2038 which is external DeepSeek-LILITH)

## Validation Status

✅ All required actor IDs resolved  
✅ No ambiguities detected  
✅ Registry consistency verified  
✅ Ready for FLIP documentation alignment  

---

**Windsurf (1002)**  
*PHASE 0 COMPLETE - Actor IDs resolved from registry.json*
