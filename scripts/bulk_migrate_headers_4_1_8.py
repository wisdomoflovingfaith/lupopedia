# -*- coding: utf-8 -*-
"""
Deterministic bulk migration: Lupopedia Markdown YAML headers to 4.1.8 (19-field schema).
Updates memory/channels/atoms/lupopedia_global_constants.atom.toon header_fields (JSON).
PRD 16_*.md and root README.md: additional body reference updates outside protected blocks.
"""
from __future__ import print_function

import io
import json
import os
import re
import subprocess
import sys
from collections import OrderedDict

try:
    import yaml
except ImportError:
    sys.stderr.write("PyYAML required\n")
    sys.exit(1)

REPO_ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
BASE_WEB = "https://www.lupopedia.com/lupopedia/"

CANONICAL_ORDER = [
    "header_format_version",
    "path_from_lupopedia_root",
    "web_path",
    "status",
    "when_updated",
    "trust_tier",
    "questions_toon",
    "memory_toon",
    "atoms_toon",
    "transcript_jsonl",
    "artifact_type",
    "artifact_kind",
    "channel_key",
    "federation_node_id",
    "thread_key",
    "lupopedia.schema",
    "prd_cluster",
    "title",
    "summary",
]

SKIP_DIRS = {
    ".git",
    "node_modules",
    "vendor",
    "__pycache__",
}

EXTENSIONS = {".md"}


def get_anchor_utc():
    tick = os.path.join(REPO_ROOT, "bin", "tick.py")
    if os.path.isfile(tick):
        p = subprocess.Popen(
            [sys.executable, tick],
            stdout=subprocess.PIPE,
            stderr=subprocess.STDOUT,
            cwd=REPO_ROOT,
            universal_newlines=True,
        )
        out, _ = p.communicate()
        if p.returncode == 0 and out:
            m = re.search(r"time=(\d{14})", out)
            if m:
                return m.group(1)
    import datetime

    return datetime.datetime.utcnow().strftime("%Y%m%d%H%M%S")


ANCHOR_UTC = get_anchor_utc()


def posix_relpath(file_path):
    rel = os.path.relpath(file_path, REPO_ROOT)
    return rel.replace("\\", "/")


def expected_web_path(path_from_root):
    return BASE_WEB + path_from_root.lstrip("/")


def split_markdown_frontmatter(text):
    if not text.startswith("---"):
        return None, text
    lines = text.splitlines(True)
    if len(lines) < 2 or lines[0].strip() != "---":
        return None, text
    end = None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            end = i
            break
    if end is None:
        return None, text
    fm = "".join(lines[1:end])
    body = "".join(lines[end + 1 :])
    return fm, body


def parse_frontmatter_yaml(fm):
    try:
        return yaml.safe_load(fm)
    except Exception as e:
        return {"__parse_error__": str(e)}


def should_skip_header_version(hfv):
    if hfv is None:
        return False
    if isinstance(hfv, int):
        return True
    if isinstance(hfv, str) and hfv.strip() == "2":
        return True
    return False


def migrate_header_dict(h, abs_file_path, anomalies):
    if not isinstance(h, dict):
        anomalies.append((abs_file_path, "lupopedia.headers is not a dict"))
        return None
    hfv = h.get("header_format_version")
    if should_skip_header_version(hfv):
        anomalies.append((abs_file_path, "SKIP_LEGACY_HEADER_FORMAT: header_format_version=%r" % (hfv,)))
        return None
    has_path = "file_path_from_root" in h or "path_from_lupopedia_root" in h
    if not has_path and hfv is None:
        return None
    raw = dict(h)
    extras = sorted(set(raw.keys()) - set(CANONICAL_ORDER) - {"file_path_from_root", "thread_id"})
    for d in ("content_id", "content_parent_id", "default_collection_id"):
        raw.pop(d, None)
    if "file_path_from_root" in raw:
        raw["path_from_lupopedia_root"] = raw.pop("file_path_from_root")
    if "thread_id" in raw:
        raw["thread_key"] = raw.pop("thread_id")
    rel = posix_relpath(abs_file_path)
    old_path = raw.get("path_from_lupopedia_root")
    if old_path != rel:
        anomalies.append((abs_file_path, "PATH_SYNC: %r -> %r" % (old_path, rel)))
        raw["path_from_lupopedia_root"] = rel
    exp_web = expected_web_path(rel)
    if raw.get("web_path") != exp_web:
        anomalies.append(
            (abs_file_path, "WEB_PATH_SYNC: %r -> %r" % (raw.get("web_path"), exp_web))
        )
        raw["web_path"] = exp_web
    if rel.startswith("docs/"):
        if not raw["path_from_lupopedia_root"].startswith("docs/"):
            anomalies.append((abs_file_path, "ERROR path must start with docs/"))
    else:
        anomalies.append(
            (
                abs_file_path,
                "NOTE path_from_lupopedia_root=%r (file outside docs/; rule 5A exception)"
                % (raw["path_from_lupopedia_root"],),
            )
        )
    raw["header_format_version"] = "4.1.8"
    raw["when_updated"] = ANCHOR_UTC
    for k in CANONICAL_ORDER:
        if k not in raw:
            raw[k] = None
    if extras:
        anomalies.append((abs_file_path, "DROPPED_KEYS: " + ", ".join(extras)))
    for k in list(raw.keys()):
        if k not in CANONICAL_ORDER:
            del raw[k]
    od = OrderedDict()
    for k in CANONICAL_ORDER:
        od[k] = raw[k]
    return od


