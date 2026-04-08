#!/usr/bin/env python3
"""
PRD 17 pseudocode validator for decisions/pseudocode/.

Canonical rules: lupo-docs/prd/17_decisions_format.md
(Pseudocode reasoning discipline for IDE agents.)

- Enforces required headers for every *.pseudo.md, *.pseudo.php, *.pseudo.txt
  under decisions/pseudocode/:
  - lupopedia.headers
  - file_path_from_root
  - when_updated
  - last_modified_utc
- Keeps Purpose 2 discipline warnings for *_design.pseudo.md, other *.pseudo.md
  (except allowlisted Purpose 1), and *.pseudo.php.

Exit 0: no violations (or only warnings without --strict).
Exit 1: any required-header violation, unreadable path, or (with --strict) warnings.

This output complies with Lupopedia Constitutional Root Rules.
"""

from __future__ import unicode_literals

import argparse
import re
import sys
from pathlib import Path


def _norm(p):
    return str(p).replace("\\", "/")


def _is_under_decisions_pseudocode(path):
    parts = _norm(path).lower().split("/")
    try:
        i = parts.index("decisions")
        return i + 1 < len(parts) and parts[i + 1] == "pseudocode"
    except ValueError:
        return False


def classify(path):
    """Return 'skip', 'purpose1', or 'purpose2'."""
    path = Path(path).resolve()
    name = path.name.lower()
    s = _norm(path).lower()

    if not path.is_file():
        return "skip"
    if not _is_under_decisions_pseudocode(path):
        return "skip"

    if name.endswith(".pseudo.php"):
        return "purpose2"

    if name.endswith(".pseudo.txt"):
        return "purpose2"

    if not name.endswith(".pseudo.md"):
        return "skip"

    if name.startswith("00_"):
        return "purpose1"
    if name.endswith("_constitution.pseudo.md"):
        return "purpose1"
    if "/lupo-docs/decisions/pseudocode/" in s or s.endswith("lupo-docs/decisions/pseudocode/" + name):
        return "purpose1"

    return "purpose2"


def _required_header_violations(path, text):
    issues = []
    if "lupopedia.headers:" not in text:
        issues.append("missing lupopedia.headers block")
    if not re.search(r"(?m)^\s*file_path_from_root:\s*['\"]?[^'\"\n]+", text):
        issues.append("missing file_path_from_root in lupopedia.headers")
    if not re.search(r"(?m)^\s*when_updated:\s*['\"]?[0-9]{8,14}", text):
        issues.append("missing when_updated in lupopedia.headers")
    if not re.search(r"(?m)^\s*last_modified_utc:\s*['\"]?[0-9]{8,14}", text):
        issues.append("missing last_modified_utc in lupopedia.headers")
    if issues:
        return ["%s: %s" % (_norm(path), item) for item in issues]
    return []


def has_decision_anchor(text):
    t = text
    if re.search(r"(?i)decision\s*reference", t):
        return True
    if re.search(r"(?i)#\s*decision\s*reference", t):
        return True
    if "lupopedia.edges:" in t and "outbound_edges:" in t:
        return True
    if re.search(r"DECISION_\d{8}_", t):
        return True
    if re.search(r"\d{8}_\d{6}_DECISION_", t):
        return True
    return False


def comment_rationale_ratio(text):
    lines = text.splitlines()
    nonempty = [ln for ln in lines if ln.strip()]
    if not nonempty:
        return 1.0
    rationale = 0
    for ln in nonempty:
        s = ln.strip()
        if s.startswith("#") or s.startswith(">"):
            rationale += 1
    return float(rationale) / float(len(nonempty))


def fork_without_pending(text):
    if not re.search(r"(?i)\boption\s+[ab]\b|\bOPTION\s+[AB]\b", text):
        return False
    if "DECISION PENDING" in text or "decision pending" in text.lower():
        return False
    if re.search(r"DECISION_\d{8}_", text) or re.search(r"\d{8}_\d{6}_DECISION_", text):
        return False
    return True


