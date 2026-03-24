#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324182200"
#   file_path_from_root: "lupo-scripts/import_content.py"
#   last_modified_utc: "20260324182200"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324182200"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
import_content.py

Imports a LUPOPEDIA Markdown artifact into lupo_contents.

Behavior:
  - Reads full file
  - Extracts YAML front matter (first --- block) and body (everything after)
  - Uses yaml.safe_load to parse lupopedia.headers
  - Generates deterministic BIGINT content_id using:
      sha256(file_path_from_root + "\n" + body_content)
    and int(digest[:16], 16) mapped into signed BIGINT range.
  - Upserts into MySQL (no ORM, explicit columns)
  - Updates lupopedia.headers.content_id in-memory and writes the file back to disk

No randomness, no hidden state, no DB-side logic. Timestamps are generated in Python.
"""

from __future__ import annotations

import argparse
import hashlib
import os
import re
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any, Dict, List, Tuple

from lib.header_validation import validate_header

try:
    import yaml
except ModuleNotFoundError:
    print("ERROR: PyYAML not installed. Install with: pip install pyyaml", file=sys.stderr)
    raise

try:
    import pymysql
except ModuleNotFoundError:
    print("ERROR: pymysql not installed. Install with: pip install pymysql", file=sys.stderr)
    raise


def _repo_root() -> Path:
    return Path(__file__).resolve().parent.parent


def _load_connection_params() -> Dict[str, Any]:
    """
    Connection must come from environment variables or lupopedia-config.php.
    We require config presence if env vars are not set.
    """
    env_needed = ("DB_HOST", "DB_USER", "DB_PASSWORD", "DB_NAME")
    if all(os.getenv(k) for k in env_needed):
        return {
            "host": os.getenv("DB_HOST"),
            "user": os.getenv("DB_USER"),
            "password": os.getenv("DB_PASSWORD"),
            "database": os.getenv("DB_NAME"),
            "port": int(os.getenv("DB_PORT") or "3306"),
            "charset": os.getenv("DB_CHARSET") or "utf8mb4",
        }

    config_path = _repo_root() / "lupopedia-config.php"
    if not config_path.is_file():
        missing = [k for k in env_needed if not os.getenv(k)]
        raise RuntimeError(
            "Missing DB configuration. Provide env vars "
            f"{', '.join(env_needed)} (missing: {', '.join(missing)}) "
            "or ensure lupopedia-config.php exists in the repo root."
        )

    # Reuse existing helper that reads defines from lupopedia-config.php.
    from db_config import get_connection_params  # local import

    params = get_connection_params()
    params["charset"] = "utf8mb4"
    return params


def _load_table_prefix_from_config() -> str:
    """
    Best-effort parsing of LUPO_TABLE_PREFIX from lupopedia-config.php.
    If not found, fall back to 'lupo_' (the repo default).
    """
    config_path = _repo_root() / "lupopedia-config.php"
    if not config_path.is_file():
        return "lupo_"

    content = config_path.read_text(encoding="utf-8", errors="replace")

    m = re.search(
        r"define\s*\(\s*['\"]LUPO_TABLE_PREFIX['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)",
        content,
        flags=re.IGNORECASE,
    )
    if m:
        return m.group(1).strip()

    m = re.search(
        r"table_prefix\s*=\s*['\"]([^'\"]+)['\"]\s*;",
        content,
        flags=re.IGNORECASE,
    )
    if m:
        return m.group(1).strip()

    return "lupo_"


def _now_ymdhis() -> int:
    return int(datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S"))


def _signed_bigint_fit(value: int) -> int:
    """
    Map a computed value deterministically into signed BIGINT range.
    """
    max_bigint = 9223372036854775807  # 2^63-1
    if value <= max_bigint:
        return value
    return value % (max_bigint + 1)


def _slugify_content_path(file_path_from_root: str) -> str:
    """
    Deterministic slug from file_path_from_root (repo-relative path).
    """
    p = file_path_from_root.replace("\\", "/")
    if p.endswith(".md"):
        p = p[: -len(".md")]
    parts = [x for x in p.split("/") if x]
    joined = "-".join(parts)
    joined = joined.lower()
    joined = re.sub(r"[^a-z0-9\-]+", "", joined)
    joined = re.sub(r"\-+", "-", joined).strip("-")
    return joined or "content"


def _title_from_file_path(file_path_from_root: str) -> str:
    slug = _slugify_content_path(file_path_from_root)
    # Rehydrate into a readable title deterministically.
    title = slug.replace("-", " ").strip()
    title = title[:1].upper() + title[1:] if title else "Untitled"
    return title


def _parse_markdown_front_matter(text: str) -> Tuple[Dict[str, Any], str, str]:
    """
    Returns: (yaml_data, yaml_text_preserved, body_content)

    - Assumes the file starts with a line exactly '---' (ignoring surrounding whitespace).
    - Uses the first two standalone '---' lines as YAML delimiters.
    """
    lines = text.splitlines(keepends=True)
    if not lines:
        raise ValueError("File is empty")

    if lines[0].strip() != "---":
        raise ValueError("Missing opening '---' YAML delimiter at the start of file")

    close_idx = None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            close_idx = i
            break

    if close_idx is None:
        raise ValueError("Missing closing '---' YAML delimiter")
    if close_idx <= 1:
        yaml_text = ""
        body_text = "".join(lines[close_idx + 1 :])
    else:
        yaml_text = "".join(lines[1:close_idx])
        body_text = "".join(lines[close_idx + 1 :])

    yaml_data = yaml.safe_load(yaml_text)
    if yaml_data is None:
        yaml_data = {}
    if not isinstance(yaml_data, dict):
        raise ValueError("YAML front matter must parse as a mapping")

    return yaml_data, yaml_text, body_text


def _extract_lupopedia_headers_block(yaml_data: Dict[str, Any]) -> Dict[str, Any]:
    """
    Support both dotted-key and nested forms:
      - lupopedia.headers: { ... }
      - lupopedia: { headers: { ... } }
    """
    if "lupopedia.headers" in yaml_data:
        hdrs = yaml_data.get("lupopedia.headers")
        if not isinstance(hdrs, dict):
            raise ValueError("lupopedia.headers must be a mapping")
        return hdrs

    if "lupopedia" in yaml_data and isinstance(yaml_data["lupopedia"], dict):
        inner = yaml_data["lupopedia"]
        if "headers" in inner:
            hdrs = inner.get("headers")
            if not isinstance(hdrs, dict):
                raise ValueError("lupopedia.headers (nested) must be a mapping")
            return hdrs

    raise ValueError("lupopedia.headers block missing")


def _set_content_id_in_yaml(yaml_data: Dict[str, Any], content_id: int) -> None:
    """
    Writes lupopedia.headers.content_id into in-memory YAML dict without changing body.
    """
    if "lupopedia.headers" in yaml_data:
        hdrs = yaml_data["lupopedia.headers"]
        if not isinstance(hdrs, dict):
            raise ValueError("lupopedia.headers must be a mapping")
        hdrs["content_id"] = content_id
        return

    if "lupopedia" in yaml_data and isinstance(yaml_data["lupopedia"], dict):
        inner = yaml_data["lupopedia"]
        if "headers" in inner and isinstance(inner["headers"], dict):
            inner["headers"]["content_id"] = content_id
            return

    raise ValueError("lupopedia.headers block missing")


def _extract_required_header_fields(headers: Dict[str, Any]) -> Dict[str, Any]:
    file_path_from_root = headers.get("file_path_from_root")
    when_updated = headers.get("when_updated")

    if not file_path_from_root:
        raise ValueError("lupopedia.headers.file_path_from_root missing")
    if not when_updated:
        raise ValueError("lupopedia.headers.when_updated missing")

    out = {
        "file_path_from_root": str(file_path_from_root),
        "when_updated": str(when_updated),
    }

    if "title" in headers and headers.get("title") is not None:
        out["title"] = str(headers.get("title"))

    if "channel_id" in headers and headers.get("channel_id") is not None:
        try:
            out["channel_id"] = int(headers.get("channel_id"))
        except (TypeError, ValueError):
            out["channel_id"] = None

    if "actor_id" in headers and headers.get("actor_id") is not None:
        try:
            out["actor_id"] = int(headers.get("actor_id"))
        except (TypeError, ValueError):
            out["actor_id"] = None

    return out


def _compute_deterministic_content_id(file_path_from_root: str, body_content: str) -> int:
    hash_input = file_path_from_root + "\n" + body_content
    digest = hashlib.sha256(hash_input.encode("utf-8")).hexdigest()
    raw = int(digest[:16], 16)
    return _signed_bigint_fit(raw)


def _load_lupo_contents_column_order() -> List[str]:
    """
    Use the repo's TOON schema reference as a stable list of columns.
    """
    toon_path = _repo_root() / "lupo-database" / "lupopedia" / "json" / "lupo_contents.json"
    if not toon_path.is_file():
        raise RuntimeError(f"Missing TOON schema reference: {toon_path}")

    data = __import__("json").loads(toon_path.read_text(encoding="utf-8", errors="replace"))
    fields = data.get("fields") or []

    cols = []
    for f in fields:
        m = re.search(r"`([^`]+)`", f)
        if not m:
            raise RuntimeError(f"Unexpected field entry (no column name): {f}")
        cols.append(m.group(1))

    return cols


def _safe_sql_identifier(ident: str) -> str:
    """
    Avoid vendor-specific quoting; allow only simple SQL identifiers.
    """
    if not re.fullmatch(r"[A-Za-z_][A-Za-z0-9_]*", ident):
        raise RuntimeError(f"Unsafe SQL identifier: {ident!r}")
    return ident


def _build_insert_sql_and_params(
    table_name: str,
    column_order: List[str],
    values_by_column: Dict[str, Any],
) -> Tuple[str, Tuple[Any, ...]]:
    table_name = _safe_sql_identifier(table_name)
    cols_sql = ", ".join(_safe_sql_identifier(c) for c in column_order)
    placeholders = ", ".join(["%s"] * len(column_order))
    sql = f"INSERT INTO {table_name} ({cols_sql}) VALUES ({placeholders})"
    params = tuple(values_by_column[col] for col in column_order)
    return sql, params


def _build_update_sql_and_params(
    table_name: str,
    update_columns: List[str],
    pk_column: str,
    values_by_column: Dict[str, Any],
) -> Tuple[str, Tuple[Any, ...]]:
    table_name = _safe_sql_identifier(table_name)
    pk_column = _safe_sql_identifier(pk_column)
    update_columns = [_safe_sql_identifier(c) for c in update_columns]

    set_clause = ", ".join([f"{c}=%s" for c in update_columns])
    sql = f"UPDATE {table_name} SET {set_clause} WHERE {pk_column}=%s"
    params = tuple(values_by_column[c] for c in update_columns) + (values_by_column[pk_column],)
    return sql, params


def _build_values_for_lupo_contents(
    headers: Dict[str, Any],
    body_content: str,
    content_id: int,
) -> Dict[str, Any]:
    """
    Build explicit values for *all* lupo_contents columns.
    """
    now = _now_ymdhis()

    file_path_from_root = headers["file_path_from_root"]
    when_updated = headers["when_updated"]
    title = headers.get("title") or _title_from_file_path(file_path_from_root)
    slug = _slugify_content_path(file_path_from_root)

    # Only is_deleted has an explicit rule in the prompt; everything else must still be explicit.
    # Values below are deterministic and schema-aligned.
    values = {
        "content_id": int(content_id),
        "content_parent_id": None,
        "federation_node_id": 1,
        "federation_source_url": None,
        "channel_id": headers.get("channel_id"),
        "department_id": None,
        "actor_id": headers.get("actor_id"),
        "title": title,
        "slug": slug,
        "custom_path": None,
        "description": None,
        "seo_keywords": None,
        "body": body_content,
        "content": body_content,
        "content_type": "article",
        "format": "markdown",
        "content_url": None,
        "default_collection_id": None,
        "source_url": None,
        "source_title": None,
        "is_template": 0,
        "status": "draft",
        "visibility": "public",
        "view_count": 0,
        "created_ymdhis": now,
        "utc_cycle": "creative",
        "triage_status": "untriaged",
        "triage_notes": None,
        "updated_ymdhis": now,
        "is_deleted": 0,
        "is_active": 1,
        "deleted_ymdhis": None,
        "content_sections": None,
        "version_number": 1,
        "file_path_from_root": file_path_from_root,
        "file_last_modified_system_version": when_updated,
        "file_last_modified_utc": _parse_last_modified_utc(headers),
        "tags": None,
        "dialog_notes": None,
        "atom_mappings": None,
        "category_mappings": None,
        "content_events": None,
        "hashtags": None,
        "inbound_links": None,
        "like_users": None,
        "media_attachments": None,
        "question_mappings": None,
        "content_references": None,
        "revision_history": None,
        "share_users": None,
        "tag_relationships": None,
        "like_count": 0,
        "share_count": 0,
        "comment_count": 0,
    }
    return values


def _parse_last_modified_utc(headers: Dict[str, Any]) -> int:
    """
    Use lupopedia.headers.last_modified_utc when provided.

    Governance:
    - If `last_modified_utc` is missing or explicitly null => default to 'now'.
    - If `last_modified_utc` is present but invalid => fail loudly (do NOT silently coerce to 'now').
    """
    if "last_modified_utc" not in headers or headers.get("last_modified_utc") is None:
        return _now_ymdhis()

    last = headers.get("last_modified_utc")
    if isinstance(last, int):
        as_str = str(last)
        if re.fullmatch(r"\d{14}", as_str):
            return last
        raise ValueError(
            "lupopedia.headers.last_modified_utc must be a 14-digit YYYYMMDDHHIISS integer "
            f"(got {as_str!r})"
        )

    if isinstance(last, str):
        candidate = last.strip()
        if re.fullmatch(r"\d{14}", candidate):
            return int(candidate)
        raise ValueError(
            "lupopedia.headers.last_modified_utc must be a 14-digit YYYYMMDDHHIISS string "
            f"(got {candidate!r})"
        )

    raise ValueError(
        "lupopedia.headers.last_modified_utc must be an int or 14-digit string "
        f"(got type {type(last).__name__})"
    )


def _update_lupopedia_headers_content_id_in_yaml_text(yaml_text: str, content_id: int) -> str:
    """
    Update only `lupopedia.headers.content_id` inside the *original YAML text*.

    This preserves unrelated formatting as much as possible.
    """
    yaml_lines = yaml_text.splitlines(keepends=True)

    def find_block_start_dotted() -> int:
        for idx, line in enumerate(yaml_lines):
            if re.fullmatch(r"lupopedia\.headers\s*:\s*", line.strip()):
                return idx
        return -1

    def find_block_start_nested() -> Tuple[int, int]:
        """
        Returns (lupopedia_idx, headers_idx) or (-1, -1).
        """
        lupopedia_idx = -1
        for idx, line in enumerate(yaml_lines):
            if re.fullmatch(r"lupopedia\s*:\s*", line.strip()):
                lupopedia_idx = idx
                break
        if lupopedia_idx < 0:
            return -1, -1

        # Find nested `headers:` beneath `lupopedia:`.
        base_indent = len(yaml_lines[lupopedia_idx]) - len(yaml_lines[lupopedia_idx].lstrip(" "))
        for j in range(lupopedia_idx + 1, len(yaml_lines)):
            line = yaml_lines[j]
            indent = len(line) - len(line.lstrip(" "))
            if indent <= base_indent and line.strip() and not line.strip().startswith("#"):
                break
            if re.fullmatch(r"headers\s*:\s*", line.strip()):
                return lupopedia_idx, j
        return lupopedia_idx, -1

    def update_with_block(start_idx: int) -> str:
        base_indent = len(yaml_lines[start_idx]) - len(yaml_lines[start_idx].lstrip(" "))

        # Determine the end of the block by indentation dropping back to top-level.
        end_idx = len(yaml_lines)
        for j in range(start_idx + 1, len(yaml_lines)):
            line = yaml_lines[j]
            indent = len(line) - len(line.lstrip(" "))
            stripped = line.strip()
            if indent <= base_indent and stripped and not stripped.startswith("#"):
                end_idx = j
                break

        # Find existing content_id line.
        content_id_line_re = re.compile(r"^(\s*)content_id\s*:\s*.*$")
        content_id_line_idx = None
        for j in range(start_idx + 1, end_idx):
            m = content_id_line_re.match(yaml_lines[j])
            if m:
                content_id_line_idx = j
                break

        new_line = None
        if content_id_line_idx is not None:
            indent = re.match(r"^(\s*)", yaml_lines[content_id_line_idx]).group(1)  # type: ignore[union-attr]
            line_ending = "\n" if yaml_lines[content_id_line_idx].endswith("\n") else ""
            # Always write content_id as an integer (no quotes).
            new_line = f"{indent}content_id: {int(content_id)}{line_ending}"
            yaml_lines[content_id_line_idx] = new_line
            return "".join(yaml_lines)

        # Otherwise insert after file_path_from_root if present, else after when_updated.
        insert_after_idx = None
        for j in range(start_idx + 1, end_idx):
            if yaml_lines[j].strip().startswith("file_path_from_root:"):
                insert_after_idx = j
                break
        if insert_after_idx is None:
            for j in range(start_idx + 1, end_idx):
                if yaml_lines[j].strip().startswith("when_updated:"):
                    insert_after_idx = j
                    break
        if insert_after_idx is None:
            # Fallback: insert at end of block (before end_idx).
            insert_after_idx = end_idx - 1

        # Determine indentation for child properties (usually base_indent + 2).
        child_indent = None
        for j in range(start_idx + 1, end_idx):
            line = yaml_lines[j]
            if line.strip().startswith("file_path_from_root:"):
                child_indent = re.match(r"^(\s*)", line).group(1)  # type: ignore[union-attr]
                break
        if child_indent is None:
            child_indent = " " * (base_indent + 2)

        line_ending = "\n" if yaml_lines[insert_after_idx].endswith("\n") else ""
        yaml_lines.insert(insert_after_idx + 1, f"{child_indent}content_id: {int(content_id)}{line_ending}")
        return "".join(yaml_lines)

    start = find_block_start_dotted()
    if start >= 0:
        return update_with_block(start)

    lup_idx, hdr_idx = find_block_start_nested()
    if lup_idx >= 0 and hdr_idx >= 0:
        return update_with_block(hdr_idx)

    raise ValueError("Could not locate lupopedia.headers block in YAML front matter text")


def main() -> int:
    parser = argparse.ArgumentParser(description="Import a LUPOPEDIA markdown file into lupo_contents.")
    parser.add_argument("path", help="Path to LUPOPEDIA Markdown file")
    parser.add_argument("--dry-run", action="store_true", help="Compute content_id and validate, but do not write DB or disk")
    args = parser.parse_args()

    md_path = Path(args.path)
    if not md_path.is_file():
        print(f"ERROR: File not found: {md_path}", file=sys.stderr)
        return 2

    original_text = md_path.read_text(encoding="utf-8", errors="replace")
    newline = "\r\n" if "\r\n" in original_text else "\n"

    try:
        yaml_data, yaml_text, body_content = _parse_markdown_front_matter(original_text)
        headers = _extract_lupopedia_headers_block(yaml_data)
        validation = validate_header(headers)
        if not validation.get("valid"):
            raise ValueError(__import__("json").dumps(validation))
        header_fields = _extract_required_header_fields(headers)
        # Include last_modified_utc in header_fields if present (for file_last_modified_utc).
        if headers.get("last_modified_utc") is not None:
            header_fields["last_modified_utc"] = headers.get("last_modified_utc")
        content_id = _compute_deterministic_content_id(header_fields["file_path_from_root"], body_content)

        # Optional improvement: log mismatch between stored and deterministic content_id.
        if "content_id" in headers and headers.get("content_id") is not None:
            existing = headers.get("content_id")
            existing_int = None
            if isinstance(existing, int):
                existing_int = existing
            elif isinstance(existing, str):
                s = existing.strip()
                if re.fullmatch(r"\d+", s):
                    existing_int = int(s)
            if existing_int is not None and existing_int != int(content_id):
                print(
                    f"WARNING: lupopedia.headers.content_id is {existing_int}, "
                    f"but deterministic recompute is {int(content_id)}; overwriting.",
                    file=sys.stderr,
                )
    except Exception as e:
        print(f"ERROR: {e}", file=sys.stderr)
        return 3

    if args.dry_run:
        print(f"Imported (dry-run): {md_path}")
        print(f"content_id: {content_id}")
        return 0

    # DB upsert
    table_prefix = _load_table_prefix_from_config()
    table_name = f"{table_prefix}contents"

    column_order = _load_lupo_contents_column_order()
    values = _build_values_for_lupo_contents(header_fields, body_content, content_id)

    # Ensure we set every column explicitly.
    missing_cols = [c for c in column_order if c not in values]
    if missing_cols:
        raise RuntimeError(f"Internal error: missing explicit values for columns: {', '.join(missing_cols)}")

    conn_params = _load_connection_params()
    conn = pymysql.connect(
        host=conn_params["host"],
        user=conn_params["user"],
        password=conn_params["password"],
        database=conn_params["database"],
        port=int(conn_params["port"]),
        charset=conn_params.get("charset") or "utf8mb4",
        cursorclass=pymysql.cursors.Cursor,
        autocommit=False,
    )

    try:
        pk_column = "content_id"
        # Application-layer upsert:
        # 1) SELECT by content_id
        # 2) UPDATE if exists
        # 3) INSERT if not
        with conn.cursor() as cursor:
            select_sql = f"SELECT {pk_column} FROM {_safe_sql_identifier(table_name)} WHERE {pk_column}=%s"
            cursor.execute(select_sql, (int(content_id),))
            exists = cursor.fetchone() is not None

            if exists:
                # Update all deterministic file-derived columns that may change,
                # not only body/content.
                update_columns = [
                    "body",
                    "content",
                    "updated_ymdhis",
                    "title",
                    "slug",
                    "file_path_from_root",
                    "file_last_modified_system_version",
                    "file_last_modified_utc",
                    "channel_id",
                    "actor_id",
                ]
                update_columns = [c for c in update_columns if c in column_order]
                if not update_columns:
                    raise RuntimeError("Internal error: no update columns available for deterministic file-derived update")
                missing_update_cols = [c for c in update_columns if c not in values]
                if missing_update_cols:
                    raise RuntimeError(f"Internal error: missing values for update columns: {', '.join(missing_update_cols)}")
                update_sql, update_params = _build_update_sql_and_params(
                    table_name=table_name,
                    update_columns=update_columns,
                    pk_column=pk_column,
                    values_by_column=values,
                )
                cursor.execute(update_sql, update_params)
            else:
                insert_sql, params = _build_insert_sql_and_params(table_name, column_order, values)
                cursor.execute(insert_sql, params)

        conn.commit()
    except Exception as e:
        try:
            conn.rollback()
        except Exception:
            pass
        print(f"ERROR: DB failure: {e}", file=sys.stderr)
        try:
            conn.close()
        except Exception:
            pass
        return 4
    finally:
        try:
            conn.close()
        except Exception:
            pass

    # Update YAML and write file back to disk.
    _set_content_id_in_yaml(yaml_data, int(content_id))  # keep in-memory consistent
    updated_yaml_text = _update_lupopedia_headers_content_id_in_yaml_text(yaml_text, int(content_id))
    if not updated_yaml_text.endswith("\n") and not updated_yaml_text.endswith("\r\n"):
        updated_yaml_text += newline
    updated_text = "---" + newline + updated_yaml_text + "---" + newline + body_content
    try:
        md_path.write_text(updated_text, encoding="utf-8", errors="replace")
    except Exception as e:
        print(f"ERROR: file rewrite failed after DB commit: {e}", file=sys.stderr)
        return 5

    print(f"Imported: {md_path}")
    print(f"content_id: {content_id}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
