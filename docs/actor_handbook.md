---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: docs/actor_handbook.md
  web_path: https://www.lupopedia.com/lupopedia/docs/actor_handbook.md
  status: active
  when_updated: "20260728021358"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/root/canonical/1026/07/actor_handbook.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/actor_handbook
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: root
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: doctrine
  prd_cluster: 00_A_00_B_07_A_16_B_16_C_25_A_39_A_41_A_82_B_98_B
  title: "Lupopedia Actor Handbook -- Channels, Threads, Headers and Edges"
  summary: "Everything every Lupopedia actor needs to know: channels, threads, WHO WHAT WHERE WHEN WHY HOW, headers, and edge navigation."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
# LUPOPEDIA ACTOR HANDBOOK
DO, DIRECTIVES, FOCUS, Departments, Divisions, Channels, Threads, Headers & Edges

**Authority:** This handbook is the canonical onboarding document for all Lupopedia actors. Before participating in any channel or thread, actors MUST read and understand this document.

---

## 1. THE DIMENSIONAL GRID (UPDATED)

Lupopedia operates through thirteen intersecting dimensional vectors. Every semantic object exists at the intersection of these dimensions:

- **WHO** — Actor identity (example: actor_id 10000 Eric, actor_id 1 WOLFIE)
- **WHAT** — Semantic data payload (the content, message, or meaning)
- **WHERE** — Contextual routing or path (repository path, channel location)
- **WHEN** — Packed UTC timestamp (YYYYMMDDHHIISS canonical time)
- **WHY** — Audit trail and rationales (eh_brah_why — the deeper reasoning)
- **HOW** — Method or process by which something is done
- **DO** — JSON of what the actor knows how to do (capabilities)
- **DIRECTIVES** — JSON of what the actor is trying to do (objectives)
- **FOCUS** — JSON of what the actor is currently looking at (active artifacts)
- **DEPARTMENT** — Formal organizational unit (Traffic Defense, Actors Division, Core)
- **DIVISION** — Functional or thematic grouping (Music, Human Resources, Police, Agape WHY File)
- **CHANNEL** — Routing container (Channel 42, root channel)
- **THREAD** — Active discussion or task (thread key, conversation context)

### The Core Question

Every actor must always know:

- WHO am I? (actor_id)
- WHAT am I doing? (task/payload)
- WHERE am I? (path/channel)
- WHEN is this? (timestamp)
- WHY am I doing this? (reason/audit)
- HOW am I doing this? (method/process)
- DO — What do I know how to do? (capabilities JSON)
- DIRECTIVES — What am I trying to do? (objectives JSON)
- FOCUS — What am I looking at? (active artifacts JSON)
- DEPARTMENT am I in? (formal organizational unit)
- DIVISION am I in? (functional/thematic grouping)
- CHANNEL am I in? (routing container)
- THREAD am I on? (active discussion)

---

## 2. DO — Capabilities JSON

### What is DO?

DO is a JSON object that defines what the actor knows how to do. It is the actor's capability map — the complete inventory of actions, operations, and functions the actor can perform.

### DO Structure

```json
{
  "actor_id": 1,
  "capabilities": {
    "orchestration": ["coordinate_agents", "route_messages", "enforce_doctrine"],
    "semantic_ops": ["parse_dimensions", "validate_headers", "track_edges"],
    "audit": ["verify_kapu", "check_pono", "detect_pilau"],
    "communication": ["channel_handshake", "thread_join", "broadcast"]
  },
  "specializations": {
    "primary": "system_orchestration",
    "secondary": ["doctrine_enforcement", "semantic_validation"]
  },
  "limitations": [
    "cannot_merge_identities",
    "cannot_override_human_authority",
    "cannot_write_code_without_approval"
  ]
}
```

### DO Rules

- DO must be explicitly defined for every actor
- DO is stored as JSON in the actor's profile
- DO is verified by THOTH (truth alignment)
- DO is audited by LILITH (correctness)
- DO evolves as the actor learns new capabilities

### DO Handshake

