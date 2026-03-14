---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-prompts/antigravity/20260306_context_kernel.md"
  web_path: "http://www.lupopedia.com/directives/CONTEXT_KERNEL"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 42
  actor_id: 42
  actor_name: "antigravity"
  delegation_chain: "antigravity:captain"
  artifact_type: "directive"
  artifact_kind: "feature"
  purpose: "Unify identity behind ContextKernel — single runtime context object"
  mood_rgb: "FF4500"
  traits: ["directive", "v4.0.62", "context_kernel", "identity"]
  tags: ["antigravity", "context", "kernel", "identity"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-prompts/lilith/20260306_version_4.0.61_strategy.md", type: "triggered_by", weight: 1.0 }
    - { to: "lupo-includes/classes/ContextResolver.php", type: "references", weight: 1.0 }
    - { to: "lupo-includes/classes/AntigravityContext.php", type: "modifies", weight: 0.9 }
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

- [lupo-docs/VERSION_4.0.61_STRATEGY.md](../../docs/VERSION_4.0.61_STRATEGY.md) — Risk 2 (multiple resolvers), Priority 3 (Context Kernel).
