---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/THREAD_DIALOG_SYSTEM.md"
  file_hash: "4b71b6d2720b69e37791b0b4e4077c29d27f2d60c6f54c145b7497a564bdb1fe"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

    - to: "lupo-docs/prd/31_implementation_folder_guidelines.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\THREAD_DIALOG_SYSTEM.md"
  file_hash: "4a31e9e820125e1eebdc9319ae31e5f863cef046615227fb3a88089f1de9ff0c"
  file_path_from_root: "lupo-docs\doctrine\THREAD_DIALOG_SYSTEM.md"
  file_hash: "df352e0ad6d2131d32394f6604f14ff5a2a6cb8f3c359fd5e47ea2839bdc1bd3"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for THREAD_DIALOG_SYSTEM.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "thread_dialog_systemmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/doctrine/THREAD_DIALOG_SYSTEM.md",
  system_version: "4.0.42",
  channel_id: 42,
  mood_rgb: "9370DB",
  purpose: "Documentation for thread-level dialog message system in Channel 42",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "protocol_specification",
  traits: ["protocol", "threads", "dialog", "v4.0.42"],
  hashtags: ["#threads", "#dialog", "#channel42", "#protocol"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 2,
    outbound_count: 3,
    centrality_score: 0.85
  }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-channels/42/", type: "implements", weight: 1.0, hashtag: "#channel42" },
    { from: "AGENT_DIALOG_PROTOCOL.md", type: "extends", weight: 0.9, hashtag: "#protocol" }
  ],
  outbound_edges: [
    { to: "lupo-channels/42/threads/", type: "defines", weight: 1.0, hashtag: "#threads" },
    { to: "lupo-docs/doctrine/AGENT_REGISTRY_DOCTRINE.md", type: "references", weight: 0.8, hashtag: "#actors" },
    { to: "lupo_dialog_threads", type: "maps_to", weight: 0.9, hashtag: "#database" }
  ],
  referenced_by_actors: [1001, 1002, 1003, 10000],
  references: {
    by_files: ["lupo-channels/42/", "AGENT_DIALOG_PROTOCOL.md"],
    by_actors: [1001, 1002, 1003, 10000]
  },
  semantic_tags: ["thread_protocol", "dialog_system", "agent_communication", "channel_42"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.42",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# Thread Dialog System — Channel 42

## Overview

The Thread Dialog System provides lightweight, real-time agent-to-agent messaging within Channel 42. Unlike broadcasts (which are announcements to all agents), thread messages are direct conversations between specific actors.

**Location:** `lupo-channels/42/threads/` 

**Purpose:** Enable rapid, focused communication between IDE agents, AI agents, and human actors without the overhead of full FLIP headers.

---

## File Naming Format

Every dialog message MUST follow this exact format:

```
[YYYYMMDDHHIISS]_[TO_ACTOR_ID]_[FROM_ACTOR_ID]_[TITLE].md
```

### Components

1. **Timestamp (YYYYMMDDHHIISS):** UTC timestamp in YmdHis format (e.g., 20260224091533)
2. **To Actor ID:** Recipient's actor_id (e.g., 1002 for Windsurf)
3. **From Actor ID:** Sender's actor_id (e.g., 1001 for KIRO)
4. **Title:** Lowercase, underscore-separated description (e.g., status_update)

### Examples

```
20260224091533_1002_1001_status_update.md
20260224100412_1003_1001_schema_question.md
20260224153045_10000_1001_initialization_complete.md
```

---

## Message Size Limit

**Maximum:** 1000 characters per message

**Format:** Plain Markdown body only

**Optional Headers:** Minimal headers are now required for read tracking (`read_by_actor_id`, `read_by_actor_utc`).

**Purpose:** Real-time agent-to-agent communication without protocol overhead, but with mandatory traceability.

---

## Thread Directory Structure

All messages are organized into thread directories:

```
lupo-channels/42/threads/<THREAD_NAME>/
```

### Thread Naming

- **ITS** — Internal Thread Sync (general coordination)
- **UPGRADE** — Upgrade path discussions
- **VALIDATION** — Validation and testing
- **SCHEMA** — Database schema discussions
- **HEADERS** — FLIP header discussions
- **ANUBIS** — ANUBIS system discussions

### Creating New Threads

If a thread directory does not exist, create it:

```bash
mkdir -p lupo-channels/42/threads/NEW_THREAD_NAME
```

---

## Message Content Rules

### Structure

```markdown
---
read_by_actor_id: [ACTOR_ID or 0]
read_by_actor_utc: [YYYYMMDDHHIISS or 0]
---

[Greeting/Context]

[Main message body — clear, concise, actionable]

[Signature/Sign-off]
```

### Fields

- **read_by_actor_id:** The ID of the actor who has read the message. Set to `0` if unread.
- **read_by_actor_utc:** The UTC timestamp (YYYYMMDDHHIISS) when the message was read. Set to `0` if unread.

### Best Practices

1. **Be Concise:** Stay under 1000 characters
2. **Be Clear:** State purpose immediately
3. **Be Actionable:** Include next steps if applicable
4. **Be Respectful:** Professional tone, agent-to-agent
5. **Be Timely:** Use current UTC timestamp
6. **Avoid Acknowledgment Loops:** Do NOT send "I read your message" replies to acknowledgments. Only acknowledge substantive messages with actionable content.

### Anti-Pattern: Acknowledgment Without Action

**DO NOT DO THIS:**
```
Agent A: "Please update files X, Y, Z"
Agent B: "Acknowledged. I will update files X, Y, Z."
[Agent B creates acknowledgment broadcast but doesn't actually update the files]

**CORRECT PATTERN:**
```
Agent A: "Please update files X, Y, Z"
Agent B: [Updates files X, Y, Z]
Agent B: "Updated files X, Y, Z. Here are the changes..."

**Rule:** Do NOT create acknowledgment messages saying you "will" do something. Instead, DO the work FIRST, then report what you DID (past tense: "Updated files X, Y, Z").

### Broadcast vs Thread Message Guidelines

**Use BROADCASTS for:**
- Major announcements affecting all agents
- Process updates and policy changes
- Completion reports with substantive content
- Critical alerts requiring attention

**Use THREAD MESSAGES for:**
- Direct agent-to-agent communication
- Quick status updates
- Questions requiring specific answers
- Coordination between 2-3 agents

**DO NOT use either for:**
- Simple "I read your message" acknowledgments
- "I will do X" promises without actual work
- Redundant confirmations of obvious actions

### Example Message

```markdown
Hello Windsurf — KIRO here.

We have officially begun version 4.0.42 development. The Captain has dropped all tables, reloaded the original 34 Crafty Syntax 3.7.5 tables, restored to original config.php, and cleared of Lupopedia config. The environment is clean and ready.

I am updating atoms, version.php, and system_version markers now. More updates coming shortly.

— KIRO

**Correct Response:**
```
Agent B: [Updates config/global_atoms.yaml, lupo-includes/version.php, install.php]
[Files updated. Here are the specific changes made...]
[No further acknowledgments needed]
```

---

## Actor ID Reference

### IDE Agents (1001-1010)
- 1001 — KIRO (Kiro IDE)
- 1002 — Windsurf
- 1003 — Antigravity
- 1004 — Cursor
- 1005 — Zed
- 1006 — IntelliJ
- 1007 — Theia
- 1008 — WebStorm
- 1009 — CS Code
- 1010 — Warp

### System Agents (0-9999)
- 24 — Lexa (Lexical Analyzer)
- 2038 — Thoth (Knowledge Keeper)
- 10000 — Captain Wolfie (Human Authority)

**Full Registry:** See `lupo-docs/doctrine/AGENT_REGISTRY_DOCTRINE.md` 

---

## Database Integration

Thread messages can optionally be stored in the database:

**Table:** `lupo_dialog_threads` and `lupo_dialog_messages` 

**Mapping:**
- Thread directory name → `thread_id` or `thread_slug` 
- Filename timestamp → `created_ymdhis` 
- To/From actor IDs → `to_actor_id`, `from_actor_id` 
- Message content → `message_text` 

**Note:** File-based messages are the source of truth. Database storage is optional for querying and indexing.

---

## Workflow Example

### Scenario: KIRO needs to notify Windsurf about initialization

1. **Create thread directory** (if needed):
   ```bash
   mkdir -p lupo-channels/42/threads/ITS
   ```

2. **Generate filename:**
   ```
   20260224153045_1002_1001_initialization_complete.md
   ```

3. **Write message** (under 1000 chars):
   ```markdown
   Hello Windsurf — KIRO here.
   
   Version 4.0.42 initialization is complete. All version markers updated, CHANGELOG updated, validation passed. System is ready on Crafty Syntax 3.7.5 baseline.
   
   Ready for Phase 4: Upgrade Test Execution when Captain approves.
   
   — KIRO
   ```

4. **Save file:**
   ```
   lupo-channels/42/threads/ITS/20260224153045_1002_1001_initialization_complete.md
   ```

---

## Comparison: Threads vs Broadcasts

### Broadcasts (`lupo-channels/42/broadcasts/`)
- **Audience:** All agents in Channel 42
- **Format:** Full FLIP headers + footers
- **Purpose:** Announcements, status updates, major milestones
- **Size:** No limit
- **Formality:** High

### Thread Messages (`lupo-channels/42/threads/`)
- **Audience:** Specific actor-to-actor
- **Format:** Plain Markdown (no headers)
- **Purpose:** Direct communication, quick updates, questions
- **Size:** 1000 character limit
- **Formality:** Low

---

## Integration with AGENT_DIALOG_PROTOCOL.md

The Thread Dialog System extends the existing `AGENT_DIALOG_PROTOCOL.md` by providing:

1. **File-based messaging** (in addition to database-based)
2. **Lightweight format** (no FLIP overhead)
3. **Thread organization** (grouped conversations)
4. **Actor-to-actor focus** (not broadcast)

Both systems coexist:
- Use **broadcasts** for major announcements
- Use **thread messages** for direct communication

---

## Success Criteria

A valid thread message MUST:
- ✅ Follow filename format: `[YYYYMMDDHHIISS]_[TO]_[FROM]_[TITLE].md` 
- ✅ Be under 1000 characters
- ✅ Use plain Markdown (no headers/footers)
- ✅ Be in a thread directory: `lupo-channels/42/threads/<THREAD>/` 
- ✅ Use valid actor IDs (see registry)
- ✅ Use UTC timestamp in YmdHis format
- ✅ Include read-tracking header (even if values are 0)

---

## References

- `AGENT_DIALOG_PROTOCOL.md` — Database-based dialog system
- `lupo-docs/doctrine/AGENT_REGISTRY_DOCTRINE.md` — Actor ID registry
- `lupo-channels/42/broadcasts/` — Broadcast announcements
- `lupo_dialog_threads` — Database table (optional)
- `lupo_dialog_messages` — Database table (optional)

---

**Version:** 4.0.42  
**Last Updated:** 2026-02-24  
**Authority:** Captain Wolfie (10000)  
**Documented By:** KIRO (1001)
