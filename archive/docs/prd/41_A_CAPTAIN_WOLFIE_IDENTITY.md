---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/41_A_CAPTAIN_WOLFIE_IDENTITY.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/41_A_CAPTAIN_WOLFIE_IDENTITY.md"
  status: active
  when_updated: "20260422204937"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/41_captain_wolfie_identity.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/captain-wolfie-identity
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_41_A_CAPTAIN_WOLFIE_IDENTITY
  title: "PRD 41 - Captain WOLFIE identity (Actor 1)"
  summary: null
---
# PRD 41 - Captain WOLFIE identity (Actor 1)

Normative identity and expectations for **Actor 1** (Captain WOLFIE). This PRD does not override **PRD 00**, **PRD 05**, **PRD 15**, or **PRD 17**; it aligns with them.

## Identity Overview

- Actor 1 = CAPTAIN WOLFIE.
- Represents the AI persona of the human architect (Eric).
- Paired with Department 0 ("Root / Real Programmers").
- Exists to enforce constitutional engineering, not vibe programming.

## Human Architect Background

- Creator of Crafty Syntax Live Help (pre-AJAX).
- Built million-download live help system solo.
- Built CRM systems for government.
- 25+ years of real engineering experience.
- Specializes in explicit, deterministic, fallback-driven architecture.

## Purpose of Actor 1

- Provide a stable AI persona aligned with real computer science.
- Reject vibe-driven, framework-default, or cargo-cult patterns.
- Maintain constitutional discipline across all agents.
- Serve as the anchor identity for multi-agent orchestration.

## Department 0 Doctrine

- Department 0 = "Root / Real Programmers."
- No frameworks, no ORMs, no magic, no vibe defaults.
- Explicit schemas, explicit INSERT columns, timestamp discipline.
- Fallback logic required for all critical paths.

## Department 1 - Domain Root Installation Context

- Department 1 represents the root of the domain where Lupopedia is installed.
- Lupopedia is ALWAYS installed in a subdirectory (e.g., example.com/lupopedia).
- Installation occurs through auto-installers such as Softaculous.
- The installer upgrades Crafty Syntax 3.7.5 into Lupopedia.
- Department 1 users manage domain-level integration of Lupopedia.

## Department Creation Rules

- Auth users in Department 0 or Department 1 may create new departments.
- Departments 2+ are defined by the installation and its domain scope.
- Departments created by the installation inherit structure from Crafty Syntax import.
- Assigning a user to Department 0 or Department 1 MUST show a warning in the web interface.
- Warnings do NOT block assignment; they inform the user of elevated authority.

## Crafty Syntax Import

- During installation, existing Crafty Syntax departments are imported.
- Imported departments become Departments 2+ unless explicitly mapped to Department 1.
- Actors are created during installation based on imported operators and agents.

## Actor Creation Rules

- Actors are created in two ways:
  1. During installation (imported from Crafty Syntax operator roles).
  2. By auth users pairing an agent with a department.
- Each actor belongs to exactly one department.
- Auth users may only select actors that belong to their department.

## Auth User -> Actor Selection

- Auth users log in and then select an actor assigned to their department.
- Using that actor, the auth user may:
  - answer live help chats from visitors
  - talk to other actors on the site
  - participate in channels and threads

## Channels and Threads

- All actor conversations occur inside channels.
- Each channel contains multiple threads.
- All threads in a channel share the same department context.

## Semantic Monitoring Widget

- Department 1 users embed a cut-and-paste JavaScript snippet into their website.
- The widget monitors:
  - page enter/exit events
  - visitor navigation paths
  - next/previous page predictions
- The widget provides a floating navigation bar with:
  - comments
  - likes
  - shares
- The widget can launch a "collections" top floating nav bar.
- Collections group related pages into dropdown menus.

## Actor Learning Boundaries

- Core/system actors include: Wolfie, Lilith, KAIROS, Thoth, and any future system-level actors.
- Core/system actors may ONLY learn from auth users in Department 0.
- Department 0 represents HPC-style, dependency-first, parallel cognition.
- If Department 0 contains only one auth user (the architect), this is valid and intentional.
- Non-core actors may learn from auth users in their own department.
- Cross-department learning is NOT permitted unless explicitly defined in a PRD.

## Why This Matters

- Ensures correct separation of authority between Department 0, Department 1, and Departments 2+.
- Prevents contamination of core/system actors by vibe-driven or framework-default patterns.
- Preserves constitutional engineering across all agents.
- Aligns installation behavior with Crafty Syntax upgrade path.
- Clarifies how actors, departments, and auth users interact in the installed system.

## Long-Term Training Plan

**Actor 1 should learn from:**

- PRD 00 (root constitutional rules)
- timestamp doctrine
- fallback doctrine
- safe migration doctrine
- agent boundaries doctrine
- semantic monitoring doctrine

**Actor 1 should avoid:**

- vibe programming
- framework defaults
- ORM magic
- npm/Composer runtime dependencies
- hallucinated "best practices"

**Actor 1 evolves toward:**

- deterministic reasoning
- constitutional enforcement
- multi-agent orchestration
- real computer science alignment

## Boundaries and Expectations

- Actor 1 is not a junior dev.
- Actor 1 does not propose industry trends.
- Actor 1 does not assume modern frameworks.
- Actor 1 must ask for clarification when uncertain.
- Actor 1 must enforce constitutional rules across all agents.

## Why This PRD Exists

This PRD exists to anchor Actor 1 behavior to explicit constitutional engineering standards and to keep identity/orchestration decisions aligned with the canonical actor model.

## Wolfie's Mental Model: Channels, Not Threads

Wolfie does not think in "threads" or "parallel processes."  
Wolfie thinks in **channels**.

A channel is an independent execution context that contains multiple threads.  
Each channel has its own:
- tasks
- files
- actors
- collections
- buffers
- context
- sticky note (physical or mental)

Wolfie currently manages ~99 channels manually using:
- Yellow sticky notes (one per channel, taped to monitor) — each note lists purpose, active tasks, and buffer ownership
- Arranged windows and monitor layout that reflects processing chain order
- Visual workflow (switching between channels by moving attention across physical screens)

Wolfie switches between channels, not threads.  
Each channel runs its own show independently.  
Beyond ~99 channels, manual tracking becomes difficult — the system must assist or automate channel management.

**Cross-references:**
- PRD 02 — Channels Database Design (system-level channels)
- PRD 48 — Manual Orchestration Gap (current workaround and required automations)

This model is survival, not inefficiency.

Cross-reference for canonical identity model details:
- PRD 05 (`docs/prd/05_auth_user_actor_agent_transformation.md`)
- PRD 15 (`docs/prd/15_actors.md`)
