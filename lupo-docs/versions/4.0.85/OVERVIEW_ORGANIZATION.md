---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-docs/versions/4.0.85/OVERVIEW_ORGANIZATION.md"
  last_modified_utc: "20260322_184651"
  channel_id: 42
  thread_id: 2014
  actor_id: 4
  actor_name: "athena"
  artifact_type: "documentation"
  artifact_kind: "derived_view"
  purpose: "Derived organization overview for 4.0.85."
---

# 4.0.85 OVERVIEW ORGANIZATION

> Derived view. Authoritative state in TASK_REGISTRY.

## Work Organization Model

4.0.85 work is organized so that overview surfaces do not compete with authority surfaces. The central problem solved in this version is not only â€œwhat changed,â€ but â€œwhich document is allowed to say what.â€

That organization matters because Lupopedia now spans schema, doctrine, threads, routing, actor/auth mapping, and research classification. A single summary file can no longer carry all of that safely.

### Primary Channel: 42

Channel 42 is the root orchestration channel for 4.0.85 and the place where the most important architecture corrections were driven.

- **1xxx threads**: structural work, synchronization, documentation governance, validation, and research classification
- **2xxx threads**: schema reconciliation, relationship-model correction, dialog routing design and MVP correction, install readiness, and overview rewrite
- Authority: TASK_REGISTRY.md at `lupo-docs/versions/4.0.85/TASK_REGISTRY.md`

The key threads for understanding the current architecture are:

| thread | significance |
|---|---|
| **1047** | corrected the authority model so TASK_REGISTRY owns task state and THREAD_INDEX becomes navigation-only |
| **2004** | restored install SQL â†” TOON parity and kept Doom research from being mistaken for accepted schema |
| **2011** | corrected actor â†” auth_user modeling into an actual many-to-many support relationship |
| **2012** | defined and corrected deterministic actor-to-human routing MVP behavior |
| **2013** | confirmed install readiness and runtime safety after 2011/2012 corrections |
| **2015** | resolved the mood_rgb model into authoritative canonical tokens plus vector-only routing influence |
| **2014** | rewrote overview surfaces so they describe the system truthfully at 4.0.85 |

### Question Channel: 66

Channel 66 carries question-oriented work and unresolved decision context. It is separate so investigation and contradiction handling do not get mixed into execution threads.

### Support Channels: 1, 7, 11, 17, 23, 31, 51, 88, 420

Specific workstreams delegated by channel. All tracked in central TASK_REGISTRY.

## Structural Decisions

| decision | rationale |
|---|---|
| TASK_REGISTRY as single source | Eliminated authority conflicts between THREAD_INDEX and version docs |
| CONTRADICTIONS.md as diagnostic-only | Separates violation recording from execution authority |
| THREAD_INDEX as navigation-only | Prevents circular authority between thread artifacts and registry |
| TOON parity via Python generation | Eliminates manual drift; TOONs are always derived from install SQL |
| Domain file placement for major thread outcomes | Keeps final outcomes in exactly one primary documentation location per concept |
| Version overviews kept separate | README, OVERVIEW, and OVERVIEW_ORGANIZATION answer different questions and must not collapse into one summary |

## Canonical Domain File Placement

| thread | canonical documentation location |
|---|---|
| 1047 | `organization_changes/authority_and_governance_model.md` |
| 2004 | `database_changes/schema_reconciliation_and_toon_state.md` |
| 2011 | `actor_auth_user_relationship_model.md` |
| 2012 | `dialog_routing_design.md` |
| 2015 | `doctrine_changes/mood_rgb_hybrid_model.md` |
| 2013 | `channel_42_canonical_summary.md` plus install-readiness declarations in overview/state files |

## Which Overview Surface Answers Which Question

| surface | question it answers |
|---|---|
| `README.md` (root) | What is Lupopedia overall, why is it structured this way, and what is the current high-level reality? |
| `EXECUTIVE_SUMMARY.md` | What are the architecture, rationale, lineage, identity model, and deterministic design principles? |
| `lupo-docs/versions/4.0.85/README.md` | What is special about 4.0.85 and why does the version directory exist? |
| `lupo-docs/versions/4.0.85/OVERVIEW.md` | What became true in the system during 4.0.85? |
| `lupo-docs/versions/4.0.85/OVERVIEW_ORGANIZATION.md` | How is 4.0.85 work and documentation authority organized? |

This separation is intentional. These files should align, but they should not duplicate each other line-for-line or claim the same authority.

## Why This Organization Fits Lupopedia

Lupopedia combines several architectural concerns at once:

- relationship modeling through edges and metadata
- channel and thread coordination inherited from Crafty Syntax
- actor, agent, faucet, and auth-user distinction
- doctrine-driven determinism
- research-informed but evidence-gated evolution

That combination is exactly why overview surfaces have to be structured carefully. Without separate overview layers, the system is easy to misdescribe as either â€œjust chatâ€ or â€œjust graph.â€

## Personas Active in 4.0.85

| persona | role | threads |
|---|---|---|
| WOLFIE (1) | Orchestrator | 1047, 2006, 2013 |
| ATHENA (4) | Strategy | 1048, 2012, 2014 |
| THOTH (26) | Research/Records | 2004, 2005 |
| HEPHAESTUS | Implementer | 2004, 2011, 2012 |
| LILITH (2) | Reviewer (non-interfering) | 1047, 2011, 2012 |
| cursor (102) | Lead orchestration faucet | root documentation continuity |
