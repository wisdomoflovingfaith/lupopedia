#!/usr/bin/env python3
"""
**In Lupopedia, missing config is a hard failure, not a reason to guess.**

Database connection parameters for Lupopedia Python **operational** tooling.

Python is **not** the installer: credentials come **only** from a resolved
**lupopedia-config.php** (same rules as **lupo-scripts/db_config.py**), including
optional **LUPOPEDIA_CONFIG** pointing at that file. There is **no** parallel
env-based DB credential tier (**no** ``LUPO_DB_*`` for Python) and no
installer-style “continue without config” behavior.

``get_connection_params()`` delegates to ``db_config.load_db_config()`` and raises
``LupopediaConfigError`` if the file cannot be found or parsed.
"""

import os
import sys
from typing import Any, Dict

_SCRIPTS_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)


def get_connection_params() -> Dict[str, Any]:
    """
    Return dict with keys: host, user, password, database, port.

    Raises:
        LupopediaConfigError: if lupopedia-config.php cannot be resolved or required keys are absent.
    """
    from db_config import load_db_config

    return load_db_config()


def merge_connection_params_with_args(args) -> Dict[str, Any]:
    """
    Start from get_connection_params(), then apply non-None CLI overrides from argparse Namespace.

    Used by importer CLIs that default DB flags to None so canonical config is used unless overridden.
    Raises LupopediaConfigError if merged user or database is empty.
    """
    from db_config import LupopediaConfigError

    m = dict(get_connection_params())
    if getattr(args, "host", None) is not None:
        m["host"] = args.host
    if getattr(args, "port", None) is not None:
        m["port"] = int(args.port)
    if getattr(args, "user", None) is not None:
        m["user"] = args.user
    if getattr(args, "password", None) is not None:
        m["password"] = args.password
    if getattr(args, "database", None) is not None:
        m["database"] = args.database
    if not m.get("user") or not m.get("database"):
        raise LupopediaConfigError(
            "Lupopedia configuration error: database user and database name must be non-empty "
            "after merging CLI args with config.\n"
            "Python DB-aware scripts must not use fallback credentials."
        )
    return m


def get_connection():
    """Return a pymysql connection using credentials from get_connection_params()."""
    import pymysql

    params = get_connection_params()
    return pymysql.connect(**params)


def get_db_connection():
    """Alias for get_connection() for compatibility."""
    return get_connection()


if __name__ == "__main__":
    from db_config import LupopediaConfigError

    try:
        params = get_connection_params()
    except LupopediaConfigError as exc:
        print(str(exc), file=sys.stderr)
        sys.exit(1)
    print("Host: %s" % params["host"])
    print("User: %s" % params["user"])
    print("Database: %s" % params["database"])
    print("Port: %s" % params["port"])
    print("Password: %s" % ("*" * len(params["password"])))
