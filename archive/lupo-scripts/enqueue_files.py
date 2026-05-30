# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-scripts/enqueue_files.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/enqueue_files.py"
#   status: "in_progress"
#   when_updated: "20260416185134"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/staging/2026/04/enqueue_files.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/enqueue-files"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "00"
#   content_slug: "enqueue-files"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Lupopedia enqueue_files tooling script"
#   summary: "Scan the repo and enqueue in-scope files into lupo_anubis_queue for ingestion."
# ---------------------------------------------------------------------
"""
Canonical Lupopedia file ingestion script.

- Walks the entire Lupopedia repository (including lupo-scripts/)
- Respects .gitignore located one directory above this script
- Enqueues .md, .php, .py, .json, .toon files into {prefix}anubis_queue
- Uses canonical DB config via lupo-scripts/db_config.py
- Stores file_path as repo-relative POSIX paths (forward slashes); ANUBIS PHP resolves via LUPOPEDIA_PATH
- Sets status="pending" and detection_method="filesystem_scan" (matches ANUBIS_QueueProcessor)
- Snapshots file content and computes SHA-256 hashes

Requires: pathspec, pymysql
"""

import os
import time
import hashlib
from pathlib import Path

import pymysql
from pathspec import PathSpec

from db_config import get_connection_params, get_table_prefix


SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
GITIGNORE_PATH = REPO_ROOT / ".gitignore"

TARGET_EXTENSIONS = {".md", ".php", ".py", ".json", ".toon"}
BATCH_SIZE = 100


def now_ymdhis_utc() -> int:
    """Return BIGINT UTC YYYYMMDDHHIISS."""
    return int(time.strftime("%Y%m%d%H%M%S", time.gmtime()))


def load_gitignore_spec() -> PathSpec:
    if GITIGNORE_PATH.exists():
        patterns = GITIGNORE_PATH.read_text(encoding="utf-8", errors="ignore").splitlines()
        return PathSpec.from_lines("gitwildmatch", patterns)
    return PathSpec.from_lines("gitwildmatch", [])


def is_ignored(path: Path, spec: PathSpec) -> bool:
    rel = path.relative_to(REPO_ROOT)
    return spec.match_file(rel.as_posix())


def sha256_file(path: Path) -> str:
    h = hashlib.sha256()
    with path.open("rb") as f:
        for chunk in iter(lambda: f.read(8192), b""):
            h.update(chunk)
    return h.hexdigest()


def main() -> None:
    spec = load_gitignore_spec()

    params = get_connection_params()
    db = pymysql.connect(**params, charset="utf8mb4", autocommit=False)
    cursor = db.cursor()

    table_name = f"{get_table_prefix()}anubis_queue"
    insert_sql = f"""
        INSERT INTO {table_name}
        (file_path, file_hash, file_content, detected_utc, priority, status,
         detection_method, header_snapshot, attempts, filesystem_copy_exists,
         created_utc, updated_utc)
        VALUES (%s, %s, %s, %s, %s, %s,
                %s, %s, %s, %s,
                %s, %s)
    """

    now = now_ymdhis_utc()
    pending = 0

    try:
        for root, dirs, files in os.walk(REPO_ROOT):
            root_path = Path(root)
            for filename in files:
                ext = Path(filename).suffix.lower()
                if ext not in TARGET_EXTENSIONS:
                    continue

                full_path = root_path / filename
                if is_ignored(full_path, spec):
                    continue

                rel_path = full_path.relative_to(REPO_ROOT).as_posix()

                try:
                    content = full_path.read_text(encoding="utf-8", errors="ignore")
                except Exception:
                    print(f"Skipping unreadable file: {rel_path}")
                    continue

                file_hash = sha256_file(full_path)

                data = (
                    rel_path,          # file_path (repo-relative; not public URL folder name)
                    file_hash,         # file_hash
                    content,           # file_content
                    now,               # detected_utc
                    5,                 # priority
                    "pending",         # status (QueueProcessor.processQueue)
                    "filesystem_scan", # detection_method
                    None,              # header_snapshot
                    0,                 # attempts
                    1 if full_path.is_file() else 0,  # filesystem_copy_exists
                    now,               # created_utc
                    now,               # updated_utc
                )

                try:
                    cursor.execute(insert_sql, data)
                    pending += 1
                    print(f"Queued: {rel_path}")
                except pymysql.err.IntegrityError as err:
                    # 1062 = duplicate key (e.g. unique file_hash)
                    if err.args and err.args[0] == 1062:
                        print(f"Skipping existing hash for: {rel_path}")
                        continue
                    print(f"DB integrity error for {rel_path}: {err}")
                except Exception as err:
                    print(f"DB error for {rel_path}: {err}")

                if pending >= BATCH_SIZE:
                    db.commit()
                    pending = 0

        if pending:
            db.commit()
    finally:
        cursor.close()
        db.close()

    print("Done.")


if __name__ == "__main__":
    main()
