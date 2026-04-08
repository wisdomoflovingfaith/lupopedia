#!/usr/bin/env python3
"""
sync_trust_ladder_registry_to_db.py — Sync lupo_trust_ladder_registry DB table from doctrine Markdown.

Reads TRUST_LADDER_REGISTRY.md (doctrine source of truth) and synchronises the
lupo_trust_ladder_registry database table, reporting any drift between the two.

Usage:
    python lupo-scripts/sync_trust_ladder_registry_to_db.py [options]

Options:
    --dry-run        Report drift without writing to the database (default: False)
    --force          Write missing/changed rows even when drift is detected
    --strict         Treat any drift as a non-zero exit (for CI gating)
    --registry PATH  Path to TRUST_LADDER_REGISTRY.md (default: auto-detect from PROJECT_ROOT)
    --db-config PATH Path to db_config.py (default: auto-detect)
    -v, --verbose    Show extra detail on each table entry

Exit codes:
    0 — No drift detected (or --dry-run with no drift)
    1 — Drift detected (doctrine vs DB mismatch)
    2 — Configuration / connection error
    3 — Invariant validation failure (archetype rules violated)

Invariants enforced (per note_on_seed_range.md and CHRONOLOGICAL_TRUST_LADDER.md):
    parent  → seed_required = 1, reference_target = 'seed'
    child   → seed_required = 0, reference_target = 'canonical'
    system  → seed_required = 1

TODO(LEGACY-GAP): Self-referential bootstrap: the registry table's own row (registry_id=1)
    must exist before this script can write any other rows. The script inserts the
    self-referential row first, but if that insert fails due to missing DDL, the entire
    sync aborts. Ensure DDL is applied before running this script.

TODO(LEGACY-GAP): Federated installs with non-'lupo_' table prefix. The doctrine Markdown
    stores bare table names (e.g. 'lupo_memory_nodes') but the installed table may have
    a different prefix. Mitigation: pass --table-prefix to override the default 'lupo_'.

TODO(LEGACY-GAP): Tables that already exist but are not yet registered (migration path).
    Running this script in --dry-run mode first allows reviewing the gap without risk.
"""

import argparse
import os
import re
import sys
from pathlib import Path

# ─────────────────────────────────────────────────────────────────────────────
# Project root detection
# ─────────────────────────────────────────────────────────────────────────────

PROJECT_ROOT = Path(__file__).resolve().parent.parent


def find_file(relative_path: str, cli_override: str = None) -> Path:
    """Resolve a file path, preferring CLI override then PROJECT_ROOT."""
    if cli_override:
        p = Path(cli_override)
        if p.is_file():
            return p
        raise FileNotFoundError(f"Specified path not found: {cli_override}")
    candidate = PROJECT_ROOT / relative_path
    if candidate.is_file():
        return candidate
    raise FileNotFoundError(f"Cannot find {relative_path} relative to {PROJECT_ROOT}")


# ─────────────────────────────────────────────────────────────────────────────
# DB connection (via lib.db_connection or db_config.py fallback)
# ─────────────────────────────────────────────────────────────────────────────

def get_db_connection(args):
    """
    Return a DB-API 2.0 connection.
    Tries lib.db_connection.get_connection_params() first;
    falls back to importing db_config.py from the project root.
    """
    try:
        sys.path.insert(0, str(PROJECT_ROOT / 'lupo-scripts' / 'lib'))
        from db_connection import get_connection_params  # type: ignore
        params = get_connection_params()
    except (ImportError, Exception):
        params = _load_db_config(args)

    db_type = params.get('db_type', 'mysql').lower()
    host    = params.get('host', 'localhost')
    user    = params.get('user', '')
    passwd  = params.get('passwd', '')
    db      = params.get('db', '')
    port    = int(params.get('port', 3306))

    if db_type in ('mysql', 'mariadb'):
        try:
            import pymysql
            conn = pymysql.connect(
                host=host, user=user, password=passwd, database=db, port=port,
                charset='utf8mb4', autocommit=False,
            )
            return conn, db_type
        except ImportError:
            pass
        try:
            import MySQLdb
            conn = MySQLdb.connect(host=host, user=user, passwd=passwd, db=db, port=port)
            return conn, db_type
        except ImportError:
            raise RuntimeError(
                "Neither pymysql nor MySQLdb is installed. "
                "pip install pymysql and retry."
            )

    if db_type == 'postgresql':
        import psycopg2
        conn = psycopg2.connect(host=host, user=user, password=passwd, dbname=db, port=port)
        return conn, db_type

    if db_type == 'sqlite':
        import sqlite3
        conn = sqlite3.connect(db)
        conn.row_factory = sqlite3.Row
        return conn, db_type

    raise ValueError(f"Unsupported db_type: {db_type}")


