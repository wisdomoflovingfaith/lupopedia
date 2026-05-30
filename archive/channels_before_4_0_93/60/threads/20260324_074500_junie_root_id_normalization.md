---
lupopedia.headers:
  version_when_written: "4.0.86"
  lupopedia.schema: "documentation"
  file_path_from_root: "channels/60/threads/20260324_074500_junie_root_id_normalization.md"
  web_path: "http://www.lupopedia.com/channels/60/threads/20260324_074500_junie_root_id_normalization"
  questions_toon: null
  channel_id: 60
  actor_id: 108
  actor_name: "junie"
  delegation_chain: "junie:root"
  artifact_type: "status_report"
  artifact_kind: "documentation"
  purpose: "Document normalization of Root auth_user_id to 0 for v4.0.86"
  tags: ["root", "id", "normalization", "v4.0.86", "junie"]

lupopedia.edges:
  outbound_edges:
    - { to: "database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql", type: "references" }
    - { to: "database/lupopedia/actors/actor_id/registry.json", type: "references" }
    - { to: "docs/database/lupopedia/tables/active/lupo_auth_users.md", type: "references" }

lupopedia.footer:
  version: "4.0.86"
  last_verified: "20260324"
  last_verified_by: "junie"
---
# file: root_id_normalization — delegation: junie:root — web_path: http://www.lupopedia.com/channels/60/threads/20260324_074500_junie_root_id_normalization

### Status Report: Root User ID Normalization (v4.0.86)

**Summary:**
As of v4.0.86, the root user's `auth_user_id` has been normalized to `0` across all database seeds, actor registries, and documentation. This change resolves contradictions where the root user was previously identified as `1000` or `10000` in different subsystems.

**Changes:**
- **Database Seeds:**
  - `seed_actors_agents_4.0.45.sql`: Human root actor (ID 1000) now explicitly references `auth_user_id = 0`. IDE agents' `paired_actor_id` updated from `1000` to `0`.
  - `seed_registry_comprehensive_4.0.45.sql`: Root human actor registry entry moved to ID 0. IDE agents' metadata updated to `paired_actor_id: 0`.
  - `seed_rules_doctrine_4.0.68.sql` & `seed_fallback_rule_4.0.69.sql`: Rule targets applied by root now use `applied_by_actor_id = 0`.
- **Actor Registries:**
  - `database/lupopedia/actors/actor_id/registry.json`: Normalized `human_context.auth_user_id` to `0` for all actors associated with the root user.
  - `database/lupopedia/actors/108/registry.json`: Junie's registry updated to reference `auth_user_id: 0`.
- **Documentation:**
  - `lupo_auth_users.md`: Explicitly documented that the Root User ID is 0.
  - `README.md` & `AGENTS.md`: Updated identity tier definitions to include the special exception for `auth_user_id: 0` as the Root human operator.

**Rationale:**
Normalizing the root ID to `0` aligns with common system administration patterns while providing a definitive, non-conflicting identity for the master user in the Unified Identity Model. While 0 is often reserved for system actors, its use as the root human user's `auth_user_id` clearly distinguishes the "Master Human Operator" from other human-led actors starting at 1000.

**Verification:**
- Verified all 1000/10000 references in seed files have been updated or verified as legitimate non-root IDs.
- Confirmed documentation matches the implemented schema changes.

**Validated by:** Junie (Actor 108)
**Date:** 2026-03-24
