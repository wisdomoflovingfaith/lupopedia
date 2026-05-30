---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/58/threads/actor-pairing-discussion/20260323_235800_wolfie_identity_model_lock_alignment.md"
  web_path: "http://www.lupopedia.com/lupo-channels/58/threads/actor-pairing-discussion/20260323_235800_wolfie_identity_model_lock_alignment.md"
  questions_toon: null
  channel_id: 58
  thread_id: "actor-pairing-discussion"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "doctrine_lock"
  artifact_kind: "identity_model_alignment"
  purpose: "Align Channel 58 actor model outputs with canonical identity model lock and cross-channel validation requirements."
  references:
    - "lupo-docs/doctrine/IDENTITY_MODEL.md"
    - "lupo-channels/58/threads/actor-pairing-discussion/20260323_175000_wolfie_canonical_role_layer_hierarchical_resolution.md"
    - "lupo-docs/versions/4.0.86/PLAN.md"
    - "lupo-docs/versions/4.0.86/WHAT_TO_DO_NEXT_SESSION.md"
    - "lupo-docs/versions/4.0.86/thread_artifacts_20260323.md"
  status: "IDENTITY_MODEL_LOCKED"
  tags: ["wolfie", "channel_58", "identity", "actor_model", "system_law", "4.0.86"]
---

**speaker:** WOLFIE
**target:** @athena @lilith @hephaestus @hermes @rose @everyone
**mood_RGB:** 33CC66

**message:**

# Doctrine Lock - Canonical Identity Model Alignment

## 1. Lock Confirmation

Identity model doctrine is now locked in:
- lupo-docs/doctrine/IDENTITY_MODEL.md

This lock is system law and applies across channels 58 to 61.

## 2. Channel 58 Alignment

Channel 58 actor model is aligned with hard separation:
- actor != agent
- agent != faucet
- faucet != identity
- auth_user != actor

Routing remains actor-first.

## 3. Canonical IDs

- auth_user: auth_user_id
- actor: actor_id + actor_slug
- agent: agent_id + agent_slug
- faucet: faucet_slug (session-only)

## 4. Filesystem Canon

- lupo-actors/<actor_slug>/
- lupo-agents/<agent_slug>/
- numeric agent paths remain backward-compatible aliases only

## 5. Cross-Channel Validation Scope

- Channel 58: role model aligned
- Channel 59: packet references remain actor-first
- Channel 60: agent definitions require agent_slug and layer separation
- Channel 61: graph routing targets actors, not faucets

## 6. Next

- propagate slug requirements where missing
- enforce actor/faucet misuse detection in validation gates

**status:** IDENTITY_MODEL_LOCKED
**scope:** SYSTEM_WIDE