def _load_db_config(args) -> dict:
    """Load DB parameters from db_config.py or environment variables."""
    config_path = getattr(args, 'db_config', None)
    try:
        cfg_file = find_file('db_config.py', config_path)
        ns = {}  # type: ignore
        exec(compile(cfg_file.read_text(encoding='utf-8'), str(cfg_file), 'exec'), ns)
        return {
            'db_type': ns.get('DB_TYPE', 'mysql'),
            'host':    ns.get('DB_HOST', 'localhost'),
            'user':    ns.get('DB_USER', ''),
            'passwd':  ns.get('DB_PASSWORD', ''),
            'db':      ns.get('DB_NAME', ''),
            'port':    ns.get('DB_PORT', 3306),
        }
    except FileNotFoundError:
        pass

    # Environment variable fallback (CI / Docker environments).
    return {
        'db_type': os.environ.get('DB_TYPE', 'mysql'),
        'host':    os.environ.get('DB_HOST', 'localhost'),
        'user':    os.environ.get('DB_USER', ''),
        'passwd':  os.environ.get('DB_PASSWORD', ''),
        'db':      os.environ.get('DB_NAME', ''),
        'port':    int(os.environ.get('DB_PORT', '3306')),
    }


# ─────────────────────────────────────────────────────────────────────────────
# Markdown parser — extract table entries from TRUST_LADDER_REGISTRY.md
# ─────────────────────────────────────────────────────────────────────────────

# Maps the participates bold-markdown labels to enum values.
_PARTICIPATES_MAP = {
    'full':              'full',
    'generator_staging': 'generator_staging',
    'seed_only':         'seed_only',
    'not_ladder':        'not_ladder',
    'deprecated':        'not_ladder',   # legacy alias
}

# Maps archetype labels to enum values.
_ARCHETYPE_MAP = {
    'parent':      'parent',
    'child':       'child',
    'system':      'system',
    'child (legacy)': 'child',
}


def parse_registry_markdown(path: Path) -> list[dict]:
    """
    Parse TRUST_LADDER_REGISTRY.md and return a list of table entry dicts.

    Each dict contains:
        table_name, archetype, participates, seed_required, reference_target,
        canonical_lineage_edge, promotion_target, notes

    Only rows with a backtick-quoted table name beginning 'lupo_' are included.
    """
    text = path.read_text(encoding='utf-8')
    entries = []

    # Match markdown table rows: | `lupo_something` | ... |
    # Handles **bold** wrapping around participates and archetype values.
    row_re = re.compile(
        r'^\|\s*`(lupo_[^`]+)`\s*\|'          # group 1: table_name
        r'[^|]*\|'                             # PK column
        r'[^|]*\|'                             # PK shape
        r'\s*\*?\*?([^|*]+?)\*?\*?\s*\|'      # group 2: participates
        r'\s*\*?\*?([^|*]+?)\*?\*?\s*\|'      # group 3: entity archetype
        r'([^|]*)\|',                          # group 4: notes
        re.MULTILINE,
    )

    seen = set()
    for m in row_re.finditer(text):
        table_name  = m.group(1).strip()
        participates_raw = m.group(2).strip().lower()
        archetype_raw    = m.group(3).strip().lower()
        notes_raw        = m.group(4).strip()

        participates = _PARTICIPATES_MAP.get(participates_raw, 'not_ladder')
        archetype    = _ARCHETYPE_MAP.get(archetype_raw, '')

        if not archetype:
            # Could not parse archetype — skip with warning.
            print(f"[WARN] Cannot parse archetype '{archetype_raw}' for {table_name} — skipped")
            continue

        if table_name in seen:
            continue
        seen.add(table_name)

        # Derive seed_required and reference_target from archetype invariants.
        if archetype == 'parent':
            seed_required    = 1
            reference_target = 'seed'
        elif archetype == 'child':
            seed_required    = 0
            reference_target = 'canonical'
        elif archetype == 'system':
            seed_required    = 1
            reference_target = 'seed'
        else:
            seed_required    = 0
            reference_target = 'canonical'

        # Canonical lineage edge and promotion target from notes (heuristic).
        canonical_lineage_edge = None
        promotion_target       = None
        if participates == 'full' and archetype == 'parent':
            canonical_lineage_edge = 'canonical_instance_of'
            promotion_target       = 'canonical'

        entry = {
            'table_name':             table_name,
            'archetype':              archetype,
            'participates':           participates,
            'seed_required':          seed_required,
            'reference_target':       reference_target,
            'canonical_lineage_edge': canonical_lineage_edge,
            'promotion_target':       promotion_target,
            'notes':                  notes_raw or None,
        }
        entries.append(entry)

    return entries


