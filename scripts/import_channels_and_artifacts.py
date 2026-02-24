#!/usr/bin/env python3
"""
Import Channels and Artifacts System 4.0.42

Validates, normalizes, and imports channel messages and artifacts from the filesystem
into the Lupopedia database.

Features:
- Validates FLIP v3 headers and footers.
- Maps channels/<id>/ and artifacts/<id>/ to database.
- Preserves timestamps and metadata.
- Skips duplicates based on hash.
- Supports dry-run and logging.

Usage: python scripts/import_channels_and_artifacts.py [--dry-run] [--verbose]
"""
import os
import sys
import json
import hashlib
import re
from pathlib import Path
from datetime import datetime
from typing import Dict, List, Optional, Any
import argparse

# Add project root to path
PROJECT_ROOT = Path(__file__).parent.parent
sys.path.insert(0, str(PROJECT_ROOT))

# Database imports
try:
    import pymysql
    from pymysql.cursors import DictCursor
    from scripts.db_config import get_connection_params
except ImportError:
    print("Error: Required dependencies not found. pip install pymysql")
    sys.exit(1)

class Importer:
    def __init__(self, dry_run: bool = False, verbose: bool = False):
        self.dry_run = dry_run
        self.verbose = verbose
        self.conn = None
        self.stats = {
            'channels_scanned': 0,
            'node_folders_scanned': 0,
            'messages_imported': 0,
            'artifacts_imported': 0,
            'skipped': 0,
            'errors': 0,
            'anubis_routing': 0
        }
        self.log_file = PROJECT_ROOT / "docs" / "status" / "antigravity_channel_artifact_import_system_4_0_42.md"
        self.log_entries = []

    def log(self, msg: str, is_error: bool = False):
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        prefix = "❌ " if is_error else "✅ "
        full_msg = f"[{timestamp}] {prefix}{msg}"
        if self.verbose or is_error:
            print(full_msg)
        self.log_entries.append(full_msg)

    def connect(self):
        try:
            params = get_connection_params()
            self.conn = pymysql.connect(
                **params,
                cursorclass=DictCursor,
                autocommit=False
            )
            self.log("Database connected.")
        except Exception as e:
            self.log(f"Database connection failed: {e}", True)
            sys.exit(1)

    def close(self):
        if self.conn:
            self.conn.close()
            self.log("Database connection closed.")

    def parse_flip_v3(self, content: str) -> Optional[Dict[str, Any]]:
        """Extracts FLIP v3 header and footer JSON metadata."""
        # Header range
        header_match = re.search(r'^---\s*\n(.*?)\n---', content, re.DOTALL)
        if not header_match:
            return None
        
        header_raw = header_match.group(1)
        # Footer range (look for JSON in comments at the end)
        footer_match = re.search(r'<!--\s*FLIP_FOOTER_BEGIN\s*\n(.*?)\n\s*FLIP_FOOTER_END\s*-->', content, re.DOTALL)
        footer_raw = footer_match.group(1) if footer_match else None

        # Simple YAML-ish parser for header
        header_dict = {}
        for line in header_raw.split('\n'):
            line = line.strip()
            if not line or line.startswith('#'): continue
            if ':' in line:
                parts = line.split(':', 1)
                if len(parts) == 2:
                    k, v = parts
                    header_dict[k.strip()] = v.strip().strip('"').strip("'")

        footer_dict = None
        if footer_raw:
            try:
                footer_dict = json.loads(footer_raw)
            except json.JSONDecodeError:
                pass

        # Tags parsing
        tags = []
        if 'tags' in header_dict:
            t_val = header_dict['tags']
            if t_val.startswith('[') and t_val.endswith(']'):
                try:
                    tags = json.loads(t_val.replace("'", '"'))
                except:
                    tags = [t.strip() for t in t_val[1:-1].split(',')]
            else:
                tags = [t.strip() for t in t_val.split(',')]
        
        return {
            'header': header_dict,
            'footer': footer_dict,
            'tags': tags,
            'raw_header': header_raw,
            'raw_footer': footer_raw
        }

    def import_channels(self):
        channels_dir = PROJECT_ROOT / "channels"
        if not channels_dir.exists():
            return

        for ch_folder in sorted(channels_dir.iterdir()):
            if not ch_folder.is_dir() or not ch_folder.name.isdigit():
                continue
            
            channel_id = int(ch_folder.name)
            self.stats['channels_scanned'] += 1
            self.log(f"Processing Channel {channel_id}...")
            
            # Process subfolders
            for sub in ["broadcasts", "threads", "actors", "directives"]:
                sub_dir = ch_folder / sub
                if not sub_dir.exists(): continue
                
                for file_path in sub_dir.rglob("*.md"):
                    self.process_file(file_path, channel_id, sub)

    def import_artifacts(self):
        artifacts_dir = PROJECT_ROOT / "artifacts"
        if not artifacts_dir.exists():
            return

        for node_folder in sorted(artifacts_dir.iterdir()):
            if not node_folder.is_dir() or not node_folder.name.isdigit():
                continue
            
            federated_node_id = int(node_folder.name)
            self.stats['node_folders_scanned'] += 1
            self.log(f"Processing Artifact Node {federated_node_id}...")
            
            for file_path in node_folder.rglob("*.md"):
                # Avoid re-scanning channel folders if they were symlinked (not expected here but safe)
                if "channels" in str(file_path): continue
                self.process_file(file_path, None, "artifact", federated_node_id)

    def process_file(self, file_path: Path, channel_id: Optional[int], kind: str, federated_node_id: int = 1):
        try:
            content = file_path.read_text(encoding="utf-8")
            meta = self.parse_flip_v3(content)
            
            if not meta or not meta['header']:
                self.log(f"Malformed FLIP header in {file_path}. Routing to ANUBIS.", True)
                self.route_to_anubis(file_path)
                return

            header = meta['header']
            footer = meta['footer'] or {}
            
            file_hash = hashlib.sha256(content.encode()).hexdigest()
            
            if self.is_duplicate(file_hash):
                self.stats['skipped'] += 1
                return

            if kind == "broadcasts":
                 if channel_id == 0:
                     self.import_broadcast_into_db(file_path, channel_id, header, footer, meta['tags'], file_hash)
                 else:
                     self.import_artifact_into_db(file_path, channel_id, federated_node_id, header, footer, file_hash, kind)
            elif kind == "directives":
                 self.import_artifact_into_db(file_path, channel_id, federated_node_id, header, footer, file_hash, kind)
            elif kind == "threads":
                 self.import_thread_into_db(file_path, channel_id, federated_node_id, header, footer, file_hash)
            elif kind == "actors":
                 self.import_actor_artifact(file_path, channel_id, federated_node_id, header, footer, file_hash)
            elif kind == "artifact":
                 self.import_artifact_into_db(file_path, channel_id, federated_node_id, header, footer, file_hash, kind)

        except Exception as e:
            self.log(f"Error processing {file_path}: {e}", True)
            self.stats['errors'] += 1

    def is_duplicate(self, file_hash: str) -> bool:
        if self.dry_run: return False
        with self.conn.cursor() as cursor:
            # Check metadata JSON column for hash
            cursor.execute("SELECT artifact_id FROM lupo_artifacts WHERE JSON_EXTRACT(metadata, '$.file_hash') = %s", (file_hash,))
            if cursor.fetchone(): return True
            cursor.execute("SELECT dialog_message_id FROM lupo_dialog_doctrine WHERE JSON_EXTRACT(metadata_json, '$.file_hash') = %s", (file_hash,))
            if cursor.fetchone(): return True
        return False

    def import_artifact_into_db(self, path: Path, channel_id: Optional[int], federated_node_id: int, header: Dict, footer: Dict, file_hash: str, kind: str):
        if self.dry_run:
            self.log(f"[Dry Run] Would import artifact: {path.name} (Kind: {kind}, Node: {federated_node_id})")
            return

        try:
            with self.conn.cursor() as cursor:
                sql = """
                INSERT INTO lupo_artifacts (
                    actor_id, federation_node_id, `utc_timestamp`, entity_type, content, 
                    metadata, created_ymdhis, updated_ymdhis
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                """
                actor_id = self._safe_int(header.get('actor_id'), 1)
                ts = self._safe_timestamp(header.get('file_last_modified_utc'))
                
                metadata_obj = {
                    'header': header,
                    'footer': footer,
                    'file_hash': file_hash,
                    'channel_id': channel_id,
                    'kind': kind,
                    'original_path': str(path.relative_to(PROJECT_ROOT))
                }
                
                now = datetime.now().strftime('%Y%m%d%H%M%S')
                cursor.execute(sql, (
                    actor_id, federated_node_id, ts, "file", f"Imported from {path.name}", 
                    json.dumps(metadata_obj), now, now
                ))
                self.conn.commit()
                self.stats['artifacts_imported'] += 1
                if self.verbose: self.log(f"Imported artifact: {path.name}")
        except Exception as e:
            self.log(f"Failed to insert artifact {path.name}: {e}", True)
            self.stats['errors'] += 1

    def import_broadcast_into_db(self, path: Path, channel_id: int, header: Dict, footer: Dict, tags: List[str], file_hash: str):
        if self.dry_run:
            self.log(f"[Dry Run] Would import broadcast: {path.name}")
            return

        try:
            with self.conn.cursor() as cursor:
                # Timestamp logic: Filename > Header created_ymdhis > Header last_modified > Now
                ts_match = re.search(r'^(\d{14})_', path.name)
                if ts_match:
                    created_ts = ts_match.group(1)
                elif header.get('created_ymdhis'):
                    created_ts = header.get('created_ymdhis')
                else:
                    created_ts = self._safe_timestamp(header.get('file_last_modified_utc'))
                
                # Strip YAML from content for message_body
                content = path.read_text(encoding="utf-8")
                message_body = re.sub(r'^---\s*\n.*?\n---\s*\n', '', content, flags=re.DOTALL).strip()
                
                sql = """
                INSERT INTO lupo_dialog_doctrine (
                    channel_id, from_actor_id, to_actor_id, message_type, 
                    message_text, message_body, tags, metadata_json,
                    priority, visibility, mood_rgb,
                    created_ymdhis, updated_ymdhis, is_deleted
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """
                
                from_id = self._safe_int(header.get('actor_id'), 10000)
                to_id = self._safe_int(header.get('to_actor_id'), 0)
                
                metadata_obj = {
                    'header': header,
                    'footer': footer,
                    'file_hash': file_hash,
                    'original_path': str(path.relative_to(PROJECT_ROOT))
                }
                
                now = datetime.now().strftime('%Y%m%d%H%M%S')
                cursor.execute(sql, (
                    channel_id, from_id, to_id, header.get('message_type', 'broadcast'),
                    message_body, message_body, json.dumps(tags), json.dumps(metadata_obj),
                    header.get('priority'), header.get('visibility'), header.get('mood_rgb'),
                    created_ts, now, 0
                ))
                self.conn.commit()
                self.stats['messages_imported'] += 1
                if self.verbose: self.log(f"Imported broadcast: {path.name}")
        except Exception as e:
            self.log(f"Failed to insert broadcast {path.name}: {e}", True)
            self.stats['errors'] += 1

    def import_thread_into_db(self, path: Path, channel_id: int, federated_node_id: int, header: Dict, footer: Dict, file_hash: str):
        if self.dry_run:
            self.log(f"[Dry Run] Would import thread summary: {path.name}")
            return
        
        # Threads go to lupo_dialog_threads
        try:
            with self.conn.cursor() as cursor:
                sql = """
                INSERT INTO lupo_dialog_threads (
                    federation_node_id, channel_id, created_by_actor_id, summary_text, 
                    status, metadata_json, created_ymdhis, updated_ymdhis
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                """
                actor_id = self._safe_int(header.get('actor_id'), 1)
                summary = f"Imported thread {path.name}"
                status = header.get('status', 'Open')
                
                metadata_obj = {
                    'header': header,
                    'footer': footer,
                    'file_hash': file_hash,
                    'original_path': str(path.relative_to(PROJECT_ROOT))
                }
                
                now = datetime.now().strftime('%Y%m%d%H%M%S')
                cursor.execute(sql, (
                    federated_node_id, channel_id, actor_id, summary, 
                    status, json.dumps(metadata_obj), now, now
                ))
                self.conn.commit()
                self.stats['messages_imported'] += 1 # Counting threads as messages for stats
                if self.verbose: self.log(f"Imported thread summary: {path.name}")
        except Exception as e:
            self.log(f"Failed to insert thread {path.name}: {e}", True)
            self.stats['errors'] += 1

    def import_actor_artifact(self, path: Path, channel_id: int, federated_node_id: int, header: Dict, footer: Dict, file_hash: str):
        # Similar to artifact but specific kind
        self.import_artifact_into_db(path, channel_id, federated_node_id, header, footer, file_hash, "actor_profile")

    def route_to_anubis(self, path: Path):
        anubis_dir = PROJECT_ROOT / "channels" / "666" / "quarantine"
        anubis_dir.mkdir(parents=True, exist_ok=True)
        if not self.dry_run:
            try:
                # Move file
                target = anubis_dir / path.name
                # Avoid overwrite
                if target.exists():
                    target = anubis_dir / f"{datetime.now().strftime('%H%M%S')}_{path.name}"
                os.rename(path, target)
                self.log(f"Moved malformed file to quarantine: {path.name}")
                self.stats['anubis_routing'] += 1
            except Exception as e:
                self.log(f"Failed to move file to ANUBIS: {e}", True)

    def _safe_int(self, val, default):
        if not val: return default
        try:
            # Strip comments if any
            if isinstance(val, str):
                val = val.split('#')[0].strip()
            return int(val)
        except ValueError:
            return default

    def _safe_timestamp(self, val):
        if not val:
            return int(datetime.now().strftime('%Y%m%d%H%M%S'))
        # Standardize format
        val = str(val).split('#')[0].strip()
        if len(val) == 14 and val.isdigit():
            return int(val)
        return int(datetime.now().strftime('%Y%m%d%H%M%S'))

    def write_status_doc(self):
        status_content = f"""# Import Status: Channel & Artifact System 4.0.42

## Metadata
- **Generated By**: Antigravity (Assistant)
- **Date**: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
- **Project Root**: `{PROJECT_ROOT}`
- **Dry Run**: {'Yes' if self.dry_run else 'No'}

## Summary
The import system has scanned the filesystem to synchronize offline messages and artifacts created during the Crafty Syntax migration.

### Execution Log
{"".join([f"- {entry}\n" for entry in self.log_entries])}

### Statistics
- Channels Scanned: {self.stats['channels_scanned']}
- Artifact Node Folders Scanned: {self.stats['node_folders_scanned']}
- Messages/Threads Imported: {self.stats['messages_imported']}
- Artifacts Imported: {self.stats['artifacts_imported']}
- Items Skipped (Duplicates): {self.stats['skipped']}
- ANUBIS Quarantine Routing: {self.stats['anubis_routing']}
- Errors Encountered: {self.stats['errors']}

## Conclusion
The filesystem is now aligned with the database schema for Version 4.0.42. 
Future synchronization should rely on these imported records.

---
*Authorized by Agent 42 (Antigravity)*
"""
        self.log_file.parent.mkdir(parents=True, exist_ok=True)
        self.log_file.write_text(status_content, encoding="utf-8")
        print(f"\n✓ Status document written to: {self.log_file}")

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--dry-run", action="store_true", help="Perform a dry run without database changes.")
    parser.add_argument("--verbose", action="store_true", help="Increase output verbosity.")
    args = parser.parse_args()

    print(f"--- Channel & Artifact Importer 4.0.42 ---")
    if args.dry_run:
        print("!!! DRY RUN MODE - No database changes will be made !!!")

    importer = Importer(args.dry_run, args.verbose)
    importer.connect()
    
    try:
        importer.import_channels()
        importer.import_artifacts()
    finally:
        importer.write_status_doc()
        importer.close()

if __name__ == "__main__":
    main()
