# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\CLASS_CONVERSION_DOCTRINE.md"
  file_hash: "01ad66ce44e996c50271d0b4ef3b7406a4adcec469a840da983e62d754ce78cc"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CLASS_CONVERSION_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "class_conversion_doctrinemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/CLASS_CONVERSION_DOCTRINE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/CLASS_CONVERSION_DOCTRINE.md
---

# Class Conversion Doctrine

**Status:** Canonical  
**Applies to:** Lupopedia 3.0.0 — converting legacy helper functions into classes, and all new logic  
**Overrides:** Any previous assumptions about keeping helpers procedural or "modernizing" behavior.

---

## 1. All new logic must be organized into classes (MANDATORY)

From now on:

- **All new functions must be placed inside classes.**
- **All converted helper functions must be placed inside classes.**
- **No new global helper functions may be created.**
- **No new files under `functions/` may be created.**
- **Existing helper functions must be migrated into classes one-by-one.**

This is a mandatory architectural rule. Classes are the unit of organization for logic; global helpers are legacy and are being phased out via conversion.

---

## 2. Reference semantics (`&$var`) must be preserved unless explicitly instructed to optimize (MANDATORY)

Crafty Syntax used pass-by-reference heavily, especially in:

- Session manipulation
- Operator state
- Global config arrays
- Message counters
- Visitor tracking
- Chat state mutation
- Array-based objects
- Legacy procedural flows

When converting helper functions into class methods:

- **If the original function accepted `&$var`, the class method must also accept `&$var`** — unless the user explicitly instructs you to optimize it away.
- **If the original function mutated a global array, the class method must mutate the same structure** — unless explicitly instructed otherwise.
- **If the original function relied on reference return values, the class method must replicate that behavior** — unless explicitly instructed to optimize.
- **You must never remove reference semantics automatically.** Default is preserve.

Before converting, **analyze** each reference to determine whether it is:

- **Essential** — required for correct behavior (e.g. caller relies on mutated variable).
- **Optional** — could be replaced by return value but current callers expect mutation.
- **Legacy noise** — redundant or unused in practice.

Regardless of the analysis, **do not remove or change reference semantics unless the user explicitly instructs you to optimize.** Preserving behavior is the default; optimization is opt-in per function.

---

## 3. Scope must be preserved unless explicitly instructed to change (MANDATORY)

When converting helpers into classes:

**Preserve (unless the user explicitly instructs you to change scope):**

- The same variable lifetime
- The same mutation behavior
- The same side effects
- The same global interactions
- The same array structure expectations

**Do NOT introduce unless explicitly requested:**

- New scopes that change when/where variables are visible
- New lifetimes that break shared state
- New object instances that break shared state
- Static state (unless explicitly required by the original design)
- Dependency injection
- Service containers
- Middleware patterns

**Classes must behave like structured namespaces, not modern OOP services.** The conversion is organizational (logic grouped into classes); it is not a rewrite into a different architecture. Scope and behavior are preserved by default.

---

## 4. Conversion must be incremental and safe (MANDATORY)

When converting helpers:

- **Convert one helper file (or one logical helper) at a time.**
- **Keep the old helper functions temporarily as wrappers.** The wrapper calls the new class method.
- **Remove the wrapper only after testing** confirms the class version behaves identically.
- **Never convert multiple helpers blindly** in a single change.
- **Never assume behavior from 25-year-old code** — inspect and preserve; do not guess.

This ensures correctness and avoids regressions. Incremental conversion with wrappers allows callers to remain unchanged until the new path is verified.

---

## 5. Cursor must inspect legacy helper functions before converting (MANDATORY)

Before converting any helper function, Cursor must analyze:

- Whether it uses **references** (`&$param`, reference return)
- Whether it **mutates globals** (`$GLOBALS`, global $var, superglobals)
- Whether it depends on **include order** (files included before/after)
- Whether it depends on **global constants**
- Whether it depends on **global config arrays** (e.g. Crafty `$config`)
- Whether it depends on **session state** (`$_SESSION`, session functions)
- Whether it depends on **legacy Crafty Syntax quirks** (naming, side effects, ordering)

**Cursor must preserve all of these behaviors in the class version** unless the user explicitly instructs otherwise (e.g. "We can optimize this one").

**Cursor must NOT "clean up" or "modernize" behavior by default.** We are preserving behavior, not rewriting the system. If the legacy code mutates a global by reference, the class method must do the same — until the user explicitly allows optimization for that function.

---

## 6. Optimization is allowed only when explicitly requested (MANDATORY)

**Do not optimize (remove references, simplify logic, reduce side effects, change scope) unless the user explicitly requests it.**

If the user says something like:

- "We can optimize this one."
- "You may remove the reference here."
- "Simplify this function."
- "Optimize away the reference for this specific helper."

Then, **for that specific function only**, you may:

- Remove references (replace with return values or in-place mutation as appropriate)
- Simplify logic (within PHP 5.3 limits)
- Modernize structure (within PHP 5.3 limits — no strict types, return types, etc.)
- Reduce side effects (only where the user has agreed)

**Only for that specific function.** Do not generalize to other helpers. Do not optimize by default; optimization is opt-in per conversion.

---

## 7. PHP 5.3 → 8.1 compatibility must be maintained (MANDATORY)

All class conversions must follow the same constraints as the rest of Lupopedia:

- No strict types
- No return types
- No scalar type hints
- No nullable types
- No union types
- No attributes
- No arrow functions
- No enums
- No typed properties
- No modern OOP patterns (no DI, no service locators, no middleware)
- No autoloaders beyond **spl_autoload_register()**

Classes must be **simple, procedural-friendly containers** — a way to group related functions and preserve reference/scope behavior, not a move toward modern PHP or frameworks.

See **PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md** and **COMPATIBILITY_MATRIX.md**.

---

## 8. No-framework and no–modern-PHP rules (MANDATORY)

Class conversion does not override the core prohibitions:

- No frameworks, no middleware, no Composer, no `vendor/`
- No database logic (procedures, triggers, views, FKs, etc.); PDO only, manual SQL
- No ORM, no query builders
- PHP 5.3 → 8.1+ only; no strict types, return types, enums, etc.

Classes are organizational units only. They do not introduce a new stack or new dependencies.

---

## 9. Summary

| Rule | Requirement |
|------|-------------|
| New logic | All new functions in classes; no new global helpers; no new files under `functions/` |
| Conversion | Existing helpers migrated into classes one-by-one |
| References | Preserve `&$var` and reference return unless user explicitly instructs to optimize; analyze essential/optional/legacy; never remove automatically |
| Scope | Preserve variable lifetime, mutation, side effects, globals unless explicitly instructed to change; no DI, no service containers |
| Incremental | One helper at a time; keep wrapper until tested; then remove wrapper |
| Inspection | Analyze references, globals, include order, config, session, Crafty quirks before converting; preserve all unless explicitly instructed otherwise |
| Optimization | Allowed only when user explicitly requests it (e.g. "We can optimize this one"); only for that specific function |
| Compatibility | PHP 5.3 → 8.1+; no modern PHP features; classes as structured namespaces |
| Behavior | Preserve behavior by default; do not clean up or modernize unless explicitly requested |

---

## 10. References

- **.cursorrules** — Class Conversion Doctrine section; reference semantics; scope preservation; optimization-only-when-requested
- **PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md** — PHP 5.3 → 8.1+ and forbidden features
- **COMPATIBILITY_MATRIX.md** — Full compatibility and prohibition list

This document and .cursorrules must include: reference semantics rules, scope preservation rules, incremental conversion rules, optimization rules (opt-in only), and PHP 5.3 compatibility rules.
