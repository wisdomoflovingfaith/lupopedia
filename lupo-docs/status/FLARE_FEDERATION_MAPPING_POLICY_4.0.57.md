# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/status/FLARE_FEDERATION_MAPPING_POLICY_4.0.57
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "report"
  file_path_from_root: "docs/status/FLARE_FEDERATION_MAPPING_POLICY_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/FLARE_FEDERATION_MAPPING_POLICY_4.0.57"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "report"
  artifact_kind: "policy"
  purpose: "v4.0.57 Federation Mapping Policy for Node 0 Complete vs. Partial for >0"
  mood_rgb: "4169E1"
  traits: ["report", "v4.0.57", "flare", "federation", "mapping"]
  tags: ["4.0.57", "flare", "federation", "mapping", "cursor"]
  lupo_agent: "cursor"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/status/FLARE_FEDERATION_REFINEMENT_4.0.57.md", type: "references", weight: 0.9 }
    - { to: "lupo-tools/flare_see.py", type: "references", weight: 0.8 }
lupopedia.see:
  mappings:
    - ["docs/status/FLARE_FEDERATION_MAPPING_POLICY_4.0.57.md", "http://www.lupopedia.com/status/FLARE_FEDERATION_MAPPING_POLICY_4.0.57"]
lupopedia.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## 1. Summary

Federation mapping policy is documented in **FLARE_DOCTRINE Section 23 (v4.0.57+)**: for **federation_node_id 0** (base `http://www.lupopedia.com`), every `.md` file in the repo **SHOULD** have a **lupopedia.see** (or equivalent) mapping so that full web resolution is possible; for **node_id > 0**, mappings are **partial/as-needed** (key artifacts only), to avoid overhead in multi-node setups and align with the Safety Rule.

---

## 2. Research: Mapping Requirements

### 2.1 FLARE_DOCTRINE and related

- **Section 12 (web_path):** `web_path` and the header comment see URL derive from `file_path_from_root`; base URL from federation_node_id (Section 22). Full URL enables external linking and lupopedia.see.
- **Section 17 (lupopedia.see):** URL-to-path mappings in a `lupopedia.see` block; `lupo-tools/flare_see.py` scans `.md` files for these blocks and builds `artifacts/index/flame_see_index.json`. CLI `lupo see <URL>` resolves URL → path. Index schema: mappings with path, url, file_hash, etc.; stats (total_mappings, unique_urls, unique_paths).
- **Section 22 (Federation Node Integration):** Node 0 = repo root, base URL www.lupopedia.com; node > 0 = optional path prefix (lupo-database/files/<id>/), base URL from node_base_url. lupopedia.see index may store per-node or node-prefixed entries for multi-node.

### 2.2 How mappings are built

- **flare_see.py:** Uses an MD file list (from `tools/flare_md_index.txt` or `lupo-tools/flare_md_index.txt`, else recursive walk). For each `.md`, parses YAML frontmatter, looks for `lupopedia.see.mappings` (list of `[path, url]`). Each mapping is normalized (URL lowercased, trailing slash stripped) and added to the index. No node_id in current index schema; single index file. So today the index is effectively **node 0** (all repo paths).
- **Node impact:** For multi-node, index could be extended with `node_id` per entry or separate index per node; Section 23 defers that. Policy: node 0 = aim for complete coverage (every MD has lupopedia.see); node > 0 = partial.

---

## 3. Doctrine Updates (Section 23)

### 3.1 Before

Section 22 described domain/path by node and alignment with lupopedia.see but did not state a **policy** for how many or which files must have lupopedia.see per node.

### 3.2 After (excerpt)

**Section 23. Federation Mapping Policies (v4.0.57+)**

- **Node 0:** **Complete mappings.** Every `.md` in node 0 scope SHOULD have a lupopedia.see block so the index gives full web resolution. Rationale: single source of truth, CLI can resolve every doc, Safety Rule alignment.
- **Node > 0:** **Partial/as-needed.** Only key artifacts (doctrine, status, channel-critical) need lupopedia.see. Rationale: scalability, avoid legacy overhead.
- **Summary table:** Node 0 | Base URL www.lupopedia.com | Complete | example path→url. Node >0 | node_base_url | Partial | key docs only.
- **Tooling (future):** Node 0 completeness check (compare .md count vs index entries); node > 0 optional node-scoped or selective indexing. Deferred in v4.0.57.

---

## 4. Policy Rationale

- **Node 0 complete:** Ensures the primary Lupopedia instance has full URL resolvability for all docs; supports automation, linking, and ANUBIS/CLI workflows. Incremental adoption (add lupopedia.see to files that lack it).
- **Node > 0 partial:** Keeps federated nodes lightweight; avoids requiring every MD on every node to have lupopedia.see; aligns with Safety Rule (mandatory flame only for certain artifact_kind); key artifacts remain discoverable.

---

## 5. Examples Table

| federation_node_id | Base URL (example) | Mapping policy | Example path/mapping |
|--------------------|--------------------|----------------|----------------------|
| **0** | `http://www.lupopedia.com` | Complete — every repo `.md` | `docs/status/REPORT.md` → `lupopedia.see: mappings: [["docs/status/REPORT.md", "http://www.lupopedia.com/status/REPORT"]]` |
| **1** | From `lupo_federation_nodes.node_base_url` | Partial — key artifacts only | Doctrine, status, channel-critical; other `.md` may omit lupopedia.see |

---

## 6. Tooling Considerations

- **flare_see.py:** No change in v4.0.57. It already indexes every `.md` that contains a lupopedia.see block. A future run could add a **completeness report** for node 0 (list of .md files without any lupopedia.see entry). Documented in Section 23 as future.
- **Node > 0:** If/when multi-node indexing is implemented, index could be built only for paths under a node path or include node_id; Section 23 defers.

---

## 7. Validation

- **flare_validate.py:** Run on this report and on `FLARE_DOCTRINE.md` (after adding Section 23); exit code **0**. Canonical order and structure preserved.
- **lupopedia.see indexing:** Running `python lupo-tools/flare_see.py` (or equivalent) builds/updates `artifacts/index/flame_see_index.json`. For node 0 completeness, compare total `.md` count (e.g. from directory tree or flare_md_index) to number of unique paths in the index; gaps indicate MDs without lupopedia.see. Not automated in v4.0.57; manual or future script.

---

## 8. Delegation

- **Lilith (actor 2):** Requested for meta-review of this policy report and of doctrine Section 23 (lupopedia.see alignment, canonical order, Safety Rule).

---

## 9. Open / Questions

- **Node > 0 “key artifacts”:** Criteria are doctrine, status, channel-critical; specific list per channel could be refined with Captain or channel owners.
- **Completeness automation:** Optional script or flag in flare_see.py to report node 0 gaps (MDs without lupopedia.see). Deferred.

---

## 10. Timestamp and Actor

- **Report generated:** 2026-03-06  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **lupo_agent:** cursor  

---

*End of report.*
