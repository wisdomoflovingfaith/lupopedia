# lupo_actor_channel_roles

**Purpose:** **Channel-scoped roles** for actors: who has which role on which channel. Role keys are `captain`, `administrator`, and `monitor`. This is the **first level** of the 3-level permission model (channel → department → system). Replaces legacy “operator on channel” assignment; there is no lupo_operators table.

**Schema:** See `docs/toons/lupo_actor_channel_roles.toon.json`. Primary key: `actor_channel_role_id`. Key columns: `actor_id`, `channel_id`, `role_key`. May include protocol/awareness fields (e.g. handshake_metadata_json, awareness_snapshot_json, protocol_completion_status) for agent join and handshake flows.

---

## Use and need

- **Permission checks:** Code that needs “is this actor staff on this channel?” queries lupo_actor_channel_roles for (actor_id, channel_id, role_key IN ('captain','administrator','monitor')). Captain has highest channel-level authority; administrator and monitor have reduced scope.
- **Channel 1 (Administration):** Global admin channel. Crafty admins (livehelp_users.isadmin = 'Y') get a row with (actor_id, channel_id=1, role_key='captain') so they retain admin access.
- **Personal channels:** Each imported Crafty operator gets a personal channel and one row here with role_key = 'captain' for that channel. Created by the install wizard in `InstallWizardChannels::createOperatorChannels()`.
- **Resolution order:** Permissions resolve channel first (this table), then department (lupo_department_roles), then system (department_id = 0).

---

## Mapping from Crafty Syntax

**Legacy:** There was no single “roles” table in Crafty. Operator–channel assignment and “is operator” were implied by livehelp_operator_channels and livehelp_users.isoperator. Lupopedia **does not** import into lupo_actor_channel_roles from legacy; the **install wizard** assigns roles after import.

**Migration:** See `docs/doctrine/migrations/operator_to_roles_migration.md`, `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md`. The wizard inserts captain on personal channels and captain on channel_id = 1 for Crafty admins. For existing DBs that had lupo_channel_roles, `database/migrations_legacy/migration_operator_to_actor_channel_roles.sql` copies data into lupo_actor_channel_roles (role_type → role_key).

**Note:** lupo_channel_roles (role_type) still exists in schema; some code uses lupo_actor_channel_roles (role_key). See docs/ACTOR_CHANNEL_ROLES_VS_CHANNEL_ROLES_ANALYSIS.md.
