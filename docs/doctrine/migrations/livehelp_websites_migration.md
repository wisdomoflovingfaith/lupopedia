# Migration Note: livehelp_websites
# Status: IMPORTED -> DROPPED
# Replacement: lupo_federation_nodes
# Purpose: Seed the federation registry with legacy Crafty Syntax "websites"

# 1. Summary
livehelp_websites was Crafty Syntax's table for storing remote LiveHelp installations that were part of a multi-site deployment.
Each row represented:

a remote installation URL

a human-readable name

a default department for routing

a legacy livehelp_id

In Lupopedia, these become federation nodes -- the modern, doctrine-aligned representation of remote or peer installations.

The legacy table is imported into:

Code
lupo_federation_nodes
...and then dropped.

# 2. What the Legacy Table Actually Did
Each row in livehelp_websites represented:

Legacy Field	Meaning
id	Primary key
site_name	Human-readable name of the remote node
site_url	Base URL of the remote node
defaultdepartment	Legacy routing target
livehelp_id	Legacy federation identifier
This table powered:

cross-site routing

multi-site chat deployments

remote operator handoff

legacy federation logic

It was a primitive federation registry, but the concept was sound.

# 3. Why Lupopedia Uses lupo_federation_nodes
Lupopedia's federation model is:

URL-based

metadata-driven

lifecycle-aware

doctrine-aligned

extensible

safe for distributed systems

lupo_federation_nodes provides:

node_base_url

node_name

meta_json for legacy fields

lifecycle fields

soft-delete

trust levels

sync timestamps

This is a modern, durable, federated architecture.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_websites ENGINE=InnoDB;
ALTER TABLE livehelp_websites CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Add missing column to lupo_federation_nodes
Legacy Crafty Syntax stored a defaultdepartment, but the new table did not originally have a place for it.

We add:

Code
ALTER TABLE lupo_federation_nodes
    ADD COLUMN default_department_id BIGINT NULL AFTER node_base_url;
This preserves legacy routing without polluting the new schema.

Step 4 -- Import federation nodes
Code
INSERT INTO lupo_federation_nodes (
    federation_node_id,
    node_name,
    node_base_url,
    default_department_id,
    meta_json,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    id,
    site_name,
    site_url,
    defaultdepartment,
    JSON_OBJECT(
        'legacy_livehelp_id', livehelp_id
    ),
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    0,
    NULL
FROM livehelp_websites;
This preserves:

node identity

node URL

default department

legacy federation ID

# 5. Mapping Summary
Legacy -> New
Legacy Field	New Field	Notes
id	federation_node_id	preserved
site_name	node_name	preserved
site_url	node_base_url	preserved
defaultdepartment	default_department_id	new column
livehelp_id	meta_json	preserved as metadata
Added fields
Code
created_ymdhis = now
updated_ymdhis = now
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Preserving legacy federation intent
Crafty Syntax had a primitive federation model.
Lupopedia formalizes it.

Modernizing the federation registry
We add:

lifecycle fields

metadata JSON

trust levels

sync timestamps

soft-delete

The Slope Principle
We preserve:

URLs

names

default routing

legacy IDs

We do not attempt to reinterpret:

routing semantics

department logic

legacy federation behavior

These belong to higher-level systems.

# 7. Final Decision
Code
livehelp_websites -> IMPORTED -> DROPPED
Legacy multi-site entries preserved as federation nodes.
