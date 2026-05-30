# -*- coding: utf-8 -*-
"""
Insert channel pointer stubs into docs/channels/** and docs/database/**
per batch rules. Legacy pointer for docs/doctrine/channels.md only.
"""
from __future__ import print_function

import os
import re
import sys

REPO = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))

SKIP_PHRASES = (
    "semantic container under a domain (node)",
    "authoritative channel model",
)

CHANNEL_RE = re.compile(r"(?i)\bchannels?\b")

LEGACY_POINTER = (
    "> **This file is legacy. For the canonical definition of channels, see PRD 02 "
    "and docs/doctrine/channel_model_doctrine.md. Channels are semantic containers, "
    "not conversational rooms.**\n\n"
)

STUB = (
    "> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. "
    "Channels are semantic containers under a domain (node), not chat rooms.**\n\n"
)

TEXT_SUFFIXES = (".md", ".txt", ".htm", ".html", ".pseudo.md", ".mdc")


def _lower_contains(hay, needle):
    return needle.lower() in hay.lower()


def should_skip_for_stub(text):
    for p in SKIP_PHRASES:
        if _lower_contains(text, p):
            return True
    return False


def frontmatter_insert_pos(text):
    if not text.startswith("---"):
        return 0
    lines = text.splitlines(keepends=True)
    if not lines or lines[0].strip() != "---":
        return 0
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            return sum(len(lines[j]) for j in range(i + 1))
    return 0


def bump_when_updated(text, ts):
    return re.sub(
        r"(when_updated:\s*)['\"][0-9]+['\"]",
        r"\1'" + ts + "'",
        text,
        count=1,
    )


def process_channels_md(ts):
    path = os.path.join(REPO, "docs", "doctrine", "channels.md")
    with open(path, "r", encoding="utf-8", errors="replace") as f:
        text = f.read()
    if "This file is legacy" in text:
        return False
    text = LEGACY_POINTER + text
    if re.search(r"when_updated:\s*['\"][0-9]+['\"]", text):
        text = bump_when_updated(text, ts)
    with open(path, "w", encoding="utf-8", newline="\n") as f:
        f.write(text)
    return True


def process_tree(root, ts):
    n = 0
    for dirpath, _dirnames, filenames in os.walk(root):
        for name in filenames:
            if not name.lower().endswith(TEXT_SUFFIXES):
                continue
            path = os.path.join(dirpath, name)
            rel = os.path.relpath(path, REPO)
            try:
                with open(path, "r", encoding="utf-8", errors="replace") as f:
                    text = f.read()
            except (IOError, OSError):
                continue
            if not CHANNEL_RE.search(text):
                continue
            if should_skip_for_stub(text):
                continue
            pos = frontmatter_insert_pos(text)
            new_text = text[:pos] + STUB + text[pos:]
            if re.search(r"when_updated:\s*['\"][0-9]+['\"]", new_text):
                new_text = bump_when_updated(new_text, ts)
            with open(path, "w", encoding="utf-8", newline="\n") as f:
                f.write(new_text)
            n += 1
            print("[OK]", rel)
    return n


def main():
    if len(sys.argv) < 2:
        print("Usage: insert_channel_docs_pointer_stubs.py <UTC_14_digits>")
        return 1
    ts = sys.argv[1].strip()
    if len(ts) != 14 or not ts.isdigit():
        print("Bad timestamp", ts)
        return 1
    if process_channels_md(ts):
        print("[OK] docs/doctrine/channels.md")
    else:
        print("[SKIP] docs/doctrine/channels.md (already has legacy pointer)")
    total = 0
    for sub in ("docs/channels", "docs/database"):
        root = os.path.join(REPO, sub)
        if os.path.isdir(root):
            total += process_tree(root, ts)
    print("Inserted stub into", total, "files")
    return 0


if __name__ == "__main__":
    sys.exit(main())