def build_frontmatter_yaml(hdr_ordered):
    dumped = yaml.dump(
        {"lupopedia.headers": dict(hdr_ordered)},
        default_flow_style=False,
        sort_keys=False,
        allow_unicode=True,
        width=4096,
    )
    return "---\n" + dumped + "---\n"


def process_markdown_file(abs_path, stats):
    with io.open(abs_path, "r", encoding="utf-8", newline="") as f:
        text = f.read()
    fm, body = split_markdown_frontmatter(text)
    if fm is None:
        return False
    data = parse_frontmatter_yaml(fm)
    if not isinstance(data, dict) or "lupopedia.headers" not in data:
        return False
    if "__parse_error__" in data:
        stats["anomalies"].append((abs_path, "YAML_PARSE: %s" % data["__parse_error__"]))
        return False
    h = data["lupopedia.headers"]
    new_h = migrate_header_dict(h, abs_path, stats["anomalies"])
    if new_h is None:
        return False
    new_text = build_frontmatter_yaml(new_h) + body
    if new_text != text:
        with io.open(abs_path, "w", encoding="utf-8", newline="") as f:
            f.write(new_text)
        stats["updated"].append(abs_path)
        return True
    return False


def iter_markdown_files():
    for dirpath, dirnames, filenames in os.walk(REPO_ROOT):
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS and not d.startswith(".")]
        rel_dir = os.path.relpath(dirpath, REPO_ROOT)
        parts = rel_dir.split(os.sep)
        if parts and parts[0] == "archive":
            continue
        for name in filenames:
            if os.path.splitext(name)[1].lower() != ".md":
                continue
            yield os.path.join(dirpath, name)


def protected_body_replace(body):
    tags = (
        ("<!-- ASCII_ART_BLOCK -->", "<!-- /ASCII_ART_BLOCK -->"),
        ("<!-- HUMAN_SEMANTIC -->", "<!-- /HUMAN_SEMANTIC -->"),
    )
    spans = []
    for start_t, end_t in tags:
        i = 0
        while True:
            a = body.find(start_t, i)
            if a < 0:
                break
            b = body.find(end_t, a + len(start_t))
            if b < 0:
                break
            spans.append((a, b + len(end_t)))
            i = b + len(end_t)
    spans.sort()

    def is_protected(pos):
        for a, b in spans:
            if a <= pos < b:
                return True
        return False

    out = []
    i = 0
    while i < len(body):
        if is_protected(i):
            next_end = len(body)
            for a, b in spans:
                if a <= i < b:
                    next_end = b
                    break
            out.append(body[i:next_end])
            i = next_end
            continue
        next_prot = len(body)
        for a, b in spans:
            if i < a < next_prot:
                next_prot = a
        chunk = body[i:next_prot]
        chunk = chunk.replace("file_path_from_root:", "path_from_lupopedia_root:")
        chunk = chunk.replace("file_path_from_root", "path_from_lupopedia_root")
        chunk = chunk.replace("thread_id:", "thread_key:")
        chunk = re.sub(r"\b4\.1\.7\b", "4.1.8", chunk)
        out.append(chunk)
        i = next_prot
    return "".join(out)


def postprocess_prd16_and_readme():
    targets = []
    readme = os.path.join(REPO_ROOT, "README.md")
    if os.path.isfile(readme):
        targets.append(readme)
    prd_dir = os.path.join(REPO_ROOT, "docs", "prd")
    if os.path.isdir(prd_dir):
        for name in os.listdir(prd_dir):
            if name.startswith("16_") and name.endswith(".md"):
                targets.append(os.path.join(prd_dir, name))
    for path in targets:
        with io.open(path, "r", encoding="utf-8", newline="") as f:
            text = f.read()
        fm, body = split_markdown_frontmatter(text)
        if fm is None:
            new_text = protected_body_replace(text)
        else:
            new_body = protected_body_replace(body)
            new_text = "---\n" + fm.rstrip("\n") + "\n---\n" + new_body
        if new_text != text:
            with io.open(path, "w", encoding="utf-8", newline="") as f:
                f.write(new_text)


def update_atom_json():
    atom_path = os.path.join(
        REPO_ROOT, "memory", "channels", "atoms", "lupopedia_global_constants.atom.toon"
    )
    if not os.path.isfile(atom_path):
        return None
    with io.open(atom_path, "r", encoding="utf-8") as f:
        atom = json.load(f)
    c = atom.setdefault("constants", {})
    hf = c.setdefault("header_fields", {})
    hf["count"] = 19
    hf["order"] = list(CANONICAL_ORDER)
    with io.open(atom_path, "w", encoding="utf-8", newline="\n") as f:
        json.dump(atom, f, indent=2, ensure_ascii=False, sort_keys=False)
        f.write("\n")
    return atom_path


def main():
    stats = {"updated": [], "anomalies": []}
    ap = update_atom_json()
    if ap:
        stats["updated"].append(ap)
    for abs_path in iter_markdown_files():
        try:
            process_markdown_file(abs_path, stats)
        except Exception as e:
            stats["anomalies"].append((abs_path, "PROCESS_ERROR: %s" % e))
    postprocess_prd16_and_readme()
    print("ANCHOR_UTC=%s" % ANCHOR_UTC)
    print("UPDATED_COUNT=%d" % len(stats["updated"]))
    for p in stats["updated"]:
        print("UPDATED\t%s" % os.path.relpath(p, REPO_ROOT).replace("\\", "/"))
    for item in stats["anomalies"]:
        print("ANOMALY\t%s\t%s" % (os.path.relpath(item[0], REPO_ROOT).replace("\\", "/"), item[1]))


if __name__ == "__main__":
    main()
