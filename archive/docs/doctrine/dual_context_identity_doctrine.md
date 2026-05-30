---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/DUAL_CONTEXT_IDENTITY_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/DUAL_CONTEXT_IDENTITY_DOCTRINE.md"
  status: "active"
  when_updated: "20260403113047"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_id: "doctrine-header-repair"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: DUAL_CONTEXT_IDENTITY_DOCTRINE — delegation: cursor:root

# file: DUAL CONTEXT / IDENTITY DOCTRINE — WHOAMI + WHOAREYOU — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/docs/doctrine/DUAL_CONTEXT_IDENTITY_DOCTRINE

# DUAL CONTEXT / IDENTITY DOCTRINE (WHOAMI + WHOAREYOU)

## Core Principle

Every Lupopedia interpretation requires two distinct and non-overlapping layers:

1. **WHOAMI**: execution context (how and where code is executing in the current run)
2. **WHOAREYOU**: canonical identity (who the actor is as a permanent registry-backed identity)

Without both layers, interpretation is ambiguous and is treated as a system failure.

---

## Layer Definitions

### WHOAMI (Execution Context)
WHOAMI is the current execution context and must be derived from runtime/session context and/or the current artifact/thread header fields.

WHOAMI represents:
- facet_type (what interface is executing)
- session_mode (system / agent / human_direct / etc.)
- delegation_chain (who issued authority in this run)
- channel_id (coordination workspace)
- thread_id (work thread within the channel)
- federation_node_id (where the channel context belongs, if present)

WHOAMI may vary between runs and between facets.

### WHOAREYOU (Canonical Identity)
WHOAREYOU is the canonical actor identity and must be derived from the actor registry by `actor_id` and `actor_name`.

WHOAREYOU represents:
- actor_id (permanent identity key)
- actor_name (canonical slug for that identity)
- any persona/acronym metadata defined for that actor in registry/actor identity artifacts

WHOAREYOU must be stable even if an actor is banned/restricted/soft-deleted.

---

## Separation of Concerns (Non-Negotiable)

1. **WHOAMI must never redefine WHOAREYOU.**
   - Execution context cannot create identity substitutions.
2. **WHOAREYOU must never embed WHOAMI runtime state.**
   - Banned/active/restricted are state flags; they are part of the same canonical actor identity.
3. **No mixing of layers inside interpretation.**
   - A validator may read both layers, but may not merge them into a single concept.

---

## Deterministic Resolution Contract

When interpreting any Lupopedia artifact/message:

1. Read **system header** fields (versioning + artifact metadata) from the artifact’s LUPOPEDIA HEADERS block.
2. Resolve **WHOAMI**:
   - Use current runtime/session context when available.
   - Otherwise, use the artifact’s header fields that represent coordination context (channel_id/thread_id, delegation_chain, facet_type equivalents).
3. Resolve **WHOAREYOU**:
   - Use canonical actor registry lookups by `actor_id`.
   - `actor_name` must match the canonical registry slug for that `actor_id`.
4. Apply interpretation rules only after both WHOAMI and WHOAREYOU are resolved.

---

## Identity Drift Prohibitions

The following are forbidden by this doctrine:
- Creating variant identities to represent banned/test/restricted modes (e.g. `lilith_banned`, `wolfie_test`).
- Returning null identity for an existing canonical actor (including Lilith).
- Replacing canonical identity based on runtime/session context.
- Allowing IDE faucet names to masquerade as canonical identity values.

---

## Enforcement Hooks (Assignment-Friendly)

This doctrine expects validators to enforce:
- WHOAMI presence/structure (execution context fields exist where required)
- WHOAREYOU canonical resolution (actor_id present; actor_name matches registry)
- Separation checks (no runtime context fields inside canonical identity)
- Prohibition checks (no variant identity creation; banned actors remain addressable)

Implementation details belong in validators and propagation scripts, not in the doctrine itself.

---

## Reference Sources

- Actor registry source of truth: `database/lupopedia/actors/actor_id/registry.json`
- Convergence baseline: `rules/root/CONVERGENCE_DOCTRINE.md`
- Actor identity vs state separation: `docs/doctrine/ACTOR_STATE_DOCTRINE.md` (when present)

