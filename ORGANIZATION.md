---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: ORGANIZATION.md
  web_path: https://www.lupopedia.com/lupopedia/ORGANIZATION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/organization-root.toon
  atoms_toon: null
  transcript_jsonl: 0/development/organization-root
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: guide
  prd_cluster: 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS
  title: Repository organization (root guide)
  summary: Root-level doc map; PRD 26 alignment; documentation placement gate (anti-litter); doctrine-PRD linkage; changelog buffers; UTF-8 transport + ASCII-only authored prose (see Encoding); PRD gaps; root markdown rule.
---
# Repository organization (root guide)

This file is the **root-level** map for humans and IDE agents. It **does not** override **PRD 00** or topic PRDs; it **routes** readers to the correct layer.

Lupopedia treats **undisciplined `docs/` trees and root markdown sprawl** as **data litter**, not documentation. Canonical prose is **PRD-first**, **header-validated**, **cross-linked**, **timestamped**, and **multi-agent coordinated** (see **[AGENTS.md](AGENTS.md)** changelog buffers and channel handoffs). This guide states **where** prose may live; **PRD 26** states **layering and authority**.

## Documentation placement gate (canonical prose)

Hand-authored **documentation** (normative or explanatory Markdown meant as system truth) **MUST** live in one of:

1. **`lupo-docs/prd/`** -- two-digit group files **`NN_*.md`** (see **`PRD_INDEX.md`**, regenerated only via **`lupo-scripts/generate_prd_index.py`**).
2. **`lupo-docs/doctrine/`** -- **only** when **inbound-linked from at least one PRD** (or listed under an explicit doctrine index maintained by **PRD 26** / **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)**). Floating doctrine is **invalid**.
3. **`lupo-docs/audits/`** -- time-bounded audits, integration reports, and **explicit justifications** (for example, why a file is not yet relocated).
4. **`lupo-docs/implementations/{prd_file_stem}/`** -- **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)** implementation mirrors (same stem as **`lupo-docs/prd/{prd_file_stem}.md`**); not a substitute for PRD or doctrine.

**Temporal / coordination artifacts** (task completion, multi-IDE collision avoidance): JSON under **`lupo-changelog-pending/`** and **`lupo-changelog-archive/`** per **AGENTS.md**, not random narrative files at repository root.

If prose fits none of the above, **it does not belong** in the tree as documentation until it is moved, split, or given a PRD-backed home.

## Root Markdown (repository root)

At repository root, **`README.md`** and **`ORGANIZATION.md`** are the **default-allowed** Markdown documentation entrypoints. **Any other root `*.md`** is **presumed invalid** documentation unless **`lupo-docs/audits/`** (or a PRD-scoped implementation mirror) records **explicit justification** and a **planned relocation or retirement**.

## Alignment with PRD 26 (Five-Layer Documentation Architecture)

Canonical layering, authority split (authored filesystem vs runtime DB), and examples live in **`lupo-docs/prd/26_five_layer_documentation_architecture.md`**.

**Rule:** If this guide ever disagrees with **PRD 26**, **PRD 26 wins** and this file must be corrected.

## Where things live (summary)

| Area | Location |
|------|----------|
| Normative product specs | **`lupo-docs/prd/`** (`NN_*.md`; index auto-generated) |
| Doctrine and constitutional detail | **`lupo-docs/doctrine/`** |
| Implementation mirrors | **`lupo-docs/implementations/`** (per **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)**) |
| Audits and integration reports | **`lupo-docs/audits/`** |
| Reserved PRD numbers / gaps | **`lupo-docs/doctrine/PRD_GAPS.md`** |

## Doctrine folder linkage rule

Every new hand-authored **`lupo-docs/doctrine/**/*.md`** SHOULD be **linked from at least one PRD** (hyperlink or explicit index under **PRD 26** / **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)**). Orphan tracking and audit status: **`lupo-docs/audits/DOCTRINE_PRD_LINKAGE_AUDIT.md`**.

## Changelog buffer integration (multi-IDE)

- **Pending entries:** `lupo-changelog-pending/` -- one JSON file per logical task; filename **`YYYYMMDDHHMMSS_{agent_slug}_{hash}.json`** using real UTC from **`python lupo-bin/tick.py`** (see **[AGENTS.md](AGENTS.md)**).
- **Archive:** `lupo-changelog-archive/` -- consolidated history (owner: THOTH / merge process per project policy).
- **Handoff `.toon` / memory:** pointer lives in **`lupopedia.headers`** (`memory_toon`, `transcript_jsonl`); do not use changelog files as a substitute for headers.
- **Timestamp collisions:** reuse the **same** tick batch for all files touched in one edit wave (`echo_anchor_utc.py`); never guess UTC across IDE sessions.

## Encoding standard

- **Transport:** Author and save **UTF-8 without BOM** for Markdown and code (Windows Notepad "UTF-8" export is the operator baseline).
- **Visible characters (normative):** ALL text in this repository MUST be strictly ASCII (code points U+0020 through U+007E only). This includes Markdown, headers, code, comments, tables, commit messages, logs, JSON/YAML/TOON payloads, database strings, channel handoffs, CLI output, and user-facing copy. Emoji, Unicode arrow glyphs, box drawing characters, curly quotes, em/en dash characters, and any other non-ASCII glyph are FORBIDDEN. There are NO EXCEPTIONS. Use ASCII punctuation only (for example `--` for em dash, straight `'` and `"`, and `->` / `<->` for direction). Full mandate, applicability list, and replacement rules: **[AGENTS.md](AGENTS.md)** section **ASCII-ONLY DOCTRINE (LILITH / constitutional)**.
- **Locale catalogs:** Translated visitor strings under **`lupo-includes/lang/`** follow **PRD 00** RULE 93; keys and agent-added comments stay ASCII-only.
- **Mojibake:** Garbled apostrophe or punctuation in titles indicates a source encoding mistake. Fix **source** files, then regenerate **`PRD_INDEX.md`** via **`lupo-scripts/generate_prd_index.py`**.

## Two-digit PRD groups and gaps

PRD **NN** is a **group id**; multiple files may share **NN** with exactly **one** primary marker (**`(primary)`**) in **`PRD_INDEX.md`**. Missing numbers are not automatically defects; see **`lupo-docs/doctrine/PRD_GAPS.md`**.

---

## Verification checklist (documentation architecture)

- [x] **ORGANIZATION.md** (this file) explicitly defers layering authority to **PRD 26**.
- [x] Doctrine linkage rule stated: doctrine Markdown **SHOULD** be linked from **>= 1** PRD (or indexed under **PRD 26** / **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)**).
- [x] Changelog buffer coordination documented for multi-IDE workflows (**`tick.py`**, pending JSON, no guessed UTC).
- [x] Encoding standard (**UTF-8 without BOM**) stated to prevent mojibake drift in titles.
- [x] Two-digit grouping includes pointer to **PRD_GAPS.md** for reserved vs missing numbers.
- [x] Documentation **placement gate** (PRD / linked doctrine / audits+changelog buffers / [PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md) mirrors) and **root Markdown** rule (**`README.md`** + **`ORGANIZATION.md`**) stated explicitly.
