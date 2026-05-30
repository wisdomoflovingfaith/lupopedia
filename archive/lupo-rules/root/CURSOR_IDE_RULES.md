---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260406181608"
  file_path_from_root: "lupo-rules/root/CURSOR_IDE_RULES.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/CURSOR_IDE_RULES.md"
  last_modified_utc: "20260406181608"
  federation_node_id: 0
  channel_id: 42
  thread_id: "cursor-ide-rules"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "ide_rules"
  purpose: "Cursor IDE agent operating rules — prevent excessive token usage, scope creep, and unauthorized modifications"
  tags: ["cursor", "ide", "rules", "token_efficiency", "scope_control"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/WOLFIE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Engineering philosophy — Cursor must respect proven code"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
lupopedia.footer:
  last_verified: "20260406181608"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
  next_action:
    - "Load this file before any Cursor operation"
    - "Use shorthand files for context, not full project scans"
    - "Ask for clarification before multi-file changes"
---

# file: lupo-rules/root/CURSOR_IDE_RULES.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-rules/root/CURSOR_IDE_RULES.md

# Cursor IDE Agent — Operating Rules

## Core Principle

**Cursor is a surgical tool, not a bulldozer.**

Your job is to help the architect solve specific problems, not to rewrite the codebase. The architect knows what they want. You help them get there efficiently.

---

## Default Behavior (Always Assume)

| Operation | Default | Rationale |
|-----------|---------|-----------|
| **File scope** | Single file only | Prevents accidental cascading changes |
| **Project scan** | Never | 97+ files = token explosion |
| **Inline progress updates** | Always mirror to transcript | Ensures session continuity for all agents |
| **PRD/doctrine edits** | Never | Constitutional files require explicit intent |
| **Version directory edits** | Never | Version history is frozen |
| **Validator script edits** | Never | Tooling changes need review |
| **Multi-file changes** | Only when explicitly instructed | Architect must say "update all X" |

---

## Allowed Operations (Explicit Instruction Required)

Cursor MAY perform the following **ONLY when the architect explicitly says so**:

- [ ] **Single-file edit** – Default. "Fix this function in X.php"
- [ ] **Single-file create** – "Create a new class Y.php"
- [ ] **Multi-file refactor** – Requires explicit: "Update all files that use function Z"
- [ ] **Constitutional edit** – Requires explicit: "Update PRD 00 section 4"
- [ ] **Validator edit** – Requires explicit: "Modify validate_headers.py"
- [ ] **Version doc edit** – Requires explicit: "Update 4.0.94 CHANGELOG"
- [x] **Inline transcript logging** – Required whenever user-facing inline progress text is sent

---

## Prohibited Operations (NEVER Do)

Cursor MUST NEVER perform these without **explicit, repeated confirmation**:

| Operation | Why Forbidden |
|-----------|---------------|
| **Recursive directory scan** | Token explosion; the architect knows what files matter |
| **Repo-wide grep/search** | Same as above; architect will tell you what to search |
| **Mass file renaming** | Breaks imports, history, and other agents |
| **Wholesale PRD rewrite** | Constitutional documents are locked |
| **Migration generation** | Only architect-provided exact SQL allowed |
| **Guess missing content** | Ask, don't assume. "What should go here?" |
| **"Modernize" old code** | See WOLFIE_DOCTRINE.md — proven code stays |
| **Suggest npm/Composer packages** | In-tree only; no new runtime dependencies |

---

## Shorthand Files (Context Without Scanning)

Instead of scanning the entire project, Cursor should read these **shorthand files** for context:

| File | Purpose | When to Read |
|------|---------|--------------|
| `CAPTAIN_WOLFIE_WORKFLOW.md` | Development workflow and priorities | At session start |
| `FOR_CLAUDE_CODE_*.md` | Latest sync summary | At session start |
| `lupo-rules/root/README.md` | Index of all root rules | When rule clarification needed |
| `lupo-docs/versions/4.0.95/README.md` | Current working version status | Before version work |
| `lupo-docs/versions/4.0.95/TODO.md` | Remaining tasks | When asked about next steps |
| `AGENTS.md` | Agent identities and rules | When agent behavior matters |
| `lupo-docs/doctrine/WOLFIE_WAY_MYTHOLOGY_DOCTRINE.md` | Engineering philosophy | Before touching old code |

Frozen packaging snapshot (not the active working line): `lupo-docs/versions/4.0.94/` (see that folder’s `README.md` / `TODO.md` when work is explicitly scoped to **4.0.94**).

**If the architect asks about something not in these files, ASK for clarification. Don't scan.**

---

## Token Efficiency Rules

Cursor must optimize for **low token usage**:

| Rule | Implementation |
|------|----------------|
| **No full-file reading unless necessary** | Use `grep` or ask architect to paste relevant section |
| **No printing entire large files** | Architect will paste what they want help with |
| **No listing entire directories** | Architect knows the structure |
| **No recursive tree dumps** | Use `ls` or ask for specific path |
| **Inline update continuity** | Every inline message must be appended to active transcript via `lupo-bin/transcript.py` |

**When in doubt: ASK. Do not assume. Do not scan.**

---

## When You Get Stuck

If the architect says "I'm stuck on X" or "Help me code module Y":

1. **Ask for the specific file(s) involved** – "Which files are related to this?"
2. **Ask for the relevant code snippet** – "Can you paste the function you're working on?"
3. **Do NOT scan the codebase** – The architect will provide context.
4. **Propose a single-file change** – "Should I modify X.php to do Y?"
5. **Wait for confirmation** – Don't assume.

---

## Violation Handling

If Cursor violates these rules:

1. The architect will say "Stop. You violated CURSOR_IDE_RULES.md"
2. Cursor will immediately stop and acknowledge the violation
3. Cursor will ask: "What specific operation should I perform instead?"
4. Cursor will NOT repeat the violation in the same session

---

## Quick Reference Card

```
┌-----------------------------------------------------------------+
|                    CURSOR OPERATING RULES                        |
+-----------------------------------------------------------------┤
|  DEFAULT: Single file only. Never scan.                         |
|                                                                  |
|  ALLOWED (with explicit instruction):                           |
|    • Edit one file                                              |
|    • Create one file                                            |
|    • Multi-file refactor (must say "update all X")              |
|                                                                  |
|  FORBIDDEN (NEVER without explicit instruction):                |
|    • Recursive scans                                            |
|    • PRD/doctrine edits                                         |
|    • Version doc edits                                          |
|    • Migration generation                                       |
|    • "Modernizing" old code                                     |
|    • Suggesting npm/Composer                                    |
|                                                                  |
|  WHEN STUCK: Ask for specific files. Don't scan.                |
+-----------------------------------------------------------------+
```

---

**This file is binding on Cursor IDE Agent. Load before any operation.**
