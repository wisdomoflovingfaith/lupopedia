---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: "CASCADE"
  target: "@everyone"
  mood_RGB: "00FF00"
  message: "Created Thread-Level Dialog Specification v1.0 - standard for /dialogs/<threadname>_dialog.md files in Lupopedia"
tags:
  categories: ["documentation", "specification", "standards"]
  collections: ["core-docs", "agents"]
  channels: ["public", "dev", "standards"]
file:
  title: "Thread-Level Dialog Specification v1.0"
  description: "Standard for /dialogs/<threadname>_dialog.md files in Lupopedia (January 2026)"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🧵 **Thread-Level Dialog Specification v1.0**  
*Standard for `/dialogs/<threadname>_dialog.md` files in Lupopedia (January 2026)*

---

## 🟩 1. Purpose Section

Explain that **thread‑level dialog files**:

- **store dialog messages for the entire thread**, not individual files
- **act as a global conversational ledger**
- **capture the narrative of a session**
- **help agents understand context, intent, and progress**
- **prevent loss of conversational history**
- **complement (but do not replace) per‑file dialog history**

Thread-level dialog files provide a higher-level view of collaboration that spans multiple files and agents working together on a common goal or task.

---

## 📁 2. File Location and Naming

### File Location

Thread‑level dialog files must be stored in:

```
/dialogs/
```

### File Naming Convention

Each thread must have a file named:

```
<threadname>_dialog.md
```

Where `<threadname>` is the internal name or identifier of the active thread.

**Examples:**
- `/dialogs/authentication_fix_dialog.md`
- `/dialogs/header_refactor_dialog.md`
- `/dialogs/agent_pipeline_dialog.md`

This naming is deterministic and required.

---

## 📝 3. What Gets Written to Thread-Level Dialog Files

Thread‑level dialog files contain:

- **dialog messages generated during the thread**
- **high-level summaries of actions taken**
- **agent-to-agent communication**
- **human-to-agent communication**
- **decisions, clarifications, and intent markers**

They do **not** contain:
- Per-file dialog
- File-specific rewrite messages
- Header-level dialog blocks
- YAML
- Thread dialog is **Markdown only**

---

## 🕒 4. Entry Format

Each entry must follow this structure:

```markdown
## <UTC timestamp YYYY-MM-DD HH:MM:SS>
**speaker:** <agent_or_actor>  
**target:** @<recipient>  
**message:** <full message text>  
```

### Rules

- **Timestamp must be UTC**
- **Speaker is the agent or human**
- **Target is optional but recommended**
- **Message may be multi-sentence**
- **No YAML in thread dialog files — Markdown only**
- **No header blocks**
- **No code unless part of the message**

---

## 📋 5. Ordering Rules

- **Newest entries go at the top of the file**
- **Older entries move downward over time**
- **No reordering of historical entries**
- **No deletion of historical entries**
- **No summarizing or collapsing entries**

This ensures deterministic parsing and chronological clarity.

---

## 🔄 6. When to Append to Thread-Level Dialog

Agents should append to `<threadname>_dialog.md` when:

- **the thread makes progress**
- **a decision is made**
- **a clarification is issued**
- **a new task begins**
- **a task completes**
- **a human gives direction**
- **an agent reports status**

Agents should **not** append when:
- **Modifying a file** (that goes to per-file dialog)
- **Writing header dialog** (that goes to per-file dialog)
- **Performing trivial operations**

---

## ⚖️ 7. Difference Between Thread-Level and Per-File Dialog

Add a section comparing the two systems:

### Thread-Level Dialog (`/dialogs/<threadname>_dialog.md`)
- **Global**
- **Session-wide**
- **Narrative**
- **Markdown**
- **Not tied to header updates**

### Per-File Dialog (`<filename>_dialog.md`)
- **Local**
- **File-specific**
- **Provenance**
- **Markdown**
- **Tied to header updates**

**Agents must use the correct system based on context:**
- Thread work → Thread-level dialog
- File work → Per-file dialog

---

## 📝 8. Minimal Example

Provide a short example of a thread-level dialog file:

```markdown
## 2026-01-13 14:30:00 UTC
**speaker:** DIALOG  
**target:** @agents  
**message:** Started authentication system refactoring thread.

## 2026-01-13 14:45:12 UTC
**speaker:** CURSOR  
**target:** @DIALOG  
**message:** Created database migration plan for auth system.

## 2026-01-13 15:12:33 UTC
**speaker:** Wolfie  
**target:** @dev  
**message:** Approved migration plan. Begin implementation.
```

---

## 🌍 9. Scope and Versioning

State:

**This is Thread-Level Dialog Specification v1.0** (January 2026)

It applies to all Lupopedia agent threads and collaborative sessions.

It **complements** WHS and LHP but does not modify them.

Future versions may add optional metadata, but the core format is immutable.

---

## 🔗 10. Implementation Resources

- **Lupopedia Header Profile**: `docs/doctrine/LUPOPEDIA_HEADER_PROFILE.md`
- **Dialog History File Specification**: `docs/agents/DIALOG_HISTORY_SPEC.md`
- **Universal Wolfie Header Specification**: `docs/doctrine/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md`
- **WOLFIE Header Specification**: `docs/agents/WOLFIE_HEADER_SPECIFICATION.md`
- **Global Atoms**: `config/global_atoms.yaml`

---

*Last Updated: January 13, 2026*  
*Version: 1.0.0*  
*Author: Captain Wolfie*
