#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/apply_namespace_to_table_docs.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Apply or normalize namespace in table doc LUPOPEDIA_HEADERS. Uses path/basename
to infer namespace per approved taxonomy. Run from repo root.
"""
from __future__ import print_function

import os
import re
import sys

APPROVED = frozenset(['auth', 'channels', 'core', 'content', 'analytics', 'federation', 'governance', 'integration', 'legacy'])
# Invalid value -> approved
NORMALIZE = {
    'channel': 'channels', 'world': 'core', 'agent': 'core', 'collection': 'content',
    'org': 'governance', 'dialog': 'content', 'session': 'auth',
    'lupopedia': 'core', 'projects': 'core',
}


def _infer_namespace(rel_path):
    """Infer namespace from path/table name. Returns approved value."""
    base = os.path.basename(rel_path).lower().replace('.md', '')
    if base.startswith('lupo_auth') or base.startswith('lupo_session') or 'anubis' in base:
        return 'auth'
    if base.startswith('lupo_channel'):
        return 'channels'
    if base.startswith('lupo_analytics') or base.startswith('lupo_cip_'):
        return 'analytics'
    if base.startswith('lupo_federation'):
        return 'federation'
    if base.startswith('lupo_audit') or base.startswith('lupo_bans') or base.startswith('lupo_gov'):
        return 'governance'
    if base.startswith('lupo_content') or base.startswith('lupo_collection') or base.startswith('lupo_dialog') or base.startswith('lupo_artifact') or base.startswith('lupo_context'):
        return 'content'
    if base.startswith('lupo_api'):
        return 'integration'
    if 'crafty' in base or 'livehelp' in base or 'migration' in base:
        return 'legacy'
    return 'core'


def _extract_namespace_from_block(block_text):
    in_headers = False
    key_val = re.compile(r"^(\s*)(\S+):\s*(.*)$")
    for line in block_text.splitlines():
        m = key_val.match(line)
        if not m:
            continue
        indent, key, val = m.group(1), m.group(2), m.group(3)
        if key == "lupopedia.headers":
            in_headers = True
            continue
        if in_headers:
            if indent == "" or (len(indent) < 2 and key.startswith("lupopedia.")):
                break
            if key == "namespace":
                return val.strip().strip('"\'')
    return None


def _apply_namespace(content, rel_path, add_or_replace):
    """Add or replace namespace in first YAML block. add_or_replace: 'add' | 'replace'."""
    blocks = re.split(r"(\r?\n---\r?\n)", content)
    if len(blocks) < 2:
        return content, False
    # blocks[0] = before first ---, blocks[1] = \n---\n, blocks[2] = first block
    first_block = blocks[2] if len(blocks) > 2 else ""
    if "lupopedia.headers" not in first_block:
        return content, False

    ns = _infer_namespace(rel_path)
    current = _extract_namespace_from_block(first_block)
    if add_or_replace == 'replace' and current and current in APPROVED:
        return content, False
    if add_or_replace == 'replace' and current:
        ns = NORMALIZE.get(current, ns)
    elif add_or_replace == 'add' and current and current in APPROVED:
        return content, False

    lines = first_block.splitlines()
    new_lines = []
    inserted = False
    in_headers = False
    for line in lines:
        new_lines.append(line)
        m = re.match(r"^(\s*)(\S+):\s*(.*)$", line)
        if m:
            indent, key = m.group(1), m.group(2)
            if key == "lupopedia.headers":
                in_headers = True
                continue
            if in_headers:
                if key == "namespace":
                    new_lines[-1] = indent + "namespace: \"" + ns + "\""
                    inserted = True
                if indent == "" or (len(indent) < 2 and key.startswith("lupopedia.")):
                    in_headers = False
                elif key == "purpose" and not inserted and add_or_replace == 'add':
                    new_lines.append("  namespace: \"" + ns + "\"")
                    inserted = True

    if not inserted and add_or_replace == 'add':
        for i, line in enumerate(lines):
            if re.match(r"^\s{2,}\w+:", line):
                prev = "\n".join(lines[:i])
                if "lupopedia.headers" in prev:
                    new_lines = lines[:i+1] + ["  namespace: \"" + ns + "\""] + lines[i+1:]
                    inserted = True
                    break
        if not inserted:
            new_lines = lines
    elif not inserted:
        new_lines = lines

    new_block = "\n".join(new_lines)
    new_content = blocks[0] + blocks[1] + new_block
    if len(blocks) > 3:
        new_content += "".join(blocks[3:])
    return new_content, inserted


def main():
    root_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
    os.chdir(root_dir)
    tables_dir = "lupo-docs/database/lupopedia/tables"
    updated = 0
    for dirpath, _dnames, fnames in os.walk(os.path.join(root_dir, tables_dir)):
        for name in fnames:
            if not name.lower().endswith(".md") or "_validator_fixtures" in dirpath:
                continue
            full = os.path.join(dirpath, name)
            rel = os.path.relpath(full, root_dir).replace("\\", "/")
            try:
                content = open(full, "r", encoding="utf-8", errors="replace").read()
            except IOError:
                continue
            current = _extract_namespace_from_block(re.split(r"\r?\n---\r?\n", content)[2] if len(re.split(r"\r?\n---\r?\n", content)) > 2 else "")
            if current and current not in APPROVED:
                new_content, done = _apply_namespace(content, rel, 'replace')
            elif not current or current == "":
                new_content, done = _apply_namespace(content, rel, 'add')
            else:
                continue
            if done:
                with open(full, "w", encoding="utf-8") as f:
                    f.write(new_content)
                updated += 1
                print(rel)
    print("Updated %d files." % updated)
    return 0


if __name__ == "__main__":
    sys.exit(main())