#!/usr/bin/env python3
"""
Phase 7 — livehelp_ table import validation.
Checks: existence, row count, timestamp doctrine (BIGINT UTC, no datetime/timestamp),
        NULL/zero/non-14-digit in semantic timestamp columns.

Usage: python scripts/validate_livehelp_import.py
Env: DB_HOST, DB_USER, DB_PASS, DB_NAME (defaults: localhost, root, ServBay.dev, lupopedia)

Output: JSON to stdout. Use --scorecard to print a one-line per-table summary.
"""
import os
import sys
import json
import re

try:
    import mysql.connector
except ImportError:
    print('{"error": "mysql-connector-python required"}', file=sys.stderr)
    sys.exit(1)

def connect():
    return mysql.connector.connect(
        host=os.getenv('DB_HOST', 'localhost'),
        user=os.getenv('DB_USER', 'root'),
        password=os.getenv('DB_PASS', 'ServBay.dev'),
        database=os.getenv('DB_NAME', 'lupopedia'),
        charset='utf8mb4',
    )

def main():
    out = []
    db = os.getenv('DB_NAME', 'lupopedia')
    with connect() as conn:
        cur = conn.cursor(dictionary=True)
        cur.execute("SHOW TABLES LIKE 'livehelp_%%'")
        tables = [list(r.values())[0] for r in cur.fetchall()]

        for t in sorted(tables):
            r = {
                'table': t, 'exists': True, 'row_count': 0,
                'ts_valid': 'pass', 'schema_match': 'pass', 'doctrine': 'no', 'notes': []
            }
            try:
                cur.execute("SELECT COUNT(*) AS c FROM `" + t.replace('`','``') + "`")
                r['row_count'] = cur.fetchone()['c']
            except Exception as e:
                r['notes'].append('count: ' + str(e))
                r['ts_valid'] = r['schema_match'] = 'fail'

            cur.execute("""
                SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA=%s AND TABLE_NAME=%s ORDER BY ORDINAL_POSITION
            """, (db, t))
            cols = cur.fetchall()
            ts_cols = []
            for c in cols:
                n = (c.get('COLUMN_NAME') or '').lower()
                ty = (c.get('DATA_TYPE') or '').lower()
                if ty in ('timestamp','datetime'):
                    r['doctrine'] = 'yes'
                    r['notes'].append('illegal ' + ty + ' on ' + c.get('COLUMN_NAME','?'))
                    r['ts_valid'] = r['schema_match'] = 'fail'
                if ty == 'bigint' and re.search(
                    r'time|date|expires|lastaction|showedup|lastcalled|chataction|last_login|_expires|_at|_ymdhis|dateof',
                    n
                ):
                    ts_cols.append(c.get('COLUMN_NAME'))

            for col in ts_cols:
                try:
                    cur.execute(
                        "SELECT COUNT(*) AS c FROM `" + t.replace('`','``') + "` WHERE `" + col.replace('`','``') + "` IS NULL"
                    )
                    n = cur.fetchone()['c']
                    if n > 0:
                        r['notes'].append(col + ': ' + str(n) + ' NULL')
                        r['ts_valid'] = r['schema_match'] = 'fail'
                    cur.execute(
                        "SELECT COUNT(*) AS c FROM `" + t.replace('`','``') + "` WHERE `" + col.replace('`','``') + "` = 0"
                    )
                    z = cur.fetchone()['c']
                    if z > 0:
                        r['notes'].append(col + ': ' + str(z) + ' zero')
                    q = (
                        "SELECT COUNT(*) AS c FROM `" + t.replace('`','``') + "` "
                        "WHERE `" + col.replace('`','``') + "` IS NOT NULL AND `" + col.replace('`','``') + "` != 0 "
                        "AND LENGTH(CAST(`" + col.replace('`','``') + "` AS CHAR)) NOT IN (6,8,14)"
                    )
                    cur.execute(q)
                    bad = cur.fetchone()['c']
                    if bad > 0:
                        r['notes'].append(col + ': ' + str(bad) + ' non-doctrine (allow 6/8/14-digit)')
                        r['ts_valid'] = r['schema_match'] = 'fail'
                except Exception as e:
                    r['notes'].append(col + ': ' + str(e))

            out.append(r)

    total = len(out)
    passed = sum(1 for x in out if x['ts_valid']=='pass' and x['schema_match']=='pass' and x['doctrine']=='no')
    failed = total - passed
    overall = 'PASS' if failed == 0 else 'FAIL'

    if '--scorecard' in sys.argv:
        for x in out:
            status = 'PASS' if (x['ts_valid']=='pass' and x['schema_match']=='pass' and x['doctrine']=='no') else 'FAIL'
            notes = '; '.join(x['notes']) if x['notes'] else ''
            print(f"{x['table']}\t{x['row_count']}\t{status}\t{notes}")
        print(f"---\nTotal: {total}  Passed: {passed}  Failed: {failed}  Overall: {overall}")
    else:
        print(json.dumps({
            'tables': out, 'total_tables': total, 'total_passed': passed,
            'total_failed': failed, 'overall': overall
        }, indent=2))

if __name__ == '__main__':
    main()
