# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\livehelp_users_migration.md"
  file_hash: "6d1dd6926247b95accdf73173b5738c8f02ad53287354fde5d5eecff8f8fc736"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_users_migration.md"
  file_hash: "28939cd502d23724707af42e244a73406e3a9813bab63b10ea2d76d3bad2aba9"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_users_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_users_migrationmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flare.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_users_migration.md",
  file_hash: "d14d98a8fd8139edeb51b828a7574678ec30ed574fee482697d3c776443c1a0f"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_users → lupo_auth_users/lupo_actors table mapping",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_users", "critical"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_users", "#auth_users", "#actors", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 3, outbound_count: 5, centrality_score: 0.88 }
}

flip.footer: {
  inbound_edges: [
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "install.php", type: "uses", weight: 0.8, hashtag: "#installer" }
  ],
  outbound_edges: [
    { to: "docs/doctrine/database/auth_users.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "docs/doctrine/database/actors.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "docs/doctrine/migrations/operator_to_roles_migration.md", type: "references", weight: 0.9, hashtag: "#permissions" },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.8, hashtag: "#source" },
    { to: "docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md", type: "references", weight: 0.7, hashtag: "#audit" }
  ],
  referenced_by_actors: [1001, 1002, 10000],
  references: {
    by_files: ["database/migrations/import_from_old_crafty_syntax.sql", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 1002, 10000]
  },
  semantic_tags: ["livehelp_users_mapping", "auth_identity", "actor_model", "operator_import", "visitor_import"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_users
# Status: IMPORTED -> DROPPED
# Replacement: lupo_auth_users (identity/authentication)
# Related: lupo_actors, lupo_actor_properties; permissions use 3-level role system (lupo_actor_channel_roles, lupo_department_roles), not lupo_operators (removed).

# 1. Summary
livehelp_users was Crafty Syntax's legacy table for storing operator login accounts and visitor records in the same table.
It mixed:

authentication

operator metadata

visitor metadata

timestamps

routing state

department assignments

session state

...into a single structure.

Lupopedia replaces this with a real identity system:

- **lupo_auth_users** — authentication and credentials (username, display_name, email, password_hash, auth_provider, provider_id, last_login_ymdhis). On import, **auth_user_id = 10000 + livehelp_users.user_id** so that human IDs stay in the reserved range (actor_id 0–9999 = agents/system only; humans ≥ 10000).
- **lupo_actors** — unified identity layer (one row per human/agent/service; actor_id = auth_user_id for imported users, so imported human actor_id ≥ 10000).
- **lupo_actor_properties** — presence, device, and behavioral metadata.
- **Permissions** — there is no lupo_operators table. Operator/staff permissions use the **3-level role system**: (1) **lupo_actor_channel_roles** (channel-scoped: captain, administrator, monitor); (2) **lupo_department_roles** (department-scoped); (3) system (department_id = 0 = global admin). Resolution order: channel → department → system. The install wizard assigns captain on personal channels and on channel_id = 1 (Administration) for Crafty admins (livehelp_users.isadmin = 'Y'). See docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md and docs/doctrine/database/actor_channel_roles.md.

Only meaningful identity data is imported. The legacy table is dropped after migration.

# 2. What the Legacy Table Actually Did
Crafty Syntax stored both operators and visitors in the same table:

Operator fields
username

password (legacy hash)

email

displayname

isoperator

auth_provider / provider_id (rarely used)

Visitor fields
username (auto-generated)

password (blank)

email (blank)

isoperator = 'N'

Session-like fields
lastaction (UNIX timestamp)

sessionid

ipaddress

Department assignment
department (legacy routing)

This table was a hybrid identity/session/routing artifact, not a real authentication model.

# 3. Why Lupopedia Imports Only the Identity Layer
Lupopedia's identity model is:

actor-centric

provider-aware

federated

lifecycle-aware

device-aware

multi-agent compatible

The only durable data in livehelp_users is:

username

display name

email

password hash (if present)

auth provider + provider ID

last login timestamp

Everything else is:

ephemeral

routing state

session state

UI state

legacy operator metadata

...and is handled by other tables in the new system.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_users ENGINE=InnoDB;
ALTER TABLE livehelp_users CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
5. Importing Operators -> lupo_auth_users
The first INSERT imports operators only:

Code
WHERE u.isoperator = 'Y'
This ensures:

operator accounts are created first

no visitor accounts pollute the auth table

usernames remain unique

operator identity is preserved

The NOT EXISTS clause ensures idempotency.

Field mapping
Code
username        -> username
displayname     -> display_name
email           -> email (NULL if blank)
password        -> password_hash (NULL if blank)
auth_provider   -> auth_provider (NULL if blank)
provider_id     -> provider_id (NULL if blank)
lastaction      -> last_login_ymdhis (converted from UNIX timestamp)
Lifecycle fields are synthesized:

Code
created_ymdhis = now
updated_ymdhis = now
is_active = 1
is_deleted = 0
6. Importing Non-Operators (Visitors) -> lupo_auth_users
The second INSERT imports any remaining users that do not already exist in lupo_auth_users.

This preserves:

legacy visitor identities (optional)

usernames that may be referenced by transcripts

compatibility with historical data

Visitors are imported with:

no password

no provider

no email

no profile image

This is intentional -- visitors are not operators and do not authenticate.

# 7. Why the Migration Uses Two INSERTs
Reason 1 -- Operators must be imported first
Operators have:

passwords

emails

provider IDs

last login timestamps

Visitors do not.

Reason 2 -- Operators and visitors share usernames in some installs
Some legacy installs reused usernames across roles.
Importing operators first ensures the correct identity wins.

Reason 3 -- Idempotency
Each INSERT uses:

Code
NOT EXISTS (SELECT 1 FROM lupo_auth_users WHERE username = u.username)
This makes the migration safe to run multiple times.

# 8. Mapping Summary
Legacy -> New
Legacy Field	lupo_auth_users Field	Notes
username	username	preserved
displayname	display_name	preserved
email	email	NULL if blank
password	password_hash	NULL if blank
auth_provider	auth_provider	NULL if blank
provider_id	provider_id	NULL if blank
lastaction	last_login_ymdhis	UNIX -> YmdHis
isoperator	determines import order	operators first
Added fields
Code
profile_image_url = NULL
created_ymdhis = now
updated_ymdhis = now
is_active = 1
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
sessionid

ipaddress

department

isonline

status

routing state

UI state

These belong to other tables in Lupopedia.

# 9. Doctrine Notes
This migration is a perfect example of:

Separating identity from session state
Crafty Syntax mixed:

identity

routing

session

UI state

Lupopedia separates them cleanly.

Preserving durable identity
We keep:

usernames

display names

emails

password hashes

provider IDs

last login timestamps

Modernizing authentication
We add:

lifecycle fields

soft-delete

provider-aware identity

federated identity model

The Slope Principle
We do not attempt to import:

session state

routing state

operator presence

department assignments

These belong to other subsystems.

# 10. Final Decision
Code
livehelp_users -> IMPORTED -> DROPPED
Operators and visitors imported into lupo_auth_users.
Session and routing data discarded.