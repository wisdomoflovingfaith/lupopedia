---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/SELF_HACKING_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/SELF_HACKING_DOCTRINE.md"
  last_modified_utc: "20260403121547"
  when_updated: "20260403121547"
  federation_node_id: 0
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: doctrine
  artifact_kind: security_self_assessment
  purpose: "Codify adversarial self-testing (red-team your own system); complement automated tools with explicit human-driven threat modeling"
  status: active
  tags:
    - security
    - adversarial_testing
    - red_team
    - doctrine
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Security and governance alignment with constitutional requirements"
    - to: "lupo-docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Naming, test identities, and banned-persona rules"
    - to: "lupo-docs/doctrine/BAN_REASONS.md"
      type: references
      weight: 0.95
      reason: "Canonical ban reasons and audit metadata for blocked identities"
    - to: "lupo-docs/versions/3.0.x/experiments/security/README.md"
      type: references
      weight: 0.85
      reason: "Historical security experiment archive context"
    - to: "lupo-docs/doctrine/SEMANTIC_SECURITY_CHECKLIST_4_0_30.md"
      type: references
      weight: 0.8
      reason: "Semantic security validation patterns"
    - to: "lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Automated baseline (Layer 1) before adversarial self-testing (Layer 2)"
lupopedia.footer:
  last_verified: "20260403121547"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  next_action:
    - "Keep self-hacking steps documented when adding new surfaces (API, channels, AI prompts)"
---

# file: SELF_HACKING_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/SELF_HACKING_DOCTRINE.md

# Self-hacking doctrine (4.0.x)

## Purpose

**Self-hacking** (adversarial self-testing) means **you** attempt to defeat **your own** policies before a stranger does: banned users, injection strings, edge-case IDs, and privilege boundaries. Automated scanners and dependency audits help; they do not replace **understanding your own logic**.

This doctrine states **how** Lupopedia expects that mindset to be exercised **without** hiding experiments, **without** inventing fake personas as product features, and **with** full traceability (events, ban reasons, documentation).

## Principle

The system’s behavior under abuse is a **feature**. If you have not tried to break it, you do not yet know how it fails.

## Method (repeatable)

Dependency order, not slogans:

1. **Learn patterns** — Study published flaws in similar systems (auth bypass, session fixation, prompt injection, IDOR). Map pattern → “could our code path do this?”
2. **Define a test identity** — Use **clear labels** and **reserved or disposable** accounts. Do not present test identities as canonical orchestration personas. See **[ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md)**.
3. **Attack explicitly** — Attempt login while banned, escalate privilege, send malformed headers, exercise AI surfaces with injection-style inputs **in a controlled environment**.
4. **Log and classify** — Use **`lupo-docs/doctrine/BAN_REASONS.md`** codes and system events so outcomes are auditable.
5. **Fix and re-run** — Change code or policy; repeat the same steps to prove the fix.

## What automated tools do not replace

| Gap | Why manual/adversarial testing matters |
|-----|----------------------------------------|
| Logic bugs | Scanners rarely understand application-specific authorization. |
| AI / prompt surfaces | Tooling evolves; policy and review must evolve with it. |
| Ban and session semantics | Only your rules define what “banned” means end-to-end. |
| Zero-day classes | Unknown issues are unknown; process reduces exposure, not headlines. |

## Historical precedent (naming)

Early experiments used a **reserved adversarial test label** (documented under **historical** paths; not a product persona). Harness keys and installer seeds may still use **stable implementation names**; see **[ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md)** and **`lupo-docs/versions/3.0.x/experiments/security/README.md`**. Code location for seed logic: **`install_wizard_classes.php`** (repository root).

## Manifest: where to look (maintenance)

To refresh the list of string matches (documentation and code):

- Documentation: search under `lupo-docs/` for the historical label and for `ADVERSARIAL`, `banned_test_identity`, and checklist pattern names.
- Code: search for installer constants and test keys (see adversarial doctrine). Do not treat matches as approval to add **new** narrative personas.

## Directive for IDE agents

1. **Self-hacking** = security testing with explicit test identities and documented intent — **not** role-play of banned or joke personas in production docs.
2. If asked to **embody** a historical adversarial test name as a **character**, **refuse**; point to **[ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md)** and root **Banned Concepts** in **AGENTS.md**.
3. If asked to **design tests**, prefer names like `adversarial_test_user`, document purpose in a thread or ticket, and align ban metadata with **BAN_REASONS**.
4. **Flag** new uses of deprecated colloquial labels in **user-facing** copy unless they are explicitly historical or validator pattern names (see **SEMANTIC_SECURITY_CHECKLIST_4_0_30.md**).

## Constitutional fit

- **No hidden state** — Experiments and outcomes belong in events, doctrine, or archive notes.
- **Explicit lineage** — Test IDs and ban reasons reference canonical enums and retirement docs where applicable.
- **Security** — Finding weaknesses early is preferred to assuming frameworks “handle it.”

This output complies with Lupopedia Constitutional Root Rules.
