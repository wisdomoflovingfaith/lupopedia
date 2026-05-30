# -*- coding: utf-8 -*-
"""One-off: split 02_channels_discussions.md into three PRD files (do not remove original)."""
from __future__ import print_function

import os

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC = os.path.join(ROOT, "docs", "prd", "02_channels_discussions.md")
WHEN = "20260419030946"


def read_lines_fixed(path):
    with open(path, "r", encoding="utf-8") as f:
        return f.readlines()


def join_ranges(lines, ranges):
    """ranges: list of (start1, end1) inclusive 1-based line numbers."""
    out = []
    for a, b in ranges:
        out.extend(lines[a - 1 : b])
    return "".join(out)


def ascii_sanitize(s):
    reps = (
        ("\u2019", "'"),
        ("\u2018", "'"),
        ("\u201c", '"'),
        ("\u201d", '"'),
        ("\u2026", "..."),
        ("\u2014", "--"),
        ("\u2013", "-"),
        ("\u00a0", " "),
        ("\u2192", "->"),
        ("\u2705", "[OK] "),
        ("\u274c", "[NO] "),
        ("\U0001f680", "[SEND] "),
        ("\U0001f4dd", "[DRAFT] "),
    )
    for a, b in reps:
        s = s.replace(a, b)
    return s


def fix_prd_links(s):
    s = s.replace("(docs/prd/", "(")
    s = s.replace("](docs/prd/", "](")
    s = s.replace("[PRD 82](82_hermes_message_routing_memory_gateway.md)", "[PRD 82](82_hermes_message_routing_memory_gateway.md)")
    return s


def nav_db():
    return (
        "> **Split navigation (PRD 02 family):** This file is **database DDL and color data** only. "
        "Normative routing and projection: **[02_channels_db_overview.md](02_channels_db_overview.md)**. "
        "UI mockups, APIs, transport: **[02_channels_mockups_modules.md](02_channels_mockups_modules.md)**.\n\n"
    )


def nav_over():
    return (
        "> **Split navigation (PRD 02 family):** This file is **core channel doctrine** (projection, presence, agent rules). "
        "DDL and color YAML: **[02_channels_db_design.md](02_channels_db_design.md)**. "
        "UI and implementation surfaces: **[02_channels_mockups_modules.md](02_channels_mockups_modules.md)**.\n\n"
    )


def nav_mock():
    return (
        "> **Split navigation (PRD 02 family):** This file is **UI, workflow, orchestration chrome, APIs, transport**. "
        "Core doctrine: **[02_channels_db_overview.md](02_channels_db_overview.md)**. "
        "Database tables and color DDL: **[02_channels_db_design.md](02_channels_db_design.md)**.\n\n"
    )


def hdr_db():
    return """---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/prd/02_channels_db_design.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/02_channels_db_design.md"
  status: "active"
  when_updated: "%s"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/02-channels-db-design.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/channels-db-design"
  artifact_type: prd
  artifact_kind: specification
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "02-channels-db-design"
  default_collection_id: null
  lupopedia.schema: prd
  title: "PRD 02 -- Channels Database Design"
  summary: "Database schema for channels threads messages tasks recent files agent colors and performance indexes."
---
""" % WHEN


def hdr_over():
    return """---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/prd/02_channels_db_overview.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/02_channels_db_overview.md"
  status: "active"
  when_updated: "%s"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/02-channels-db-overview.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/channels-db-overview"
  artifact_type: prd
  artifact_kind: specification
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "02-channels-db-overview"
  default_collection_id: null
  lupopedia.schema: prd
  title: "PRD 02 -- Channels Core Overview"
  summary: "Channel vs thread projection presence visibility agent write-only doctrine release line task intake."
---
""" % WHEN


def hdr_mock():
    return """---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/prd/02_channels_mockups_modules.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/02_channels_mockups_modules.md"
  status: "active"
  when_updated: "%s"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/02-channels-mockups-modules.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/channels-mockups-modules"
  artifact_type: prd
  artifact_kind: specification
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "02-channels-mockups-modules"
  default_collection_id: null
  lupopedia.schema: prd
  title: "PRD 02 -- Channels UI Mockups and Modules"
  summary: "UI specifications mockups template-first workflow orchestration surfaces APIs transport anti-patterns."
---
""" % WHEN


