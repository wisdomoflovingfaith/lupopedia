# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md"
  file_hash: "c836edbcd17196b8845f9d3343657a3f5a6a782b575bcb1389e1d784ac147372"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\audits\PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md"
  file_hash: "4771a732221c7bcc1c1adcf542560853e77ad4cc53dd9a450f3cb8c04528bbff"
  file_path_from_root: "docs\audits\PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md"
  file_hash: "989162eb9d6ba0e4aee789beed93685dc0a960cbbd38a7b85d1c05edf6971ebb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "PHP 5.3 Array Syntax Sweep Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "php_5_3_array_syntax_sweep_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# PHP 5.3 Array Syntax Sweep Report

## 1. Files Where `[]` Was Replaced With `array()`

The following files were updated in this sweep to use `array()` instead of short array syntax `[]`:

| File | Changes |
|------|--------|
| **lupo-includes/themes/default/layouts/main_layout.php** | `$collectionsData = []` → `array()`, `$tabs_data = []` → `array()`, ternary `: []` → `: array()`. |
| **lupo-includes/modules/channels/channels-controller.php** | All empty-array assignments (`$threads = []`, `$messages = []`, `$operators = []`, etc.) → `array()`. All `execute([...])`, `render_main_layout([...])`, `extract([...])` → `execute(array(...))`, `render_main_layout(array(...))`, `extract(array(...))` with correct closing `));`. Inline arrays in `$pending_visitors[] = [...]` and `$visitors[] = [...]` and `$my_channels[] = [...]` → `array(...)`. Ternary `: []` → `: array()`. |
| **debug_collection_zero.php** | `$errors = []` → `array()`, default parameter `array $params = []` → `array $params = array()`. |
| **api/load_collection_tabs.php** | `$tabs_data = []` → `array()`. |
| **app/Services/CraftySyntax/LegacyFunctions.php** | `$session_data = []` → `array()`, ternary `) : []` → `) : array()`. |
| **app/Services/ActorService.php** | `return array()` (was `return []`), `$ids = []` → `array()`, `$tempMeta` / `$realMeta` and ternary `? $decoded : []` → `array()`. |

**Note:** Many other PHP files in the repo still contain short array syntax (e.g. ` = []`, `return []`, `['key' => val]`). The rule in `.cursor/rules/php-5-3-compatibility.mdc` already requires `array()` everywhere. A full codebase sweep would apply the same replacements to every remaining file (see “Required patterns” below).

---

## 2. Confirmation: `array()` Is NOT Deprecated in PHP 8.3

- **PHP 8.3** (and all supported PHP versions) fully support the `array()` language construct.
- The PHP manual does **not** deprecate `array()` in favor of `[]`.
- Short array syntax `[]` was added in **PHP 5.4**; `array()` remains valid and supported in PHP 5.3 through 8.x.
- Using `array()` everywhere ensures **PHP 5.3 compatibility** and is safe for PHP 8.3+.

---

## 3. Confirmation: Shorthand Arrays Removed in Touched Files

- In all files listed in section 1, **empty** short arrays `[]` have been replaced with `array()`.
- In **channels-controller.php**, **all** short array literals in the modified sections (assignments, `execute`, `render_main_layout`, `extract`, inline arrays) were replaced with `array()` and correct closing parentheses.
- **Array push** usage (e.g. `$log[] = ...`, `$operators[] = $row`) was **not** changed; that is not short array syntax for a literal, it is the append operator.
- Remaining files in the codebase may still contain `[]`; the **Cursor rule** (section 4) and this report document the required convention for future edits and for completing the sweep.

---

## 4. Confirmation: Cursor Will Not Generate `[]` in Future Code

- The file **`.cursor/rules/php-5-3-compatibility.mdc`** is **alwaysApply: true** and states:
  - **Short array (`[]`)** — Use `array()` only.
  - **Arrays:** Always `array(...)`, never `[...]`.
- Cursor must:
  - **ALWAYS** generate `array()` for array literals.
  - **NEVER** generate `[]` in any new or edited Lupopedia code.
  - **NEVER** suggest or convert `array()` to `[]`.

This applies to all PHP in: installer, wizard, API endpoints, modules, seeds, migrations, tests, and any 4.0.5 development.

---

## Required Patterns for a Full Sweep

For any remaining file, apply:

- ` = [];` → ` = array();`
- `return [];` → `return array();`
- ` = []` (no semicolon, e.g. in ternary) → ` = array()`
- ` : []` → ` : array()`
- `, []` (argument) → `, array()`
- `([ ])` → `( array() )`
- Default parameters: `$x = []` → `$x = array()`
- Non-empty literals: `[ 'a' => 1, 'b' => 2 ]` → `array( 'a' => 1, 'b' => 2 )`, and `[ 1, 2, 3 ]` → `array( 1, 2, 3 )`, with matching closing `]` → `)`.

Do **not** change:

- `$var[] = value` (array push).
- `[]` inside strings or regex.
- Array access: `$a[$i]`, `$a['key']`.
