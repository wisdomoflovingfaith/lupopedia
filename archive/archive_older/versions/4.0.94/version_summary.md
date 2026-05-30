---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260406043326"
  file_path_from_root: "docs/versions/4.0.94/VERSION_SUMMARY.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/VERSION_SUMMARY.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: version_summary
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: docs/versions/4.0.94/VERSION_SUMMARY.md — delegation: cursor:root

# Version 4.0.94 summary

## Completed work (scoped refactor)

### Constitutional rules

- PK Naming Rule (Rule 93.PK_NAMING)
- Absolute-Root Pathing (Rule 93.PATH_PURITY)
- PHP tiered compatibility (Option 4) + honest Y2038 / 32-bit note

### Code (high level)

- **`App\Auth\Session`** — `createEmbedSession`, `getDecodedMetadata`, `mergeSessionMetadata`
- **`AuthSessionManager`** — deprecated; delegate to Session
- **`AuthService`** — transient flags in `lupo_sessions.metadata`
- **`login.php`**, **`select_agent.php`**, **`admin.php`** — headers, `$UNTRUSTED`, metadata
- **`install.php`** / **`install_wizard_classes.php`** — portable table checks, `$UNTRUSTED`
- **`UrlResolver`** — path anchoring, `expires_ymdhis`
- **`ToonSchemaCache`** — deprecated toward JSON schema under `database/lupopedia/json/`

### Documentation

- PRD 16, PRD 26 — approved; PRD 30/31 outcomes as recorded in version docs
- **LUPO_LAYERS_DOCTRINE.md** active; **DYNAPI_DOCTRINE.md** deprecated
- **AGENTS.md**, root **README** — Y2038 / constitutional pointers as landed

### Validators

- **`validate_implementation.py`** — conditional fields
- **`validate_lupopedia_headers_universal.py`** — `author` block support, `web_path` `/lupopedia/` check

### Integration

- **Channel 66** extended integration test + **Channel66ProductionIngester** `discoverChannelFiles` fix

## Deferred to 4.0.95

See **`docs/versions/4.0.95/TODO.md`**.

## Packaging status

**Ready for Softaculous packaging test** — execute **Phase 7** in **`PLAN.md`** (tarball, Linux smoke, regression suite).

This output complies with Lupopedia Constitutional Root Rules.
