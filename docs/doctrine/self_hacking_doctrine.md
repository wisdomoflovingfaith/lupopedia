---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/self_hacking_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/self_hacking_doctrine.md
  status: active
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: security_self_assessment
  channel_key: null
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: SELF_HACKING_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/SELF_HACKING_DOCTRINE.md

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
4. **Log and classify** — Use **`docs/doctrine/BAN_REASONS.md`** codes and system events so outcomes are auditable.
5. **Fix and re-run** — Change code or policy; repeat the same steps to prove the fix.

## What automated tools do not replace

| Gap | Why manual/adversarial testing matters |
|-----|----------------------------------------|
| Logic bugs | Scanners rarely understand application-specific authorization. |
| AI / prompt surfaces | Tooling evolves; policy and review must evolve with it. |
| Ban and session semantics | Only your rules define what “banned” means end-to-end. |
| Zero-day classes | Unknown issues are unknown; process reduces exposure, not headlines. |

## Historical precedent (naming)

Early experiments used a **reserved adversarial test label** (documented under **historical** paths; not a product persona). Harness keys and installer seeds may still use **stable implementation names**; see **[ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md)** and **`docs/versions/3.0.x/experiments/security/README.md`**. Code location for seed logic: **`install_wizard_classes.php`** (repository root).

## Manifest: where to look (maintenance)

To refresh the list of string matches (documentation and code):

- Documentation: search under `docs/` for the historical label and for `ADVERSARIAL`, `banned_test_identity`, and checklist pattern names.
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
