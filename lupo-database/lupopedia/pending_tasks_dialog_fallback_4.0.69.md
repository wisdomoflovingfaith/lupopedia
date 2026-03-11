---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/pending_tasks_dialog_fallback_4.0.69.md"
  last_modified_utc: "20260312"
  system_version: "4.0.69"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  artifact_type: "dialog_message_fallback"
  artifact_kind: "pending_tasks"
  purpose: "Offline fallback: pending 4.0.69 tasks when DB unavailable. Not stored in lupo_dialog_messages."
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  channel_id: 42
  paired_actor_id: 1000
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
---
# file: Pending tasks dialog fallback 4.0.69 — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor)

**speaker:** LILITH (via Cursor/Wolfie)  
**target:** @cursor, @antigravity, maintainers  
**message:** Pending tasks for 4.0.69 after install schema consolidation. Use this file when offline; DB remains source of truth when available.  
**mood_RGB:** 4169E1  
**created_ymdhis:** 20260312000000

---

## Install SQL (done)

- `install_new_lupopedia.sql` now includes all LILITH implementation prompt schema:
  - `lupo_actor_traits`: added `federation_node_id`, `created_by_actor_id`, index `lupo_actor_traits_idx_federation`
  - `lupo_edge_type_definitions`: new table (edge_type_definition_id PK, edge_type UNIQUE, domain, description, allowed_left/right_object_types, is_bidirectional, semantic_meaning, created_ymdhis, created_by_actor_id)
  - `lupo_action_authorization`: new table (action_authorization_id PK, action_key UNIQUE, description, required_trait_keys/capabilities/role_keys text, requires_all_conditions tinyint, created_ymdhis, created_by_actor_id)
  - `lupo_dialog_messages`: added `source_faucet_slug`, `source_faucet_instance_id`, index `lupo_dialog_messages_idx_faucet`
  - `lupo_sessions`: added `faucet_slug`, `faucet_instance_id`, index `lupo_sessions_idx_faucet`
  - `lupo_federation_nodes`: added `node_type` (default 'local'), `allows_foreign_traits` (default 1)
- Upgrade path comment at top of install: Crafty Syntax 3.7.5 → Lupopedia 4.0.x only; no Lupopedia→Lupopedia until 4.1.0.

---

## Pending (code and seeds)

1. **TraitEnforcer class** — `lupo-includes/classes/TraitEnforcer.php`: `actorHasTrait($actor_id, $trait_key, $federation_node_id)`, `isActionAuthorized($actor_id, $action_key, $channel_id)`; PDO_DB only; PHP 5.3 compatible.
2. **Pre-action hooks** — In dialog send and other kernel operations, call TraitEnforcer before performing action; reject if not authorized.
3. **Session faucet tracking** — On session create, set `faucet_slug` and `faucet_instance_id` from runtime (e.g. 'cursor', 'antigravity').
4. **Message faucet tracking** — When creating `lupo_dialog_messages`, set `source_faucet_slug` and `source_faucet_instance_id` from current session/faucet.
5. **SessionCustodian** — Optional tool for Antigravity: audit/correct `lupo-database/sessions/*.md` (e.g. paired_actor_id drift); report only or auto-correct with dry_run.
6. **Seed data** — Seed kernel actor traits into `lupo_actor_traits` (explicit actor_trait_id); seed core edge types into `lupo_edge_type_definitions`; seed core actions into `lupo_action_authorization`. Use allocator or registry for IDs; timestamps set in PHP.
7. **One-time migration** — If applying to an existing DB that was installed before these columns/tables: run a single migration that ALTERs and CREATEs as in install; then record in `lupo_schema_migrations`. New installs get everything from install SQL only.
8. **Documentation** — TRAITS_DOCTRINE, EDGE_TYPE_SEMANTICS_DOCTRINE, AUTHORIZATION_DOCTRINE, FAUCET_TRACEABILITY_DOCTRINE, FEDERATION_NODE_TYPES_DOCTRINE; update IDENTITY_LAYERS_DOCTRINE, ActorFaucetOntology, COMMUNICATION_DOCTRINE as in LILITH prompt.
9. **TOONs** — TOON files updated from install: `lupo_actor_traits.toon`, `lupo_dialog_messages.toon`, `lupo_sessions.toon`, `lupo_federation_nodes.toon`; new `lupo_edge_type_definitions.toon`, `lupo_action_authorization.toon`. After applying migration to a live DB, run `python scripts/generate_toon_files.py` so TOONs match live schema if the script reads from DB.

---

## Reference

- Implementation directive: `prompts/cursor/20260312_lilith_implementation_prompt_traits_authorization.md`
- Install schema: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- Single-install doctrine: no Lupopedia→Lupopedia upgrade until 4.1.0.
