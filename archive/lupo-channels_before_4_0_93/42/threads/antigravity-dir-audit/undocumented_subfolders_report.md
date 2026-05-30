---
lupopedia.headers:
  file_path_from_root: "lupo-channels/42/threads/antigravity-dir-audit/undocumented_subfolders_report.md"
  lupopedia.schema: "report"
  artifact_type: "report"
  purpose: "Listing undocumented subfolders across the project that may contain old or legacy documentation"
---

# Undocumented Subfolders Report (Documentation Domains)

**Channel:** 42  
**Thread:** antigravity-dir-audit  
**Date:** 2026-04-01  

You correctly pointed out that the `project_structure_prd.md` ONLY explicitly documents the Sub-folders located inside `lupo-docs/` (e.g., `lupo-docs/versions/`, `lupo-docs/prd/`). 

This means that **every single subfolder in the rest of the repository is technically undocumented by the PRD**. However, since your goal is to find "what other folders we need to look at for old docs", compiling a list of the 300+ Javascript, CSS, and application logic subfolders (like `lupo-ui/js/`) would be useless. 

I have manually scoured the root-level directories known to house text files, research, coordination, and rules. Here are the **subdirectories that exist on disk, are undocumented in the PRD, and are highly likely to contain old or active documentation:**

## 1. `lupo-rules/` (Doctrines and Enforcement)
This directory houses a massive amount of Markdown rules, but its internal structure is completely missing from the PRD.
* **`lupo-rules/root/`** - Contains the dense network of canonical `.md` constitutional rules (e.g., `WOLFIE_DOCTRINE.md`).
* **`lupo-rules/cursor/`** - Likely contains old IDE agent specific directives that might need archival now that `lupo-prompts` is decommissioned.
* **`lupo-rules/skills/`** - Undocumented subfolder structure for skill usage logic.

## 2. `lupo-channels/` (Legacy File-System Coordination)
While the PRD mentions `lupo-channels/` at the root, it fails to outline the sprawling subfolder network inside of it. Furthermore, since channels formally moved to the database (`lupo_dialog_messages`), many of these folders may be harboring entirely dead status reports:
* **`lupo-channels/edge_generation_governance/`** - Potential legacy `.md` planning artifacts.
* **`lupo-channels/semantic-edges/`** - Potential legacy `.md` planning artifacts.
* **`lupo-channels/table-structure-optimization/`** - Potential legacy `.md` schemas.
* **`lupo-channels/1_channel_refactor_governance/`** - Obsolete architectural planning.
* **Thread Directories (`lupo-channels/42/`, `lupo-channels/0/`, etc.)** - Holds active and legacy cross-agent coordination files and broadcast directories.

## 3. `lupo-research/` (Knowledge & Experiments)
The master PRD does not document the internal structure of research projects, which often turn into abandoned markdown:
* **`lupo-research/bmad_method/`**
* **`lupo-research/doom_emacs/`**

## 4. `lupo-archive/` (The Graveyard)
The PRD states `lupo-archive/` holds historic code, but its internal compartmentalization is absent from the structure document. It is heavily fragmented:
* **`lupo-archive/docs/`**
* **`lupo-archive/scripts/`**
* **`lupo-archive/legacy/`**
* **`lupo-archive/lupo-docs-status-archive/`** (The directory we just created!)
* **`lupo-archive/lupo-docs-archive-batch[1-4]/`**
* **`lupo-archive/lupo-root-archive-batch[6-7]/`**

## 5. `lupo-skills/` (Agent Specialized Capabilities)
* **`lupo-skills/lupopedia-headers/`** - Likely holds documentation and validators for the header schema that should probably be moved to `lupo-docs/doctrine/` or `lupo-docs/implementations/`.

---

## Strategic Recommendation
If you want to continue the documentation cleanup crusade:
1. **Target `lupo-channels/` Next**: Since you confirmed Channels and Threads are now database-managed, the dozens of named folders (like `edge_generation_governance`) and numeric thread directories in `lupo-channels/` might be bloated with the same kind of legacy `.md` handoffs we just cleared from `status/`. 
2. **Target `lupo-rules/` PRD Alignment**: `lupo-rules/` currently contains the system's most vital PRD/Doctrines (like the new `WOLFIE_DOCTRINE`), but its subfolders (`root/`, `cursor/`) are not codified in the structure PRD. We should either document its internal layout in `project_structure_prd.md`, or ensure no "old/dead" rules are lurking in `lupo-rules/cursor/`.
