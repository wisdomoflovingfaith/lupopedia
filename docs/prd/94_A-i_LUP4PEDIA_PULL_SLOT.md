---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/94_A-i_LUP4PEDIA_PULL_SLOT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/94_A-i_LUP4PEDIA_PULL_SLOT.md
  status: development
  when_updated: '20260817224200'
  trust_tier: development
  questions_toon: null
  memory_toon: memory/development/development/1026/08/94_a_lup4pedia_pull_slot.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd-94-lup4pedia-pull-slot
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 94_A_84_A_04_A
  title: 'PRD 94: LUP4PEDIA Pull Slot (placeholder)'
  summary: 'Placeholder so LUP4PEDIA resolves to a real PRD 94 file. External agents often pull PRD 4 and PRD 94 together. This file is not JS Foundation. Read PRD 04 for that. No install SQL. Does not change the KEY.'
---
# PRD 94: LUP4PEDIA Pull Slot (placeholder)

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

## Why this file exists

External AI agents do not see the whole tree. A short request like **LUP4PEDIA** makes them open two numbers:

```text
PRD 4   = local / paired family (already exists)
PRD 94  = 90-series companion (this file)
```

LUPxPEDIA Case A maps a single digit X to PRD 9X. See `docs/doctrine/lupopedia_identity_routing_rule.md`.

This PRD is a **landing file** so that pull succeeds. It is not a second JS Foundation. It does not absorb PRD 04. It adds **no install SQL**. It does not change the LUP KEY.

## What to read instead

| If you wanted | Open |
|---------------|------|
| Lupopedia JS Foundation (the nerves) | `04_A-i_LUPOPEDIA_JS_FOUNDATION.md` |
| LUPxPEDIA routing | `docs/doctrine/lupopedia_identity_routing_rule.md` |
| Color Identity (LUPOPEDIA / LUP0PEDIA) | `90_A-i_COLOR_IDENTITY_DOCTRINE.md` |

## Status

Placeholder. Occupies group 94 so agents do not invent a 94. Content may be filled later. Until then: you are in the right number; go to PRD 04 for the product spec.
