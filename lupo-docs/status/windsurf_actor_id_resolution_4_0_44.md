# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\status\windsurf_actor_id_resolution_4_0_44.md"
  file_hash: "d997eb2c67b686416535ef7feef5696bbda7f91c391c1555c8f12223ec93879d"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\status\windsurf_actor_id_resolution_4_0_44.md"
  file_hash: "d22f6f0a07294bbdc0e04e4f0f73a632a0bcb0f6f566bf74ce6f899f843c4bd5"
  file_path_from_root: "docs\status\windsurf_actor_id_resolution_4_0_44.md"
  file_hash: "abdb3830c1af2ae5ca43270cc9b880edfbf6062e0ca1ede09d80e085a7bd2178"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for windsurf_actor_id_resolution_4_0_44.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "windsurf_actor_id_resolution_4_0_44md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

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
