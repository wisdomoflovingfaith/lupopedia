#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
find_edges.py — Suggest lupopedia.edges outbound_edges from markdown content.

Default: print suggestions only (dry-run). Never writes files unless --apply is used.

Constitutional posture: discovery is automated; edge writes require explicit --apply
plus either --yes (batch) or --interactive (per-edge confirmation).
"""
from __future__ import print_function

import argparse
import os
import re
import sys

ROOT = os.path.normpath(os.path.join(os.path.dirname(os.path.abspath(__file__)), ".."))

try:
    input_func = raw_input  # Python 2
except NameError:
    input_func = input  # Python 3

try:
    import yaml
except ImportError:
    yaml = None

# Optional keyword hints: phrase -> repo-relative path (must exist under ROOT at runtime).
DEFAULT_KEYWORD_TARGETS = {
    "dynapi": "docs/doctrine/DYNAPI_DOCTRINE.md",
    "stoned wolfie": "docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md",
    "two-ui": "docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md",
    "two ui": "docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md",
    "mobile separation": "docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md",
    "utc timestamp": "docs/doctrine/TIMESTAMP_DOCTRINE.md",
    "tick.py": "docs/doctrine/TICK_PY_DOCTRINE.md",
    "foreign keys": "docs/doctrine/DATABASE_DOCTRINE.md",
    "no foreign key": "docs/doctrine/DATABASE_DOCTRINE.md",
    "soft delete": "docs/doctrine/DATABASE_DOCTRINE.md",
    "silent harvest": "docs/doctrine/SILENT_HARVEST_DOCTRINE.md",
    "reverse engineering": "docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md",
    "self hacking": "docs/doctrine/SELF_HACKING_DOCTRINE.md",
    "lupopedia headers": "docs/doctrine/LUPOPEDIA_HEADERS/README.md",
}

MD_LINK_RE = re.compile(r"\[([^\]]*)\]\(([^)]+)\)")
PRD_NUM_RE = re.compile(
    r"(?:^|[^\w])(?:PRD|prd)\s*[-:]?\s*(\d{1,3})\b", re.IGNORECASE | re.MULTILINE
)
PRD_PATH_RE = re.compile(
    r"docs/prd/(\d{1,3})_[^\s\])\"']+\.md", re.IGNORECASE
)
INLINE_PRD_PATH = re.compile(
    r"(docs/prd/\d{1,3}_[^\s\]\)\"\'>]+\.md)", re.IGNORECASE
)
CODE_PATH_RE = re.compile(
    r"\b((?:includes|scripts|app|agents|ui|bin)/[a-zA-Z0-9_./-]+\.(?:php|js|css|py))\b"
)
DIR_PATH_RE = re.compile(
    r"\b((?:includes|scripts|craftysyntax-reference)/[a-zA-Z0-9_./-]+/?)\b"
)


def _posix(p):
    return p.replace("\\", "/")


def _exists_rel(rel):
    return os.path.isfile(os.path.join(ROOT, rel)) or os.path.isdir(
        os.path.join(ROOT, rel.rstrip("/"))
    )


def parse_frontmatter(text):
    """Return (yaml_string_or_None, body) like audit_doctrine_prd_edges."""
    if not text.startswith("---"):
        return None, text
    rest = text[3:]
    m = re.search(r"\n---\s*\n", rest)
    if m:
        return rest[: m.start()], rest[m.end() :]
    m2 = re.search(r"\n# file:", rest)
    if m2:
        return rest[: m2.start()], rest[m2.start() :]
    return None, text


def existing_edge_targets(text):
    """Collect `to` values already in lupopedia.edges (best-effort YAML parse)."""
    fm, _ = parse_frontmatter(text)
    if not fm or yaml is None:
        return set()
    try:
        data = yaml.safe_load(fm) or {}
    except Exception:
        return set()
    out = set()
    le = data.get("lupopedia.edges") or data.get("lupopedia", {}).get("edges")
    if isinstance(le, dict):
        ob = le.get("outbound_edges")
        if isinstance(ob, list):
            for e in ob:
                if isinstance(e, dict) and e.get("to"):
                    out.add(_posix(str(e["to"]).strip()))
    return out


def resolve_prd_files(prd_num):
    """Return repo-relative paths for PRD number."""
    prd_dir = os.path.join(ROOT, "docs", "prd")
    if not os.path.isdir(prd_dir):
        return []
    n = int(prd_num)
    prefix = "{0}_".format(n)
    found = []
    try:
        for name in os.listdir(prd_dir):
            if name.startswith(prefix) and name.endswith(".md"):
                found.append(_posix(os.path.join("docs", "prd", name)))
    except OSError:
        pass
    return sorted(set(found))


def normalize_markdown_href(href, base_file):
    """Return repo-relative path or None if external / skip."""
    href = href.strip()
    if not href or href.startswith("#"):
        return None
    low = href.lower()
    if low.startswith("http://") or low.startswith("https://"):
        return None
    if low.startswith("mailto:"):
        return None
    # strip query
    if "?" in href:
        href = href.split("?", 1)[0]
    base_dir = os.path.dirname(base_file)
    # Paths written as repo-root-relative (common in doctrine) — resolve from ROOT
    rootish_prefixes = (
        "docs/",
        "includes/",
        "scripts/",
        "agents/",
        "ui/",
        "bin/",
        "app/",
        "config/",
        "channels/",
    )
    if any(href.startswith(p) for p in rootish_prefixes):
        cand = os.path.normpath(os.path.join(ROOT, href))
    elif href.startswith("/"):
        cand = os.path.normpath(os.path.join(ROOT, href.lstrip("/")))
    else:
        cand = os.path.normpath(os.path.join(base_dir, href))
    try:
        rel = os.path.relpath(cand, ROOT)
    except ValueError:
        return None
    if rel.startswith(".."):
        return None
    return _posix(rel)


def find_markdown_links(content, base_file):
    """List of (rel_path, confidence, reason)."""
    out = []
    base_file = os.path.normpath(base_file)
    for _text, href in MD_LINK_RE.findall(content):
        rel = normalize_markdown_href(href, base_file)
        if not rel:
            continue
        if rel.endswith(".md"):
            conf = 0.95 if _exists_rel(rel) else 0.55
            reason = "Markdown link to .md" if _exists_rel(rel) else "Markdown link (target missing on disk)"
            out.append((rel, conf, reason))
        elif _exists_rel(rel) or _exists_rel(rel.rstrip("/")):
            conf = 0.88
            out.append((rel.rstrip("/") + ("/" if rel.endswith("/") else ""), conf, "Markdown link to path"))
    return out


def find_prd_refs(content):
    """PRD numbers and explicit prd/*.md paths."""
    out = []
    seen = set()
    for m in PRD_NUM_RE.finditer(content):
        n = m.group(1)
        if n in seen:
            continue
        seen.add(n)
        for p in resolve_prd_files(n):
            out.append((p, 0.95, "PRD {0} referenced in prose".format(n)))
    for m in PRD_PATH_RE.finditer(content):
        n = m.group(1)
        for p in resolve_prd_files(n):
            out.append((p, 0.96, "PRD path mention"))
    for m in INLINE_PRD_PATH.finditer(content):
        rel = m.group(1).strip()
        if _exists_rel(rel):
            out.append((rel, 0.97, "Inline docs/prd path"))
    return out


def find_keyword_hits(content, keyword_map):
    """Keyword substring -> target files."""
    out = []
    lower = content.lower()
    for phrase, target in keyword_map.items():
        if phrase.lower() in lower:
            if _exists_rel(target):
                out.append(
                    (
                        target,
                        0.78,
                        "Keyword hint: '{0}'".format(phrase),
                    )
                )
    return out


def build_header_index(scan_roots, max_files=4000):
    """header_text_lower -> first rel path seen."""
    index = {}
    count = 0
    for root in scan_roots:
        root = os.path.join(ROOT, root)
        if not os.path.isdir(root):
            continue
        for dirpath, _dirnames, filenames in os.walk(root):
            for fn in filenames:
                if not fn.endswith(".md"):
                    continue
                path = os.path.join(dirpath, fn)
                count += 1
                if count > max_files:
                    return index
                try:
                    txt = open(path, "r", encoding="utf-8", errors="replace").read()
                except OSError:
                    continue
                for hm in re.finditer(r"^## ([^\n]+)$", txt, re.MULTILINE):
                    h = hm.group(1).strip().lower()
                    if len(h) < 4:
                        continue
                    rel = _posix(os.path.relpath(path, ROOT))
                    if h not in index:
                        index[h] = rel
    return index


def find_header_matches(content, current_rel, header_index, min_len=6):
    """Match ## headers to other files (first match per header)."""
    out = []
    seen_targets = set()
    for hm in re.finditer(r"^## ([^\n]+)$", content, re.MULTILINE):
        h = hm.group(1).strip()
        if len(h) < min_len:
            continue
        key = h.lower()
        other = header_index.get(key)
        if not other or other == current_rel:
            continue
        if other in seen_targets:
            continue
        seen_targets.add(other)
        out.append(
            (
                other,
                0.62,
                'Shared heading "## {0}"'.format(h[:80]),
            )
        )
    return out


def find_code_paths(content):
    """includes/... etc."""
    out = []
    seen = set()
    for pat in (CODE_PATH_RE, DIR_PATH_RE):
        for m in pat.finditer(content):
            rel = m.group(1).rstrip("/")
            if rel in seen:
                continue
            seen.add(rel)
            if _exists_rel(rel):
                out.append((rel, 0.82, "Code or tree path in prose"))
            elif _exists_rel(rel + "/"):
                out.append((rel + "/", 0.8, "Directory path in prose"))
    return out


def dedupe(suggestions):
    """Merge by target path, keep max confidence."""
    best = {}
    for rel, conf, reason in suggestions:
        c = best.get(rel)
        if c is None or conf > c[0]:
            best[rel] = (conf, reason)
    out = []
    for rel in sorted(best.keys()):
        conf, reason = best[rel]
        out.append((rel, conf, reason))
    return out


def collect_suggestions(path, args, header_index):
    """path is absolute. Returns list of (rel, conf, reason)."""
    current_rel = _posix(os.path.relpath(path, ROOT))
    try:
        text = open(path, "r", encoding="utf-8", errors="replace").read()
    except OSError as e:
        print("Error reading {0}: {1}".format(current_rel, e), file=sys.stderr)
        return []

    existing = existing_edge_targets(text)
    kw = dict(DEFAULT_KEYWORD_TARGETS)
    if args.keyword_file:
        kpath = os.path.join(ROOT, args.keyword_file)
        if os.path.isfile(kpath) and yaml:
            try:
                extra = yaml.safe_load(open(kpath, "r", encoding="utf-8").read())
                if isinstance(extra, dict):
                    kw.update({str(k).lower(): str(v) for k, v in extra.items()})
            except Exception as ex:
                print("Warning: keyword file: {0}".format(ex), file=sys.stderr)

    # Filter keyword map to existing targets only
    kw_filt = {}
    for k, v in kw.items():
        if _exists_rel(v):
            kw_filt[k] = v

    sug = []
    sug.extend(find_markdown_links(text, path))
    sug.extend(find_prd_refs(text))
    sug.extend(find_keyword_hits(text, kw_filt))
    sug.extend(find_code_paths(text))
    if args.headers and header_index is not None:
        sug.extend(find_header_matches(text, current_rel, header_index))

    sug = dedupe(sug)
    # Drop self and already-edged
    filtered = []
    for rel, conf, reason in sug:
        if rel == current_rel:
            continue
        if rel in existing:
            continue
        if conf < args.confidence:
            continue
        filtered.append((rel, conf, reason))
    return filtered


def format_yaml_edges(suggestions):
    """Print outbound_edges snippet."""
    lines = ["  outbound_edges:"]
    if not suggestions:
        lines.append("    []")
        return "\n".join(lines)
    for rel, conf, reason in suggestions:
        lines.append('    - to: "{0}"'.format(rel.replace('"', '\\"')))
        lines.append("      type: references")
        lines.append("      weight: {0:.2f}".format(min(1.0, max(0.0, conf))))
        lines.append('      reason: "{0}"'.format(reason.replace('"', "'")[:200]))
    return "\n".join(lines)


def merge_edges_into_file(path, suggestions, dry_run=False):
    """Insert suggestions into lupopedia.edges.outbound_edges. Requires PyYAML."""
    if yaml is None:
        print("PyYAML is required for --apply.", file=sys.stderr)
        return False
    try:
        raw = open(path, "r", encoding="utf-8").read()
    except OSError as e:
        print(str(e), file=sys.stderr)
        return False
    fm, body = parse_frontmatter(raw)
    if fm is None:
        print("No YAML front matter (---) found; cannot --apply.", file=sys.stderr)
        return False
    try:
        data = yaml.safe_load(fm) or {}
    except Exception as e:
        print("YAML parse error: {0}".format(e), file=sys.stderr)
        return False

    if "lupopedia.edges" not in data:
        data["lupopedia.edges"] = {}
    le = data["lupopedia.edges"]
    if not isinstance(le, dict):
        le = {}
        data["lupopedia.edges"] = le
    ob = le.get("outbound_edges")
    if not isinstance(ob, list):
        ob = []
    existing_tos = set()
    for e in ob:
        if isinstance(e, dict) and e.get("to"):
            existing_tos.add(_posix(str(e["to"])))

    added = 0
    for rel, conf, reason in suggestions:
        if rel in existing_tos:
            continue
        ob.append(
            {
                "to": rel,
                "type": "references",
                "weight": round(min(1.0, max(0.0, conf)), 2),
                "reason": reason[:500],
            }
        )
        existing_tos.add(rel)
        added += 1

    le["outbound_edges"] = ob

    new_fm = yaml.safe_dump(
        data,
        default_flow_style=False,
        allow_unicode=True,
        sort_keys=False,
        width=120,
    )
    # Ensure leading --- for Lupopedia style
    out_text = "---\n" + new_fm.rstrip() + "\n---\n" + body
    if dry_run:
        print(out_text[:2000])
        if len(out_text) > 2000:
            print("... [truncated]", file=sys.stderr)
        return True

    backup = path + ".bak_find_edges"
    try:
        open(backup, "w", encoding="utf-8").write(raw)
    except OSError:
        pass
    open(path, "w", encoding="utf-8").write(out_text)
    print("Wrote {0} new edge(s) to {1} (backup: {2})".format(added, path, backup))
    return True


def main():
    ap = argparse.ArgumentParser(
        description="Suggest lupopedia.edges from markdown (dry-run by default)."
    )
    ap.add_argument("path", help="File or directory under the repo")
    ap.add_argument(
        "--suggest",
        action="store_true",
        help="Print suggested edges only (default; never writes files)",
    )
    ap.add_argument(
        "-r", "--recursive", action="store_true", help="Scan directory recursively"
    )
    ap.add_argument(
        "--confidence",
        type=float,
        default=0.5,
        help="Minimum weight/confidence to include (0.0-1.0)",
    )
    ap.add_argument(
        "--headers",
        action="store_true",
        help="Match ## headings against other docs (slower; indexes docs/)",
    )
    ap.add_argument(
        "--keyword-file",
        metavar="YAML",
        help="Optional YAML map phrase -> repo-relative path (extends defaults)",
    )
    ap.add_argument(
        "--apply",
        action="store_true",
        help="Write merged outbound_edges into each file (requires --yes or --interactive)",
    )
    ap.add_argument(
        "--yes",
        action="store_true",
        help="With --apply, skip final batch confirmation prompt",
    )
    ap.add_argument(
        "-i",
        "--interactive",
        action="store_true",
        help="Confirm each suggested edge before applying (y/n)",
    )
    args = ap.parse_args()

    target = os.path.normpath(os.path.join(ROOT, args.path))
    if not os.path.exists(target):
        print("Not found: {0}".format(target), file=sys.stderr)
        sys.exit(1)

    files = []
    if os.path.isfile(target):
        if target.endswith(".md"):
            files.append(target)
    else:
        if args.recursive:
            for dp, _dn, fns in os.walk(target):
                for fn in fns:
                    if fn.endswith(".md"):
                        files.append(os.path.join(dp, fn))
        else:
            for fn in os.listdir(target):
                if fn.endswith(".md"):
                    files.append(os.path.join(target, fn))

    if not files:
        print("No .md files to scan.", file=sys.stderr)
        sys.exit(0)

    header_index = None
    if args.headers:
        print("Building header index for docs/ ...", file=sys.stderr)
        header_index = build_header_index(["docs"])

    if args.apply and not (args.yes or args.interactive):
        print(
            "Refusing --apply without --yes or --interactive (safety).",
            file=sys.stderr,
        )
        sys.exit(2)

    for path in sorted(files):
        rel = _posix(os.path.relpath(path, ROOT))
        suggestions = collect_suggestions(path, args, header_index)
        print("\n=== {0} ===".format(rel))
        if not suggestions:
            print("No new suggestions (or all below confidence / already linked).")
            continue
        print(format_yaml_edges(suggestions))

        if args.apply:
            to_apply = []
            if args.interactive:
                for item in suggestions:
                    print("\nProposed: {0}".format(item[0]))
                    print("  weight={0:.2f} {1}".format(item[1], item[2]))
                    ans = input_func("Add this edge? [y/N]: ")
                    if ans in ("y", "yes"):
                        to_apply.append(item)
            else:
                # --yes: apply all suggestions without per-edge prompts
                to_apply = list(suggestions)
            if not to_apply:
                print("Nothing to apply.")
                continue
            merge_edges_into_file(path, to_apply, dry_run=False)

    return 0


if __name__ == "__main__":
    sys.exit(main() or 0)
