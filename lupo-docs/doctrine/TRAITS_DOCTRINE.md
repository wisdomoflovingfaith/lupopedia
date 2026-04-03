---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/TRAITS_DOCTRINE.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "windsurf"
  artifact_type: "doctrine"
  artifact_kind: "traits"
  purpose: "Actor traits: definition, storage, federation scope, enforcement. Schema from install_new_lupopedia.sql; column names from TOON."
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260313"
  last_verified_by: "windsurf"
  version: "4.0.73"
  next_action:
    - "Update doctrine to reflect TraitEnforcer implementation in 4.0.73"
    - "Ensure trait storage and enforcement examples are current"
    - "Verify federation scope examples match actual implementation"
---
# file: Traits Doctrine — web_path: http://www.lupopedia.com/doctrine/TRAITS_DOCTRINE

# Traits Doctrine (v4.0.73)

## 1. Definition

**Traits** are intrinsic constraints or capabilities attached to an **actor**. They are stored in `lupo_actor_traits`. Traits are actor-scoped only; channel-local permissions are **roles** (`lupo_actor_channel_roles`).

## 2. Schema (install / TOON)

Table: `lupo_actor_traits`. Columns (from install): `actor_trait_id`, `actor_id`, `trait_key`, `trait_value`, `federation_node_id`, `created_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `metadata`. No AUTO_INCREMENT; explicit IDs. No FK, no triggers.

## 3. Federation scope

`federation_node_id` (default 1) scopes the trait to a federation node. Traits can follow the actor across nodes when allowed by `lupo_federation_nodes.allows_foreign_traits`.

## 4. Enforcement

- **TraitEnforcer** (`lupo-includes/classes/TraitEnforcer.php`): `actorHasTrait($actor_id, $trait_key, $federation_node_id)`.
- Kernel operations that require a trait MUST check via TraitEnforcer (or equivalent) before executing. See AUTHORIZATION_DOCTRINE.md.

## 5. References

- Identity layers: IDENTITY_LAYERS_DOCTRINE.md
- Authorization: AUTHORIZATION_DOCTRINE.md
- Install: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOON: `lupo-database/lupopedia/toon/lupo_actor_traits.toon`
