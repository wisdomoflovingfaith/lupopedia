#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-scripts/lib/lupopedia_markdown_header_peel.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/lib/lupopedia_markdown_header_peel.py"
#   status: "complete"
#   when_updated: "20260411035853"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/lupopedia-markdown-header-peel.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/lupopedia-markdown-header-peel"
#   artifact_type: implementation
#   artifact_kind: library
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   content_slug: ""
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Lupopedia markdown header peel utility"
#   summary: "Peel leading lupopedia.headers YAML blocks from Markdown"
# ---------------------------------------------------------------------
"""
Peel consecutive YAML front-matter blocks that contain lupopedia.headers.

Used to detect duplicate headers (HDR_MULTIPLE_HEADERS) and to repair files
where a second block was appended after the first (often with a stray BOM).

**Parsing strategy**
1. **25-line mechanical envelope (PRD 16 v4.0.99):** Fast path when the dense
   22-key block and closing ``---`` match (no blank lines inside keys).
2. **Legacy v4.0.0–98 fast path:** 20 keys + two blank inner lines before ``---``.
3. **Line-based fallback:** If the fast path fails, scan line-by-line for the
   first standalone ``---`` line (strip-only) that closes a block whose joined
   inner text still contains ``lupopedia.headers:``. This avoids a single
   naive ``\\n---\\n`` substring match inside the YAML region in some cases;
   it is not a full YAML parser (quoted multiline edge cases remain rare for
   canonical headers).

Optional: :func:`validate_lupopedia_header_inner_keys_present` checks all 22
PRD 16 v4.0.99 scalar keys using ``header_spec_v3_1.V4_HEADER_KEYS_ORDERED``.
"""

from __future__ import annotations

import re
import sys
from functools import lru_cache
from typing import List, Tuple, Union

try:
    from .header_spec_v3_1 import V4_HEADER_KEYS_ORDERED
except ImportError:
    from header_spec_v3_1 import V4_HEADER_KEYS_ORDERED

_WHEN_UPDATED_RE = re.compile(r"when_updated:\s*[\"']?(\d{14})[\"']?", re.MULTILINE)
# _LAST_MOD_RE: kept for backward compatibility with pre-v4.0.99 files that still
# carry last_modified_utc.  In v4.0.99+ this field was renamed to questions_toon
# (PRD 16 §4.2 field 6), which is null or a .questions.toon path — not a timestamp.
# header_timestamp_tuple() therefore uses _LAST_MOD_RE only as a legacy tie-break
# for old files; new files will always produce lm=0 from this regex.
_LAST_MOD_RE = re.compile(r"last_modified_utc:\s*[\"']?(\d{14})[\"']?", re.MULTILINE)

# Per-key line start after ``lupopedia.headers:`` (two-space indent, PRD 16 Markdown)
_KEY_LINE_RE_TEMPLATE = r"(?m)^  %s:\s"


def _normalize_content_input(content: Union[str, bytes, None]) -> str:
    if content is None:
        raise TypeError("content must be str or bytes, not None")
    if isinstance(content, bytes):
        # utf-8-sig strips BOM if present
        return content.decode("utf-8-sig")
    if not isinstance(content, str):
        raise TypeError("content must be str or bytes, got %s" % type(content).__name__)
    return content


def _fallback_block_from_open_fence(content: str, idx: int, n: int) -> Tuple[str, int] | None:
    """
    If ``content[idx:]`` starts with ``---\\n``, consume lines until a standalone
    ``---`` line closes the block. Return (inner_without_outer_fences, advance_from_idx).

    The closing line is not part of ``inner``. If no valid close is found, return None.
    """
    if idx >= n or not content.startswith("---\n", idx):
        return None
    pos = idx + 4
    buf_lines: List[str] = []
    while pos < n:
        nl = content.find("\n", pos)
        if nl < 0:
            line = content[pos:n]
            next_pos = n
        else:
            line = content[pos:nl]
            next_pos = nl + 1
        if line.strip() == "---" and buf_lines:
            inner = "\n".join(buf_lines)
            if "lupopedia.headers:" in inner:
                return (inner, next_pos - idx)
            buf_lines.append(line)
        else:
            buf_lines.append(line)
        pos = next_pos
    return None


