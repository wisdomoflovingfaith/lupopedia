---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/channels.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "docs\doctrine\channels.md"
  file_hash: "6665e9e4b9052b1b4ba0d23ca66dbe050a0d93319fc1f7708fe64591df3cf040"
  file_path_from_root: "docs\doctrine\channels.md"
  file_hash: "3ad742903a0c628e9c025f2fcbda596a24e3770aed75879940614f758dd4d57b"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for channels.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "channelsmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
lupopedia.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/channels.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/channels.md
---

# Lupopedia Channel System Doctrine

## Projects and Channels

**Projects** exist as a semantic layer **above** channels. A **project** groups related channels, collections, and dialogs within a federation node. **Channels belong to exactly one project.** Dialog operations should be understood in project context. **IDE agents** infer project context from the workspace; **external actors** must declare project context explicitly. See [docs/projects/PROJECTS.md](../projects/PROJECTS.md), [docs/projects/PROJECTS_API.md](../projects/PROJECTS_API.md), and [PROJECT_REGISTRY_DOCTRINE.md](PROJECT_REGISTRY_DOCTRINE.md) for project doctrine, external actor API, and registry identity. This doctrine file continues to define channel behavior; project governance and lifecycle are defined in the projects documentation.

---

## Purpose of the Channel System

The channel system defines the communication spaces used by the Lupopedia
Semantic OS. It is implemented through three core tables: `lupo_channels`,
`lupo_actors`, and `lupo_actor_channels`. The system exists to provide stable,
explicit lanes for kernel operations, doctrine, routing, emotional metadata,
training, system events, and recovery workflows.

## Conceptual Model

Channels are communication spaces. They are identified by a unique
`channel_number`, stable string identifiers (`channel_key`, `channel_slug`), a
`channel_type` (currently `chat_room`), and descriptive metadata. Channels are
treated as defined, named system surfaces rather than ad hoc or inferred spaces.

## Numbering, Naming, and Categorization

Doctrine for channel creation:
- allocate a new `channel_number` with clear intent
- set `channel_key`, `channel_slug`, and `description` explicitly
- keep inserts idempotent (`INSERT IGNORE`)

The system currently contains approximately 222 channels in total. This refers
to the total number of channel records, not the numbering scheme.

`channel_number` is a logical identifier, not a bounded index. Channel numbers
are not sequential and do not form a contiguous range. Values may be sparse,
non-linear, or grouped by subsystem (for example, the 51-series). High channel
numbers such as 51 are valid and intentional, and the total count is what
matters, not the numeric range.

Channel 51 (Lupopedia reserved) and channels 5100-5130 are reserved for system and experimental spaces and were
inserted in two batches:
- 51: Lupopedia reserved; 5100-5115: core system channels
- 5116-5130: extended system and experimental channels

These channels cover:
- kernel logs
- doctrine
- semantic routing
- emotional metadata
- agent training
- system events
- recovery and debugging
- experimental sandboxes

## Kernel vs Non-Kernel Channels

The channel row includes an `is_kernel` flag. Kernel channels represent core
system lanes, while non-kernel channels represent extended or experimental
spaces. Kernel status is part of the channel definition and is not inferred.

## Channel 51 and channels 5100-5130 (Doctrinal Role)

Channel 51 (Lupopedia) and channels 5100-5130 exist to define the system's core and experimental
communication topology. This range provides a stable set of lanes for kernel
logs, doctrine management, semantic routing, emotional metadata, agent training,
system events, recovery operations, and sandboxed experimentation.

## Relationship to System Actors

The `lupo_actors` table defines system identities. Three system actors exist with
`actor_source_type = 'system'`, active status, and no soft deletion:

WOLFIE
- `actor_type = 'ai_agent'`
- Role: Founder-Architect
- Purpose: system design, doctrine, kernel alignment
- Metadata includes default channels

LILITH
- `actor_type = 'ai_agent'`
- Role: Counter-Agent
- Purpose: stress-testing, contradiction detection, emotional interrogation
- Metadata includes default channels

LUPOPEDIA
- `actor_type = 'service'`
- Role: Semantic OS
- Purpose: system events, routing, kernel operations
- Metadata includes default channels

These names are system identifiers. Any mythic interpretation is metadata only.

## Metadata-Driven Routing

Actors store default channels in metadata. This enables routing to be driven by
explicit actor definitions rather than hardcoded paths. Memberships provide the
active presence, while metadata defines intended channel scope.

## Required Execution Order

This order guarantees deterministic setup:
1. Insert system actors
2. Query actor IDs
3. Insert channel batches
4. Query channel IDs
5. Insert actor-channel memberships

## Design Principles

- Idempotent migrations using `INSERT IGNORE`
- Separation of concerns: channels, actors, memberships
- Metadata-driven routing: actors store default channels in metadata
- Extensibility: add actors or channels without modifying existing rows
- Schema-aligned: inserts follow the current TOON schema

## Extension and Maintenance Guidance

When adding channels:
- allocate a new `channel_number` with clear intent
- set `channel_key`, `channel_slug`, and `description` explicitly
- keep inserts idempotent (`INSERT IGNORE`)

When adding actors:
- define `actor_type` and `actor_source_type`
- include default channel metadata
- keep the actor active and non-deleted unless intentionally retired

When adding memberships:
- resolve actor and channel IDs first
- insert using `INSERT IGNORE`
- keep `status = 'A'` for active memberships

Schema changes must follow the canonical changelog and TOON workflow.
