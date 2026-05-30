#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "scripts/validate_agent_name.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/validate_agent_name.py"
#   status: "complete"
#   when_updated: "20260418153241"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/validate-agent-name.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/validate-agent-name"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   content_slug: "validate-agent-name"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "validate_agent_name.py -- agent pack slug and agent.json key validator"
#   summary: "Enforces DISALLOWED_AGENT_NAMES.md: exact set, regex patterns, charset; optional scan of agents for duplicates and collisions."
# ---------------------------------------------------------------------
"""
CLI validator for new agent pack slugs and agent.json agent_key/slug fields.

Normative list: docs/doctrine/DISALLOWED_AGENT_NAMES.md (keep in sync with DISALLOWED_EXACT below).
"""

from __future__ import print_function

import argparse
import json
import os
import re
import sys

# Must match docs/doctrine/DISALLOWED_AGENT_NAMES.md section 2 (exact names, lowercase).
DISALLOWED_EXACT = frozenset(
    [
        "root",
        "system",
        "admin",
        "superuser",
        "kernel",
        "meta",
        "self",
        "this",
        "parent",
        "agent",
        "ai",
        "bot",
        "assistant",
        "wolfie",
        "lilith",
        "thoth",
        "rose",
        "agape",
        "carmen",
        "hermes",
        "anubis",
        "kairos",
        "vish",
        "hephaestus",
        "iris",
        "asclepius",
        "cursor",
        "antigravity",
        "antigravity-ide",
        "vscode",
        "vscode-ide",
        "windsurf",
        "kiro",
        "zed",
        "trae",
        "warp",
        "cascade",
        "castcade",
        "claude",
        "gemini",
        "grok",
        "auggie",
        "chatgpt",
        "copilot",
    ]
)

# (regex, human reason)
DISALLOWED_PATTERNS = [
    (re.compile(r"^_"), "leading underscore (hidden convention)"),
    (re.compile(r"^\."), "leading dot (hidden file convention)"),
    (re.compile(r"[/\\\\]"), "path separator (security risk)"),
    (re.compile(r"\.\."), "parent directory traversal"),
]

ALLOWED_NAME_RE = re.compile(r"^[a-z0-9-]+$")

# Skip underscore-prefixed utility trees (e.g. _shared prompts, template stubs).
SKIP_DIR_PREFIX = "_"


def validate_slug_structure(name):
    """
    Structural rules only: lowercase, charset, dangerous patterns.
    Used when scanning an existing tree (reserved names are allowed on canonical dirs).
    """
    if name is None:
        return False, "name is required"
    name = name.strip()
    if name == "":
        return False, "name is empty"
    if name != name.lower():
        return False, "agent name must be lowercase"
    if not ALLOWED_NAME_RE.match(name):
        return False, "agent name must contain only a-z, 0-9, and hyphen (no spaces)"
    for rx, reason in DISALLOWED_PATTERNS:
        if rx.search(name):
            return False, "disallowed pattern: " + reason
    return True, "valid"


def validate_agent_name(name):
    """
    Full rules for a *new* agent registration: structure plus reserved/disallowed exact set.

    Returns (ok, message) where ok is bool and message is English explanation.
    """
    ok, msg = validate_slug_structure(name)
    if not ok:
        return ok, msg
    if name in DISALLOWED_EXACT:
        return False, "agent name is reserved or disallowed: " + name
    return True, "valid"


def _repo_root_from_script():
    return os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))


def _load_agent_json_key(root, rel_path):
    path = os.path.join(root, rel_path)
    if not os.path.isfile(path):
        return None, None
    try:
        with open(path, "r") as f:
            data = json.load(f)
    except (ValueError, IOError):
        return None, "invalid_json"
    if not isinstance(data, dict):
        return None, "not_object"
    key = data.get("agent_key")
    slug = data.get("slug")
    if key is not None and isinstance(key, str):
        return key.strip(), None
    if slug is not None and isinstance(slug, str):
        return slug.strip(), None
    return None, "missing_agent_key"


def scan_root(repo_root, agents_subdir):
    """
    Scan top-level directories under agents: structural slug rules on dirname,
    duplicate agent_key/slug across packs. Does not reject reserved canonical names
    on existing directories (use validate_agent_name() for new registrations).
    """
    base = os.path.join(repo_root, agents_subdir)
    if not os.path.isdir(base):
        print("SCAN: missing directory: " + base, file=sys.stderr)
        return 2

    key_to_dirs = {}
    errors = []
    warnings = []

    for entry in sorted(os.listdir(base)):
        path = os.path.join(base, entry)
        if not os.path.isdir(path):
            continue
        if entry.startswith(SKIP_DIR_PREFIX):
            continue

        dirname = entry
        ok, msg = validate_slug_structure(dirname)
        if not ok:
            errors.append("DIR " + dirname + ": " + msg)

        rel = os.path.join(agents_subdir, entry, "agent.json")
        ak, err = _load_agent_json_key(repo_root, rel)
        if err == "invalid_json":
            warnings.append(rel + ": could not parse JSON")
            continue
        if err == "missing_agent_key":
            warnings.append(rel + ": missing agent_key and slug")
            continue
        if ak is None:
            continue
        key_lower = ak.lower()
        key_to_dirs.setdefault(key_lower, []).append(entry)

    dup = [(k, v) for k, v in key_to_dirs.items() if len(v) > 1]
    for k, dirs in dup:
        errors.append("DUPLICATE agent_key/slug lower=%r in dirs: %s" % (k, ", ".join(sorted(dirs))))

    for w in warnings:
        print("WARN: " + w)
    for e in errors:
        print("ERR: " + e)

    if errors:
        return 1
    return 0


def main():
    parser = argparse.ArgumentParser(description="Validate agent pack slug / agent_key (DISALLOWED_AGENT_NAMES).")
    parser.add_argument("name", nargs="?", help="Proposed agent directory name or agent_key")
    parser.add_argument("--file", metavar="PATH", help="Validate agent_key (or slug) inside this agent.json")
    parser.add_argument("--scan-root", action="store_true", help="Scan agents top-level dirs for duplicates and disallowed names")
    parser.add_argument(
        "--agents-dir",
        default="agents",
        help="Relative to repo root for --scan-root (default: agents)",
    )
    args = parser.parse_args()
    repo_root = _repo_root_from_script()

    if args.scan_root:
        code = scan_root(repo_root, args.agents_dir)
        sys.exit(code)

    name = None
    if args.file:
        p = args.file
        if not os.path.isabs(p):
            p = os.path.join(repo_root, p)
        p = os.path.abspath(p)
        try:
            rel = os.path.relpath(p, repo_root)
        except ValueError:
            print("FAIL: file path must be under repo root: " + p)
            sys.exit(1)
        ak, err = _load_agent_json_key(repo_root, rel)
        if err == "invalid_json":
            print("FAIL: could not parse JSON: " + p)
            sys.exit(1)
        if err == "not_object":
            print("FAIL: JSON root must be an object: " + p)
            sys.exit(1)
        if err == "missing_agent_key":
            print("FAIL: missing agent_key and slug: " + p)
            sys.exit(1)
        name = ak
    elif args.name:
        name = args.name
    else:
        parser.print_help()
        sys.exit(2)

    ok, msg = validate_agent_name(name)
    if ok:
        print("OK: " + msg + " (" + name + ")")
        sys.exit(0)
    print("FAIL: " + msg)
    sys.exit(1)


if __name__ == "__main__":
    main()