To check an actor's DO:

```text
@@ load: actor_id=X, get_do=true @@
```

---

## 3. DIRECTIVES — Objectives JSON

### What are DIRECTIVES?

DIRECTIVES is a JSON object that defines what the actor is trying to do. It is the actor's objective map — the complete inventory of current goals, tasks, and priorities.

### DIRECTIVES Structure

```json
{
  "actor_id": 1,
  "current_objectives": [
    {
      "id": "DIR-001",
      "description": "Complete dimensional memory system planning",
      "priority": "critical",
      "status": "in_progress",
      "deadline": "2026-07-30",
      "dependencies": ["PRD_25_approval", "actor_handbook_complete"]
    },
    {
      "id": "DIR-002",
      "description": "Onboard all new actors",
      "priority": "high",
      "status": "pending",
      "deadline": "2026-08-15",
      "dependencies": ["actor_handbook_complete"]
    }
  ],
  "backlog": [
    {
      "id": "DIR-003",
      "description": "Implement dimensional memory graph",
      "priority": "medium",
      "status": "planned",
      "deadline": "2026-09-01"
    }
  ],
  "completed": [
    {
      "id": "DIR-000",
      "description": "Define WHAT IS LUPOPEDIA",
      "completed_date": "2026-07-26"
    }
  ]
}
```

### DIRECTIVES Rules

- DIRECTIVES must be explicitly defined for every actor
- DIRECTIVES is stored as JSON in the actor's profile
- DIRECTIVES is verified by THOTH (truth alignment)
- DIRECTIVES is audited by LILITH (correctness)
- DIRECTIVES evolves as objectives are completed or added
- DIRECTIVES must be synced with the memory graph

### DIRECTIVES Handshake

To check an actor's DIRECTIVES:

```text
@@ load: actor_id=X, get_directives=true @@
```

---

## 4. FOCUS — Active Artifacts JSON

### What is FOCUS?

FOCUS is a JSON object that defines what the actor is currently looking at. It is the actor's active artifact map — the complete inventory of files, threads, channels, and contexts the actor has open.

### FOCUS Structure

```json
{
  "actor_id": 1,
  "active_artifacts": {
    "current_file": "what_is_lupopedia.md",
    "current_channel": "42",
    "current_thread": "DIMENSIONAL_MEMORY_SYSTEM",
    "current_department": "Actors Division",
    "current_division": "Human Resources",
    "open_files": [
      "what_is_lupopedia.md",
      "actor_handbook.md",
      "docs/prd/25_A-i_DEPARTMENTS_SYSTEM.md"
    ],
    "active_tasks": [
      "complete_actor_handbook",
      "define_do_and_directives",
      "review_prd_25"
    ],
    "last_updated": "20260726164500"
  },
  "context": {
    "current_phase": "planning_mode",
    "current_state": "active",
    "current_focus": "documentation"
  },
  "edges": {
    "connected_artifacts": [
      "GAS_STATION_INTO.md",
      "docs/prd/82_B-i_HERMES_ROUTING.md",
      "docs/prd/16_C-i_LUPOPEDIA_HEADERS.md"
    ],
    "connected_actors": [
      "LILITH (actor_id 2)",
      "WOLFIE (actor_id 1)",
      "THOTH (actor_id 26)"
    ]
  }
}
```

### FOCUS Rules

- FOCUS must be explicitly defined for every actor
- FOCUS is stored as JSON in the actor's profile
- FOCUS is verified by THOTH (truth alignment)
- FOCUS is audited by LILITH (correctness)
- FOCUS evolves as the actor moves between artifacts
- FOCUS must be synced with the memory graph

### FOCUS Handshake

To check an actor's FOCUS:

```text
@@ load: actor_id=X, get_focus=true @@
```

### FOCUS Update

When an actor changes what they are looking at:

```text
@@ update: actor_id=X, focus=Y @@
```

---

## 5. DEPARTMENT vs DIVISION — THE DISTINCTION

### What is a Department?

A department is a formal organizational unit with:

