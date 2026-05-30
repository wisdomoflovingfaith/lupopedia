---
lupopedia.init:
  document_type: audit
  system_version: 4.0.71
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: docs/database/lupopedia/tables/semantic_navbar/SEMANTIC_NAVBAR_TABLE_AUDIT_REPORT.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/semantic_navbar
  questions_toon: null
  channel_id: 42
  actor_id: 1003
  artifact_type: audit
  artifact_kind: table_audit
  purpose: Audit of DB tables required for the semantic floating navigation bar (previous
    pages, references, contexts, edges, hashtags, folders, Q/A, next pages).
  tags:
  - semantic_navbar
  - audit
  - database
  - 4.0.71
  when_updated: '20260324174654'
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: Semantic Navbar Table Audit Report â€” web_path: http://www.lupopedia.com/database/lupopedia/tables/semantic_navbar

# Semantic Navbar Backend â€” Table Audit Report

**Date:** 2026-03-12  
**Version:** 4.0.71  
**Scope:** All tables required for the Lupopedia semantic floating navigation bar (previous pages, references, contexts/collections, edges, hashtags, folders, Q/A, next pages).

---

## 1. Previous Pages

| Table | Status | Location | Notes |
|-------|--------|----------|--------|
| lupo_paths | **EXISTS** | install_new_lupopedia.sql | Aggregated navigation flows; entercontentid, exitcontentid, transition_type. Populated by gc from lupo_visits. |
| lupo_paths_summary | Optional | â€” | Not present; optional. Navbar can use lupo_paths + lupo_visits. |
| lupo_page_stats | Optional | â€” | Not present; optional. |

---

## 2. References (citations / source links)

| Table | Status | Location | Notes |
|-------|--------|----------|--------|
| lupo_references | **ADDED** | install + migration 20260312 | New table: reference_id, source_entity_type, source_entity_id, url, title, created_ymdhis, etc. |
| lupo_reference_links | **ADDED** | install + migration 20260312 | New junction: reference_link_id, reference_id, object_type, object_id. |
| lupo_reference_map | If exists | â€” | Not added; lupo_reference_links serves as map. |
| lupo_contents.content_references | EXISTS | install | JSON column on lupo_contents; navbar may use lupo_references for normalized lookups. |

---

## 3. Contexts (Collections)

| Table | Status | Location | Notes |
|-------|--------|----------|--------|
| lupo_collections | **EXISTS** | install_new_lupopedia.sql | Contexts = collections; channel_id, is_nav_menu, nav_icon. |
| lupo_collection_tabs | **EXISTS** | install_new_lupopedia.sql | Tabs within collections. |
| lupo_collection_tab_map | **EXISTS** | install_new_lupopedia.sql | Items in tabs (item_type, item_id). |
| lupo_collection_tab_paths | **EXISTS** | install_new_lupopedia.sql | Paths per tab. |
| lupo_collection_links | If exists | â€” | Not separate; collection_tab_map + collection_tab_paths cover links. |
| lupo_collection_map | If exists | â€” | Not added; collection_tab_map used. |

---

## 4. Edges (semantic edges between pages)

| Table | Status | Location | Notes |
|-------|--------|----------|--------|
| lupo_edges | **EXISTS** | install_new_lupopedia.sql | left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, etc. |
| lupo_edge_type_definitions | **EXISTS** | install_new_lupopedia.sql | Registry of edge types (replaces legacy lupo_edge_types). |
| lupo_edge_map | If exists | â€” | Not added; lupo_edges is the edge store. |

---

## 5. Hashtags

| Table | Status | Location | Notes |
|-------|--------|----------|--------|
| lupo_hashtags | **ADDED** | install + migration 20260312 | New table: hashtag_id, tag_slug, label, use_count, created_ymdhis. |
| lupo_hashtag_map | **ADDED** | install + migration 20260312 | New junction: hashtag_map_id, hashtag_id, object_type, object_id. |
| lupo_hashtag_stats | Optional | â€” | Not added; optional. |
| lupo_contents.hashtags | EXISTS | install | JSON column; navbar may use lupo_hashtags for normalized tag list. |

---

## 6. Folders (folder-based grouping)

| Table | Status | Location | Notes |
|-------|--------|----------|--------|
| lupo_folders | **ADDED** | install + migration 20260312 | New table: folder_id, name, slug, parent_folder_id, actor_id, channel_id, created_ymdhis. |
| lupo_folder_map | **ADDED** | install + migration 20260312 | New junction: folder_map_id, folder_id, object_type, object_id. |

---

## 7. Questions / Answers (Q/A)

| Table | Status | Location | Notes |
|-------|--------|----------|--------|
| lupo_truth_knowledge | **EXISTS** | install_new_lupopedia.sql | Knowledge items; question_id, answer_id, truth_type, slug. Used for Q/A. |
| lupo_truth_answers | **EXISTS** | install_new_lupopedia.sql | Answers linked to questions (truth_question_id). |
| lupo_questions / lupo_answers | â€” | â€” | Not added; lupo_truth_knowledge + lupo_truth_answers serve as Q/A backend. |
| lupo_question_map | If exists | â€” | Not added. |

---

## 8. Next Pages

Uses **lupo_paths** and **lupo_edges** (same as previous pages + semantic graph).

---

## Summary

| Category | Existing | Added | Optional / Not added |
|----------|----------|--------|----------------------|
| Previous Pages | lupo_paths | â€” | paths_summary, page_stats |
| References | (contents JSON) | lupo_references, lupo_reference_links | reference_map |
| Contexts | lupo_collections, lupo_collection_tabs, lupo_collection_tab_map, lupo_collection_tab_paths | â€” | collection_links, collection_map |
| Edges | lupo_edges, lupo_edge_type_definitions | â€” | edge_map |
| Hashtags | (contents JSON) | lupo_hashtags, lupo_hashtag_map | hashtag_stats |
| Folders | â€” | lupo_folders, lupo_folder_map | â€” |
| Q/A | lupo_truth_knowledge, lupo_truth_answers | â€” | lupo_questions, lupo_answers, question_map |
| Next Pages | lupo_paths, lupo_edges | â€” | â€” |

**New tables created:** lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map.

**TOONs:** Regenerate from install or live DB after applying migration (`scripts/generate_toon_files.py` or equivalent).

**Doctrine:** No foreign keys, no triggers, no stored procedures. All timestamps BIGINT YYYYMMDDHHIISS UTC. Application enforces relations.

