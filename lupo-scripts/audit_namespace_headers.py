#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/audit_namespace_headers.py"
#   last_modified_utc: "20260324175617"
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
Audit LUPOPEDIA_HEADERS namespace across repository. Produces namespace_audit_4_0_78.md.
Run from repo root: python lupo-scripts/audit_namespace_headers.py
"""
from __future__ import print_function

import os
import re
import subprocess
import sys

APPROVED_NAMESPACES = frozenset(['auth', 'channels', 'core', 'content', 'analytics', 'federation', 'governance', 'integration', 'legacy'])
TABLES_DIR = "lupo-docs/database/lupopedia/tables"
REPORT_PATH = "lupo-docs/status/namespace_audit_4_0_78.md"


def _repo_root():
    script_dir = os.path.abspath(os.path.dirname(__file__))
    return os.path.abspath(os.path.join(script_dir, ".."))


def _extract_namespace(block_text):
    """Extract namespace value from first lupopedia.headers section."""
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
                v = val.strip().strip('"\'')
                return v if v else None
    return None


def _collect_md_files(root_dir, *rel_dirs):
    out = []
    for rel_dir in rel_dirs:
        base = os.path.join(root_dir, rel_dir)
        if not os.path.isdir(base):
            continue
        for dirpath, _dnames, fnames in os.walk(base):
            for name in fnames:
                if name.lower().endswith(".md"):
                    full = os.path.join(dirpath, name)
                    rel = os.path.relpath(full, root_dir)
                    out.append(rel.replace("\\", "/"))
    return sorted(out)


def _is_table_doc(rel_path):
    return "lupopedia/tables/" in rel_path.replace("\\", "/") and "_validator_fixtures" not in rel_path


def run_php_validator(root_dir, rel_path):
    """Run validate_lupopedia_headers.php on path; return (success, errors)."""
    full = os.path.join(root_dir, rel_path)
    if not os.path.isfile(full):
        return True, []
    try:
        proc = subprocess.Popen(
            ["php", "lupo-scripts/validate_lupopedia_headers.php", full],
            cwd=root_dir,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
        )
        _, stderr = proc.communicate(timeout=10)
        err_text = stderr.decode("utf-8", errors="replace").strip()
        errors = [e.strip() for e in err_text.splitlines() if e.strip()]
        return proc.returncode == 0, errors
    except Exception as e:
        return False, [str(e)]


def main():
    root_dir = _repo_root()
    os.chdir(root_dir)

    tables = _collect_md_files(root_dir, TABLES_DIR)
    table_docs = [p for p in tables if _is_table_doc(p)]

    missing = []
    invalid_value = []
    valid_with_namespace = []
    validator_failures = []

    for rel_path in table_docs:
        full_path = os.path.join(root_dir, rel_path)
        try:
            content = open(full_path, "r", encoding="utf-8", errors="replace").read()
        except IOError:
            continue
        blocks = re.split(r"\n---\n", content)
        header_blocks = [b for b in blocks if "lupopedia.headers" in b]
        ns = _extract_namespace(header_blocks[0]) if header_blocks else None

        if ns is None or ns == "":
            missing.append(rel_path)
        elif ns not in APPROVED_NAMESPACES:
            invalid_value.append((rel_path, ns))
        else:
            valid_with_namespace.append((rel_path, ns))

        ok, errs = run_php_validator(root_dir, rel_path)
        if not ok:
            validator_failures.append((rel_path, errs))

    # Non-table areas: sample for namespace usage
    other_dirs = ["lupo-docs/versions", "lupo-docs/status", "lupo-docs/doctrine", "lupo-rules", ".cursor"]
    other_files = _collect_md_files(root_dir, *other_dirs)
    other_with_ns = []
    for rel_path in other_files[:200]:
        full_path = os.path.join(root_dir, rel_path)
        try:
            content = open(full_path, "r", encoding="utf-8", errors="replace").read()
        except IOError:
            continue
        blocks = re.split(r"\n---\n", content)
        header_blocks = [b for b in blocks if "lupopedia.headers" in b]
        ns = _extract_namespace(header_blocks[0]) if header_blocks else None
        if ns:
            other_with_ns.append((rel_path, ns))

    # Write report
    lines = [
        "# Namespace audit (4.0.78)",
        "",
        "Generated by `lupo-scripts/audit_namespace_headers.py`. Doctrine: LUPOPEDIA_HEADERS_FORMAT.md §2.2; approved taxonomy: " + ", ".join(sorted(APPROVED_NAMESPACES)) + ".",
        "",
        "---",
        "",
        "## Summary",
        "",
        "| Category | Count |",
        "|----------|-------|",
        "| Table docs scanned | %d |" % len(table_docs),
        "| Missing namespace (table docs) | %d |" % len(missing),
        "| Invalid namespace value | %d |" % len(invalid_value),
        "| Valid namespace present | %d |" % len(valid_with_namespace),
        "| Validator failures (any reason) | %d |" % len(validator_failures),
        "",
        "---",
        "",
        "## Artifact-type policy (4.0.78)",
        "",
        "| Artifact type | Namespace |",
        "|---------------|-----------|",
        "| Table documentation | **Required** |",
        "| API docs | Optional (policy TBD) |",
        "| Rule docs | Optional (policy TBD) |",
        "| Skill docs | Optional (policy TBD) |",
        "| Planning docs | Optional (policy TBD) |",
        "| Status docs | Optional (policy TBD) |",
        "",
        "---",
        "",
        "## Missing namespace (table docs)",
        "",
    ]
    for p in missing:
        lines.append("- `" + p + "`")
    lines.extend(["", "---", "", "## Invalid namespace value (table docs)", ""])
    for p, val in invalid_value:
        lines.append("- `" + p + "` — value: `" + val + "`")
    lines.extend(["", "---", "", "## Valid namespace present (sample)", ""])
    for p, val in valid_with_namespace[:30]:
        lines.append("- `" + p + "` — `" + val + "`")
    if len(valid_with_namespace) > 30:
        lines.append("- ... and %d more." % (len(valid_with_namespace) - 30))
    lines.extend(["", "---", "", "## Validator failures (first 50)", ""])
    for p, errs in validator_failures[:50]:
        lines.append("- `" + p + "`: " + "; ".join(errs[:2]))
    lines.extend(["", "---", "", "## Non-table artifacts with namespace (sample)", ""])
    for p, val in other_with_ns[:20]:
        lines.append("- `" + p + "` — `" + val + "`")
    lines.extend(["", "---", "", "*End of report.*", ""])

    out_path = os.path.join(root_dir, REPORT_PATH)
    out_dir = os.path.dirname(out_path)
    if not os.path.isdir(out_dir):
        os.makedirs(out_dir)
    with open(out_path, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))
    print("Wrote " + out_path)
    print("Missing: %d | Invalid: %d | Valid: %d | Validator fail: %d" % (len(missing), len(invalid_value), len(valid_with_namespace), len(validator_failures)))
    return 0


if __name__ == "__main__":
    sys.exit(main())