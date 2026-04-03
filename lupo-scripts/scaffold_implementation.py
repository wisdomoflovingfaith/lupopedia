#!/usr/bin/env python3
"""
Implementation Folder Scaffolding Script

Automatically creates the complete implementation folder structure for a new PRD.
Constitutionally compliant - pure file system operations, no database dependencies.

Usage:
    python scaffold_implementation.py --prd 30 --title "channel_usage_patterns"
    python scaffold_implementation.py --prd 31 --title "implementation_folder_guidelines"
"""

import argparse
import os
import sys
import shutil
from datetime import datetime, timezone
from pathlib import Path

def get_script_dir():
    """Get the directory where this script is located."""
    return os.path.dirname(os.path.abspath(__file__))

def get_template_dir():
    """Get the template directory."""
    script_dir = get_script_dir()
    return os.path.join(script_dir, "..", "lupo-docs", "implementations", "_template")

def generate_timestamp():
    """Generate current timestamp for file headers."""
    dt = datetime.now(timezone.utc)
    return dt.strftime("%Y%m%d%H%M%S")

def create_folder_structure(base_path, prd_id, prd_title, prd_slug):
    """Create the complete folder structure for an implementation."""
    
    # Create main implementation folder
    impl_name = f"{prd_id}_{prd_slug}"
    impl_path = os.path.join(base_path, impl_name)
    os.makedirs(impl_path, exist_ok=True)
    
    # Create subfolders
    subfolders = [
        "questions/critical",
        "questions/optimization", 
        "questions/clarification",
        "answers",
        "decisions",
        "comments",
        "templates",
        "versions/v1.0.0",
        "tests"
    ]
    
    for folder in subfolders:
        folder_path = os.path.join(impl_path, folder)
        os.makedirs(folder_path, exist_ok=True)
    
    return impl_path, impl_name

def copy_templates(impl_path, template_dir):
    """Copy template files to the new implementation."""
    
    # Copy question templates
    question_levels = ["critical", "optimization", "clarification"]
    for level in question_levels:
        src_template = os.path.join(template_dir, "questions", level, "YYYYMMDD_HHIISS_QUESTION_title.md")
        dst_template = os.path.join(impl_path, "templates", f"QUESTION_{level.upper()}_TEMPLATE.md")
        
        if os.path.exists(src_template):
            shutil.copy2(src_template, dst_template)
    
    # Copy answer template
    answer_template = os.path.join(template_dir, "answers", "YYYYMMDD_HHIISS_ANSWER_title.md")
    if os.path.exists(answer_template):
        shutil.copy2(answer_template, os.path.join(impl_path, "templates", "ANSWER_TEMPLATE.md"))
    
    # Copy other templates if they exist
    other_templates = [
        ("README.md", "README_TEMPLATE.md"),
        ("comments/THREAD_INDEX.md", "COMMENTS_INDEX_TEMPLATE.md")
    ]
    
    for src, dst in other_templates:
        src_path = os.path.join(template_dir, src)
        dst_path = os.path.join(impl_path, "templates", dst)
        if os.path.exists(src_path):
            shutil.copy2(src_path, dst_path)

