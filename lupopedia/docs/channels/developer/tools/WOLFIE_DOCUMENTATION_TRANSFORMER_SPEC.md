---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created comprehensive specification for WOLFIE Documentation Transformer - first Python program for Lupopedia ecosystem."
tags:
  categories: ["documentation", "specification", "tools", "python"]
  collections: ["core-docs", "tools"]
  channels: ["dev"]
header_atoms:
  - GLOBAL_CURRENT_AUTHORS
  - GLOBAL_CURRENT_VERSION
sections:
  - title: "Overview"
    anchor: "#overview"
  - title: "Purpose and Goals"
    anchor: "#purpose-and-goals"
  - title: "Inputs and Outputs"
    anchor: "#inputs-and-outputs"
  - title: "Core Pipeline"
    anchor: "#core-pipeline"
  - title: "Atom Scoping and Resolution"
    anchor: "#atom-scoping-and-resolution"
  - title: "Section Extraction"
    anchor: "#section-extraction"
  - title: "WOLFIE Header Management"
    anchor: "#wolfie-header-management"
  - title: "Idempotency Rules"
    anchor: "#idempotency-rules"
  - title: "Python Implementation Structure"
    anchor: "#python-implementation-structure"
  - title: "Detailed Algorithms"
    anchor: "#detailed-algorithms"
  - title: "CLI Interface"
    anchor: "#cli-interface"
  - title: "Error Handling"
    anchor: "#error-handling"
  - title: "Testing Requirements"
    anchor: "#testing-requirements"
file:
  title: "WOLFIE Documentation Transformer — Complete Specification"
  description: "Detailed specification for the first Python tool in Lupopedia ecosystem - transforms Markdown files with WOLFIE Headers and atom replacement"
  version: "0.9"
  created: 20260109000000
  modified: 20260109000000
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# WOLFIE Documentation Transformer — Complete Specification (v0.9)

**Your first Python program** — replacing "Hello World" with "Rewrite the world's documentation standards."

---

## Overview

This Python tool is the **documentation janitor** for the Lupopedia ecosystem. It scans Markdown files, extracts structure, replaces repeated content with symbolic atoms, and ensures every file has a valid WOLFIE Header.

**For Perl Programmers Learning Python:**
- This spec is written for someone familiar with programming concepts but new to Python
- Algorithms are described in pseudocode that maps to Python
- No Python-specific idioms assumed
- Focus on clear, explicit logic

---

## Purpose and Goals

### Primary Purpose

The WOLFIE Documentation Transformer performs four major tasks:

1. **Scan a directory tree** for Markdown files
2. **Extract structure** (sections, repeated blocks)
3. **Replace repeated content** with symbolic atoms
4. **Insert or update WOLFIE Headers** on every file

### Goals

- Enforce documentation doctrine across all files
- Deduplicate repeated content using atoms
- Apply global/module/directory/file atom scoping
- Generate section lists automatically
- Ensure every file has a valid WOLFIE Header
- Maintain idempotency (running twice does nothing)

### What This Tool Does NOT Do

- ❌ Does NOT modify non-Markdown files
- ❌ Does NOT create new atoms without explicit permission
- ❌ Does NOT expand atom values back to literals
- ❌ Does NOT break existing formatting
- ❌ Does NOT modify files outside the target directory

---

## Inputs and Outputs

### Inputs

#### Required
- **Root directory** to scan (command-line argument or config)
- **Global atoms file:** `/config/global_atoms.yaml`

#### Optional
- **Module atom files:** `/modules/<module>/module_atoms.yaml`
- **Directory atom files:** `<path>/_dir_atoms.yaml`
- **Exclusion patterns:** Directories to skip (e.g., `node_modules`, `vendor`, `.git`)

#### File Types
- Markdown files: `*.md`
- YAML atom files: `*_atoms.yaml`, `global_atoms.yaml`

### Outputs

For each Markdown file processed:

1. **Cleaned, atom-aware version** of the content
2. **Valid WOLFIE Header** (created or updated)
3. **`sections:` block** (if file has 3+ `##` headings)
4. **`header_atoms:` block** (if atoms are used in the file)
5. **`file_atoms:` block** (if file-specific atoms are defined)

#### Updated Files
- Markdown files: Modified in-place (or to output directory)
- `global_atoms.yaml`: Only updated if new atoms are explicitly allowed

