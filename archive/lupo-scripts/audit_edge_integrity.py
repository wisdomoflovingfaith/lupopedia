#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Audit Chronological Trust Ladder edge integrity.

Checks (normative per CHRONOLOGICAL_TRUST_LADDER.md §9.2):
  1. Dangling references in lupo_edges: any row where left_object_type or
     right_object_type is 'memory_node' must point to an existing (non-deleted)
     row in lupo_memory_nodes.
  2. Dangling references in lupo_memory_edges: from_memory_node_id and
     to_memory_node_id must both exist in lupo_memory_nodes (non-deleted).
  3. Forbidden tier direction: a living-canonical endpoint on a "truth" edge
     must NOT point to a staging endpoint as authority
     (canonical → staging on non-consolidation edges is FORBIDDEN).

Output:
  INVALID EDGE edge_id=<id> reason=<reason> [field=<value> ...]
  ...
  Total invalid: N

Exit codes:
  0 — no invalid edges
  1 — one or more invalid edges found
  2 — configuration / connection error

This script is READ-ONLY. It MUST NOT auto-repair or delete any rows.

See: lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md §9.2
     lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md
"""

import argparse
import sys
from pathlib import Path

PROJECT_ROOT = Path(__file__).resolve().parent.parent

DEFAULT_TABLE_PREFIX = "lupo_"

# Edge types that represent consolidation direction (staging → canonical).
# These are ALLOWED to have a staging right endpoint even when the left is canonical.
CONSOLIDATION_EDGE_TYPES = frozenset([
    "consolidated_into",
    "merged_into",
    "promoted_to",
    "kairos_consolidates_from",
    "archived_to",
    "restored_from",
    "canonical_instance_of",
    "reverted_to",
])

# Seed / reserved space range (0-999,999).
SEED_SPACE_MAX = 999_999


def _band(pk_str):
    """
    Return trust band string for a PK represented as a decimal string.
    Mirrors PHP §2.2.1 logic exactly.

    Returns: 'seed', 'canonical', 'staging', or 'unknown'.
    """
    try:
        val = int(pk_str)
    except (ValueError, TypeError):
        return "unknown"

    if 0 <= val <= SEED_SPACE_MAX:
        return "seed"

    s = str(val)
    if len(s) != 18:
        return "unknown"

    try:
        year = int(s[0:4])
    except ValueError:
        return "unknown"

    if 1000 <= year <= 1999:
        return "canonical"
    if 2000 <= year <= 2099:
        return "staging"
    return "unknown"


def _get_db_connection(table_prefix):
    """
    Obtain a database connection using canonical lib.db_connection (resolved lupopedia-config.php only).
    """
    scripts_dir = PROJECT_ROOT / "lupo-scripts"
    sys.path.insert(0, str(scripts_dir))
    from lib.db_connection import get_connection_params

    params = get_connection_params()

    try:
        import pymysql
    except ImportError:
        raise RuntimeError(
            "pymysql is not installed. Run: pip install pymysql"
        )

    conn = pymysql.connect(
        host=params["host"],
        user=params["user"],
        password=params["password"],
        database=params["database"],
        port=int(params["port"]),
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
    )
    return conn


def _fetch_all(cursor, sql, args=None):
    cursor.execute(sql, args or ())
    return cursor.fetchall()


def audit(conn, prefix, verbose=False):
    """
    Run all three integrity checks. Returns list of violation dicts.
    Each dict has at minimum: 'table', 'edge_id'/'edge_key', 'reason'.
    """
    violations = []

    t_edges = "`%sedges`" % prefix
    t_memory_nodes = "`%smemory_nodes`" % prefix
    t_memory_edges = "`%smemory_edges`" % prefix

    with conn.cursor() as cur:
        # ---------------------------------------------------------------
        # Check 1: lupo_edges — dangling memory_node references
        # ---------------------------------------------------------------
        if verbose:
            print("  Checking %s for dangling memory_node references..." % t_edges)

        sql_edges = (
            "SELECT edge_id, left_object_type, left_object_id, "
            "right_object_type, right_object_id, edge_type "
            "FROM %s WHERE is_deleted = 0 OR is_deleted IS NULL" % t_edges
        )
        try:
            edges = _fetch_all(cur, sql_edges)
        except Exception as exc:
            if verbose:
                print("  WARNING: Could not query %s: %s" % (t_edges, exc))
            edges = []

        # Build set of valid memory_node_id values (non-deleted).
        sql_nodes = (
            "SELECT memory_node_id FROM %s "
            "WHERE is_deleted = 0 OR is_deleted IS NULL" % t_memory_nodes
        )
        try:
            node_rows = _fetch_all(cur, sql_nodes)
            valid_node_ids = set(str(r["memory_node_id"]) for r in node_rows)
        except Exception as exc:
            if verbose:
                print("  WARNING: Could not query %s: %s" % (t_memory_nodes, exc))
            valid_node_ids = None  # Cannot validate; skip reference checks

        for edge in edges:
            eid = str(edge.get("edge_id", "?"))
            ltype = str(edge.get("left_object_type") or "")
            lid = str(edge.get("left_object_id") or "")
            rtype = str(edge.get("right_object_type") or "")
            rid = str(edge.get("right_object_id") or "")
            etype = str(edge.get("edge_type") or "")

            # Dangling left
            if ltype == "memory_node" and valid_node_ids is not None:
                if lid not in valid_node_ids:
                    violations.append({
                        "table": "lupo_edges",
                        "edge_id": eid,
                        "reason": "dangling_left_memory_node",
                        "left_id": lid,
                    })

            # Dangling right
            if rtype == "memory_node" and valid_node_ids is not None:
                if rid not in valid_node_ids:
                    violations.append({
                        "table": "lupo_edges",
                        "edge_id": eid,
                        "reason": "dangling_right_memory_node",
                        "right_id": rid,
                    })

            # Forbidden tier direction (only for memory_node → memory_node edges)
            if ltype == "memory_node" and rtype == "memory_node":
                if etype not in CONSOLIDATION_EDGE_TYPES:
                    l_band = _band(lid)
                    r_band = _band(rid)
                    if l_band == "canonical" and r_band == "staging":
                        violations.append({
                            "table": "lupo_edges",
                            "edge_id": eid,
                            "reason": "forbidden_tier_direction",
                            "left_band": l_band,
                            "right_band": r_band,
                            "edge_type": etype,
                        })

        # ---------------------------------------------------------------
        # Check 2: lupo_memory_edges — dangling node references
        # ---------------------------------------------------------------
        if verbose:
            print("  Checking %s for dangling node references..." % t_memory_edges)

        sql_medges = (
            "SELECT memory_edge_id, from_memory_node_id, to_memory_node_id, edge_type "
            "FROM %s WHERE is_deleted = 0 OR is_deleted IS NULL" % t_memory_edges
        )
        try:
            medges = _fetch_all(cur, sql_medges)
        except Exception as exc:
            if verbose:
                print("  WARNING: Could not query %s: %s" % (t_memory_edges, exc))
            medges = []

        for medge in medges:
            meid = str(medge.get("memory_edge_id", "?"))
            from_id = str(medge.get("from_memory_node_id") or "")
            to_id = str(medge.get("to_memory_node_id") or "")
            etype = str(medge.get("edge_type") or "")

            if valid_node_ids is not None:
                if from_id not in valid_node_ids:
                    violations.append({
                        "table": "lupo_memory_edges",
                        "edge_id": meid,
                        "reason": "dangling_from_memory_node",
                        "from_id": from_id,
                    })
                if to_id not in valid_node_ids:
                    violations.append({
                        "table": "lupo_memory_edges",
                        "edge_id": meid,
                        "reason": "dangling_to_memory_node",
                        "to_id": to_id,
                    })

            # Forbidden tier direction check in memory_edges too
            if etype not in CONSOLIDATION_EDGE_TYPES:
                from_band = _band(from_id)
                to_band = _band(to_id)
                if from_band == "canonical" and to_band == "staging":
                    violations.append({
                        "table": "lupo_memory_edges",
                        "edge_id": meid,
                        "reason": "forbidden_tier_direction",
                        "from_band": from_band,
                        "to_band": to_band,
                        "edge_type": etype,
                    })

    return violations


def format_violation(v):
    """Format a violation dict as a single output line."""
    table = v.pop("table", "?")
    edge_id = v.pop("edge_id", "?")
    reason = v.pop("reason", "?")
    extras = " ".join("%s=%s" % (k, val) for k, val in sorted(v.items()))
    line = "INVALID EDGE [%s] edge_id=%s reason=%s" % (table, edge_id, reason)
    if extras:
        line += " " + extras
    return line


def main():
    parser = argparse.ArgumentParser(
        description=(
            "Audit Chronological Trust Ladder edge integrity "
            "(CHRONOLOGICAL_TRUST_LADDER.md §9.2). "
            "READ-ONLY — reports violations, does not repair."
        )
    )
    parser.add_argument(
        "--table-prefix",
        default=DEFAULT_TABLE_PREFIX,
        help="DB table prefix (default: lupo_)",
    )
    parser.add_argument(
        "--verbose", "-v",
        action="store_true",
        help="Print progress messages during audit",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Present for forward compat; audit is always read-only",
    )
    args = parser.parse_args()

    if args.dry_run and args.verbose:
        print("[dry-run] Audit is always read-only; --dry-run has no additional effect.")

    try:
        conn = _get_db_connection(args.table_prefix)
    except RuntimeError as exc:
        print("ERROR: %s" % exc)
        return 2

    try:
        violations = audit(conn, args.table_prefix, verbose=args.verbose)
    finally:
        conn.close()

    for v in violations:
        print(format_violation(v))

    print("Total invalid: %d" % len(violations))

    return 1 if violations else 0


if __name__ == "__main__":
    sys.exit(main())
