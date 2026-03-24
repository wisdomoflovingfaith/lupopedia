#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324182200"
#   file_path_from_root: "lupo-scripts/validate_channel_artifacts.py"
#   last_modified_utc: "20260324182200"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324182200"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Validate lupo-channels/{id}/ tree.

Default: numeric thread dirs (excluding legacy names); canonical .md under numeric threads only.
Use --audit-all to scan broadcasts/content/tasks/rules/direct (many legacy violations expected).
Use --enforce-thread-review-bodies to require substantive body for review-marked thread artifacts.

Project awareness (4.0.81+): optional --enforce-project applies V-PROJECT-002/004 and
TODO.md/plan.md link scope. V-PROJECT-001 always runs on scanned files (path under repo).

Usage:
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --strict
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --enforce-project
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --mode enforce
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --option-a-registry
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --thread-validation
"""
from __future__ import annotations

import importlib.util
import argparse
import os
import re
import sys
from pathlib import Path

CANONICAL_MD = re.compile(r"^[0-9]{8}_[0-9]{6}_[a-z][a-z0-9]*_[a-z][a-z0-9_-]+\.md$")
NUMERIC_THREAD = re.compile(r"^[1-9][0-9]{0,17}$")
SECTION_H2 = re.compile(r"^##\s+", re.MULTILINE)
PROJECT_REF = re.compile(r"^project-(\d+):(.+)$")
# Optional project_id / project_slug in first YAML block (not required for validity)
HEADER_PROJECT_ID = re.compile(r"^\s*project_id\s*:\s*(\d+)\s*$", re.MULTILINE)
HEADER_PROJECT_SLUG = re.compile(
    r'^\s*project_slug\s*:\s*(?:"([^"]+)"|([^\s#]+))\s*$', re.MULTILINE
)
MD_LINK = re.compile(r"\[([^\]]*)\]\(([^)]+)\)")

WEB_BASE = "http://www.lupopedia.com/"
HEADER_FILE_PATH_FROM_ROOT = re.compile(
    r'^\s*file_path_from_root\s*:\s*(?:"([^"]+)"|([^\s#]+))\s*$',
    re.MULTILINE,
)
HEADER_WEB_PATH = re.compile(
    r'^\s*web_path\s*:\s*(?:"([^"]+)"|([^\s#]+))\s*$',
    re.MULTILINE,
)


def _header_scalar_value(m: re.Match | None) -> str | None:
    if not m:
        return None
    return m.group(1) if m.group(1) is not None else m.group(2)


def _expected_web_path(file_path_from_root: str) -> str:
    p = (file_path_from_root or "").strip().lstrip("/")
    return WEB_BASE + p


def validate_web_path_canonicalization(path: Path, frontmatter: str | None) -> list[str]:
    """
    Blocking:
    - web_path missing when file_path_from_root exists
    - web_path != WEB_BASE + file_path_from_root (deterministic)
    - for .md files: file_path_from_root must end with .md and web_path must end with .md
    """
    if not frontmatter:
        return []
    m_fp = HEADER_FILE_PATH_FROM_ROOT.search(frontmatter)
    fp = _header_scalar_value(m_fp)
    if not fp:
        return []
    fp = fp.strip().lstrip("/")
    expected = _expected_web_path(fp)
    m_wp = HEADER_WEB_PATH.search(frontmatter)
    wp = _header_scalar_value(m_wp)
    if not wp:
        return [
            "HEADER_ERROR[WEBPATH_MISSING]: %s file_path_from_root present but web_path missing (expected %s)"
            % (path, expected)
        ]
    wp = wp.strip()
    if wp != expected:
        return [
            "HEADER_ERROR[WEBPATH_MISMATCH]: %s web_path '%s' does not match expected '%s'"
            % (path, wp, expected)
        ]
    if path.suffix.lower() == ".md":
        if not fp.lower().endswith(".md"):
            return [
                "HEADER_ERROR[FILEPATH_NOT_MD]: %s file_path_from_root must end with .md for markdown artifacts; got '%s'"
                % (path, fp)
            ]
        if not wp.lower().endswith(".md"):
            return [
                "HEADER_ERROR[WEBPATH_NOT_MD]: %s web_path must end with .md for markdown artifacts; got '%s'"
                % (path, wp)
            ]
    return []


def infer_project_context(
    repo_root: Path,
    project_id: int | None = None,
    project_slug: str | None = None,
) -> dict:
    """
    Deterministic project context from repo root only (no DB).
    Env LUPO_PROJECT_ID / LUPO_PROJECT_SLUG override CLI when set.
    """
    root = repo_root.resolve()
    pid = project_id if project_id is not None else 0
    env_id = os.environ.get("LUPO_PROJECT_ID", "").strip()
    if env_id.isdigit():
        pid = int(env_id)
    slug = project_slug if project_slug is not None else "lupopedia-core"
    env_slug = os.environ.get("LUPO_PROJECT_SLUG", "").strip()
    if env_slug:
        slug = env_slug
    return {
        "project_id": pid,
        "project_slug": slug,
        "project_root": root,
    }


def path_within_project(path: Path, project_root: Path) -> bool:
    try:
        path.resolve().relative_to(project_root.resolve())
        return True
    except (ValueError, OSError, RuntimeError):
        return False


def validate_v_project_001(path: Path, project_root: Path) -> list[str]:
    if path_within_project(path, project_root):
        return []
    return [
        "PROJECT_ERROR[V-PROJECT-001]: %s not under project root %s"
        % (path, project_root.resolve())
    ]


def parse_frontmatter_block(text: str) -> str | None:
    if not text.startswith("---"):
        return None
    parts = text.split("---", 2)
    if len(parts) < 3:
        return None
    return parts[1]


def parse_header_project_fields(frontmatter: str | None) -> tuple[int | None, str | None]:
    if not frontmatter:
        return (None, None)
    mid = HEADER_PROJECT_ID.search(frontmatter)
    pid = int(mid.group(1)) if mid else None
    mslug = HEADER_PROJECT_SLUG.search(frontmatter)
    slug = None
    if mslug:
        slug = mslug.group(1) if mslug.group(1) else mslug.group(2)
    return (pid, slug)


def validate_v_project_004_warn(path: Path, frontmatter: str | None) -> list[str]:
    if not frontmatter:
        return []
    has_multi = bool(
        re.search(r"^\s*federation_node_id\s*:", frontmatter, re.MULTILINE)
        or re.search(r'to:\s*["\']?project-\d+:', frontmatter)
    )
    if not has_multi:
        return []
    if HEADER_PROJECT_ID.search(frontmatter):
        return []
    return [
        "PROJECT_WARN[V-PROJECT-004]: %s multi-project indicator in header but project_id omitted"
        % path
    ]


def _normalize_link_target(raw: str) -> str:
    t = raw.strip().split()[0].strip('"').strip("'")
    return t.split("#")[0] if t else ""


def validate_v_project_002_003_links(
    path: Path, text: str, project_root: Path
) -> list[str]:
    """
    V-PROJECT-002: cross-boundary paths must use project-<id>:<path>.
    V-PROJECT-003: resolved path must stay within project_root (no escape).
    """
    out: list[str] = []
    pr = project_root.resolve()
    parent = path.parent
    for m in MD_LINK.finditer(text):
        raw = m.group(2)
        target = _normalize_link_target(raw)
        if not target or target.startswith(("#", "http://", "https://", "mailto:")):
            continue
        pm = PROJECT_REF.match(target)
        if pm:
            inner = pm.group(2).strip()
            if inner.startswith("/") or ".." in Path(inner).parts:
                out.append(
                    "PROJECT_ERROR[V-PROJECT-003]: %s project-N: inner path must be relative, no .. : %s"
                    % (path, target)
                )
            continue
        if target.startswith("/"):
            try:
                cand = Path(target).resolve()
            except OSError:
                continue
        else:
            try:
                cand = (parent / target).resolve()
            except (OSError, RuntimeError):
                continue
        try:
            cand.relative_to(pr)
        except ValueError:
            out.append(
                "PROJECT_ERROR[V-PROJECT-002]: %s link escapes project root; use project-<id>:<path> for cross-project: %s"
                % (path, raw.strip())
            )
    return out


def validate_todo_plan_project_scope(
    repo: Path, project_root: Path, enforce_links: bool
) -> list[str]:
    """
    TODO.md / plan.md are project-scoped registries; optional link checks.
    """
    out: list[str] = []
    for name in ("TODO.md", "plan.md"):
        p = repo / name
        if not p.is_file():
            continue
        out.extend(validate_v_project_001(p, project_root))
        if not enforce_links:
            continue
        try:
            body = p.read_text(encoding="utf-8", errors="replace")
        except OSError as e:
            out.append("READ_ERROR: %s (%s)" % (p, e))
            continue
        out.extend(validate_v_project_002_003_links(p, body, project_root))
    return out


def _is_review_frontmatter(fm: str) -> bool:
    low = fm.lower()
    return (
        "artifact_kind: review" in low
        or 'artifact_kind: "review"' in low
        or "message_type: review" in low
    )


def _is_help_response_frontmatter(fm: str) -> bool:
    low = fm.lower()
    return (
        "artifact_kind: help_response" in low
        or 'artifact_kind: "help_response"' in low
        or "message_type: help_response" in low
    )


def validate_thread_review_body(path: Path) -> list[str]:
    """Enforce substantive body for thread .md files marked as review."""
    out: list[str] = []
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except OSError as e:
        return ["READ_ERROR: %s (%s)" % (path, e)]
    if not text.startswith("---"):
        return out
    parts = text.split("---", 2)
    if len(parts) < 3:
        out.append("THREAD_FRONTMATTER: incomplete YAML %s" % path)
        return out
    fm = parts[1]
    body = parts[2].strip()
    name_low = path.name.lower()
    if not _is_review_frontmatter(fm) and "review" not in name_low:
        return out
    if len(body) < 500:
        out.append(
            "THREAD_REVIEW_SHORT: %s (body %d chars, need 500+ to match API)" % (path, len(body))
        )
        return out
    n = len(SECTION_H2.findall(body))
    if n < 3:
        out.append("THREAD_REVIEW_SECTIONS: %s (need 3+ ## headings, got %d)" % (path, n))
    return out


def validate_thread_help_response_body(path: Path) -> list[str]:
    """Enforce substantive body for thread .md marked help_response (LILITH ATER001)."""
    out: list[str] = []
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except OSError as e:
        return ["READ_ERROR: %s (%s)" % (path, e)]
    if not text.startswith("---"):
        return out
    parts = text.split("---", 2)
    if len(parts) < 3:
        out.append("THREAD_FRONTMATTER: incomplete YAML %s" % path)
        return out
    fm = parts[1]
    body = parts[2].strip()
    if not _is_help_response_frontmatter(fm):
        return out
    if len(body) < 200:
        out.append("THREAD_HELP_RESPONSE_SHORT: %s (body %d chars, need 200+)" % (path, len(body)))
        return out
    if body.count("#") < 3:
        out.append("THREAD_HELP_RESPONSE_HASH: %s (need 3+ # in body)" % path)
        return out
    if not re.search(r"^#\s+\S", body, re.MULTILINE):
        out.append("THREAD_HELP_RESPONSE_H1: %s (need # title line)" % path)
        return out
    n = len(SECTION_H2.findall(body))
    if n < 3:
        out.append(
            "THREAD_HELP_RESPONSE_SECTIONS: %s (need 3+ ## headings, got %d)" % (path, n)
        )
    return out


def validate_channel(
    repo: Path,
    channel_id: int,
    legacy_thread_dirs: frozenset[str] | None = None,
    audit_all: bool = False,
    enforce_thread_review_bodies: bool = False,
    enforce_help_response_bodies: bool = False,
    project_root: Path | None = None,
    enforce_project: bool = False,
    actor_identity_validation: bool = False,
    actor_validate_fn=None,
    actor_registry=None,
    interpretation_validate_fn=None,
    footer_validation: bool = True,
    footer_validate_fn=None,
    footer_autofix_fn=None,
    footer_actor_registry=None,
    footer_cutoff_utc_ymdhis: int = 20260301000000,
    footer_autofix: bool = True,
    footer_validator_actor_id: int = 102,
    footer_validator_actor_name: str = "cursor",
) -> list[str]:
    errors: list[str] = []
    pr = project_root if project_root is not None else repo.resolve()
    base = repo / "lupo-channels" / str(channel_id)
    if not base.is_dir():
        return ["missing %s" % base]

    legacy = legacy_thread_dirs or frozenset({"4.0.x", "4.0.68", "4.0.73", "4.0.80"})

    th = base / "threads"
    if th.is_dir():
        for sub in th.iterdir():
            if not sub.is_dir():
                continue
            name = sub.name
            is_legacy = name in legacy
            if not NUMERIC_THREAD.match(name) and not is_legacy:
                errors.append("NON_NUMERIC_THREAD_DIR: %s" % sub.relative_to(repo))
            if is_legacy:
                continue
            for f in sub.rglob("*.md"):
                if f.name == "README.md":
                    continue
                errors.extend(validate_v_project_001(f, pr))
                if not CANONICAL_MD.match(f.name):
                    errors.append("BAD_FILENAME: %s" % f.relative_to(repo))
                else:
                    if enforce_thread_review_bodies:
                        errors.extend(validate_thread_review_body(f))
                    if enforce_help_response_bodies:
                        errors.extend(validate_thread_help_response_body(f))
                    if enforce_project or actor_identity_validation or footer_validation:
                        try:
                            full = f.read_text(encoding="utf-8", errors="replace")
                        except OSError as e:
                            errors.append("READ_ERROR: %s (%s)" % (f, e))
                        else:
                            fm = parse_frontmatter_block(full)
                            errors.extend(validate_web_path_canonicalization(f, fm))
                            if enforce_project:
                                errors.extend(validate_v_project_002_003_links(f, full, pr))
                                errors.extend(validate_v_project_004_warn(f, fm))
                            if footer_validation and footer_validate_fn is not None:
                                footer_issues = footer_validate_fn(
                                    full,
                                    f,
                                    footer_actor_registry,
                                    footer_cutoff_utc_ymdhis,
                                )
                                if footer_issues and footer_autofix and footer_autofix_fn is not None:
                                    updated, changed, fix_err = footer_autofix_fn(
                                        full,
                                        int(footer_validator_actor_id),
                                        str(footer_validator_actor_name),
                                    )
                                    if fix_err is not None:
                                        errors.append(
                                            "FOOTER_AUTOFIX_ERROR: %s (%s)" % (f, fix_err)
                                        )
                                    elif changed and updated != full:
                                        try:
                                            f.write_text(updated, encoding="utf-8")
                                            footer_issues = footer_validate_fn(
                                                updated,
                                                f,
                                                footer_actor_registry,
                                                footer_cutoff_utc_ymdhis,
                                            )
                                        except OSError as e:
                                            errors.append("WRITE_ERROR: %s (%s)" % (f, e))
                                errors.extend(footer_issues)
                    if actor_identity_validation and actor_validate_fn is not None and actor_registry is not None:
                                errors.extend(actor_validate_fn(full, f, actor_registry))
                    if (
                        actor_identity_validation
                        and interpretation_validate_fn is not None
                        and actor_registry is not None
                    ):
                        errors.extend(
                            interpretation_validate_fn(full, f, actor_registry)
                        )

    if not audit_all:
        if enforce_project:
            errors.extend(
                validate_todo_plan_project_scope(repo, pr, enforce_links=True)
            )
        return errors

    for subname in ("broadcasts", "content", "tasks", "rules"):
        d = base / subname
        if d.is_dir():
            for f in d.glob("*.md"):
                if f.name == "README.md":
                    continue
                errors.extend(validate_v_project_001(f, pr))
                if not CANONICAL_MD.match(f.name):
                    errors.append("BAD_FILENAME: %s" % f.relative_to(repo))
                if actor_identity_validation or footer_validation:
                    try:
                        full = f.read_text(encoding="utf-8", errors="replace")
                    except OSError as e:
                        errors.append("READ_ERROR: %s (%s)" % (f, e))
                    else:
                        fm = parse_frontmatter_block(full)
                        errors.extend(validate_web_path_canonicalization(f, fm))
                        if actor_identity_validation and actor_validate_fn is not None and actor_registry is not None:
                            errors.extend(actor_validate_fn(full, f, actor_registry))
                            if (
                                interpretation_validate_fn is not None
                                and actor_registry is not None
                            ):
                                errors.extend(
                                    interpretation_validate_fn(full, f, actor_registry)
                                )
                        if footer_validation and footer_validate_fn is not None:
                            footer_issues = footer_validate_fn(
                                full,
                                f,
                                footer_actor_registry,
                                footer_cutoff_utc_ymdhis,
                            )
                            if footer_issues and footer_autofix and footer_autofix_fn is not None:
                                updated, changed, fix_err = footer_autofix_fn(
                                    full,
                                    int(footer_validator_actor_id),
                                    str(footer_validator_actor_name),
                                )
                                if fix_err is not None:
                                    errors.append("FOOTER_AUTOFIX_ERROR: %s (%s)" % (f, fix_err))
                                elif changed and updated != full:
                                    try:
                                        f.write_text(updated, encoding="utf-8")
                                        footer_issues = footer_validate_fn(
                                            updated,
                                            f,
                                            footer_actor_registry,
                                            footer_cutoff_utc_ymdhis,
                                        )
                                    except OSError as e:
                                        errors.append("WRITE_ERROR: %s (%s)" % (f, e))
                            errors.extend(footer_issues)

    dr = base / "direct"
    if dr.is_dir():
        for actor_dir in dr.iterdir():
            if not actor_dir.is_dir():
                continue
            if not NUMERIC_THREAD.match(actor_dir.name):
                errors.append("NON_NUMERIC_DIRECT_DIR: %s" % actor_dir.relative_to(repo))
                continue
            for f in actor_dir.glob("*.md"):
                if f.name == "README.md":
                    continue
                errors.extend(validate_v_project_001(f, pr))
                if not CANONICAL_MD.match(f.name):
                    errors.append("BAD_FILENAME: %s" % f.relative_to(repo))
                if actor_identity_validation or footer_validation:
                    try:
                        full = f.read_text(encoding="utf-8", errors="replace")
                    except OSError as e:
                        errors.append("READ_ERROR: %s (%s)" % (f, e))
                    else:
                        fm = parse_frontmatter_block(full)
                        errors.extend(validate_web_path_canonicalization(f, fm))
                        if actor_identity_validation and actor_validate_fn is not None and actor_registry is not None:
                            errors.extend(actor_validate_fn(full, f, actor_registry))
                            if (
                                interpretation_validate_fn is not None
                                and actor_registry is not None
                            ):
                                errors.extend(
                                    interpretation_validate_fn(full, f, actor_registry)
                                )
                        if footer_validation and footer_validate_fn is not None:
                            footer_issues = footer_validate_fn(
                                full,
                                f,
                                footer_actor_registry,
                                footer_cutoff_utc_ymdhis,
                            )
                            if footer_issues and footer_autofix and footer_autofix_fn is not None:
                                updated, changed, fix_err = footer_autofix_fn(
                                    full,
                                    int(footer_validator_actor_id),
                                    str(footer_validator_actor_name),
                                )
                                if fix_err is not None:
                                    errors.append("FOOTER_AUTOFIX_ERROR: %s (%s)" % (f, fix_err))
                                elif changed and updated != full:
                                    try:
                                        f.write_text(updated, encoding="utf-8")
                                        footer_issues = footer_validate_fn(
                                            updated,
                                            f,
                                            footer_actor_registry,
                                            footer_cutoff_utc_ymdhis,
                                        )
                                    except OSError as e:
                                        errors.append("WRITE_ERROR: %s (%s)" % (f, e))
                            errors.extend(footer_issues)

    if enforce_project:
        errors.extend(validate_todo_plan_project_scope(repo, pr, enforce_links=True))
    return errors


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--channel", type=int, default=42)
    ap.add_argument("--strict", action="store_true")
    ap.add_argument("--legacy-thread-dirs", default="4.0.x,4.0.68,4.0.73,4.0.80")
    ap.add_argument("--no-legacy-skip", action="store_true")
    ap.add_argument(
        "--audit-all",
        action="store_true",
        help="Include broadcasts/content/tasks/rules/direct (legacy-heavy)",
    )
    ap.add_argument(
        "--enforce-thread-review-bodies",
        action="store_true",
        help="Thread .md with review in name or artifact_kind: review need 500+ chars and 3+ ##",
    )
    ap.add_argument(
        "--enforce-help-response-bodies",
        action="store_true",
        help="Thread .md with artifact_kind: help_response need 200+ chars, # title, 3+ ##",
    )
    ap.add_argument(
        "--enforce-project",
        action="store_true",
        help="V-PROJECT-002/003/004 + TODO.md/plan.md link scope (V-PROJECT-001 always on scanned paths)",
    )
    ap.add_argument(
        "--project-id",
        type=int,
        default=0,
        help="Declared project_id for logging only (default 0; env LUPO_PROJECT_ID overrides)",
    )
    ap.add_argument(
        "--project-slug",
        default="lupopedia-core",
        help="Declared project_slug for logging (env LUPO_PROJECT_SLUG overrides)",
    )
    ap.add_argument(
        "--mode",
        choices=("check", "enforce"),
        default="check",
        help="enforce = --strict + thread review bodies + help_response bodies (CI gateway)",
    )
    ap.add_argument(
        "--option-a-registry",
        action="store_true",
        help="Run Option A validator (TODO.md + plan.md); exits with that result; skips channel scan",
    )
    ap.add_argument(
        "--warnings-as-errors-registry",
        action="store_true",
        help="With --option-a-registry: exit 1 on WARN",
    )
    ap.add_argument(
        "--thread-validation",
        action="store_true",
        help="After channel (and/or option-a) run V-THREAD-001..005 on numeric threads for --channel",
    )
    ap.add_argument(
        "--actor-identity-validation",
        action="store_true",
        help="Enforce actor/facet convergence + identity canonicality via validate_actor_identity.py",
    )
    ap.add_argument(
        "--footer-validation",
        dest="footer_validation",
        action="store_true",
        default=True,
        help="Enforce lupopedia.footer verification freshness and validator identity (default: on)",
    )
    ap.add_argument(
        "--no-footer-validation",
        dest="footer_validation",
        action="store_false",
        help="Disable lupopedia.footer verification checks",
    )
    ap.add_argument(
        "--footer-autofix",
        dest="footer_autofix",
        action="store_true",
        default=True,
        help="Auto-update stale/missing footer verification fields while scanning (default: on)",
    )
    ap.add_argument(
        "--no-footer-autofix",
        dest="footer_autofix",
        action="store_false",
        help="Do not auto-update footers; report revalidation-required issues only",
    )
    ap.add_argument(
        "--footer-cutoff-utc",
        default="20260301000000",
        help="Revalidation cutoff in UTC YYYYMMDDHHIISS (default: 20260301000000)",
    )
    ap.add_argument(
        "--footer-validator-actor-id",
        type=int,
        default=102,
        help="Actor ID written when footer autofix updates verification fields",
    )
    ap.add_argument(
        "--footer-validator-actor-name",
        default="cursor",
        help="Actor name written when footer autofix updates verification fields",
    )
    args = ap.parse_args()

    def _run_threads(root: Path) -> int:
        vt = Path(__file__).resolve().parent / "validate_threads.py"
        spec = importlib.util.spec_from_file_location("validate_threads", vt)
        if spec is None or spec.loader is None:
            print("validate_threads.py not found", file=sys.stderr)
            return 2
        mod = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(mod)
        return mod.run(root, args.channel, None)

    if args.option_a_registry:
        root = Path(args.repo_root).resolve()
        vtp = Path(__file__).resolve().parent / "validate_todo_plan.py"
        spec = importlib.util.spec_from_file_location("validate_todo_plan", vtp)
        if spec is None or spec.loader is None:
            print("validate_todo_plan.py not found", file=sys.stderr)
            return 2
        mod = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(mod)
        rc = mod.run(root, args.warnings_as_errors_registry)
        if args.thread_validation:
            tr = _run_threads(root)
            if tr > rc:
                rc = tr
        return rc
    enforce_rev = args.enforce_thread_review_bodies
    enforce_help = args.enforce_help_response_bodies
    if args.mode == "enforce":
        args.strict = True
        enforce_rev = True
        enforce_help = True
        args.actor_identity_validation = True
    root = Path(args.repo_root).resolve()
    actor_validate_fn = None
    actor_registry = None
    footer_validate_fn = None
    footer_autofix_fn = None
    footer_actor_registry = None
    footer_cutoff_utc_ymdhis = 20260301000000
    if args.actor_identity_validation:
        av = Path(__file__).resolve().parent / "validate_actor_identity.py"
        spec = importlib.util.spec_from_file_location("validate_actor_identity", av)
        if spec is None or spec.loader is None:
            print("validate_actor_identity.py not found", file=sys.stderr)
            return 2
        mod = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(mod)
        actor_registry = mod.load_actor_registry(root)
        actor_validate_fn = mod.validate_actor_identity_text
        iv = Path(__file__).resolve().parent / "validate_interpretation_headers.py"
        ispec = importlib.util.spec_from_file_location("validate_interpretation_headers", iv)
        if ispec is None or ispec.loader is None:
            print("validate_interpretation_headers.py not found", file=sys.stderr)
            return 2
        imod = importlib.util.module_from_spec(ispec)
        ispec.loader.exec_module(imod)
        interpretation_validate_fn = imod.validate_interpretation_headers_text
    if args.footer_validation:
        fv = Path(__file__).resolve().parent / "validate_footer_verification.py"
        fspec = importlib.util.spec_from_file_location("validate_footer_verification", fv)
        if fspec is None or fspec.loader is None:
            print("validate_footer_verification.py not found", file=sys.stderr)
            return 2
        fmod = importlib.util.module_from_spec(fspec)
        fspec.loader.exec_module(fmod)
        footer_validate_fn = fmod.validate_footer_verification_text
        footer_autofix_fn = fmod.autofix_footer_verification_text
        footer_actor_registry = fmod.load_actor_registry(root)
        try:
            footer_cutoff_utc_ymdhis = int(str(args.footer_cutoff_utc).strip())
        except (TypeError, ValueError):
            print("Invalid --footer-cutoff-utc: expected YYYYMMDDHHIISS integer", file=sys.stderr)
            return 2
    ctx = infer_project_context(root, args.project_id, args.project_slug)
    print(
        "project_context: project_id=%s project_slug=%s project_root=%s"
        % (ctx["project_id"], ctx["project_slug"], ctx["project_root"]),
        file=sys.stderr,
    )
    leg = (
        frozenset()
        if args.no_legacy_skip
        else frozenset(x.strip() for x in args.legacy_thread_dirs.split(",") if x.strip())
    )
    errs = validate_channel(
        root,
        args.channel,
        leg,
        audit_all=args.audit_all,
        enforce_thread_review_bodies=enforce_rev,
        enforce_help_response_bodies=enforce_help,
        project_root=ctx["project_root"],
        enforce_project=args.enforce_project,
        actor_identity_validation=args.actor_identity_validation,
        actor_validate_fn=actor_validate_fn,
        actor_registry=actor_registry,
        interpretation_validate_fn=interpretation_validate_fn if args.actor_identity_validation else None,
        footer_validation=args.footer_validation,
        footer_validate_fn=footer_validate_fn,
        footer_autofix_fn=footer_autofix_fn,
        footer_actor_registry=footer_actor_registry,
        footer_cutoff_utc_ymdhis=footer_cutoff_utc_ymdhis,
        footer_autofix=args.footer_autofix,
        footer_validator_actor_id=args.footer_validator_actor_id,
        footer_validator_actor_name=args.footer_validator_actor_name,
    )
    warn_count = sum(
        1 for e in errs if ("PROJECT_WARN[" in e or "INTERPRETATION_WARN[" in e)
    )
    err_only = [
        e
        for e in errs
        if ("PROJECT_WARN[" not in e and "INTERPRETATION_WARN[" not in e)
    ]
    for e in errs:
        print(e)
    print(
        "validate_channel_artifacts: %d issue(s) for channel %s"
        % (len(errs), args.channel)
    )
    if warn_count:
        print(
            "(includes %d PROJECT_WARN; strict exit uses errors only)" % warn_count,
            file=sys.stderr,
        )
    rc = 1 if ((args.strict or args.actor_identity_validation) and err_only) else 0
    if args.thread_validation:
        tr = _run_threads(root)
        if tr > rc:
            rc = tr
    return rc


if __name__ == "__main__":
    sys.exit(main())