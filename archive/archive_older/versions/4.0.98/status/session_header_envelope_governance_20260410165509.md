---
lupopedia.headers:
  header_format_version: "4.0.98"
  lupopedia.schema: documentation
  when_updated: "20260410191942"
  file_path_from_root: "docs/versions/4.0.98/status/SESSION_HEADER_ENVELOPE_GOVERNANCE_20260410165509.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.0.98/status/SESSION_HEADER_ENVELOPE_GOVERNANCE_20260410165509.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/status-header-envelope-governance-20260410165509.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Session report \u2014 LUPOPEDIA HEADERS 25-line envelope governance"
  status: "active"
  parent_pk_id: "16"
  summary: ""
  module: null
  dialog_transcript: "0/development/4_0_98_status"
---
# Session report: LUPOPEDIA HEADERS 25-line envelope governance

**Packed UTC (original session batch):** `20260410165509` (**16:55 UTC**). **Header timestamps** in YAML updated to **`20260410191942`** when aligning this file to PRD 16 (**`content_id: null`**, **`thread_id: ''`**, **`artifact_kind: guide`**, **`prd_id`/`prd_slug`** null/empty for **`documentation`**, two blank lines before closing `---`); **`validate_lupopedia_headers_universal.py`** reports **PASS**.  
**Primary actor:** Cursor IDE Agent (**actor_id 102**, WOLFIE execution / documentation stewardship)  
**Review / audit voice:** LILITH auditor setup (**actor_id 2**) — script reviews and root-cause analysis (no code ownership change)

---

## WHO did WHAT (summary)

| Actor / role | Work |
|--------------|------|
| **WOLFIE (human orchestrator)** | Directed header doctrine alignment, envelope fixes, and version-folder documentation updates. |
| **Cursor (102)** | Implemented validator hints, peel guard fix, tooling (`add_lupopedia_header_to_file.py`, templates, Cursor rule, git-hook sample), AGENTS.md mandatory-header section, PRD 16 / FORMAT doctrine edits, `normalize_lupopedia_md_header_25.py` header, PRD 16 `memory_key` path correction, CHANGELOG/TODO/PLAN updates (this batch). |
| **LILITH (2)** | Audited scripts (validator, header_spec, fix_double_headers, batch_validate, unicode fix); identified peel/index confusion and optional-field wording risks. |

---

## WHERE it applies

- **Runtime / validation:** `scripts/validate_lupopedia_headers_universal.py`, `scripts/lib/lupopedia_markdown_header_peel.py`, `scripts/lib/header_spec_v3_1.py`
- **Normalization / repair:** `scripts/fix_double_headers.py`, `scripts/normalize_lupopedia_md_header_25.py`, `scripts/batch_validate_prd_headers.py`, `scripts/fix_unicode_box_drawing_ascii.py`, `scripts/add_lupopedia_header_to_file.py`
- **Doctrine / PRD:** `docs/prd/16_lupopedia_headers.md`, `docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`, `docs/doctrine/LUPOPEDIA_HEADERS/TEMPLATES_NEW_FILE.md`, `docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`
- **Agent / IDE policy:** `AGENTS.md`, `.cursor/rules/lupopedia-headers-mandatory.mdc`, `scripts/git-hooks/pre-commit-lupopedia-headers.sample`, `agents/_TEMPLATE/LUPOPEDIA_FILE_HEADER_README.txt`
- **Truth data:** `docs/prd/16_lupopedia_headers.md` `memory_key` now points at existing `16-lupopedia-headers.toon` under `memory/development/canonical/2026/04/`

---

## WHEN (with hour)

Single batch anchor for documentation in this report: **2026-04-10 16:55 UTC** (`20260410165509`). Earlier edits in the same multi-turn session used other `tick.py` anchors; all are valid UTC batches per TIMESTAMP doctrine.

---

## WHY it was done