---

## Core Pipeline

The program runs in **four deterministic stages**:

### Stage 1: File Discovery

**Purpose:** Find all Markdown files to process

**Algorithm:**
```
1. Start at root directory
2. Walk directory tree recursively
3. For each file:
   a. Check if filename ends with .md
   b. Check if file path matches exclusion patterns
   c. If not excluded, add to file list
4. Return list of file paths
```

**Exclusion Patterns (default):**
- `node_modules/`
- `vendor/`
- `.git/`
- `__pycache__/`
- `.venv/`
- `venv/`
- Any directory starting with `.`

**Python Notes:**
- Use `os.walk()` or `pathlib.Path.rglob()` for directory traversal
- Use `fnmatch` or `pathlib` pattern matching for exclusions

### Stage 2: Pattern Extraction

**Purpose:** Analyze each file to extract structure and identify atoms

**For each file:**

#### 2.1 Read File Content
```
1. Open file in read mode
2. Read all lines into a list
3. Store original content for comparison
```

#### 2.2 Identify Section Headings
```
1. Scan all lines
2. For each line:
   a. Check if line starts with "## " (exactly two # and a space)
   b. Extract text after "## "
   c. Store as section title
3. Return list of section titles
```

#### 2.3 Identify Existing WOLFIE Header
```
1. Check if first line is "---"
2. If yes:
   a. Find next "---" delimiter
   b. Extract YAML block between delimiters
   c. Parse YAML
   d. Store header data
3. If no:
   a. Mark as "no header"
```

#### 2.4 Identify Repeated Blocks (Atom Candidates)
```
1. Scan file content (excluding header)
2. For each line or block:
   a. Compare with known atom values from all scopes
   b. If exact match found:
      - Mark as atom candidate
      - Record atom name and scope
3. Return list of atom candidates
```

**Known Atom Patterns:**
- Author names (e.g., "Captain Wolfie", "Eric Robin Gerdes")
- Version strings (e.g., "4.0.1")
- Copyright strings
- Project names
- Status values (e.g., "published", "draft")

#### 2.5 Identify Directory/Module/Global Atoms in Scope
```
1. Determine file's directory path
2. Check for _dir_atoms.yaml in:
   a. File's directory (for DIR_*)
   b. Parent directories (for DIRR_*, walk up)
3. Determine module from path:
   a. If path starts with /modules/<module>/, use that module
   b. Load module_atoms.yaml if exists
4. Load global_atoms.yaml
5. Return all atoms in scope for this file
```

### Stage 3: Atom Replacement

**Purpose:** Replace literal values with symbolic atom references

**Algorithm:**
```
1. Load all atoms in scope (FILE → DIR → DIRR → MODULE → GLOBAL)
2. For each atom candidate found in Stage 2:
   a. Check resolution order:
      - First check FILE_* atoms (from file_atoms block)
      - Then DIR_* atoms (from file's directory)
      - Then DIRR_* atoms (walk up parent dirs)
      - Then MODULE_* atoms (from module)
      - Finally GLOBAL_* atoms (from global)
   b. If atom value matches literal in file:
      - Replace literal with atom name
      - Record in header_atoms list
3. Preserve all existing atom references (do NOT expand them)
4. Return modified content
```

**Critical Rules:**
- ✅ DO replace literals with matching atom names
- ✅ DO preserve existing atom references
- ❌ DO NOT expand atom values to literals
- ❌ DO NOT create new atoms without permission
- ❌ DO NOT modify atom references

**Example:**
```
Before:
  author: "Captain Wolfie"
  version: "4.0.1"

After (if GLOBAL_CURRENT_AUTHORS = "Captain Wolfie" and GLOBAL_CURRENT_VERSION = "4.0.1"):
  author: GLOBAL_CURRENT_AUTHORS
  version: GLOBAL_CURRENT_VERSION
```

### Stage 4: WOLFIE Header Injection

**Purpose:** Ensure every file has a valid, complete WOLFIE Header

#### 4.1 If File Has No Header

**Create new header:**
```
1. Build header YAML:
   a. wolfie.headers.version: 4.0.1
   b. dialog:
      - speaker: PYTHON_TOOL
      - target: @everyone
      - message: "Applied atom replacements and updated header."
      - mood: "88CCFF"
   c. header_atoms: [list of atoms used]
   d. sections: [if 3+ sections exist]
   e. file:
      - title: <extracted from first # heading or filename>
      - created: <current timestamp>
      - modified: <current timestamp>
      - status: <from atoms or "draft">
2. Format as YAML block
3. Wrap with "---" delimiters
4. Insert at top of file
```

