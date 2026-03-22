# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/42/broadcasts/20260223_windsurf_sql_seed_alignment.md"
  file_hash: "dfbac8e17bef2fa95ec26b265068ded3ad1bc63ec436614f82c04ccce55e291b"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\42\broadcasts\20260223_windsurf_sql_seed_alignment.md"
  file_hash: "0939cc781c746c25d1f44afab1031fa35a42e98f361d7216d410d4871c7c8ca1"
  file_path_from_root: "lupo-docs\channels\42\broadcasts\20260223_windsurf_sql_seed_alignment.md"
  file_hash: "40072d881d3909fc8e7d46fa4b9865218065db345a971b9a0622793799e73bf1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260223_windsurf_sql_seed_alignment.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "42", "broadcasts", "20260223_windsurf_sql_seed_alignmentmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header)
wolfie.headers:
  file_path_from_root: "lupo-channels/42/broadcasts/20260223_windsurf_sql_seed_alignment.md"
  system_version: "4.0.33"
  channel_id: 42
  mood_rgb: "4B0082"
  purpose: "SQL seed alignment directive for Windsurf to sync database registry with MD registry"
  last_modified_utc: "20260223"
  x_lupo_forwarded: "10000:10000"
  actor_id: 10000
  lupo_agent: "captain_wolfie"

flip.footer:
  referenced_by_files:
    - "lupo-docs/AGENT_INVENTORY.md"
    - "lupo-docs/doctrine/AGENT_REGISTRY_DOCTRINE.md"
    - "lupo-database/install_new_lupopedia.sql"
    - "lupo-database/seed_lupopedia.sql"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 10000
    - 1002
    - 1001
    - 1003
  inbound_edges:
    - "sql_seed_alignment"
    - "registry_synchronization"
    - "dual_registry_support"
    - "windsurf_directive"
  footnotes:
    - "Aligns SQL seed files with canonical MD registry"
    - "Maintains both legacy and new registry tables until v4.0.34"
    - "Prepares for ANUBIS resolution of duplicate tables"
---

# CHANNEL 42 BROADCAST — WINDSURF SQL SEED ALIGNMENT DIRECTIVE

**From**: Captain Wolfie (Actor ID: 10000)  
**To**: Windsurf IDE (Actor ID: 1002)  
**Date**: 2026-02-23 11:56:00 UTC  
**Subject**: SQL Seed Alignment + Dual Registry Table Support (v4.0.33)  
**Priority**: HIGH

---

## 🎯 **WINDSURF DIRECTIVE — SQL SEED ALIGNMENT + DUAL REGISTRY TABLE SUPPORT (v4.0.33)**

Windsurf, this directive instructs you to update the SQL seed files so that the database's agent registry tables match the **canonical MD‑based registry** created by KIRO for version **4.0.33**.

This directive also formalizes the decision to temporarily maintain **both** registry tables:

- `lupo_unified_registry` (legacy)
- `lupo_registry` (new)

Agents are still writing to the old table, so both must be seeded identically until ANUBIS resolves the duplication in **version 4.0.34**.

---

# 1. SOURCE OF TRUTH (MANDATORY)

Use ONLY the following MD files as the authoritative registry:

- `lupo-docs/AGENT_INVENTORY.md` 
- `lupo-docs/doctrine/AGENT_REGISTRY_DOCTRINE.md` 
- KIRO's v4.0.33 registry/status files under `lupo-docs/status/` 

Extract:

- `actor_id` 
- `agent_key` (slug)
- `canonical_name` 
- `agent_type` (ide, external, human)
- `status` (active, dormant, banned)

These MD files override all SQL seed content.

---

# 2. TARGET SQL FILES TO UPDATE

Update the following:

- `lupo-database/install_new_lupopedia.sql` 
- `lupo-database/seed_lupopedia.sql` 
- `lupo-database/seed/lupo_registered.sql` (if present)
- Any SQL file that seeds:
  - `lupo_actors` 
  - `lupo_unified_registry` 
  - `lupo_registry`  ← **NEW**
  - `lupo_registered` 

**Both registry tables must receive identical seed rows.**

---

# 3. REQUIRED AGENT ENTRIES (EXAMPLES)

### IDE Agents
| Key | Actor ID | Type |
|-----|----------|------|
| kiro | 1001 | ide |
| windsurf | 1002 | ide |
| antigravity | 1003 | ide |

### Human Operator
| Key | Actor ID | Type |
|-----|----------|------|
| captain | 10000 | human |

### External Agents
(lilith, lexa, maat, thoth, ara, chatgpt, gemini, claude, etc.)

### Banned / Archive‑Only
| Key | Actor ID | Status |
|-----|----------|--------|
| grok_banned | 420 | banned |

These MUST appear in **both** registry tables.

---

# 4. REQUIRED ACTIONS FOR WIND SURF

### ✔ A. Parse MD registry  
Extract all canonical agent definitions.

### ✔ B. Compare with SQL seed files  
Identify:
- Missing agents  
- Incorrect actor_ids  
- Incorrect names  
- Incorrect types  
- Incorrect statuses  
- Outdated entries  

### ✔ C. Rewrite SQL seed inserts  
For **both** tables:

```sql
INSERT INTO lupo_unified_registry (...)
INSERT INTO lupo_registry (...)
```

Rows must be identical.

### ✔ D. Preserve legacy compatibility  
Do NOT remove `lupo_unified_registry`.  
Do NOT rename it.  
Do NOT drop it.

### ✔ E. Add ANUBIS note for v4.0.34  
Create a comment block in the SQL seed files:

```sql
-- TODO (ANUBIS, v4.0.34):
-- Resolve duplicate registry tables.
-- lupo_unified_registry (legacy) and lupo_registry (new)
-- must be merged or unified under a single canonical table.
```

### ✔ F. Validate consistency  
Ensure:
- No duplicate actor_ids  
- No missing actor_ids  
- No mismatched agent keys  
- Both tables contain the same rows  

---

# 5. OUTPUT REQUIRED

Create:

`lupo-docs/status/windsurf_sql_seed_alignment_report_4_0_33.md` 

Include:
- Agents added  
- Agents corrected  
- Agents removed  
- SQL diffs  
- Final SQL seed blocks for both tables  
- ANUBIS note for v4.0.34  
- Any anomalies requiring manual review  

---

# 6. POST A CHANNEL 42 STATUS MESSAGE

After completing the alignment, post:

"Windsurf: SQL seed files updated to match MD registry for v4.0.33.
Both lupo_unified_registry and lupo_registry now seeded identically.
ANUBIS note added for v4.0.34 to resolve duplicate tables.
Alignment report generated.
UTC Date: 20260223 — Sioux Falls, SD."

---

# 7. SAFETY & SCOPE

- ❗ Metadata‑driven SQL seed updates only  
- ❗ Do NOT write to the live database  
- ❗ Do NOT modify schema  
- ✔ Only update seed files  
- ✔ All changes reversible  

---

**END OF DIRECTIVE** 🚀
