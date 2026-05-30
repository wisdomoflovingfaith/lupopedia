# lupopedia.headers:
#   when_updated: "20260403194301"
#   file_path_from_root: "lupo-scripts/db_config.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260403194301"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
**In Lupopedia, missing config is a hard failure, not a reason to guess.**

Load MySQL connection settings from **lupopedia-config.php** only (canonical resolver).
If the file cannot be found or required DB keys are missing, this module **raises**
``LupopediaConfigError`` — it does **not** return localhost / empty-user / default-database
fallbacks.

**Canonical Python DB config (4.0.99):** Operational ``.py`` tools MUST NOT hardcode DB credentials,
duplicate secrets, or bypass this file. Use ``load_db_config()`` / ``get_table_prefix()`` or
``lib.db_connection.get_connection_params()`` (delegates here). **No** ``LUPO_DB_*`` env tier for Python;
missing **lupopedia-config.php** is only acceptable during the **PHP/web installer**, not for tooling.
See ``lupo-docs/doctrine/PYTHON_DB_CONFIG_AND_SECRETS_4.0.99.md``.

Config file discovery matches **index.php** (same order when ``DOCUMENT_ROOT`` is known):

1. ``dirname(DOCUMENT_ROOT) / lupopedia-config.php``
2. ``dirname(DOCUMENT_ROOT) / basename(LUPOPEDIA_PATH) / lupopedia-config.php``
3. ``LUPOPEDIA_PATH / lupopedia-config.php`` (repository root — parent of **lupo-scripts/**)

**CLI / Python**

- Set **DOCUMENT_ROOT** or **LUPOPEDIA_DOCUMENT_ROOT** to the same value as ``$_SERVER['DOCUMENT_ROOT']``
  for full parity with the web entrypoint.
- If unset, the resolver tries ``document_root = repo.parent`` then ``document_root = repo`` and merges
  candidates (deduped).
- **LUPOPEDIA_CONFIG** — optional absolute path to **lupopedia-config.php**; if set, it must exist
  (otherwise ``LupopediaConfigError``).
"""

import os
import re
from pathlib import Path
from typing import Any, Dict, List, Optional


class LupopediaConfigError(RuntimeError):
    """Raised when lupopedia-config.php cannot be loaded or required DB keys are absent."""


def _repo_root() -> Path:
    """Filesystem root of the Lupopedia tree (directory that contains lupo-scripts/)."""
    return Path(__file__).resolve().parent.parent


def _paths_for_document_root(document_root: Path, lupopedia_path: Path) -> List[Path]:
    """Three search paths in the same order as index.php (paths 1, 2, 3)."""
    dr = document_root.resolve()
    repo = lupopedia_path.resolve()
    parent_doc = dr.parent
    pub_segment = repo.name
    return [
        parent_doc / "lupopedia-config.php",
        parent_doc / pub_segment / "lupopedia-config.php",
        repo / "lupopedia-config.php",
    ]


def iter_candidate_config_paths() -> List[Path]:
    """
    Ordered unique candidate paths tried by the resolver (for error messages).
    Does not check existence on disk beyond LUPOPEDIA_CONFIG.
    """
    repo = _repo_root()
    explicit = os.environ.get("LUPOPEDIA_CONFIG")
    out: List[Path] = []
    seen = set()

    def add(p: Path) -> None:
        try:
            r = p.resolve()
        except OSError:
            r = p
        key = str(r)
        if key not in seen:
            seen.add(key)
            out.append(r)

    if explicit:
        add(Path(explicit.strip()).expanduser())
        return out

    doc_raw = os.environ.get("DOCUMENT_ROOT") or os.environ.get("LUPOPEDIA_DOCUMENT_ROOT")
    if doc_raw:
        doc_roots = [Path(doc_raw).resolve()]
    else:
        doc_roots = [repo.parent.resolve(), repo.resolve()]

    for dr in doc_roots:
        for candidate in _paths_for_document_root(dr, repo):
            add(candidate)
    return out


def find_lupopedia_config_path() -> Optional[Path]:
    """
    Return the first existing lupopedia-config.php using index.php-equivalent rules.

    If LUPOPEDIA_CONFIG is set: resolved path must be a regular file ending in .php
    (after casefold); otherwise returns None (caller raises in load_db_config).
    """
    explicit = os.environ.get("LUPOPEDIA_CONFIG")
    if explicit:
        p = Path(explicit.strip()).expanduser().resolve()
        if not p.is_file():
            return None
        if p.suffix.lower() != ".php":
            return None
        return p

    repo = _repo_root()
    doc_raw = os.environ.get("DOCUMENT_ROOT") or os.environ.get("LUPOPEDIA_DOCUMENT_ROOT")
    if doc_raw:
        doc_roots = [Path(doc_raw).resolve()]
    else:
        doc_roots = [repo.parent.resolve(), repo.resolve()]

    seen = set()
    for dr in doc_roots:
        for candidate in _paths_for_document_root(dr, repo):
            resolved = candidate.resolve()
            if resolved in seen:
                continue
            seen.add(resolved)
            if resolved.is_file():
                return resolved
    return None


def _format_config_missing_message(
    reason: str,
    explicit_raw: Optional[str],
    tried_paths: List[Path],
) -> str:
    doc_root = os.environ.get("DOCUMENT_ROOT", "")
    lupo_doc = os.environ.get("LUPOPEDIA_DOCUMENT_ROOT", "")
    lupo_cfg = os.environ.get("LUPOPEDIA_CONFIG", "")
    repo = _repo_root()
    lines = [
        "Lupopedia configuration error:",
        reason,
        "",
        "Python DB-aware scripts must not use fallback credentials.",
        "",
        "Checked:",
        "  LUPOPEDIA_CONFIG=%r" % (lupo_cfg,),
        "  DOCUMENT_ROOT=%r" % (doc_root,),
        "  LUPOPEDIA_DOCUMENT_ROOT=%r" % (lupo_doc,),
        "  repo_root=%s" % (repo,),
        "",
        "Candidate paths (resolver order):",
    ]
    if explicit_raw and not tried_paths:
        lines.append("  (LUPOPEDIA_CONFIG invalid or missing file: %r)" % (explicit_raw,))
    for tp in tried_paths:
        lines.append("  - %s" % tp)
    lines.extend(
        [
            "",
            "Python tooling is not the installer: create or deploy lupopedia-config.php first.",
            "Set LUPOPEDIA_CONFIG to its absolute path, or set DOCUMENT_ROOT /",
            "LUPOPEDIA_DOCUMENT_ROOT to match the web server so the resolver can find the file.",
        ]
    )
    return "\n".join(lines)


def resolve_lupopedia_config_path() -> Path:
    """
    Return the resolved path to lupopedia-config.php.

    Raises:
        LupopediaConfigError: if the file cannot be located or LUPOPEDIA_CONFIG is invalid.
    """
    explicit_raw = os.environ.get("LUPOPEDIA_CONFIG")
    tried = iter_candidate_config_paths()
    if explicit_raw:
        p = Path(explicit_raw.strip()).expanduser().resolve()
        if not p.is_file():
            raise LupopediaConfigError(
                _format_config_missing_message(
                    "lupopedia-config.php could not be found: LUPOPEDIA_CONFIG points to a missing or non-file path.",
                    explicit_raw,
                    [p],
                )
            )
        if p.suffix.lower() != ".php":
            raise LupopediaConfigError(
                _format_config_missing_message(
                    "LUPOPEDIA_CONFIG must point to a .php file (refusing non-PHP path).",
                    explicit_raw,
                    [p],
                )
            )
        return p
    path = find_lupopedia_config_path()
    if path is None or not path.is_file():
        raise LupopediaConfigError(
            _format_config_missing_message(
                "lupopedia-config.php could not be found using approved resolver rules.",
                explicit_raw,
                tried,
            )
        )
    return path


def _extract_define(content: str, key: str) -> Optional[str]:
    """Extract value from define('KEY', 'value'); or define(\"KEY\", \"value\");"""
    pattern = r"define\s*\(\s*['\"]" + re.escape(key) + r"['\"]\s*,\s*['\"]([^'\"]*)['\"]\s*\)"
    m = re.search(pattern, content)
    if m:
        return m.group(1).strip()
    return None


def _extract_var(content: str, var_name: str) -> Optional[str]:
    """Extract value from $var_name = 'value'; or $var_name = \"value\";"""
    pattern = r"\$" + re.escape(var_name) + r"\s*=\s*['\"]([^'\"]*)['\"]\s*;"
    m = re.search(pattern, content)
    if m:
        return m.group(1).strip()
    return None


def load_db_config() -> Dict[str, Any]:
    """
    Read lupopedia-config.php and return connection params for pymysql.

    Looks for:
      - define('DB_HOST', '...');  (and DB_USER, DB_PASSWORD, DB_NAME, DB_PORT)
      - or $db_host = '...';       (and $db_user, $db_password, $db_name, $db_port)

    **Required in file:** DB_HOST, DB_USER, DB_NAME (non-empty strings).
    **DB_PASSWORD** may be empty. **DB_PORT** defaults to 3306 only when the config file was found.

    Raises:
        LupopediaConfigError: if no config file or required keys missing.
    """
    path = resolve_lupopedia_config_path()
    content = path.read_text(encoding="utf-8", errors="replace")

    def get_required(key_define: str, key_var: str, label: str) -> str:
        val = _extract_define(content, key_define)
        if val is None:
            val = _extract_var(content, key_var)
        if val is None or val == "":
            raise LupopediaConfigError(
                "Lupopedia configuration error: required key %s is missing or empty in %s.\n"
                "Python DB-aware scripts must not invent defaults for credentials.\n"
                % (label, path)
            )
        return val

    def get_password(key_define: str, key_var: str) -> str:
        val = _extract_define(content, key_define)
        if val is not None:
            return val
        val = _extract_var(content, key_var)
        return val if val is not None else ""

    port_str = _extract_define(content, "DB_PORT")
    if port_str is None:
        port_str = _extract_var(content, "db_port")
    if port_str is None or port_str == "":
        port = 3306
    else:
        try:
            port = int(port_str)
        except ValueError:
            raise LupopediaConfigError(
                "Lupopedia configuration error: DB_PORT in %s is not a valid integer (%r)."
                % (path, port_str)
            )

    return {
        "host": get_required("DB_HOST", "db_host", "DB_HOST"),
        "user": get_required("DB_USER", "db_user", "DB_USER"),
        "password": get_password("DB_PASSWORD", "db_password"),
        "database": get_required("DB_NAME", "db_name", "DB_NAME"),
        "port": port,
    }


def get_table_prefix() -> str:
    """
    Read $table_prefix or LUPO_TABLE_PREFIX from the same config file as load_db_config().
    Raises LupopediaConfigError if the config file cannot be loaded.
    If the file has no prefix line, returns 'lupo_' (schema default, not a credential).
    """
    path = resolve_lupopedia_config_path()
    content = path.read_text(encoding="utf-8", errors="replace")
    m = re.search(r"\$table_prefix\s*=\s*['\"]([a-zA-Z0-9_]+)['\"]", content)
    if m:
        return m.group(1)
    m2 = _extract_define(content, "LUPO_TABLE_PREFIX")
    if m2 is not None and m2 != "":
        return m2
    return "lupo_"


def get_connection_params() -> Dict[str, Any]:
    """
    Return dict suitable for pymysql.connect(**get_connection_params()).

    Keys: host, user, password, database, port.
    Caller may add charset, cursorclass, etc.

    Raises LupopediaConfigError if config cannot be loaded (same as load_db_config).
    """
    return load_db_config()


# Backward-compatibility export for legacy tooling that still imports
# `from db_config import DB_CONFIG`. This preserves hard-failure behavior
# when lupopedia-config.php is missing or invalid.
DB_CONFIG = load_db_config()
