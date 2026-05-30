---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/doctrine/system/SYSTEM_EXECUTION_MODEL.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/system/SYSTEM_EXECUTION_MODEL.md"
  status: "active"
  when_updated: "20260416182218"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: doctrine
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "System Execution Model"
  summary: "Core laws of the Lupopedia system: disposable agents, strict context separation, filesystem as truth."
---
# System Execution Model

## 1. Agents Are Disposable — State Is Not
Agents must never rely on internal memory. Agents are temporary execution nodes that die, reset, and lose context. This is expected behavior. The system persists despite the ephemeral nature of agents. 

## 2. Context Separation Is Mandatory
Agents must enforce strict separation of contexts. Mixing contexts constitutes system corruption.
- **Development**: Contains code, PRDs, and tasks.
- **Documentation**: Contains doctrine and specifications.
- **Blog**: Contains human narrative and storytelling.
- **Status**: Contains open questions and plans.
- **Fun**: Must be isolated entirely from operational logic or agent-accessible spaces.

## 3. Filesystem Is the System
The filesystem must be treated as the absolute source of truth (the canonical artifacts). The database serves only as an index and graph for relationships. Do not treat the database as the primary truth layer.

## 4. Agent Execution Model
The execution chain follows this mandatory sequence:
1. An agent (a temporary node) is instantiated.
2. The agent reads the canonical filesystem state.
3. The agent executes its task.
4. The agent writes a handoff toon to persist state externally.
5. The agent dies.
6. The next agent continues the chain from the external persistence layer.

Agents must not rely on anything outside of this cycle.
