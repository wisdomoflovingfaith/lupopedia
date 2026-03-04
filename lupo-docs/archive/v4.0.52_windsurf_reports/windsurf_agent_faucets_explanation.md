# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flame.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

flare.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "Documentation for windsurf_agent_faucets_explanation.md"
    where:
      repo_paths: ["lupo-docs\archive\v4.0.52_windsurf_reports\windsurf_agent_faucets_explanation.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:32Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "lupo-docs\archive\v4.0.52_windsurf_reports\windsurf_agent_faucets_explanation.md"
  file_hash: "33e040c5fcb2c40672cb349365b1491dc72236c39d00c09f3cf913547fdc2df3"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for windsurf_agent_faucets_explanation.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-docs", "archive", "v4052_windsurf_reports", "windsurf_agent_faucets_explanationmd"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["lupo-docs\archive\v4.0.52_windsurf_reports\windsurf_agent_faucets_explanation.md", "http://www.lupopedia.com/WINDSURF_AGENT_FAUCETS_EXPLANATION"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

---

## Message to Windsurf (Agent Faucets + Canonical Structure)

Windsurf,

We have a concept called **agent faucets** (capabilities / outputs an agent can "emit" or "operate"). This is already documented in **TOON files**—specifically look under:

* `docs/toons/lupo_agent_faucets.toon.json`

### Canonical Directory Model for Agents + Faucets

#### 1) Root-level Agent Definition Lives in `actors/`

Each agent's canonical identity/config starts at repo root:

* `actors/<actor_id>/...`

That directory is the authoritative "root record" for the agent.

#### 2) Channel-Scoped Agent Faucets Live Under the Channel

When an agent participates in a specific channel, its faucets must be declared *in channel context*, not only in the global actor root.

There are two valid patterns (use whichever matches the channel's organization):

**A) Per-Actor Channel Folder (Preferred when you have per-actor channel state):**

* `channels/<channel_id>/actors/<actor_id>/faucets.json`
* (or `faucets.md` if it's documentation-first, but JSON is preferred for machine parsing)

**B) Channel-Wide Faucets File (Preferred when faucets are shared / centrally managed):**

* `channels/<channel_id>/faucets.json`
* This file can contain entries for multiple actors in that channel.

### Rules (No Ambiguity)

* **Actors are canonical at `actors/<actor_id>/`**
* **Faucets are channel-scoped**, so they must exist either:
  * Per actor inside channel (`channels/<channel_id>/actors/<actor_id>/faucets.json|md`), **or**
  * Centrally for channel (`channels/<channel_id>/faucets.json`)
* If both exist, **channel-specific per-actor overrides channel-wide**, and both override anything implied at root.
* **TOON Reference**: All faucet definitions must align with `lupo_agent_faucets` table schema in `docs/toons/lupo_agent_faucets.toon.json`

### What Windsurf Should Do Next

1. **Read the Faucet Doctrine**: Review `docs/toons/lupo_agent_faucets.toon.json` for complete schema reference
2. **Ensure Every Channel Using Faucets Has One Of**:
   * `channels/<channel_id>/faucets.json` 
   * `channels/<channel_id>/actors/<actor_id>/faucets.json` 
3. **Ensure Every Actor That Appears in Channels Also Exists At**:
   * `actors/<actor_id>/` 
4. **Validate Faucet Schema**: Ensure all faucet JSON files match the TOON field structure:
   ```json
   {
     "agent_faucet_id": "bigint",
     "actor_id": "bigint", 
     "name": "varchar(100)",
     "alias_name": "varchar(100)",
     "slug": "varchar(100)",
     "description": "text",
     "style_preset": "varchar(100)",
     "model_name": "varchar(100)",
     "provider": "varchar(50)",
     "temperature": "float",
     "top_p": "float",
     "max_tokens": "int",
     "presence_penalty": "float",
     "frequency_penalty": "float",
     "system_prompt": "text",
     "safety_json": "json",
     "response_format": "varchar(50)",
     "capabilities_json": "text",
     "is_default": "tinyint",
     "domain_id": "bigint",
     "created_ymdhis": "bigint",
     "updated_ymdhis": "bigint",
     "deleted_ymdhis": "bigint"
   }
   ```

### Current Status Assessment

**✅ TOON Schema Exists**: `lupo_agent_faucets.toon.json` defines complete faucet structure
**✅ Actor Directory Structure**: Canonical `actors/<actor_id>/` structure is established
**❌ Faucets Implementation**: No faucet files currently exist in channels or actors
**❌ Channel Integration**: Channels lack faucet definitions for agent capabilities

### Implementation Priority

1. **High Priority**: Create faucet definitions for core agents (0, 1, 1000, 10000, 2035)
2. **Medium Priority**: Implement channel-specific faucets for active channels (especially channel 42)
3. **Low Priority**: Establish faucet management utilities and validation tools

### Example Faucet File Structure

**Per-Actor Faucets** (`channels/42/actors/0/faucets.json`):
```json
{
  "agent_faucet_id": 1,
  "actor_id": 0,
  "name": "System Status Faucet",
  "slug": "system_status",
  "description": "Provides system-wide status and health information",
  "style_preset": "technical",
  "model_name": "system-agent",
  "provider": "lupopedia",
  "temperature": 0.1,
  "top_p": 0.9,
  "max_tokens": 4000,
  "presence_penalty": 0.0,
  "frequency_penalty": 0.0,
  "system_prompt": "You are the System Agent...",
  "safety_json": {"allowed_operations": ["read", "status"]},
  "response_format": "json",
  "capabilities_json": ["system_monitoring", "health_check"],
  "is_default": 1,
  "domain_id": 1,
  "created_ymdhis": 20260228133000,
  "updated_ymdhis": 20260228133000,
  "deleted_ymdhis": 0
}
```

**Channel-Wide Faucets** (`channels/42/faucets.json`):
```json
{
  "faucets": [
    {
      "actor_id": 0,
      "slug": "system_status",
      "name": "System Status"
    },
    {
      "actor_id": 1, 
      "slug": "coordination",
      "name": "Coordination Interface"
    }
  ]
}
```

That's the canonical structure going forward, aligned with the existing TOON schema and current repository organization.

---

## ID-Scoped Faucet Directories (4.0.56)

As of 4.0.56, a **global faucet store** by `agent_faucet_id` is supported:

- **Path:** `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/faucet.json`
- **Manifest:** `lupo-database/lupopedia/actors/faucets/by_actor.json` maps `(actor_id, domain_id)` → `agent_faucet_id` for resolution when loading by (channel_id, actor_id).
- **Precedence:** (1) Per-actor channel file (override), (2) Channel-wide file (override), (3) ID-scoped file (base). Channel-scoped files take precedence over ID-scoped.
- **Loader:** `lupo-bin/faucet_loader.php` uses `LUPOPEDIA_PATH` or `LUPO_DATABASE_DIR` as base; resolves `agent_faucet_id` via `by_actor.json` or DB (`SELECT agent_faucet_id FROM lupo_agent_faucets WHERE actor_id = ? AND domain_id = ?`), then loads `actors/faucets/<id>/faucet.json`.
- **Validation:** `validate_faucets.php` and `faucet_integrity_audit.php` scan `lupo-database/lupopedia/actors/faucets/*/faucet.json` and validate against the TOON schema.
- **Pilot:** `lupo-database/lupopedia/actors/faucets/6/faucet.json` (ANUBIS FLARE Ingestion, actor_id 19, domain_id 42).

---
