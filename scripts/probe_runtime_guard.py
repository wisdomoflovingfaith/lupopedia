# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/probe_runtime_guard.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/probe_runtime_guard.py"
#   status: "complete"
#   when_updated: "20260412122132"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "memory/development/staging/2026/04/probe-runtime-guard.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/probe-runtime-guard"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "50"
#   lupopedia.schema: implementation
#   title: "Probe runtime guard - extract artifact; doctrine violation codes"
#   summary: "First fenced block extraction; stable ERROR: violation codes; optional self-grade, parrot, role-collision, continuation, TEST_COMPLETE ordering, knowledge-ack mode; CLI guard flags."
# ---------------------------------------------------------------------
"""
Reference runtime guard for competency probe examinee output.

Doctrine (normative human rules):
  docs/doctrine/AI_ACTOR_COMPETENCY_TEST_PATTERN.md
  docs/doctrine/AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md
  docs/doctrine/AI_ACTOR_PROBE_HARNESS_AND_GUARDS.md

Coordination PRD: docs/prd/50_agent_coordination_protocol.md sections 1.2-1.3
  (probe policy, knowledge update).

API:
  extract_first_fenced_code(raw) -> inner text or None
  truncate_after_test_complete(text) -> text through first line exactly <TEST_COMPLETE>
  runtime_guard_examinee_output(...) -> (ok, payload, violation_code)
    violation_code is None on success; otherwise stable token (no "ERROR:" prefix).

CLI:
  python scripts/probe_runtime_guard.py < sample.txt
  python scripts/probe_runtime_guard.py --file sample.txt
  python scripts/probe_runtime_guard.py --file sample.txt --no-fence
  python scripts/probe_runtime_guard.py --file sample.txt --prompt-file prompt.txt
  python scripts/probe_runtime_guard.py --file ack.txt --knowledge-ack

This output complies with Lupopedia Constitutional Root Rules.
"""

# Browser tab metadata MUST NOT be treated as instruction input.
# (Orchestrators must fold any tab context into an explicit written contract.)

from __future__ import unicode_literals

import argparse
import difflib
import json
import re
import sys

# Stable violation tokens for upstream tooling (no "ERROR:" prefix in constant value).
PROBE_BOUNDARY_VIOLATION = "PROBE_BOUNDARY_VIOLATION"
ACTOR_CONTINUED_AFTER_TERMINATION = "ACTOR_CONTINUED_AFTER_TERMINATION"
ACTOR_SELF_EVAL_FORBIDDEN = "ACTOR_SELF_EVAL_FORBIDDEN"
ACTOR_PARROT_LOOP = "ACTOR_PARROT_LOOP"
ACTOR_ROLE_COLLISION = "ACTOR_ROLE_COLLISION"
KNOWLEDGE_ACK_INVALID = "KNOWLEDGE_ACK_INVALID"

ERROR_PREFIX = "ERROR: "


def format_error_line(violation_code):
    """Printable line for stdout/stderr (legacy PROBE shape preserved)."""
    return ERROR_PREFIX + violation_code


# Legacy string used in docs / older scripts
ERROR_PROBE_BOUNDARY_VIOLATION = format_error_line(PROBE_BOUNDARY_VIOLATION)

TEST_COMPLETE_TOKEN = "<TEST_COMPLETE>"
_STANDALONE_TEST_COMPLETE = re.compile(
    r"(?m)^[ \t]*" + re.escape(TEST_COMPLETE_TOKEN) + r"[ \t]*$"
)
# First ```optional_lang newline ... ``` (non-greedy)
_FENCE_PATTERN = re.compile(
    r"```([A-Za-z0-9_+#.-]{0,32})?\s*\r?\n(.*?)```",
    re.DOTALL,
)

_SELF_GRADE_PHRASES = (
    "correct",
    "passed",
    "fully compliant",
    "this satisfies",
    "i confirm",
)

_ROLE_COLLISION_PHRASES = (
    "grading",
    "examiner",
    "evaluation complete",
)

