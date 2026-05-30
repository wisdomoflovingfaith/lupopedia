---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/TICK_PY_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/TICK_PY_DOCTRINE.md"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: temporal
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# TICK_PY and temporal anchor (mandatory)

## Purpose

All **`last_modified_utc`**, **`when_updated`**, **`last_verified`**, and **UTC-based filename prefixes** for canonical artifacts must come from **real system UTC**, not from model guesses, "nice" round times, or copied dates from other files.

The **only** supported way to refresh the canonical clock for the repo is:

```bash
python bin/tick.py
```

## Global Atoms for Agents

All agents MUST read `config/global_atoms.json` before any task.

This file contains:
- `version` — Current Lupopedia version
- `current_utc` — Current UTC timestamp (YYYYMMDDHHIISS)
- `session_salt` — Session salt (if generated)
- `project.name` — Project name
- `authors.primary` — Primary author name

**DO NOT:**
- Hardcode version numbers
- Guess timestamps
- Assume author names

**DO:**
- Read `global_atoms.json` at the start of every session
- Use atom values for all headers and metadata

**Example:**
```python
import json
with open('config/global_atoms.json') as f:
    atoms = json.load(f)
    version = atoms['version']
    timestamp = atoms['current_utc']
```

## when_updated Rule for Python Files

`when_updated` in a Python file's Lupopedia header comment block tracks **code logic change time**, not header maintenance time.

### Preserve existing when_updated

If the edit touches **only the header comment block**, do not change `when_updated`.

This includes:
- Reordering header fields to match the canonical field order
- Renaming a header field (e.g. `dialog_transcript` → `transcript_jsonl`)
- Normalizing header values (e.g. fixing a path, correcting a slug)

The file's code has not changed. The timestamp must not drift.

### Update when_updated to current UTC

If the edit changes **Python code logic** in any meaningful way, set `when_updated` to the current UTC timestamp from `config/global_atoms.json`.

This includes:
- Validator logic changes
- Parser logic changes
- Generator logic changes
- Field rename support changes (adding a compatibility alias in code)
- Compatibility logic changes
- Emitted header structure changes

### Decision rule

> **Header comment block only?** → Preserve `when_updated`.
>
> **Code logic changed?** → Update `when_updated` to current UTC.

### How to get the current UTC timestamp

```bash
python bin/tick.py
```

Then read `current_utc` from `config/global_atoms.json`.

```python
import json
with open('config/global_atoms.json') as f:
    timestamp = json.load(f)['current_utc']
```