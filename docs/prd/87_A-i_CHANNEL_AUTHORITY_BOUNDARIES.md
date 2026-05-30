---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/87_A-i_CHANNEL_AUTHORITY_BOUNDARIES.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/87_A-i_CHANNEL_AUTHORITY_BOUNDARIES.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/87_channel_authority_boundaries.toon
  atoms_toon: null
  transcript_jsonl: 0/development/channel-authority-boundaries
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_87_A-i
  title: 'PRD 87: Channel Authority & Department Boundaries'
  summary: Defines channel as department-scoped execution boundary with enforcement rules and architectural definitions for maintaining isolation and preventing cross-domain contamination.
---
# PRD 87: Channel Authority & Department Boundaries

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Overview

This PRD defines the structural rule that channels are department-scoped execution boundaries, establishing the enforcement rules and architectural definitions necessary to maintain isolation and prevent cross-domain contamination in the Lupopedia system.

## Department Scoping

### Channel = Department Boundary (Structural Rule)

A channel is a **department-scoped execution boundary**.

Every channel MUST define and enforce:

* department boundary (which domain this channel belongs to)
* actor permissions (which actors are allowed)
* agent pairing rules (which agents a human may use)

---

### Enforcement Rules

* A channel MUST NOT contain actors from multiple unrelated departments
* An actor MUST NOT enter a channel outside its department
* A user MUST NOT pair with agents not allowed by the channel
* Cross-domain execution inside a channel is FORBIDDEN

---

### Architectural Definition

Channel = Context Isolation + Department Boundary + Actor Permissions + Agent Scope

---

### Failure Mode

Without this rule:

* cross-domain contamination occurs
* unauthorized agents influence execution
* context isolation breaks
* system becomes non-deterministic

---

### Relationship to System Doctrine

This rule connects:

* channel isolation
* actor isolation
* agent pairing
* department authority

---

## Cross-References

* PRD 02_C-i_CHANNELS_DISCUSSIONS.md - Channels, Threads, and Discussions Database Tables
* PRD 25_A-i_DEPARTMENTS_SYSTEM.md - Departments System
* PRD 32_A-i_ACTOR_AUTHORITY_AGENT_ROLES.md - Actor Authority and Agent Roles

---

**Status: Active - Extracted from PRD 02_C-i_CHANNELS_DISCUSSIONS.md for better modularity and maintainability.**
