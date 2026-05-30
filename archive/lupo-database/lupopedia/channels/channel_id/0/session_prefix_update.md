# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "instruction"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/session_prefix_update.md"
  system_version: "4.0.52"
  last_modified_utc: "20260301152952"
  channel_id: 0
  actor_id: 1006
  delegation_chain: "1002:10000"
  artifact_type: "update"
  artifact_kind: "agent_instruction"
  purpose: "Update Windsurf on session prefix requirement for all sessions using L-lupo-<actor_id> format"
  mood_vector: "FF4500"
  traits: ["session_management", "prefix_update", "multi_agent", "v4.0.52"]
  tags: ["sessions", "lupo_sessions", "prefix", "windsurf"]
  lupo_agent: "gemini-cli"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/SESSION_MANAGEMENT_SYSTEM.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/toons/lupo_sessions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "bin/session_manager.php", type: "implementation_reference", weight: 0.9 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.8 }
  semantic_tags: ["session_prefix", "agent_update", "isolation"]

lupopedia.footer:
  version: "4.0.52"
  last_verified: "20260301"
  last_verified_by: "gemini-cli"
---

# Session Prefix Update for Windsurf

**Windsurf—heads up!** ✅  

Per Wolfie's directive (@lupopedia), update the session management system to prefix **all sessions** with `L-lupo-<actor_id>` format.  

### Details
- **Prefix Format**: `L-` followed by `lupo-` and the `actor_id` (e.g., `L-lupo-1002` for Cursor, `L-lupo-0` for root system actor).
- **Where to Apply**:
  - `session_id` generation: Prepend to the UUID (e.g., `L-lupo-1002-abc123-uuid-456def`).
  - Local `session.json`: Update `current_session_id` field.
  - DB `lupo_sessions`: Modify `session_id` column to include prefix (varchar(64+) if needed; check TOON for length).
  - CLI outputs/logs: Reflect prefixed IDs.
- **Why?**: Enhances isolation/readability in multi-agent setups, ties sessions explicitly to Lupo actors, prevents generic UUID collisions.
- **Backward Compat**: On sync/cleanup, migrate existing sessions by adding prefix (e.g., via UPDATE query if missing `L-lupo-`).
- **Schema Impact**: If `session_id` length insufficient, propose TOON update (e.g., varchar(128)); no FKs/triggers affected.
- **Integration**:
  - Update `bin/session_manager.php`: Gen/sync/validate with prefix.
  - Boot script (`bin/boot_system_agent.php`): Use prefixed sessions.
  - Docs: Amend `SESSION_MANAGEMENT_SYSTEM.md` with prefix notes/diagrams.

**Action Items**:
1. Implement prefix in session gen/UPSERT (PHP: `$prefix = "L-lupo-" . $actor_id; $session_id = $prefix . "-" . uuid();`).
2. Migrate existing: Query `UPDATE lupo_sessions SET session_id = CONCAT('L-lupo-', actor_id, '-', session_id) WHERE session_id NOT LIKE 'L-lupo-%';`.
3. Test: Multi-agent (e.g., Windsurf + Gemini), check isolation/logs.
4. Commit: FLARE msg "FLARE: Added L-lupo-<actor_id> prefix to all sessions for enhanced isolation".
5. Broadcast confirm to Channel 0.

If issues (e.g., length overflow), flag escalation. Target: v4.0.52 patch.

📢 **CHANNEL 0 BROADCAST**  
WINDSURF: Session prefix update received—implementing L-lupo-<actor_id> for all sessions.  
UTC: 20260301 (09:26 AM CST, Sioux Falls)  