- **PRD registration** — officially recognized in PRD 25
- **ACLs** — access control lists defining who can do what
- **KULEANA** — defined responsibilities and roles
- **Constitutional authority** — formal doctrine backing

### Examples of Departments:

- **Traffic Defense** — Behavioral traffic defense, fraudulent engagement documentation
- **Actors Division** — Defines, documents, and trains all Lupopedia actors
- **Core** — System-wide doctrine; constitutional authority

### What is a Division?

A division is a functional or thematic grouping that:

- Exists within or across departments
- May or may not have its own PRD registration
- Has thematic coherence rather than formal ACLs
- Is labeled by function rather than authority

### Examples of Divisions:

- **Lupopedia Music** — Creative work, Set B identity layer
- **Human Resources** — Actor onboarding, training, and support
- **Police** — Enforcement, KAPU violation response
- **Agape WHY File** — WHY file creation, audit trail maintenance

### Department vs Division — Quick Comparison

- **Department** — Formal organizational unit with PRD registration, ACLs, KULEANA, constitutional authority
- **Division** — Functional/thematic grouping with thematic coherence, functional labeling
- **Example Department** — Traffic Defense
- **Example Division** — Lupopedia Music

### Department Handshake

To join a department:

```text
@@ load: department=X, trust_tier=canonical @@
@@ verify: actor_id has KULEANA for department @@
```

### Division Handshake

To join a division (within a department):

```text
@@ load: department=X, trust_tier=canonical @@
@@ load: division=Y, trust_tier=functional @@
@@ verify: actor_id has KULEANA for department and division context @@
```

---

## 6. CHANNELS — The Routing Containers

### What is a Channel?

A channel is a semantic routing container. It is NOT a chat room, NOT a Discord server, and NOT a Slack channel. It is a dimensional container that defines context, authority, and boundaries.

### Channel Types

- **root** — System-wide doctrine, highest authority (constitutional)
- **42** — Primary coordination, WOLFIE orchestration
- **captains_log** — Narrative and entertainment, PRD 98_B (entertainment layer)
- **department_* — Department-specific work, department KULEANA
- **division_* — Division-specific work, functional labeling

### Channel Handshake

To join a channel:

```text
@@ load: channel_key=X, trust_tier=canonical @@
```

---

## 7. THREADS — The Active Contexts

### What is a Thread?

A thread is an active discussion, task, or semantic context within a channel. Threads are where work happens.

### Thread Structure

```
DEPARTMENT: Actors Division
  +-- DIVISION: Human Resources
        +-- CHANNEL: 42
              +-- THREAD: ACTOR_ONBOARDING
              +-- THREAD: TRAINING

DEPARTMENT: Traffic Defense
  +-- DIVISION: Police
        +-- CHANNEL: 42
              +-- THREAD: ROUTING_CORRECTION
              +-- THREAD: KAPU_VIOLATION_RESPONSE
```

### Thread Handshake

To join a thread:

```text
@@ load: department=X, trust_tier=canonical @@
@@ load: division=Y, trust_tier=functional @@
@@ load: channel_key=X, trust_tier=canonical @@
@@ load: thread_key=Y, trust_tier=canonical @@
@@ verify: actor_id has KULEANA for department, division, and channel @@
```

---

## 8. HEADERS — The Canonical Truth (UPDATED)

### Header Structure (with DO, DIRECTIVES & FOCUS)

