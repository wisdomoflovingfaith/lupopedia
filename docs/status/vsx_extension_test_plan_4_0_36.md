# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\status\vsx_extension_test_plan_4_0_36.md"
  file_hash: "c61eebca86eacffd5a3daa927243163632bf74f70986daff46ec1308a6efaebd"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for vsx_extension_test_plan_4_0_36.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "vsx_extension_test_plan_4_0_36md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers:
  file_path_from_root: "docs/status/vsx_extension_test_plan_4_0_36.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "0044FF"
  purpose: "Full test plan for VSX Extension in version 4.0.36"
  last_modified: "20260223"
  actor_id: 10000
  lupo_agent: "human|captain"
---

# VSX EXTENSION TEST PLAN — VERSION 4.0.36

## 1. TEST MODES

### ✔ MD-ONLY MODE
- Disable DB connection
- Confirm:
  - Registry loads from MD
  - Channels load from MD
  - FLIP headers/footers parsed
  - Agent activity detected from MD
  - Status API returns `md_only`

### ✔ HYBRID MODE
- DB online but MD fallback available
- Confirm:
  - Registry loads from DB
  - MD fallback still functional
  - Status API returns `hybrid`

### ✔ DB-ONLINE MODE
- Full DB access
- Confirm:
  - Registry loads from DB
  - Channels load from DB
  - Status API returns `db_online`

---

## 2. KIRO INTEGRATION TESTS

### ✔ Status Query
KIRO must correctly read: `vsx_extension_status.md`

### ✔ Mode Detection
KIRO must detect:
- md_only  
- hybrid  
- db_online  

### ✔ Reporting
KIRO must generate: `docs/status/kiro_vsx_status_query_4_0_36.md`

---

## 3. PUBLISHER IDENTITY TESTS

### ✔ Eclipse Foundation Identity
- publisher: "lupopedia"
- publisher_verified: true

### ✔ package.json
- Correct metadata  
- Correct version markers  
- Correct display name  

---

## 4. EXTENSION FUNCTIONALITY TESTS

### ✔ FLIP Parsing
- Multi-block YAML (headers/footers)
- Lists and arrays
- Graph edges

### ✔ Registry Loading
- MD inventory (`docs/AGENT_INVENTORY.md`)
- DB registry
- Hybrid fallback

### ✔ Channel Discovery
- `channels/42`
- `docs/channels/42`
- `messages/`
- `docs/status`

---

## 5. REPORTING

Antigravity must generate: `docs/status/vsx_extension_test_report_4_0_36.md`  
Windsurf must verify it.
