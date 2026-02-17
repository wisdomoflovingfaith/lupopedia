# lupo_actor_departments

**Purpose:** Maps **actors to departments**: which actor belongs to which department, with an optional title/label per assignment. Used for department-based routing, UI grouping, and (with lupo_department_roles) department-scoped permissions.

**Schema:** See `docs/toons/lupo_actor_departments.toon.json`. Key columns: `actor_department_id`, `actor_id`, `department_id`, `title`, plus lifecycle fields (created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis).

---

## Use and need

- **Membership:** One row per (actor, department) assignment. An actor can be in multiple departments.
- **Title:** The `title` field holds an optional role label (e.g. “Support”, “Sales”) from legacy `extra`.
- **Department roles:** Actual permission roles within a department are in **lupo_department_roles**, not here. This table answers “who is in which department”; department_roles answers “what role do they have there”.
- **System department:** department_id = 0 is reserved (system); membership there is for global admin context.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_operator_departments`.

**Migration:** `docs/doctrine/migrations/livehelp_operator_departments_migration.md`, `import_from_old_crafty_syntax.sql`.

- **Field mapping:** recno → actor_department_id, user_id → actor_id, department → department_id, extra → title.
- **Lifecycle:** created_ymdhis, updated_ymdhis set at import; is_deleted = 0, deleted_ymdhis = NULL.
- **Result:** livehelp_operator_departments → IMPORTED → DROPPED. All operator–department mappings preserved in lupo_actor_departments.
