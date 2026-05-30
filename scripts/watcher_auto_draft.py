#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/watcher_auto_draft.py"
#   questions_toon: null
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
Filesystem watcher (polling) for Lupopedia channel artifacts.

Goals (offline-first; no DB required):
- Monitor thread artifacts on disk
- Generate *draft* coordination artifacts (HEPHAESTUS-authored) without overwriting any files
- Respect actor boundaries: this tool does NOT write HERMES prompt files (actor_id 15)
- Enforce auto-draft safeguards (ATER001 gate + rate limit) for help_response only

Default behavior:
- Watches channels/{channel_id}/threads/{thread_id}/ for new/changed .md
- When an eligible file is detected (help_response + passes ATER001), emit a draft status
  artifact into channels/{channel_id}/threads/{out_thread_id}/.

Run (PowerShell):
  python scripts/watcher_auto_draft.py --repo-root . --channel 42 --watch-threads 1001,1002 --out-thread 1001
"""

from __future__ import annotations

import argparse
import json
import os
import re
import sys
import time
from datetime import datetime, timedelta, timezone
from pathlib import Path
from typing import Any, Dict, Tuple


CANONICAL_MD = re.compile(r"^[0-9]{8}_[0-9]{6}_[a-z][a-z0-9]*_[a-z][a-z0-9_-]+\.md$")
NUMERIC_THREAD = re.compile(r"^[1-9][0-9]{0,17}$")
SECTION_H2 = re.compile(r"^##\s+", re.MULTILINE)


def _utc_now() -> datetime:
    return datetime.now(timezone.utc)


def _ymdhis(dt: datetime) -> str:
    return dt.strftime("%Y%m%d_%H%M%S")


def _safe_mkdir(p: Path) -> None:
    if not p.is_dir():
        p.mkdir(parents=True, exist_ok=True)


def _read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8", errors="replace")


def _split_frontmatter(raw: str) -> Tuple[str, str]:
    if not raw.startswith("---"):
        return "", raw
    parts = raw.split("---", 2)
    if len(parts) < 3:
        return parts[1] if len(parts) > 1 else "", ""
    return parts[1], parts[2].lstrip("\n")


def _is_help_response_frontmatter(fm: str) -> bool:
    low = fm.lower()
    return (
        "artifact_kind: help_response" in low
        or 'artifact_kind: "help_response"' in low
        or "message_type: help_response" in low
    )


def _help_response_ater001_errors(path: Path) -> list[str]:
    """
    Mirror scripts/validate_channel_artifacts.py help_response rules:
    - body >= 200 chars
    - body has a '# ' title line
    - body has >= 3 '##' headings
    - body contains >= 3 '#' characters overall
    """
    try:
        raw = _read_text(path)
    except OSError as e:
        return ["READ_ERROR: %s (%s)" % (path.as_posix(), str(e))]
    fm, body = _split_frontmatter(raw)
    if not fm:
        return ["THREAD_FRONTMATTER: missing or incomplete YAML %s" % path.as_posix()]
    if not _is_help_response_frontmatter(fm):
        return ["NOT_HELP_RESPONSE: %s" % path.as_posix()]
    b = body.strip()
    if len(b) < 200:
        return ["THREAD_HELP_RESPONSE_SHORT: %s (body %s chars, need 200+)" % (path.as_posix(), len(b))]
    if b.count("#") < 3:
        return ["THREAD_HELP_RESPONSE_HASH: %s (need 3+ # in body)" % path.as_posix()]
    if not re.search(r"^#\s+\S", b, re.MULTILINE):
        return ["THREAD_HELP_RESPONSE_H1: %s (need # title line)" % path.as_posix()]
    n = len(SECTION_H2.findall(b))
    if n < 3:
        return ["THREAD_HELP_RESPONSE_SECTIONS: %s (need 3+ ## headings, got %s)" % (path.as_posix(), n)]
    return []


def _load_state(path: Path) -> Dict[str, Any]:
    if not path.is_file():
        return {"seen": {}, "rate": {}}
    try:
        return json.loads(path.read_text(encoding="utf-8", errors="replace"))
    except Exception:
        return {"seen": {}, "rate": {}, "state_read_error": True}


def _save_state(path: Path, state: Dict[str, Any]) -> None:
    tmp = path.with_suffix(".tmp")
    tmp.write_text(json.dumps(state, indent=2, sort_keys=True), encoding="utf-8")
    tmp.replace(path)


def _rate_bucket(now: datetime) -> str:
    # UTC hour bucket: YYYYMMDDHH
    return now.strftime("%Y%m%d%H")


def _rate_allow(state: Dict[str, Any], channel_id: int, max_per_hour: int, now: datetime) -> Tuple[bool, str]:
    key = "%s:%s" % (channel_id, _rate_bucket(now))
    rate = state.get("rate", {})
    used = int(rate.get(key, 0))
    if used >= max_per_hour:
        return False, "RATE_LIMIT: channel %s bucket %s used %s max %s" % (channel_id, key, used, max_per_hour)
    rate[key] = used + 1
    state["rate"] = rate
    return True, ""


def _unique_out_path(out_dir: Path, base_name: str) -> Path:
    """
    Ensure we never overwrite an existing artifact.
    If base exists, append -02, -03, ... before .md.
    """
    p = out_dir / base_name
    if not p.exists():
        return p
    stem = base_name[:-3] if base_name.endswith(".md") else base_name
    for i in range(2, 1000):
        cand = out_dir / ("%s-%02d.md" % (stem, i))
        if not cand.exists():
            return cand
    raise RuntimeError("unable to find unique filename for %s" % base_name)


def _emit_draft_status(
    *,
    repo_root: Path,
    channel_id: int,
    out_thread_id: str,
    source_rel: str,
    target_actor_slug: str,
    purpose_slug: str,
    gate_notes: list[str],
) -> Path:
    now = _utc_now()
    ts = _ymdhis(now)
    out_dir = repo_root / "channels" / str(channel_id) / "threads" / str(out_thread_id)
    _safe_mkdir(out_dir)

    fname = "%s_hephaestus_status_watcher-auto-draft.md" % ts
    out_path = _unique_out_path(out_dir, fname)

    notes = "\n".join(["- " + x for x in gate_notes]) if gate_notes else "- (none)"
    source_posix = source_rel.replace("\\", "/")
    cmd = (
        "python scripts/draft_hermes_prompt_from_artifact.py "
        "--artifact \"%s\" --target %s --purpose %s --write"
        % (source_posix, target_actor_slug, purpose_slug)
    )

    body = (
        "---\n"
        "lupopedia.headers:\n"
        "  lupopedia.version: \"4.0.81\"\n"
        "  file_path_from_root: \"%s\"\n"
        "  channel_id: %s\n"
        "  thread_id: %s\n"
        "  actor_id: 14\n"
        "  actor_name: \"hephaestus\"\n"
        "  artifact_type: \"thread\"\n"
        "  artifact_kind: \"status\"\n"
        "  message_type: \"status\"\n"
        "  purpose: \"watcher auto-draft suggestion (no prompts emitted)\"\n"
        "  source_artifact: \"%s\"\n"
        "  target_actor_slug: \"%s\"\n"
        "  status: \"draft\"\n"
        "---\n"
        "\n"
        "# file: HEPHAESTUS — watcher auto-draft suggestion\n"
        "\n"
        "## What happened\n"
        "\n"
        "A new/changed thread artifact was detected that appears eligible for **help_response** auto-draft.\n"
        "This watcher **does not write** to `prompts/` (HERMES boundary). It emits a **draft status** with the exact command to generate a HERMES-shaped draft prompt for human/HERMES review.\n"
        "\n"
        "## Source artifact\n"
        "\n"
        "- Path: `%s`\n"
        "\n"
        "## Safeguards / gates applied\n"
        "\n"
        "%s\n"
        "\n"
        "## Suggested next action (manual / HERMES-reviewed)\n"
        "\n"
        "Run:\n"
        "\n"
        "```bash\n"
        "%s\n"
        "```\n"
        "\n"
        "## Why no prompt file was written automatically\n"
        "\n"
        "- **Actor boundary**: prompt files in `channels/42/prompts/` must be authored by **HERMES (actor_id 15)**.\n"
        "- **Policy default**: prompt emission is human-gated by default; watcher-only classification + suggestion is safe offline behavior.\n"
        "\n"
    ) % (
        out_path.relative_to(repo_root).as_posix(),
        channel_id,
        out_thread_id,
        source_posix,
        target_actor_slug,
        source_posix,
        notes,
        cmd,
    )

    out_path.write_text(body, encoding="utf-8")
    return out_path


def _iter_candidate_files(repo_root: Path, channel_id: int, thread_ids: list[str]) -> list[Path]:
    out: list[Path] = []
    base = repo_root / "channels" / str(channel_id) / "threads"
    for tid in thread_ids:
        if not NUMERIC_THREAD.match(tid):
            continue
        d = base / tid
        if not d.is_dir():
            continue
        for p in d.glob("*.md"):
            if p.name == "README.md":
                continue
            if not CANONICAL_MD.match(p.name):
                continue
            out.append(p)
    return out


def main() -> int:
    ap = argparse.ArgumentParser(description="Lupopedia filesystem watcher (draft auto-drafts)")
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--channel", type=int, default=42)
    ap.add_argument("--watch-threads", default="1001,1002", help="Comma-separated numeric thread dirs to watch")
    ap.add_argument("--out-thread", default="1001", help="Numeric thread dir to write draft status artifacts into")
    ap.add_argument("--poll-seconds", type=float, default=2.0)
    ap.add_argument("--state-file", default="scripts/state/watcher_auto_draft_state.json")
    ap.add_argument("--max-drafts-per-hour", type=int, default=10)
    ap.add_argument("--target-actor-slug", default="wolfie")
    ap.add_argument("--purpose-slug", default="help_response_followup")
    ap.add_argument("--once", action="store_true", help="Run one scan and exit (no loop)")
    args = ap.parse_args()

    repo_root = Path(args.repo_root).resolve()
    state_path = (repo_root / args.state_file).resolve()
    _safe_mkdir(state_path.parent)

    thread_ids = [x.strip() for x in args.watch_threads.split(",") if x.strip()]
    out_thread = str(args.out_thread).strip()
    if not NUMERIC_THREAD.match(out_thread):
        print("Invalid --out-thread: %s" % out_thread, file=sys.stderr)
        return 2

    state = _load_state(state_path)
    seen: Dict[str, Any] = state.get("seen", {})
    state["seen"] = seen

    def scan_once() -> int:
        now = _utc_now()
        emitted = 0
        for p in _iter_candidate_files(repo_root, int(args.channel), thread_ids):
            rel = p.relative_to(repo_root).as_posix()
            try:
                st = p.stat()
            except OSError:
                continue
            key = rel
            last_mtime = float(seen.get(key, 0.0))
            mtime = float(st.st_mtime)
            if mtime <= last_mtime:
                continue
            seen[key] = mtime

            gate_notes: list[str] = []
            errs = _help_response_ater001_errors(p)
            if errs and errs[0].startswith("NOT_HELP_RESPONSE"):
                continue
            if errs:
                gate_notes.append("ATER001_FAIL: %s" % "; ".join(errs))
                continue
            gate_notes.append("ATER001_PASS: help_response body contract satisfied")

            ok, why = _rate_allow(state, int(args.channel), int(args.max_drafts_per_hour), now)
            if not ok:
                gate_notes.append(why)
                continue
            gate_notes.append("RATE_LIMIT_PASS: <= %s/hour" % int(args.max_drafts_per_hour))

            out_path = _emit_draft_status(
                repo_root=repo_root,
                channel_id=int(args.channel),
                out_thread_id=out_thread,
                source_rel=rel,
                target_actor_slug=str(args.target_actor_slug).strip().lower(),
                purpose_slug=str(args.purpose_slug).strip().lower(),
                gate_notes=gate_notes,
            )
            emitted += 1
            print("EMITTED: %s" % out_path.relative_to(repo_root).as_posix())
        _save_state(state_path, state)
        return emitted

    if args.once:
        scan_once()
        return 0

    while True:
        scan_once()
        time.sleep(float(args.poll_seconds))


if __name__ == "__main__":
    raise SystemExit(main())
