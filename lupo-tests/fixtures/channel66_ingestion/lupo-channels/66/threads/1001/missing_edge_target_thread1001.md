---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-tests/fixtures/channel66_ingestion/lupo-channels/66/threads/1001/missing_edge_target_thread1001.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1001/missing_edge_target_thread1001"
  last_modified_utc: "20260319"
  system_version: "4.0.80"
  channel_id: 66
  thread_id: 1001
  actor_id: 14
  delegation_chain: "hephaestus:root"
  artifact_type: "thread"
  artifact_kind: "test"
  purpose: "This file must ingest even when edge targets are missing"

lupopedia.edges:
  comment: "static"
  outbound_edges:
    - { to: "lupo-channels/66/threads/1001/DOES_NOT_EXIST.md", type: "references", weight: 1.0, reason: "fixture missing target", edge_category: "documentation" }

---
# file: Missing Edge Target Fixture — session: L-LUPO-ROOT-HEPHAESTUS — delegation: hephaestus:root — web_path: http://www.lupopedia.com/lupo-channels/66/threads/1001/missing_edge_target_thread1001