# ─────────────────────────────────────────────────────────────────────────────
# Invariant validation
# ─────────────────────────────────────────────────────────────────────────────

def validate_invariants(entries: list[dict]) -> list[str]:
    """
    Validate archetype invariants from the doctrine spec.
    Returns a list of error messages (empty = all pass).
    """
    errors = []
    for e in entries:
        name       = e['table_name']
        archetype  = e['archetype']
        seed_req   = e['seed_required']
        ref_target = e['reference_target']

        if archetype == 'parent':
            if not seed_req:
                errors.append(f"[INVARIANT] {name}: parent must have seed_required=1")
            if ref_target != 'seed':
                errors.append(f"[INVARIANT] {name}: parent must have reference_target='seed'")

        elif archetype == 'child':
            if seed_req:
                errors.append(f"[INVARIANT] {name}: child must have seed_required=0")
            if ref_target != 'canonical':
                errors.append(f"[INVARIANT] {name}: child must have reference_target='canonical'")

        elif archetype == 'system':
            if not seed_req:
                errors.append(f"[INVARIANT] {name}: system must have seed_required=1")

        else:
            errors.append(f"[INVARIANT] {name}: unknown archetype '{archetype}'")

    return errors


# ─────────────────────────────────────────────────────────────────────────────
# DB operations
# ─────────────────────────────────────────────────────────────────────────────

def quote_identifier(name: str, db_type: str) -> str:
    """Database-portable identifier quoting."""
    if db_type in ('mysql', 'mariadb'):
        return f'`{name}`'
    return f'"{name}"'


def fetch_db_registry(conn, db_type: str, prefix: str = 'lupo_') -> dict:
    """
    Query the registry table and return a dict keyed by table_name.
    Returns empty dict if the table does not exist (pre-DDL state).
    """
    table = prefix + 'trust_ladder_registry'
    qt    = quote_identifier(table, db_type)
    try:
        cur = conn.cursor()
        cur.execute(
            f'SELECT registry_id, table_name, archetype, participates, seed_required,'
            f'       canonical_lineage_edge, promotion_target, is_active, is_deleted,'
            f'       reference_target, notes'
            f' FROM {qt} WHERE is_deleted = 0 ORDER BY registry_id ASC'
        )
        cols = [d[0] for d in cur.description]
        rows = {}
        for row in cur.fetchall():
            r = dict(zip(cols, row))
            rows[str(r['table_name'])] = r
        return rows
    except Exception as exc:
        # Table may not exist yet (first-run before DDL).
        msg = str(exc).lower()
        if 'exist' in msg or 'no such table' in msg or 'doesn\'t exist' in msg:
            return {}
        raise


def insert_registry_row(conn, db_type: str, prefix: str, registry_id: int, entry: dict, now_ts: int):
    """Insert a new registry row."""
    table = prefix + 'trust_ladder_registry'
    qt    = quote_identifier(table, db_type)
    sql = (
        f'INSERT INTO {qt}'
        f' (registry_id, table_name, archetype, participates, seed_required,'
        f'  canonical_lineage_edge, promotion_target, is_active,'
        f'  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis,'
        f'  canonical_id, reference_target, notes)'
        f' VALUES'
        f' (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, 0, 0, NULL, ?, ?)'
    )
    if db_type in ('mysql', 'mariadb'):
        sql = sql.replace('?', '%s')
    params = (
        registry_id,
        entry['table_name'],
        entry['archetype'],
        entry['participates'],
        entry['seed_required'],
        entry.get('canonical_lineage_edge'),
        entry.get('promotion_target'),
        now_ts,
        now_ts,
        entry['reference_target'],
        entry.get('notes'),
    )
    cur = conn.cursor()
    cur.execute(sql, params)


def update_registry_row(conn, db_type: str, prefix: str, registry_id: int, entry: dict, now_ts: int):
    """Update an existing registry row."""
    table = prefix + 'trust_ladder_registry'
    qt    = quote_identifier(table, db_type)
    sql = (
        f'UPDATE {qt}'
        f' SET archetype=?, participates=?, seed_required=?, canonical_lineage_edge=?,'
        f'     promotion_target=?, reference_target=?, notes=?, updated_ymdhis=?'
        f' WHERE registry_id=?'
    )
    if db_type in ('mysql', 'mariadb'):
        sql = sql.replace('?', '%s')
    params = (
        entry['archetype'],
        entry['participates'],
        entry['seed_required'],
        entry.get('canonical_lineage_edge'),
        entry.get('promotion_target'),
        entry['reference_target'],
        entry.get('notes'),
        now_ts,
        registry_id,
    )
    cur = conn.cursor()
    cur.execute(sql, params)


