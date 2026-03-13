# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\prompts\REORGANIZATION_COMPLETE.md"
  file_hash: "9bd6d583c98f0b3cfe3c521b3feafca32c126ddfb45eb55061788791ac254b34"
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
  file_path_from_root: "prompts\REORGANIZATION_COMPLETE.md"
  file_hash: "d4755e7b819a63f031ff4a3db3b61b83c0926255061f9e99b80b38f06ccf50a1"
  file_path_from_root: "prompts\REORGANIZATION_COMPLETE.md"
  file_hash: "1bce44d83a78eeb69b786e30eefd42d24cfd94200377c166969a12e8817a64be"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Prompts Folder Reorganization Complete"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["prompts", "reorganization_completemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Prompts Folder Reorganization Complete

**Date**: 2026-02-25  
**Version**: 4.0.45  
**Lead**: WARP (actor_id 1004)  
**Directive**: Captain Wolfie (actor_id 10000)

## ✅ Registry Lookup Completed

All actor IDs verified from source of truth: `database/migrations/seed_actors_agents_4.0.45.sql`

### Verified Actor IDs

| Agent | Actor ID | Type | Status |
|-------|----------|------|--------|
| System | 0 | system | ✅ Verified |
| Captain WOLFIE (AI) | 1 | agent | ✅ Verified |
| LILITH | 2 | agent | ✅ Verified |
| ROSE | 3 | agent | ✅ Verified |
| ERIS | 4 | agent | ✅ Verified |
| METIS | 5 | agent | ✅ Verified |
| KIRO IDE | 1000 | ide_agent | ✅ Verified |
| Windsurf IDE | 1001 | ide_agent | ✅ Verified |
| Cursor IDE | 1002 | ide_agent | ✅ Verified |
| Cascade IDE | 1003 | ide_agent | ✅ Verified |
| Warp IDE | 1004 | ide_agent | ✅ Verified |
| Captain (Human) | 10000 | human | ✅ Verified |

## ✅ Folder Structure Created

All folders created using ACTUAL IDs from registry (NO HARDCODING):

```
prompts/
├── registry.json              # Complete actor lookup table
├── 0/                         # System
│   └── README.md
├── 1/                         # Captain WOLFIE (AI)
│   └── README.md
├── 2/                         # LILITH
│   ├── README.md
│   └── [moved files]
├── 3/                         # ROSE
│   └── README.md
├── 4/                         # ERIS
│   └── README.md
├── 5/                         # METIS
│   └── README.md
├── 1000/                      # KIRO IDE
│   ├── README.md
│   └── [moved files]
├── 1001/                      # Windsurf IDE
│   ├── README.md
│   └── [moved files]
├── 1002/                      # Cursor IDE
│   └── README.md
├── 1003/                      # Cascade IDE
│   └── README.md
├── 1004/                      # Warp IDE
│   └── README.md
└── 10000/                     # Captain (Human)
    └── README.md
```

## ✅ Files Moved

- **KIRO prompts**: Moved from `prompts/kiro/` to `prompts/1000/`
- **Windsurf prompts**: Moved from `prompts/windsurf/` to `prompts/1001/`
- **LILITH prompts**: Moved from `prompts/lilith/` to `prompts/2/`

## ⚠️ Manual Review Required

The following folders need actor_id assignment:

- `prompts/antigravity/` - Actor ID unknown, needs verification
- `prompts/ai/` - Generic folder, needs specific actor assignment

## 📋 Registry.json Created

Complete lookup table with:
- All 12 actors with verified IDs
- Canonical names and aliases
- Folder mappings
- Type classifications
- Verification notes

## 🚨 Critical Rules Enforced

1. ✅ **NO HARDCODED IDs** - All IDs looked up from seed file
2. ✅ **Registry as Source of Truth** - `seed_actors_agents_4.0.45.sql` is canonical
3. ✅ **Verified Core IDs** - Captain=10000, KIRO=1000 confirmed
4. ✅ **All Other IDs Verified** - Every agent ID extracted from seed file
5. ✅ **Folder Names = Actor IDs** - No name-based folders remain

## 📝 Naming Convention

All prompts should follow: `[YYYYMMDD]_[description].md`

Example: `20260225_registry_reorganization.md`

## ✅ Completion Checklist

- [x] Registry lookup from seed file
- [x] All actor IDs verified
- [x] Folders created with numeric IDs
- [x] README files created for each actor
- [x] Existing prompts moved to correct folders
- [x] registry.json generated with all mappings
- [x] No name-based folders remain (except pending review)
- [x] Documentation complete

## 🎯 Result

**NO ASSUMPTIONS WERE MADE.**  
**ALL IDs VERIFIED FROM SOURCE OF TRUTH.**  
**REGISTRY IS NOW THE SINGLE SOURCE OF TRUTH FOR ACTOR LOOKUPS.**

---

**Completed by**: WARP IDE (actor_id 1004)  
**UTC**: 20260225  
**Status**: ✅ COMPLETE
