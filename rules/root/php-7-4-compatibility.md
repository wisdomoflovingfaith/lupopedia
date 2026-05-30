---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/php-7-4-compatibility.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/php-7-4-compatibility.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rule
  artifact_kind: cursor_doctrine
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: cursor_rule
  prd_cluster: null
  title: null
  summary: null
---
# file: Rule — PHP tiered compatibility (Option 4) — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/php-7-4-compatibility

# PHP tiered compatibility (Option 4 — MANDATORY)

Constitutional source: **PRD 00 §4** and **§4.5**. This rule reconciles **production Y2038 safety** with **Crafty Syntax / legacy hosts on PHP 5.6** and **32-bit** builds.

## Production (normative)

- **PHP 7.4+** and **64-bit** (`PHP_INT_SIZE === 8`).
- Required for correct **fourteen-digit packed UTC** as a PHP **integer** and for **`timestamp_ymdhis`** arithmetic (**`timestamp_ymdhis::runtimePackedUtcIntSafe()`** must be true).
- **No Composer** or outside frameworks in runtime paths that are not already in `includes`.

## Shared core source (legacy reach)

- **Syntax** in `includes/`, `admin.php`, `index.php`, theme layouts, and the main request path **SHOULD** stay **PHP 5.6-parsable**: avoid PHP 7+–only operators and declarations unless the surrounding file already targets a higher floor and is documented.
- **Forbidden PHP 7+ features** for new/edited code in those paths: follow **`rules/root/PHP_VERSION_COMPATIBILITY.md`** (e.g. no `??`, no scalar type hints, no return types, prefer `array()` where the file uses 5.6 style).
- **Match the file:** when a file already uses PHP 7.4+ idioms (`??`, `[]`, typed properties), preserve consistency within that file; do not expand PHP 8-only syntax into legacy-style files without an explicit scope decision.

## Forbidden syntax (PHP 8.0+ only — do not use in shared core paths)

- **Union types**, **`match`**, **named arguments**, **enums**, **attributes**, **`readonly`**, **`declare(strict_types=1)`** — unless the file is explicitly scoped as modern-only.

## Namespace doctrine

- **Lupopedia core** (entry points, `includes`, admin, themes) must **not** use namespaces unless explicitly instructed.
- Third-party or explicitly namespaced libraries are the only exception.

## Where this applies

- All files in `includes/`, `admin.php`, `index.php`, theme layouts, and any file included in the main request path.
- When editing, prefer **PRD 00 §4** + **`PHP_VERSION_COMPATIBILITY.md`** over ad hoc modernization.

This rule is permanent.
