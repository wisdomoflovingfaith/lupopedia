---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "spec_design"
  file_path_from_root: ".kiro/specs/kiro-rules-import/design.md"
  web_path: "http://www.lupopedia.com/specs/kiro-rules-import/design"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  artifact_type: "spec"
  artifact_kind: "design"
  purpose: "Design for the kiro-rules-import feature: extending propagate_agent_rules.php with --target=kiro, writing .kiro/rules/*.md, .kiro/lupopedia_rules.json, enforcement test, and README"
  tags: ["kiro", "rules", "import", "propagation", "design"]
---

# Design Document: kiro-rules-import

## Overview

This feature extends the existing `lupo-scripts/propagate_agent_rules.php` script to support a `--target=kiro` flag, producing Kiro-specific rule artifacts in `.kiro/`. The pipeline reads the 15 canonical root rules from `lupo-rules/root/`, extracts their `lupopedia.rules` blocks, and writes:

- `.kiro/rules/<slug>.md` — one rule file per root rule, with Kiro-scoped LUPOPEDIA HEADERS
- `.kiro/lupopedia_rules.json` — machine-readable rule index (fully overwritten on each run)
- `.kiro/README.md` — import summary and rule inventory

An enforcement test at `lupo-tests/unit/kiro_rules_enforcement.php` validates the output. All code is PHP 7.4 compatible, uses no frameworks, and follows AGENTS.md constraints.

The design decision to extend the existing script (rather than create a new one) keeps the propagation logic in a single place and avoids duplication. The `--target` flag makes the script's scope explicit and prevents cross-IDE contamination.

---

## Architecture

```
lupo-rules/root/*.md  (read-only, 15 files)
        |
        v
lupo-scripts/propagate_agent_rules.php --target=kiro
        |
        +---> .kiro/rules/<slug>.md          (one per rule, LUPOPEDIA HEADERS + body)
        +---> .kiro/lupopedia_rules.json     (rules array, fully overwritten)
        +---> .kiro/README.md                (import summary)

lupo-tests/unit/kiro_rules_enforcement.php
        |
        +---> loads .kiro/lupopedia_rules.json
        +---> checks each rule entry (id, text, enforcement, scope)
        +---> checks corresponding .kiro/rules/<slug>.md exists
        +---> exits 0 (pass) or non-zero (fail)
```

### Target Dispatch

The script parses `--target=<value>` from `$argv`. When `--target=kiro` is given, only the Kiro output functions run. When no `--target` is given, the existing all-targets behavior is preserved for backward compatibility.

```
argv parsing
  |
  +-- target == "kiro"   --> runKiroTarget($rules, $kiroDir)
  +-- target == "cursor" --> runCursorTarget($rules, $cursorDir)
  +-- target == null     --> runAllTargets($rules, ...)   (existing behavior)
```

---

## Components and Interfaces

### 1. `propagate_agent_rules.php` — extended

**New responsibilities (--target=kiro path):**

- `parseArgs($argv)` — returns `['target' => 'kiro'|'cursor'|null]`
- `parseRootRules($rootDir)` — reads all `*.md` except `README.md`, extracts `lupopedia.rules` block, returns array of rule structs. Logs warnings for missing blocks or missing `rule_id`. Returns count of warnings.
- `runKiroTarget($rules, $kiroDir, $rootDir, $timestamp)` — orchestrates Kiro output:
  - calls `writeKiroRuleFiles()`
  - calls `writeKiroRulesJson()`
- `writeKiroRuleFiles($rules, $kiroDir, $rootDir, $timestamp)` — for each rule, writes `.kiro/rules/<slug>.md`
- `writeKiroRulesJson($rules, $kiroDir, $timestamp)` — writes `.kiro/lupopedia_rules.json`
- `buildKiroHeader($slug, $ruleId, $timestamp)` — builds the LUPOPEDIA HEADERS YAML block for a rule file
- `extractRuleBody($content)` — strips the root rule's own LUPOPEDIA HEADERS block, returns the markdown body

