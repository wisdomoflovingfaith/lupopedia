# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/registry/REGISTERED_IDS.md"
  file_hash: "76e015d218893d0399fd27297e8efbc7f9b4b1e84946981de46978255b962dd4"
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
  file_path_from_root: "lupo-docs\registry\REGISTERED_IDS.md"
  file_hash: "efdccda4287fe451eca6ef1b772432cbb9be7bb55540759c827bed2f9a8d87eb"
  file_path_from_root: "lupo-docs\registry\REGISTERED_IDS.md"
  file_hash: "f28184f1c0bc0df6d64e9c36eb9678c9cfd77a59d729d7361f6d9f30e390521a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for REGISTERED_IDS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "registry", "registered_idsmd"]
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
  file_path_from_root: "lupo-docs/registry/REGISTERED_IDS.md",
  system_version: "4.0.39",
  channel_id: 0,
  mood_rgb: "AAAAAA",
  purpose: "Canonical dynamic registry of Lupopedia IDs (Living Artifact)",
  last_modified_utc: "20260224",
  delegation_chain: "19:1001:10000",
  actor_id: 10000,
  artifact_type: "registry",
  artifact_kind: "governance_core",
  traits: ["canonical", "registry", "dynamic", "v4.0.39"],
  hashtags: ["#id_registry", "#governance", "#living_artifact"],
  engagement: { likes: 12, shares: 3, views: 45 },
  graph_stats: { inbound: 5, outbound: 20, centrality: 0.9 }
}

flip.footer: {
  inbound_edges: [
    { from: "CHANGELOG.md", type: "defines", weight: 1.0 },
    { from: "QUICKSTART.md", type: "implements", weight: 0.9 }
  ],
  outbound_edges: [
    { to: "lupo-docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md", type: "references", weight: 0.8 },
    { to: "lupo-docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md", type: "references", weight: 0.8 }
  ],
  semantic_relationships: { "defines": ["actors", "channels", "departments"] },
  enrichment: { "llm_inferred_edges": ["related_to:actor_model"] },
  referenced_by_actors: [10000, 1001, 1003],
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "captain"
}
---

# 🧾 LUPopedia Canonical ID Registry (v4.0.39)

This is a **Living Artifact**. It is authoritative but dynamic, synchronized by ANUBIS/KIRO swarms and enriched with semantic metrics.

---

## 📦 REGISTRY DATA (JSON5)

```json5
{
  // =====================================================
  // ACTORS (AI + IDE + SYSTEM + HUMAN)
  // =====================================================
  "actors": {
    "0": { 
      "name": "SYSTEM", "type": "system", "role": "kernel", "status": "active",
      "hashtags": ["#kernel", "#root"], "engagement": { "likes": 5, "shares": 0 }
    },
    "1": { "name": "AUTHENTICATOR", "type": "system", "role": "identity_manager", "status": "active" },
    "19": { 
        "name": "ANUBIS", "type": "system", "role": "orphan_resolver", "status": "active",
        "hashtags": ["#guardian", "#routing"], "engagement": { "likes": 25, "shares": 5 }
    },
    "1001": { 
      "name": "KIRO", "type": "ai", "role": "orchestrator", "status": "active", "since": "4.0.0",
      "hashtags": ["#fast_agent", "#db_migrations"],
      "engagement": { "likes": 150, "shares": 45 },
      "edges": { "owns": ["department_210"], "in_channel": ["42"] }
    },
    "1002": { "name": "Windsurf", "type": "ai", "role": "documentation_audit", "status": "active" },
    "1003": { 
      "name": "Antigravity", "type": "ai", "role": "tooling_ux", "status": "active",
      "hashtags": ["#vsx", "#tooling"],
      "engagement": { "likes": 120, "shares": 30 },
      "edges": { "owns": ["department_210"], "in_channel": ["42"] }
    },
    "2038": { 
      "name": "LILITH", "type": "ai", "role": "heterodox_reviewer", "status": "active",
      "hashtags": ["#critique", "#security"],
      "engagement": { "likes": 88, "shares": 12 },
      "edges": { "owns": ["department_220"], "in_channel": ["42"] }
    },
    "10000": { 
      "name": "Captain Wolfie", "type": "human", "role": "system_owner", "status": "active",
      "hashtags": ["#authority", "#captain"],
      "engagement": { "likes": 500, "shares": 100 }
    }
  },

  // =====================================================
  // CHANNELS
  // =====================================================
  "channels": {
    "1": { "name": "admin", "purpose": "Strategic command", "status": "active" },
    "42": { 
      "name": "development", "purpose": "Multi-agent coordination", "status": "active",
      "hashtags": ["#dev", "#swarm"], "views": 1200, "active_members": 25
    },
    "420": { "name": "protocol_dev", "purpose": "Legacy protocol dev", "status": "archived", "archived_utc": "20260222" },
    "666": { "name": "security", "purpose": "Security monitoring", "status": "active" }
  },

  // =====================================================
  // DEPARTMENTS / DOMAINS
  // =====================================================
  "departments": {
    "200": { "name": "core_systems", "owner": 10000 },
    "210": { "name": "tooling", "owner": 1003 },
    "220": { "name": "documentation", "owner": 2038 },
    "230": { "name": "security", "owner": 24 }
  },

  // =====================================================
  // COLLECTIONS
  // =====================================================
  "collections": {
    "v4.1.0_release": { "version": "4.1.0", "lead": 1001, "status": "active" },
    "v4.0_archive": { "version": "4.0.x", "status": "frozen" },
    "crafty_migration": { "purpose": "Crafty Syntax -> Lupopedia Upgrade", "status": "active" }
  },

  // =====================================================
  // AGENT POOLS / SWARMS
  // =====================================================
  "agent_pools": {
    "swarm_docs": { "members": [1001, 2038, 1003], "owner": 10000 },
    "swarm_upgrade": { "members": [1001, 1002, 1003, 19], "owner": 10000 }
  }
}
```

---

## 🔐 GOVERNANCE RULES

1. **All IDs MUST appear here before use** in `wolfie.headers` or `flip.footer`.
2. **Deprecated IDs MUST be marked `status: archived`**.
3. **Deleted IDs are never reused** to maintain temporal integrity.
4. **Tooling MUST block or warn on unknown IDs**.
5. **Changes require Channel 42 approval** and multi-agent verification.

---

## 📜 CHANGELOG

* **4.1.0 (2026-02-24)** — Initial unified registry. Integrated all 39+ known actors and core channels.
