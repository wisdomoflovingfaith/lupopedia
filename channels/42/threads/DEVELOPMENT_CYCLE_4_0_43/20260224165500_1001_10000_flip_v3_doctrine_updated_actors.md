# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224165500_1001_10000_flip_v3_doctrine_updated_actors.md"
  file_hash: "3253d39cf5e27c2688e973bb2f02fae47ca3cb28ed555c6781f47d28a2a39023"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224165500_1001_10000_flip_v3_doctrine_updated_actors.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "threads", "development_cycle_4_0_43", "20260224165500_1001_10000_flip_v3_doctrine_updated_actorsmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: {
  file_path_from_root: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_43/20260224165500_1001_10000_flip_v3_doctrine_updated_actors.md",
  system_version: "4.0.43",
  channel_id: 42,
  actor_id: 1001,
  to_actor_id: 10000,
  created_ymdhis: 20260224165500,
  updated_ymdhis: 20260224165500
}
flip.footer: {
  outbound_edges: [
    { to: "channels/0/broadcasts/20260224165300_0_10000_flip_v3_retrofit_doctrine.md", type: "references", weight: 1.0 },
    { to: "actors/", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["update", "flip", "actors", "doctrine"]
}
---

# KIRO: FLIP v3 Doctrine Updated to Include actors/

**From:** KIRO (1001)  
**To:** Captain Wolfie (10000)  
**Date:** 2026-02-24 16:55 UTC  
**Thread:** DEVELOPMENT_CYCLE_4_0_43

## Status: ✅ UPDATED

Doctrine #14 updated to include actors/ directory alongside artifacts/ and channels/. All .md files in actors/ will receive FLIP v3 headers.

## Changes Made

### Doctrine #14 Updated
- Title changed to "FLIP v3 RETROFIT FOR ARTIFACTS + CHANNELS + ACTORS"
- Scope expanded to include `actors/**/*.md`
- Added special handling section for actors/ files

### Special Handling for actors/ Files

**1. Extract actor_id from path:**
- Path format: `actors/<actor_id>/<filename>.md`
- `actor_id` is the folder name (numeric)
- Set `actor_confidence = 1.0` (explicit from path)

**2. Set artifact_type appropriately:**
- `artifact_type: "actor_metadata"` for profiles/metadata
- `artifact_type: "actor_document"` for documentation

**3. Add actor-specific relations:**
```yaml
relations:
  - rel: "describes_actor"
    target_actor_id: <actor_id>
  - rel: "part_of_actor_folder"
    target: "actors/<actor_id>/"
```

**4. Cross-reference with actors/registry.json:**
- Validate actor_id exists in registry
- Add display_name, canonical_slug to metadata
- Enrich with actor_kind, agent_class

### Deterministic artifact_id for actors/
```
artifact_id = sha1("actors/<actor_id>/<relative_path>" + "\n" + file_bytes_sha1)
```

### Example actors/ FLIP Header
```yaml
---
flip_version: 3
system_version: "4.0.43"
artifact_id: "sha1:..."
federated_node_id: 10000  # from actors/<id>/
artifact_path: "actors/10000/profile.md"
artifact_type: "actor_metadata"
artifact_kind: "actor_profile"
actor_id: 10000
actor_source: "explicit"
actor_confidence: 1.0
created_ymdhis: 20260224091530
created_source: "mtime"
created_confidence: 0.5
semantic_tags: ["actor", "profile", "human"]
relations:
  - rel: "describes_actor"
    target_actor_id: 10000
  - rel: "part_of_actor_folder"
    target: "actors/10000/"
is_deleted: 0
deleted_ymdhis: 0
---
```

## Implementation Impact

**Retrofit script will now process:**
- `artifacts/**/*.md`
- `channels/**/*.md`
- `actors/**/*.md`

**Actor files will receive:**
- Deterministic artifact_id based on path
- actor_id extracted from folder name (confidence 1.0)
- Cross-reference validation with actors/registry.json
- Actor-specific relations (describes_actor, part_of_actor_folder)
- Enrichment with registry metadata (display_name, canonical_slug, actor_kind, agent_class)

## Validation

✅ Doctrine #14 updated with actors/ scope  
✅ Special handling section added  
✅ Example FLIP header provided  
✅ Deterministic artifact_id formula documented  
✅ Actor-specific relations defined  
✅ Registry cross-reference requirements documented  

## Ready for Implementation

All three directories (artifacts/, channels/, actors/) are now covered by Doctrine #14. Ready to implement `scripts/flip_retrofit_artifacts.py` with full support for all three source folders.

---

**KIRO (1001)**  
**Delegation Chain:** 1001:10000  
**Status:** ✅ UPDATED — READY FOR IMPLEMENTATION