# ─────────────────────────────────────────────────────────────────────────────
# Drift detection
# ─────────────────────────────────────────────────────────────────────────────

COMPARE_FIELDS = (
    'archetype', 'participates', 'seed_required',
    'canonical_lineage_edge', 'promotion_target', 'reference_target',
)


def _norm(v):
    """Normalise a DB value for comparison (None, 0, 1 consistency)."""
    if v is None:
        return None
    if isinstance(v, int):
        return v
    s = str(v).strip()
    return None if s == '' else s


def detect_drift(doc_entries: list[dict], db_rows: dict) -> list[dict]:
    """
    Compare doctrine entries with DB rows.
    Returns a list of drift descriptors: {'type': 'missing'|'changed', 'entry': ..., 'db_row': ...}
    """
    drift = []
    for entry in doc_entries:
        name = entry['table_name']
        if name not in db_rows:
            drift.append({'type': 'missing', 'entry': entry, 'db_row': None})
            continue
        db = db_rows[name]
        changed_fields = []
        for f in COMPARE_FIELDS:
            doc_val = _norm(entry.get(f))
            db_val  = _norm(db.get(f))
            if doc_val != db_val:
                changed_fields.append(f)
        if changed_fields:
            drift.append({
                'type':   'changed',
                'entry':  entry,
                'db_row': db,
                'fields': changed_fields,
            })
    return drift


# ─────────────────────────────────────────────────────────────────────────────
# Now() — packed UTC integer (doctrine timestamp_ymdhis format)
# ─────────────────────────────────────────────────────────────────────────────

def now_packed() -> int:
    """Return current UTC as packed int YYYYMMDDHHIISS (doctrine timestamp_ymdhis::now())."""
    from datetime import datetime, timezone
    dt  = datetime.now(tz=timezone.utc)
    val = (dt.year * 10_000_000_000
           + dt.month  * 100_000_000
           + dt.day    * 1_000_000
           + dt.hour   * 10_000
           + dt.minute * 100
           + dt.second)
    return val


# ─────────────────────────────────────────────────────────────────────────────
# Self-referential bootstrap entry
# ─────────────────────────────────────────────────────────────────────────────

SELF_ENTRY = {
    'table_name':             'lupo_trust_ladder_registry',
    'archetype':              'system',
    'participates':           'seed_only',
    'seed_required':          1,
    'canonical_lineage_edge': None,
    'promotion_target':       None,
    'reference_target':       'seed',
    'notes':                  'Bootstrap: registry table follows its own rules. Seed-only archetype.',
}


# ─────────────────────────────────────────────────────────────────────────────
# Main
# ─────────────────────────────────────────────────────────────────────────────

