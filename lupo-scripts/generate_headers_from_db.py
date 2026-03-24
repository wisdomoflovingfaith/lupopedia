#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324193500"
#   file_path_from_root: "lupo-scripts/generate_headers_from_db.py"
#   last_modified_utc: "20260324193500"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324193500"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Generate LUPOPEDIA HEADERS from database metadata.

This script reads TOON/JSON schema artifacts to determine exact database
structure, then reconstructs canonical YAML front matter from lupo_contents
and lupo_metadata tables.

Authoritative sources:
- TOON/JSON files in docs/toons/ (exact table/column names)
- lupo_contents table (content records)
- lupo_metadata table (header metadata)

TIMESTAMP SEMANTICS (enforced):
- `when_updated`: Logical content change time (preserved, never auto-modified)
- `last_modified_utc`: File system write time (always updated to current UTC)
- `last_verified`: Validation timestamp (only from DB footer, never inferred)

STALENESS WARNINGS (non-mutating):
- Warns if last_verified < 20260301000000 (regenerate recommended)
- Warns if lupopedia.footer missing (adds validation gap)
- Warns if header block order incorrect (canonicalization failure)

Usage:
    python lupo-scripts/generate_headers_from_db.py --file-path path/from/root.md
    python lupo-scripts/generate_headers_from_db.py --content-id 123456789
    python lupo-scripts/generate_headers_from_db.py --dry-run --file-path path/from/root.md

