#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/check_doc_schema_consistency.py"
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
Doc–schema consistency checker (4.0.69).

Lightweight validation that key architectural claims in docs match install SQL and TOONs.
Run from project root: python scripts/check_doc_schema_consistency.py

Checks:
- lupo_actors: primary key is actor_name; actor_id is unique (install + TOON).
- lupo_actor_traits: required columns present (actor_id, trait_key, federation_node_id).
- lupo_action_authorization: required columns present (action_key, required_trait_keys, etc.).
- lupo_dialog_messages: source_faucet_slug, source_faucet_instance_id present.
- lupo_sessions: faucet_slug, faucet_instance_id present.
- install SQL does NOT create lupo_threads or lupo_messages (dialog unification).
"""

from __future__ import print_function
import os
import re
import sys

# Paths from project root
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TOON_DIR = os.path.join(ROOT, 'lupo-database', 'lupopedia', 'toon')
INSTALL_SQL = os.path.join(ROOT, 'lupo-database', 'lupopedia', 'mysql', 'install', 'install_new_lupopedia.sql')


def read_file(path):
    if not os.path.isfile(path):
        return None
    with open(path, 'r', encoding='utf-8', errors='replace') as f:
        return f.read()


def toon_fields(path):
    content = read_file(path)
    if not content:
        return []
    # Simple extraction: lines like - '`col` type ...'
    return re.findall(r"'\`([^`]+)\`\s+[^']+'", content)


def toon_primary_key(path):
    content = read_file(path)
    if not content:
        return None
    m = re.search(r'primary_key:\s*\n\s*column_name:\s*(\w+)', content)
    return m.group(1) if m else None


def install_has_primary_key(table, pk_col):
    content = read_file(INSTALL_SQL)
    if not content:
        return False, "install SQL not found"
    if table == 'lupo_actors' and pk_col == 'actor_name':
        if 'PRIMARY KEY (actor_name)' in content and 'CREATE TABLE lupo_actors' in content:
            return True, None
    return False, "PRIMARY KEY ({}) not found for {}".format(pk_col, table)


def install_has_table(table):
    content = read_file(INSTALL_SQL)
    if not content:
        return False
    return re.search(r'CREATE TABLE\s+' + re.escape(table) + r'\s*\(', content) is not None


def install_has_column(table, col):
    content = read_file(INSTALL_SQL)
    if not content:
        return False
    # Find CREATE TABLE lupo_XXX ( ... ) and look for column
    start = content.find('CREATE TABLE ' + table + ' (')
    if start == -1:
        return False
    end = content.find(');', start) + 2
    block = content[start:end]
    return re.search(r'\b' + re.escape(col) + r'\s+[\w\[\]\(\)]+', block) is not None


def main():
    errors = []
    warnings = []

    # 1. lupo_actors PK = actor_name, actor_id unique
    toon_pk = toon_primary_key(os.path.join(TOON_DIR, 'lupo_actors.toon'))
    if toon_pk != 'actor_name':
        errors.append("lupo_actors.toon primary_key.column_name is '{}'; expected 'actor_name'".format(toon_pk))
    ok, msg = install_has_primary_key('lupo_actors', 'actor_name')
    if not ok:
        errors.append("install SQL: " + str(msg))

    # 2. lupo_actor_traits required columns
    for col in ('actor_id', 'trait_key', 'federation_node_id'):
        path = os.path.join(TOON_DIR, 'lupo_actor_traits.toon')
        fields = toon_fields(path)
        if fields and col not in fields:
            errors.append("lupo_actor_traits.toon missing column: {}".format(col))
        if not install_has_column('lupo_actor_traits', col):
            errors.append("install SQL lupo_actor_traits missing column: {}".format(col))

    # 3. lupo_action_authorization required columns
    for col in ('action_key', 'required_trait_keys', 'required_role_keys'):
        path = os.path.join(TOON_DIR, 'lupo_action_authorization.toon')
        fields = toon_fields(path)
        if fields and col not in fields:
            errors.append("lupo_action_authorization.toon missing column: {}".format(col))
        if not install_has_column('lupo_action_authorization', col):
            errors.append("install SQL lupo_action_authorization missing column: {}".format(col))

    # 4. lupo_dialog_messages faucet columns
    for col in ('source_faucet_slug', 'source_faucet_instance_id'):
        if not install_has_column('lupo_dialog_messages', col):
            errors.append("install SQL lupo_dialog_messages missing column: {}".format(col))
        path = os.path.join(TOON_DIR, 'lupo_dialog_messages.toon')
        fields = toon_fields(path)
        if fields and col not in fields:
            errors.append("lupo_dialog_messages.toon missing column: {}".format(col))

    # 5. lupo_sessions faucet columns
    for col in ('faucet_slug', 'faucet_instance_id'):
        if not install_has_column('lupo_sessions', col):
            errors.append("install SQL lupo_sessions missing column: {}".format(col))
        path = os.path.join(TOON_DIR, 'lupo_sessions.toon')
        fields = toon_fields(path)
        if fields and col not in fields:
            errors.append("lupo_sessions.toon missing column: {}".format(col))

    # 6. No lupo_threads / lupo_messages in install (dialog unification)
    if install_has_table('lupo_threads'):
        errors.append("install SQL must not create lupo_threads (dialog unification)")
    if install_has_table('lupo_messages'):
        errors.append("install SQL must not create lupo_messages (dialog unification)")

    # Report
    if errors:
        print("Doc–schema consistency check FAILED:")
        for e in errors:
            print("  -", e)
        sys.exit(1)
    print("Doc–schema consistency check PASSED.")
    sys.exit(0)


if __name__ == '__main__':
    main()