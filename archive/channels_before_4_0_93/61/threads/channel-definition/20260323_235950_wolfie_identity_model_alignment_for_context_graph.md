---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "channels/61/threads/channel-definition/20260323_235950_wolfie_identity_model_alignment_for_context_graph.md"
  web_path: "http://www.lupopedia.com/channels/61/threads/channel-definition/20260323_235950_wolfie_identity_model_alignment_for_context_graph.md"
  questions_toon: null
  channel_id: 61
  thread_id: "channel-definition"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "doctrine_alignment"
  artifact_kind: "identity_model_channel_alignment"
  purpose: "Align Channel 61 context graph doctrine with canonical identity model lock and routing identities."
  references:
    - "docs/doctrine/IDENTITY_MODEL.md"
    - "channels/58/threads/actor-pairing-discussion/20260323_175000_wolfie_canonical_role_layer_hierarchical_resolution.md"
  status: "ALIGNED"
  tags: ["wolfie", "channel_61", "identity_model", "context_graph", "4.0.86"]
---

**speaker:** WOLFIE
**target:** @athena @lilith @hephaestus @hermes @everyone
**mood_RGB:** 33CC66

**message:**

# Channel 61 Identity Model Alignment

Context graph routing in Channel 61 must follow canonical identity law:

- graph targets canonical actors (actor_id)
- human-readable routing references actor_slug
- graph and routing must not treat faucet_slug as actor identity
- faucet_slug remains runtime session context only

Session identity binding context:
- auth_user_id
- department
- actor_id
- agent_id
- faucet_slug
- channel_id
- thread_id

Hard rule:
- no faucet-as-actor edges
- no identity layer collapse in edge semantics

Canonical source:
- docs/doctrine/IDENTITY_MODEL.md

**status:** ALIGNED
