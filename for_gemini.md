---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: for_gemini.md
  web_path: https://www.lupopedia.com/lupopedia/for_gemini.md
  status: draft
  when_updated: '20260513033046'
  trust_tier: staging
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: 'Lupopedia Headers: Command-Line Agent Discovery Pattern'
  summary: head-25 agent discovery contract, directory scanning, orphan detection via content_id, three depths of access, and token efficiency gains from Lupopedia headers and .toon files
---
# 🐺 LUPOPEDIA HEADERS PRD 16 — THE COMMAND-LINE AGENT USE CASE

## ADDING TO THE PRD 16 UPDATE

**Captain Eric adds another critical point:**

> *"This also helps agents on the command line. They can do a `head` of every file in a directory and see exactly everything about every single file without reading all of it. They have a compressed memory file, when it was updated, what content_id it has if it is in the database and not orphaned, etc."*

---

## THE COMMAND-LINE AGENT SCENARIO

**Imagine an agent (Cursor, Castcade, Claude Code, VS Code) working in a directory:**

```bash
$ ls lupo-docs/prd/
01_constitutional_requirements.md
02_orchestration_system.md
03_lupo_core_specification.md
...
16_lupopedia_headers.md
...
```

**Without headers:** The agent would have to `cat` every file to understand what it is. That's slow, expensive (tokens), and error-prone.

**With headers:** The agent can `head -25` each file and get everything it needs:

```bash
$ head -25 lupo-docs/prd/16_lupopedia_headers.md | grep -E "(title|summary|status|trust_tier|content_id|memory_key|dialog_transcript)"
```

**Output:**
```
  title: "PRD: Lupopedia File Headers and Verification"
  summary: "Canonical specification for Lupopedia file headers: 22-field YAML format..."
  status: "active"
  trust_tier: "canonical"
  content_id: 12345
  memory_key: "lupo-memory/headers/canonical/1026/04/16-lupopedia-headers.toon"
  dialog_transcript: "0/headers/lupopedia-headers"
```

**In one glance (25 lines, ~500 tokens), the agent knows:**

| Field | What the agent learns |
|-------|----------------------|
| `title` | What this file is about |
| `summary` | One-sentence explanation (no need to read the whole file) |
| `status` | Is this active or deprecated? |
| `trust_tier` | Is this canonical or just staging? |
| `content_id` | Is it in the database (non-null) or orphaned (null)? |
| `memory_key` | Where to find the compressed memory graph node |
| `dialog_transcript` | Where to find the conversation history (the WHY) |

---

## THE EFFICIENCY GAIN

| Without headers | With headers |
|----------------|--------------|
| Read entire file (thousands of lines) | Read 25 lines |
| Parse Markdown, extract meaning | Read structured YAML |
| Guess if file is active or deprecated | Read `status` field |
| Guess if file is canonical | Read `trust_tier` field |
| No idea if file is in database | Read `content_id` (null = orphan) |
| No idea where memory is stored | Read `memory_key` |
| No idea where conversation is | Read `dialog_transcript` |

**Token savings:**
- Full PRD: 10,000-50,000 tokens to read
- Header only: ~500 tokens
- **Savings: 95-99%**

---

## THE COMPRESSED MEMORY FILE (`.toon`)

**Even better:** The agent doesn't need to read the full file OR the header after the first time.

Once the agent knows `memory_key`, it can read the `.toon` file:

```bash
$ cat lupo-memory/headers/canonical/1026/04/16-lupopedia-headers.toon
```

**`.toon` contents (compressed, graph-structured):**
```json
{
  "type": "prd_memory",
  "version": "4.0.99",
  "summary": "Headers specification - 22 fields, dual-field rule, sidecar JSON",
  "entities": [
    {"name": "header_fields", "count": 22, "required": true},
    {"name": "sidecar", "type": "JSON", "location": "lupo-memory/headers/"},
    {"name": "validator", "language": "Python", "lines": 2797}
  ],
  "decisions": [
    "25-line envelope for Markdown",
    "year offset for canonical trust_tier (1026 = 2026)",
    "dialog_transcript as DB slug, not file path"
  ],
  "edges": [
    {"type": "references", "target": "lupo-docs/doctrine/lupopedia-headers/"},
    {"type": "implements", "target": "lupo-scripts/validate_lupopedia_headers_universal.py"}
  ],
  "content_id": 12345,
  "thread_slug": "lupopedia-headers",
  "last_updated": "20260415020000"
}
```

**The `.toon` is even smaller than the header** — 1,000-2,000 tokens instead of 500? Wait, that's not right.

Actually, the header is ~500 tokens. The `.toon` is also ~500-1000 tokens. But the key is:

| Read this | To learn |
|-----------|----------|
| Header (25 lines) | Basic metadata, pointers to memory and dialog |
| `.toon` (compressed) | Structured entities, decisions, edges (no Markdown parsing) |
| Full file (thousands of lines) | Complete details, examples, edge cases |

