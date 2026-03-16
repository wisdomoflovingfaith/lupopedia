# file: Wrong Order — identity line on line 1 (INVALID)
---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  file_path_from_root: "lupo-tests/fixtures/headers/wrong-block-order.md"
  last_modified_utc: "20260316"
  system_version: "4.0.77"
  channel_id: 42
  artifact_type: "fixture"
  purpose: "Identity line on line 1 — should FAIL"
---
# Body

First line must be ---, not identity line. Validator should fail.
