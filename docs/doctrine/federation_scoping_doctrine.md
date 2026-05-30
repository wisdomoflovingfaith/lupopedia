---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/federation_scoping_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/federation_scoping_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: federation
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: Federation Scoping Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/FEDERATION_SCOPING

# Federation Scoping Doctrine (v4.0.69)

This document clarifies how **federation_node_id**, **channel_id**, and **domain_id** are used across Lupopedia so that edges, channels, contents, and session files stay consistent and drift is avoidable.

---

## 1. federation_node_id

**Meaning:** **Domain / federation scope.** Identifies which federation node or “universe” an entity belongs to.

- **Used in:** `lupo_channels`, `lupo_federation_nodes`, `lupo_federation_categories`, `lupo_federation_category_map`, `lupo_actor_edges` (as `domain_id` in some tables), session files (`federation_node_id` in `lupopedia.session`), and other tables that need a federation/domain dimension.
- **Typical values:** 0 = system root / kernel; 1 = local Lupopedia node; 42 = Lupopedia core development (when used as node); 100+ = federated external nodes.
- **Scope:** “Which domain or federation does this row belong to?” Use when the primary axis is **federation/domain**, not conversation or collaboration.

---

## 2. channel_id

**Meaning:** **Collaboration / conversation scope.** Identifies the channel (e.g. chat room, dev channel, governance channel) where content, dialog, and tasks live.

- **Used in:** `lupo_channels`, `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_dialog_threads`, `lupo_dialog_messages`, `lupo_tasks`, `lupo_edges`, `lupo_contents` (when content is channel-scoped), session files (`channel_id` in `lupopedia.session`).
- **Typical values:** 42 = Lupopedia Development (general). Other IDs from channel registry.
- **Scope:** “Which channel is this message/task/content/role tied to?” Use when the primary axis is **collaboration context** (A2A-style work, threads, messages, tasks).

---

## 3. domain_id (in edge and actor tables)

**Meaning:** **Edge-domain or actor-edge context.** In `lupo_edges` and `lupo_actor_edges`, `domain_id` provides the **domain context for the edge** (which federation/domain the relationship is valid in), not the channel.

- **Used in:** `lupo_edges` (domain_id), `lupo_actor_edges` (domain_id), and any table that stores relationships with a domain dimension.
- **Scope:** “In which domain does this edge or actor–actor relation hold?” Distinct from **channel_id**, which is about conversation/collaboration. Content can inherit **channel scope** (e.g. content visible on channel 42) or **explicit federation scope** (e.g. content tagged with federation_node_id for cross-node visibility).

---

## 4. When to use which

| Need | Prefer | Example |
|------|--------|---------|
| “Which federation/domain does this channel or node belong to?” | **federation_node_id** | `lupo_channels.federation_node_id`, session `federation_node_id` |
| “Which channel is this message/task/role/content in?” | **channel_id** | `lupo_dialog_messages.channel_id`, `lupo_tasks.channel_id`, `lupo_actor_channel_roles.channel_id` |
| “In which domain does this edge or actor relation hold?” | **domain_id** (in edge tables) | `lupo_edges.domain_id`, `lupo_actor_edges.domain_id` |
| “Content scoped to a channel” | **channel_id** on content/edge | `lupo_contents.channel_id`, `lupo_edges.channel_id` |
| “Content or entity scoped to a federation node” | **federation_node_id** (or domain_id where used for domain) | `lupo_channels.federation_node_id`, `lupo_actor_edges.domain_id` |

---

## 5. Content: channel scope vs federation scope

- **Channel-scoped content:** Content that “lives” in a channel (e.g. Channel 42) uses **channel_id** on the content row or on edges that link content to the channel. Visibility and access follow channel membership and roles.
- **Federation-scoped content:** When content or entities must be visible across a **domain/federation** (e.g. a node), use **federation_node_id** (or the table’s domain column) to tag that scope. Do not overload **channel_id** for federation; keep channel = collaboration, federation_node/domain = domain.

---

## 6. Session files

Session files under `database/sessions/*.md` may include both:

- **channel_id** — which channel the session is operating in.
- **federation_node_id** — which domain/node the session is associated with.

Validators and reconciliation tools should treat both as required (or recommended) for consistency with `lupo_sessions` and with the rest of the architecture.

---

## References

- `docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md` — canonical architecture, channels, edges.
- `docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md` — session required fields.
- `docs/doctrine/EDGE_VOCABULARY_DOCTRINE.md` — edge types and object pairs.
