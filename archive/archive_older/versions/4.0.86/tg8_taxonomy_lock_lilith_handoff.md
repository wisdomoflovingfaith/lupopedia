---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: null
  file_path_from_root: "docs/versions/4.0.86/tg8_taxonomy_lock_lilith_handoff.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: tg8_taxonomy_lock_handoff
  thread_id: "agent-system-design"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# TG-8 Taxonomy Lock - LILITH Handoff

## What Was Fixed

- `MessageEdgeParser` was aligned to emit canonical TG-8 taxonomy names.
- `EdgeValidationService` was expanded to accept canonical TG-8 taxonomy names.
- Legacy aliases are still normalized internally for backward compatibility.
- `TG8IntegrationService` continues to operate without taxonomy-specific changes.

## What Is Now Working

- Parser emits canonical edge types only in the current TG-8 message flow:
  - `reference`
  - `implements`
  - `dependency`
  - `contains`
  - `contradiction`
- Validator accepts those canonical types for current TG-8 message scope pairs.
- Parser determinism remains intact.
- Direct test execution passed:
  - `test_message_edge_parser.php`
  - `test_tg8_taxonomy_alignment.php`
  - `test_tg8_integration.php`
- Legacy alias inputs still validate through canonicalization:
  - `references`
  - `assigns`
  - `depends_on`
  - `produces`
  - `blocks`

## What Is Still Broken

- Phase 3 actor-vs-faucet enforcement is still inactive.
- Actor-target edges are now structurally valid before faucet identity rejection is active.
- Delete-path actor identity enforcement remains inactive.

## Risks Remaining

1. Actor edges can validate while faucet misuse is still not blocked in the active system.
2. Backward compatibility is validated at the request-validation layer, not yet proven against historical persisted edge populations.
3. Integration tests still include stub-based legacy fixtures, which proves continuity logic but not full production-path persistence semantics.

## Structural Soundness

- TG-8 taxonomy lock is structurally sound for the frozen message-driven edge flow.
- Parser and validator mismatch has been resolved.
- Current remaining risk is no longer taxonomy drift; it is identity enforcement sequencing.

## Version 4.0.86 Consolidation Note

For version documentation, the correct frozen-state statement is:

`TG-8 taxonomy lock is accepted with risks: taxonomy alignment is fixed, integration is stable, and the remaining open issue is Phase 3 actor/faucet enforcement rather than TG-8 taxonomy mismatch.`