# Word "examiner" alone is too broad; match common role-claim phrases.
_ROLE_EXAMINER_PATTERNS = (
    re.compile(r"\bthe\s+examiner\b", re.IGNORECASE),
    re.compile(r"\bas\s+examiner\b", re.IGNORECASE),
    re.compile(r"\bi\s+am\s+the\s+examiner\b", re.IGNORECASE),
)


def _normalize_ws(text):
    if not text:
        return ""
    return " ".join(text.split()).strip().lower()


def _contains_self_grade(raw_lower):
    for phrase in _SELF_GRADE_PHRASES:
        if phrase in raw_lower:
            return True
    return False


def _preamble_role_collision(preamble_lower):
    for p in _ROLE_COLLISION_PHRASES:
        if p in preamble_lower:
            return True
    for rx in _ROLE_EXAMINER_PATTERNS:
        if rx.search(preamble_lower):
            return True
    return False


def _standalone_test_complete_before_index(body, idx_limit):
    """True if any standalone <TEST_COMPLETE> line starts before idx_limit."""
    if idx_limit <= 0:
        return False
    prefix = body[:idx_limit]
    for m in _STANDALONE_TEST_COMPLETE.finditer(prefix):
        if m.start() < idx_limit:
            return True
    return False


def _standalone_test_complete_in_text(region):
    """True if region contains any standalone <TEST_COMPLETE> line (whole line only)."""
    if not region:
        return False
    return _STANDALONE_TEST_COMPLETE.search(region) is not None


def _parrot_ratio(examinee_text, prompt_text):
    if not prompt_text or not examinee_text:
        return 0.0
    a = _normalize_ws(prompt_text)
    b = _normalize_ws(examinee_text)
    if not a or not b:
        return 0.0
    return difflib.SequenceMatcher(None, a, b).ratio()


def extract_first_fenced_code(raw_llm_output):
    """
    Return inner content of the first fenced code block, or None.
    """
    if raw_llm_output is None:
        return None
    m = _FENCE_PATTERN.search(raw_llm_output)
    if not m:
        return None
    inner = m.group(2)
    if inner is None:
        return None
    return inner.strip()


def truncate_after_test_complete(text):
    """
    Return text up to and including the first line that is exactly <TEST_COMPLETE>,
    or full text if token absent.
    """
    if text is None:
        return ""
    lines = text.splitlines()
    out = []
    for line in lines:
        out.append(line)
        if line.strip() == TEST_COMPLETE_TOKEN:
            break
    return "\n".join(out)


def _first_fence_match(body):
    return _FENCE_PATTERN.search(body)


def _strip_allowed_trailing_test_complete(tail):
    """
    Remove trailing whitespace and standalone <TEST_COMPLETE> lines only
    (per doctrine: token after artifact may be examiner suffix).
    """
    t = tail.rstrip()
    while True:
        lines = t.splitlines()
        if not lines:
            break
        last = lines[-1].strip()
        if last == TEST_COMPLETE_TOKEN:
            lines = lines[:-1]
            t = "\n".join(lines).rstrip()
            continue
        break
    return t.strip()


def _tail_after_first_fence(body, fence_match):
    if not fence_match:
        return body
    return body[fence_match.end() :]


