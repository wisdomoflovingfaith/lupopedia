#!/usr/bin/env python3
"""
Implementation Folder Scaffolding Script

Automatically creates the complete implementation folder structure for a new PRD.
Constitutionally compliant - pure file system operations, no database dependencies.

The created directory is lupo-docs/implementations/{prd_id}_{prd_slug}/ — that string
MUST match the basename (without .md) of the canonical PRD under lupo-docs/prd/
(e.g. PRD file 36_rose_multi_persona_synthetic_dialog.md -> folder 36_rose_multi_persona_synthetic_dialog/).
See lupo-docs/prd/31_implementation_folder_guidelines.md and PRD 00 Section 5.8.

Usage:
    python scaffold_implementation.py --prd 30 --title "channel_usage_patterns"
    python scaffold_implementation.py --prd 31 --title "implementation_folder_guidelines"
"""

import argparse
import os
import re
import sys
import shutil
from datetime import datetime, timezone

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
    
    # Create subfolders (PRD 31 + constitution §5.8)
    subfolders = [
        "questions/critical",
        "questions/optimization",
        "questions/clarification",
        "answers",
        "decisions",
        "comments",
        "status",
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
├── status/                      # STATUS.md + THREAD_INDEX (PRD 31)
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

def _substitute_thread_index_from_template(body, impl_name, timestamp):
    """
    Rewrite _template paths and metadata for a concrete implementation folder.
    """
    body = body.replace(
        "lupo-docs/implementations/_template/",
        "lupo-docs/implementations/{}/".format(impl_name)
    )
    body = body.replace(
        "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/_template/",
        "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{}/".format(impl_name)
    )
    body = body.replace('parent_prd: "_template"', 'parent_prd: "{}"'.format(impl_name))
    body = re.sub(
        r'^(\s*when_updated:\s*)"[0-9]{14}"',
        r'\1"{}"'.format(timestamp),
        body,
        count=1,
        flags=re.MULTILINE
    )
    # Distinct thread_id per implementation (avoid collisions in metadata)
    body = body.replace(
        'thread_id: "implementation-questions-index"',
        'thread_id: "{}-questions-index"'.format(impl_name)
    )
    body = body.replace(
        'thread_id: "implementation-answers-index"',
        'thread_id: "{}-answers-index"'.format(impl_name)
    )
    body = body.replace(
        'thread_id: "implementation-comments-index"',
        'thread_id: "{}-comments-index"'.format(impl_name)
    )
    return body


def create_thread_indexes(impl_path, impl_name, template_dir):
    """
    Create THREAD_INDEX.md under questions/, answers/, comments/ from _template/ (PRD 31).
    decisions/ gets a minimal index (no full template in _template).
    status/ gets THREAD_INDEX.md + stub STATUS.md.
    """
    timestamp = generate_timestamp()

    for subfolder in ("questions", "answers", "comments"):
        src = os.path.join(template_dir, subfolder, "THREAD_INDEX.md")
        dst = os.path.join(impl_path, subfolder, "THREAD_INDEX.md")
        if not os.path.isfile(src):
            sys.stderr.write("WARNING: missing template: {}\n".format(src))
            continue
        with open(src, "r", encoding="utf-8") as f:
            body = _substitute_thread_index_from_template(f.read(), impl_name, timestamp)
        with open(dst, "w", encoding="utf-8") as f:
            f.write(body)

    # decisions/ — minimal table (matches common implementation mirrors)
    decisions_dst = os.path.join(impl_path, "decisions", "THREAD_INDEX.md")
    with open(decisions_dst, "w", encoding="utf-8") as f:
        f.write("# THREAD_INDEX — {} / decisions\n\n".format(impl_name))
        f.write("| Artifact | Summary |\n")
        f.write("|----------|---------|\n")
        f.write("| *(none yet)* | |\n")

    # status/ (folder created in create_folder_structure)
    status_body = """---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "{ts}"
  file_path_from_root: "lupo-docs/implementations/{impl}/status/STATUS.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{impl}/status/STATUS.md"
  last_modified_utc: "{ts}"
  federation_node_id: 0
  channel_id: 42
  artifact_type: documentation
  artifact_kind: implementation_status
  purpose: "Implementation completion vs planned work for {impl}"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
lupopedia.footer:
  last_verified: "{day}"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
---

# file: {impl} status — implementation mirror

# Status: {impl}

Scaffolded by **lupo-scripts/scaffold_implementation.py**. Replace this stub with a real **STATUS.md** per **PRD 31**.

## Completion (high level)

| Area | State | Notes |
|------|-------|-------|
| Scaffold | **Done** | Folders, THREAD_INDEX files, templates copied |

## What is next

- Link the canonical PRD and record blockers under **decisions/** and **questions/**.

This output complies with Lupopedia Constitutional Root Rules.
""".format(
        ts=timestamp,
        impl=impl_name,
        day=timestamp[:8],
    )
    with open(os.path.join(impl_path, "status", "STATUS.md"), "w", encoding="utf-8") as f:
        f.write(status_body)

    status_index = os.path.join(impl_path, "status", "THREAD_INDEX.md")
    with open(status_index, "w", encoding="utf-8") as f:
        f.write("# THREAD_INDEX — {} / status\n\n".format(impl_name))
        f.write("| Artifact | Purpose |\n")
        f.write("|----------|---------|\n")
        f.write("| [STATUS.md](STATUS.md) | Current completion and next steps |\n")

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

def update_implementations_index(base_path, impl_name, prd_id):
    """
    Append a row to lupo-docs/implementations/README.md if missing.
    Table shape: | Folder | PRD | Notes |
    """
    index_path = os.path.join(base_path, "README.md")
    if not os.path.isfile(index_path):
        return
    with open(index_path, "r", encoding="utf-8") as f:
        content = f.read()
    if impl_name in content:
        return
    marker = "\n## Template\n"
    if marker not in content:
        return
    new_row = "| **{impl}** | [PRD {pid}](../prd/{impl}.md) | Scaffolded — edit Notes |\n".format(
        impl=impl_name, pid=prd_id
    )
    content = content.replace(marker, "\n" + new_row + marker, 1)
    with open(index_path, "w", encoding="utf-8") as f:
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
    create_thread_indexes(impl_path, impl_name, template_dir)
    create_supporting_files(impl_path, args.prd, prd_slug)
    
    # Update index
    update_implementations_index(base_path, impl_name, args.prd)
    
    print("OK Implementation folder scaffolded successfully:")
    print(f"   Path: {impl_path}")
    print(f"   PRD: {args.prd}")
    print(f"   Title: {args.title}")
    print(f"   Slug: {prd_slug}")
    print("\nCreated THREAD_INDEX.md under: questions/, answers/, comments/, decisions/, status/")
    print("Copied question/answer templates into templates/ when _template/ provides them.")
    print("\nNext steps:")
    print("1. Review the scaffolded structure and STATUS.md stub")
    print("2. Update README.md and implementations/README.md row with specific details")
    print("3. Run python lupo-bin/tick.py before editing LUPOPEDIA header timestamps")
    print("4. Use create_implementation_question.py for questions (if present)")

if __name__ == "__main__":
    main()
