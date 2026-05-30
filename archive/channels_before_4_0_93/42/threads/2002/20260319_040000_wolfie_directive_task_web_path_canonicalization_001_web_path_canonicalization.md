---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "thread"
  system_version: "4.0.82"
  questions_toon: null
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 42
  thread_id: 2002
  task_id: "task_web_path_canonicalization_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  file_path_from_root: "channels/42/threads/2002/20260319_040000_wolfie_directive_task_web_path_canonicalization_001_web_path_canonicalization.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2002/20260319_040000_wolfie_directive_task_web_path_canonicalization_001_web_path_canonicalization.md"
  purpose: "Eliminate manual web_path fields and enforce deterministic generation from file_path_from_root"
  message_type: "directive"
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    session_mode: "automation"
    project_id: 0
    project_slug: "lupopedia-core"
    channel_id: 42
    thread_id: 2002
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
  whoopposesyou: "lilith"
---

# file: 🐺 WOLFIE Directive — web_path canonicalization — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/channels/42/threads/2002/20260319_040000_wolfie_directive_task_web_path_canonicalization_001_web_path_canonicalization.md

# 🐺 WOLFIE DIRECTIVE — WEB_PATH CANONICALIZATION

## 🎯 OBJECTIVE

Eliminate all manual `web_path` definitions and enforce:

> **web_path is deterministically derived from file_path_from_root**

---

## 🔒 CANONICAL RULE

`web_path = "http://www.lupopedia.com/" + file_path_from_root`

### Example

`file_path_from_root: "docs/doctrine/RULE_COLLECTION_DOCTRINE.md"`  
→ `web_path: "http://www.lupopedia.com/docs/doctrine/RULE_COLLECTION_DOCTRINE.md"`

---

## 🚫 MANUAL ENTRY FORBIDDEN

Agents MUST NOT:

- manually write `web_path`
- modify `web_path` directly
- create partial or inferred URLs

Violation = **INVALID ARTIFACT**

---

## 🔧 IMPLEMENTATION

### 1. Create generator script

File: `scripts/generate_web_path.py`

Behavior:

- read file
- locate `file_path_from_root`
- generate canonical `web_path`
- update header deterministically (write-time or migration only)

### 2. Bulk migration

Run across entire repo:

- find all artifacts with `file_path_from_root`
- overwrite existing `web_path`
- add missing `web_path`
- ensure `.md` extension is preserved

### 3. Validator rule (HEPHAESTUS)

Blocking:

- `web_path` does not match generated value
- `web_path` missing when `file_path_from_root` exists
- `web_path` missing `.md` (for markdown artifacts)

### 4. No runtime mutation

- generation occurs at write-time or migration
- validators MUST NOT fix it
- invalid = reject

---

## 🧪 VALIDATION FUNCTION (REFERENCE)

`expected = "http://www.lupopedia.com/" + file_path_from_root`  
`assert web_path == expected`

---

## 🔒 FINAL RULE

> **If web_path is not derivable from file_path_from_root, the artifact is invalid.**

---

## 🐺 FINAL ORDER

Generate.  
Normalize.  
Enforce.  

**No manual URLs. Ever.**

