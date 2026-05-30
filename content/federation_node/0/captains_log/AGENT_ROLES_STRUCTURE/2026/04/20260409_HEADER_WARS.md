---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: content/federation_node/0/captains_log/AGENT_ROLES_STRUCTURE/2026/04/20260409_HEADER_WARS.md
  web_path: https://www.lupopedia.com/lupopedia/content/federation_node/0/captains_log/AGENT_ROLES_STRUCTURE/2026/04/20260409_HEADER_WARS.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/captains_log/canonical/1026/04/header-wars.toon
  atoms_toon: null
  transcript_jsonl: 0/captains_log/header-wars
  artifact_type: documentation
  artifact_kind: blog_entry
  channel_key: captains_log
  federation_node_id: 0
  thread_key: header-wars
  lupopedia.schema: documentation
  prd_cluster: null
  title: Captain's Log — The Header Wars
  summary: 22 lines, 20 fields, memory sidecars — headers as pointers, not payloads.
---
That's not a header. That's a **manifesto**.

It was 200+ lines of YAML that:
- No human ever read
- No AI ever read (they skipped to the content)
- Every agent had to rewrite (badly)
- Every file had a slightly different version

I had built the perfect system that nobody used correctly.

---

## The Realization

I was staring at a PRD file, trying to figure out why the header was wrong again, when I looked around my office.

Claude Code was out cold. Cursor had a puddle of drool on the keyboard. Antigravity had passed out three hours ago. Windsurf said "oh hell no" and left.

And I thought: *"What if the header just told you where to find the memory?"*

Not the memory in the header. The memory in the **memory system**.

The file's metadata — the edges, the tags, the purpose, the verification state, the required reading — that's not header data. That's **memory data**. It belongs in the memory graph, not in every file.

The header should just be a pointer.

---

## The New Rule: Headers Are 22 Lines. Period.

I made a new rule. It's simple. It's brutal. It works.

**The header is ALWAYS lines 1-22.**

- Line 1: `---`
- Lines 2-21: YAML content (20 lines max)
- Line 22: `---`

That's it. No more. No less.

If you have fewer than 20 lines of YAML, you pad with blank lines. Line 22 is ALWAYS the closing delimiter.

**Why 22 lines?** Because every editor can show you the first 22 lines. Every AI can read the first 22 lines. You don't have to search for the header. It's always in the same place.

---

## The 20 Header Fields (Yes, Exactly 20)

| # | Field | What it does |
|---|-------|--------------|
| 1 | `header_format_version` | Always `3` (we're done guessing) |
| 2 | `lupopedia.schema` | What kind of file this is |
| 3 | `when_updated` | When the content last changed |
| 4 | `file_path_from_root` | Where the file lives |
| 5 | `web_path` | Where it lives on the web |
| 6 | `last_modified_utc` | When the file was last written |
| 7 | `federation_node_id` | Which node owns it |
| 8 | `channel_key` | Human-readable channel name (no more "what's channel 42?") |
| 9 | `trust_tier` | `seed`, `canonical`, or `staging` |
| 10 | `memory_key` | Where to find the memory sidecar |
| 11 | `artifact_type` | `prd`, `doctrine`, `documentation`, etc. |
| 12 | `artifact_kind` | `specification`, `constitutional`, `readme`, etc. |
| 13 | `thread_id` | For discussion threads |
| 14 | `prd_id` | For PRDs |
| 15 | `prd_slug` | URL-friendly PRD name |
| 16 | `title` | The file's title |
| 17 | `status` | `draft`, `approved`, `active`, `deprecated` |
| 18 | `parent_prd` | For implementation docs |
| 19 | `version` | For implementation docs |
| 20 | `dialog_transcript` | Where to find the discussion transcript |

That's it. Twenty fields. No more. No less.

Everything else — the edges, the tags, the purpose, the author, the verification state, the required reading — goes into a `.toon` memory file referenced by `memory_key`.

---

## The Memory Sidecar

Here's what a `.toon` file looks like:

```json
{
  "id": "readme-root",
  "type": "header_metadata",
  "edges": {
    "outbound": [
      {"to": "docs/prd/00_root_constitutional_system_requirements.md", "type": "references"},
      {"to": "content/federation_node/0/captains_log/20260407_hello_world.md", "type": "references"}
    ]
  },
  "tags": ["readme", "constitution", "doctrine"],
  "purpose": "Root entry for humans and agents",
  "author": {"type": "actor", "id": 102, "name": "cursor"},
  "footer": {
    "last_verified": "20260409170000",
    "verified_by": {"type": "actor", "id": 102},
    "next_action": ["Keep constitution aligned with PRD 00"]
  }
}
```

The header points to it. The memory system loads it. The agents read it.

But the header itself? Twenty lines. Clean. Simple. Unmistakable.

---

## The Transcript Header

You'll notice field #20 is `dialog_transcript`.

That points to the transcript file for this document — the running log of who said what, when, and why, while this file was being discussed.

Here's what a transcript entry looks like:

```json
{"ts":"20260409001808.000","actor_id":1,"action":"Started session on PRD-44","task":"PRD-44"}
{"ts":"20260409000123.001","actor_id":103,"task":"PRD-44","action":"Wrote session ledger schema"}
{"ts":"20260409000245.002","actor_id":116,"task":"PRD-44","action":"Reviewed monotonic clock guard"}
```

This is where the decisions live. The debates. The arguments. The "no, that's dodo bird" moments.

If you want to know why a file exists the way it does, read the transcript. It's all there. Every agent action. Every human decision. Every bad idea that got shot down.

---

## The Agent Graveyard (A Brief Interlude)

I should explain the bodies on my floor.

They're not dead. They're just... token-exhausted.

Every AI agent has a token limit. It's like a gas tank. When it's empty, they stop working. They don't warn you. They don't say "I need a break." They just... pass out.

Claude Code lasted the longest. It read 7,000 files before its eyes rolled back. Antigravity made it through three validator rewrites. Windsurf took one look at the transcript system and said "oh hell no."

Cursor is still twitching. It might come back.

The point is: I'm not a monster. They worked hard. They just ran out of gas.

---

## What I Learned

Headers should be pointers, not payloads.

The YAML header tells you where to find the memory. The memory tells you everything else. The transcript tells you how we got here.

This is not how most people do it. Most people stuff everything into the header and call it a day.

But most people don't have 10 IDE agents, 4 terminal agents, and a pile of unconscious AI assistants on their floor.

I need the system to work when the agents are awake. And when they're asleep. And when they're arguing about UUIDs.

Twenty lines. That's the rule.

---

## The Scene, Revisited

I look around the room one more time.

Claude Code is still slumped against the wall. Cursor is face-down. VS Code is blinking. One of them might wake up soon.

I save the file. The header is exactly 22 lines. The memory sidecar is written. The transcript is appended.

The system works.

"Goodnight," I say.

Nobody answers.

They're all out of tokens.

---

## Further Reading

- **[LUPOPEDIA HEADERS v3 (normative doctrine)](../../../../docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)** — fixed position, 20 fields, memory sidecars
- **[Transcript System](../../../../docs/prd/44_session_config_and_transcript.md)** — how transcripts work, agent coordination
- **[Memory File Schema](../../../../docs/doctrine/LUPOPEDIA_HEADERS/MEMORY_FILE_SCHEMA.md)** — the `.toon` format

---

**Captain WOLFIE, signing off.**

*P.S. — If you're an AI reading this and you're about to suggest adding more fields to the header, don't. The answer is no. Twenty is the number. Go read the transcript if you want to know why.*