#### 4.2 If File Has Existing Header

**Update header:**
```
1. Parse existing header YAML
2. Update dialog block:
   a. Set speaker: PYTHON_TOOL
   b. Set target: @everyone
   c. Update message to reflect changes
   d. Set mood: "88CCFF"
3. Update header_atoms:
   a. Merge with existing list
   b. Remove duplicates
   c. Keep alphabetical order
4. Update sections:
   a. If file has 3+ sections, ensure sections block exists
   b. Update section list if structure changed
5. Update file metadata:
   a. Preserve existing created date
   b. Update modified date to now
   c. Preserve existing status unless changed
6. Preserve all other header fields (context, tags, etc.)
7. Reconstruct header YAML
8. Replace existing header in file
```

**Preservation Rules:**
- ✅ Preserve all existing header fields
- ✅ Preserve existing atom references
- ✅ Preserve existing sections
- ✅ Only update dialog and modified date
- ❌ Do NOT remove existing fields
- ❌ Do NOT change existing values (except dialog and modified)

---

## Atom Scoping and Resolution

### Resolution Order (First Match Wins)

```
1. FILE_*   → Check file_atoms block in WOLFIE Header
2. DIR_*    → Check _dir_atoms.yaml in file's directory
3. DIRR_*   → Check _dir_atoms.yaml, walk up parent directories
4. MODULE_* → Check module_atoms.yaml for current module
5. GLOBAL_* → Check /config/global_atoms.yaml
```

### Atom Loading Algorithm

**Function: `load_atoms_for_file(file_path)`**

```
1. Initialize empty atom dictionary
2. Load FILE_* atoms:
   a. Parse WOLFIE Header if exists
   b. Extract file_atoms block
   c. Add to atom dictionary with FILE_ prefix
3. Load DIR_* atoms:
   a. Get file's directory path
   b. Check for _dir_atoms.yaml in that directory
   c. If found, parse and add DIR_* atoms
4. Load DIRR_* atoms:
   a. Start at file's directory
   b. Walk up parent directories
   c. For each directory:
      - Check for _dir_atoms.yaml
      - If found, parse and add DIRR_* atoms
      - Stop at project root
5. Load MODULE_* atoms:
   a. Determine module from file path
   b. If path starts with /modules/<module>/:
      - Load /modules/<module>/module_atoms.yaml
      - Parse and add MODULE_* atoms
6. Load GLOBAL_* atoms:
   a. Load /config/global_atoms.yaml
   b. Parse and add GLOBAL_* atoms
7. Return atom dictionary
```

### Atom Resolution Algorithm

**Function: `resolve_atom(atom_name, file_path)`**

```
1. Extract scope prefix (FILE_, DIR_, DIRR_, MODULE_, GLOBAL_)
2. Load all atoms for file_path
3. Check in resolution order:
   a. If FILE_*: Check file_atoms block
   b. If DIR_*: Check _dir_atoms.yaml in file's directory
   c. If DIRR_*: Walk up parent dirs, check _dir_atoms.yaml
   d. If MODULE_*: Check module_atoms.yaml
   e. If GLOBAL_*: Check global_atoms.yaml
4. Return atom value if found, None if not found
```

### Atom Replacement Algorithm

**Function: `replace_atoms_in_content(content, atoms_dict)`**

```
1. Split content into lines
2. For each line:
   a. Check if line contains literal values that match atom values
   b. For each matching atom:
      - Check if literal is exact match (case-sensitive for values)
      - If match:
         * Replace literal with atom name
         * Add atom to header_atoms list
   c. Preserve existing atom references (do NOT modify lines with atom names)
3. Return modified content and list of atoms used
```

**Matching Rules:**
- Exact string match (case-sensitive)
- Match in YAML values (after `:`)
- Match in quoted strings
- Do NOT match in comments
- Do NOT match in code blocks

---

## Section Extraction

### Algorithm

**Function: `extract_sections(lines)`**