def php_has_decision_hook(text):
    head = "\n".join(text.splitlines()[:120])
    if re.search(r"(?i)decision\s*reference", head):
        return True
    if re.search(r"(?i)pseudocode\s+only", head):
        return True
    if "DECISION_" in head or "decisions/" in head:
        return True
    return False


def validate_file(path, warnings_out, violations_out):
    if not _is_under_decisions_pseudocode(path):
        return

    lname = Path(path).name.lower()
    if not (
        lname.endswith(".pseudo.md")
        or lname.endswith(".pseudo.php")
        or lname.endswith(".pseudo.txt")
    ):
        return

    try:
        raw = Path(path).read_text(encoding="utf-8", errors="replace")
    except EnvironmentError as e:
        violations_out.append("%s: unreadable (%s)" % (_norm(path), e))
        return

    violations_out.extend(_required_header_violations(path, raw))

    kind = classify(path)
    if kind != "purpose2":
        return

    name = Path(path).name.lower()

    if name.endswith(".pseudo.md"):
        if not has_decision_anchor(raw):
            warnings_out.append(
                "%s: missing decision anchor "
                "(expected Decision Reference, DECISION_ link, or lupopedia.edges)"
                % _norm(path)
            )
        ratio = comment_rationale_ratio(raw)
        if ratio < 0.35:
            warnings_out.append(
                "%s: low rationale density (~%.0f%% #/blockquote lines of non-empty; target ~60%%)"
                % (_norm(path), ratio * 100.0)
            )
        if fork_without_pending(raw):
            warnings_out.append(
                "%s: option fork without DECISION PENDING or DECISION_ resolution"
                % _norm(path)
            )

    if name.endswith(".pseudo.php"):
        if not php_has_decision_hook(raw):
            warnings_out.append(
                "%s: missing top-of-file decision anchor or PSEUDOCODE ONLY banner"
                % _norm(path)
            )


def collect_paths(roots):
    out = []
    for r in roots:
        p = Path(r)
        if p.is_file():
            out.append(p)
        elif p.is_dir():
            for f in p.rglob("*"):
                if f.is_file() and (
                    f.name.endswith(".pseudo.md")
                    or f.name.endswith(".pseudo.php")
                    or f.name.endswith(".pseudo.txt")
                ):
                    out.append(f)
        else:
            sys.stderr.write("Not found: %s\n" % r)
    seen = set()
    unique = []
    for f in out:
        rp = f.resolve()
        if rp not in seen:
            seen.add(rp)
            unique.append(rp)
    return unique


def main():
    parser = argparse.ArgumentParser(
        description="Warn on Purpose 2 pseudocode discipline (PRD 17)."
    )
    parser.add_argument(
        "paths",
        nargs="*",
        default=["."],
        help="Files or directories (default: current directory)",
    )
    parser.add_argument(
        "--strict",
        action="store_true",
        help="Treat warnings as errors (exit 1)",
    )
    parser.add_argument(
        "--verbose",
        action="store_true",
        help="Print skipped files (Purpose 1 / non-pseudocode)",
    )
    args = parser.parse_args()

    all_files = collect_paths(args.paths)
    warnings = []
    violations = []
    skipped_verbose = []

    for f in all_files:
        k = classify(f)
        if k == "skip":
            if args.verbose:
                skipped_verbose.append("skip %s" % _norm(f))
            continue
        if k == "purpose1":
            if args.verbose:
                skipped_verbose.append("purpose1 %s" % _norm(f))
            continue
        validate_file(f, warnings, violations)

    for line in skipped_verbose:
        print(line)

    if violations:
        print("Pseudocode header violations (%d):" % len(violations))
        for v in violations:
            print("  ", v)
        return 1

    if warnings:
        print("Pseudocode discipline warnings (%d):" % len(warnings))
        for w in warnings:
            print("  ", w)
        if args.strict:
            return 1
    else:
        print("No Purpose 2 pseudocode discipline warnings.")

    return 0


if __name__ == "__main__":
    sys.exit(main())
