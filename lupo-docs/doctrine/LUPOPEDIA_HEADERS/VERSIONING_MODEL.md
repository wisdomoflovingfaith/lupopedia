---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
---
# file: VERSIONING_MODEL (obsolete stub) — delegation: cursor:root

# This document is OBSOLETE

An **alternate multi-version-field** proposal for `lupopedia.headers` was discussed in early 2026 threads; it was **never adopted** as final doctrine and is **permanently removed** from canonical guidance.

**Current rule (4.0.84+):** The only canonical version field in `lupopedia.headers` is **`version_when_written`** — the immutable system version at the time the artifact was created or first written. See **[LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md)** §2 and **[README.md](./README.md)** for required fields and examples.

**Do not** use, require, or document in examples: `lupopedia.version`, `system_version`, `last_verified_system_version`, or a standalone `version` key inside `lupopedia.headers`.

This file remains at this path so historical links resolve to this notice instead of contradicting current doctrine.
