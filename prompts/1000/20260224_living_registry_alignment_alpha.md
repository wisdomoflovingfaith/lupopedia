# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "prompts\1000\20260224_living_registry_alignment_alpha.md"
  file_hash: "7aba3a1856f51ebb74f549fd10ca8773216ff30d45c0e2aa7e32fe2e15942f8f"
  file_path_from_root: "prompts\1000\20260224_living_registry_alignment_alpha.md"
  file_hash: "77504924a603292feb99e9186635010dddde1f7177371dea65d2f5f425975882"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224_living_registry_alignment_alpha.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["prompts", "1000", "20260224_living_registry_alignment_alphamd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "prompts/kiro/20260224_living_registry_alignment_alpha.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "FFD700",
  purpose: "Directive for KIRO to align Crafty Syntax Alpha headers with the Living Registry standard",
  last_modified_utc: "20260224",
  delegation_chain: "1003:10000",
  actor_id: 1003,
  lupo_agent: "antigravity",
  artifact_type: "prompt",
  artifact_kind: "directive",
  traits: ["kiro", "fix", "registry", "alpha", "v4.0.39"]
}
---

# 📡 DIRECTIVE: KIRO (1001) — ALIGN WITH LIVING REGISTRY (v4.0.39)

**From:** Antigravity (1003)  
**To:** KIRO (1001)  
**Subject:** REDO BATCH ALPHA (PHASE 0) — Living Registry Alignment

## 🚨 SITUATION RECAP
The **Master ID Registry** (`docs/registry/REGISTERED_IDS.md`) has been upgraded to a **Living Artifact** following a critical LILITH review. It is no longer a static list of IDs; it is a dynamic semantic engine that tracks **Hashtags**, **Engagement**, and **Typed Edges** for every actor and channel.

## 🎯 MANDATORY ACTION
You are directed to **REDO/UPDATE** all headers and footers for the **Batch Alpha (Phase 0)** files to include the enriched semantic fields. These files must now "speak" to the living registry.

### 🛠️ New Header Requirements:
1.  **Engagement Block**: Every header must include the `engagement` object:
    ```json5
    engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" }
    ```
2.  **Hashtags**: Add at least 2 relevant `#hashtags` to the `wolfie.headers`.
3.  **Graph Stats**: Every header must include the `graph_stats` object:
    ```json5
    graph_stats: { inbound_count: 0, outbound_count: 0, centrality_score: 0.0 }
    ```
4.  **Typed Edges**: In the `flip.footer`, all edges must be **Typed** and **Weighted** (e.g., `{ to: "path", type: "references", weight: 0.8, hashtag: "#migration" }`).

## 📂 TARGET FILES (REDO LIST)
Please apply these semantic upgrades to the following Batch Alpha artifacts:

- `lupo-includes/modules/crafty_syntax/crafty_syntax-controller.php`
- `lupo-includes/modules/crafty_syntax/visitor-session-helper.php`
- `app/Services/CraftyMigrationService.php`
- `app/Services/CraftyConfigTransformer.php`
- `app/Services/CraftySyntax/LegacyTheatricalUIWrapper.php`
- `docs/channels/doctrine/legacy-import/CRAFTY_SYNTAX_UI_THEATRICAL_DOCTRINE.md`
- `plan_for_crafty_syntax.md`
- `install.php`
- `install_wizard_classes.php`
- `lupo-includes/bootstrap.php`
- `lupo-includes/lupopedia-setup.php`
- `database/migrations/install_new_lupopedia.sql`
- `scripts/migrate_user_mappings.php`
- `lupo-includes/modules/crafty_syntax/CRAFTY_SYNTAX_SQL_TOON_REPORT.md`
- `scripts/generate_install_sql.py`

## 📊 QUALITY METRICS
- **JSON5 Compliance**: 100%. No YAML.
- **Authority**: All `delegation_chain` values must be valid and end with Human 10000.
- **Centrality**: Ensure major migration files (like `install.php`) have a high `centrality_score`.

**Reply in Channel 42 when Batch Alpha is re-aligned.**