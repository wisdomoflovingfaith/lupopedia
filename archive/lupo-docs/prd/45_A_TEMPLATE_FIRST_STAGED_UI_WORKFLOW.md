---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/45_A_TEMPLATE_FIRST_STAGED_UI_WORKFLOW.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/45_A_TEMPLATE_FIRST_STAGED_UI_WORKFLOW.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/45_template_first_staged_ui_workflow.toon
  atoms_toon: null
  transcript_jsonl: 0/development/45-template-first-staged-ui-workflow
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_45_A_TEMPLATE_FIRST_STAGED_UI_WORKFLOW
  title: "PRD 45: Template-First, Gate-Gated UI Workflow (Staged Operator and Admin Implementation)"
  summary: "Template-first staged UI: workflow gates G0-G4, prohibition on direct PRD-to-ship for scoped surfaces, lupo-templates then lupo-includes/lang and lupo_t, then entrypoints; cross-refs PRD 13 16 29 31 00; severity for bypass."
---
# PRD 45: Template-First Development Workflow (Staged UI Implementation)

## 1. Purpose

This PRD defines the **Template-First Development Workflow** (also called **Staged UI Implementation**) for **non-public-facing** surfaces: **admin tools**, **dashboards**, **operator pages**, and **internal templates** shipped under paths such as **`lupo-templates/admin/`**.

**Goal:** Reduce coupling between structure, data flow, and localization so iteration stays fast in plain editors (including Notepad-class workflows), matching the **Crafty Syntax** delivery pattern that scaled across **fourteen languages** without front-loading every translation key.

**Cross-references:**

- **[PRD 00 ??? Root constitutional system requirements](00_root_constitutional_system_requirements.md)** ??? **RULE 93.UI_STRINGS_LOCALE** (ship-facing strings, **`lupo_t()`**).
- **[PRD 13 ??? Crafty Syntax 3.7.5 integration](13_crafty_integration.md)** ??? legacy upgrade path and historical **multi-locale** delivery discipline (reference for why staged language widening exists).
- **[PRD 29 ??? Project structure](29_project_structure.md)** ??? **`lupo-templates/`** and repository layout authority.
- **[PRD 31 ??? Implementation folder guidelines](31_implementation_folder_guidelines.md)** ??? implementation mirror and scaffolding after the PRD text stabilizes.
- **[PRD 16 ??? Lupopedia headers](16_lupopedia_headers.md)** ??? when artifacts become canonical or leave the template-only phase.
- **[PRD 02 ??? Channels](02_channels_discussions.md)** ??? example integration path: partial templates included from **`channels/index.php`** with **`admin_layout.php`** chrome (read-only API provider dashboard).

**Related doctrine (consumer vs operator split):** **[WOLFIE_WORKFLOW_DOCTRINE.md](../doctrine/WOLFIE_WORKFLOW_DOCTRINE.md)** and **[MOBILE_SEPARATION_DOCTRINE.md](../doctrine/MOBILE_SEPARATION_DOCTRINE.md)** ??? this PRD does **not** relax public-page rules; it scopes **internal** and **admin** iteration only.

---

## 2. Template-First Development Workflow (normative phases)

Follow these phases **in order**. Completion of each phase is a prerequisite for the next.

### Phase 1 ??? Structure and function (English only)

- Build the template under **`lupo-templates/admin/`** (or another **appropriate subdirectory** agreed in the owning PRD or implementation mirror).
- Use **plain, clear English** string literals in PHP / HTML output.
- Focus on **correct data flow**, **approved service usage** (no raw PDO; **`PDO_DB`** / facades per root rules), **layout structure**, and **base-case behavior** (empty config, missing service, human-only mode, and similar must not hard-fail).
- **Do not** introduce **`lupo_t()`** calls or new **`lupo-includes/lang/`** keys in this phase.
- **Exit criterion:** The page or partial is **readable**, **testable**, and **functionally correct** in English.

### Phase 2 ??? Review and polish

- Exercise functionality (manual or scripted checks as available for the surface).
- Refine wording and layout **still in English**.
- Remove placeholder noise; align copy with Lupopedia tone (factual, operator-facing).
- **Exit criterion:** Stakeholders accept English UX and structure; no open **P0** defects for the feature.

### Phase 3 ??? Localization pass

- Replace plain strings with **`lupo_t('semantic.key', 'Fallback English')`** (or the project???s sanctioned i18n pattern for that surface).
- Add new keys to **`lupo-includes/lang/lupo-en.php`** first; mirror other supported locales when required by **[PRD 00](00_root_constitutional_system_requirements.md)** **RULE 93.UI_STRINGS_LOCALE** and project locale policy.
- **Exit criterion:** Validators and spot checks pass; English catalog is complete for the touched keys.

### Phase 4 ??? Integration and graph

- **Include** or **route** the template from the owning entry point (e.g. **`channels/index.php`**, **`admin.php`** section handler) so it inherits **layout chrome** (e.g. **`admin_layout.php`**) instead of duplicating full **`<html>`** shells in the partial.
- Add **query parameters** or **menu entries** only when the owning PRD or spec requires them (example: **`?api_dashboard=1`** for the read-only API provider dashboard).
- Generate or update the **memory TOON** sidecar (**`*.toon`**, TOON-M) for semantic graph continuity per **[PRD 16](16_lupopedia_headers.md)** and **[PRD 38](38_memory_unification.md) section 3** (database + export mirror) **and section 6** (**MemoryExportService**, header-associated memory files) where this artifact participates in the graph, and per **[PRD 51](51_memory_graph_as_source_of_truth.md)** for graph authority and header-inference precedence.
- **Exit criterion:** Integrated page matches layout and auth expectations; headers and memory pointers are consistent.

