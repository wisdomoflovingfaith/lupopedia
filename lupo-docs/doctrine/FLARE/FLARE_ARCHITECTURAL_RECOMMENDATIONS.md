# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLARE\FLARE_ARCHITECTURAL_RECOMMENDATIONS.md"
  file_hash: "0536c1c4245f1f4006f7e8e9d493aed9ba2b6da35c6717e6a8bf46ad226c43b3"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  file_path_from_root: "docs/doctrine/FLARE/FLARE_ARCHITECTURAL_RECOMMENDATIONS.md"
  file_hash: "de993c4d610ab8dce53f1afed72dc09327812dbdbe4d7360c46224fabadb079b"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1007
delegation_chain: "1007:10000"
  created_ymdhis: 20260226210000
  updated_ymdhis: 20260227180000
  last_modified_utc: "20260227"
  artifact_type: "doctrine"
  purpose: "Recommendations for improving FLARE API and Engagement Schema"
  dialog_message: "Recommended next step: create actors/1007 profile and align any remaining docs/examples to the required FLARE prologue format."
  lupo_agent: "codex-ide"

lupopedia.edges:
  file_path_from_root: "docs\doctrine\FLARE\FLARE_ARCHITECTURAL_RECOMMENDATIONS.md"
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/toons/lupo_edges.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "api/flare-header.php", type: "implements", weight: 0.8 }
  semantic_tags: ["architecture", "recommendations", "flare", "api", "database"]

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# 🏛️ FLARE Architectural Recommendations

Following a comprehensive review of the FLARE protocol (Headers, Edges, Footer) against the current 4.1.0 database schema (TOONs), the following recommendations are proposed to enhance system integrity and semantic coherence.

## 1. Engagement Schema Normalization (lupopedia.footer)

Current `lupo_contents` table has redundant and mismatched engagement fields:
- `share_count` (int) vs `shares_total` (int).
- `likes_total` (int) but no `like_count`.
- `view_count` (int) is canonical.

### Recommendations:
1. **Rename Columns**: Rename `likes_total` to `like_count` and `shares_total` to `share_count` in `lupo_contents`.
2. **Remove Redundancy**: Drop the legacy `share_count` column once `shares_total` is renamed to `share_count`.
3. **Capture Comments**: Add `comment_count` (int) to `lupo_contents` as a denormalized cache.

## 2. Refactoring Social Engagement Tables

### lupo_user_comments (Legacy)
The current `lupo_user_comments` table uses `user_id` and is limited to `content_id`.

### Recommendations:
1. **Universal Commenting**: Refactor to `lupo_comments` with `target_table` and `target_id` to allow commenting on any Lupopedia object (Artifacts, Threads, Files).
2. **Actor Mapping**: Replace `user_id` with `actor_id` to support AI agent comments and unified identity.
3. **Threading**: Ensure `parent_comment_id` is indexed for performant tree resolution.

### lupo_actor_object_edges (Modern)
This table already supports `like`, `share`, and `bookmark` via `edge_type`.

### Recommendations:
1. **Source of Truth**: Formalize this table as the source of truth for all binary engagement.
2. **Cache Synchronization**: Implement application-level consistency checks (ANUBIS) to ensure `lupo_contents.like_count` matches the sum of `edge_type = 'like'` in this table.

## 3. API Enhancements (api/flare-header.php)

The API currently performs hardcoded logic for several fields.

### Recommendations:
1. **Dynamic Like Resolution**: Replace hardcoded `like_count: 0` with a query to `lupo_contents.likes_total` or a COUNT from `lupo_actor_object_edges`.
2. **Comment Resolution**: Include `comment_count` in the JSON response by querying `lupo_user_comments`.
3. **Relational Inference**: Use the `flare_weight` and `flare_reason` columns in `lupo_edges` to populate the `outbound_edges` more accurately.

## 4. Documentation Improvements

### Contradiction Cleanup
Several doc files (including `FLARE_DOCTRINE.md`) contained typos placing `outbound_edges` in the `lupopedia.footer`.

### Recommendations:
1. **Strict 3-Part Enforcement**: Ensure all auto-generated documentation follows the split:
    - **lupopedia.headers**: Identity & Metadata
    - **lupopedia.edges**: Relational & Graph structure
    - **lupopedia.footer**: Engagement & Verification
2. **Validation Rules**: Update `FlareValidatorService` to explicitly check for cross-pollution between sections (e.g. no edges in footer).

## 5. Summary of Proposed Table Changes

| Table | Action | Reason |
| :--- | :--- | :--- |
| `lupo_contents` | Rename `likes_total` -> `like_count` | FLARE naming alignment |
| `lupo_contents` | Rename `shares_total` -> `share_count` | Consolidate redundant fields |
| `lupo_contents` | Add `comment_count` | Unified engagement snapshots |
| `lupo_user_comments`| Refactor to `lupo_comments` | Universal addressing & Actor support |

---
*Authored by GEMINI (Actor 1006)*