#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/audit_namespace_headers.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/audit_namespace_headers.py"
#   status: "complete"
#   when_updated: "20260411040000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/audit-namespace-headers.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/audit-namespace-headers"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: "Audit module field in LUPOPEDIA HEADERS"
#   summary: "Audit module field (replaces namespace) across repository"
# ---------------------------------------------------------------------
"""
Audit LUPOPEDIA_HEADERS logical module field across repository (PRD 16 v4.0.99: YAML key ``module``; legacy ``namespace`` is still read for older files). Produces module_audit_4_0_99.md.
Run from repo root: python lupo-scripts/audit_namespace_headers.py
"""
from __future__ import print_function

import os
import re
import subprocess
import sys

APPROVED_MODULES = frozenset(['auth', 'channels', 'core', 'content', 'analytics', 'federation', 'governance', 'integration', 'legacy'])
TABLES_DIR = "lupo-docs/database/lupopedia/tables"
REPORT_PATH = "lupo-docs/status/module_audit_4_0_99.md"


def _repo_root():
    script_dir = os.path.abspath(os.path.dirname(__file__))
    return os.path.abspath(os.path.join(script_dir, ".."))


def _extract_module(block_text):
    """Extract ``module`` (v4.0.99) or legacy ``namespace`` from first lupopedia.headers section."""
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
            if key == "module" or key == "namespace":
                v = val.strip().strip('"\'')
                if v.lower() == "null":
                    return None
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


def run_py_validator(root_dir, rel_path):
    """Run validate_lupopedia_headers_universal.py on path; return (success, errors)."""
    full = os.path.join(root_dir, rel_path)
    if not os.path.isfile(full):
        return True, []
    try:
        proc = subprocess.Popen(
            [sys.executable, "lupo-scripts/validate_lupopedia_headers_universal.py", full],
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
    valid_with_module = []
    validator_failures = []

    for rel_path in table_docs:
        full_path = os.path.join(root_dir, rel_path)
        try:
            content = open(full_path, "r", encoding="utf-8", errors="replace").read()
        except IOError:
            continue
        blocks = re.split(r"\n---\n", content)
        header_blocks = [b for b in blocks if "lupopedia.headers" in b]
        mod = _extract_module(header_blocks[0]) if header_blocks else None

        if mod is None or mod == "":
            missing.append(rel_path)
        elif mod not in APPROVED_MODULES:
            invalid_value.append((rel_path, mod))
        else:
            valid_with_module.append((rel_path, mod))

        ok, errs = run_py_validator(root_dir, rel_path)
        if not ok:
            validator_failures.append((rel_path, errs))

    # Non-table areas: sample for namespace usage
    other_dirs = ["lupo-docs/versions", "lupo-docs/status", "lupo-docs/doctrine", "lupo-rules", ".cursor"]
    other_files = _collect_md_files(root_dir, *other_dirs)
    other_with_mod = []
    for rel_path in other_files[:200]:
        full_path = os.path.join(root_dir, rel_path)
        try:
            content = open(full_path, "r", encoding="utf-8", errors="replace").read()
        except IOError:
            continue
        blocks = re.split(r"\n---\n", content)
        header_blocks = [b for b in blocks if "lupopedia.headers" in b]
        mod = _extract_module(header_blocks[0]) if header_blocks else None
        if mod:
            other_with_mod.append((rel_path, mod))

    # Write report
    lines = [
        "# Module audit (4.0.99)",
        "",
        "Generated by `lupo-scripts/audit_namespace_headers.py`. Doctrine: LUPOPEDIA_HEADERS_FORMAT.md §2.2; approved taxonomy: " + ", ".join(sorted(APPROVED_MODULES)) + ".",
        "",
        "---",
        "",
        "## Summary",
        "",
        "| Category | Count |",
        "|----------|-------|",
        "| Table docs scanned | %d |" % len(table_docs),
        "| Missing module (table docs) | %d |" % len(missing),
        "| Invalid module value | %d |" % len(invalid_value),
        "| Valid module present | %d |" % len(valid_with_module),
        "| Validator failures (any reason) | %d |" % len(validator_failures),
        "",
        "---",
        "",
        "## Artifact-type policy (4.0.99)",
        "",
        "| Artifact type | Module |",
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
        "## Missing module (table docs)",
        "",
    ]
    for p in missing:
        lines.append("- `" + p + "`")
    lines.extend(["", "---", "", "## Invalid module value (table docs)", ""])
    for p, val in invalid_value:
        lines.append("- `" + p + "` — value: `" + val + "`")
    lines.extend(["", "---", "", "## Valid module present (sample)", ""])
    for p, val in valid_with_module[:30]:
        lines.append("- `" + p + "` — `" + val + "`")
    if len(valid_with_module) > 30:
        lines.append("- ... and %d more." % (len(valid_with_module) - 30))
    lines.extend(["", "---", "", "## Validator failures (first 50)", ""])
    for p, errs in validator_failures[:50]:
        lines.append("- `" + p + "`: " + "; ".join(errs[:2]))
    lines.extend(["", "---", "", "## Non-table artifacts with module (sample)", ""])
    for p, val in other_with_mod[:20]:
        lines.append("- `" + p + "` — `" + val + "`")
    lines.extend(["", "---", "", "*End of report.*", ""])

    out_path = os.path.join(root_dir, REPORT_PATH)
    out_dir = os.path.dirname(out_path)
    if not os.path.isdir(out_dir):
        os.makedirs(out_dir)
    with open(out_path, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))
    print("Wrote " + out_path)
    print("Missing: %d | Invalid: %d | Valid: %d | Validator fail: %d" % (len(missing), len(invalid_value), len(valid_with_module), len(validator_failures)))
    return 0


if __name__ == "__main__":
    sys.exit(main())