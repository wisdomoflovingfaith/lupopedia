---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "channels/0/translation/concepts/08_crafty_syntax_migration.md"
  web_path: "https://www.lupopedia.com/lupopedia/channels/0/translation/concepts/08_crafty_syntax_migration.md"
  status: "active"
  when_updated: "20260416215839"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "translation"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Translation: Crafty Syntax Migration"
  summary: "Translation artifact for Crafty Syntax to Lupopedia Migration."
---
# Concept: Crafty Syntax → Lupopedia Migration

## Lupopedia 4.0.x execution facts (must match weekly report)

- **Legacy Crafty Syntax PHP is not executed** as the Lupopedia engine. The supported path is **import and transform** from Crafty **3.7.5** data using the canonical importer SQL: `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` plus install/seed alignment.
- **Operator workflows** (real-time support, routing, auditability) are preserved at the **product semantics** level in Lupopedia; brittle legacy execution is not dragged forward as the runtime core.

## Internal Technical Wording (Layer 3)
Lupopedia is the architectural descendant of Crafty Syntax (2003-2023) at the **design and data** level: proven operator-chat semantics, shared-hosting constraints, and zero-dependency shipped runtime. New code lives under Lupopedia paths (`includes/`, `app/`, `channels/`, etc.) with PDO and current doctrine. Patterns that survived decades of production inform decisions; they do **not** mean “run the old tree in prod.”

## Conceptual Model (Layer 2)
Instead of building a skyscraper out of untested experimental materials, we built it using the steel frame from a 20-year-old building that famously never collapsed once during earthquakes. We updated the exterior, but we kept the resilient core intact.

## External Short Wording (Layer 1)
Our system is built on proven logic that has run flawlessly for 20 years, completely avoiding the fragility of trendy new tech.

## Business Wording
Lupopedia leverages a heavily vetted legacy core (Crafty Syntax) that delivers decades of proven stability. By rejecting fragile modern dependencies, we ensure the platform provides unparalleled long-term reliability and lower technical debt for the business.

## User-Guide Wording
This system may look simple under the hood, and that is on purpose. It is designed to never break, modeled after software that successfully handled millions of chats reliably for over 20 years.

## Developer Wording
Study Crafty Syntax in `legacy/` as **read-only reference**. Implement behavior in Lupopedia PHP/JS using PDO and current headers. Respect polling and operator semantics without copying unmaintained PHP 5.6 execution paths into the live request chain.

## Example Analogy
It's like using an older, highly reliable diesel engine block in a brand new car chassis. It doesn't need to be shiny and new to outperform the unreliable prototype engines on the market.

## Common Misunderstanding
"The codebase is using outdated junk."
*Correction*: The codebase intentionally avoids modern dependency frameworks in favor of proven, zero-dependency architecture specifically for catastrophic resilience.

## Wording to Avoid
* "Legacy debt"
* "Outdated core"
* "Needs a rewrite"
