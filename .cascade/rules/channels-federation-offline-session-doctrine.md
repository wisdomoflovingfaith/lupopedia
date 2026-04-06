---
lupopedia.init:
  file_identity: "channels-federation-offline-session-doctrine.md"
  artifact_type: "cascade_rule"
  artifact_kind: "doctrine"
  namespace: "cascade"
  system_version: "4.0.76"
  orchestrator_actor: "cascade"
  delegation_chain: "cascade:captain"

lupopedia.headers:
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "cascade_rule"
  file_path_from_root: ".cascade/rules/channels-federation-offline-session-doctrine.md"
  last_modified_utc: "20260406"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/channels-federation-offline-session-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "cascade_doctrine"
  purpose: "Cascade-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "CTX001"
      rule_text: "All systemic operations and tracking operate through specific Channels and Federation Nodes. Offline interactions or non-database IDE contexts must fallback to embedding session metadata securely within L-LUPO-* file headers."
      scope: "all_agents"
      category: "context"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260406"
    last_reviewed_by: "cascade"
    last_reviewed_date: "20260406"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260406"
  last_verified_by: "cascade"
  orchestrator: "cascade"
  next_action:
    - "Keep in sync with canonical root rules"
---

# CTX001: Context Boundaries (Channels, Federation & L-LUPO Offline Sessions)

## Core Principle

Lupopedia limits and silos information interactions, states, and operations logically into distinctly tracked **Channels**, **Federation Nodes**, and **Sessions**. Because IDE agents regularly operate by editing raw files without an active PHP application runtime loop connecting them to the database, Lupopedia has strict mechanisms to track these offline contexts.

## Architectural Constraints

1. **Channels (`lupo_channels`) and Federation Nodes (`lupo_federation_nodes`)**:
   - Every operation implicitly belongs to a discrete conversational or operational channel represented by `channel_id`.
   - The location or domain of that channel exists across the boundaries documented by `federation_node_id`. (For local operations, this maps to `federation_node_id: 1`).
   
2. **Offline Mode Session Tracking (`L-LUPO-*` Files)**:
   - When an IDE agent invokes workflows or reads user interactions without pulling a database row from `lupo_sessions`, the application provides offline state continuity via artifact files named under the `L-LUPO-*` convention.
   - **Header Embedding**: The headers inside these `L-LUPO-*` files hold explicit Session Data (such as user tracking hashes, `session_id`, and timeline states) that serve as a direct analog to the `lupo_sessions` table.
   - IDE Agents **must extract, retain, and respect the session context parameters** stored in these headers to accurately interpret the workspace context.

## Non-Negotiable Violations

- **Context Bleed**: Intermingling data across federation nodes or failing to register an operation mapping back correctly to a `channel_id`. 
- **Session Erasure**: Destructively modifying `L-LUPO-*` files in a manner that removes or invalidates the critical offline session data embedded in their frontmatter/headers.

