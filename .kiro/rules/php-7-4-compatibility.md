---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "kiro_rule"
  file_path_from_root: ".kiro/rules/php-7-4-compatibility.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/php-7-4-compatibility.md"
  artifact_type: "rule"
  artifact_kind: "kiro_doctrine"
---


# PHP tiered compatibility (Option 4 — MANDATORY)

Constitutional source: **PRD 00 §4** and **§3.5.4**. This rule reconciles **production Y2038 safety** with **Crafty Syntax / legacy hosts on PHP 5.6** and **32-bit** builds.

## Production (normative)

- **PHP 7.4+** and **64-bit** (`PHP_INT_SIZE === 8`).
- Required for correct **fourteen-digit packed UTC** as a PHP **integer** and for **`timestamp_ymdhis`** arithmetic (**`timestamp_ymdhis::runtimePackedUtcIntSafe()`** must be true).
- **No Composer** or outside frameworks in runtime paths that are not already in `lupo-includes`.

## Shared core source (legacy reach)

- **Syntax** in `lupo-includes/`, `admin.php`, `index.php`, theme layouts, and the main request path **SHOULD** stay **PHP 5.6-parsable**: avoid PHP 7+–only operators and declarations unless the surrounding file already targets a higher floor and is documented.
- **Forbidden PHP 7+ features** for new/edited code in those paths: follow **`lupo-rules/root/PHP_VERSION_COMPATIBILITY.md`** (e.g. no `??`, no scalar type hints, no return types, prefer `array()` where the file uses 5.6 style).
- **Match the file:** when a file already uses PHP 7.4+ idioms (`??`, `[]`, typed properties), preserve consistency within that file; do not expand PHP 8-only syntax into legacy-style files without an explicit scope decision.

## Forbidden syntax (PHP 8.0+ only — do not use in shared core paths)

- **Union types**, **`match`**, **named arguments**, **enums**, **attributes**, **`readonly`**, **`declare(strict_types=1)`** — unless the file is explicitly scoped as modern-only.

## Namespace doctrine

- **Lupopedia core** (entry points, `lupo-includes`, admin, themes) must **not** use namespaces unless explicitly instructed.
- Third-party or explicitly namespaced libraries are the only exception.

## Where this applies

- All files in `lupo-includes/`, `admin.php`, `index.php`, theme layouts, and any file included in the main request path.
- When editing, prefer **PRD 00 §4** + **`PHP_VERSION_COMPATIBILITY.md`** over ad hoc modernization.

This rule is permanent.
