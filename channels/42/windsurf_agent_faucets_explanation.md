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
