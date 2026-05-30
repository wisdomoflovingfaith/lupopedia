#!/usr/bin/env python3
"""
Repair malformed LUPOPEDIA headers in docs/doctrine/**/*.md so that
apply_doctrine_prd_lineage.py can inject PRD edges.

Handles:
  - no_leading_delimiter: first line is not --- (strip leading junk or prepend full block)
  - fake_opening: line 1 is --- but line 2 is not lupopedia.headers: (markdown HR misuse)
  - no_lupopedia_footer: no ^lupopedia.footer: in file

UTC timestamps: read from bin/temporal_anchor.json (same source as tick.py anchor).

Usage:
  python scripts/fix_doctrine_headers.py --dry-run
  python scripts/fix_doctrine_headers.py --apply
"""
from __future__ import print_function

import argparse
import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DOCTRINE = ROOT / "docs" / "doctrine"
ANCHOR = ROOT / "bin" / "temporal_anchor.json"


def load_anchor_utc():
    """14-digit UTC string from temporal_anchor.json; exit if missing."""
    try:
        data = json.loads(ANCHOR.read_text(encoding="utf-8"))
        u = data.get("current_utc")
        if u and len(str(u)) == 14 and str(u).isdigit():
            return str(u)
    except Exception:
        pass
    print(
        "ERROR: Run: python bin/tick.py  (then retry)",
        file=sys.stderr,
    )
    sys.exit(1)


def is_valid_opening_frontmatter(text):
    """True if line 1 is --- and line 2 starts with lupopedia.headers:"""
    lines = text.splitlines()
    if not lines or lines[0].strip() != "---":
        return False
    if len(lines) < 2:
        return False
    return lines[1].lstrip().startswith("lupopedia.headers:")


def has_lupopedia_footer(text):
    return bool(re.search(r"^lupopedia\.footer:", text, re.MULTILINE))


def find_first_standalone_delimiter_line_index(lines):
    """Index of first line that is exactly --- (after line 0)."""
    for i in range(1, min(len(lines), 2000)):
        if lines[i].strip() == "---":
            return i
    return None


def extract_body_after_fake_hr_block(text):
    """
    If file starts with --- but line 2 is not lupopedia.headers, treat first ---...---
    as a false block and return body after second ---.
    """
    lines = text.splitlines(True)
    if not lines or lines[0].strip() != "---":
        return None
    if len(lines) >= 2 and lines[1].lstrip().startswith("lupopedia.headers:"):
        return None
    idx = find_first_standalone_delimiter_line_index(lines)
    if idx is None:
        return None
    return "".join(lines[idx + 1 :])


def strip_leading_until_first_yaml_start(text):
    """
    If file does not start with ---, find first line that is exactly ---
    and return text from that line onward.
    """
    lines = text.splitlines(True)
    for i, line in enumerate(lines):
        if line.strip() == "---":
            return "".join(lines[i:])
    return None


def build_footer_block(utc, include_edges=True):
    """YAML fragment: optional lupopedia.edges stub + lupopedia.footer."""
    fb = []
    if include_edges:
        fb.append("lupopedia.edges:")
        fb.append("  outbound_edges: []")
    fb.append("lupopedia.footer:")
    fb.append('  last_verified: "%s"' % utc)
    fb.append("  verified_by:")
    fb.append("    identity_type: actor")
    fb.append("    actor_id: 2")
    fb.append('    name: "lilith"')
    fb.append("  verified_via:")
    fb.append('    type: "audit"')
    fb.append('    script: "fix_doctrine_headers"')
    fb.append("  next_action:")
    fb.append('    - "Run: python scripts/apply_doctrine_prd_lineage.py --apply"')
    return "\n".join(fb)


def build_minimal_header(rel_posix, utc, title_slug):
    """Full opening YAML through closing --- (no trailing body)."""
    fp = rel_posix.replace("\\", "/")
    web = "http://www.lupopedia.com/lupopedia/" + fp
    purpose = title_slug.replace("_", " ").replace(".md", "")[:200]
    purpose = purpose.replace('"', "'")
    edges_and_footer = build_footer_block(utc, include_edges=True)
    return (
        "---\n"
        "lupopedia.headers:\n"
        "  header_format_version: 2\n"
        "  lupopedia.schema: doctrine\n"
        '  file_path_from_root: "%s"\n'
        '  web_path: "%s"\n'
        '  questions_toon: null\n'  # was last_modified_utc (renamed PRD 16 v4.0.99)
        '  when_updated: "%s"\n'
        "  federation_node_id: 0\n"
        "  channel_id: 42\n"
        '  thread_id: "doctrine-header-repair"\n'
        "  actor_id: 102\n"
        '  actor_name: "cursor"\n'
        '  delegation_chain: "cursor:root"\n'
        '  artifact_type: "doctrine"\n'
        '  artifact_kind: "reference"\n'
        '  purpose: "%s"\n'
        "  status: active\n"
        "  tags:\n"
        '    - "doctrine"\n'
        '    - "header_repair"\n'
        "%s\n"
        "---\n"
        % (fp, web, utc, utc, purpose, edges_and_footer)
    )