**Existing responsibilities preserved:**
- Cursor MDC generation (unchanged)
- `.idea` XML generation (unchanged)
- All-targets path (unchanged when no `--target` given)

### 2. `.kiro/rules/<slug>.md` — Rule File

Each file has this structure:

```
---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "kiro_rule"
  file_path_from_root: ".kiro/rules/<slug>.md"
  last_modified_utc: "<YYYYMMDD>"
  system_version: "4.0.75"
  source_path: "lupo-rules/root/<slug>.md"
  artifact_type: "rule"
  artifact_kind: "kiro_doctrine"
---

<rule body from root rule, LUPOPEDIA HEADERS stripped>
```

The body is the full markdown content of the root rule after its front-matter block. The root rule's own `---` delimiters and YAML are not copied.

### 3. `.kiro/lupopedia_rules.json` — Rules Index

```json
{
  "rules": [
    {
      "id": "DB001",
      "text": "Mandatory prohibition of database-side logic...",
      "enforcement": "error",
      "scope": ["all_agents"]
    },
    ...
  ]
}
```

Fully overwritten on each run. `json_encode` with `JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES`.

### 4. `lupo-tests/unit/kiro_rules_enforcement.php` — Enforcement Test

Standalone PHP 7.4 script. No test framework. Checks:

1. `.kiro/lupopedia_rules.json` is loadable and `rules` array is non-empty
2. Each entry has `id`, `text`, `enforcement`, `scope`
3. No duplicate `id` values
4. A corresponding `.kiro/rules/<slug>.md` exists for each rule (slug derived from rule id → filename mapping stored in JSON or inferred from the rules directory)

Exits 0 on pass, 1 on failure. Outputs human-readable pass/fail lines.

### 5. `.kiro/README.md` — Import Documentation

Written by a separate `writeKiroReadme()` function (or as a post-step in `runKiroTarget()`). Contains:

- LUPOPEDIA HEADERS block (actor_id 100, system_version 4.0.75)
- Kiro identity section (actor_id 100, slug `kiro`, role `Schema Coordinator`)
- Propagation command
- Rule inventory table (Rule ID | Rule Name | Source File | Enforcement)
- Total count
- Enforcement test result placeholder (updated after test run, or written as "run `php lupo-tests/unit/kiro_rules_enforcement.php` to verify")
- Reference to `lupo-rules/root/README.md` for root rule definitions

---

## Data Models

### Rule Struct (in-memory, PHP array)

```php
array(
    'id'          => 'DB001',           // from lupopedia.rules.declares[0].rule_id
    'text'        => '...',             // from lupopedia.rules.declares[0].rule_text
    'enforcement' => 'error',           // always 'error'
    'scope'       => array('all_agents'), // from lupopedia.rules.declares[0].scope
    'slug'        => 'database-logic-prohibition-doctrine', // basename without .md
    'source_path' => 'lupo-rules/root/database-logic-prohibition-doctrine.md',
    'body'        => '...',             // extracted markdown body
)
```

### `.kiro/lupopedia_rules.json` schema

```
{
  "rules": [
    {
      "id":          string,   // e.g. "DB001"
      "text":        string,   // rule_text from lupopedia.rules block
      "enforcement": "error",  // always "error"
      "scope":       [string]  // always ["all_agents"]
    }
  ]
}
```

### LUPOPEDIA HEADERS fields written to `.kiro/rules/<slug>.md`

| Field | Value |
|---|---|
| `actor_id` | `100` |
| `actor_name` | `"kiro"` |
| `delegation_chain` | `"kiro:root"` |
| `lupopedia.version` | `"4.0.75"` |
| `lupopedia.schema` | `"kiro_rule"` |
| `file_path_from_root` | `".kiro/rules/<slug>.md"` |
| `last_modified_utc` | `gmdate('Ymd')` at run time |
| `system_version` | `"4.0.75"` |
| `source_path` | `"lupo-rules/root/<slug>.md"` |
| `artifact_type` | `"rule"` |
| `artifact_kind` | `"kiro_doctrine"` |

---

