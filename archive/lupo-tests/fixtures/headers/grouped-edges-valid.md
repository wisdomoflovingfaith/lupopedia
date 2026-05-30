---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  file_path_from_root: "lupo-tests/fixtures/headers/grouped-edges-valid.md"
  questions_toon: null
  system_version: "4.0.77"
  channel_id: 42
  artifact_type: "fixture"
  purpose: "Grouped outbound_edges with snapshot comment — should PASS"

lupopedia.edges:
  comment: "Static snapshot of edges."
  outbound_edges:
    - { to: "doc-a.md", type: "references", weight: 1.0 }
    - { to: "doc-b.md", type: "references", weight: 0.9 }
---
# file: Grouped Edges Valid — session: L-LUPO-ROOT — delegation: cursor:root

# Body

Grouped edges with snapshot comment. Should pass.
