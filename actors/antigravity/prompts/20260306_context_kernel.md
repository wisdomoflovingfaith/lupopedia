---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/antigravity/prompts/20260306_context_kernel.md
  web_path: https://www.lupopedia.com/lupopedia/actors/antigravity/prompts/20260306_context_kernel.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: directive
  artifact_kind: feature
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: directive
  prd_cluster: null
  title: null
  summary: null
---

# Antigravity Directive — Context Kernel (v4.0.62)

**Triggered by:** [LILITH strategy — v4.0.61 review](../lilith/20260306_version_4.0.61_strategy.md)

## Objective

Introduce a **ContextKernel** (or equivalent) as the single runtime context object. ContextResolver remains the **only** place that derives runtime identity; the kernel holds that result and exposes it to all consumers (Antigravity, CLI, other agents).

## Requirements

1. **Single resolution:** Kernel calls ContextResolver::resolve() once (or receives resolved context); does not recompute identity.
2. **Accessors:** getContext(), getEffectiveActor(), getHumanIdentity(), getActiveAgent(), getAuthUser() — all derived from the one resolved context (and existing ActorService/AuthService lookups where needed for human/auth details).
3. **Validation:** validate() returns a list of issues (e.g. "Session file used but DB session exists"; "Paired actor X not found").
4. **Adoption:** Antigravity (and ideally other agents) use this kernel instead of calling ContextResolver/AntigravityContext directly in multiple places.

## Implementation Notes

- Can wrap or replace current AntigravityContext usage so that AntigravityContext is fed from ContextResolver only and does not re-resolve.
- Preserve PHP 5.3 compatibility; no new frameworks.
- Follow Risk 2 mitigation: no identity derivation outside ContextResolver; kernel consumes only.

## Success Criteria

- One canonical place (ContextResolver) derives runtime identity.
- ContextKernel (or agreed name) exposes context and validation; agents use it instead of ad-hoc resolution.
- validate() surfaces session-vs-DB and paired-actor issues as in strategy.

## See Also

- [docs/VERSION_4.0.61_STRATEGY.md](../../docs/VERSION_4.0.61_STRATEGY.md) — Risk 2 (multiple resolvers), Priority 3 (Context Kernel).
