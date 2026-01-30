# Migration Note: livehelp_users
# Status: IMPORTED -> DROPPED
# Replacement: lupo_auth_users (identity/authentication)
# Related: lupo_actors, lupo_operators, lupo_actor_properties

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

Code
lupo_auth_users       -> authentication + credentials
lupo_actors           -> unified identity layer
lupo_operators        -> operator-specific metadata
lupo_actor_properties -> presence, device, and behavioral metadata
Only meaningful identity data is imported.
The legacy table is dropped after migration.

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