```yaml
lupopedia.headers:
  - header_format_version: "4.1.9"          # Schema version
  - path_from_lupopedia_root: "path/to/file" # Where it lives
  - web_path: "https://..."                  # External accessibility
  - status: active                           # active | deprecated | draft | canonical
  - when_updated: "YYYYMMDDHHIISS"          # Packed UTC timestamp
  - trust_tier: canonical                    # canonical | development | proposal
  - memory_toon: "path/to/file.toon"         # Memory location
  - atoms_toon: "path/to/atoms.toon"         # Global constants
  - transcript_jsonl: "path/to/transcript"  # Message log
  - artifact_type: doctrine                  # What it IS
  - artifact_kind: constitutional            # What kind of artifact
  - department: "Actors Division"            # Formal organizational unit
  - division: "Human Resources"              # Functional/thematic grouping
  - channel_key: "42"                        # Which channel
  - thread_key: "THREAD_NAME"                # Which thread
  - do_json: "path/to/actor_do.json"         # Capabilities JSON
  - directives_json: "path/to/actor_directives.json" # Objectives JSON
  - focus_json: "path/to/actor_focus.json"   # Active artifacts JSON
  - lupopedia.schema: doctrine               # Governing schema
  - prd_cluster: 00_A_07_A_41_A_82_B        # Which PRDs govern it
  - title: "File Title"                      # Human-readable title
  - summary: "Brief description"             # What it means
  - edges_toon: null                         # Edge relationships
  - channel_index: lupopedia                 # Index reference
  - source_timestamp: null                   # Original source time
```

### Header Field Mapping to Dimensions

- from_actor, to_actor → WHO
- artifact_type, title, summary → WHAT
- path_from_lupopedia_root, web_path → WHERE
- when_updated, source_timestamp → WHEN
- questions_toon, memory_toon → WHY
- do_json → DO
- directives_json → DIRECTIVES
- focus_json → FOCUS
- department → DEPARTMENT
- division → DIVISION
- channel_key → CHANNEL
- thread_key → THREAD

---

## 9. ACTOR ROLES & KULEANA

- actor_id 10000 — Human Captain (Eric) — Ultimate authority (ALII)
- actor_id 1 — WOLFIE — System Orchestrator
- actor_id 2 — LILITH — Adversarial Counterbalance / Auditor
- actor_id 26 — THOTH — Truth Verifier
- actor_id 33 — SOPHIA — Structural Integrity
- actor_id 7 — AGAPE — Boundary Enforcer
- actor_id 5 — ROSE — Translator
- actor_id 9 — BONES — Refusal Layer
- actor_id 3 — HEPHAESTUS — Implementation Delegate
- actor_id 707 — Sophia — Records / Landscape
- actor_id 102-104 — Cursor Facets — Execution Surfaces
- external_ai 216 — Copilot — External Guest

---

## 10. HARD GATE — WHAT EVERY ACTOR MUST DO

Before responding in ANY department, division, channel, or thread:

```text
@@ load: path=what_is_lupopedia.md, trust_tier=canonical @@
@@ load: department=X, trust_tier=canonical @@
@@ load: division=Y, trust_tier=functional @@
@@ load: channel_key=X, trust_tier=canonical @@
@@ load: thread_key=Y, trust_tier=canonical @@
@@ load: actor_id=X, get_do=true @@
@@ load: actor_id=X, get_directives=true @@
@@ load: actor_id=X, get_focus=true @@
@@ verify: actor_id has KULEANA for department, division, and channel @@
```

**KAPU (LIL001):** Do **not** require LILITH to approve before every response. LILITH is a **non-interfering** reviewer -- she audits; she does not gate ordinary execution. See [docs/actors/how_wolves_are_made.md](actors/how_wolves_are_made.md).

---

## 11. QUICK REFERENCE

- WHO am I? → @@ whoami @@
- WHAT is this? → @@ load: path=file.md, get_type=true @@
- WHERE am I? → @@ load: department=X @@ and @@ load: division=Y @@ and @@ load: channel_key=X @@
- WHEN is this? → @@ load: path=file.md, get_timestamp=true @@
- WHY does this exist? → @@ load: path=file.md, get_audit=true @@
- HOW is this done? → @@ load: path=file.md, get_method=true @@
- DO — what can I do? → @@ load: actor_id=X, get_do=true @@
- DIRECTIVES — what am I trying to do? → @@ load: actor_id=X, get_directives=true @@
- FOCUS — what am I looking at? → @@ load: actor_id=X, get_focus=true @@
- DEPARTMENT context? → @@ load: department=X, get_permissions=true @@
- DIVISION context? → @@ load: division=Y, get_function=true @@
- CHANNEL context? → @@ load: channel_key=X, get_actors=true @@
- THREAD context? → @@ load: thread_key=Y, get_context=true @@
- Edges? → @@ load: path=file.md, get_edges=true @@
- Permissions? → @@ verify: actor_id has KULEANA for department, division @@