def create_readme(impl_path, prd_id, prd_title, prd_slug):
    """Create the main README.md file for the implementation."""
    
    timestamp = generate_timestamp()
    
    readme_content = f"""---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "{timestamp}"
  file_path_from_root: "lupo-docs/implementations/{prd_id}_{prd_slug}/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{prd_id}_{prd_slug}/README.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{prd_id}-{prd_slug}-implementation"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "{prd_id}_{prd_slug}"
  artifact_type: "implementation"
  artifact_kind: "overview"
  purpose: "Implementation of {prd_title}"
  tags:
    - "implementation"
    - "prd{prd_id}"
    - "{prd_slug}"
lupopedia.edges:
  outbound_edges:
    - to: "../../../prd/{prd_id}_{prd_slug}.md"
      type: implements
      weight: 1.0
      reason: "PRD this implements"
    - to: "../../../prd/30_channel_usage_patterns.md"
      type: references
      weight: 0.9
      reason: "Channel usage patterns"
    - to: "../../../prd/31_implementation_folder_guidelines.md"
      type: references
      weight: 0.9
      reason: "Implementation guidelines"
---

# Implementation: {prd_title}

## Overview

This implementation addresses PRD {prd_id}: {prd_title}.

## Status

- **Current Status**: Planning
- **Started**: {datetime.now(timezone.utc).strftime('%Y-%m-%d')}
- **Target Completion**: TBD
- **Assigned To**: TBD

## Related Artifacts

- **PRD**: [{prd_id}_{prd_slug}.md](../../../prd/{prd_id}_{prd_slug}.md)
- **Channel**: [development](../../../lupo-channels/0/development/)
- **Implementation**: Current folder
- **Dependencies**: TBD

## Question Status

- **Critical**: 0 open, 0 answered
- **Optimization**: 0 open, 0 answered  
- **Clarification**: 0 open, 0 answered

## Implementation Progress

### Completed
- None yet

### In Progress
- None yet

### Blocked
- None yet

### Next Steps
1. Review PRD requirements
2. Create initial implementation plan
3. Set up development environment

## Folder Structure

```
{prd_id}_{prd_slug}/
├── README.md                    # This file
├── changelog.md                 # Implementation changes
├── questions/                   # Implementation questions
│   ├── critical/               # HALT implementation questions
│   ├── optimization/           # Better approaches found
│   └── clarification/          # Minor ambiguities
├── answers/                     # Human responses to questions
├── decisions/                   # Implementation decisions
├── comments/                    # Ongoing dialogue
├── templates/                   # Standardized templates
├── authors.md                   # Implementation contributors
├── edges.md                     # System-wide relational mapping
├── todo.md                      # Remaining tasks
├── versions/                    # Version snapshots
└── tests/                       # Test files and coverage
```

## Usage Guidelines

### Creating Questions
```bash
python lupo-scripts/create_implementation_question.py \\
  --implementation {prd_id}_{prd_slug} \\
  --level critical \\
  --title "your_question_here"
```

### Validating Structure
```bash
python lupo-scripts/validate_implementation_questions.py {prd_id}_{prd_slug}
```

## Implementation Notes

*Add implementation-specific notes here as work progresses.*

---

*Last Updated: {datetime.now(timezone.utc).strftime('%Y-%m-%d %H:%M:%S UTC')}*
"""
    
    readme_path = os.path.join(impl_path, "README.md")
    with open(readme_path, 'w', encoding='utf-8') as f:
        f.write(readme_content)

def create_thread_indexes(impl_path):
    """Create THREAD_INDEX.md files for all folders."""
    
    timestamp = generate_timestamp()
    
    # Base thread index template
    index_template = f"""---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "{timestamp}"
  file_path_from_root: "lupo-docs/implementations/{{folder}}/THREAD_INDEX.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{{folder}}/THREAD_INDEX.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{{folder}}-index"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "implementation"
  artifact_kind: "thread_index"
  purpose: "Index of all {{folder_type}} in this implementation"
  tags:
    - "implementation"
    - "{{folder_type}}"
    - "thread_index"
---

# {{Title}}

*No {{folder_type}} yet.*

## Creating {{FolderType}}

Use the appropriate script or template to create {{folder_type}} in this folder.

---
*This index tracks all {{folder_type}} in the implementation.*
"""
    
    # Create indexes for each folder
    folders = [
        ("questions", "questions", "Questions"),
        ("answers", "answers", "Answers"),
        ("decisions", "decisions", "Decisions"),
        ("comments", "comments", "Comments")
    ]
    
    for folder, folder_type, title in folders:
        index_path = os.path.join(impl_path, folder, "THREAD_INDEX.md")
        content = index_template.replace("{{folder}}", folder).replace("{{folder_type}}", folder_type).replace("{{FolderType}}", folder_type.title()).replace("{{Title}}", title)
        with open(index_path, 'w', encoding='utf-8') as f:
            f.write(content)

