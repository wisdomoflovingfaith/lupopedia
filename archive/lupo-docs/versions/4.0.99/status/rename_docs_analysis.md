# `rename_docs.py` Technical Analysis

This report analyzes the behavior of `rename_docs.py` to identify why it has caused a repetitive edit/run cycle in previous sessions.

## 1. Script Purpose
The script's primary goal is to **normalize filenames** within specific directories (`lupo-docs` and `lupo-memory`) to follow a strict lowercase, alphanumeric, snake_case convention. It aims to ensure filesystem compatibility across different operating systems (case-sensitivity issues) and environments.

## 2. Main Execution Flow
1.  **Scope Definition:** Targets only `lupo-docs/` and `lupo-memory/` directories.
2.  **Walkthrough:** Recursively walks through the directory tree, skipping `node_modules` and `.git`.
3.  **Normalization:** For each file, it computes a "compliant name" using `to_compliant_name()`.
4.  **Comparison:** If the current filename differs from the compliant name:
    -   It handles **case-only** renames using a temporary file (critical for Windows/macOS compatibility).
    -   It handles **standard** renames using `os.rename()`.
5.  **Conflict Handling:** Skips renaming if the target filename already exists.
6.  **Logging:** Appends old and new paths to `renamed_files_log.txt`.

## 3. Rename Decision Logic
The `to_compliant_name(filename)` function uses the following rules:
-   **Split Extension:** Separates the filename from its last extension.
-   **Multiple Dots:** Joins multiple filename parts (if any) with `_`. (e.g., `file.data.md` -> `file_data.md`).
-   **Character Substitution:**
    -   Spaces and dashes (`-`) -> Underscores (`_`).
    -   All characters to Lowercase.
    -   Non-alphanumeric (except `_`) -> Underscores (`_`).
-   **Cleanup:**
    -   Collapses multiple underscores into one (`___` -> `_`).
    -   Strips leading/trailing underscores.
-   **Extension Case:** Lowercases the extension.

## 4. Reference Update Logic
-   **CRITICAL ABSENCE:** The script **DOES NOT** update references, links, or edges within file contents.
-   Renaming `Doc-Title.md` to `doc_title.md` will break any Markdown links or memory keys referring to the original name.
-   This absence of reference-patching is the most likely driver of "instability"—manual or automated attempts to fix the resulting broken links likely trigger further renames or script adjustments.

## 5. Loop / Instability Risks
1.  **Broken Links Loop:** Renaming files causes external failures (broken links/missing files in other scripts). A developer (or AI) might then try to "fix" the script to handle links, but without a robust mapping, it fails or partial-fixes, leading to more edits.
2.  **Conflict Stalemate:** If `A_B.md` and `A-B.md` both exist, the script will rename `A-B.md` to `a_b.md`. If `a_b.md` (the lowercase compliant version) already exists, it **skips** it. This leaves "dirty" files in the repository that the script *wants* to rename but *cannot*, causing it to report changes needed every time it runs.
3.  **Normalization Divergence:** If `to_compliant_name` produces a name that another tool (e.g., a documentation generator or `lupo.py`) doesn't expect or likes to change back, the two tools will fight.
4.  **Multiple Dot Flattening:** The logic `name = '_'.join(parts[:-1])` changes `site.config.json` to `site_config.json`. If subsequent logic expects the dot, it will fail, leading to script edits to "preserve" dots.

## 6. Most Likely Bug or Design Flaw
The **lack of an "update-references" step** combined with **silent conflict skipping**.
-   When a file is renamed, every file in the project (not just `lupo-docs`) might contain a reference to it.
-   By skipping conflicts, the script never reaches a "zero-change" state if name collisions exist.

## 7. Minimal Safe Fix Strategy
1.  **Stop blindly renaming:** Do not run the script until a reference-update mechanism is integrated.
2.  **Mapping Phase:** Modify the script to first generate a complete `old_path -> new_path` JSON mapping without performing any renames.
3.  **Conflict Resolution:** Explicitly handle collisions (e.g., by appending a suffix or merging content) instead of skipping.
4.  **Global Search/Replace:** Use the mapping to perform a global search/replace across the entire codebase (grep/sed style) for all renamed paths/keys.
5.  **Validation:** Run a link-checker *after* renaming to ensure no references were missed.

---
*Note: This analysis was performed on `rename_docs.py` as found in the root directory on 2026-04-14.*
