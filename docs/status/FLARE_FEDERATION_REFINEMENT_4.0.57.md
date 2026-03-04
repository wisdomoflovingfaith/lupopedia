# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/FLARE_FEDERATION_REFINEMENT_4.0.57
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "report"
  file_path_from_root: "docs/status/FLARE_FEDERATION_REFINEMENT_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/FLARE_FEDERATION_REFINEMENT_4.0.57"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "report"
  artifact_kind: "refinement"
  purpose: "v4.0.57 FLARE See URL and File Path Refinement for Federation Nodes"
  mood_rgb: "4169E1"
  traits: ["report", "v4.0.57", "flare", "federation"]
  tags: ["4.0.57", "flare", "federation", "cursor"]
  lupo_agent: "cursor"
flare.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.9 }
    - { to: "lupo-tools/flare_apply.py", type: "references", weight: 0.9 }
flame.see:
  mappings:
    - ["docs/status/FLARE_FEDERATION_REFINEMENT_4.0.57.md", "http://www.lupopedia.com/status/FLARE_FEDERATION_REFINEMENT_4.0.57"]
flare.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## 1. Summary

The FLARE header **see URL** (and `web_path`) is refined so the **domain** derives from **federation_node_id**: node 0 uses `http://www.lupopedia.com`; other nodes use that node’s **node_base_url** from **lupo_federation_nodes**. File path handling is documented for node 0 (project root) vs optional **lupo-database/files/&lt;node_id&gt;/** for other nodes.

---

## 2. Research: Federation Integration

### 2.1 federation_node_id and node_base_url

- **lupo_federation_nodes** (see `lupo-docs/database/lupopedia/tables/lupo_federation_nodes.md`): Holds **node_base_url** (varchar 500) per **federation_node_id**. Used for sync, trust, and node identity.
- **lupo_channels**, **lupo_contents**, **lupo_sessions**, **lupo_registry**: All reference **federation_node_id** to scope data to a node. Seeds use **federation_node_id 0 or 1** for local/system; “0 for lupopedia.com” appears in session/docs.
- **REQUIRED_TABLES / FLARE_DOCTRINE**: No prior link between federation_node_id and FLARE see URL; Section 12 and 17 described a fixed `http://www.lupopedia.com` domain.

### 2.2 How node_id affects paths and URLs

- **Domain:** For a given artifact, the see URL’s host should be the domain of the node that “owns” the file. Node 0 = primary = **www.lupopedia.com**. Other nodes = **lupo_federation_nodes.node_base_url** (e.g. `https://node1.example.com`).
- **File paths:** Current work (node 0) uses **project root**; `file_path_from_root` is relative to repo root. For other nodes, doctrine now allows **lupo-database/files/&lt;federation_node_id&gt;/** (or config-driven prefix) so node-specific content does not overwrite node 0.

### 2.3 flame.see and CLI

- **Section 17:** `flame.see` index (`artifacts/index/flame_see_index.json`) and **lupo see &lt;URL&gt;** resolve URL → path. When multiple nodes contribute, the index may store **node_id** or node-prefixed paths for collision-free resolution; CLI behaviour unchanged for single-node (node 0).

---

## 3. Header Comment Format (Before / After)

| Aspect | Before | After |
|--------|--------|--------|
| Domain | Fixed `http://www.lupopedia.com` | **Derived from federation_node_id:** node 0 → `http://www.lupopedia.com`; other nodes → `node_base_url` (config/env/DB). |
| Comment line | `— see http://www.lupopedia.com/<web_path>` | `— see <base_url>/<relative_path>` with `<base_url>` from node. |
| web_path in flare.headers | `http://www.lupopedia.com/<path>` | `<base_url>/<relative_path>`; base_url from node (Section 22). |

Existing v4.0.57 docs (e.g. `docs/status/IS_DELETED_AUDIT_4.0.57.md`) already use the path-based see URL; they are node 0, so domain remains **www.lupopedia.com**. No change to existing file content; only doctrine and tooling are extended.

---

## 4. File Path Handling

- **Node 0:** No change. Paths relative to repo root; `flare_apply.py` and other tools operate as today.
- **Other nodes:** Documented in FLARE_DOCTRINE Section 22: optional prefix **lupo-database/files/&lt;federation_node_id&gt;/** or config. **flare_apply.py** does not yet accept `--node-id` or path prefix; **generate_toon_files.py** unchanged. Reserved for future implementation when multi-node file layout is adopted.

---

## 5. Doctrine and Tooling Updates

| File | Change |
|------|--------|
| **lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md** | **Section 12 (web_path):** Format now states base_url derives from federation_node_id (Section 22). **Section 22 (new):** Federation Node Integration — domain resolution (node 0 vs node_base_url), file path (root vs lupo-database/files/&lt;id&gt;/), examples table, alignment with flame.see. |
| **lupo-tools/flare_apply.py** | **base_url_for_node():** New helper; returns `LUPO_NODE_BASE_URL` if set, else `http://www.lupopedia.com`. **build_header():** Uses `base_url_for_node()` for the see URL so env overrides domain for non-zero nodes. |

### 5.1 base_url_for_node() implementation (snippet)

From `lupo-tools/flare_apply.py` (v4.0.57+):

```python
def base_url_for_node(federation_node_id=None):
    """
    Resolve base URL for FLARE see URL from federation_node_id (v4.0.57+).
    Node 0 (default) => http://www.lupopedia.com unless LUPO_NODE_BASE_URL is set.
    Other nodes => set LUPO_NODE_BASE_URL to that node's domain (from lupo_federation_nodes.node_base_url);
    LUPO_FEDERATION_NODE_ID can be set for future path-prefix use (e.g. lupo-database/files/<id>/).
    """
    default = "http://www.lupopedia.com"
    custom = os.environ.get("LUPO_NODE_BASE_URL", "").strip()
    return custom if custom else default
```

Usage in `build_header()`:

```python
# Generate canonical URL for flame.see and header comment (node-aware, v4.0.57+)
base_url = base_url_for_node()
web_path = web_path_for_comment(path)
# ...
header = (
    f"# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see {base_url}/{web_path}\n\n"
    # ...
)
```

---

## 5.2 Federation example table (node 0 vs node 1)

| federation_node_id | Base URL | File path (example) | Env / config | Example see URL |
|--------------------|----------|----------------------|--------------|------------------|
| **0** | `http://www.lupopedia.com` | `docs/status/REPORT.md` (repo root) | (none) or `LUPO_NODE_BASE_URL` override | `http://www.lupopedia.com/status/REPORT` |
| **1** | From `lupo_federation_nodes.node_base_url` (e.g. `https://node1.example.com`) | Optional: `lupo-database/files/1/status/REPORT.md` | `LUPO_NODE_BASE_URL=https://node1.example.com` (and optionally `LUPO_FEDERATION_NODE_ID=1`) | `https://node1.example.com/status/REPORT` |

See URL format per Section 22: `— see <base_url>/<relative_path>`; no trailing slash; extension omitted in path. This report’s own header comment (`— see http://www.lupopedia.com/status/FLARE_FEDERATION_REFINEMENT_4.0.57`) conforms: node 0 base URL + relative path.

---

## 6. Validation

- **flare_validate.py:** Run on this report and on `FLARE_DOCTRINE.md`; exit code **0**. No new structural errors; canonical order preserved.
- **lupo see &lt;URL&gt;:** Dynamic URL resolution unchanged; index still maps URL → path. Node-specific indexing (e.g. node_id in index) deferred to future work.

### 6.1 Capture command and sample output

To persist validation output for audit (Lilith/Grok review):

```bash
python lupo-tools/flare_validate.py docs/status/FLARE_FEDERATION_REFINEMENT_4.0.57.md 2>&1 | tee docs/status/flare_validate_federation_4.0.57.txt
```

**Observed (2026-03-06):** Exit code **0**. Validator scans the repo; errors reported (if any) are in other paths (e.g. `.kiro/specs/`); this report and FLARE_DOCTRINE.md introduce no new errors. Canonical block order and header structure preserved. Full output captured in **docs/status/flare_validate_federation_4.0.57.txt** (Lilith final verification 2026-03-06).

---

## 7. Delegation

- **Lilith (actor 2):** Requested for meta-review of this refinement and of flame-aligned Safety Rule / canonical order for federation-aware headers. Lilith’s meta-review of Grok’s review (2026-03-06) synthesized immediate actions: add code snippets, validation capture, federation example table, and see-URL consistency — implemented in this update.

---

## 8. Open Points / Questions

- **Node config for CLI:** For non-zero nodes, tooling currently uses **LUPO_NODE_BASE_URL** only. A small config file or DB lookup from **lupo_federation_nodes** could be added later for multi-node runs.
- **Path prefix in flare_apply:** Optional `--node-id` and path prefix (e.g. `lupo-database/files/<id>/`) not implemented; documented in Section 22 for future use.

---

## 9. Timestamp and Actor

- **Report generated:** 2026-03-06  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **lupo_agent:** cursor  

---

*End of report.*