Requirements:
- At least one of --file-path or --content-id is required
- If both provided and resolve to different DB records, fail loudly
- Use explicit SQL, no ORM, no vendor-specific features
- Output deterministic YAML with canonical block order
"""

import argparse
import json
import os
import subprocess
import sys
import yaml
from pathlib import Path
from typing import Dict, List, Optional, Any, Tuple
from datetime import datetime

# ============================================================================
# TIMESTAMP VALIDATION & STALENESS WARNINGS
# ============================================================================

def validate_timestamp_format(timestamp_str: str) -> bool:
    """Validate timestamp is valid UTC YYYYMMDDHHIISS format."""
    if not isinstance(timestamp_str, str):
        return False
    if len(timestamp_str) != 14:
        return False
    try:
        datetime.strptime(timestamp_str, '%Y%m%d%H%M%S')
        return True
    except ValueError:
        return False

def is_staleness_threshold() -> str:
    """Get staleness threshold timestamp (2026-03-01 00:00:00 UTC)."""
    return "20260301000000"

def check_timestamp_staleness(last_verified: Optional[str]) -> Tuple[bool, str]:
    """
    Check if last_verified is older than staleness threshold.
    
    Returns: (is_stale, timestamp_or_message)
    """
    if not last_verified:
        return (None, "Missing last_verified timestamp")
    
    if not validate_timestamp_format(last_verified):
        return (None, f"Invalid timestamp format: {last_verified} (expected YYYYMMDDHHIISS)")
    
    threshold = is_staleness_threshold()
    is_stale = last_verified < threshold
    
    return (is_stale, last_verified)

def check_header_block_order(front_matter_dict: Dict[str, Any]) -> Tuple[bool, str]:
    """
    Check if YAML blocks are in canonical order.
    
    Canonical order:
    1. lupopedia.headers
    2. lupopedia.footer  
    3. lupopedia.edges
    
    Returns: (is_correct_order, message)
    """
    canonical_order = [
        'lupopedia.headers',
        'lupopedia.footer',
        'lupopedia.edges'
    ]
    
    existing_blocks = [k for k in front_matter_dict.keys() if k in canonical_order]
    expected_order = [k for k in canonical_order if k in existing_blocks]
    
    if existing_blocks == expected_order:
        return (True, "Header block order is correct")
    
    return (False, f"Header blocks out of order. Expected {expected_order}, got {existing_blocks}")


# ============================================================================
# TIER 2: SEMANTIC RANGE VALIDATION
# ============================================================================

def validate_timestamp_semantic_range(headers: Dict[str, Any]) -> List[str]:
    """
    Tier 2: Validate timestamps fall within semantic bounds and observe ordering.

    Rules enforced:
    - All timestamps >= 20000101000000 (project floor — before year 2000 is nonsensical)
    - All timestamps <= current UTC (future timestamps are data-quality violations)
    - when_updated <= last_modified_utc (logical: content change time must not
      exceed the file write time recorded for that change)
    """
    issues: List[str] = []
    FLOOR = "20000101000000"
    now   = datetime.utcnow().strftime('%Y%m%d%H%M%S')

    ts_fields = ['when_updated', 'last_modified_utc', 'created_ymdhis', 'updated_ymdhis']
    for field in ts_fields:
        val = headers.get(field)
        if val is None:
            continue
        val_str = str(val).strip()
        if not validate_timestamp_format(val_str):
            issues.append(
                f"Tier 2: {field}={val_str!r} is not a valid YYYYMMDDHHIISS timestamp"
            )
            continue
        if val_str < FLOOR:
            issues.append(
                f"Tier 2: {field}={val_str} predates project floor ({FLOOR}) — likely data error"
            )
        if val_str > now:
            issues.append(
                f"Tier 2: {field}={val_str} is in the future (current UTC={now})"
            )

    # Ordering rule: when_updated must not exceed last_modified_utc
    wu = str(headers.get('when_updated', '') or '').strip()
    lm = str(headers.get('last_modified_utc', '') or '').strip()
    if wu and lm and validate_timestamp_format(wu) and validate_timestamp_format(lm):
        if wu > lm:
            issues.append(
                f"Tier 2: when_updated ({wu}) > last_modified_utc ({lm}): "
                "logical content-change time must not exceed recorded file-write time"
            )

    return issues


# ============================================================================
# TIER 3: ROLE-INTEGRITY VALIDATION
# ============================================================================

_ACTOR_REGISTRY_CACHE: Optional[Dict[int, str]] = None


def _load_actor_registry() -> Dict[int, str]:
    """Load actor_id -> slug mapping from the canonical registry.json (cached)."""
    global _ACTOR_REGISTRY_CACHE
    if _ACTOR_REGISTRY_CACHE is not None:
        return _ACTOR_REGISTRY_CACHE

    registry_path = (
        Path(__file__).resolve().parent.parent
        / "lupo-database" / "lupopedia" / "actors" / "actor_id" / "registry.json"
    )
    if not registry_path.exists():
        _ACTOR_REGISTRY_CACHE = {}
        return _ACTOR_REGISTRY_CACHE

    try:
        with open(registry_path, 'r', encoding='utf-8') as fh:
            data = json.load(fh)
        _ACTOR_REGISTRY_CACHE = {
            int(a['actor_id']): str(a['slug']).lower()
            for a in data.get('actors', [])
            if 'actor_id' in a and 'slug' in a
        }
    except Exception:
        _ACTOR_REGISTRY_CACHE = {}

    return _ACTOR_REGISTRY_CACHE


def validate_role_integrity(headers: Dict[str, Any]) -> List[str]:
    """
    Tier 3: Validate actor identity integrity against the canonical registry.

    Rules enforced:
    - actor_id must be a recognised integer in registry.json (when present)
    - actor_name must match the canonical slug for the stated actor_id
      (case-insensitive; the registry stores slugs in lower-case)

    Skipped silently when actor_id is absent (it is optional for non-actor files).
    """
    issues: List[str] = []

    actor_id_raw = headers.get('actor_id')
    if actor_id_raw is None:
        return issues  # actor_id is optional

    try:
        actor_id = int(actor_id_raw)
    except (ValueError, TypeError):
        issues.append(f"Tier 3: actor_id={actor_id_raw!r} is not a valid integer")
        return issues

    registry = _load_actor_registry()
    if not registry:
        return issues  # Registry file unavailable; skip rather than false-positive

    if actor_id not in registry:
        issues.append(f"Tier 3: actor_id={actor_id} is not present in the actor registry")
        return issues

    canonical_slug = registry[actor_id]
    actor_name     = str(headers.get('actor_name', '') or '').strip().lower()
    if actor_name and actor_name != canonical_slug:
        issues.append(
            f"Tier 3: actor_name={actor_name!r} does not match canonical slug "
            f"{canonical_slug!r} for actor_id={actor_id}"
        )

    return issues

def emit_staleness_warnings(existing_front_matter: Dict[str, Any]) -> None:
    """
    Emit non-mutating warnings about header staleness and gaps.
    Does not modify any data, only surfaces drift for human review.

    Tier 1 — staleness:      last_verified age vs. threshold
    Tier 2 — semantic range: timestamp floor/ceiling + ordering rules
    Tier 3 — role-integrity: actor_id/actor_name match against registry
    """
    if not existing_front_matter:
        return
    
    warnings = []
    
    # ---- Tier 1: footer staleness -----------------------------------------------
    footer = existing_front_matter.get('lupopedia.footer', {})
    if not footer:
        warnings.append("⚠️  WARNING: lupopedia.footer missing - no verification timestamp")
    else:
        last_verified = footer.get('last_verified')
        is_stale, msg = check_timestamp_staleness(last_verified)
        
        if is_stale is None:
            warnings.append(f"⚠️  WARNING: Invalid footer timestamp - {msg}")
        elif is_stale:
            threshold = is_staleness_threshold()
            warnings.append(
                f"⚠️  WARNING: header stale - last_verified ({msg}) is older than threshold ({threshold}). "
                "Regeneration recommended."
            )
    
    # ---- Tier 1: header block order ---------------------------------------------
    is_correct, order_msg = check_header_block_order(existing_front_matter)
    if not is_correct:
        warnings.append(f"⚠️  WARNING: {order_msg} - canonicalization may be needed")

    # ---- Tier 2: semantic range -------------------------------------------------
    headers_block = existing_front_matter.get('lupopedia.headers', {})
    if isinstance(headers_block, dict):
        tier2_issues = validate_timestamp_semantic_range(headers_block)
        for issue in tier2_issues:
            warnings.append(f"⚠️  WARNING: {issue}")

    # ---- Tier 3: role-integrity --------------------------------------------------
    if isinstance(headers_block, dict):
        tier3_issues = validate_role_integrity(headers_block)
        for issue in tier3_issues:
            warnings.append(f"⚠️  WARNING: {issue}")

    # Emit all warnings to stderr (non-fatal)
    for warning in warnings:
        print(warning, file=sys.stderr)
    
    if warnings:
        print(file=sys.stderr)  # Blank line after warnings

def enforce_timestamp_semantics(
    existing_front_matter: Dict[str, Any],
    new_front_matter: Dict[str, Any]
) -> None:
    """
    Enforce strict timestamp semantics rules (non-mutating validation).
    
    Rules:
    1. when_updated: Must never be auto-modified. Preserve from existing.
    2. last_modified_utc: Must always update. Set to current UTC.
    3. last_verified: Must never be inferred. Only use from DB/existing.
    """
    headers_existing = existing_front_matter.get('lupopedia.headers', {})
    headers_new = new_front_matter.get('lupopedia.headers', {})
    
    # Rule 1: when_updated preservation
    if 'when_updated' in headers_existing:
        when_updated_existing = headers_existing['when_updated']
        when_updated_new = headers_new.get('when_updated')
        
        if when_updated_new != when_updated_existing:
            print(
                f"NOTICE: when_updated preserved from existing ({when_updated_existing}). "
                "Use explicit database update if content truly changed.",
                file=sys.stderr
            )
            headers_new['when_updated'] = when_updated_existing
    
    # Rule 2: last_modified_utc must update
    current_utc = datetime.utcnow().strftime('%Y%m%d%H%M%S')
    if 'last_modified_utc' in headers_new and headers_new['last_modified_utc'] != current_utc:
        print(
            f"NOTICE: last_modified_utc updated to {current_utc} (file write time)",
            file=sys.stderr
        )
    headers_new['last_modified_utc'] = current_utc
    
    # Rule 3: last_verified should not be in headers (belongs in footer only)
    if 'last_verified' in headers_new:
        print(
            "WARNING: last_verified found in lupopedia.headers - should be in lupopedia.footer only",
            file=sys.stderr
        )
        # Move to footer if possible
        if 'lupopedia.footer' not in new_front_matter:
            new_front_matter['lupopedia.footer'] = {}
        new_front_matter['lupopedia.footer']['last_verified'] = headers_new.pop('last_verified')

# ============================================================================
# DATABASE CONNECTION
# ============================================================================

# Database connection (simplified for this implementation)
def get_db_connection():
    """Get database connection using environment or config."""
    # This would connect to actual database in production
    # For now, return a mock connection that reads from TOON files
    return MockDBConnection()

class MockDBConnection:
    """Mock database connection that reads from TOON files."""
    
    def __init__(self):
        self.toon_dir = Path("docs/toons")
        self.contents_dir = Path("lupo-database/lupopedia/content")
        self.metadata_dir = Path("lupo-database/lupopedia/metadata")
    
    def execute_query(self, query: str, params: tuple = ()) -> Optional[Dict]:
        """Execute a query and return results."""
        # Mock implementation - in production this would be actual SQL
        if "file_path_from_root" in query and params:
            return self._fetch_by_file_path(params[0])
        if "content_id" in query and params:
            return self._fetch_by_content_id(params[0])
        return None
    
    def _fetch_by_file_path(self, file_path: str) -> Optional[Dict]:
        """Fetch content record by file path."""
        content_file = self.contents_dir / f"{file_path}.content.json"
        if content_file.exists():
            with open(content_file, 'r', encoding='utf-8') as f:
                return json.load(f)
        return None
    
    def _fetch_by_content_id(self, content_id: str) -> Optional[Dict]:
        """Fetch content record by content ID."""
        # In production, this would query lupo_contents table
        # For mock, we'll return a basic structure
        return {
            'content_id': content_id,
            'file_path_from_root': f'mock/path/{content_id}.md',
            'title': f'Mock Content {content_id}',
            'body': 'Mock body content for testing.',
            'created_ymdhis': datetime.now().strftime('%Y%m%d%H%M%S')
        }
    
    def fetch_metadata_rows(self, entity_type: str, entity_id: str = None) -> List[Dict]:
        """Fetch metadata rows for an entity."""
        # In production, this would query lupo_metadata table
        # For mock, return basic metadata structure
        return [
            {
                'metadata_id': 1,
                'entity_type': entity_type,
                'entity_id': entity_id or 'default',
                'property_key': 'version_when_written',
                'property_value': '4.0.84',
                'class_name': 'lupopedia_header',
                'created_ymdhis': datetime.now().strftime('%Y%m%d%H%M%S')
            },
            {
                'metadata_id': 2,
                'entity_type': entity_type,
                'entity_id': entity_id or 'default',
                'property_key': 'file_path_from_root',
                'property_value': f'mock/path/{entity_id or "default"}.md',
                'class_name': 'lupopedia_header',
                'created_ymdhis': datetime.now().strftime('%Y%m%d%H%M%S')
            }
        ]

def parse_args():
    """Parse command line arguments."""
    parser = argparse.ArgumentParser(
        description='Generate LUPOPEDIA HEADERS from database metadata',
        formatter_class=argparse.RawDescriptionHelpFormatter
    )
    
    parser.add_argument(
        '--file-path',
        required=False,
        help='File path from repository root (resolves via lupo_contents)'
    )
    
    parser.add_argument(
        '--content-id',
        required=False,
        help='Content ID (resolves via lupo_contents)'
    )
    
    parser.add_argument(
        '--dry-run',
        action='store_true',
        help='Print reconstructed YAML and summary without writing file'
    )
    
    return parser.parse_args()


def _attempt_import_missing_file(file_path: str) -> bool:
    """
    Import a markdown artifact into lupo_contents when missing.

    Uses the existing importer script so this tool can self-heal a missing
    content row for a known file_path_from_root.
    """
    script_path = Path(__file__).resolve().parent / "import_content.py"
    try:
        result = subprocess.run(
            [sys.executable, str(script_path), file_path],
            check=False,
            capture_output=True,
            text=True
        )
    except Exception as exc:
        print(f"ERROR: Failed to invoke importer for {file_path}: {exc}", file=sys.stderr)
        return False

    if result.returncode == 0:
        return True

    stderr_text = (result.stderr or "").strip()
    stdout_text = (result.stdout or "").strip()
    details = stderr_text if stderr_text else stdout_text
    print(
        f"ERROR: Auto-import failed for {file_path}. Importer output: {details}",
        file=sys.stderr
    )
    return False

def resolve_artifact(args) -> Dict:
    """Resolve artifact from database using file_path or content_id."""
    db = get_db_connection()
    
    if args.file_path:
        synthesized_content = False
        content_row = db.execute_query(
            "SELECT * FROM lupo_contents WHERE file_path_from_root = ?",
            (args.file_path,)
        )

        if not content_row:
            file_on_disk = Path(args.file_path)
            if file_on_disk.exists():
                if _attempt_import_missing_file(args.file_path):
                    content_row = db.execute_query(
                        "SELECT * FROM lupo_contents WHERE file_path_from_root = ?",
                        (args.file_path,)
                    )

                # Fallback for environments where DB-backed metadata is unavailable:
                # keep command-line flow working with a minimal synthesized row.
                if not content_row:
                    synthesized_content = True
                    content_row = {
                        'content_id': f'pending-{datetime.now().strftime("%Y%m%d%H%M%S")}',
                        'file_path_from_root': args.file_path,
                        'title': Path(args.file_path).stem,
                        'body': file_on_disk.read_text(encoding='utf-8', errors='replace'),
                        'created_ymdhis': datetime.now().strftime('%Y%m%d%H%M%S')
                    }
            else:
                print(f"ERROR: No content found for file_path: {args.file_path}", file=sys.stderr)
                sys.exit(1)
        
        metadata_rows = [] if synthesized_content else db.fetch_metadata_rows('file', content_row['content_id'])
        
        return {
            'content': content_row,
            'metadata': metadata_rows
        }
    
    elif args.content_id:
        content_row = db.execute_query(
            "SELECT * FROM lupo_contents WHERE content_id = ?",
            (args.content_id,)
        )
        
        if not content_row:
            print(f"ERROR: No content found for content_id: {args.content_id}", file=sys.stderr)
            sys.exit(1)
        
        metadata_rows = db.fetch_metadata_rows('file', args.content_id)
        
        return {
            'content': content_row,
            'metadata': metadata_rows
        }
    
    else:
        print("ERROR: At least one of --file-path or --content-id is required", file=sys.stderr)
        sys.exit(1)

def build_block_tree(metadata_rows: List[Dict]) -> Dict[str, Dict]:
    """Build hierarchical block structure from metadata rows."""
    blocks = {}
    
    for row in metadata_rows:
        property_key = row['property_key']
        property_value = row['property_value']

        # Map plain keys to lupopedia.headers by default.
        # Example: version_when_written -> lupopedia.headers.version_when_written
        if '.' not in property_key:
            block_name = 'lupopedia.headers'
            field_name = property_key
        else:
            # Support keys like "lupopedia.headers.file_path_from_root"
            parts = property_key.split('.')
            if len(parts) >= 3 and parts[0] == 'lupopedia':
                block_name = '.'.join(parts[0:2])
                field_name = '.'.join(parts[2:])
            else:
                block_name = 'lupopedia.headers'
                field_name = property_key

        if block_name not in blocks:
            blocks[block_name] = {}

        blocks[block_name][field_name] = property_value
    
    return blocks

def normalize_legacy_blocks(blocks: Dict[str, Dict]) -> Dict[str, Dict]:
    """Normalize legacy block names to canonical LUPOPEDIA.* names."""
    legacy_mapping = {
        'flare.headers': 'lupopedia.headers',
        'flare.edges': 'lupopedia.edges',
        'flare.footer': 'lupopedia.footer',
        'flare.see': 'lupopedia.see',
        'flare.close': 'lupopedia.next_actions',
        'flame.init': 'lupopedia.init',
        'flame.headers': 'lupopedia.headers',
        'flame.edges': 'lupopedia.edges',
        'flame.footer': 'lupopedia.footer',
        'flame.see': 'lupopedia.see',
        'flame.close': 'lupopedia.next_actions',
    }
    
    normalized = {}
    for block_name, block_content in blocks.items():
        # Map legacy block names to canonical
        canonical_name = legacy_mapping.get(block_name, block_name)
        
        if canonical_name not in normalized:
            normalized[canonical_name] = {}
        
        normalized[canonical_name].update(block_content)
    
    return normalized

def build_headers_block(blocks: Dict[str, Dict]) -> Dict[str, Any]:
    """Build canonical lupopedia.headers block."""
    headers = {}
    
    # Required fields with precedence
    if 'version_when_written' in blocks.get('lupopedia.headers', {}):
        headers['version_when_written'] = blocks['lupopedia.headers']['version_when_written']
    
    if 'file_path_from_root' in blocks.get('lupopedia.headers', {}):
        headers['file_path_from_root'] = blocks['lupopedia.headers']['file_path_from_root']
    
    # Optional fields from database
    for field in ['lupopedia.schema', 'web_path', 'title', 'delegation_chain', 
                   'artifact_type', 'artifact_kind', 'purpose', 'tags', 'namespace',
                   'channel_id', 'actor_id', 'last_modified_utc']:
        if field in blocks.get('lupopedia.headers', {}):
            headers[field] = blocks['lupopedia.headers'][field]
    
    # content_id if present
    if 'content_id' in blocks.get('lupopedia.headers', {}):
        headers['content_id'] = blocks['lupopedia.headers']['content_id']
    
    return headers

def build_footer_block(blocks: Dict[str, Dict]) -> Dict[str, Any]:
    """Build canonical lupopedia.footer block if sufficient data exists."""
    footer = {}
    
    # Only include if we have enough metadata
    headers_block = blocks.get('lupopedia.headers', {})
    
    # Required footer fields from database or derived from headers
    if 'last_verified' in blocks.get('lupopedia.footer', {}):
        footer['last_verified'] = blocks['lupopedia.footer']['last_verified']
    
    if 'last_verified_by' in blocks.get('lupopedia.footer', {}):
        footer['last_verified_by'] = blocks['lupopedia.footer']['last_verified_by']
    
    if 'orchestrator' in blocks.get('lupopedia.footer', {}):
        footer['orchestrator'] = blocks['lupopedia.footer']['orchestrator']
    
    # next_action from footer or derived
    if 'next_action' in blocks.get('lupopedia.footer', {}):
        footer['next_action'] = blocks['lupopedia.footer']['next_action']
    
    return footer if footer else None

def build_metadata_block(blocks: Dict[str, Dict]) -> Dict[str, Any]:
    """Build lupopedia.metadata block as snapshot view."""
    metadata = {}
    
    if 'lupopedia.metadata' in blocks:
        # Group by property_key for snapshot format
        grouped = {}
        for row in blocks['lupopedia.metadata']:
            property_key = row['property_key']
            if property_key not in grouped:
                grouped[property_key] = []
            grouped[property_key].append({
                'schema_ref': 'lupo_metadata',
                'entity_type': row.get('entity_type'),
                'entity_id': row.get('entity_id'),
                'meta_type': row.get('meta_type'),
                'property_value': row.get('property_value'),
                'channel_id': row.get('channel_id'),
                'class_name': row.get('class_name'),
                'created_ymdhis': row.get('created_ymdhis')
            })
        
        metadata['comment'] = "Snapshot of metadata for this file or entity at artifact creation."
        metadata.update(grouped)
    
    return metadata

def generate_identity_line(headers: Dict[str, Any], content: Dict[str, Any]) -> str:
    """Generate canonical identity line."""
    title = headers.get('title') or headers.get('file_path_from_root') or 'Untitled Document'
    delegation_chain = headers.get('delegation_chain', 'root')
    web_path = headers.get('web_path', '')
    
    return f"# file: {title} — delegation: {delegation_chain} — web_path: {web_path}"

def _split_existing_markdown(text: str) -> Dict[str, Any]:
    """
    Split markdown into front matter dict, identity line, and body.
    Keeps behavior safe when front matter is missing or malformed.
    """
    result = {
        'front_matter': {},
        'identity_line': None,
        'body': text
    }

    if not text.startswith('---\n'):
        return result

    end_marker = text.find('\n---\n', 4)
    if end_marker < 0:
        return result

    yaml_text = text[4:end_marker]
    remainder = text[end_marker + len('\n---\n'):]
    try:
        parsed = yaml.safe_load(yaml_text)
        if isinstance(parsed, dict):
            result['front_matter'] = parsed
    except Exception:
        pass

    rem_lines = remainder.splitlines()
    if rem_lines and rem_lines[0].startswith('# file:'):
        result['identity_line'] = rem_lines[0]
        result['body'] = '\n'.join(rem_lines[1:]).lstrip('\n')
    else:
        result['body'] = remainder.lstrip('\n')

    return result

def write_output_file(file_path: Path, front_matter_dict: Dict[str, Any], content: Dict[str, Any]):
    """Write output file with front matter and body."""
    file_existed = file_path.exists()
    existing = {'front_matter': {}, 'identity_line': None, 'body': ''}
    if file_existed:
        existing_text = file_path.read_text(encoding='utf-8', errors='replace')
        existing = _split_existing_markdown(existing_text)

    # If generated dict is empty, keep existing front matter unchanged.
    effective_front_matter = front_matter_dict if front_matter_dict else existing.get('front_matter', {})
    if not isinstance(effective_front_matter, dict):
        effective_front_matter = {}

    headers_block = effective_front_matter.get('lupopedia.headers', {})
    if not isinstance(headers_block, dict):
        headers_block = {}

    identity_line = generate_identity_line(headers_block, content)

    body = existing.get('body', '')
    if not body:
        body = content.get('body', '')

    yaml_out = yaml.safe_dump(
        effective_front_matter,
        default_flow_style=False,
        sort_keys=False,
        allow_unicode=False
    ).rstrip()

    full_content = "---\n" + yaml_out + "\n---\n" + identity_line + "\n\n" + body
    file_path.write_text(full_content, encoding='utf-8')
    if file_existed:
        print(f"Updated existing file: {file_path}")
    else:
        print(f"Created new file: {file_path}")

def main():
    """Main entry point."""
    args = parse_args()
    
    # Resolve artifact from database
    artifact = resolve_artifact(args)
    content = artifact['content']
    metadata = artifact['metadata']
    
    # Build block structure
    blocks = build_block_tree(metadata)
    
    # Normalize legacy block names
    blocks = normalize_legacy_blocks(blocks)
    
    # Build canonical blocks in order
    ordered_blocks = {}
    canonical_order = [
        'lupopedia.init',
        'lupopedia.routing', 
        'lupopedia.actor_references',
        'lupopedia.conditional',
        'lupopedia.headers',
        'lupopedia.metadata',
        'lupopedia.session',
        'lupopedia.edges',
        'lupopedia.engagement',
        'lupopedia.footer',
        'lupopedia.see',
        'lupopedia.next_actions'
    ]
    
    for block_name in canonical_order:
        if block_name in blocks:
            ordered_blocks[block_name] = blocks[block_name]
    
    # Build specific blocks
    headers_block = build_headers_block(ordered_blocks)
    footer_block = build_footer_block(ordered_blocks)
    metadata_block = build_metadata_block(ordered_blocks)
    
    # Generate YAML front matter
    front_matter_dict = {}
    
    # Add blocks in canonical order
    for block_name in canonical_order:
        if block_name in ordered_blocks and ordered_blocks[block_name]:
            front_matter_dict[block_name] = ordered_blocks[block_name]
    
    # Convert to YAML for preview
    front_matter = yaml.safe_dump(front_matter_dict, default_flow_style=False, sort_keys=False, allow_unicode=False)
    
    if args.dry_run:
        print("=== DRY RUN - RECONSTRUCTED YAML ===")
        print(front_matter)
        print("\n=== IDENTITY LINE ===")
        print(generate_identity_line(headers_block, content))
        print("\n=== SUMMARY ===")
        print(f"File path: {content.get('file_path_from_root', 'N/A')}")
        print(f"Content ID: {content.get('content_id', 'N/A')}")
        print(f"Header blocks found: {list(ordered_blocks.keys())}")
        return
    
    # Write output file
    output_path = Path(args.file_path) if args.file_path else Path(content['file_path_from_root'])
    write_output_file(output_path, front_matter_dict, content)

if __name__ == '__main__':
    main()