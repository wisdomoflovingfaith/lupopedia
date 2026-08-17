#!/usr/bin/env python3
# -----
# lupopedia.headers:
#   header_format_version: "4.2.11"
#   path_from_lupopedia_root: scripts/generate_color_registry_seed.py
#   web_path: https://www.lupopedia.com/lupopedia/scripts/generate_color_registry_seed.py
#   status: active
#   when_updated: "20260817025714"
#   trust_tier: canonical
#   questions_toon: null
#   memory_toon: memory/development/canonical/1026/08/generate_color_registry_seed.toon
#   atoms_toon: null
#   transcript_jsonl: 0/development/prd-01-b-color-registry
#   artifact_type: script
#   artifact_kind: generator
#   channel_key: development
#   federation_node_id: 0
#   thread_key: ""
#   lupopedia.schema: tooling
#   prd_cluster: 01_B_90_A
#   title: "Generate PRD 01_B color registry seed SQL from PRT.LUP.colors.csv"
#   summary: "Writes INSERT statements for color_groups (PRD 90 base register) and color_names (CSV)."
# -----
"""Generate color registry seed SQL from docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv."""

from __future__ import print_function

import csv
import os
import sys

GROUPS = [
    "BLACK",
    "BLUE",
    "BROWN",
    "GOLD",
    "GRAY",
    "GREEN",
    "ORANGE",
    "PINK",
    "PURPLE",
    "RED",
    "SILVER",
    "WHITE",
    "YELLOW",
]


def sql_quote(value):
    return str(value).replace("'", "''")


def main():
    root = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    csv_path = os.path.join(
        root, "docs", "protocols", "hex", "PRT.LUP", "PRT.LUP.colors.csv"
    )
    out_path = os.path.join(root, "install", "_color_registry_seed_fragment.sql")
    if len(sys.argv) > 1:
        out_path = sys.argv[1]

    group_set = {}
    for group in GROUPS:
        group_set[group.lower()] = group

    lines = []
    lines.append("-- =============================================================================")
    lines.append("-- PRD 01_B Color Registry seed (PRD 90 Color Identity)")
    lines.append("-- GroupColor: PRD 90 section 5.2 base register.")
    lines.append("-- ColorName: docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv")
    lines.append("-- group_color on names is set only when color_name matches a base GroupColor.")
    lines.append("-- Other CSS seed words keep group_color empty until Captain assignment.")
    lines.append("-- hex6 from CSV hex_color, uppercased, no hash. No hex5. Not agent_colors.")
    lines.append("-- =============================================================================")
    lines.append("")
    lines.append("INSERT INTO {{prefix}}color_groups (")
    lines.append("    color_group_id, group_color, protocol_short, sort_order,")
    lines.append("    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, federation_node_id")
    lines.append(") VALUES")

    gvals = []
    idx = 1
    for group in GROUPS:
        gvals.append(
            "(%d, '%s', 'PRT.LUP', %d, 0, 0, 0, 0, 0)" % (idx, sql_quote(group), idx)
        )
        idx += 1
    lines.append(",\n".join(gvals))
    lines.append("ON DUPLICATE KEY UPDATE")
    lines.append("    group_color = VALUES(group_color),")
    lines.append("    protocol_short = VALUES(protocol_short),")
    lines.append("    sort_order = VALUES(sort_order),")
    lines.append("    is_deleted = 0,")
    lines.append("    deleted_ymdhis = 0,")
    lines.append("    updated_ymdhis = VALUES(updated_ymdhis);")
    lines.append("")

    rows = []
    with open(csv_path, "r") as handle:
        reader = csv.DictReader(handle)
        for row in reader:
            color_name_id = int(row["word_registry_id"])
            word = row["word"].strip()
            hex6 = row["hex_color"].strip().lstrip("#").upper()
            if len(hex6) != 6:
                raise SystemExit("bad hex for %s: %s" % (word, hex6))
            field_type = row["field_type"].strip() or "node"
            iso_language = row["iso_language"].strip() or "EN"
            source_table = row["source_table"].strip() or "seed"
            usage_count = int(row["usage_count"] or 0)
            actor_hex = row["actor_hex"].strip().lstrip("#").upper() or "808080"
            created_ymdhis = int(row["created_ymdhis"] or 0)
            updated_ymdhis = int(row["updated_ymdhis"] or 0)
            group_color = group_set.get(word.lower(), "")
            rows.append(
                (
                    color_name_id,
                    word,
                    hex6,
                    field_type,
                    iso_language,
                    source_table,
                    usage_count,
                    actor_hex,
                    created_ymdhis,
                    updated_ymdhis,
                    group_color,
                )
            )

    lines.append("INSERT INTO {{prefix}}color_names (")
    lines.append("    color_name_id, protocol_short, group_color, color_name, hex6, gold_mark,")
    lines.append("    field_type, iso_language, source_table, usage_count, actor_hex,")
    lines.append("    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, federation_node_id")
    lines.append(") VALUES")

    nvals = []
    for row in rows:
        nvals.append(
            "(%d, 'PRT.LUP', '%s', '%s', '%s', '', '%s', '%s', '%s', %d, '%s', %d, %d, 0, 0, 0)"
            % (
                row[0],
                sql_quote(row[10]),
                sql_quote(row[1]),
                sql_quote(row[2]),
                sql_quote(row[3]),
                sql_quote(row[4]),
                sql_quote(row[5]),
                row[6],
                sql_quote(row[7]),
                row[8],
                row[9],
            )
        )
    lines.append(",\n".join(nvals))
    lines.append("ON DUPLICATE KEY UPDATE")
    lines.append("    protocol_short = VALUES(protocol_short),")
    lines.append("    group_color = VALUES(group_color),")
    lines.append("    color_name = VALUES(color_name),")
    lines.append("    hex6 = VALUES(hex6),")
    lines.append("    gold_mark = VALUES(gold_mark),")
    lines.append("    field_type = VALUES(field_type),")
    lines.append("    iso_language = VALUES(iso_language),")
    lines.append("    source_table = VALUES(source_table),")
    lines.append("    usage_count = VALUES(usage_count),")
    lines.append("    actor_hex = VALUES(actor_hex),")
    lines.append("    is_deleted = 0,")
    lines.append("    deleted_ymdhis = 0,")
    lines.append("    updated_ymdhis = VALUES(updated_ymdhis);")
    lines.append("")

    with open(out_path, "w") as handle:
        handle.write("\n".join(lines) + "\n")
    print("wrote %d color_names and %d color_groups to %s" % (len(rows), len(GROUPS), out_path))


if __name__ == "__main__":
    main()
