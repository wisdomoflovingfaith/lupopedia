---
lupopedia.headers:
  file_path_from_root: "channels/42/threads/antigravity-dir-audit/undocumented_folders_report.md"
  lupopedia.schema: "report"
  artifact_type: "report"
  purpose: "Listing folders physically present in root that are undocumented in project_structure_prd.md"
---

# Undocumented Folders Report

**Channel:** 42  
**Thread:** antigravity-dir-audit  
**Date:** 2026-04-01  

Per your request, I have audited the 50 root-level directories in the repository and cross-referenced them against the explicit declarations within `docs/prd/project_structure_prd.md`. 

While 44 of the directories are properly documented in the PRD (including all 37 existing `` prefixed directories, `.git`, `node_modules`, and the 5 legacy IDE folders like `.cascade` and `.windsurf`), the following **6 folders exist on disk but are completely undocumented** in the structure PRD:

## The 6 Undocumented Folders

### 1. The Core Application Namespace
- **`app/`**
  - **Issue**: The PRD table incorrectly lists this as `app/`. As noted in our recent architectural fixes, this was restored to `app/` to comply with PSR-4 root constraints. The PRD still treats it as a prefixed folder rather than an exception.

### 2. Python Virtual Environment
- **`.venv/`**
  - **Issue**: Standard Python virtual environment wrapper. Not documented in the PRD, though it's likely used to execute the `scripts/` utilities like `generate_toon_files.py`. 

### 3. Additional IDE Configurations
The PRD lists `.cascade`, `.cursor`, `.lexa`, `.lilith`, and `.windsurf` under its "Legacy / Irrelevant Folders" cleanup policy. However, the following active IDE namespaces were missed and remain undocumented:
- **`.idea/`** (JetBrains / PhpStorm configuration)
- **`.kiro/`** (Kiro IDE agent workspace)
- **`.qodo/`** (Qodo / Codium IDE agent workspace)
- **`.vscode/`** (Visual Studio Code configuration)

## Recommended Actions for PRD Update

1. **Fix `app/`**: Remove the `app/` row from the main directory table. Add `app/` as a "STRICT EXCEPTION" identical to the `node_modules/` block, ensuring no agent ever attempts to prefix it.
2. **Update IDE list**: Expand the line under "Legacy / Irrelevant Folders" to include `.idea`, `.kiro`, `.qodo`, and `.vscode` alongside the existing agent workspaces.
3. **Document `.venv/`**: Add it to the "Non-prefixed Items" table alongside `.git/`.