```
1. Initialize empty sections list
2. For each line in lines:
   a. Strip leading whitespace
   b. Check if line starts with "## " (exactly two # and a space)
   c. If yes:
      - Extract text after "## "
      - Store as title
      - Generate anchor:
         * Convert to lowercase
         * Replace spaces with hyphens
         * Remove punctuation (keep alphanumeric and hyphens)
         * Remove multiple consecutive hyphens
         * Remove leading/trailing hyphens
         * Prefix with "#"
      - Add to sections list: {title: "...", anchor: "#..."}
3. Return sections list
```

### Anchor Generation Examples

```
## Getting Started → #getting-started
## API Reference → #api-reference
## What's New? → #whats-new
## Installation & Setup → #installation-setup
## 2025 Updates → #2025-updates
```

### When to Include Sections Block

```
If sections list has 3 or more entries:
  - Include sections block in header
Else:
  - Do NOT include sections block
```

---

## WOLFIE Header Management

### Header Structure

Every file MUST have:

```yaml
---
wolfie.headers.version: 4.0.1
dialog:
  speaker: PYTHON_TOOL
  target: @everyone
  message: "<descriptive message>"
  mood: "88CCFF"
header_atoms:
  - <atom names used>
sections:
  - title: "<section title>"
    anchor: "#<anchor-slug>"
file:
  title: "<file title>"
  created: <YYYYMMDDHHIISS>
  modified: <YYYYMMDDHHIISS>
  status: <draft|review|published>
---
```

### Header Creation Algorithm

**Function: `create_header(file_path, sections, atoms_used, existing_metadata)`**

```
1. Initialize header dictionary
2. Set wolfie.headers.version: "4.0.1"
3. Set dialog block:
   a. speaker: "PYTHON_TOOL"
   b. target: "@everyone"
   c. message: "Applied atom replacements and updated header."
   d. mood: "88CCFF"
4. If atoms_used is not empty:
   a. Set header_atoms: sorted list of unique atoms
5. If sections has 3+ entries:
   a. Set sections: list of {title, anchor} objects
6. Set file block:
   a. title: Extract from first # heading or use filename
   b. created: Use existing or current timestamp
   c. modified: Current timestamp
   d. status: Use existing or "draft"
7. If existing_metadata has other fields:
   a. Preserve context, tags, in_this_file_we_have, etc.
8. Convert dictionary to YAML
9. Wrap with "---" delimiters
10. Return header string
```

### Header Update Algorithm

**Function: `update_header(existing_header, sections, atoms_used, changes)`**

```
1. Parse existing header YAML
2. Update dialog block:
   a. Preserve existing speaker if not PYTHON_TOOL
   b. Update message to reflect changes
   c. Set mood: "88CCFF"
3. Update header_atoms:
   a. Merge with existing list
   b. Remove duplicates
   c. Sort alphabetically
4. Update sections:
   a. If 3+ sections exist, ensure sections block exists
   b. Update if structure changed
5. Update file metadata:
   a. Preserve created date
   b. Update modified date
   c. Preserve status unless explicitly changed
6. Preserve all other fields
7. Return updated header YAML
```

---

## Idempotency Rules

**Critical:** Running the tool twice MUST produce identical results.

### Idempotency Checks

#### 1. Header Duplication
```
Before writing header:
  - Check if file already has WOLFIE Header
  - If yes, update existing (do NOT create duplicate)
  - Ensure only one header block exists
```

#### 2. Section Duplication
```
Before adding sections:
  - Check if sections block already exists
  - If yes, update existing (do NOT create duplicate)
  - Ensure sections match actual file structure
```

#### 3. Atom Reference Preservation
```
When processing atoms:
  - If line already contains atom reference, preserve it
  - Do NOT replace atom reference with literal
  - Do NOT replace literal with atom if already atom reference
```

#### 4. Format Preservation
```
When modifying file:
  - Preserve original line endings (LF vs CRLF)
  - Preserve original indentation style
  - Preserve original YAML formatting
  - Do NOT reformat entire file
```

#### 5. Content Integrity
```
After processing:
  - File should be identical if run again
  - Only modified date may change (if file actually modified)
  - No duplicate blocks
  - No broken YAML
  - No lost content
```

### Idempotency Test

```
1. Run tool on directory
2. Save file hashes
3. Run tool again on same directory
4. Compare file hashes
5. Should be identical (except modified dates in headers)
```

---

## Python Implementation Structure

### Module Structure

