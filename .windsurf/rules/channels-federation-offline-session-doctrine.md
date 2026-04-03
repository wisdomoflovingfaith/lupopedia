---
lupopedia.init:
  file_identity: "channels-federation-offline-session-doctrine.md"
  artifact_type: "windsurf_rule"
  artifact_kind: "doctrine"
  namespace: "windsurf"
  system_version: "4.0.76"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "windsurf_rule"
  file_path_from_root: ".windsurf/rules/channels-federation-offline-session-doctrine.md"
  last_modified_utc: "20260402"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/channels-federation-offline-session-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

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
    authored_date: "20260402"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260402"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260402"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
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