def main():
    lines = read_lines_fixed(SRC)
    n = len(lines)
    if n < 2300:
        raise SystemExit("unexpected short source: %s lines" % n)

    # FILE 1: DDL blocks
    body1 = join_ranges(
        lines,
        [
            (869, 947),  # thread color + agent colors CREATE
            (978, 1042),  # recent files + hooks
            (1062, 1104),  # task DDL
            (1927, 1944),  # CREATE INDEX performance
        ],
    )
    body1 = (
        "# PRD 02 -- Channels Database Design\n\n"
        + nav_db()
        + "This extract from the PRD 02 family covers **normative DDL**, **color configuration data**, "
        "and **index** guidance for channel dialog surfaces. Routing and UI rules live in sibling files.\n\n"
        + "---\n\n"
        + body1
    )
    body1 = ascii_sanitize(body1)
    body1 = fix_prd_links(body1)

    # FILE 2: core overview
    body2 = join_ranges(
        lines,
        [
            (82, 114),  # projection through THOTH
            (115, 131),  # release line table
            (188, 263),  # chat is not a conversation
            (265, 275),  # header memory
            (949, 962),  # agent integration table only (stop before Agent Wrapper)
            (1044, 1061),  # task purpose + syntax
            (1106, 1150),  # agent task polling
            (355, 432),  # target tabs + observer doctrine through Active Output Rule
            (2279, 2297),  # anchored truth doctrine
        ],
    )
    body2 = (
        "# PRD 02 -- Channels Core Overview\n\n"
        + nav_over()
        + "---\n\n"
        + body2
    )
    body2 = ascii_sanitize(body2)
    body2 = fix_prd_links(body2)

    # FILE 3: mockups + orchestration chrome + APIs + tail
    body3 = join_ranges(
        lines,
        [
            (132, 145),  # 4.1.3 scope
            (147, 184),  # staged + template + language
            (277, 354),  # orchestration human + context + multi-channel
            (433, 488),  # updated visual + canonical ref
            (490, 867),  # unified UI through dual-button
            (963, 977),  # agent wrapper script
            (1152, 1166),  # task system API + UI integration (before task rendering)
            (1168, 1340),  # task rendering + impl patterns (through message rendering closing ```)
            (1341, 1862),  # API + chat UI + tab nav + transport (ends before Anti-Patterns at 1863)
            (1863, 2127),  # anti-patterns through security
            (2128, 2144),  # implementation phases
            (2146, 2247),  # HERMES memory
        ],
    )
    # Replace thread-specific color duplicate with cross-ref (lines were inside 490-867)
    # Instead post-process: insert note after "### Thread-Specific Colors" heading inside body3
    body3 = (
        "# PRD 02 -- Channels UI Mockups and Modules\n\n"
        + nav_mock()
        + "---\n\n"
        + body3
    )
    note = (
        "\n> **Color sequences and CREATE TABLE for `lupo_agent_colors` / `lupo_dialog_recent_files` / "
        "`lupo_dialog_pending_tasks`:** see **[02_channels_db_design.md](02_channels_db_design.md)** "
        "(single source for DDL in this family).\n\n"
    )
    body3 = body3.replace(
        "### Thread-Specific Colors (Not Agent, Not Channel)\n",
        "### Thread-Specific Colors (Not Agent, Not Channel)\n" + note,
        1,
    )
    body3 = ascii_sanitize(body3)
    body3 = fix_prd_links(body3)

    # Cross-references + summary (rewritten)
    tail = """
## Cross-References

This PRD split family references and is referenced by:

- **[PRD 00](00_root_constitutional_system_requirements.md)** -- Constitutional system requirements and limits
- **[PRD 17](17_decisions_format.md)** -- Decision threads; staged workflow alignment
- **[PRD 16](16_lupopedia_headers.md)** -- Header and metadata requirements
- **[PRD 45](45_template_first_staged_ui_workflow.md)** -- Template-first staged UI
- **[PRD 82](82_hermes_message_routing_memory_gateway.md)** -- HERMES full specification
- **[02_channels_db_overview.md](02_channels_db_overview.md)** -- Projection, presence, agent rules (this family)
- **[02_channels_db_design.md](02_channels_db_design.md)** -- DDL and color data (this family)
- **[HERMES_DOCTRINE.md](../doctrine/HERMES_DOCTRINE.md)** -- HERMES routing rules
- **[install_new_lupopedia.sql](../../database/lupopedia/mysql/install/install_new_lupopedia.sql)** -- Database schema source of truth

## Summary

This file (**02_channels_mockups_modules.md**) carries **UI layout**, **orchestration chrome** (where applicable by release line), **template-first workflow**, **API shapes**, **transport polling doctrine**, **anti-patterns**, **security**, and **HERMES transcript/memory gateway** protocol excerpts. **Projection and presence** law lives in **02_channels_db_overview.md**. **DDL** lives in **02_channels_db_design.md**. The monolithic **`02_channels_discussions.md`** remains in-repo until deprecation is decided; treat these three files as the **focused** surfaces for new edits.

**Canonical merge note:** Until **`02_channels_discussions.md`** is formally deprecated, reconcile any emergency edits across both the monolith and this split set.

"""
    body3 = body3.rstrip() + "\n" + tail

    out1 = os.path.join(ROOT, "docs", "prd", "02_channels_db_design.md")
    out2 = os.path.join(ROOT, "docs", "prd", "02_channels_db_overview.md")
    out3 = os.path.join(ROOT, "docs", "prd", "02_channels_mockups_modules.md")

    with open(out1, "w", encoding="utf-8", newline="\n") as f:
        f.write(hdr_db() + body1)
    with open(out2, "w", encoding="utf-8", newline="\n") as f:
        f.write(hdr_over() + body2)
    with open(out3, "w", encoding="utf-8", newline="\n") as f:
        f.write(hdr_mock() + body3)

    print("Wrote", out1)
    print("Wrote", out2)
    print("Wrote", out3)


if __name__ == "__main__":
    main()