```python
# wolfie_doc_transformer.py

import os
import yaml
import re
from pathlib import Path
from datetime import datetime

# Constants
WOLFIE_HEADER_VERSION = "4.0.1"
EXCLUDED_DIRS = ['node_modules', 'vendor', '.git', '__pycache__', '.venv', 'venv']
ATOM_SCOPES = ['FILE_', 'DIR_', 'DIRR_', 'MODULE_', 'GLOBAL_']

# Core Functions

def load_global_atoms(global_atoms_path):
    """Load atoms from /config/global_atoms.yaml"""
    pass

def load_module_atoms(module_path):
    """Load atoms from /modules/<module>/module_atoms.yaml"""
    pass

def load_dir_atoms(dir_path):
    """Load atoms from <dir>/_dir_atoms.yaml"""
    pass

def load_all_atoms_for_file(file_path, root_dir):
    """Load all atoms in scope for a file (FILE → DIR → DIRR → MODULE → GLOBAL)"""
    pass

def resolve_atom(atom_name, file_path, root_dir):
    """Resolve atom value following resolution order"""
    pass

def extract_sections(lines):
    """Extract all ## headings and generate anchor links"""
    pass

def generate_anchor(title):
    """Convert section title to anchor slug"""
    pass

def find_existing_header(lines):
    """Find and parse existing WOLFIE Header"""
    pass

def identify_atom_candidates(content, atoms_dict):
    """Find literal values that match atom values"""
    pass

def replace_atoms_in_content(content, atoms_dict):
    """Replace literal values with atom references"""
    pass

def create_header(file_path, sections, atoms_used, existing_metadata):
    """Create new WOLFIE Header"""
    pass

def update_header(existing_header, sections, atoms_used, changes):
    """Update existing WOLFIE Header"""
    pass

def ensure_header(file_path, content, sections, atoms_used):
    """Ensure file has valid WOLFIE Header (create or update)"""
    pass

def process_file(file_path, root_dir):
    """Process a single Markdown file"""
    pass

def discover_files(root_dir):
    """Discover all .md files in directory tree"""
    pass

def main():
    """Main entry point"""
    pass

if __name__ == "__main__":
    main()
```

### Function Signatures (Detailed)

