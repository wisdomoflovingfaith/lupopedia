---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/doctrine/IDENTITY_MODEL.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/IDENTITY_MODEL.md"
  last_modified_utc: "20260323_235800"
  channel_id: 42
  thread_id: "1001"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "doctrine"
  artifact_kind: "system_law"
  purpose: "Canonical identity model for auth_user, actor, agent, faucet, and session bindings."
  references:
    - "lupo-channels/58/threads/actor-pairing-discussion/20260323_175000_wolfie_canonical_role_layer_hierarchical_resolution.md"
    - "lupo-docs/doctrine/ROSE_DOCTRINE.md"
    - "lupo-docs/versions/4.0.86/WHAT_TO_DO_NEXT_SESSION.md"
  tags: ["identity", "system_law", "actor", "agent", "faucet", "session", "4.0.86"]
---

# Canonical Identity Model

## 1. Objective

This doctrine defines and locks non-overlapping identity layers across Lupopedia:
- clear separation of entity types
- canonical identifiers (DB vs human-readable)
- filesystem alignment
- session binding rules

This doctrine is system law.

## 2. Identity Model (Locked)

### AUTH_USER
- DB canonical: auth_user_id
- readable: username or email
- meaning: human identity in the system

### ACTOR
- DB canonical: actor_id
- readable canonical: actor_slug
- filesystem path: lupo-actors/<actor_slug>/
- meaning: canonical role identity independent of execution surface

### AGENT
- DB canonical: agent_id
- readable canonical: agent_slug
- filesystem path (preferred): lupo-agents/<agent_slug>/
- backward compatibility: numeric alias path may exist (example: lupo-agents/3/)
- meaning: implementation of actor behavior

### FAUCET
- canonical: faucet_slug
- stored in sessions only
- never treated as actor
- never used as identity
- meaning: execution surface (Cursor, Windsurf, VS Code, Antigravity, and others)

### SESSION
A session binds runtime execution context using:
- auth_user_id
- department
- actor_id
- agent_id
- faucet_slug
- channel_id
- thread_id

## 3. Core Rule

ID = canonical database identity.
SLUG = canonical human and filesystem identity.

This applies to:
- actors
- agents
- optionally departments

## 4. Hard Separation Rules

- actor != agent
- agent != faucet
- faucet != identity
- auth_user != actor

Merging identity layers is forbidden.

## 5. Filesystem Alignment

Required canonical paths:
- lupo-actors/<actor_slug>/
- lupo-agents/<agent_slug>/

Numeric agent paths are allowed only for backward compatibility and migration phase.

## 6. System Impact

This doctrine resolves:
- identity ambiguity
- agent vs actor confusion
- faucet misclassification
- session inconsistency

## 7. Implementation Directives

### HEPHAESTUS
- ensure agent_slug exists in agent definitions
- ensure filesystem alignment with slug-based paths
- maintain numeric alias paths for compatibility where needed

### ATHENA
- update architecture documentation to this model
- align Channel 58 actor model references with this doctrine

### HERMES
- use actor_slug for human-readable routing and actor_id for system routing
- do not route by faucet identity

### LILITH
- validate separation rule compliance
- flag any actor/faucet misuse and identity collapse

## 8. Validation Across Channels 58 to 61

- Channel 58: actor model and canonical role layer must remain actor-first
- Channel 59: ROSE packet and dialogue artifacts may reference actor_slug but must preserve actor_id source
- Channel 60: agent system docs and implementations must keep actor/agent/faucet separation
- Channel 61: context graph routing targets actors, not faucets

## 9. Hard Rules

- must not merge identity layers
- must not remove DB IDs
- must introduce slugs where missing
- must maintain deterministic behavior

## 10. Status

status: IDENTITY_MODEL_LOCKED
scope: SYSTEM_WIDE
