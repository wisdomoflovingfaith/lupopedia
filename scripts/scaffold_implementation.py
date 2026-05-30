#!/usr/bin/env python3
"""
Implementation Folder Scaffolding Script

Automatically creates the complete implementation folder structure for a new PRD.
Constitutionally compliant - pure file system operations, no database dependencies.

The created directory is docs/implementations/{prd_id}_{prd_slug}/ — that string
MUST match the basename (without .md) of the canonical PRD under docs/prd/
(e.g. PRD file 36_rose_multi_persona_synthetic_dialog.md -> folder 36_rose_multi_persona_synthetic_dialog/).
See docs/prd/31_implementation_folder_guidelines.md and PRD 00 Section 5.8.

Usage:
    python scaffold_implementation.py --prd 30 --title "channel_usage_patterns"
    python scaffold_implementation.py --prd 31 --title "implementation_folder_guidelines"
    python scaffold_implementation.py add-status --impl 36_rose_multi_persona_synthetic_dialog --title "phase_b_kickoff"
    python scaffold_implementation.py add-status --impl 37 --title "memory_consolidation_milestone" --edge-type references
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
    return os.path.join(script_dir, "..", "docs", "implementations", "_template")

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
  file_path_from_root: "docs/implementations/{prd_id}_{prd_slug}/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/{prd_id}_{prd_slug}/README.md"
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
- **Channel**: [development](../../../channels/0/development/)
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
python scripts/create_implementation_question.py \\
  --implementation {prd_id}_{prd_slug} \\
  --level critical \\
  --title "your_question_here"
```

### Validating Structure
```bash
python scripts/validate_implementation_questions.py {prd_id}_{prd_slug}
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
        "docs/implementations/_template/",
        "docs/implementations/{}/".format(impl_name)
    )
    body = body.replace(
        "http://www.lupopedia.com/lupopedia/docs/implementations/_template/",
        "http://www.lupopedia.com/lupopedia/docs/implementations/{}/".format(impl_name)
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
  file_path_from_root: "docs/implementations/{impl}/status/STATUS.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/{impl}/status/STATUS.md"
  questions_toon: null
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

Scaffolded by **scripts/scaffold_implementation.py**. Replace this stub with a real **STATUS.md** per **PRD 31**.

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
  file_path_from_root: "docs/implementations/{prd_id}_{prd_slug}/changelog.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/{prd_id}_{prd_slug}/changelog.md"
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
  file_path_from_root: "docs/implementations/{prd_id}_{prd_slug}/todo.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/{prd_id}_{prd_slug}/todo.md"
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
    Append a row to docs/implementations/README.md if missing.
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


def get_implementations_base_path():
    script_dir = get_script_dir()
    return os.path.normpath(os.path.join(script_dir, "..", "docs", "implementations"))


def resolve_implementation_dir(base_path, impl_arg):
    """
    Resolve --impl to (impl_name, impl_path).
    Accepts full folder name (e.g. 36_rose_multi_persona_synthetic_dialog) or numeric PRD id (e.g. 37 -> single 37_* match).
    """
    impl_arg = impl_arg.strip().strip("/\\")
    if not impl_arg:
        sys.stderr.write("ERROR: --impl is empty\n")
        sys.exit(1)

    direct = os.path.join(base_path, impl_arg)
    if os.path.isdir(direct):
        return impl_arg, direct

    if re.match(r"^\d+$", impl_arg):
        matches = []
        for name in os.listdir(base_path):
            p = os.path.join(base_path, name)
            if os.path.isdir(p) and name.startswith(impl_arg + "_"):
                matches.append(name)
        matches.sort()
        if len(matches) == 1:
            n = matches[0]
            return n, os.path.join(base_path, n)
        if len(matches) == 0:
            sys.stderr.write(
                "ERROR: No implementation folder starting with {0}_ under {1}\n".format(
                    impl_arg, base_path
                )
            )
            sys.exit(1)
        sys.stderr.write(
            "ERROR: Multiple folders match PRD id {0}: {1}\n".format(impl_arg, ", ".join(matches))
        )
        sys.stderr.write("Use full folder name with --impl.\n")
        sys.exit(1)

    sys.stderr.write("ERROR: Implementation folder not found: {0}\n".format(direct))
    sys.exit(1)


def slugify_status_title(title):
    s = title.strip().lower().replace(" ", "_").replace("-", "_")
    s = re.sub(r"[^a-z0-9_]+", "_", s)
    s = re.sub(r"_+", "_", s).strip("_")
    if not s:
        sys.stderr.write("ERROR: --title produced empty slug\n")
        sys.exit(1)
    return s


def read_header_utc_from_file(path, max_bytes=8000):
    """Return 14-digit when_updated from first YAML block, or None.

    NOTE: last_modified_utc was renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6.
    The old last_modified_utc fallback is kept for backward compat with pre-v4.0.99 headers.
    Remove the fallback after Phase 3 corpus sweep.
    """
    try:
        with open(path, "r", encoding="utf-8") as f:
            chunk = f.read(max_bytes)
    except EnvironmentError as e:
        sys.stderr.write("WARNING: could not read {0}: {1}\n".format(path, e))
        return None
    m = re.search(r'when_updated:\s*"(\d{14})"', chunk)
    if m:
        return m.group(1)
    m = re.search(r"when_updated:\s*'(\d{14})'", chunk)
    if m:
        return m.group(1)
    # Backward compat: legacy last_modified_utc (REMOVE after Phase 3 sweep)
    m = re.search(r'last_modified_utc:\s*"(\d{14})"', chunk)
    if m:
        return m.group(1)
    m = re.search(r"last_modified_utc:\s*'(\d{14})'", chunk)
    if m:
        return m.group(1)
    return None


def filename_utc_sort_key(filename):
    """14-digit string from leading YYYYMMDD_HHIISS_ or zeros."""
    m = re.match(r"^(\d{8})_(\d{6})_", filename)
    if m:
        return m.group(1) + m.group(2)
    return "00000000000000"


def status_artifact_sort_tuple(path):
    """Sort key (filename_ts, header_ts) for choosing latest prior artifact."""
    base = os.path.basename(path)
    fts = filename_utc_sort_key(base)
    hts = read_header_utc_from_file(path) or "00000000000000"
    return (fts, hts)


def list_status_markdown_files(status_dir):
    """All .md in status/ except THREAD_INDEX.md."""
    out = []
    if not os.path.isdir(status_dir):
        return out
    for name in os.listdir(status_dir):
        if not name.endswith(".md"):
            continue
        if name == "THREAD_INDEX.md":
            continue
        p = os.path.join(status_dir, name)
        if os.path.isfile(p):
            out.append(p)
    return out


def pick_previous_status_artifact(status_dir, exclude_basename=None):
    """Choose latest existing status .md by freshness tuple."""
    paths = list_status_markdown_files(status_dir)
    if exclude_basename:
        paths = [p for p in paths if os.path.basename(p) != exclude_basename]
    if not paths:
        return None
    paths.sort(key=status_artifact_sort_tuple)
    return paths[-1]


def prompt_edge_type(previous_basename):
    """Interactive choice; default references."""
    print("Previous status artifact detected: {0}".format(previous_basename))
    print("  [1] references — continues prior truth (default)")
    print("  [2] supersedes — replaces prior truth")
    print("  [3] none — no outbound edge in header")
    try:
        raw = input("Choice [1-3] (Enter=1): ").strip()
    except EnvironmentError:
        return "references"
    if raw in ("", "1"):
        return "references"
    if raw == "2":
        return "supersedes"
    if raw == "3":
        return "none"
    return "references"


def append_status_thread_index(index_path, filename, summary):
    """Append a table row to status/THREAD_INDEX.md (or replace *(none yet)* placeholder)."""
    summary_clean = summary.replace("|", "/")
    new_row = "| [{0}]({0}) | {1} |\n".format(filename, summary_clean)
    if os.path.isfile(index_path):
        with open(index_path, "r", encoding="utf-8") as f:
            content = f.read()
    else:
        content = ""

    none_line = "| *(none yet)* | |\n"
    if none_line in content:
        content = content.replace(none_line, new_row, 1)
        with open(index_path, "w", encoding="utf-8") as f:
            f.write(content)
        return

    lines = content.splitlines()
    last_data = -1
    for i, line in enumerate(lines):
        if not line.startswith("|"):
            continue
        if "---" in line and line.count("-") > 3:
            continue
        if "Artifact" in line and "Purpose" in line:
            continue
        if "[" in line and "](" in line:
            last_data = i
    if last_data >= 0:
        lines.insert(last_data + 1, "| [{0}]({0}) | {1} |".format(filename, summary_clean))
        out = "\n".join(lines) + "\n"
        with open(index_path, "w", encoding="utf-8") as f:
            f.write(out)
        return

    # Minimal table if file missing or malformed
    stub = (
        "# THREAD_INDEX — status\n\n"
        "| Artifact | Purpose |\n"
        "|----------|---------|\n"
        "{0}"
    ).format(new_row)
    with open(index_path, "w", encoding="utf-8") as f:
        f.write(stub)


def run_add_status(argv):
    parser = argparse.ArgumentParser(
        prog="scaffold_implementation.py add-status",
        description="Create a dated STATUS artifact under implementations/<impl>/status/ and update THREAD_INDEX.md",
    )
    parser.add_argument(
        "--impl",
        required=True,
        help="Implementation folder name (prd_file_stem) or numeric PRD id if unambiguous (e.g. 37)",
    )
    parser.add_argument(
        "--title",
        required=True,
        help="Short slug for filename (e.g. phase_b_kickoff)",
    )
    parser.add_argument(
        "--edge-type",
        choices=["references", "supersedes", "none"],
        default=None,
        help="Link to previous status artifact (default: prompt if TTY, else references)",
    )
    parser.add_argument(
        "--non-interactive",
        action="store_true",
        help="Never prompt; use --edge-type or default references when prior exists",
    )
    args = parser.parse_args(argv)

    base_path = get_implementations_base_path()
    impl_name, impl_path = resolve_implementation_dir(base_path, args.impl)
    status_dir = os.path.join(impl_path, "status")
    if not os.path.isdir(status_dir):
        sys.stderr.write("ERROR: missing status/ under {0}\n".format(impl_path))
        sys.exit(1)

    slug = slugify_status_title(args.title)
    ts = generate_timestamp()
    fname = "{0}_STATUS_{1}.md".format(ts, slug)
    out_path = os.path.join(status_dir, fname)

    previous_path = pick_previous_status_artifact(status_dir, exclude_basename=fname)
    previous_base = os.path.basename(previous_path) if previous_path else None

    edge_type = args.edge_type
    if previous_base and edge_type is None:
        if args.non_interactive or not sys.stdin.isatty():
            edge_type = "references"
        else:
            edge_type = prompt_edge_type(previous_base)

    if not previous_base:
        edge_type = None

    rel_prev = (
        "docs/implementations/{0}/status/{1}".format(impl_name, previous_base)
        if previous_base
        else None
    )

    file_path_from_root = "docs/implementations/{0}/status/{1}".format(impl_name, fname)
    web_path = "http://www.lupopedia.com/lupopedia/" + file_path_from_root.replace("\\", "/")

    edges_yaml = ""
    if previous_base and edge_type and edge_type != "none":
        reason = (
            "Incremental status; prior artifact remains valid"
            if edge_type == "references"
            else "This status replaces prior truth for this thread"
        )
        edges_yaml = (
            "lupopedia.edges:\n"
            "  outbound_edges:\n"
            "    - to: \"{0}\"\n"
            "      type: {1}\n"
            "      weight: 1.0\n"
            "      reason: \"{2}\"\n"
        ).format(rel_prev, edge_type, reason)

    header_edges = edges_yaml if edges_yaml else ""

    body = """---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "{ts}"
  file_path_from_root: "{fp}"
  web_path: "{wp}"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  artifact_type: documentation
  artifact_kind: implementation_status
  purpose: "{purpose}"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
{edges_block}lupopedia.footer:
  last_verified: "{day}"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