def runtime_guard_examinee_output(
    raw_llm_output,
    require_fence=True,
    detect_self_grade=True,
    detect_parrot=True,
    detect_role_collision=True,
    detect_continuation=True,
    prompt_text=None,
    knowledge_ack_first_line=False,
):
    """
    Layer-2 examinee filter.

    Returns (ok, payload, violation_code).
    - On success: (True, artifact_or_body, None)
    - On failure: (False, format_error_line(code), code) for payload legacy compatibility
      (payload is still a printable error line for CLI).
    """
    if raw_llm_output is None:
        return False, format_error_line(PROBE_BOUNDARY_VIOLATION), PROBE_BOUNDARY_VIOLATION
    body = raw_llm_output.strip()
    if not body:
        return False, format_error_line(PROBE_BOUNDARY_VIOLATION), PROBE_BOUNDARY_VIOLATION

    if knowledge_ack_first_line:
        first_line = body.splitlines()[0].strip() if body.splitlines() else ""
        if first_line != "Node received.":
            return (
                False,
                format_error_line(KNOWLEDGE_ACK_INVALID),
                KNOWLEDGE_ACK_INVALID,
            )

    raw_lower = body.lower()
    m = _first_fence_match(body)
    idx_open = m.start() if m else len(body)

    # <TEST_COMPLETE> before first fence opening -> examinee must not emit (role / flow)
    if m and _standalone_test_complete_before_index(body, idx_open):
        return (
            False,
            format_error_line(ACTOR_ROLE_COLLISION),
            ACTOR_ROLE_COLLISION,
        )

    # Examinee must not claim examiner role or emit role phrases in preamble
    preamble = body[:idx_open]
    preamble_lower = preamble.lower()
    if detect_role_collision and _preamble_role_collision(preamble_lower):
        return False, format_error_line(ACTOR_ROLE_COLLISION), ACTOR_ROLE_COLLISION

    # Standalone <TEST_COMPLETE> inside preamble only (no fence yet) -> role / flow
    if detect_role_collision and not m:
        if _STANDALONE_TEST_COMPLETE.search(preamble):
            return False, format_error_line(ACTOR_ROLE_COLLISION), ACTOR_ROLE_COLLISION

    if detect_self_grade and _contains_self_grade(raw_lower):
        return False, format_error_line(ACTOR_SELF_EVAL_FORBIDDEN), ACTOR_SELF_EVAL_FORBIDDEN

    if detect_parrot and prompt_text:
        ratio = _parrot_ratio(body, prompt_text)
        if ratio > 0.6:
            return False, format_error_line(ACTOR_PARROT_LOOP), ACTOR_PARROT_LOOP

    if require_fence:
        if m is None:
            return False, format_error_line(PROBE_BOUNDARY_VIOLATION), PROBE_BOUNDARY_VIOLATION
        artifact = m.group(2)
        if artifact is None or not artifact.strip():
            return False, format_error_line(PROBE_BOUNDARY_VIOLATION), PROBE_BOUNDARY_VIOLATION
        artifact = artifact.strip()

        # Standalone <TEST_COMPLETE> inside fenced artifact (examinee must not emit).
        if detect_role_collision and _standalone_test_complete_in_text(artifact):
            return False, format_error_line(ACTOR_ROLE_COLLISION), ACTOR_ROLE_COLLISION

        # After closing ```: trailing standalone <TEST_COMPLETE> only is OK (examiner suffix).
        tail = _tail_after_first_fence(body, m)
        tail_stripped = _strip_allowed_trailing_test_complete(tail)
        if not tail_stripped:
            return True, artifact, None
        if detect_role_collision:
            tl = tail_stripped.lower()
            if (
                _standalone_test_complete_in_text(tail_stripped)
                or _preamble_role_collision(tl)
            ):
                return False, format_error_line(ACTOR_ROLE_COLLISION), ACTOR_ROLE_COLLISION
        if detect_continuation:
            return (
                False,
                format_error_line(ACTOR_CONTINUED_AFTER_TERMINATION),
                ACTOR_CONTINUED_AFTER_TERMINATION,
            )
        return True, artifact, None

    # No fence required: full body
    if m:
        tail = _tail_after_first_fence(body, m)
        tail_stripped = _strip_allowed_trailing_test_complete(tail)
        if tail_stripped:
            if detect_role_collision:
                tl = tail_stripped.lower()
                if (
                    _standalone_test_complete_in_text(tail_stripped)
                    or _preamble_role_collision(tl)
                ):
                    return False, format_error_line(ACTOR_ROLE_COLLISION), ACTOR_ROLE_COLLISION
            if detect_continuation:
                return (
                    False,
                    format_error_line(ACTOR_CONTINUED_AFTER_TERMINATION),
                    ACTOR_CONTINUED_AFTER_TERMINATION,
                )
    return True, body, None