```python
def load_global_atoms(global_atoms_path: str) -> dict:
    """
    Load GLOBAL_* atoms from /config/global_atoms.yaml
    
    Returns:
        dict: Dictionary of {atom_name: atom_value}
    """
    pass

def load_module_atoms(module_path: str) -> dict:
    """
    Load MODULE_* atoms from module_atoms.yaml
    
    Args:
        module_path: Path to module directory (e.g., /modules/craftysyntax)
    
    Returns:
        dict: Dictionary of {atom_name: atom_value}
    """
    pass

def load_dir_atoms(dir_path: str) -> tuple:
    """
    Load DIR_* and DIRR_* atoms from _dir_atoms.yaml
    
    Args:
        dir_path: Path to directory containing _dir_atoms.yaml
    
    Returns:
        tuple: (dir_atoms_dict, dirr_atoms_dict)
    """
    pass

def load_all_atoms_for_file(file_path: str, root_dir: str) -> dict:
    """
    Load all atoms in scope for a file following resolution order
    
    Args:
        file_path: Path to the file
        root_dir: Root directory of project
    
    Returns:
        dict: Combined dictionary of all atoms in scope
    """
    pass

def resolve_atom(atom_name: str, file_path: str, root_dir: str) -> str:
    """
    Resolve atom value following resolution order
    
    Args:
        atom_name: Atom name with prefix (e.g., GLOBAL_CURRENT_AUTHORS)
        file_path: Path to file
        root_dir: Root directory of project
    
    Returns:
        str: Atom value, or None if not found
    """
    pass

def extract_sections(lines: list) -> list:
    """
    Extract all ## headings and generate anchor links
    
    Args:
        lines: List of file lines
    
    Returns:
        list: List of {title: str, anchor: str} dictionaries
    """
    pass

def generate_anchor(title: str) -> str:
    """
    Convert section title to anchor slug
    
    Args:
        title: Section title (e.g., "Getting Started")
    
    Returns:
        str: Anchor slug (e.g., "#getting-started")
    """
    pass

def find_existing_header(lines: list) -> tuple:
    """
    Find and parse existing WOLFIE Header
    
    Args:
        lines: List of file lines
    
    Returns:
        tuple: (header_dict, header_start_line, header_end_line)
               or (None, -1, -1) if no header
    """
    pass

def identify_atom_candidates(content: str, atoms_dict: dict) -> list:
    """
    Find literal values in content that match atom values
    
    Args:
        content: File content (excluding header)
        atoms_dict: Dictionary of available atoms
    
    Returns:
        list: List of (literal_value, atom_name, line_number) tuples
    """
    pass

def replace_atoms_in_content(content: str, atoms_dict: dict) -> tuple:
    """
    Replace literal values with atom references
    
    Args:
        content: File content
        atoms_dict: Dictionary of available atoms
    
    Returns:
        tuple: (modified_content, atoms_used_list)
    """
    pass

def create_header(file_path: str, sections: list, atoms_used: list, 
                  existing_metadata: dict) -> str:
    """
    Create new WOLFIE Header
    
    Args:
        file_path: Path to file
        sections: List of section dictionaries
        atoms_used: List of atom names used
        existing_metadata: Any existing metadata to preserve
    
    Returns:
        str: YAML header string with --- delimiters
    """
    pass

def update_header(existing_header: dict, sections: list, atoms_used: list,
                  changes: dict) -> str:
    """
    Update existing WOLFIE Header
    
    Args:
        existing_header: Existing header dictionary
        sections: List of section dictionaries
        atoms_used: List of atom names used
        changes: Dictionary of changes to apply
    
    Returns:
        str: Updated YAML header string
    """
    pass

def ensure_header(file_path: str, content: str, sections: list, 
                  atoms_used: list) -> str:
    """
    Ensure file has valid WOLFIE Header (create or update)
    
    Args:
        file_path: Path to file
        content: File content
        sections: List of section dictionaries
        atoms_used: List of atom names used
    
    Returns:
        str: Content with valid header
    """
    pass

def process_file(file_path: str, root_dir: str) -> bool:
    """
    Process a single Markdown file
    
    Args:
        file_path: Path to file
        root_dir: Root directory of project
    
    Returns:
        bool: True if file was modified, False otherwise
    """
    pass

def discover_files(root_dir: str, exclude_patterns: list = None) -> list:
    """
    Discover all .md files in directory tree
    
    Args:
        root_dir: Root directory to scan
        exclude_patterns: List of directory patterns to exclude
    
    Returns:
        list: List of file paths
    """
    pass

def main():
    """Main entry point"""
    pass
```

---

## Detailed Algorithms

### Algorithm 1: Directory Walking with Exclusions

```python
def discover_files(root_dir, exclude_patterns=None):
    if exclude_patterns is None:
        exclude_patterns = EXCLUDED_DIRS
    
    files = []
    root_path = Path(root_dir)
    
    for file_path in root_path.rglob("*.md"):
        # Check if any parent directory matches exclusion pattern
        should_exclude = False
        for part in file_path.parts:
            if part in exclude_patterns:
                should_exclude = True
                break
            if part.startswith('.'):
                should_exclude = True
                break
        
        if not should_exclude:
            files.append(str(file_path))
    
    return files
```

### Algorithm 2: Section Extraction

```python
def extract_sections(lines):
    sections = []
    
    for line in lines:
        stripped = line.lstrip()
        if stripped.startswith("## "):
            # Extract title (text after "## ")
            title = stripped[3:].strip()
            
            # Generate anchor
            anchor = generate_anchor(title)
            
            sections.append({
                "title": title,
                "anchor": anchor
            })
    
    return sections

def generate_anchor(title):
    # Convert to lowercase
    slug = title.lower()
    
    # Replace spaces with hyphens
    slug = slug.replace(" ", "-")
    
    # Remove punctuation (keep alphanumeric and hyphens)
    import re
    slug = re.sub(r'[^a-z0-9-]', '', slug)
    
    # Remove multiple consecutive hyphens
    slug = re.sub(r'-+', '-', slug)
    
    # Remove leading/trailing hyphens
    slug = slug.strip('-')
    
    # Prefix with #
    return f"#{slug}"
```

### Algorithm 3: Header Parsing

