---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "rule"
  file_path_from_root: "lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md"
  web_path: "http://www.lupopedia.com/lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE"
  questions_toon: null
  channel_id: 51
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "rule"
  artifact_kind: "header_enforcement"
  purpose: "Mandatory rewrite of lupopedia.headers when version_when_written is below 4.0.84 or deprecated version keys are present"
  tags: ["lupopedia_headers", "version_when_written", "4.0.84", "baseline", "enforcement"]
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Run header rewrite on any touched file still below 4.0.84 baseline"
    - "Keep LUPOPEDIA_HEADERS_FORMAT.md §2.0 in sync with this rule"
---
# file: LUPOPEDIA HEADERS version baseline rewrite — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE

# LUPOPEDIA HEADERS — Version baseline rewrite (4.0.84+)

## Purpose

Ensure every Markdown file that carries LUPOPEDIA HEADERS is brought up to the **4.0.84+ header model** when it is edited, so deprecated version fields and pre-baseline `version_when_written` values do not linger in active files.

## Rule (non-negotiable on write)

When an agent or tool **writes** or **materially edits** a file that contains a LUPOPEDIA HEADERS YAML block, it MUST **before save**:

1. **Evaluate** `lupopedia.headers`:
   - If **`version_when_written`** is **missing**, or
   - If it names a Lupopedia system version **strictly before 4.0.84** (see comparison note below), or
   - If **any** of these appear under `lupopedia.headers`: `lupopedia.version`, `system_version`, `last_verified_system_version`, or a standalone `version` key,

   then the file is **below baseline** and the header block MUST be rewritten.

2. **Rewrite** (same edit / same PR):
   - Rebuild **`lupopedia.headers`** per current doctrine: at minimum **`version_when_written`** (set to the **current** system version from **`LUPEDIA_VERSION`** / project atoms at rewrite time) and **`file_path_from_root`** (accurate path from repo root).
   - **Remove** all deprecated version-related keys listed above from `lupopedia.headers`.
   - Preserve other valid optional fields where appropriate (`lupopedia.schema`, `web_path`, `channel_id`, `actor_id`, `purpose`, `tags`, `namespace` when required, etc.).
   - Align block order, session placement, and footer rules with **`lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`** and **`lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`**.

3. **Canonical enforcement compilation:** Follow the procedural checklist and system-wide expectations in **[directives.md](../../directives.md)** § **LUPOPEDIA HEADERS baseline rewrite (4.0.84+)** in addition to the FORMAT docs.

## Version comparison note

For **4.0.PATCH** versions, compare **PATCH** numerically (e.g. `83` &lt; `84`). Do not rely on naive string sort for patch segments (`"4.0.9"` vs `"4.0.10"`). When in doubt (odd legacy strings), if **any** deprecated header version key is present, treat as below baseline and rewrite.

## Relationship to immutability

After this rewrite, **`version_when_written`** is the creation-time stamp **for that header generation**; routine edits at **4.0.84+** should not bump it unless doctrine explicitly requires a new baseline (see **LUPOPEDIA_HEADERS_FORMAT.md** §2 / §2.0).

## Source

- **Format / fields:** [LUPOPEDIA_HEADERS_FORMAT.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)
- **Index:** [LUPOPEDIA_HEADERS/README.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)
- **Directives:** [directives.md](../../directives.md)
