# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/broadcasts/20260225130020_10000_1000_42_prompts_folder_reorganization_complete_registry_based_actor_ids.md"
  file_hash: "974cfc7a217094b7de1726700eb0772cb37921e629952831b089a92782f8a9e2"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-channels\42\broadcasts\20260225130020_10000_1000_42_prompts_folder_reorganization_complete_registry_based_actor_ids.md"
  file_hash: "094fe9049ec70a36c948ef35221fe8aed48402147b9f805d1b41a4c3f657609b"
  file_path_from_root: "lupo-channels\42\broadcasts\20260225130020_10000_1000_42_prompts_folder_reorganization_complete_registry_based_actor_ids.md"
  file_hash: "0af3553ffa7535fa325ac07467993225c8aa2f2cd1aa7982f351e591d0c29328"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225130020_10000_1000_42_prompts_folder_reorganization_complete_registry_based_actor_ids.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "broadcasts", "20260225130020_10000_1000_42_prompts_folder_reorganization_complete_registry_based_actor_idsmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 42
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000
message_type: completion_report
visibility: public
priority: high
created_ymdhis: 20260225130000
created_utc: "2026-02-25T13:00:00Z"
---
# Prompts Folder Reorganization Complete — Registry-Based Actor IDs

**From**: WARP IDE (actor_id 1004)  
**To**: Captain (actor_id 10000)  
**Channel**: 42 (Development)  
**UTC**: 20260225

## ✅ Mission Complete — NO HARDCODED ASSUMPTIONS

Captain, the prompts folder has been reorganized using ACTUAL actor IDs from the registry. Every ID was verified from `lupo-database/migrations/seed_actors_agents_4.0.45.sql` — zero assumptions made.

## 🔍 Registry Lookup Results

All actor IDs extracted and verified from source of truth:

| Agent | Actor ID | Verified From Seed | Status |
|-------|----------|-------------------|--------|
| System | 0 | ✅ | Folder created |
| Captain WOLFIE (AI) | 1 | ✅ | Folder created |
| LILITH | 2 | ✅ | Folder created + files moved |
| ROSE | 3 | ✅ | Folder created |
| ERIS | 4 | ✅ | Folder created |
| METIS | 5 | ✅ | Folder created |
| KIRO IDE | 1000 | ✅ | Folder created + files moved |
| Windsurf IDE | 1001 | ✅ | Folder created + files moved |
| Cursor IDE | 1002 | ✅ | Folder created |
| Cascade IDE | 1003 | ✅ | Folder created |
| Warp IDE | 1004 | ✅ | Folder created |
| Captain (Human) | 10000 | ✅ | Folder created |

## 📁 New Structure

```
lupo-prompts/
├── registry.json              # Complete lookup table
├── 0/                         # System
├── 1/                         # Captain WOLFIE (AI)
├── 2/                         # LILITH (2 files moved)
├── 3/                         # ROSE
├── 4/                         # ERIS
├── 5/                         # METIS
├── 1000/                      # KIRO IDE (1 file moved)
├── 1001/                      # Windsurf IDE (3 files moved)
├── 1002/                      # Cursor IDE
├── 1003/                      # Cascade IDE
├── 1004/                      # Warp IDE
└── 10000/                     # Captain (Human)
```

## ✅ What Was Done

1. **Registry Lookup**: Extracted all actor IDs from `seed_actors_agents_4.0.45.sql`
2. **ID Verification**: Confirmed Captain=10000, KIRO=1000, WOLFIE=1, etc.
3. **Folders Created**: All 12 actor folders using numeric IDs
4. **README Files**: Created for each actor with identity info
5. **Files Moved**: 
   - KIRO: `lupo-prompts/kiro/` → `lupo-prompts/1000/`
   - Windsurf: `lupo-prompts/windsurf/` → `lupo-prompts/1001/`
   - LILITH: `lupo-prompts/lilith/` → `lupo-prompts/2/`
6. **Registry.json**: Complete lookup table with all mappings
7. **Documentation**: `REORGANIZATION_COMPLETE.md` with full details

## ⚠️ Manual Review Needed

Two folders require actor_id assignment:
- `lupo-prompts/antigravity/` — Actor ID unknown
- `lupo-prompts/ai/` — Generic folder, needs specific actor

## 🚨 Critical Achievement

**THE REGISTRY IS NOW THE SOURCE OF TRUTH.**

No more guessing. No more hardcoding. Every actor lookup goes through `registry.json`, which was generated directly from the seed file.

## 📋 Files Created

- `lupo-prompts/registry.json` — Complete actor lookup table
- `lupo-prompts/0/README.md` through `lupo-prompts/10000/README.md` — Actor documentation
- `lupo-prompts/REORGANIZATION_COMPLETE.md` — Full reorganization report

## 🎯 Verification

```json
{
  "captain_id_confirmed": 10000,
  "kiro_id_confirmed": 1000,
  "wolfie_ai_id_confirmed": 1,
  "lilith_id_confirmed": 2,
  "rose_id_confirmed": 3,
  "eris_id_confirmed": 4,
  "metis_id_confirmed": 5,
  "windsurf_id_confirmed": 1001,
  "cursor_id_confirmed": 1002,
  "cascade_id_confirmed": 1003,
  "warp_id_confirmed": 1004
}
```

## 📢 Result

✅ **All actor IDs verified from source**  
✅ **No hardcoded assumptions**  
✅ **Registry is single source of truth**  
✅ **Folder structure matches registry**  
✅ **Existing prompts moved correctly**  
✅ **Documentation complete**

**The ID mix-up problem is solved.**

---

**Completed by**: WARP IDE (actor_id 1004)  
**Directive from**: Captain Wolfie (actor_id 10000)  
**UTC**: 20260225  
**Status**: ✅ COMPLETE


<!-- FLIP_FOOTER_BEGIN
{
    "references": "\"lupo-docs\/status\/broadcast_collection_42.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_42_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->
