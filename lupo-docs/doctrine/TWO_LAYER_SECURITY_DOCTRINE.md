---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md"
  last_modified_utc: "20260403122335"
  when_updated: "20260403122335"
  federation_node_id: 0
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: doctrine
  artifact_kind: security_two_layer_model
  purpose: "Define two-layer security: automated baseline checks first, then adversarial self-testing; align tooling reality with Lupopedia constraints"
  status: active
  tags:
    - security
    - automation
    - adversarial_testing
    - doctrine
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Security posture under constitutional rules"
    - to: "lupo-docs/doctrine/SELF_HACKING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Layer 2 methodology (adversarial self-testing)"
    - to: "lupo-docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Test identity naming; not a product persona"
    - to: "lupo-docs/doctrine/BAN_REASONS.md"
      type: references
      weight: 0.95
      reason: "Document ban outcomes and enum codes"
    - to: "lupo-docs/doctrine/SEMANTIC_SECURITY_CHECKLIST_4_0_30.md"
      type: references
      weight: 0.85
      reason: "Semantic and pattern-oriented checks"
    - to: "lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Study packages without adopting them as runtime dependencies"
lupopedia.footer:
  last_verified: "20260403122335"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  next_action:
    - "Revisit Layer 1 tooling when deployment topology (CI, staging URL) changes"
---

# file: TWO_LAYER_SECURITY_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md

# Two-layer security doctrine (4.0.x)

## Principle

Effective security practice uses **two layers** in order:

1. **Layer 1 — Baseline / automation-friendly checks** — Find common classes of issues quickly and repeatably (known CVE classes, typical web flaws, static patterns).
2. **Layer 2 — Adversarial self-testing** — Find **logic**, **policy**, and **AI-surface** gaps that scanners miss. Defined in **[SELF_HACKING_DOCTRINE.md](SELF_HACKING_DOCTRINE.md)** and constrained by **[ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md)**.

Skipping Layer 1 wastes time; skipping Layer 2 misses the clever failures.

## Layer 1: Baseline (what “modern tools” mean here)

**Important:** Lupopedia’s **runtime** avoids Composer/npm in core paths (see **AGENTS.md**). That means **dependency scanners often have nothing to scan** in the application tree. That is expected. The **pattern** still applies: *if you introduce a dependency, you must track and scan it*.

| Class | Role | Typical examples | Lupopedia note |
|-------|------|------------------|----------------|
| Dependency / supply chain | Known CVEs in libraries | `npm audit`, Snyk, OS package audits | **No npm/composer app deps** in core; pattern is “scan what you ship.” |
| Dynamic web testing (DAST) | HTTP-level issues | OWASP ZAP, Burp Suite | **Applicable** to the PHP app as deployed (local or staging). |
| Static / custom greps | Dangerous patterns in tree | Project scripts, CI rules | Use **carefully**; avoid false positives; prefer structured review. |
| Edge / WAF | Network filtering | Cloudflare WAF | **Optional** per deployment; not assumed in all installs. |

Layer 1 tools are **run by people** against builds or URLs. They are **not** a license to add Composer/npm **into** the core product to “get scans.”

## Security testing dependencies vs runtime dependencies

The constitution bars **runtime** dependencies in **shipped application code** (no Composer `vendor/` in core PHP paths, no npm-driven app stack in `lupo-includes/` or the live site). That does **not** forbid installing tools on a **developer machine**, in **CI**, or under a **test-only** directory to **assess** the application.

| Kind | Examples | Allowed in shipped Lupopedia app tree? |
|------|-----------|----------------------------------------|
| **Runtime dependency** | Composer packages, npm packages loaded by the live PHP/JS app | **No** (see **AGENTS.md** PHP constraints) |
| **Security / test tooling** | OWASP ZAP, Burp Suite, Python `requests` in a scan script, devDependencies in an isolated test harness | **Yes, only outside shipped runtime** — local install, CI image, or a **dedicated test subtree** (never mixed into `lupo-includes/` or production entrypoints) |

**Boundary rule:** *What you use to test stays in the test environment.* Scanners and helper packages **do not ship** with the product. Existing **IDE extension** caches (e.g. under `lupo-tools/`) are **tooling**, not the Lupopedia web runtime; keep them separate from application bootstrap.

**Examples**

- **Allowed:** Run ZAP or Burp against `http://localhost/.../lupopedia/`; run `pip install` inside a CI job or venv used only for security scripts; keep optional `package.json` **only** under a clearly named test or tooling path if the project adds one for scans (do not wire it into the live app).
- **Forbidden:** `composer require` or `npm install` for libraries consumed by **`lupo-includes/`** core request path, or committing `node_modules/` as part of the **served** application.

## Dependency analysis vs dependency adoption

**Analysis** means you read, trace, or experiment with third-party code **outside** the shipped app (clone upstream, local scratch, CI read-only checkout) to learn behavior and algorithms.

**Adoption** means the product **depends** on that artifact at runtime (Composer `vendor/`, npm in the live bundle, `require` of foreign code in core paths).

| Mode | Goal | Constitutional for core? |
|------|------|---------------------------|
| **Analysis** | Understand CVE classes, compare algorithms, inform a native implementation | **Yes** (no runtime link) |
| **Adoption** | “Fix security by pulling package X into the app” | **No** (violates no-runtime-deps posture) |

**Rule:** You may study any package; you cannot blindly include it. Prefer reimplementation and documented **`inspired_by`** lineage — see **[REVERSE_ENGINEERING_DOCTRINE.md](REVERSE_ENGINEERING_DOCTRINE.md)**.

## Layer 2: Stress test (adversarial self-hacking)

Layer 2 answers: *Does our **own** policy hold when someone tries to break it?*

- Banned or disallowed identities, session behavior, direct API calls.
- Prompt and instruction-injection style probes on any AI surface.
- Privilege boundaries (admin vs user, channel posting, actor attribution).

Historical **reserved test labels** and harness keys (sometimes referred to in older docs and checklists) are **test artifacts**, not orchestration personas — see **[ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md)**. Use **clear test names** and **[BAN_REASONS.md](BAN_REASONS.md)** for outcomes.

**Do not** hand-write arbitrary SQL in docs as “how to create users”; use installer/seed patterns and reserved-ID doctrine.

## Combined workflow (dependency order)

1. Run **Layer 1** checks that apply to your environment (e.g. ZAP baseline against staging, Burp where used, any allowed static checks).
2. Record **N/A with reason** where a tool does not apply (e.g. “no npm manifest in core”).
3. Execute **Layer 2** per **SELF_HACKING_DOCTRINE** (explicit test identity, controlled attempts, logging).
4. Fix findings; repeat Layer 1 then Layer 2 on the changed surface.

## What each layer catches (roughly)

| Layer | Tends to find | Tends to miss |
|-------|----------------|---------------|
| 1 | Many common web classes, dependency CVEs (when deps exist), misconfigurations | Application-specific authorization logic, AI prompt issues, ban semantics |
| 2 | Logic and policy failures, “do it anyway” paths | May still miss unpatched generic bugs if Layer 1 skipped |

## Directive for IDE agents

1. Prefer **Layer 1 first** when the user asks for “security testing,” then **Layer 2**.
2. State **why** a tool is N/A for this repo when applicable (no dependency manifest, self-hosted, etc.).
3. For Layer 2, follow **SELF_HACKING** and **ADVERSARIAL** doctrines: **no** role-play of historical test labels as product characters.
4. File or reference findings with **[BAN_REASONS.md](BAN_REASONS.md)** codes when bans and policies are involved.

This output complies with Lupopedia Constitutional Root Rules.
