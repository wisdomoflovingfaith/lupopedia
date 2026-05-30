# -*- coding: utf-8 -*-
"""
Insert canonical channel clarification into docs/prd/*.md and
docs/prd/decisions/pseudocode/*.md when channels are discussed and block absent.
Body-only; YAML unchanged.
"""
from __future__ import print_function

import os
import re
import sys

REPO = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))

IDEMPOTENT = "semantic container inside a domain (node)"

BLOCK = """## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

"""


def insert_point(text, basename):
    if IDEMPOTENT in text:
        return -1
    if not re.search(r"(?i)channel", text):
        return -1

    # PRD 16_C: before "## 1. Purpose" (do not use generic Conceptual Model here)
    if basename.startswith("16_C"):
        m = re.search(r"\n## 1\. Purpose\s*\n", text)
        if m:
            return m.start() + 1

    # PRD 02_A: after Channel and Thread Model heading
    m = re.search(r"(^## Channel and Thread Model\s*\n)", text, re.MULTILINE)
    if m:
        return m.end()

    # PRD 02_B: before Projection and Presence Model
    if basename.startswith("02_B"):
        m = re.search(r"\n## Projection and Presence Model \(Normative\)\s*\n", text)
        if m:
            return m.start() + 1

    # PRD 02_C: before Projection (cardinality subsection follows in same PRD)
    if basename.startswith("02_C"):
        m = re.search(r"\n## Projection and Presence Model \(Normative\)\s*\n", text)
        if m:
            return m.start() + 1

    # PRD 02_D: before first major normative section
    if basename.startswith("02_D"):
        m = re.search(r"\n## UI Implementation Doctrine", text)
        if m:
            return m.start() + 1

    m = re.search(r"(^## Conceptual Model\s*\n)", text, re.MULTILINE)
    if m:
        return m.end()

    m = re.search(r"(^## Definitions\s*\n)", text, re.MULTILINE)
    if m:
        return m.end()

    m = re.search(r"(^## Definition\s*\n)", text, re.MULTILINE)
    if m:
        return m.end()

    m = re.search(r"\n## Change History\s*\n", text)
    if m:
        return m.start() + 1

    m = re.search(r"\n## 1\. Purpose\s*\n", text)
    if m:
        return m.start() + 1

    m = re.search(r"\n## (?!Table of Contents\b)[^\n]+\n", text)
    if m:
        return m.start() + 1

    return -1


def process_dir(dirpath):
    n = 0
    for name in sorted(os.listdir(dirpath)):
        if not name.endswith(".md"):
            continue
        path = os.path.join(dirpath, name)
        if not os.path.isfile(path):
            continue
        with open(path, "r", encoding="utf-8", newline="") as f:
            text = f.read()
        pos = insert_point(text, name)
        if pos < 0:
            continue
        new_text = text[:pos] + BLOCK + text[pos:]
        with open(path, "w", encoding="utf-8", newline="") as f:
            f.write(new_text)
        print(os.path.relpath(path, REPO).replace("\\", "/"))
        n += 1
    return n


def main():
    total = 0
    total += process_dir(os.path.join(REPO, "docs", "prd"))
    pseudo = os.path.join(REPO, "docs", "prd", "decisions", "pseudocode")
    if os.path.isdir(pseudo):
        total += process_dir(pseudo)
    print("TOTAL", total, file=sys.stderr)


if __name__ == "__main__":
    main()