---

# file: {impl} status — {fname}

# STATUS: {title_display}

Dated status artifact created by **`scripts/scaffold_implementation.py add-status`**.

## Summary

(TODO: one paragraph on current state.)

This output complies with Lupopedia Constitutional Root Rules.
""".format(
        ts=ts,
        fp=file_path_from_root,
        wp=web_path,
        purpose=args.title.replace('"', "'"),
        day=ts[:8],
        edges_block=header_edges,
        impl=impl_name,
        fname=fname,
        title_display=args.title,
    )

    if os.path.isfile(out_path):
        sys.stderr.write("ERROR: file already exists: {0}\n".format(out_path))
        sys.exit(1)

    with open(out_path, "w", encoding="utf-8") as f:
        f.write(body)

    index_path = os.path.join(status_dir, "THREAD_INDEX.md")
    append_status_thread_index(index_path, fname, args.title)

    print("OK add-status:")
    print("   Wrote: {0}".format(out_path))
    print("   Updated: {0}".format(index_path))
    if previous_base and edge_type and edge_type != "none":
        print("   Edge: {0} -> {1}".format(edge_type, previous_base))
    elif previous_base:
        print("   No outbound edge (none or skipped)")
    else:
        print("   No prior status artifact; no edge")


def main():
    if len(sys.argv) > 1 and sys.argv[1] == "add-status":
        run_add_status(sys.argv[2:])
        return

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
    base_path = os.path.join(script_dir, "..", "docs", "implementations")
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
    print("3. Run python bin/tick.py before editing LUPOPEDIA header timestamps")
    print("4. Use create_implementation_question.py for questions (if present)")

if __name__ == "__main__":
    main()
