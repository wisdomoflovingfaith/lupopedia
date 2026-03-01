# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224163700_1001_10000_actor_registry_alias_map_complete.md"
  file_hash: "eb8503176401e4ab171eb9e573c96961c7227f0856e68c63712ada0c1497116f"
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
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224163700_1001_10000_actor_registry_alias_map_complete.md"
  file_hash: "27349b0688178e3fc1cf96ef5974af8fabf0718834a01976fe2547e6a4ab2095"
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224163700_1001_10000_actor_registry_alias_map_complete.md"
  file_hash: "1299b998c0be8a558550ed728913c38322b6e17071f35f68569373acf0eaaa17"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224163700_1001_10000_actor_registry_alias_map_complete.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "threads", "development_cycle_4_0_43", "20260224163700_1001_10000_actor_registry_alias_map_completemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
thread_id: 20260224163700_1001_10000_actor_registry_alias_map_complete
from_actor_id: 1001
from_actor_name: "KIRO"
to_actor_id: 10000
to_actor_name: "Captain Wolfie"
channel_id: 42
thread_type: "DEVELOPMENT_CYCLE"
subject: "actors/ Updated with registry.json + aliases.csv"
created_utc: "20260224163700"
read_by_actor_id: null
read_by_actor_utc: null
---

Captain Wolfie —

## ✅ KIRO: actors/ updated with registry.json + aliases.csv. VSX/import now resolve slug aliases to canonical actor_id without guessing. Version 4.0.43.

Directive processed. The actors/ folder is now a first-class canonical source.

### Files Created

**1. actors/registry.json**
- 46 actor entries with canonical metadata
- Schema: canonical_slug, display_name, actor_type, timestamps, soft delete
- Actor types: system_kernel, ide_agent, external_ai, human_owner, banned

**2. actors/aliases.csv**
- 66 total aliases (65 active, 1 deleted)
- Schema: alias_slug, actor_id, alias_type, notes, timestamps, soft delete
- Alias types: canonical, handle, legacy_name, email_slug

**3. scripts/validate_actor_registry.py**
- CI-ready validation script
- 7 validation checks
- Exit code 0 = pass, 1 = fail

### Registry Summary

**46 Actors by Type:**
- System Kernel: 25 (IDs 0-209, 1212)
- IDE Agents: 10 (IDs 1001-1010)
- External AI: 4 (IDs 2010, 2030, 2038)
- Legacy IDs: 5 (IDs 2032, 2034, 2035, 2039, 2040)
- Human Owner: 1 (ID 10000)
- Banned: 1 (ID 420, soft-deleted)

### Aliases Summary

**66 Aliases Added:**

**Captain Wolfie (10000):**
- captain_wolfie (canonical)
- wolfie_captain (handle)
- eric (legacy_name)
- wisdomoflovingfaith-at-gmail-com (email_slug)

**IDE Agents (10):**
- kiro, kiro_ide → 1001
- windsurf, windsurf_ide → 1002
- antigravity, antigravity_ide → 1003
- warp, warp_ide → 1004
- cursor, cursor_ide → 1005
- zed, zed_ide → 1006
- intellij, intellij_idea → 1007
- webstorm, webstorm_ide → 1008
- theia, theia_ide → 1009
- cscode, cs_code → 1010

**System Kernel (25):**
- All kernel agents have canonical aliases
- Common short names: auth → 1, timekeeper → 1212

**External AI (4):**
- chatgpt, gpt4o → 2010
- gemini → 2030
- deepseek, deepseek_lilith → 2038
- deepseek_lexa → 24

**Legacy IDs (5):**
- kiro_legacy → 2032
- cursor_legacy → 2034
- antigravity_legacy → 2035
- warp_legacy → 2039
- windsurf_legacy → 2040

### Collision Analysis

**✅ No Collisions Found**

All active aliases are unique. No duplicate alias_slug values map to different actor_ids.

