#!/usr/bin/env python3
"""
Generate LUPOPEDIA HEADERS from database metadata.

This script reads TOON/JSON schema artifacts to determine exact database
structure, then reconstructs canonical YAML front matter from lupo_contents
and lupo_metadata tables.

Authoritative sources:
- TOON/JSON files in docs/toons/ (exact table/column names)
- lupo_contents table (content records)
- lupo_metadata table (header metadata)

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
import sys
import yaml
from pathlib import Path
from typing import Dict, List, Optional, Any
from datetime import datetime

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
    
    def execute_query(self, query: str, params: tuple = ()) -> List[Dict]:
        """Execute a query and return results."""
        # Mock implementation - in production this would be actual SQL
        if query == "SELECT * FROM lupo_contents WHERE file_path_from_root = ? OR content_id = ?":
            file_path = params[0] if params else None
            content_id = params[1] if len(params) > 1 else None
            
            if file_path:
                return self._fetch_by_file_path(file_path)
            elif content_id:
                return self._fetch_by_content_id(content_id)
            else:
                return []
        
        return []
    
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

def resolve_artifact(args) -> Dict:
    """Resolve artifact from database using file_path or content_id."""
    db = get_db_connection()
    
    if args.file_path:
        content_row = db.execute_query(
            "SELECT * FROM lupo_contents WHERE file_path_from_root = ?",
            (args.file_path,)
        )
        
        if not content_row:
            print(f"ERROR: No content found for file_path: {args.file_path}", file=sys.stderr)
            sys.exit(1)
        
        metadata_rows = db.fetch_metadata_rows('file', content_row['content_id'])
        
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
        
        # Initialize block if not exists
        if property_key not in blocks:
            blocks[property_key] = {}
        
        blocks[property_key][property_key] = property_value
    
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
    title = headers.get('title', 'Untitled Document')
    delegation_chain = headers.get('delegation_chain', 'root')
    web_path = headers.get('web_path', '')
    
    return f"# file: {title} — delegation: {delegation_chain} — web_path: {web_path}"

def merge_front_matter_with_existing_body(file_path: Path, front_matter: str) -> None:
    """Merge generated front matter with existing body content."""
    if not file_path.exists():
        # Create new file
        body = content.get('body', '') if 'content' in front_matter else ''
        full_content = f"---\n{front_matter}---\n{generate_identity_line(front_matter, {})}\n\n{body}"
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(full_content)
        
        print(f"Created new file: {file_path}")
        return
    
    # Read existing body
    with open(file_path, 'r', encoding='utf-8') as f:
        existing_body = f.read()
    
    # Find the identity line in existing content
    lines = existing_body.split('\n')
    identity_line_idx = None
    for i, line in enumerate(lines):
        if line.startswith('# file:'):
            identity_line_idx = i
            break
    
    if identity_line_idx is None:
        print(f"WARNING: No identity line found in {file_path}, appending", file=sys.stderr)
        identity_line_idx = len(lines)
    
    # Merge: everything before identity line + new front matter + identity line + everything after
    before = lines[:identity_line_idx]
    after = lines[identity_line_idx + 1:] if identity_line_idx < len(lines) else []
    
    # Build new content
    if 'content' in front_matter:
        body = content.get('body', '')
    else:
        body = '\n'.join(after)
    
    full_content = ''.join(before) + f"---\n{front_matter}---\n{generate_identity_line(yaml.safe_load(front_matter), {})}\n\n{body}"
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(full_content)
    
    print(f"Updated existing file: {file_path}")

def write_output_file(file_path: Path, front_matter: str, content: Dict[str, Any]):
    """Write output file with front matter and body."""
    if file_path.exists():
        merge_front_matter_with_existing_body(file_path, front_matter)
    else:
        # Create new file
        body = content.get('body', '')
        full_content = f"---\n{front_matter}---\n{generate_identity_line(yaml.safe_load(front_matter), content)}\n\n{body}"
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(full_content)
        
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
    
    # Convert to YAML
    front_matter = yaml.dump(front_matter_dict, default_flow_style=False, sort_keys=False)
    
    # Add body content if available
    if 'body' in content:
        front_matter_dict['body'] = content['body']
    
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
    write_output_file(output_path, front_matter, content)

if __name__ == '__main__':
    main()