def main() -> int:
    parser = argparse.ArgumentParser(
        description='Sync lupo_trust_ladder_registry table from TRUST_LADDER_REGISTRY.md doctrine.',
    )
    parser.add_argument('--dry-run',  action='store_true', help='Report drift without writing')
    parser.add_argument('--force',    action='store_true', help='Write rows even when drift exists')
    parser.add_argument('--strict',   action='store_true', help='Exit non-zero on any drift (CI gate)')
    parser.add_argument('--registry', default=None, help='Path to TRUST_LADDER_REGISTRY.md')
    parser.add_argument('--db-config', default=None, help='Path to db_config.py')
    parser.add_argument('--table-prefix', default='lupo_', help='DB table prefix (default: lupo_)')
    parser.add_argument('-v', '--verbose', action='store_true', help='Extra detail')
    args = parser.parse_args()

    prefix = args.table_prefix

    # ── 1. Locate and parse the Markdown registry ────────────────────────────
    try:
        registry_path = find_file(
            'lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md',
            args.registry,
        )
    except FileNotFoundError as exc:
        print(f'[ERROR] {exc}', file=sys.stderr)
        return 2

    print(f'Reading doctrine from: {registry_path}')
    try:
        doc_entries = parse_registry_markdown(registry_path)
    except Exception as exc:
        print(f'[ERROR] Failed to parse registry Markdown: {exc}', file=sys.stderr)
        return 2

    if not doc_entries:
        print('[WARN] No table entries found in TRUST_LADDER_REGISTRY.md — check format.')

    print(f'Found {len(doc_entries)} table(s) in doctrine.')

    # ── 2. Validate archetype invariants ─────────────────────────────────────
    inv_errors = validate_invariants(doc_entries)
    if inv_errors:
        for err in inv_errors:
            print(f'[ERROR] {err}', file=sys.stderr)
        print('[ERROR] Invariant validation failed — aborting before any DB write.', file=sys.stderr)
        return 3

    print('Invariant validation: PASS')

    # ── 3. Connect to database ────────────────────────────────────────────────
    try:
        conn, db_type = get_db_connection(args)
    except Exception as exc:
        print(f'[ERROR] DB connection failed: {exc}', file=sys.stderr)
        return 2

    print(f'DB connection: OK (type={db_type})')

    # ── 4. Load DB state ──────────────────────────────────────────────────────
    try:
        db_rows = fetch_db_registry(conn, db_type, prefix)
    except Exception as exc:
        print(f'[ERROR] Failed to query registry table: {exc}', file=sys.stderr)
        return 2

    if not db_rows:
        print('[INFO] Registry table is empty or does not exist (pre-DDL state).')

    if args.verbose:
        print(f'DB has {len(db_rows)} registry row(s).')

    # ── 5. Detect drift ───────────────────────────────────────────────────────
    # Always include the self-referential bootstrap entry in drift check.
    all_doc = [SELF_ENTRY] + doc_entries
    drift   = detect_drift(all_doc, db_rows)

    if not drift:
        print('No drift detected — doctrine and DB are in sync.')
        return 0

    # Report drift.
    print(f'\n{len(drift)} drift item(s) found:')
    for d in drift:
        name = d['entry']['table_name']
        if d['type'] == 'missing':
            print(f'  MISSING: {name} (in doctrine, not in DB)')
        elif d['type'] == 'changed':
            print(f'  CHANGED: {name} — fields: {", ".join(d.get("fields", []))}')
            if args.verbose:
                for f in d.get('fields', []):
                    db_val  = _norm(d['db_row'].get(f))
                    doc_val = _norm(d['entry'].get(f))
                    print(f'           {f}: DB={db_val!r} → DOCTRINE={doc_val!r}')

    # TODO(LEGACY-GAP): If tables are in DB but not in doctrine, they are silently ignored.
    #   Should these be flagged as orphan rows? Could be legitimate deprecated tables.
    #   For now, only doctrine→DB drift is reported.

    # ── 6. Strict mode exit ───────────────────────────────────────────────────
    if args.strict and not args.force:
        print('\n[STRICT] Drift detected — exiting non-zero for CI gate.', file=sys.stderr)
        return 1

    if args.dry_run:
        print('\n[DRY-RUN] No changes written.')
        return 1

    # ── 7. Apply changes ──────────────────────────────────────────────────────
    if not args.force:
        # Without --force, report drift and exit non-zero so the operator decides.
        print('\nRerun with --force to apply changes, or --dry-run to preview only.')
        return 1

    print('\nApplying changes...')
    now_ts     = now_packed()
    # Assign registry_ids for missing rows starting after the max existing.
    max_id     = max((int(r.get('registry_id', 0)) for r in db_rows.values()), default=1)
    next_id    = max(max_id + 1, 2)   # registry_id=1 is always the self-referential row

    write_errors = 0
    for d in drift:
        entry = d['entry']
        name  = entry['table_name']
        try:
            if d['type'] == 'missing':
                # Self-referential row always gets registry_id=1.
                if name == 'lupo_trust_ladder_registry':
                    rid = 1
                else:
                    rid     = next_id
                    next_id += 1
                insert_registry_row(conn, db_type, prefix, rid, entry, now_ts)
                print(f'  INSERTED: {name} (registry_id={rid})')

            elif d['type'] == 'changed':
                rid = int(d['db_row']['registry_id'])
                update_registry_row(conn, db_type, prefix, rid, entry, now_ts)
                print(f'  UPDATED:  {name} (registry_id={rid})')

        except Exception as exc:
            print(f'  ERROR: {name} — {exc}', file=sys.stderr)
            write_errors += 1
            # TODO(LEGACY-GAP): collect errors and continue vs abort-on-first-error.
            # Current behavior: collect and continue; report at end.

    try:
        conn.commit()
    except Exception as exc:
        print(f'[ERROR] Commit failed: {exc}', file=sys.stderr)
        return 2

    if write_errors:
        print(f'\n{write_errors} write error(s). See above.')
        return 1

    print('\nSync complete. Registry is now in sync with doctrine.')
    print('[INFO] Call TrustLadderRegistry::clearCache() in any running PHP workers to invalidate.')
    return 0


if __name__ == '__main__':
    sys.exit(main())
