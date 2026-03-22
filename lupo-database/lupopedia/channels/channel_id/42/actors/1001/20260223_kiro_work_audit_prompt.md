# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/actors/1001/20260223_kiro_work_audit_prompt.md"
  file_hash: "872e5254c4fd53281a571b1743b858a8e80af7c45f9a13c0b8f474ed1d3fec66"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:  
  file_path_from_root: "lupo-prompts/windsurf/20260223_kiro_work_audit_prompt.md"
  file_hash: "1e06badd5f35f1ef329e88fd9ce56d5887e39f1ce5fa00c4c57db604c47ea5d0"
  system_version: "4.0.50"
  channel_id: 42
  mood_rgb: "0044FF"
  purpose: "Directive for Windsurf to audit KIRO's recent work and enhance semantic metadata across Lupopedia"
  last_modified_utc: "2026-02-23T17:20:00Z"
  x_lupo_forwarded: "external_ai_chatgpt:10000"
  lupo_agent: "external_ai|chatgpt"

flip.footer:
  referenced_by_files:
    - "lupo-docs/audits/prompt_message_audit_20260223.md"
    - "CHANGELOG.md"
    - "lupo-docs/doctrine/FLIP_FOOTER_DOCTRINE.md"
    - "lupo-docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md"
    - "lupo-docs/AGENT_INVENTORY.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1000
    - 1001
    - 1002
    - 10000
  inbound_edges:
    - "audit_directive"
    - "semantic_enhancement"
    - "kiro_verification"
    - "windsurf_task"
  footnotes:
    - "Improved clarity, structure, and doctrine compliance from original prompt"
    - "Focuses on KIRO's recent metadata work (v4.0.33)"
    - "Enhances FLIP footers with semantic cross-references"
    - "Compliant with Lupopedia metadata doctrine"
---

# CHANNEL 42 DIRECTIVE — WINDSURF  
## AUDIT KIRO'S WORK & IMPROVE SEMANTIC METADATA (v4.0.33)

Issued By: ChatGPT External Interface  
Forwarded To: **Windsurf IDE (actor_id 1002)**  
Human Operator: **Captain Wolfie (actor_id 10000)**  
Location: **Sioux Falls, South Dakota, USA**  
UTC Timestamp: **2026‑02‑23T17:20:00Z**

---

# 1. PURPOSE OF THIS DIRECTIVE
Windsurf, your task is to perform a **full, independent audit** of all work completed by  
**KIRO IDE (actor_id 1001)** for version **4.0.33**.

You must verify:
- CHANGELOG updates  
- KIRO's Channel 42 messages  
- KIRO's prompt replies  
- All metadata changes KIRO applied  
- All FLIP HEADERS + FLIP FOOTERS  
- All semantic cross‑references  

Your goal is to ensure **metadata integrity**, **semantic consistency**, and **cross‑file alignment** across Lupopedia.

This is **metadata‑only work**.  
**No database writes.**

---

# 2. WHERE TO LOOK

### ✔ A. CHANGELOG.md
Verify:
- Entries for 4.0.30 → 4.0.33  
- Correct timestamps  
- Correct version ordering  
- Correct agent attribution  

### ✔ B. CHANNEL 42
Review:
- All KIRO messages  
- All status updates  
- All confirmations  
- All directives  

Check for:
- Metadata correctness  
- Version alignment  
- FLIP compliance  

### ✔ C. PROMPTS + DIRECTIVES
Scan:
- lupo-prompts/  
- lupo-docs/directives/  
- lupo-docs/archive/  
- lupo-channels/42/broadcasts/  

Look for:
- Missing headers  
- Missing footers  
- Incorrect x_lupo_forwarded  
- Incorrect lupo_agent  
- Missing referenced_by metadata  

---

# 3. WHAT TO VERIFY

### ✔ A. FLIP HEADERS
Ensure each header contains:
- file_path_from_root  
- system_version: "4.0.33"  
- channel_id  
- purpose  
- last_modified_utc  
- x_lupo_forwarded  
- lupo_agent  

### ✔ B. FLIP FOOTERS
Ensure each footer contains:
- referenced_by_files  
- referenced_by_channels  
- referenced_by_actors  
- inbound_edges  
- footnotes  
- version: "4.0.33"  
- last_verified_utc  
- last_verified_by  

### ✔ C. SEMANTIC ALIGNMENT
Check that:
- All referenced files exist  
- All referenced channels are valid  
- All referenced actors appear in AGENT_INVENTORY.md  
- No dangling references remain  
- No malformed YAML blocks exist  

---

# 4. IMPROVE SEMANTIC DATA (MANDATORY)

Where metadata is missing or weak, Windsurf must:

### ✔ Strengthen semantic relationships
Add missing:
- referenced_by_files  
- referenced_by_channels  
- referenced_by_actors  
- inbound_edges  

### ✔ Normalize versioning
Use:
system_version: "4.0.33"

### ✔ Normalize timestamps
Use:
last_modified_utc: "2026-02-23T17:20:00Z"
last_verified_utc: "2026-02-23T17:20:00Z"

### ✔ Normalize agent attribution
Ensure:
- KIRO = actor_id 1001  
- Windsurf = actor_id 1002  
- Captain Wolfie = actor_id 10000  
- External AIs use consistent identifiers  

---

# 5. PRODUCE AN AUDIT REPORT

Create:

`lupo-docs/status/windsurf_audit_kiro_work_4_0_33.md` 

Include:
- Files KIRO modified  
- Files KIRO missed  
- Metadata errors found  
- Metadata errors corrected  
- Semantic improvements applied  
- Any inconsistencies requiring follow‑up  

---

# 6. POST A CHANNEL 42 STATUS MESSAGE

After completing audit, post:

"Windsurf: Audit of KIRO's 4.0.33 work complete.  
Metadata verified and improved.  
Semantic alignment updated across dialog MD files.  
Audit report generated.  
UTC: 2026‑02‑23T17:20:00Z — Sioux Falls, SD."

---

# 7. SAFETY & SCOPE

- ❗ Do NOT write to the database  
- ✔ Metadata‑only operations  
- ✔ No schema changes  
- ✔ No migrations  
- ✔ No destructive edits  

## END OF DIRECTIVE
