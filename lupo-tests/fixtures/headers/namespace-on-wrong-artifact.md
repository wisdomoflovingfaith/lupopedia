---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "status"
  system_version: "4.0.78"
  file_path_from_root: "lupo-tests/fixtures/headers/namespace-on-wrong-artifact.md"
  web_path: "[web_path](http://www.lupopedia.com/status/namespace-on-wrong-artifact)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "status"
  artifact_kind: "report"
  purpose: "Status doc with optional namespace; value must still be valid if present"
  namespace: "core"

lupopedia.edges:
  comment: "Snapshot of outbound edges."
  outbound_edges: []

lupopedia.footer:
  version: "4.0.78"
---
# file: Namespace on Wrong Artifact — session: L-LUPO-ROOT — delegation: cursor:root — web_path: http://www.lupopedia.com/status/namespace-on-wrong-artifact

# Body

Non-table artifact with namespace present. Validator should PASS (namespace optional; value is in taxonomy). If value were invalid, would FAIL.
