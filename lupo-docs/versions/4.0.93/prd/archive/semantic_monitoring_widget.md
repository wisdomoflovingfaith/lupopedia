---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: /lupo-docs/versions/4.0.93/prd/semantic_monitoring_widget.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/prd/semantic_monitoring_widget.md
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "semantic-monitoring-duplicate"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "legacy_duplicate"
  purpose: "Legacy duplicate PRD - use 01_semantic_monitoring_widget.md instead"
  tags:
  - "prd"
  - "legacy"
  - "duplicate"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/prd/01_semantic_monitoring_widget.md"
      type: references
      weight: 1.0
      reason: Use this file instead
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
lupopedia.footer:
  last_verified: '20260330'
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent

lupopedia.headers:
  lupopedia.schema: prd
  file_path_from_root: lupo-docs/versions/4.0.93/prd/semantic_monitoring_widget.md
  last_modified_utc: '20260330'
  purpose: PRD for Semantic Monitoring Widget (The Eye) v4.0.93
  traits:
    - prd
    - semantic_monitoring
    - the_eye
    - v4.0.93
    - doctrine
    - constraints
    - architecture
---
# 📘 **PRD Update — Semantic Monitoring Widget (The Eye) — v4.0.93**

## **1. Installation Constraint (Critical Doctrine)**

### **Lupopedia MUST always be installed in a subdirectory of the host site.**

Examples:

- `https://example.com/lupopedia/`
- `https://mysite.org/knowledge/`
- `https://domain.com/support/lupopedia/`

This is a **hard requirement** because:

- Auto‑installers (Softaculous, Installatron, Fantastico) **do not allow** replacing or overwriting the web root.
- Lupopedia must coexist with an existing site.
- The Semantic Monitoring Widget (The Eye) must monitor **the site above it**, not the Lupopedia directory itself.

This rule must be explicitly documented in:

- PRDs  
- INSTALL.md  
- ORGANIZATION.md  
- The Eye documentation  
- The installer wizard text  

---

## **2. Monitoring Architecture**

### **The Eye monitors the parent site, not Lupopedia.**

The monitoring scripts:

- `lupopedia_js.php`
- `livehelp_js.php`

are PHP endpoints that generate JavaScript dynamically.

These scripts:

- are served from inside the Lupopedia directory  
- are embedded into pages **outside** the Lupopedia directory  
- track user navigation across the host site  
- collect referers, page paths, dwell time, and content interactions  
- send events back to Lupopedia via AJAX endpoints  

### **Therefore:**

> **The Eye must always assume Lupopedia is NOT in the web root.**

This affects:

- path resolution  
- cookie scope  
- JS include URLs  
- referer normalization  
- content ID extraction  
- cross‑directory access rules  

---

## **3. Functional Requirements**

### **3.1 JavaScript Injection**

The Eye must generate a `<script>` include that works regardless of the subdirectory name.

Example:

```html
<script src="/lupopedia/lupopedia_js.php"></script>
```

But the system must NOT assume the folder is literally named `lupopedia`.

The installer must detect and store:

```
LUPOPEDIA_SUBDIRECTORY = "/lupopedia/"
```

And all JS includes must use this value.

---

### **3.2 Content Interaction Bar**

`lupopedia_js.php` must generate a dynamic toolbar that supports:

- Likes  
- Shares  
- Comments  
- Content metadata  
- Semantic edges (optional future)  

This toolbar must appear on **host site pages**, not inside Lupopedia.

---

### **3.3 Visitor Tracking**

`livehelp_js.php` must:

- track page views  
- track referers  
- track dwell time  
- track navigation path  
- send events to `lupo_visitors`  
- support auto‑invite logic  
- support operator monitoring  

This is the Crafty Syntax parity requirement.

---

## **4. Technical Constraints**

### **4.1 No Assumption of Web Root**

All paths must be relative to:

```
/<subdirectory>/
```

NOT:

```
/
```

### **4.2 No Hardcoded Folder Names**

The system must never assume:

- `/lupopedia/`
- `/support/`
- `/helpdesk/`

The installer must detect the folder name and write it to:

```
lupopedia-config.php
```

### **4.3 No Cross‑Directory File Access**

The Eye must use:

- AJAX  
- JS includes  
- API endpoints  

NOT filesystem traversal.

---

## **5. Required Documentation Updates**

The following files must be updated to reflect the “subdirectory‑only” doctrine:

- `lupo-docs/ORGANIZATION.md`
- `lupo-docs/versions/4.0.93/PLAN.md`
- `lupo-docs/versions/4.0.93/prd/semantic_monitoring_widget.md`
- `lupo-docs/installer/INSTALL.md`
- Installer wizard text (`install.php`)
- `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`

Each must include:

> **“Lupopedia is always installed in a subdirectory of the host site.  
> The system must never assume installation at the web root.”**

---

## **6. Required IDE Awareness**

The IDE must understand:

- Lupopedia is always in a subdirectory  
- The Eye monitors the parent site  
- JS endpoints must be subdirectory‑aware  
- The installer must detect the folder name  
- JSON TOON files are read‑only  
- Schema changes belong in `install_new_lupopedia.sql`  
- The Eye depends on visitor tracking tables (`lupo_visitors`, `lupo_referers`, etc.)  

This must be added to:

```
lupo-docs/ide/IDE_BEHAVIOR_RULES.md
```

---

# ✔️ **This PRD update is now ready to paste into your repo.**
