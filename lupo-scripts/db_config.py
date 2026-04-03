# lupopedia.headers:
#   when_updated: "20260403194301"
#   file_path_from_root: "lupo-scripts/db_config.py"
#   last_modified_utc: "20260403194301"
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
Load MySQL connection settings from lupopedia-config.php.

Config file discovery matches **index.php** (same order when ``DOCUMENT_ROOT`` is known):

1. ``dirname(DOCUMENT_ROOT) / lupopedia-config.php``
2. ``dirname(DOCUMENT_ROOT) / basename(LUPOPEDIA_PATH) / lupopedia-config.php``
   (``LUPOPEDIA_PUBLIC_PATH`` in PHP is ``'/' + basename(__DIR__)``; we use the path segment only.)
3. ``LUPOPEDIA_PATH / lupopedia-config.php`` (repository root — parent of **lupo-scripts/**)

**CLI / Python**

- Set **DOCUMENT_ROOT** or **LUPOPEDIA_DOCUMENT_ROOT** to the same value as ``$_SERVER['DOCUMENT_ROOT']``
  for full parity with the web entrypoint.
- If unset, the script tries ``document_root = repo.parent`` then ``document_root = repo`` and merges
  candidates (deduped), which covers common “subfolder of vhost” and “lupopedia is docroot” layouts.
- **LUPOPEDIA_CONFIG** — optional absolute path to **lupopedia-config.php**; if set and the file exists,
  only that file is used.

Used by generate_toon_files.py, generate_seed_from_toons.py, and other **lupo-scripts/** tools.
"""

import os
import re
from pathlib import Path
from typing import Any, Dict, List, Optional


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


def find_lupopedia_config_path() -> Optional[Path]:
    """
    Return the first existing lupopedia-config.php using index.php-equivalent rules.

    See module docstring for environment variables.
    """
    repo = _repo_root()
    explicit = os.environ.get("LUPOPEDIA_CONFIG")
    if explicit:
        p = Path(explicit).expanduser().resolve()
        return p if p.is_file() else None

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

    Returns dict: host, user, password, database, port.
    port defaults to 3306 if missing. Other keys default to empty string if missing.
    """
    path = find_lupopedia_config_path()
    if path is None or not path.is_file():
        return {
            "host": "localhost",
            "user": "",
            "password": "",
            "database": "lupopedia",
            "port": 3306,
        }

    content = path.read_text(encoding="utf-8", errors="replace")

    def get(key_define: str, key_var: str, default: str = "") -> str:
        val = _extract_define(content, key_define)
        if val is not None:
            return val
        val = _extract_var(content, key_var)
        return val if val is not None else default

    port_str = get("DB_PORT", "db_port", "3306")
    try:
        port = int(port_str)
    except ValueError:
        port = 3306

    return {
        "host": get("DB_HOST", "db_host", "localhost"),
        "user": get("DB_USER", "db_user", ""),
        "password": get("DB_PASSWORD", "db_password", ""),
        "database": get("DB_NAME", "db_name", "lupopedia"),
        "port": port,
    }


def get_connection_params() -> Dict[str, Any]:
    """
    Return dict suitable for pymysql.connect(**get_connection_params()).

    Keys: host, user, password, database, port.
    Caller may add charset, cursorclass, etc.
    """
    return load_db_config()
