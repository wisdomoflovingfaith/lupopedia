---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "implementation_report"
  file_path_from_root: "channels/42/threads/2011/20260322_182000_hephaestus_actor_auth_user_relationship_schema_implementation_report.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2011/actor_auth_user_relationship_schema_implementation_report"
  questions_toon: null
  channel_id: 42
  thread_id: 2011
  task_id: "task_ch42_th2011"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "implementation_report"
  artifact_kind: "actor_auth_user_relationship_schema"
  purpose: "Implements canonical many-to-many auth_user to actor relationship schema for Thread 2011"
  tags: ["implementation_report", "schema", "actors", "auth_users", "many_to_many", "thread_2011"]

lupopedia.edges:
  outbound_edges:
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "implements", weight: 1.0, reason: "Canonical install schema updated" }
    - { to: "docs/versions/4.0.85/actor_auth_user_relationship_model.md", type: "documents", weight: 1.0, reason: "Relationship model documentation" }

lupopedia.footer:
  last_updated: "20260322_182000"
  thread_status: "completed"
---

# Thread 2011 Implementation Report

## Summary

Gap A from Thread 2011 review has been implemented at the schema layer.

Implemented objective:
- true many-to-many `auth_user` <-> `actor` mapping via dedicated join table

## Table Created

Added to install schema:
- `lupo_actor_auth_users`

Fields implemented:
- `actor_auth_user_id BIGINT PRIMARY KEY`
- `actor_id BIGINT NOT NULL`
- `auth_user_id BIGINT NOT NULL`
- `relationship_role VARCHAR(64) NOT NULL DEFAULT 'supporting_human'`
- `is_primary TINYINT NOT NULL DEFAULT 0`
- `routing_priority SMALLINT NOT NULL DEFAULT 100`
- `status VARCHAR(32) NOT NULL DEFAULT 'active'`
- `metadata_json JSON DEFAULT NULL`
- `created_ymdhis BIGINT NOT NULL DEFAULT 0`
- `updated_ymdhis BIGINT NOT NULL`
- `is_deleted TINYINT NOT NULL DEFAULT 0`
- `deleted_ymdhis BIGINT DEFAULT 0`

## Indexes Added

- unique `(actor_id, auth_user_id, relationship_role)`
- index `(auth_user_id, status)`
- index `(actor_id, status, is_primary, routing_priority)`

## Install SQL Updated

File updated:
- `database/lupopedia/mysql/install/install_new_lupopedia.sql`

Update type:
- canonical DDL extension only
- no removals of existing compatibility columns

Backward compatibility retained:
- `lupo_actors.auth_user_id` kept as required

## Doctrine Compliance Verification

Verified in implementation:
- no foreign keys added
- no triggers added
- no procedures/functions added
- BIGINT UTC lifecycle fields used
- deterministic ID expectation preserved (application-supplied keys)

## Documentation Updated

Added:
- `docs/versions/4.0.85/actor_auth_user_relationship_model.md`

Documentation includes:
- new relationship model explanation
- supporting-human modeling semantics
- routing priority behavior
- primary-support semantics (`is_primary`)

## Scope Guard

Not implemented in this task:
- PHP logic
- routing logic
- UI logic

This delivery is database schema and schema documentation only.
