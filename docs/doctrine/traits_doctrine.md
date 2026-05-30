---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/traits_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/traits_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: traits
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
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

- **TraitEnforcer** (`includes/classes/TraitEnforcer.php`): `actorHasTrait($actor_id, $trait_key, $federation_node_id)`.
- Kernel operations that require a trait MUST check via TraitEnforcer (or equivalent) before executing. See AUTHORIZATION_DOCTRINE.md.

## 5. References

- Identity layers: IDENTITY_LAYERS_DOCTRINE.md
- Authorization: AUTHORIZATION_DOCTRINE.md
- Install: `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOON: `database/lupopedia/toon/lupo_actor_traits.toon`