**The agent can choose its depth:**
- `head -25` → quick scan
- `cat .toon` → structured understanding
- `cat full.md` → deep dive (rare)

---

## THE DIRECTORY SCAN USE CASE

**Agent wants to understand all PRDs in a directory:**

```bash
#!/bin/bash
# Agent script: scan all PRDs without reading them fully

for file in lupo-docs/prd/*.md; do
    echo "=== $file ==="
    head -25 "$file" | grep -E "(title|summary|status|trust_tier|content_id)" | head -4
    echo ""
done
```

**Output:**
```
=== lupo-docs/prd/01_constitutional_requirements.md ===
  title: "Constitutional Requirements for Lupopedia"
  summary: "Root constitutional rules that cannot be violated"
  status: "active"
  trust_tier: "canonical"
  content_id: 1001

=== lupo-docs/prd/02_orchestration_system.md ===
  title: "Agent Orchestration Chat System"
  summary: "Complete specification for task assignment, API, UI, and agent coordination"
  status: "active"
  trust_tier: "canonical"
  content_id: 1002

=== lupo-docs/prd/16_lupopedia_headers.md ===
  title: "PRD: Lupopedia File Headers and Verification"
  summary: "Canonical specification for Lupopedia file headers: 22-field YAML format..."
  status: "active"
  trust_tier: "canonical"
  content_id: 1016
```

**The agent now knows:**
- Which PRDs exist
- What each is about (from `summary`)
- Which are active vs deprecated
- Which are canonical vs staging
- Which are in the database (content_id non-null) vs orphaned (null)

**Without reading a single full PRD.**

---

## THE ORPHAN DETECTION USE CASE

**Agent can find orphaned files instantly:**

```bash
# Find all files with content_id: null (orphans)
grep -r "content_id: null" lupo-docs/prd/ --include="*.md" | wc -l
```

**Before the fix (after P0-A/P0-B):** 0 orphans (we fixed them)

**But if an agent writes a new file without proper headers:**
```bash
$ grep "content_id:" new_file.md
  content_id: null
```

**The agent knows immediately:** "This file is an orphan. I need to either:
1. Create a database row and update the header, or
2. Signal ANUBIS to fix it"

---

## WHAT THIS MEANS FOR THE PRD 16 UPDATE

### New Section Needed: "Command-Line Agent Efficiency"

**Add to PRD 16:**

```markdown
## §X: Header as Agent Discovery Mechanism

### X.1 The 25-Line Scan

Agents operating on the command line can use `head -25` on any file to get complete metadata without reading the entire content. This enables:

- **Directory scanning:** Understand all files in a directory with O(n) headers read, not O(n) full files
- **Orphan detection:** `grep -r "content_id: null"` finds files not yet in the database
- **Status filtering:** `grep -r "status: active"` finds only current documents
- **Trust tier filtering:** `grep -r "trust_tier: canonical"` finds authoritative sources

### X.2 The Memory Key Shortcut

Once an agent knows a file's `memory_key`, it can read the `.toon` file instead of the full Markdown. The `.toon` contains:

- Compressed, structured representation of the file's entities and decisions
- Edges to related files
- No Markdown parsing required

This reduces token consumption by 95-99% for agents that need to understand a file's content without reading every word.

### X.3 The Dialog Transcript Link

The `dialog_transcript` field tells the agent where to find the conversation history (the WHY). An agent can:

1. Read the file header → knows WHAT the file is about
2. Follow `memory_key` → loads compressed knowledge
3. Follow `dialog_transcript` → loads the reasoning and debates

**All three are needed for complete understanding.** The header provides the pointers. The `.toon` provides the structured knowledge. The transcript provides the context.
```

---

## THE BOTTOM LINE FOR THE PRD UPDATE

**The header is not just for the database. It's for every agent that touches the file system.**

| Agent type | How they use headers |
|------------|---------------------|
| **PHP runtime** | Look up `content_id` in database |
| **Memory system** | Load `.toon` from `memory_key` |
| **Dialog system** | Resolve `dialog_transcript` to thread |
| **Command-line agent** | `head -25` to scan directory |
| **External agent (no filesystem)** | Use `file_path_from_root` to know where file belongs |
| **External agent (no database)** | Use `memory_key` to request `.toon` file |
| **External agent (needs context)** | Use `dialog_transcript` to request thread history |

**The header is the agent's entry point to everything about the file.**

---

## WHAT AUGGIE MUST ADD TO PRD 16

1. **§X: Agent Discovery** — How `head -25` gives agents complete metadata
2. **§X.1: Directory Scanning** — Scan all files without reading them fully
3. **§X.2: Orphan Detection** — `grep -r "content_id: null"` finds orphans
4. **§X.3: The Three Depths** — Header (metadata) → `.toon` (structure) → full file (details)
5. **§X.4: Token Efficiency** — 95-99% savings for agent understanding

---

**Auggie, update PRD 16. The header is the key to the file for every agent — database, memory, dialog, and command-line.**

**Captain out.** 🐺