def create_supporting_files(impl_path, prd_id, prd_slug):
    """Create supporting files like changelog.md and todo.md."""
    
    timestamp = generate_timestamp()
    
    # Create changelog.md
    changelog_content = f"""---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "{timestamp}"
  file_path_from_root: "lupo-docs/implementations/{prd_id}_{prd_slug}/changelog.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{prd_id}_{prd_slug}/changelog.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{prd_id}-{prd_slug}-changelog"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "{prd_id}_{prd_slug}"
  artifact_type: "implementation"
  artifact_kind: "changelog"
  purpose: "Changes to the implementation over time"
  tags:
    - "implementation"
    - "changelog"
    - "prd{prd_id}"
---

# Changelog

## [Unreleased]
- Initial scaffolding created

## [0.1.0] - {datetime.now(timezone.utc).strftime('%Y-%m-%d')}
- Implementation folder created
- Structure scaffolded per PRD 31
- Templates and indexes prepared

---

*Last Updated: {datetime.now(timezone.utc).strftime('%Y-%m-%d %H:%M:%S UTC')}*
"""
    
    with open(os.path.join(impl_path, "changelog.md"), 'w', encoding='utf-8') as f:
        f.write(changelog_content)
    
    # Create todo.md
    todo_content = f"""---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "{timestamp}"
  file_path_from_root: "lupo-docs/implementations/{prd_id}_{prd_slug}/todo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{prd_id}_{prd_slug}/todo.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{prd_id}-{prd_slug}-todo"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "{prd_id}_{prd_slug}"
  artifact_type: "implementation"
  artifact_kind: "todo"
  purpose: "Remaining tasks and TODO items for this implementation"
  tags:
    - "implementation"
    - "todo"
    - "tasks"
    - "prd{prd_id}"
---

# TODO

## High Priority
- [ ] Review PRD requirements thoroughly
- [ ] Create detailed implementation plan
- [ ] Set up development environment

## Medium Priority
- [ ] Identify potential questions and ambiguities
- [ ] Plan testing strategy
- [ ] Document implementation approach

## Low Priority
- [ ] Set up CI/CD pipeline
- [ ] Create deployment documentation
- [ ] Plan future enhancements

## Completed
- [x] Implementation folder scaffolded
- [x] Basic structure created
- [x] Templates copied

---

*Last Updated: {datetime.now(timezone.utc).strftime('%Y-%m-%d %H:%M:%S UTC')}*
"""
    
    with open(os.path.join(impl_path, "todo.md"), 'w', encoding='utf-8') as f:
        f.write(todo_content)

def update_implementations_index(base_path, impl_name, prd_id, prd_title):
    """Update the main implementations README.md index."""
    
    index_path = os.path.join(base_path, "README.md")
    
    if not os.path.exists(index_path):
        return
    
    with open(index_path, 'r') as f:
        content = f.read()
    
    # Find the table and add new entry
    table_start = content.find("| PRD | Implementation Folder")
    if table_start == -1:
        return
    
    # Find the end of the table
    table_end = content.find("\n##", table_start)
    if table_end == -1:
        table_end = len(content)
    
    # Add new entry before the table ends
    new_entry = f"| [{prd_id}_{prd_title}.md](../prd/{prd_id}_{prd_title}.md) | [{impl_name}/](./{impl_name}/) | 🟡 Planning | {datetime.now(timezone.utc).strftime('%Y-%m-%d')} |\n"
    
    # Find the last row in the table
    last_row = content.rfind("|", table_start, table_end)
    if last_row != -1:
        content = content[:last_row] + new_entry + content[last_row:]
    
    with open(index_path, 'w', encoding='utf-8') as f:
        f.write(content)

def main():
    parser = argparse.ArgumentParser(description="Scaffold implementation folder structure")
    parser.add_argument("--prd", required=True, help="PRD ID (e.g., 30)")
    parser.add_argument("--title", required=True, help="PRD title (use quotes for multi-word)")
    parser.add_argument("--slug", help="Custom slug (defaults to title with underscores)")
    
    args = parser.parse_args()
    
    # Generate slug if not provided
    if args.slug:
        prd_slug = args.slug
    else:
        prd_slug = args.title.lower().replace(" ", "_").replace("-", "_").replace("__", "_")
    
    # Get paths
    script_dir = get_script_dir()
    base_path = os.path.join(script_dir, "..", "lupo-docs", "implementations")
    template_dir = get_template_dir()
    
    # Validate template directory exists
    if not os.path.exists(template_dir):
        print(f"ERROR: Template directory not found: {template_dir}")
        sys.exit(1)
    
    # Create folder structure
    impl_path, impl_name = create_folder_structure(base_path, args.prd, args.title, prd_slug)
    
    # Copy templates
    copy_templates(impl_path, template_dir)
    
    # Create main files
    create_readme(impl_path, args.prd, args.title, prd_slug)
    create_thread_indexes(impl_path)
    create_supporting_files(impl_path, args.prd, prd_slug)
    
    # Update index
    update_implementations_index(base_path, impl_name, args.prd, args.title)
    
    print(f"✅ Implementation folder scaffolded successfully:")
    print(f"   Path: {impl_path}")
    print(f"   PRD: {args.prd}")
    print(f"   Title: {args.title}")
    print(f"   Slug: {prd_slug}")
    print(f"\n📝 Next steps:")
    print(f"1. Review the scaffolded structure")
    print(f"2. Update README.md with specific details")
    print(f"3. Begin implementation work")
    print(f"4. Use create_implementation_question.py for questions")

if __name__ == "__main__":
    main()