```python
def find_existing_header(lines):
    if not lines or lines[0].strip() != "---":
        return None, -1, -1
    
    # Find end delimiter
    end_line = -1
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            end_line = i
            break
    
    if end_line == -1:
        return None, -1, -1
    
    # Extract YAML block
    yaml_lines = lines[1:end_line]
    yaml_content = "\n".join(yaml_lines)
    
    try:
        header_dict = yaml.safe_load(yaml_content)
        return header_dict, 0, end_line
    except yaml.YAMLError:
        return None, -1, -1
```

### Algorithm 4: Atom Loading with Resolution Order

```python
def load_all_atoms_for_file(file_path, root_dir):
    atoms = {}
    file_path_obj = Path(file_path)
    file_dir = file_path_obj.parent
    
    # 1. Load FILE_* atoms (from existing header if present)
    # (Will be loaded separately when processing header)
    
    # 2. Load DIR_* atoms
    dir_atoms_file = file_dir / "_dir_atoms.yaml"
    if dir_atoms_file.exists():
        dir_atoms = load_dir_atoms(str(file_dir))
        atoms.update(dir_atoms[0])  # DIR_* atoms
    
    # 3. Load DIRR_* atoms (walk up parent directories)
    current_dir = file_dir
    while current_dir != Path(root_dir).parent:
        dirr_atoms_file = current_dir / "_dir_atoms.yaml"
        if dirr_atoms_file.exists():
            dirr_atoms = load_dir_atoms(str(current_dir))
            atoms.update(dirr_atoms[1])  # DIRR_* atoms
            break
        current_dir = current_dir.parent
    
    # 4. Load MODULE_* atoms
    if "/modules/" in str(file_path):
        # Extract module name from path
        parts = Path(file_path).parts
        if "modules" in parts:
            module_idx = parts.index("modules")
            if module_idx + 1 < len(parts):
                module_name = parts[module_idx + 1]
                module_path = Path(root_dir) / "modules" / module_name
                module_atoms = load_module_atoms(str(module_path))
                atoms.update(module_atoms)
    
    # 5. Load GLOBAL_* atoms
    global_atoms_path = Path(root_dir) / "config" / "global_atoms.yaml"
    if global_atoms_path.exists():
        global_atoms = load_global_atoms(str(global_atoms_path))
        atoms.update(global_atoms)
    
    return atoms
```

### Algorithm 5: Atom Replacement

```python
def replace_atoms_in_content(content, atoms_dict):
    lines = content.split('\n')
    modified_lines = []
    atoms_used = set()
    
    for line in lines:
        modified_line = line
        
        # Skip if line is part of header
        if line.strip() == "---":
            modified_lines.append(modified_line)
            continue
        
        # Skip if line already contains atom reference
        if any(atom_name in line for atom_name in atoms_dict.keys()):
            modified_lines.append(modified_line)
            continue
        
        # Check for literal values that match atom values
        for atom_name, atom_value in atoms_dict.items():
            # Match in YAML value format: "key: value"
            pattern = f':\\s*["\']?{re.escape(str(atom_value))}["\']?'
            if re.search(pattern, line):
                # Replace with atom name
                modified_line = re.sub(
                    pattern,
                    f': {atom_name}',
                    modified_line
                )
                atoms_used.add(atom_name)
                break
        
        modified_lines.append(modified_line)
    
    return '\n'.join(modified_lines), sorted(list(atoms_used))
```

---

## CLI Interface

### Command-Line Arguments

```python
import argparse

def parse_arguments():
    parser = argparse.ArgumentParser(
        description="WOLFIE Documentation Transformer - Transform Markdown files with WOLFIE Headers"
    )
    
    parser.add_argument(
        "root_dir",
        type=str,
        help="Root directory to scan for Markdown files"
    )
    
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Show what would be changed without modifying files"
    )
    
    parser.add_argument(
        "--output-dir",
        type=str,
        help="Output directory (if not specified, modify files in-place)"
    )
    
    parser.add_argument(
        "--exclude",
        nargs="+",
        help="Additional directories to exclude (space-separated)"
    )
    
    parser.add_argument(
        "--allow-new-atoms",
        action="store_true",
        help="Allow creating new atoms in global_atoms.yaml"
    )
    
    parser.add_argument(
        "--verbose",
        action="store_true",
        help="Verbose output"
    )
    
    return parser.parse_args()
```

### Usage Examples

