#!/usr/bin/env python3
"""One-shot audit: doctrine files vs PRD edges in YAML headers. Output JSON lines."""
from __future__ import print_function

import argparse
import importlib.util
import json
import re
import sys
from pathlib import Path

try:
    import yaml
except ImportError:
    yaml = None

ROOT = Path(__file__).resolve().parents[1]
DOCTRINE = ROOT / "docs" / "doctrine"


def extract_prd_num(s):
    m = re.search(r"prd/(\d+)[^\s\"')]*\.md", s)
    return int(m.group(1)) if m else None


def parse_frontmatter(text):
    """Return YAML string inside first --- block, or None.

    Prefers closing ``---`` on its own line; if missing (invalid but common),
    ends at first line starting with ``# file:`` (identity line).
    """
    if not text.startswith("---"):
        return None, text
    rest = text[3:]
    # Closing delimiter after opening --- : newline, ---, optional ws, newline
    m = re.search(r"\n---\s*\n", rest)
    if m:
        return rest[: m.start()], rest[m.end() :]
    m2 = re.search(r"\n# file:", rest)
    if m2:
        return rest[: m2.start()], rest[m2.start() :]
    return None, text


def collect_edges_from_yaml_block(data):
    """Return list of (to, type, prd_id or None)."""
    out = []
    if not isinstance(data, dict):
        return out
    ob = data.get("outbound_edges")
    if ob is None:
        return out
    if isinstance(ob, list):
        for e in ob:
            if isinstance(e, dict) and "to" in e:
                t = e["to"]
                out.append((t, e.get("type"), extract_prd_num(t)))
    elif isinstance(ob, dict):
        for _cat, lst in ob.items():
            if not isinstance(lst, list):
                continue
            for e in lst:
                if isinstance(e, dict) and "to" in e:
                    t = e["to"]
                    out.append((t, e.get("type"), extract_prd_num(t)))
    return out


def scan_file(path):
    rel = path.relative_to(ROOT).as_posix()
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except Exception as e:
        return {"path": rel, "error": str(e)}

    fm, body = parse_frontmatter(text)
    prd_in_yaml = []
    yaml_err = None
    headers = {}
    if fm and yaml:
        try:
            headers = yaml.safe_load(fm) or {}
        except Exception as e:
            yaml_err = str(e)[:120]
        le = headers.get("lupopedia.edges")
        if isinstance(le, dict):
            prd_in_yaml = collect_edges_from_yaml_block(le)
    elif fm and not yaml:
        yaml_err = "no PyYAML"

    prd_ids = sorted(set(x[2] for x in prd_in_yaml if x[2] is not None))
    any_prd_to = any("prd/" in (x[0] or "").lower() for x in prd_in_yaml)

    head = text[:14000]
    content_prd_refs = sorted(
        set(
            int(m.group(1))
            for m in re.finditer(r"(?:docs/)?prd/(\d+)[^\s\"')]*\.md", head)
        )
    )

    return {
        "path": rel,
        "yaml_err": yaml_err,
        "prd_ids_from_edges": prd_ids,
        "any_edge_to_prd_path": any_prd_to,
        "edge_count_to_prd": sum(1 for x in prd_in_yaml if "prd/" in (x[0] or "").lower()),
        "content_prd_refs_in_header_zone": content_prd_refs[:20],
        "has_lupopedia_edges_key": "lupopedia.edges:" in head,
    }


def load_apply_module():
    """Load apply_doctrine_prd_lineage for lineage skip reasons (no circular import at import time)."""
    spec = importlib.util.spec_from_file_location(
        "apply_doctrine_prd_lineage",
        ROOT / "scripts" / "apply_doctrine_prd_lineage.py",
    )
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod


def load_fix_classify():
    spec = importlib.util.spec_from_file_location(
        "fix_doctrine_headers", ROOT / "scripts" / "fix_doctrine_headers.py"
    )
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod


def main():
    ap = argparse.ArgumentParser(description="Audit doctrine YAML for PRD edge links.")
    ap.add_argument(
        "--show-skipped",
        action="store_true",
        help="List lineage-injector skip reasons (same as apply_doctrine_prd_lineage.py)",
    )
    ap.add_argument(
        "--show-header-issues",
        action="store_true",
        help="List fix_doctrine_headers.py classify_issue per file",
    )
    args = ap.parse_args()

    files = sorted(DOCTRINE.rglob("*.md"))
    rows = [scan_file(f) for f in files]
    missing = []
    for r in rows:
        if r.get("error"):
            continue
        if not r.get("prd_ids_from_edges") and not r.get("any_edge_to_prd_path"):
            missing.append(r["path"])

    out = {
        "total": len(files),
        "with_prd_id_in_yaml_edge": sum(
            1 for r in rows if r.get("prd_ids_from_edges")
        ),
        "with_any_prd_path_in_edge": sum(
            1 for r in rows if r.get("any_edge_to_prd_path")
        ),
        "missing_prd_edge_entirely": len(missing),
        "missing_paths": missing,
    }
    print(json.dumps(out, indent=2))
    print("---")
    for p in missing[:80]:
        print(p)
    if len(missing) > 80:
        print("... and", len(missing) - 80, "more")

    if args.show_skipped:
        try:
            adl = load_apply_module()
        except Exception as e:
            print("---", file=sys.stderr)
            print("Could not load apply_doctrine_prd_lineage:", e, file=sys.stderr)
            sys.exit(1)
        print("---")
        print("lineage_injector_skip\tpath\treason")
        for f in files:
            r = adl.process_file(f, apply_changes=False)
            if r.get("status") == "skip":
                print("%s\t%s" % (r.get("reason", "?"), r["path"]))

    if args.show_header_issues:
        try:
            fx = load_fix_classify()
        except Exception as e:
            print("---", file=sys.stderr)
            print("Could not load fix_doctrine_headers:", e, file=sys.stderr)
            sys.exit(1)
        print("---")
        print("header_issue\tpath")
        for f in files:
            t = f.read_text(encoding="utf-8", errors="replace")
            issue = fx.classify_issue(f, t)
            if issue != "ok":
                print("%s\t%s" % (issue, f.relative_to(ROOT).as_posix()))


if __name__ == "__main__":
    main()