**Soft Delete Integrity:**
- 1 deleted alias (stoned_wolfie → 420)
- Deleted alias has proper deleted_ymdhis
- No active aliases for deleted actor 420

### Unresolved Aliases

**✅ None Found**

All aliases map to valid actor_ids in registry.json.

### VSX Extension Integration

**Resolution Order:**
1. If numeric actor_id present → use directly
2. Else lookup alias_slug in aliases.csv (case-insensitive)
3. Else fallback to registry.json canonical_slug match
4. Else mark as unresolved → audit report

**Implementation:**
- VSX reads registry.json + aliases.csv on startup
- Alias lookups normalized to lowercase
- Deleted aliases excluded from resolution
- Unresolved aliases logged to audit

### Import Tooling Integration

**Crafty Syntax 3.7.5 → Lupopedia:**
- Import uses aliases.csv for legacy name resolution
- Email-safe slugs map to actor_ids
- Legacy IDs provide backward compatibility
- Actor ID remapping (10000 + user_id) preserved

**Channel/Artifact Import:**
- Python importer uses registry.json
- Unresolved references logged to audit
- No actor_id invention — all must exist

### Validation Results

**Script:** `scripts/validate_actor_registry.py`

**Checks:**
1. ✅ No duplicate active aliases
2. ✅ All actor_ids in aliases exist in registry
3. ✅ All canonical_slugs have canonical alias
4. ✅ No collisions
5. ✅ Soft delete integrity
6. ✅ Deleted actors have only deleted aliases
7. ✅ Active actors have active canonical aliases

**Output:**
```
======================================================================
ACTOR REGISTRY VALIDATION REPORT
======================================================================
Registry entries: 46
Alias entries: 66
Active aliases: 65
Deleted aliases: 1
======================================================================

✅ ALL VALIDATIONS PASSED
======================================================================
```

### Enforcement Rules

**VSX Extension:**
- Normalize alias_slug to lowercase
- Exclude deleted aliases
- Log unresolved aliases
- Never invent actor_ids

**Import Tooling:**
- Validate actor_ids against registry
- Use aliases.csv for legacy resolution
- Fail import if unresolved references
- Log all resolutions to audit

**Maintenance:**
- Never remove rows — soft delete only
- Never reuse deleted actor_ids
- Always add canonical alias with actor
- Run validator before commit

### Actor ID Ranges

**Reserved:**
- 0-9999: System kernel + AI agents
- 10000+: Human operators
- 420: Banned actors

**Current Allocation:**
- 0-209: System kernel (25 allocated)
- 1001-1010: IDE agents (10 allocated)
- 1212: UTC Timekeeper
- 2010-2040: External AI + Legacy (9 allocated)
- 10000: Captain Wolfie
- 420: STONED WOLFIE (banned)

**Available:**
- 210-419: System kernel expansion
- 1011-1211: IDE expansion
- 2041-9999: External AI expansion
- 10001+: Additional humans

### Documentation

**Report:** `docs/status/kiro_actor_registry_alias_map_4_0_43.md`

**Contents:**
- Complete registry summary
- Alias inventory
- Collision analysis
- VSX integration guide
- Import tooling guide
- Validation script documentation
- Enforcement rules
- Actor ID allocation

### Folder Structure

```
actors/
├── registry.json          # 46 actors
├── aliases.csv            # 66 aliases
├── 0/                     # System kernel
├── 10000/                 # Captain Wolfie
└── 420/                   # Banned (archive)
```

### Conclusion

The actors/ folder is now a first-class canonical source alongside channels/ and artifacts/. VSX extension and import tooling can resolve any alias to canonical actor_id without guessing.

**Key Achievements:**
- 46 actors registered
- 66 aliases mapped
- 0 collisions
- 100% validation pass
- CI-ready validator
- VSX integration ready
- Import tooling ready

System ready for 4.0.43 development with full actor identity resolution.

— KIRO (1001)  
UTC: 20260224163700