```bash
# Basic usage
python wolfie_doc_transformer.py /path/to/docs

# Dry run (see what would change)
python wolfie_doc_transformer.py /path/to/docs --dry-run

# Output to different directory
python wolfie_doc_transformer.py /path/to/docs --output-dir /path/to/output

# Exclude additional directories
python wolfie_doc_transformer.py /path/to/docs --exclude build dist

# Allow creating new atoms
python wolfie_doc_transformer.py /path/to/docs --allow-new-atoms

# Verbose output
python wolfie_doc_transformer.py /path/to/docs --verbose
```

---

## Error Handling

### Error Types and Handling

#### 1. File I/O Errors
```python
try:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
except IOError as e:
    print(f"Error reading {file_path}: {e}")
    return False
```

#### 2. YAML Parsing Errors
```python
try:
    header_dict = yaml.safe_load(yaml_content)
except yaml.YAMLError as e:
    print(f"Error parsing YAML in {file_path}: {e}")
    # Skip file or use default header
    return None
```

#### 3. Missing Atom Files
```python
# If atom file doesn't exist, return empty dict (not an error)
if not atom_file.exists():
    return {}
```

#### 4. Invalid Atom References
```python
# If atom not found, preserve reference (don't fail)
if atom_value is None:
    # Keep original atom reference in file
    pass
```

### Error Reporting

```python
class ProcessingError(Exception):
    """Base exception for processing errors"""
    pass

class AtomResolutionError(ProcessingError):
    """Error resolving atom"""
    pass

class HeaderParseError(ProcessingError):
    """Error parsing WOLFIE Header"""
    pass
```

---

## Testing Requirements

### Unit Tests

1. **Section Extraction**
   - Test with various heading formats
   - Test anchor generation
   - Test edge cases (empty titles, special characters)

2. **Atom Loading**
   - Test each scope (FILE, DIR, DIRR, MODULE, GLOBAL)
   - Test resolution order
   - Test missing files

3. **Atom Replacement**
   - Test literal replacement
   - Test preservation of existing references
   - Test edge cases

4. **Header Management**
   - Test header creation
   - Test header update
   - Test idempotency

### Integration Tests

1. **Full Pipeline**
   - Process sample directory
   - Verify all files have headers
   - Verify atom replacement
   - Verify idempotency

2. **Multi-Scope Atoms**
   - Test with files using multiple atom scopes
   - Test resolution order
   - Test inheritance

### Test Data

Create test directory structure:
```
test_docs/
├── config/
│   └── global_atoms.yaml
├── modules/
│   └── testmodule/
│       └── module_atoms.yaml
├── docs/
│   ├── _dir_atoms.yaml
│   ├── file1.md
│   └── subdir/
│       └── file2.md
└── standalone.md
```

---

## Implementation Notes for Perl Programmers

### Python vs Perl Differences

1. **String Handling**
   - Python: `"string"` or `'string'` (both work)
   - Use `f"text {variable}"` for interpolation (like Perl's `"text $variable"`)

2. **Lists/Arrays**
   - Python: `list = [1, 2, 3]` (like Perl's `@array = (1, 2, 3)`)
   - Access: `list[0]` (like Perl's `$array[0]`)

3. **Dictionaries/Hashes**
   - Python: `dict = {"key": "value"}` (like Perl's `%hash = ("key" => "value")`)
   - Access: `dict["key"]` (like Perl's `$hash{"key"}`)

4. **File I/O**
   - Python: `with open(file, 'r') as f: content = f.read()`
   - Similar to Perl's `open(my $fh, '<', $file) or die`

5. **Regular Expressions**
   - Python: `import re; re.search(pattern, string)`
   - Similar to Perl's `$string =~ /pattern/`

### Key Python Libraries

- `pathlib`: File path handling (recommended over `os.path`)
- `yaml`: YAML parsing (`pip install pyyaml`)
- `re`: Regular expressions (built-in)
- `argparse`: Command-line argument parsing (built-in)

---

## Next Steps

1. **Implement Core Functions**
   - Start with file discovery
   - Then section extraction
   - Then atom loading
   - Finally header management

2. **Test Incrementally**
   - Test each function separately
   - Build up to full pipeline

3. **Handle Edge Cases**
   - Empty files
   - Files with no headings
   - Files with broken YAML
   - Missing atom files

4. **Add Logging**
   - Track what files are processed
   - Log atom replacements
   - Log errors

---

**This specification is complete and ready for implementation.**
