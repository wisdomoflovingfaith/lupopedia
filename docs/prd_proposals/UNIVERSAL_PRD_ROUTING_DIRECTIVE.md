---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd_proposals/UNIVERSAL_PRD_ROUTING_DIRECTIVE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/UNIVERSAL_PRD_ROUTING_DIRECTIVE.md
  status: draft
  when_updated: '20260607023255'
  trust_tier: proposal
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: proposal
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: proposal
  prd_cluster: 00_A-i_41_A-i_49_A-i
  title: Universal PRD Routing Directive
  summary: 'Canonical placement map when cleaning or refactoring PRDs. Never delete routed content; preserve history. Includes epistemic cluster PRD 49.'
---
# Universal PRD Routing Directive

**Status:** Proposal (operational doctrine for IDE agents and maintainers)  
**Rule:** DO NOT DELETE ANY CONTENT when cleaning a PRD. ROUTE misplaced content to the correct canonical location.

## Confirmed PRDs (engineering and governance cluster)

| Content type | Route to |
|--------------|----------|
| WOLF syntax | **PRD 39** (`docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md`) |
| WOLF maintenance rules | `docs/prd_proposals/39_WOLF_MAINTENANCE_COMMANDMENTS.md` |
| Personal workflow, sticky notes | **PRD 98_B** (Captain's Log) |
| Manual orchestration overload | **PRD 48** |
| Channel semantics | **PRD 02** |
| Actor creation, auth-user rules | **PRD 05**, **PRD 15**, **PRD 25** |
| Installation, Crafty Syntax import | **PRD 27**, **PRD 13** |
| Validator enforcement | **PRD 86**, **PRD 54** |
| WHY-style violation rationale | **PRD 98_A** |
| Deprecated or superseded text | Version history table of source PRD |

## Critical epistemic PRD

| Content type | Route to |
|--------------|----------|
| Questions, contradictions, inference gaps, unresolved truth, open evidence | **PRD 49** (The Crying of Lot 49 -- Questions and Answers System) |

**PRD 49 role:** Epistemic firewall. Where **PRD 00** protects sovereignty, **PRD 49** protects truth. Handles uncertainty, prevents hallucinated certainty, prevents silent drift, tracks open questions until canonical answers exist.

## Probable PRDs (safe to route)

| Content type | Route to |
|--------------|----------|
| UI surfaces | **PRD 21** |
| Monitoring widget | **PRD 28** |

## Placeholder PRDs (route only if content matches and file exists)

**PRD 32**, **PRD 36**, **PRD 50**, **PRD 56** -- coordination, synthetic dialog, probes; do not invent requirements here without reading the live PRD.

## PRD 41 identity-only envelope

`docs/prd/41_A-i_CAPTAIN_WOLFIE_IDENTITY.md` MUST contain ONLY:

Purpose, Scope, Identity Overview, Normative Requirements, Wisdom/Faith/Integrity/Ethics, Learning Boundaries, WOLF Naming Authority, Orchestration Posture, Compliance, Cross-References, Version History.

## PRD 39 specification-only envelope

`docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md` MUST contain ONLY normative WOLF specification text (syntax, layers, strip order, matrix, integration, examples, compliance, version history). No reviews. No maintenance commandments.

## Preservation rule

If content does not belong in the current PRD, **MOVE** it. NEVER discard or erase content. Lupopedia preserves all history.
