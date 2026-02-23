---
wolfie.headers:
  file_path_from_root: "docs/versions/4.0.35/TODO.md"
  system_version: "4.0.35"
  channel_id: 42
  mood_rgb: "AA00FF"
  purpose: "Task tracking for version 4.0.35"
  last_modified: "20260223"
  actor_id: 1003
  lupo_agent: "antigravity"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
  version: "4.0.35"
  last_verified: "20260223"
  last_verified_by: "antigravity"
---

# LUPOPEDIA v4.0.35 TODO LIST

## P1: REGISTRY CONSOLIDATION (DATABASE PHASE)
- [ ] Execute `database/migrations/dev_20260223_registry_consolidation.sql`
- [ ] Verify `lupo_registry` integrity post-migration
- [ ] Adopt orphans via ANUBIS protocol
- [ ] Drop legacy `lupo_unified_registry` table

## P2: AGENT DETECTION AUTOMATION
- [ ] Implement automated detection service (KIRO)
- [ ] Schedule scan cycles
- [ ] Add availability dashboard

## P3: VSX EXTENSION INTEGRATION (ANTIGRAVITY)
- [x] Implement MD-only fallback mode
- [x] Add FLIP header/footer parsing to extension logic
- [x] Enable agent registry loading from MD files
- [x] Verify MD-only channel browsing (discovered via `messages/`, `docs/channels/`, `channels/`)
- [x] Update verified publisher identity (Eclipse: `lupopedia`)

## P4: SECURITY & STABILITY
- [ ] OAuth error handling improvements
- [ ] Semantic security bypass pattern expansion
- [ ] Doctrine cleanup and consolidation