def peel_leading_lupopedia_yaml_blocks(content: Union[str, bytes, None]) -> Tuple[List[str], str]:
    """
    Extract consecutive LUPOPEDIA YAML header blocks from the start of Markdown.

    Returns ``(list_of_inner_yaml_bodies, remaining_content)``. Each inner string
    is the text between the opening ``---\\n`` and the closing ``---`` (exclusive
    of the outer fence lines), and includes the ``lupopedia.headers:`` line plus
    key lines as stored in the file.

    **Args**
        content: Full file text. May include a leading UTF-8 BOM. Pass ``bytes``
        to decode as UTF-8 (with BOM strip).

    **Stops peeling when**
        The next segment does not begin a Lupopedia header block (so a lone
        Markdown horizontal rule is not consumed as a header).

    **Example**

    .. code-block:: python

        text = "---\\nlupopedia.headers:\\n  title: \\"x\\"\\n\\n\\n---\\n# Body"
        inners, body = peel_leading_lupopedia_yaml_blocks(text)
        assert len(inners) == 1
        assert body.lstrip().startswith("# Body")

    This function does not mutate the input string (BOM strip uses a slice view
    for the str case only).
    """
    content = _normalize_content_input(content)
    if content.startswith("\ufeff"):
        content = content[1:]
    content = content.replace("\r\n", "\n")
    inners: List[str] = []
    idx = 0
    n = len(content)
    while idx < n:
        try:
            while idx < n and content[idx] in "\r\n \t":
                idx += 1
            if idx < n and content[idx] == "\ufeff":
                idx += 1
            if idx >= n or not content.startswith("---\n", idx):
                break
            tail = content[idx + 4 :]
            lines = tail.split("\n")
            inner = ""
            advanced = 0
            # v4.0.99 dense: tail[0]=lupopedia.headers:, tail[1:23]=22 keys, tail[23]=---
            if len(lines) >= 24 and lines[23].strip() == "---":
                dense_ok = bool(lines[0].strip() == "lupopedia.headers:")
                if dense_ok:
                    for j in range(1, 23):
                        if j >= len(lines) or not lines[j].strip():
                            dense_ok = False
                            break
                if dense_ok:
                    inner_part = "\n".join(lines[0:23]) + "\n---\n"
                    prefix = "---\n" + inner_part
                    if idx + len(prefix) <= n and content[idx : idx + len(prefix)] == prefix:
                        inner = "\n".join(lines[0:23])
                        advanced = len(prefix)
            # Legacy: 21 inner lines + two blanks + closing --- (lines[0:21], blank, blank, ---)
            if not advanced and (
                len(lines) >= 24
                and not lines[21].strip()
                and not lines[22].strip()
                and lines[23].strip() == "---"
            ):
                inner_part = "\n".join(lines[0:21]) + "\n\n\n---\n"
                prefix = "---\n" + inner_part
                if idx + len(prefix) <= n and content[idx : idx + len(prefix)] == prefix:
                    inner = "\n".join(lines[0:21])
                    advanced = len(prefix)
            if not advanced:
                fb = _fallback_block_from_open_fence(content, idx, n)
                if fb is None:
                    break
                inner, adv = fb
                advanced = adv
            if "lupopedia.headers:" not in inner:
                break
            inners.append(inner)
            idx += advanced
        except (IndexError, ValueError) as e:
            sys.stderr.write(
                "[WARN] lupopedia_markdown_header_peel: stopped at byte %s: %s\n"
                % (idx, e)
            )
            break
    body = content[idx:]
    return inners, body


@lru_cache(maxsize=256)
def _header_timestamp_key(inner: str) -> Tuple[int, int]:
    m = _WHEN_UPDATED_RE.search(inner)
    w = int(m.group(1)) if m else 0
    m2 = _LAST_MOD_RE.search(inner)
    lm = int(m2.group(1)) if m2 else 0
    return (w, lm)


def header_timestamp_tuple(inner: str) -> Tuple[int, int]:
    """Return (when_updated, legacy_last_modified_utc) as 14-digit ints for sorting.

    The second element is always 0 for v4.0.99+ files (last_modified_utc was renamed
    to questions_toon in PRD 16 v4.0.99 §4.2 field 6 — it is no longer a timestamp).
    Legacy pre-v4.0.99 files that still carry last_modified_utc will produce a non-zero
    second element as a tie-break; that behaviour is preserved for backward compatibility.
    """
    return _header_timestamp_key(inner)


def select_newest_lupopedia_header_inner(inners: List[str]) -> str:
    """When multiple duplicate blocks exist, keep the one with newest when_updated (then legacy last_modified_utc as tie-break for pre-v4.0.99 files)."""
    if not inners:
        return ""
    return max(inners, key=_header_timestamp_key)


def validate_lupopedia_header_inner_keys_present(inner: str) -> bool:
    """
    Return True if ``inner`` contains all 22 v4.1.2 scalar keys (or legacy names
    that map to them) as two-space-indented line starts.

    Legacy alias chains (accepted with HDR_PK_LEGACY_ALIAS in the full validator):
      content_id       <- pk_id
      content_parent_id <- parent_pk_id
      content_slug     <- pk_slug
      default_collection_id  (no legacy alias; required for v4.1.x files)

    This is a **presence** check only; it does not validate values or key order.
    """
    if "lupopedia.headers:" not in inner:
        return False
    for key in V4_HEADER_KEYS_ORDERED:
        pat = re.compile(_KEY_LINE_RE_TEMPLATE % re.escape(key))
        if pat.search(inner):
            continue
        # Legacy alias candidates for canonical content_* fields
        legacy_aliases: list = []
        if key == "content_id":
            legacy_aliases = ["pk_id"]
        elif key == "content_parent_id":
            legacy_aliases = ["parent_pk_id"]
        elif key == "content_slug":
            legacy_aliases = ["pk_slug"]
        # default_collection_id: no legacy alias — required in v4.1.x
        for legacy in legacy_aliases:
            pat2 = re.compile(_KEY_LINE_RE_TEMPLATE % re.escape(legacy))
            if pat2.search(inner):
                break
        else:
            # for/else: the loop completed without break — no alias matched (or no aliases)
            return False
    return True


def has_duplicate_lupopedia_headers(content: Union[str, bytes, None]) -> bool:
    """True if ``content`` starts with two or more consecutive Lupopedia YAML blocks."""
    inners, _ = peel_leading_lupopedia_yaml_blocks(content)
    return len(inners) > 1


def get_newest_lupopedia_header_inner_from_content(content: Union[str, bytes, None]) -> str:
    """
    From full file text, return the newest inner header body (same rule as
    :func:`select_newest_lupopedia_header_inner`), or ``""`` if none peeled.
    """
    inners, _ = peel_leading_lupopedia_yaml_blocks(content)
    return select_newest_lupopedia_header_inner(inners)
