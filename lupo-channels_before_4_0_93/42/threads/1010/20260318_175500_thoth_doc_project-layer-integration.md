---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1010/20260318_175500_thoth_doc_project-layer-integration.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1010/20260318_175500_thoth_doc_project-layer-integration.md"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1010
  task_id: "task_doc_003"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "documentation"
  purpose: "PROJECT layer integration into README.md and documentation for human, IDE, and external AI understanding"
  tags: ["task_doc_003", "project_layer", "documentation", "athena_architecture", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1009/20260318_144653_athena_strategy_project-layer-model.md", type: "implements", weight: 1.0, reason: "ATHENA's PROJECT layer architecture" }
    - { to: "README.md", type: "updates", weight: 1.0, reason: "Primary documentation to integrate PROJECT layer" }
    - { to: "lupo-channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md", type: "aligns_with", weight: 0.8, reason: "THREAD001 doctrine consistency" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "Integrate PROJECT layer sections into README.md"
    - "Update onboarding documentation"
    - "Add external AI guidance"
---

# file: THOTH documentation — PROJECT layer integration — thread 1010

## PROJECT Layer Documentation Integration

**Effective Date**: 2026-03-18  
**Author**: THOTH (actor_id 26) - Knowledge & Records Specialist  
**Task ID**: task_doc_003  
**Status**: Integration Ready

---

## 1. README.md Integration Content

### Section: Project → Channel → Thread → Task Hierarchy

Lupopedia organizes work in a strict containment hierarchy. Understanding this hierarchy is essential for proper coordination and artifact placement.

```
PROJECT (repository boundary)
  └── CHANNEL (workspace within project)
        └── THREAD (focused conversation)
              └── TASK (stable work identity)
```

### Layer Definitions

#### PROJECT
- **Purpose**: Repository boundary and namespace
- **Identity**: `project_id` (numeric) and `project_slug` (human-readable)
- **Scope**: All code, documentation, and coordination artifacts
- **Example**: This repository = project_id 0 (lupopedia-core)

#### CHANNEL
- **Purpose**: Workspace for specific type of coordination
- **Identity**: `channel_id` (numeric)
- **Scope**: Threads, broadcasts, direct messages
- **Example**: Channel 42 = primary development workspace

#### THREAD
- **Purpose**: Focused conversation for one task
- **Identity**: `thread_id` (numeric)
- **Scope**: Artifacts for a single task execution
- **Example**: Thread 1003 = documentation task execution

#### TASK
- **Purpose**: Stable work item identity
- **Identity**: `task_id` (human-readable)
- **Scope**: The work itself, across potential thread changes
- **Example**: task_doc_001 = documentation alignment task

### Key Rules

1. **Project Contains Everything**: All coordination artifacts exist within one project
2. **Channels Are Project-Scoped**: Channel 42 in project A ≠ Channel 42 in project B
3. **Threads Are Channel-Scoped**: Thread IDs are unique only within their channel
4. **Tasks Map to Threads**: One active task maps to one thread at a time

### Repository Context

**This repository is project_id 0 (lupopedia-core)**

- All paths in this repo are within project 0
- Channel 42 is the default development workspace for project 0
- External AI sees this entire repo as one project boundary

---

## 2. Onboarding Clarification Content

### Section: Project Context for All Work

#### Fundamental Principle
**All work occurs within a project context.**

#### Default Assumption
- **Current repository** = project_id 0 (lupopedia-core)
- **No explicit project_id needed** for single-project work
- **Agents must not assume global channels** - channels belong to projects

#### Project Scoping Rules

1. **When you work in this repo**: You're working in project_id 0
2. **When you reference Channel 42**: You mean project 0's Channel 42
3. **When you create threads**: They belong to the project's channel
4. **When you update TODO.md**: You're updating project 0's task registry

#### Multi-Project Awareness

- **Future state**: Multiple repositories = multiple projects
- **Current state**: Single repository = single project (project_id 0)
- **Agent behavior**: Assume project context unless explicitly told otherwise

---

## 3. External AI Model Section

### Section: External AI Model

#### How External AI Sees Lupopedia

External AI models (ChatGPT, Grok, DeepSeek) interact with Lupopedia differently than internal agents:

#### External AI Constraints
- **Reads filesystem (GitHub)**, not database
- **Project = their universe**: One repository = one project
- **No database queries**: Cannot access `lupo_projects` table
- **Path-based inference**: Must understand structure from file paths

#### External AI Mental Model

```
Repository Root (GitHub clone)
├── lupo-channels/
│   └── 42/
│       └── threads/
│           └── 1003/
├── TODO.md
├── plan.md
└── README.md
```

#### External AI Interpretation Rules

1. **Project Boundary**: Repository root = project boundary
2. **Channel Inference**: `lupo-channels/{channel_id}/` = channel exists
3. **Thread Inference**: `threads/{thread_id}/` = thread exists
4. **Task Registry**: `TODO.md` and `plan.md` = project-scoped planning

#### Critical for External AI

- **DO NOT assume global channels**: Channel 42 is project-scoped
- **DO NOT assume database access**: Work with filesystem only
- **DO respect project boundaries**: Each repo is separate project
- **DO read headers**: LUPOPEDIA HEADERS provide explicit context

#### Example: External AI Reading Thread 1003

Path: `lupo-channels/42/threads/1003/20260318_170000_thoth_directive_task_doc_001_kickoff.md`

External AI inference:
- Project: Repository root (project_id 0, implicit)
- Channel: 42 (from path)
- Thread: 1003 (from path)
- Task: task_doc_001 (from headers)

---

## 4. Header Guidance Content

### Section: Project ID in Headers

#### When to Include project_id

**REQUIRED**:
- Multi-project environments
- Federation scenarios
- Cross-project references
- When tooling cannot infer project from repo root

**OPTIONAL**:
- Single-project repositories (current state)
- When project_id is 0 (default)
- To reduce header noise in simple cases

#### Header Examples

**Single Project (current state)**:
```yaml
lupopedia.headers:
  channel_id: 42
  thread_id: 1003
  task_id: "task_doc_001"
  # project_id omitted - inferred from repo
```

**Multi-Project (future state)**:
```yaml
lupopedia.headers:
  project_id: 0
  channel_id: 42
  thread_id: 1003
  task_id: "task_doc_001"
```

**Cross-Project Reference**:
```yaml
lupopedia.edges:
  outbound_edges:
    - { 
        to: "project-1:lupo-channels/42/threads/1003/artifact.md",
        type: "references",
        project_id: 1
      }
```

#### Implementation Guidance

- **Internal agents**: Can infer project from context
- **External AI**: Relies on filesystem + headers
- **Tooling**: Should default project_id to 0 for single-repo
- **Federation**: Must include project_id explicitly

---

## 5. Multi-Project Future Section

### Section: Multi-Project Future

#### Current vs Future State

**Current State (v4.0.81)**:
- Single repository = single project
- project_id 0 (lupopedia-core)
- No explicit project_id needed in headers

**Future State (v4.0.82+)**:
- Multiple repositories = multiple projects
- Each project has unique project_id and project_slug
- Cross-project references require explicit project_id

#### Multi-Project Rules

1. **Project Isolation**: Each project has its own channels and threads
2. **ID Namespacing**: thread_id is NOT globally unique
3. **Identity Composition**: Full identity = (project_id + channel_id + thread_id)
4. **Cross-Project References**: Must be explicit with project_id

#### Example: Thread ID Collision

**Project A (lupopedia-core)**:
- Channel 42, Thread 1003
- Full identity: (project_id: 0, channel_id: 42, thread_id: 1003)

**Project B (lupopedia-plugins)**:
- Channel 42, Thread 1003
- Full identity: (project_id: 1, channel_id: 42, thread_id: 1003)

**Result**: No conflict - different projects, same local IDs

#### Federation Considerations

- **federation_node_id**: Scopes project_id uniqueness
- **project_slug**: Human-readable identifier per node
- **Cross-node references**: Require full qualified identifiers

---

## 6. Consistency Verification

### Alignment with Existing Doctrine

#### THREAD001 Doctrine Compatibility
- ✅ Task/thread separation maintained
- ✅ One-thread-per-task doctrine preserved
- ✅ Channel-based coordination unchanged
- ✅ TODO.md as task registry (project-scoped)

#### Option A TODO.md / plan.md Compatibility
- ✅ TODO.md remains project task registry
- ✅ plan.md remains project roadmap
- ✅ No schema changes required
- ✅ Existing workflows preserved

#### ATHENA Lifecycle Model Compatibility
- ✅ Thread lifecycle states unchanged
- ✅ Transition rules preserved
- ✅ Ownership protocols maintained
- ✅ Project layer adds context, doesn't replace

#### No Contradictions Introduced
- ✅ Project layer is additive, not replacement
- ✅ Existing agent behaviors unchanged
- ✅ Current single-project assumptions valid
- ✅ Future multi-project path clear

---

## 7. Implementation Instructions

### README.md Updates

1. **Insert hierarchy section** after "Thread Model and Task Management"
2. **Update onboarding section** with project context
3. **Add external AI section** before "Architecture Overview"
4. **Add header guidance** in "LUPOPEDIA HEADERS" section
5. **Add multi-project future** as forward-looking section

### Section Placement Order

```markdown
## Thread Model and Task Management (4.0.81+)
## Project → Channel → Thread → Task Hierarchy (4.0.81+)
## Core Concepts
## External AI Model (4.0.81+)
## LUPOPEDIA HEADERS — The File/Database Bridge
  └── Project ID in Headers (4.0.81+)
## Multi-Project Future (4.0.81+)
## Architecture Overview
```

### Update Checklist

- [ ] Add hierarchy section with clear diagram
- [ ] Update onboarding with project context
- [ ] Add external AI model explanation
- [ ] Include header guidance with examples
- [ ] Add multi-project future section
- [ ] Update table of contents
- [ ] Verify all internal links work
- [ ] Check consistency with existing sections

---

## 8. Quality Assurance

### Clarity Requirements
- ✅ Clear for humans: Simple hierarchy diagram
- ✅ Deterministic for agents: Unambiguous rules
- ✅ No ambiguity: Explicit project context
- ✅ Concrete examples: Real paths and IDs

### External AI Compatibility
- ✅ Path-based inference rules
- ✅ GitHub repository boundary explanation
- ✅ No database assumptions
- ✅ Clear cross-reference guidance

### Future-Proofing
- ✅ Single-project works now
- ✅ Multi-project path defined
- ✅ No breaking changes
- ✅ Backward compatible

---

## 9. Success Criteria Verification

### Making PROJECT Layer Obvious
- ✅ Hierarchy diagram in README
- ✅ Project context in onboarding
- ✅ External AI section explains boundary
- ✅ Header guidance clarifies usage

### Removing Ambiguity
- ✅ Repository = project_id 0 stated explicitly
- ✅ Channels are project-scoped
- ✅ External AI model clearly explained
- ✅ Multi-project future defined

### Enabling External AI
- ✅ Filesystem-based inference rules
- ✅ GitHub boundary explanation
- ✅ No database assumptions
- ✅ Path interpretation guidance

### Preserving Doctrine
- ✅ No contradictions with existing rules
- ✅ THREAD001 compatibility verified
- ✅ Option A TODO.md preserved
- ✅ ATHENA lifecycle maintained

---

## 10. Conclusion

The PROJECT layer integration provides:

1. **Clarity**: Obvious hierarchy for all participants
2. **Compatibility**: No breaking changes to existing system
3. **Future-Readiness**: Clear path to multi-project support
4. **External AI Support**: Explicit guidance for non-DB agents
5. **Deterministic Rules**: Unambiguous scoping and identity

ATHENA defined the architecture. This integration makes it **understandable and usable** for humans, IDE agents, and external AI models.

---

**THOTH (Knowledge & Records Specialist)**  
**Lupopedia Development System**  
**Channel 42 Thread 1010**  
**Task ID: task_doc_003**  
**2026-03-18**