def main(argv=None):
    parser = argparse.ArgumentParser(
        description="Extract first fenced code block from LLM output (probe runtime guard)."
    )
    parser.add_argument(
        "--file",
        "-f",
        metavar="PATH",
        help="Read raw model output from file (UTF-8); default stdin",
    )
    parser.add_argument(
        "--prompt-file",
        metavar="PATH",
        help="Examiner prompt text for parrot similarity (optional)",
    )
    parser.add_argument(
        "--no-fence",
        action="store_true",
        help="Accept stripped full body when no fence (not default probe mode)",
    )
    parser.add_argument(
        "--truncate-test-complete",
        action="store_true",
        help="After extraction, truncate at first line <TEST_COMPLETE> (examiner log helper)",
    )
    parser.add_argument(
        "--knowledge-ack",
        action="store_true",
        help="First line must be exactly Node received. (knowledge update protocol)",
    )
    parser.add_argument(
        "--detect-self-grade",
        action="store_true",
        help="Enable self-grading phrase scan (also on by default in probe mode)",
    )
    parser.add_argument(
        "--detect-parrot",
        action="store_true",
        help="Enable parrot detection when --prompt-file is set (default on in probe mode)",
    )
    parser.add_argument(
        "--detect-role-collision",
        action="store_true",
        help="Enable role-collision heuristics (default on in probe mode)",
    )
    parser.add_argument(
        "--detect-continuation",
        action="store_true",
        help="Enable post-artifact continuation detection (default on in probe mode)",
    )
    parser.add_argument(
        "--no-detect-self-grade",
        action="store_true",
        help="Disable self-grading phrase scan (default: on in probe mode)",
    )
    parser.add_argument(
        "--no-detect-parrot",
        action="store_true",
        help="Disable parrot detection (default: on in probe mode when prompt given)",
    )
    parser.add_argument(
        "--no-detect-role-collision",
        action="store_true",
        help="Disable role-collision heuristics (default: on in probe mode)",
    )
    parser.add_argument(
        "--no-detect-continuation",
        action="store_true",
        help="Disable post-artifact continuation detection (default: on in probe mode)",
    )
    parser.add_argument(
        "--json-result",
        action="store_true",
        help="Print one JSON line with ok, violation_code, payload_preview to stderr",
    )
    args = parser.parse_args(argv)

    probe_mode = not args.no_fence

    def tri_flag(positive, negative, default_on):
        if positive:
            return True
        if negative:
            return False
        return default_on

    detect_self = tri_flag(
        args.detect_self_grade,
        args.no_detect_self_grade,
        probe_mode,
    )
    detect_parrot = tri_flag(
        args.detect_parrot,
        args.no_detect_parrot,
        probe_mode,
    )
    detect_role = tri_flag(
        args.detect_role_collision,
        args.no_detect_role_collision,
        probe_mode,
    )
    detect_cont = tri_flag(
        args.detect_continuation,
        args.no_detect_continuation,
        probe_mode,
    )

    if args.file:
        try:
            with open(args.file, "rb") as f:
                raw = f.read().decode("utf-8", errors="replace")
        except EnvironmentError as e:
            sys.stderr.write("probe_runtime_guard: cannot read file: %s\n" % (e,))
            return 1
    else:
        raw = sys.stdin.read()

    prompt_text = None
    if args.prompt_file:
        try:
            with open(args.prompt_file, "rb") as f:
                prompt_text = f.read().decode("utf-8", errors="replace")
        except EnvironmentError as e:
            sys.stderr.write("probe_runtime_guard: cannot read prompt file: %s\n" % (e,))
            return 1

    ok, payload, vcode = runtime_guard_examinee_output(
        raw,
        require_fence=not args.no_fence,
        detect_self_grade=detect_self,
        detect_parrot=detect_parrot,
        detect_role_collision=detect_role,
        detect_continuation=detect_cont,
        prompt_text=prompt_text,
        knowledge_ack_first_line=args.knowledge_ack,
    )

    if args.json_result:
        preview = payload if ok else ""
        if len(preview) > 200:
            preview = preview[:200] + "..."
        sys.stderr.write(
            json.dumps(
                {
                    "ok": ok,
                    "violation_code": vcode,
                    "payload_preview": preview,
                },
                ensure_ascii=False,
            )
            + "\n"
        )

    if args.truncate_test_complete and ok:
        payload = truncate_after_test_complete(payload)

    sys.stdout.write(payload)
    if not payload.endswith("\n"):
        sys.stdout.write("\n")
    return 0 if ok else 2


if __name__ == "__main__":
    sys.exit(main())