def insert_footer_only(text, utc):
    """
    Insert lupopedia.edges (if missing) and lupopedia.footer before first closing ---
    after opening ---, or before # file: if no closing ---.
    """
    if has_lupopedia_footer(text):
        return text, "already_has_footer"

    text = text.lstrip("\ufeff")
    nl = text.find("\n")
    if nl < 0:
        return None, "not_opening_delimiter"
    if text[:nl].strip() != "---":
        return None, "not_opening_delimiter"

    after_open = nl + 1
    m = re.search(r"\n---\s*\n", text[after_open:])
    if m:
        pos = after_open + m.start()
        inner = text[after_open:pos]
        has_edges = bool(re.search(r"^lupopedia\.edges:", inner, re.MULTILINE))
        block = build_footer_block(utc, include_edges=not has_edges)
        return text[:pos] + block + "\n" + text[pos:], "inserted_footer"

    m2 = re.search(r"\n# file:", text[after_open:])
    if m2:
        pos = after_open + m2.start()
        inner = text[after_open:pos]
        has_edges = bool(re.search(r"^lupopedia\.edges:", inner, re.MULTILINE))
        block = build_footer_block(utc, include_edges=not has_edges)
        return text[:pos] + block + "\n" + text[pos:], "inserted_footer"

    return None, "no_closing_delimiter_or_file_line"


def classify_issue(path, text):
    """Return issue code for logging."""
    rel = path.relative_to(ROOT).as_posix()
    if not text.lstrip().startswith("---"):
        return "no_leading_delimiter"
    if not is_valid_opening_frontmatter(text):
        return "fake_opening_or_invalid_yaml"
    if not has_lupopedia_footer(text):
        return "no_lupopedia_footer"
    return "ok"


def fix_file(path, utc, apply_changes):
    rel = path.relative_to(ROOT).as_posix()
    text = path.read_text(encoding="utf-8", errors="replace")
    issue = classify_issue(path, text)

    if issue == "ok":
        return {"path": rel, "status": "skip", "reason": "already_valid"}

    new_text = None
    action = None

    if issue == "no_leading_delimiter":
        stripped = strip_leading_until_first_yaml_start(text)
        if stripped and is_valid_opening_frontmatter(stripped):
            new_text, action = insert_footer_only(stripped, utc)
            if action == "already_has_footer" and stripped != text:
                action = "stripped_leading_junk_footer_ok"
            if new_text is None:
                new_text = stripped
                action = "stripped_prefix_only"
        else:
            body = text
            if stripped:
                body = stripped
            title = path.stem
            new_text = build_minimal_header(rel, utc, title) + "\n# file: %s — delegation: cursor:root\n\n" % title + body.lstrip()
            action = "prepended_full_header"

    elif issue == "fake_opening_or_invalid_yaml":
        body = extract_body_after_fake_hr_block(text)
        if body is not None:
            title = path.stem
            new_text = (
                build_minimal_header(rel, utc, title)
                + "\n# file: %s — delegation: cursor:root\n\n" % title
                + body.lstrip()
            )
            action = "replaced_fake_hr_with_valid_yaml"
        else:
            title = path.stem
            new_text = build_minimal_header(rel, utc, title) + "\n# file: %s\n\n" % title + text
            action = "prepended_full_header_wrapped_body"

    elif issue == "no_lupopedia_footer":
        new_text, action = insert_footer_only(text, utc)
        if new_text is None:
            return {"path": rel, "status": "skip", "reason": action or "footer_insert_failed"}

    if new_text is None or new_text == text:
        return {"path": rel, "status": "skip", "reason": "no_change", "issue": issue}

    if apply_changes:
        path.write_text(new_text, encoding="utf-8", newline="\n")

    return {"path": rel, "status": "fixed", "action": action, "issue": issue}


def main():
    ap = argparse.ArgumentParser(description="Fix malformed doctrine headers / footers.")
    ap.add_argument("--apply", action="store_true", help="Write files (default is dry-run)")
    args = ap.parse_args()
    apply_changes = bool(args.apply)

    utc = load_anchor_utc()
    files = sorted(DOCTRINE.rglob("*.md"))
    results = []
    for f in files:
        results.append(fix_file(f, utc, apply_changes))

    fixed = sum(1 for r in results if r.get("status") == "fixed")
    skipped = sum(1 for r in results if r.get("status") == "skip")
    print(
        json.dumps(
            {
                "anchor_utc": utc,
                "apply": apply_changes,
                "total": len(files),
                "fixed" if apply_changes else "would_fix": fixed,
                "skipped": skipped,
            },
            indent=2,
        )
    )
    for r in results:
        if r.get("status") == "fixed":
            print("%s\t%s\t%s" % (r["path"], r.get("issue"), r.get("action")))


if __name__ == "__main__":
    main()