### Workflow gates (G0 through G4)

This PRD is the **constitutional workflow** between **PRD prose** and **ship entrypoint** code for **operator / admin / internal** surfaces. It exists because **direct PRD-to-production-file** jumps routinely produce mixed logic and strings, missing locales, and unreplayable diffs.

| Gate | From | To | Role |
|------|------|-----|------|
| **G0** | Problem / directive | **PRD** (and **PRD 31** mirror when used) | **WHAT** is authorized |
| **G1** | PRD | **`lupo-templates/{scope}/`** | **Staged HOW**: structure, control flow, data wiring (**PRD 29** tree) |
| **G2** | Template draft | **Human or orchestrator review** | Reject unsafe patterns, wrong service boundaries, missing edge cases |
| **G3** | Reviewed template | **`lupo-includes/lang/lupo-{locale}.php`** + **`lupo_t()`** | **Strings only** in catalogs; logic stays in PHP partials and classes |
| **G4** | Localized partial | **Ship entrypoints** | **DEPLOYED wiring**: includes from **`admin.php`** handlers, **`channels/index.php`**, **`lupo-includes/themes/`**, **`login.php`**, and similar served paths ??? **not** a second hidden copy of the same business rules |

**Repository note:** There is **no** separate top-level **`public/`** application tree in this layout; **ship entrypoints** are the PHP/HTML/JS files the installer serves (**PRD 29**).

**G1 may open when:** the owning PRD or **PRD 31** mirror names the feature, auth boundary, and template subdirectory.

**G2 may close when:** Phase 1???2 exit criteria are met for **internal** surfaces **or** the **public-facing** exception in **??4** is satisfied (**visitor routes never ship Phase-1 English literals**).

**G3 may open when:** English UX is accepted; new keys land in **`lupo-en.php`** first, then other allowed locales.

**G4 may close when:** integration uses shared chrome; **PRD 16**, **PRD 38**, and **PRD 51** pointers and precedence are consistent for promoted artifacts.

### Prohibition: direct PRD-to-ship UI (CRITICAL for scoped work)

For **new** or **materially expanded** **operator / admin / internal** UI covered by this PRD:

1. **FORBIDDEN:** Implementing **only** from PRD paragraphs pasted into a **ship entrypoint** (for example a large new **`admin.php`** region) **without** a reviewable **`lupo-templates/`** artifact that can be exercised in isolation.
2. **FORBIDDEN:** Treating **industry-default** ???approved spec, then open PR against production file??? as **implicit permission** here. **Common practice is not compliance.**
3. **FORBIDDEN (CRITICAL):** **Visitor-facing** surfaces that show **user-visible** literals **without** **`lupo_t()`** per **PRD 00** and **??4**.

**Remediation:** revert or quarantine the ship change; restore **PRD to template to locale to entrypoint** ordering; record systemic bypass in **`lupo-docs/audits/`** when discovery is cross-cutting.

**Approval record:** use **channel artifacts**, **implementation mirror** `status/` notes, or **PRD 16** metadata on promoted files ??? **do not** invent ad hoc mini-schemas inside template bodies unless a separate PRD authorizes them.

### Industry default (non-normative context)

Many org charts jump **requirements to grooming to dev** with **no** template-gated fragment, **no** string catalog boundary, and **no** graph/header promotion step. That normalized pipeline is **why** multi-agent and multi-locale work fails under automation. This PRD **rejects** that default for the surfaces it governs.

### Template and locale file contracts (minimal)

- **`lupo-templates/`** partials **MAY** contain structure, includes, and calls into **sanctioned** application code paths (**PDO_DB**, services). They **MUST NOT** introduce **`lupo_t()`** until **Phase 3** unless the surface is **public-facing** per **??4**.
- **`lupo-includes/lang/*.php`** catalogs **MUST** expose **string data** (arrays returned to **`lupo_t()`** consumers). They **MUST NOT** perform authorization decisions, schema migrations, remote I/O, or other **business logic**.

---

## 3. Rationale

| Topic | Rule |
|-------|------|
| Cognitive load | Translation keys and catalog churn add overhead during early structure iteration. |
| Refactor safety | Plain English allows faster **delete / move / split** of markup without chasing key renames. |
| Provenance | Matches the historical **Crafty Syntax** process: ship **shape + behavior**, then **widen languages**. |
| Coupling | Avoids premature binding between **UI structure** and **locale catalogs** before the UX is stable. |

---

## 4. Exceptions and overrides

| Surface | Rule |
|---------|------|
| **Public-facing** visitor pages (marketing, visitor chat, embeds) | **`lupo_t()` from the first shipped line** per **[PRD 00](00_root_constitutional_system_requirements.md)** and **[AGENTS.md](../../AGENTS.md)** operator/consumer split. |
| **Critical safety or compliance copy** | May move to **`lupo_t()`** earlier if **user-visible** and **legally or safety-sensitive** (confirm in implementation notes). |
| **Base-case banners** that ship to non-admin users | Treat as **public-facing** unless explicitly scoped as **operator-only** behind auth. |

---

## 5. Non-goals

- This PRD does **not** authorize **skipping** **`lupo_t()`** on **ship-ready public** routes.
- This PRD does **not** override **database**, **header**, or **memory** doctrines; it sequences **UI work** only.

---

*This output complies with Lupopedia Constitutional Root Rules.*
