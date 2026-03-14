# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\versions\4.0.35\TODO.md"
  file_hash: "8938d069877bf09a43b9b8398a29df43742f413720df5fdabb899b4897f071ec"
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\versions\4.0.35\TODO.md"
  file_hash: "56b96cb497c50d209a0e5d73a060e1904af58d4ef0ec9b0e9bbe5c6f305b2737"
  file_path_from_root: "lupo-docs\versions\4.0.35\TODO.md"
  file_hash: "1b4c52a08e8b80dd770faf7315315ec10cbf23c35e18657b56446ee701bbf58a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TODO.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "versions", "4035", "todomd"]
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
wolfie.headers:
  file_path_from_root: "lupo-docs/versions/4.0.35/TODO.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "AA00FF"
  purpose: "Task tracking for version 4.0.35"
  last_modified: "20260223"
  actor_id: 1003
  lupo_agent: "antigravity"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "antigravity"
---

# LUPOPEDIA v4.0.35 TODO LIST

## P1: REGISTRY CONSOLIDATION (DATABASE PHASE)
- [ ] Execute `lupo-database/migrations/dev_20260223_registry_consolidation.sql`
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
- [x] Verify MD-only channel browsing (discovered via `messages/`, `lupo-docs/channels/`, `lupo-channels/`)
- [x] Update verified publisher identity (Eclipse: `lupopedia`)

## P4: SECURITY & STABILITY
- [ ] OAuth error handling improvements
- [ ] Semantic security bypass pattern expansion
- [ ] Doctrine cleanup and consolidation
