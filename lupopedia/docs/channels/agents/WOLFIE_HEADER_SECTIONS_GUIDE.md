---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Cursor-safe implementation guide for sections module in WOLFIE Headers."
tags:
  categories: ["documentation", "specification", "implementation-guide"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "WOLFIE Header Sections Module â€” Cursor Implementation Guide"
  description: "Practical guide for Cursor and other IDE agents on when and how to populate the sections module"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ðŸŸ¦ WOLFIE Header Sections Module â€” Cursor Implementation Guide

## Purpose

This guide provides **Cursor-safe, doctrine-aligned** instructions for when and how to populate the `sections:` module in WOLFIE Headers. This module extracts `##` headings from Markdown files and generates anchor links, enabling AI agents to understand file structure before reading.

---

## When to Include `sections:`

### âœ… **DO Include When:**
- File has **3 or more** `##` headings (major sections)
- File is a **documentation file** that benefits from quick navigation
- File is **large/complex** where structure overview helps agents
- You are **creating** a new large documentation file
- You are **significantly modifying** file structure (adding/removing `##` headings)

### âŒ **DON'T Include When:**
- File has **1-2 sections** (too simple, not needed)
- File has **no Markdown headings** (not applicable)
- File is a **simple script or config file** (overhead not justified)
- You are **only reading** the file (do not modify headers when reading)

---

## How to Extract Sections

### Step 1: Scan for Headings
Scan the file for lines that begin with exactly `## ` (two `#` characters followed by a space).

**Pattern:** `^## ` (regex) or `starts_with("## ")` (string check)

### Step 2: Extract Title
Extract the text after `## ` as the section title.

**Example:**
- Line: `## Getting Started`
- Title: `"Getting Started"`

### Step 3: Generate Anchor
Convert the title to an anchor slug:

1. **Convert to lowercase:** `"Getting Started"` â†’ `"getting started"`
2. **Replace spaces with hyphens:** `"getting started"` â†’ `"getting-started"`
3. **Remove punctuation:** `"What's New?"` â†’ `"whats-new"`
4. **Prefix with #:** `"getting-started"` â†’ `"#getting-started"`

**Examples:**
- `## Getting Started` â†’ `anchor: "#getting-started"`
- `## API Reference` â†’ `anchor: "#api-reference"`
- `## What's New?` â†’ `anchor: "#whats-new"`
- `## Installation & Setup` â†’ `anchor: "#installation-setup"`

### Step 4: Build YAML Array
Create an array of objects with `title` and `anchor` fields:

```yaml
sections:
  - title: "Getting Started"
    anchor: "#getting-started"
  - title: "API Reference"
    anchor: "#api-reference"
  - title: "Examples"
    anchor: "#examples"
```

---

## Implementation Rules for Cursor

### **When Creating a File:**
1. âœ… Check if file has 3+ `##` headings
2. âœ… If yes, extract all `##` headings
3. âœ… Generate anchor slugs for each
4. âœ… Add `sections:` block to WOLFIE Header
5. âœ… Include in initial header creation

### **When Modifying a File:**
1. âœ… Check if file structure changed (added/removed `##` headings)
2. âœ… If structure changed, update `sections:` block
3. âœ… If structure unchanged, leave `sections:` block as-is
4. âŒ Do NOT update `sections:` if only content changed (not structure)

### **When Reading a File:**
1. âŒ Do NOT modify the header at all
2. âŒ Do NOT update `sections:` block
3. âœ… You MAY read the `sections:` block to understand file structure

---

## Code Example (Pseudocode)

```python
def extract_sections(file_content: str) -> list:
    """Extract sections from Markdown file."""
    sections = []
    lines = file_content.split('\n')
    
    for line in lines:
        # Check if line starts with "## " (exactly two # and a space)
        if line.startswith('## '):
            # Extract title (text after "## ")
            title = line[3:].strip()
            
            # Generate anchor slug
            anchor = generate_anchor(title)
            
            sections.append({
                'title': title,
                'anchor': anchor
            })
    
    return sections

def generate_anchor(title: str) -> str:
    """Convert section title to anchor slug."""
    # Convert to lowercase
    slug = title.lower()
    
    # Replace spaces with hyphens
    slug = slug.replace(' ', '-')
    
    # Remove punctuation (keep only alphanumeric and hyphens)
    import re
    slug = re.sub(r'[^a-z0-9-]', '', slug)
    
    # Remove multiple consecutive hyphens
    slug = re.sub(r'-+', '-', slug)
    
    # Remove leading/trailing hyphens
    slug = slug.strip('-')
    
    # Prefix with #
    return f"#{slug}"

def should_include_sections(sections: list) -> bool:
    """Determine if sections block should be included."""
    # Only include if 3+ sections
    return len(sections) >= 3
```

---

## YAML Format

```yaml
sections:
  - title: "<STRING>"
    anchor: "#anchor-slug"
  - title: "<STRING>"
    anchor: "#another-slug"
```

**Rules:**
- Each entry MUST have both `title` and `anchor` fields
- `title` is the human-readable heading text
- `anchor` is the lowercase, hyphenated slug prefixed with `#`
- Order MUST match the order of sections in the file

---

## Complete Example

**Markdown File:**
```markdown
## Overview
This is the overview section.

## Installation
Installation instructions here.

## Usage
How to use the system.

## API Reference
API documentation.

## Examples
Code examples.
```

**WOLFIE Header with sections:**
```yaml
---
wolfie.headers.version: 4.0.1
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created new API documentation with sections module."
sections:
  - title: "Overview"
    anchor: "#overview"
  - title: "Installation"
    anchor: "#installation"
  - title: "Usage"
    anchor: "#usage"
  - title: "API Reference"
    anchor: "#api-reference"
  - title: "Examples"
    anchor: "#examples"
file:
  title: "Lupopedia API Guide"
  description: "Developer documentation for the Lupopedia API."
  version: "4.0.1"
  status: published
  author: "Eric Robin Gerdes"
---
```

---

## Common Mistakes to Avoid

### âŒ **Mistake 1: Including for Simple Files**
```yaml
# DON'T do this for a file with only 1-2 sections
sections:
  - title: "Overview"
    anchor: "#overview"
```

### âŒ **Mistake 2: Updating When Only Reading**
- Reading a file does NOT mean you should update `sections:`
- Only update when **modifying** the file structure

### âŒ **Mistake 3: Wrong Anchor Format**
```yaml
# DON'T do this
anchor: "getting-started"  # Missing # prefix

# DO this
anchor: "#getting-started"  # Correct
```

### âŒ **Mistake 4: Extracting Wrong Heading Level**
```yaml
# DON'T extract # (H1) or ### (H3) headings
# ONLY extract ## (H2) headings
```

### âŒ **Mistake 5: Not Matching File Order**
```yaml
# DON'T reorder sections
# Order MUST match the order in the file
```

---

## Integration with `content_sections` Field

The `sections:` module in WOLFIE Headers **mirrors** the `content_sections` field in the `lupo_contents` table. When syncing:

1. Extract `sections:` from WOLFIE Header
2. Store in `content_sections` field (JSON or serialized format)
3. Use for database queries and content navigation
4. Keep both in sync when file structure changes

---

## Testing Checklist

Before committing a file with `sections:` module:

- [ ] File has 3+ `##` headings
- [ ] All `##` headings are extracted
- [ ] Anchor slugs are correctly formatted (lowercase, hyphenated, prefixed with `#`)
- [ ] Order matches file order
- [ ] Both `title` and `anchor` fields present for each entry
- [ ] YAML syntax is valid
- [ ] Header dialog updated (if modifying file)

---

## Related Documentation

- **[WOLFIE_HEADER_SPECIFICATION.md](WOLFIE_HEADER_SPECIFICATION.md)** â€” Complete WOLFIE Header specification
- **[INLINE_DIALOG_SPECIFICATION.md](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** â€” Inline Dialog format
- **[wolfie_headers.yaml](../../wolfie_headers.yaml)** â€” Machine-readable YAML schema

---

**This guide is Cursor-safe and doctrine-aligned. Follow these rules when implementing the sections module.**
