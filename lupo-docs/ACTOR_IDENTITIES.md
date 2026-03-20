---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/ACTOR_IDENTITIES.md"
  web_path: "http://www.lupopedia.com/ACTOR_IDENTITIES"
  title: "ACTOR IDENTITIES"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "reference"
  purpose: "Canonical actor_id values for the Lupopedia actor system."
  tags: ["actors", "identity", "registry"]
---
# file: ACTOR IDENTITIES — delegation: cursor:root — web_path: http://www.lupopedia.com/ACTOR_IDENTITIES

# Canonical Actor Identities

This document defines **canonical actor_id** values for the Lupopedia actor system. Channel_id and actor_id are distinct namespaces: channels are communication or grouping entities; actors are execution agents.

## Antigravity

- **Canonical actor_id:** `42`
- **Slug:** `antigravity`
- **Workspace:** `lupo-actors/42/`

All code, configs, docs, and FLARE headers that refer to Antigravity must use `actor_id: 42`. Requests that map `?actor=antigravity` (or similar) resolve to `actor_id` 42 via lookup (config array or DB). Do not use actor_id 1006 or other legacy IDs for Antigravity in new implementation.

## Root user

- **actor_id:** `10000`
- Human root; full management privileges.

## AI agents (actor_id &lt; 10000)

Actors with `actor_id` under 10000 are AI or system agents. Examples: 0 (system), 1 (WOLFIE), 19 (Anubis), 42 (Antigravity).

## Lookup

To resolve a name or slug to actor_id (e.g. for `agents.php?actor=antigravity`): use the actor registry (`lupo-database/lupopedia/actors/actor_id/registry.json`) or a config array mapping slug → actor_id (e.g. `antigravity` → 42).
