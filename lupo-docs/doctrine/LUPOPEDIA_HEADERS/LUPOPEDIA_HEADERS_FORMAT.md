# file: LUPOPEDIA HEADERS Format — session: L-LUPO-PLAN — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT
---
flare.headers:
  system_version: "4.0.68"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT"
  title: "LUPOPEDIA HEADERS Format"
  session_name: "L-LUPO-PLAN"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
---
# LUPOPEDIA HEADERS — File format and version rule

**Version:** 4.0.68+  
**Canonical name:** LUPOPEDIA HEADERS (FLARE = historical logical structure)

---

## 1. Markdown file structure

The **first visible line** of the file MUST be the audit/identity line:

```text
# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}
```

Then a single line:

```text
---
```

Then the YAML header blocks in **canonical order** (see [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) §4):

- flame.init (optional)
- flare.conditional (optional)
- flare.headers
- flare.edges (optional)
- flare.footer (optional)
- flame.see (optional)
- flame.close (optional)

Then the closing delimiter:

```text
---
```

Then the document body.

**Important:** The identity line is first. Do not start the file with `---` and put the identity line after the YAML; that would contradict the required format.

---

## 2. Required header fields (in flare.headers)

Stored as metadata properties (or in YAML when written to file). Minimum: `flare.version`, `flare.schema`, `file_path_from_root`, `web_path`, `last_modified_utc`, `system_version`, `channel_id`, `actor_id`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`. Optional: `actor_name`, `session_name`, `mood_rgb`, `traits`, `tags`, `lupo_agent`, `agent_name_identity`.

---

## 3. Version rule (4.0.68)

- **New or modified** metadata-bearing Markdown from 4.0.68 onward MUST use LUPOPEDIA HEADERS rules and this format.
- **Existing FLARE-headed** files remain valid until migrated; validators MUST accept both during transition.
- Canonical storage is `lupo_metadata`; migration is incremental.

---

## 4. Database and channel resolution

Headers can be attached by:

- `entity_type` + `entity_id` (file- or object-scoped)
- `channel_id` (channel-scoped)
- Both when appropriate

Resolution and validators MUST support channel-aware lookup.
