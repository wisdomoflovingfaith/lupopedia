"""
Load MySQL connection settings from lupopedia-config.php (project root).

Used by generate_toon_files.py and generate_seed_from_toons.py (in scripts/).
No env vars required; credentials come from the PHP config file.
"""

import re
from pathlib import Path
from typing import Any, Dict, Optional


def _find_config_path() -> Path:
    """Return path to lupopedia-config.php in project root (parent of scripts/)."""
    base = Path(__file__).resolve().parent
    root = base.parent
    return root / "lupopedia-config.php"


def _extract_define(content: str, key: str) -> Optional[str]:
    """Extract value from define('KEY', 'value'); or define(\"KEY\", \"value\");"""
    # define('DB_HOST', 'localhost'); or define("DB_HOST", "localhost");
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
    path = _find_config_path()
    if not path.is_file():
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
