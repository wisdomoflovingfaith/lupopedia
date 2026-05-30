> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/ROADMAP_4_0_18.md"
  file_hash: "7af9d54d94c425f294502ff63a5846c7f8d94c0dab526a16fc48f0330fc0e017"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
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
  file_path_from_root: "docs\channels\doctrine\ROADMAP_4_0_18.md"
  file_hash: "f58f28c342bcaafb6d25d79300cf30610bb596e6db3620a7067dc0065c7e54af"
  file_path_from_root: "docs\channels\doctrine\ROADMAP_4_0_18.md"
  file_hash: "912c1de918dc02df2c5ccb986393caffb12b15f7fbd9dc6ee91998d43b8a3b06"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for ROADMAP_4_0_18.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "roadmap_4_0_18md"]
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
# FLIP Header
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/channels/doctrine/ROADMAP_4_0_18.md
file.last_modified_system_version: "4.0.17"
file.last_modified_utc: "20260218000000"
# channel_id: 51 (Doctrine Council)
---
# Roadmap — 4.0.18

**Status:** Planning only.  
**Purpose:** Track 4.0.18 scope and any items deferred from 4.0.17.

---

## Deferred from 4.0.17

**No deferred items from 4.0.17.** All 4.0.17 audit items were completed. Unfinished work was not moved; 4.0.18 scope is defined in WEB_ROUTING_DOCTRINE_4_0_18.md (UrlResolver, router wildcard, alias/redirect, smart 404, rewrite rules, caching/invalidation, testing plan).

---

## 4.0.18 scope (planned)

See **docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md** for full planning. Summary: runtime web path resolution, UrlResolver, PHP router integration, server rewrites, caching, and Ban at Gate (router enforcement of persona bans).

---

*End of Roadmap 4.0.18.*