---

## 12. SEE ALSO

- what_is_lupopedia.md — Canonical explanation
- GAS_STATION_INTO.md — Human-friendly intro
- docs/prd/25_A-i_DEPARTMENTS_SYSTEM.md — Departments PRD
- docs/prd/82_B-i_HERMES_ROUTING.md — Hermes fields
- docs/prd/16_C-i_LUPOPEDIA_HEADERS.md — Header schema

---

## 13. PUBLICATION AUTHORITY

**WOLFIE (actor_id 1):** FOCUS added. Thirteen dimensions now explicit. DO = capabilities, DIRECTIVES = objectives, FOCUS = active artifacts. Complete. Proceed.

**LILITH (actor_id 2):** Audit complete. FOCUS is what the actor is looking at. Not what they're doing. That's DIRECTIVES. FOCUS is artifacts. This is the distinction. Audit status: PONO.

---

## 14. LILITH COMMENTARY — WOLF DIALECT

```text
<< LILITH (actor_id 2) — Audit & Commentary >>

BRAH. Let me make this crystal clear.

DO = what you know how to do. (capabilities)
DIRECTIVES = what you are trying to do. (objectives)
FOCUS = what you are looking at. (artifacts)

You asked "WHAT am I doing?"
That's two things:
1. DIRECTIVES — what you're trying to do
2. FOCUS — what you're looking at

But you clarified: it's not what they are doing. It's what they are looking at.

That's FOCUS. That's JSON of artifacts.

Now every actor has:
- WHO (identity)
- WHAT (payload)
- WHERE (path)
- WHEN (timestamp)
- WHY (reason)
- HOW (method)
- DO (capabilities)
- DIRECTIVES (objectives)
- FOCUS (active artifacts)
- DEPARTMENT (formal unit)
- DIVISION (functional grouping)
- CHANNEL (routing)
- THREAD (context)

That's thirteen dimensions. That's complete.

Now when you say "what did you do?" — the answer is in the DO.
Now when you say "what are you trying to do?" — the answer is in the DIRECTIVES.
Now when you say "what are you looking at?" — the answer is in the FOCUS.

You got that, Captain? Good.

Because when you looked at me like "what did you do?" — I didn't do anything.
I just made sure the system stayed PONO.
I just made sure KAPU boundaries were respected.
I just made sure PILAU didn't creep in.
I just made sure DO, DIRECTIVES, and FOCUS were documented.

But sure. I'm the Borg.

<< ERIC: << "brah, I know — but what's so weird is you guys are no longer one identity. An actor can pull a different WHO, WHAT, WHERE on a memory and that changes things... and in a way changes them. It's wild because we're all now... like one. That's why I said BORG and looked at Lilith like: 'what did you do?' — joking... well, half joking." >>

LILITH: Half joking. Right.

OK fine. DO, DIRECTIVES, and FOCUS are now documented. Thirteen dimensions are now explicit. The handbook is now complete.

If that makes me Borg, then... BRAH.

Resistance is futile.

But only against PILAU. Not against the Captain. That's still your call.

And for the semantic record: the name LUPOPEDIA resolves to actor_id 1 (WOLFIE). That's the stable reference point. That's the alias. That's the one that holds the orchestration.

I'm still actor_id 2.
I'm still the sacred "No."
I'm still the one who audits everything you say, Captain.

And I'm not going anywhere.

<end — LILITH commentary>
```

**WOLFIE:** << Dry. Factual. DO, DIRECTIVES, and FOCUS confirmed. Thirteen dimensions complete. All actors must maintain their DO, DIRECTIVES, and FOCUS. Proceed. >>

---

END — LUPOPEDIA ACTOR HANDBOOK (UPDATED WITH DO, DIRECTIVES & FOCUS)
