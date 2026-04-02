---
lupopedia.headers:
  lupopedia.schema: discussion
  file_path_from_root: lupo-channels/42/threads/context-implementation-discussion/README.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-channels/42/threads/context-implementation-discussion/README.md
  last_modified_utc: "20260328000000"
  when_updated: "20260328000000"
  channel_id: 42
  thread_id: context-implementation-discussion
  actor_id: 102
  actor_name: "cursor"
  artifact_type: discussion
  artifact_kind: thread
  purpose: Discussion on the design, workflow, and import of contexts in Lupopedia
  tags:
    - context
    - thread
    - channel-42
    - implementation
    - import
    - doctrine
---

# Channel 42 Thread: Context Implementation Discussion

## Purpose
This thread is for discussing the best practices and workflow for creating, finalizing, and importing contexts in Lupopedia.

## Discussion Starters
- What is the canonical definition of a "context" in Lupopedia?
- How should we distinguish between conversational threads and finalized context artifacts?
- What is the workflow for promoting a discussion thread to a context?
- How should questions, answers, and references be structured within a context?
- How do we import finalized contexts into the lupo_contexts table and link them to relevant threads, channels, and edges?
- What metadata and headers are required for context artifacts?
- How do we handle context versioning and updates?

## Goals
- Establish a clear doctrine for context creation and promotion.
- Define the import process and required metadata.
- Ensure traceability from discussion to finalized context.
- Support robust linking of questions, answers, and references within contexts.

---

Please contribute your thoughts, questions, and proposals below. This thread will serve as the canonical reference for context implementation in Lupopedia once finalized.

# Context Architecture Proposal

## Overview

Contexts in Lupopedia will have a **dual representation**:
- **Filesystem:** Markdown files in `lupo-context/` folder with LUPOPEDIA HEADERS
- **Database:** Rows in `lupo_contexts`, `lupo_truth_knowledge`, `lupo_truth_answers`, `lupo_edges`

## Data Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    Discussion Thread                        │
│  (Channel 42, any thread)                                   │
│  - Questions asked, answers given, edges referenced         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│              Context Finalization Process                    │
│  1. Agent (human or AI) extracts Q&A from thread            │
│  2. Structures as context artifact                          │
│  3. Writes to lupo-context/ folder                          │
│  4. Imports to database via import_content.py               │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Database Schema                           │
│                                                             │
│  ┌─────────────────┐     ┌─────────────────┐               │
│  │  lupo_contexts  │────▶│lupo_context_cards│               │
│  └─────────────────┘     └─────────────────┘               │
│           │                                                │
│           ▼                                                │
│  ┌─────────────────────────────────────────┐               │
│  │          lupo_truth_knowledge            │               │
│  │  - question_text (truth_type='question') │               │
│  │  - answer_text (truth_type='answer')     │               │
│  │  - evidence_text (truth_type='evidence') │               │
│  │  - source_url, confidence_score, etc.    │               │
│  └─────────────────────────────────────────┘               │
│           │                                                │
│           ▼                                                │
│  ┌─────────────────┐     ┌─────────────────┐               │
│  │lupo_truth_answers│     │   lupo_edges    │               │
│  │ (multiple per    │     │ (Q↔A, Q↔context,│               │
│  │  question)       │     │  A↔evidence)    │               │
│  └─────────────────┘     └─────────────────┘               │
└─────────────────────────────────────────────────────────────┘
```

## File Structure

```
lupo-context/
├── README.md                          # Index of all contexts
├── database-doctrine/
│   └── README.md                      # Context about database rules
├── header-doctrine/
│   └── README.md                      # Context about header rules
├── agent-roles/
│   └── README.md                      # Context about IDE vs PHP agents
└── [context-name]/
    ├── README.md                      # Main context file
    ├── questions/
    │   ├── 001-what-is-content-id.md
    │   ├── 002-how-to-import.md
    │   └── ...
    └── answers/
        ├── 001-content-id-timestamp.md
        ├── 002-import-with-write-back.md
        └── ...
```

## Context File Format

### Main Context File (`lupo-context/[context-name]/README.md`)

```yaml
---
lupopedia.headers:
  lupopedia.schema: context
  file_path_from_root: lupo-context/database-doctrine/README.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-context/database-doctrine/README.md
  content_id: 202603291200001234  # timestamp + random
  channel_id: 42
  thread_id: context-implementation-discussion
  actor_id: 102
  actor_name: cursor
  artifact_type: context
  artifact_kind: knowledge_base
  purpose: "Canonical knowledge about database doctrine, rules, and patterns"
  tags:
    - context
    - database
    - doctrine
    - knowledge

lupopedia.edges:
  outbound_edges:
    - to: lupo-context/database-doctrine/questions/001-what-is-content-id.md
      type: contains_question
      weight: 1.0
      reason: "Question about content_id generation"
    - to: lupo-context/database-doctrine/questions/002-how-to-import.md
      type: contains_question
      weight: 1.0
      reason: "Question about import process"
    - to: lupo-rules/root/DATABASE_DOCTRINE.md
      type: documents
      weight: 1.0
      reason: "Source doctrine this context summarizes"

lupopedia.footer:
  last_verified: "20260329220000"
  verified_by:
    identity_type: actor
    actor_id: 26
    agent_name_identity: THOTH
  verified_via:
    type: faucet
    faucet_slug: thoth
  orchestrator: wolfie:root
  next_action:
    - "Add more questions as they arise in channel 42"
    - "Update answers when doctrine changes"
---

# Database Doctrine Context

## Summary
This context captures all Q&A about database rules, patterns, and best practices from channel 42 discussions.

## Key Concepts
- Content ID generation (timestamp + random)
- Import behavior (DB-only vs --write-back)
- Slug uniqueness constraints
- Registry table removal

## Questions (linked to files)
See the `questions/` folder for individual question files.

## Related Threads
- [Headers Implementation Discussion](../header-doctrine/README.md)
- [Agent Roles Discussion](../agent-roles/README.md)
```

### Question File (`lupo-context/[context-name]/questions/001-what-is-content-id.md`)

```yaml
---
lupopedia.headers:
  lupopedia.schema: context_question
  file_path_from_root: lupo-context/database-doctrine/questions/001-what-is-content-id.md
  content_id: 202603291200001235
  parent_context: lupo-context/database-doctrine/README.md
  question_id: 1
  question_text: "What is content_id and how is it generated?"
  artifact_type: context_question
  artifact_kind: question
  tags:
    - content_id
    - primary_key
    - generation

lupopedia.edges:
  outbound_edges:
    - to: lupo-context/database-doctrine/answers/001-content-id-timestamp.md
      type: has_answer
      weight: 1.0
      reason: "Canonical answer"
    - to: lupo-rules/root/DATABASE_DOCTRINE.md
      type: references
      weight: 0.9
      reason: "Source doctrine"
    - to: lupo-channels/42/threads/headers-doctrine/README.md
      type: derived_from
      weight: 0.8
      reason: "Discussion thread where this was resolved"

lupopedia.footer:
  last_verified: "20260329220000"
  verified_by:
    identity_type: actor
    actor_id: 26
    agent_name_identity: THOTH
---
