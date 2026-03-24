#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/generate_install_sql.py"
#   last_modified_utc: "20260324175617"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Generate DB-agnostic install_new_lupopedia.sql from lupopedia_mysql.sql.
Doctrine 17: BIGINT/INT/SMALLINT/TINYINT only, no display widths, no UNSIGNED,
no BOOLEAN, timestamps as BIGINT YYYYMMDDHHIISS, no FKs, no triggers.

Run from project root: python scripts/generate_install_sql.py
"""
import re
import sys
from pathlib import Path

EXCLUDE = {
    "lupo_crafty_syntax_auto_invite",
    "lupo_crafty_syntax_chat_mod_departments",
    "lupo_crafty_syntax_chat_questions",
    "lupo_crafty_syntax_layer_invites",
    "lupo_crafty_syntax_leave_message",
    "lupo_crafty_user_mapping",
    "lupo_contexts_old",
}


def convert_col(line: str) -> str:
    s = line.strip().rstrip(",").strip()
    s = re.sub(r"`(\w+)`", r"\1", s)
    s = re.sub(r"\s+CHARACTER SET \S+", "", s)
    s = re.sub(r"\s+COLLATE \S+", "", s)
    # COMMENT '...' (allow escaped '' inside)
    s = re.sub(r"\s+COMMENT\s+'(?:[^']|'')*'", "", s)
    s = re.sub(r"\s+UNSIGNED", "", s, flags=re.IGNORECASE)
    s = re.sub(r"\b(BIGINT|INT|SMALLINT|TINYINT)\(\d+\)", r"\1", s, flags=re.IGNORECASE)
    s = re.sub(r"\bTINYINT\s*\(\s*1\s*\)", "TINYINT", s, flags=re.IGNORECASE)
    s = re.sub(r"\blongtext\b", "text", s, flags=re.IGNORECASE)
    if "timestamp" in s.lower() or "datetime" in s.lower():
        s = re.sub(r"\b(timestamp|datetime)\b[^,\)]*", "bigint", s, flags=re.IGNORECASE)
        s = re.sub(r"DEFAULT CURRENT_TIMESTAMP", "DEFAULT NULL", s, flags=re.IGNORECASE)
        s = re.sub(r"ON UPDATE CURRENT_TIMESTAMP", "", s, flags=re.IGNORECASE)
    s = re.sub(r"\benum\s*\([^)]+\)", "varchar(64)", s, flags=re.IGNORECASE)
    if "char(14)" in s.lower() and ("ymdhis" in s.lower() or "timestamp" in s.lower() or "timestamp_utc" in s.lower()):
        s = re.sub(r"char\s*\(\s*14\s*\)", "bigint", s, flags=re.IGNORECASE)
    return s


def main():
    base = Path(__file__).resolve().parent
    project_root = base.parent
    db_dir = project_root / "database"
    src = db_dir / "install" / "lupopedia_mysql.sql"
    if not src.exists():
        print("Missing:", src, file=sys.stderr)
        sys.exit(1)
    text = src.read_text(encoding="utf-8", errors="replace")

    # Parse CREATE TABLE blocks: table name and body (columns)
    tables = []
    for m in re.finditer(
        r"CREATE TABLE\s+`?(\w+)`?\s*\(\s*\n(.*?)\n\)\s*ENGINE",
        text,
        re.DOTALL | re.IGNORECASE,
    ):
        tname = m.group(1)
        if tname in EXCLUDE or "crafty_syntax" in tname:
            continue
        body = m.group(2)
        cols = []
        for line in body.split("\n"):
            line = line.strip().rstrip(",")
            if not line or line.startswith("--"):
                continue
            if "PRIMARY KEY" in line and "ADD" not in line or "KEY `" in line or "UNIQUE KEY `" in line:
                continue
            c = convert_col(line)
            if c:
                cols.append(c)
        if cols:
            tables.append((tname, cols))

    # Parse indexes: from "Indexes for dumped tables" to "AUTO_INCREMENT" or "Constraints"
    idx_start = text.find("-- Indexes for dumped tables")
    if idx_start < 0:
        idx_start = text.find("ADD PRIMARY KEY")
    idx_end = text.find("-- AUTO_INCREMENT for table")
    if idx_end < 0:
        idx_end = text.find("-- Constraints for dumped tables")
    if idx_end < 0:
        idx_end = len(text)
    idx_text = text[idx_start:idx_end] if idx_start >= 0 else ""

    indexes = {}
    # Match each "Indexes for table `name`" then (optional comment lines) then "ALTER TABLE `name`" and ADD lines
    for m in re.finditer(
        r"--\s*Indexes for table\s+`?(\w+)`?.*?ALTER TABLE\s+`?\w+`?\s*\n(.*?)(?=\n--\s*Indexes for table|\n--\s*AUTO_INCREMENT|$)",
        idx_text,
        re.DOTALL | re.IGNORECASE,
    ):
        tname = m.group(1)
        add_block = m.group(2)
        items = []
        for add in re.finditer(r"ADD\s+(PRIMARY KEY|UNIQUE KEY\s+`?(\w+)`?|KEY\s+`?(\w+)`?)\s*\(`?([^)]+)`?\)", add_block):
            kind = add.group(1)
            cols = [x.strip().strip("`") for x in add.group(4).split(",")]
            if "PRIMARY" in kind:
                items.append(("PK", cols))
            elif "UNIQUE" in kind:
                items.append(("UQ", add.group(2), cols))
            else:
                items.append(("IX", add.group(3), cols))
        if items:
            indexes[tname] = items

    out = []
    out.append("-- FILE: database/migrations/install_new_lupopedia.sql")
    out.append("-- TYPE: sql")
    out.append("-- Purpose: Install brand-new Lupopedia database from scratch. DB-agnostic (MySQL + PostgreSQL).")
    out.append("-- Doctrine 17: no FKs, no triggers, BIGINT timestamps, no display widths, no UNSIGNED.")
    out.append("-- No Crafty Syntax logic, no migration, no DROP TABLE.")
    out.append("")

    for tname, cols in tables:
        idx_items = indexes.get(tname, [])
        pk_cols = None
        for it in idx_items:
            if it[0] == "PK":
                pk_cols = it[1]
                break
        if pk_cols:
            cols = [c for c in cols if not re.match(r"PRIMARY KEY\s*\(.*\)", c, re.IGNORECASE)]
            cols.append("PRIMARY KEY (" + ", ".join(pk_cols) + ")")
        out.append("CREATE TABLE " + tname + " (")
        out.append("  " + ",\n  ".join(cols))
        out.append(");")
        out.append("")
        for it in idx_items:
            if it[0] == "PK":
                continue
            idx_name = it[1]
            # PostgreSQL requires globally unique index names; prefix with table
            safe_name = tname + "_" + idx_name if not idx_name.startswith(tname + "_") else idx_name
            if it[0] == "UQ":
                out.append("CREATE UNIQUE INDEX " + safe_name + " ON " + tname + " (" + ", ".join(it[2]) + ");")
            else:
                out.append("CREATE INDEX " + safe_name + " ON " + tname + " (" + ", ".join(it[2]) + ");")
        if idx_items:
            out.append("")

    # Seed atoms: minimal bootstrap (optional)
    out.append("-- Required seed atoms (minimal bootstrap). Expand via database/install/ seed scripts.")
    out.append("-- INSERT lupo_atoms for GLOBAL_CURRENT_LUPOPEDIA_VERSION and kernel actors/channels as needed.")
    out.append("")

    dest = db_dir / "migrations" / "install_new_lupopedia.sql"
    dest.write_text("\n".join(out), encoding="utf-8")
    print("Wrote", dest, "(", len(tables), "tables)")


if __name__ == "__main__":
    main()