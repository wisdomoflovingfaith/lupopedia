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
