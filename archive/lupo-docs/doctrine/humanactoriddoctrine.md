---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/HumanActorIdDoctrine.md"
  web_path: "http://www.lupopedia.com/doctrine/HumanActorIdDoctrine"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: governance
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Human Actor ID Doctrine — session: L-LUPO-WOLFIE-CURSOR — delegation: wolfie:cursor:root — web_path: http://www.lupopedia.com/doctrine/HumanActorIdDoctrine

# Human Actor ID Doctrine (v1.0)

**PURPOSE:** Human actors must have `actor_id` starting at or above **1000** for clarity and lineage safety. No human actor may exist with `actor_id < 1000`.

---

## Rule

1. **No human actors below 1000**  
   Every row in `lupo_actors` with `actor_type = 'human'` MUST have `actor_id >= 1000`.

2. **Allocation**  
   Lupopedia does **not** use `AUTO_INCREMENT` on `lupo_actors`. IDs are supplied explicitly from:
   - **lupo_registry_open** (entity_type = 'actor', use slots reserved for human users, e.g. 1001+ in current seed), or  
   - Reserved constants (e.g. root = 1000).  
   When allocating a new human actor, the application MUST choose an `actor_id >= 1000` from the registry or allocation logic.

3. **Verification**  
   Before treating this doctrine as satisfied, ensure no human actor exists below 1000:

   ```sql
   SELECT actor_id, actor_name, actor_type
   FROM lupo_actors
   WHERE actor_type = 'human' AND actor_id < 1000;
   ```
   This MUST return no rows. (Use table prefix if not `lupo_`.)

---

## Schema notes

- **Table:** `lupo_actors` (with table prefix). Primary key is `actor_name`; `actor_id` is unique, not auto-increment.
- **No AUTO_INCREMENT:** Do not use `ALTER TABLE lupo_actors AUTO_INCREMENT = 1000`; the table does not use AUTO_INCREMENT for `actor_id`.
- **No id_allocator table:** Lupopedia uses **lupo_registry_open** for tracking available IDs. Ensure registry seed and application logic never allocate `actor_id < 1000` for human actors.

---

## Legacy or reassignment

If any human actor is found with `actor_id < 1000`, reassign them to an ID >= 1000 (and update any foreign references) before considering the doctrine satisfied. This is an application-level operation; no generic migration is provided here.
