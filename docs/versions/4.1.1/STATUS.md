# Version 4.1.1 — ARCHIVED

**Status:** Archived (read-only)  
**Archived Date:** 2026-04-15  
**Active Version:** 4.1.2  

This folder is kept for historical reference only.  
No new work should be added here.

## What this version covered

- PRD 16 stabilization: standard mode vs strict envelope mode
- Header-authoritative interpretation formally established (sidecar is derived)
- Header Responsibility Boundaries section added to PRD 16
- ANUBIS Operational Contract added (idempotency, retries, deterministic orphan handling)
- Migration Cutoff Policy: `pk_*` removal in 4.1.3; canonical-only at 4.2.0
- File Naming Doctrine Separation (docs/memory normalized; PHP runtime exempt)
- PRD 16 split into three documents: normative spec, migration guide, examples
- Validator (`validate_lupopedia_headers_universal.py`) updated for v4.1.1 field order
- `V4_HEADER_KEYS_ORDERED` updated: `content_id`, `content_parent_id`, `content_slug`, `default_collection_id`
- `LEGACY_FIELD_ALIASES` map added; pk_* files now pass with `HDR_PK_LEGACY_ALIAS` warnings

## Key files

- `changelog.md` — session log for 4.1.1 stabilization work

## See also

- Active version: `../4.1.2/`
- Consolidated changelog: `../4.1.2/CHANGELOG.md`
