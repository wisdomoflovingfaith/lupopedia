---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.95+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  author:
    type: "actor"
    id: 102
    name: "cursor"
  delegation_chain: "cursor:root"
  lupopedia.version: "4.0.95"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/php-7-4-compatibility.md"
  web_path: "http://www.lupopedia.com/rules/root/php-7-4-compatibility"
  last_modified_utc: "20260406062838"
  system_version: "4.0.95"
  rule_name: "PHP tiered compatibility (Option 4)"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "Production PHP 7.4+ 64-bit for Y2038; shared core syntax targets PHP 5.6+ per PHP_VERSION_COMPATIBILITY.md; forbid PHP 8-only syntax in shared paths"
  tags: ["cursor", "php", "compatibility", "doctrine", "y2038"]
  source_path: ".cursor/rules/php-7-4-compatibility.mdc"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC003"
      rule_text: "Production: PHP 7.4+ 64-bit. Shared core: PHP 5.6-parsable syntax; no PHP 8-only features unless file is modern-only."
      scope: "all_agents"
      category: "compatibility"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260314"
    last_reviewed_by: "cursor"
    last_reviewed_date: "20260406"
    version: "3.0"
    status: "active"
lupopedia.footer:
  version: "4.0.95"
  last_verified: "20260406062838"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Keep in sync with .cursor/rules/php-7-4-compatibility.mdc (regenerate via propagate_agent_rules.php)"
    - "Pair with lupo-rules/root/PHP_VERSION_COMPATIBILITY.md (forbidden PHP 7+ list)"
---
# file: Rule — PHP tiered compatibility (Option 4) — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/php-7-4-compatibility

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