1. **Constitutional / PRD 16:** The **25-line** envelope (Markdown: lines 1–25 header, line **26** body) and **all 20 keys present** are non-negotiable; missing keys or wrong blank/`---` placement break validation in non-obvious ways.
2. **Operational pain:** IDEs created files **without** headers; normalizers fixed shape but did not **invent** missing headers — policy + tooling + IDE rules were required.
3. **Correctness:** The markdown **peel** fast path used **full-file** line indices after **stripping** the opening `---\n`, causing the 25-line branch to never match until **tail-relative** indices (**21, 22, 23**) were used; inner extraction stays **`lines[0:21]`** in **tail** coordinates (lupopedia + 20 keys — not `[:22]`, which would pull a blank into inner).
4. **Traceability:** Version **4.0.98** folder needs a single place for **WHO/WHAT/WHERE/WHEN/WHY/HOW** for envelope governance.

---

## HOW it was implemented (mechanisms)

- **Validator:** `[HINT]` when line-count / envelope fails lists **missing §4.2 keys** (e.g. `content_id`); PRD **§19.3** documents extended codes (`HDR_PYTHON_*`, `HDR_MISSING_BLANK_LINE`, etc.); removed dead **`generate_context_id`** from validator.
- **Peel:** Envelope detection for **tail** `split("\n")`: blanks **`lines[21]`–`lines[22]`**, closing **`lines[23]`**; prefix / inner use **`lines[0:21]`** unchanged.
- **Mandatory headers:** `add_lupopedia_header_to_file.py` (runs `tick.py` then `echo_anchor_utc.py`), `TEMPLATES_NEW_FILE.md`, **always-apply** Cursor rule, optional **git hook** sample, **AGENTS.md** section.
- **Doctrine:** Clarified **no key may be omitted** — “optional” applies to **values** (`''` / `null`), not keys.
- **PRD 16:** **`memory_key`** corrected to the **on-disk** `16-lupopedia-headers.toon` path.

---

## Troubles, observations, and what we learned

### Why the 25-line envelope is hard to keep consistent

1. **Two coordinate systems:** Validators and human mental models often use **1-based file lines**; **`tail.split("\n")`** after removing `---\n` shifts indices — easy to off-by-one the **closing `---`** and blank lines.
2. **“Optional field” language:** Agents interpreted **optional** as **omit the key**, which drops a line in the 3–22 key block and cascades to **HDR_LINE_COUNT** / **HDR_MISSING_CLOSE**; fix was **doctrine + hint text**, not a new key.
3. **Missing `content_id`:** Dropping **`content_id: null`** removes one key line and shifts the rest; same symptom as (2).
4. **`memory_key` paths:** Header pointed at a **non-existent** `.toon` filename; self-check text requires the path to exist — **truth** is the paired JSON/TOON on disk.
5. **Temporal anchor:** Scripts must use **`tick.py` / `echo_anchor_utc.py`**, not guessed local time — `add_lupopedia_header_to_file.py` encodes that.

### Are we missing rules, validations, or exceptions?

| Gap | Mitigation already in tree or recommended |
|-----|-------------------------------------------|
| IDE skips headers | `.cursor/rules/lupopedia-headers-mandatory.mdc` + **AGENTS.md** |
| No block on commit | Optional **`pre-commit-lupopedia-headers.sample`** (substring check); full compliance = run universal validator in CI |
| Substring-only hook | Does not prove 25-line envelope — **validator** remains authoritative |
| Legacy files | `parse_front_matter_yaml` **fallback** `find("\n---\n")` for non-25-line migration |

### Idea: “reserved” 21st key line to fill space?

**Not recommended.** PRD 16 fixes **20** scalar keys in **fixed order**; adding a reserved filler key would **change schema**, validators, and TOON — high cost. Better levers: **IDE rules**, **`add_lupopedia_header_to_file.py`**, **pre-commit**, and **validator hints** that name **missing keys** when the envelope breaks.

### Related prior note in this folder

See **`HEADER_LESSONS_20260410103101.md`** for earlier envelope lessons; this report extends that thread with peel index fix and mandatory-header policy.

---

## Completion criteria (this governance slice)

- [x] Peel fast path uses **tail-relative** blank/`---` indices.
- [x] Validator surfaces **missing-key hint** on envelope failure.
- [x] Mandatory-header policy documented (**AGENTS**, Cursor rule, templates, add-header script).
- [x] PRD 16 **`memory_key`** matches existing memory export file.
- [x] Version **4.0.98** CHANGELOG / TODO / PLAN updated with this session.

This output complies with Lupopedia Constitutional Root Rules.
