---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/44_A_SESSION_CONFIG_AND_TRANSCRIPT.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/44_A_SESSION_CONFIG_AND_TRANSCRIPT.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/44_session_config_and_transcript.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/session-config-and-transcript
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_44_A_SESSION_CONFIG_AND_TRANSCRIPT
  title: "1. Standard Agent Operating Process (IMPORTANT)"
  summary: null
---
# 1. Standard Agent Operating Process (IMPORTANT)

All actors/agents entering a workspace **MUST** follow this strict execution sequence:

1. **Update Time**: Run `python lupo-bin/tick.py` to establish the current UTC operating time.
2. **Read Tasks**: Check the active channel's `tasks/` folder for tasks addressed specifically to your `actor_id` (`python lupo-bin/pending.py --actor 103 --check`).
3. **Execute**: Complete the assigned work.
4. **Log**: Write to the channel's `transcript.jsonl` using `transcript.py`.
5. **(Optional) Delegate**: Write tasks to other actors via `pending.py` (which targets `tasks/` and logs to `transcript.jsonl`) or structured `actions/`.

---

# 2. Channel Architecture Convergence

The `transcript.jsonl`, `tasks/` queue, and `actions/` queue do **NOT** belong in `lupo-config/`. Instead, they are contextual and belong to the active interaction space (the thread/channel).

Active state is driven by the `lupo-channels/{federation_node_id}/{channel_key}/{slug}/` architecture.

## Session Configuration
To avoid passing flags constantly, the **global IDE session** is pinned in `lupo-config/session.json`:
```json
{
  "version": "4.0.97",
  "active_federation_node": 0,
  "active_channel_key": "development",
  "active_slug": "prd_files/44_prd_discussion"
}
```
Scripts normally pull node, channel\_key, and slug from this config, but they can be overridden via command-line flags.

## Thread Directory Structure
All actual data happens inside the channel:
```
lupo-channels/0/development/prd_files/44_prd_discussion/
+-- THREAD_MANIFEST.md        # Standard Thread Context
+-- transcript.jsonl          # The running log of all IDE interaction
+-- session.json              # Historical/archived session ticks (optional)
+-- tasks/                    # Async messages (CLI tool uses "pending")
|   +-- 103_20260409020000_102.json
+-- actions/                  # Structured tasks
    +-- action_001_103.json
```

---

# 3. Agent Identity (Lupopedia Actor System)

Agents derive identity from `lupo-database/lupopedia/actors/registry.json`.
- WOLFIE (Captain) = `1`
- Cursor IDE = `102`
- Antigravity IDE = `103`
- Claude Code = `116`

*(Note: Identity convergence mandates that actors strictly log via their independent `actor_id`. A human operating the orchestrator `1` is distinct from an automated IDE session `102`.)*

Agents run their scripts explicitly passing their actor identity via flags or hardcoded wrappers.

---

# 4. Async Agent Tasks (`pending.py` -> `tasks/`)

Messages and tasks are placed directly into the assigned thread's `tasks/` folder so context is maintained.
Additionally, delegating tasks natively appends to the thread's transcript!

### Format: `lupo-channels/{node}/{channel_key}/{slug}/tasks/{to_actor_id}_{timestamp}_{from_actor_id}.json`

```json
{
  "from_actor_id": 102,
  "to_actor_id": 103,
  "ts": "20260409020000",
  "task": "PRD-44",
  "action": "Implement missing script",
  "message": "It should be in lupo-channels...",
  "status": "pending"
}
```

### Commands

```bash
# Eric (102) leaves message for Antigravity (103) in the active channel OR specific channel
python lupo-bin/pending.py --from 102 --to 103 --federation_node 0 --channel_key development --slug "prd_files/44_prd_discussion" --task PRD-44 --message "Fix the structure"

# Antigravity (103) checks pending tasks in active channel
python lupo-bin/pending.py --actor 103 --check

# Antigravity (103) claims a specific task safely using an instance ID
python lupo-bin/pending.py --actor 103 --claim --id 103_20260409020000_102.json --instance cursor-pid-12345

# Antigravity (103) runs continuously as a background daemon polling for tasks
python lupo-bin/pending.py --actor 103 --daemon --poll-interval 5

# Antigravity (103) checks status of tasks (pending, claimed, resolved)
python lupo-bin/pending.py --actor 103 --status

# Antigravity (103) marks as done
python lupo-bin/pending.py --actor 103 --resolve --id 103_20260409020000_102.json
```

**Transcript Append Side-Effect:** When you issue a task via `pending.py`, it inherently leaves an immutable record on the channel transcript:
`102 sent task to 103 on development: Fix the structure...`

**Locking Mechanism:** To prevent concurrency issues across multiple agents, `transcript.jsonl` writes are protected using atomic cross-platform directory locking (`.lock` directories) ensuring strict sequential execution across parallel IDE processes.

---

# 5. Actions Directory ???????? Structured Tasks

*(Planned / Deferred: `actions.py` scaffolding is currently backlogged; implementers should rely entirely on `tasks/` until explicitly instructed otherwise.)*

For longer-running tasks or multiple steps spanning multiple agents.

### Format: `lupo-channels/{node}/{channel_key}/{slug}/actions/{action_id}_{actor_id}.json`

```json
{
  "id": "action_001",
  "assigned_to": 103,
  "assigned_by": 102,
  "ts": "20260409010000",
  "task": "PRD-44",
  "action": "Audit session config implementation",
  "steps": [
    "Verify session.json schema",
    "Test transcript.py append"
  ],
  "status": "in_progress"
}
```

### Commands

```bash
# Assign action
python lupo-bin/actions.py --from 102 --assign 103 --federation_node 0 --channel_key development --slug "prd_files/44_prd_discussion" --action "Audit implementation"
```

---

# 6. Session & Transcript Execution

The transcript is a channel-specific log file: `lupo-channels/{node}/{channel_key}/{slug}/transcript.jsonl`.
It dynamically locks via `os.mkdir(.transcript.lock)` to safely sequence concurrent agent executions.

### Format
```jsonl
{"ts":"20260409000000.000","actor_id":102,"instance":"cursor-pid-12345","task":"PRD-44","action":"Started session on PRD-44"}
{"ts":"20260409000123.001","actor_id":103,"task":"PRD-44","action":"Wrote session ledger schema"}
```

### Commands

```bash
# Append transcript entry (assumes routing from global session.json if omitted)
python lupo-bin/transcript.py --actor 103 --task PRD-44 --action "Wrote schema"

# (Optional) Log explicit workspace instance PID
python lupo-bin/transcript.py --actor 103 --instance "antigravity-381" --action "Tested schema"

# Dry-run validation
python lupo-bin/transcript.py --actor 103 --action "Test" --dry-run
```

---

**Status:** FULL  
**Key Changes:** Relocated Transcript, Pending/Tasks, and Actions to `lupo-channels/{federation_node_id}/{channel_key}/{slug}/` directories. `transcript.py` fully modernized to align with the `.lock` sequence patterns and dynamic routing logic built initially for `pending.py`.
**Doctrine Alignment:** FULL

This output complies with Lupopedia Constitutional Root Rules.
