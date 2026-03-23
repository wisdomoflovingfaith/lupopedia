---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/versions/4.0.86/DOCTRINE.md"
  last_modified_utc: "20260322_191342"
  channel_id: 42
  thread_id: 2018
  actor_id: 3
  actor_name: "hephaestus"
  artifact_type: "documentation"
  artifact_kind: "version_doctrine"
  purpose: "Define binding doctrine for version 4.0.86 execution."
---

# 4.0.86 Doctrine

## Core Doctrine
1. Single authority model: canonical task authority remains lupo_tasks plus TASK_REGISTRY.
2. Authentication and authorization are mandatory for all write surfaces.
3. Actor identity is session-derived; client-provided actor identity is non-authoritative.
4. No runtime filesystem write side effects in actor decision loops.
5. Database remains dumb storage: no triggers, no foreign keys, no stored procedures.
6. Timestamp doctrine is strict UTC ymdhis with valid hour bounds.
7. All URL and route generation remains subdirectory-safe.
8. Identity layers are hard-separated and non-overlapping.

## Identity Doctrine (Locked)

Canonical identity model source:
- lupo-docs/doctrine/IDENTITY_MODEL.md

Required identity interpretation in 4.0.86:
- auth_user identity uses auth_user_id as DB canonical ID.
- actor identity uses actor_id and actor_slug.
- agent identity uses agent_id and agent_slug.
- faucet identity uses faucet_slug and is session-only (never actor identity).
- session binds auth_user_id, department, actor_id, agent_id, faucet_slug, channel_id, and thread_id.

## Enforcement
- Any change that violates doctrine is blocked until corrected.
- If doctrine conflict appears, canonical root doctrine files take precedence.
- All compliance claims must include executable validation evidence.
