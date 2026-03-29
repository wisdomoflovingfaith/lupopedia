#!/usr/bin/env python3
"""
import_context.py - Import context files into Lupopedia database

This script imports context artifacts from the lupo-context/ folder into the database.
It supports deterministic ID generation, multi-pass import, validation, and dry-run mode.

Usage:
    python import_context.py [--dry-run] [--force-draft] [--root-path PATH]
    
    --dry-run: Validate and report without writing to database
    --force-draft: Import draft contexts (skip verification check)
    --root-path: Root path to scan (default: 'lupo-context/')
"""

import os
import sys
import re
import json
import hashlib
import argparse
import yaml
from datetime import datetime
from typing import Dict, List, Tuple, Optional, Set
from pathlib import Path

# Add parent directory to path for imports
sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

# Import database connection
try:
    from lib.db_connection import get_db_connection
except ImportError:
    print("⚠️  Could not import db_connection. Database operations will fail.")
    print("   Ensure lupo-scripts/lib/db_connection.py exists.")
    sys.exit(1)

# ============================================================================
# Constants
# ============================================================================

# BigInt max value (signed 63-bit)
BIGINT_MAX = (1 << 63) - 1

# Required header fields by artifact type
REQUIRED_FIELDS = {
    'context': ['artifact_type', 'file_path_from_root', 'when_updated', 'tags'],
    'context_question': ['artifact_type', 'file_path_from_root', 'when_updated', 'question_text'],
    'context_answer': ['artifact_type', 'file_path_from_root', 'when_updated', 'confidence'],
    'context_card': ['artifact_type', 'file_path_from_root', 'when_updated', 'card_title']
}

# Forbidden header fields (deprecated)
DEPRECATED_FIELDS = ['version_when_written', 'system_version', 'lupopedia.version']

# ============================================================================
# ID Generation
# ============================================================================

def generate_stable_id(path_string: str) -> int:
    """
    Generate a stable BIGINT ID from a string using SHA-256.
    
    Returns a 63-bit integer (signed BIGINT range) suitable for MySQL primary keys.
    """
    hash_obj = hashlib.sha256(path_string.encode('utf-8'))
    # Take first 16 hex chars (64 bits)
    full_hash = int(hash_obj.hexdigest()[:16], 16)
    # Mask to 63 bits (BIGINT signed max)
    return full_hash & BIGINT_MAX


def generate_content_id(file_path: str, artifact_type: str) -> int:
    """
    Generate content_id with type prefix to avoid collisions across tables.
    
    The type prefix ensures that a context and a question with the same path
    (theoretically possible) get different IDs.
    """
    namespaced_path = f"{artifact_type}:{file_path}"
    return generate_stable_id(namespaced_path)


def generate_slug(context_code: str, question_slug: str) -> str:
    """Generate unique slug for questions across contexts."""
    return f"{context_code}-{question_slug}"

# ============================================================================
# Timestamp Utilities
# ============================================================================

def get_current_timestamp() -> str:
    """Get current UTC timestamp in YYYYMMDDHHIISS format."""
    return datetime.utcnow().strftime("%Y%m%d%H%M%S")


def parse_timestamp(timestamp_str: str) -> int:
    """Convert YYYYMMDDHHIISS string to integer."""
    if isinstance(timestamp_str, int):
        return timestamp_str
    return int(timestamp_str) if timestamp_str else 0

# ============================================================================
# YAML Parsing
# ============================================================================

def parse_artifact(file_path: str) -> Tuple[Dict, str]:
    """
    Parse a markdown file with YAML front matter.
    
    Returns:
        Tuple of (headers_dict, body_text)
    """
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Normalize line endings
    content = content.replace('\r\n', '\n')
    
    # Split YAML front matter
    parts = content.split('\n---\n', 1)
    
    if len(parts) < 2:
        # Try older format with closing ---
        parts = content.split('---')
        if len(parts) >= 3:
            headers_text = parts[1]
            body_text = parts[2].strip()
        else:
            return {}, content
    else:
        # New format: first --- is opening, second is closing
        # The first part might be empty or have content before first ---
        # We need the section between first --- and the closing ---
        # Simpler: find first ---, then find the next ---
        lines = content.split('\n')
        in_yaml = False
        yaml_lines = []
        body_lines = []
        yaml_started = False
        
        for line in lines:
            if line.strip() == '---':
                if not yaml_started:
                    yaml_started = True
                    continue
                else:
                    # End of YAML
                    in_yaml = False
                    continue
            
            if yaml_started and not in_yaml:
                in_yaml = True
            
            if in_yaml:
                yaml_lines.append(line)
            elif yaml_started:
                body_lines.append(line)
        
        headers_text = '\n'.join(yaml_lines)
        body_text = '\n'.join(body_lines).strip()
    
    # Parse YAML
    try:
        headers = yaml.safe_load(headers_text) if headers_text.strip() else {}
    except yaml.YAMLError as e:
        print(f"❌ YAML parsing error in {file_path}: {e}")
        return {}, content
    
    return headers or {}, body_text


def extract_title(body: str) -> str:
    """Extract first H1 heading from body text."""
    lines = body.split('\n')
    for line in lines:
        if line.startswith('# '):
            return line[2:].strip()
    return ""

# ============================================================================
# Validation
# ============================================================================

def validate_headers(headers: Dict, file_path: str, artifact_type: str) -> List[str]:
    """
    Validate headers against doctrine.
    
    Returns:
        List of error messages (empty if valid)
    """
    errors = []
    
    # Check required fields
    required = REQUIRED_FIELDS.get(artifact_type, ['artifact_type', 'file_path_from_root', 'when_updated'])
    for field in required:
        if field not in headers:
            errors.append(f"Missing required field: {field}")
    
    # Check deprecated fields
    for field in DEPRECATED_FIELDS:
        if field in headers:
            errors.append(f"Deprecated field found: {field}")
    
    # Check timestamp format
    for field in ['when_updated', 'last_modified_utc']:
        if field in headers:
            value = headers[field]
            if not re.match(r'^\d{14}$', str(value)):
                errors.append(f"Invalid timestamp format for {field}: {value} (expected YYYYMMDDHHIISS)")
    
    # Check confidence range (for answers)
    if artifact_type == 'context_answer':
        confidence = headers.get('confidence', 0)
        if not 0 <= confidence <= 1:
            errors.append(f"Confidence must be between 0 and 1, got: {confidence}")
    
    # Check tags (for contexts)
    if artifact_type == 'context':
        tags = headers.get('tags', [])
        if not tags:
            errors.append("Context missing tags (used for context_code)")
    
    return errors


def validate_edges(headers: Dict, file_path: str, root_path: Path, resolved_paths: Set[str]) -> List[str]:
    """
    Validate outbound edges point to existing files.
    
    Args:
        headers: Parsed headers dictionary
        file_path: Current file path (for reference)
        root_path: Root directory being scanned
        resolved_paths: Set of file paths already discovered
    
    Returns:
        List of error messages
    """
    errors = []
    edges = headers.get('lupopedia', {}).get('edges', {})
    outbound = edges.get('outbound_edges', [])
    
    # If edges is a dict with category keys, flatten
    if isinstance(outbound, dict):
        flat_edges = []
        for category, edges_list in outbound.items():
            for edge in edges_list:
                edge['category'] = category
                flat_edges.append(edge)
        outbound = flat_edges
    
    for edge in outbound:
        target = edge.get('to')
        if not target:
            continue
        
        # Resolve target path
        if target.startswith('lupo-'):
            target_path = target
        else:
            # Relative to file's directory
            file_dir = os.path.dirname(file_path)
            target_path = os.path.normpath(os.path.join(file_dir, target))
        
        # Check if target exists in resolved paths or on disk
        if target_path not in resolved_paths:
            full_path = root_path / target_path
            if not full_path.exists():
                errors.append(f"Edge target not found: {target} (resolved to {target_path})")
    
    return errors

# ============================================================================
# Database Operations
# ============================================================================

class ContextImporter:
    """Main importer class for context artifacts."""
    
    def __init__(self, root_path: str, dry_run: bool = False, force_draft: bool = False):
        self.root_path = Path(root_path)
        self.dry_run = dry_run
        self.force_draft = force_draft
        self.db = None if dry_run else get_db_connection()
        self.errors = []
        self.warnings = []
        
        # Maps for resolved IDs
        self.context_map: Dict[str, int] = {}      # file_path -> context_id
        self.question_map: Dict[str, int] = {}     # file_path -> truth_id
        self.answer_map: Dict[str, int] = {}       # file_path -> truth_answer_id
        self.card_map: Dict[str, int] = {}         # file_path -> context_card_id
        
        # Set of all discovered files
        self.discovered_files: Set[str] = set()
    
    def log_error(self, message: str):
        """Log an error."""
        self.errors.append(message)
        print(f"❌ {message}")
    
    def log_warning(self, message: str):
        """Log a warning."""
        self.warnings.append(message)
        print(f"⚠️  {message}")
    
    def log_info(self, message: str):
        """Log an info message."""
        print(f"ℹ️  {message}")
    
    def log_success(self, message: str):
        """Log a success message."""
        print(f"✅ {message}")
    
    def get_current_timestamp_int(self) -> int:
        """Get current timestamp as integer."""
        return int(get_current_timestamp())
    
    # ========================================================================
    # Discovery Phase
    # ========================================================================
    
    def discover_files(self) -> Dict[str, List[Tuple[str, Dict, str]]]:
        """
        Discover and categorize all context files.
        
        Returns:
            Dictionary with keys: context, question, answer, card, other
        """
        if not self.root_path.exists():
            self.log_error(f"Root path not found: {self.root_path}")
            return {}
        
        categories = {
            'context': [],
            'question': [],
            'answer': [],
            'card': [],
            'other': []
        }
        
        for file_path in self.root_path.rglob('*.md'):
            rel_path = str(file_path.relative_to(self.root_path.parent))
            self.discovered_files.add(rel_path)
            
            headers, body = parse_artifact(str(file_path))
            artifact_type = headers.get('artifact_type', 'unknown')
            
            # Check if draft should be skipped
            if not self.force_draft:
                status = headers.get('canonical_status', 'draft')
                footer = headers.get('lupopedia', {}).get('footer', {})
                verified_by = footer.get('verified_by')
                
                if status != 'final' or not verified_by:
                    self.log_warning(f"Skipping draft/unverified: {rel_path}")
                    continue
            
            # Validate headers
            errors = validate_headers(headers, rel_path, artifact_type)
            for error in errors:
                self.log_error(f"{rel_path}: {error}")
            
            # Categorize
            if artifact_type == 'context':
                categories['context'].append((rel_path, headers, body))
            elif artifact_type == 'context_question':
                categories['question'].append((rel_path, headers, body))
            elif artifact_type == 'context_answer':
                categories['answer'].append((rel_path, headers, body))
            elif artifact_type == 'context_card':
                categories['card'].append((rel_path, headers, body))
            else:
                categories['other'].append((rel_path, headers, body))
                self.log_warning(f"Unknown artifact_type: {artifact_type} in {rel_path}")
        
        return categories
    
    # ========================================================================
    # Import Phase 1: Contexts
    # ========================================================================
    
    def import_context(self, file_path: str, headers: Dict, body: str) -> Optional[int]:
        """Import a context into lupo_contexts."""
        # Generate stable ID
        context_id = generate_content_id(file_path, 'context')
        
        # Extract context_code from first tag or folder name
        tags = headers.get('tags', [])
        context_code = tags[0] if tags else Path(file_path).parent.name
        context_code = context_code.lower().replace(' ', '-')
        
        # Extract title from body
        title = extract_title(body) or headers.get('title', context_code)
        
        # Determine parent context from edges
        parent_context_id = None
        edges = headers.get('lupopedia', {}).get('edges', {})
        outbound = edges.get('outbound_edges', [])
        
        for edge in outbound:
            if edge.get('type') == 'parent_context':
                parent_path = edge.get('to')
                if parent_path in self.context_map:
                    parent_context_id = self.context_map[parent_path]
        
        # Prepare data
        data = {
            'context_id': context_id,
            'context_code': context_code,
            'context_name': title,
            'context_description': headers.get('purpose', ''),
            'parent_context_id': parent_context_id,
            'is_system': 1 if headers.get('is_system', False) else 0,
            'is_fiction': 1 if headers.get('is_fiction', False) else 0,
            'is_installation_local': 1 if headers.get('is_installation_local', False) else 0,
            'sort_order': headers.get('sort_order', 0),
            'created_ymdhis': parse_timestamp(headers.get('when_updated', get_current_timestamp())),
            'updated_ymdhis': self.get_current_timestamp_int(),
            'weight_score': headers.get('weight_score', 0.0),
            'is_active': 1,
            'metadata_json': json.dumps(headers)
        }
        
        if self.dry_run:
            self.log_info(f"[DRY RUN] Would import context: {file_path} (ID: {context_id})")
            return context_id
        
        try:
            cursor = self.db.cursor()
            cursor.execute("""
                INSERT INTO lupo_contexts 
                (context_id, context_code, context_name, context_description,
                 parent_context_id, is_system, is_fiction, is_installation_local,
                 sort_order, created_ymdhis, updated_ymdhis, weight_score,
                 is_active, metadata_json)
                VALUES (%(context_id)s, %(context_code)s, %(context_name)s, %(context_description)s,
                        %(parent_context_id)s, %(is_system)s, %(is_fiction)s, %(is_installation_local)s,
                        %(sort_order)s, %(created_ymdhis)s, %(updated_ymdhis)s, %(weight_score)s,
                        %(is_active)s, %(metadata_json)s)
                ON DUPLICATE KEY UPDATE
                    context_name = VALUES(context_name),
                    context_description = VALUES(context_description),
                    updated_ymdhis = VALUES(updated_ymdhis),
                    weight_score = VALUES(weight_score),
                    metadata_json = VALUES(metadata_json)
            """, data)
            self.db.commit()
            self.log_success(f"Imported context: {file_path} (ID: {context_id})")
            return context_id
        except Exception as e:
            self.log_error(f"Failed to import context {file_path}: {e}")
            return None
    
    # ========================================================================
    # Import Phase 2: Questions
    # ========================================================================
    
    def import_question(self, file_path: str, headers: Dict, body: str, parent_context_id: Optional[int] = None) -> Optional[int]:
        """Import a question into lupo_truth_questions (Option A)."""
        # Generate stable ID
        question_id = generate_content_id(file_path, 'question')
        # Generate slug with context prefix
        context_code = headers.get('context_code', '')
        if not context_code and parent_context_id:
            for ctx_path, ctx_id in self.context_map.items():
                if ctx_id == parent_context_id:
                    context_code = Path(ctx_path).parent.name
                    break
        question_slug = headers.get('slug', Path(file_path).stem)
        slug = generate_slug(context_code, question_slug) if context_code else question_slug
        # Prepare data for Option A split table
        data = {
            'truth_question_id': question_id,
            'qtype': headers.get('qtype', ''),
            'slug': slug,
            'question_text': headers.get('question_text', ''),
            'target_object_type': headers.get('target_object_type', 'context'),
            'target_object_id': parent_context_id,
            'actor_id': headers.get('actor_id', 0),
            'question_status': 'open' if headers.get('canonical_status') == 'final' else 'draft',
            'is_featured': 1 if headers.get('is_featured', False) else 0,
            'answer_count': 0,
            'created_ymdhis': parse_timestamp(headers.get('when_updated', get_current_timestamp())),
            'updated_ymdhis': self.get_current_timestamp_int(),
            'is_deleted': 0,
            'metadata_json': json.dumps(headers)
        }
        if self.dry_run:
            self.log_info(f"[DRY RUN] Would import question: {file_path} (ID: {question_id})")
            return question_id
        try:
            cursor = self.db.cursor()
            cursor.execute("""
                INSERT INTO lupo_truth_questions
                (truth_question_id, qtype, slug, question_text, target_object_type, target_object_id, actor_id, question_status, is_featured, answer_count, created_ymdhis, updated_ymdhis, is_deleted, metadata_json)
                VALUES (%(truth_question_id)s, %(qtype)s, %(slug)s, %(question_text)s, %(target_object_type)s, %(target_object_id)s, %(actor_id)s, %(question_status)s, %(is_featured)s, %(answer_count)s, %(created_ymdhis)s, %(updated_ymdhis)s, %(is_deleted)s, %(metadata_json)s)
                ON DUPLICATE KEY UPDATE
                    question_text = VALUES(question_text),
                    updated_ymdhis = VALUES(updated_ymdhis),
                    question_status = VALUES(question_status),
                    is_featured = VALUES(is_featured),
                    answer_count = VALUES(answer_count),
                    metadata_json = VALUES(metadata_json)
            """, data)
            self.db.commit()
            self.log_success(f"Imported question: {file_path} (ID: {question_id})")
            return question_id
        except Exception as e:
            self.log_error(f"Failed to import question {file_path}: {e}")
            return None
    
    # ========================================================================
    # Import Phase 3: Answers
    # ========================================================================
    
    def import_answer(self, file_path: str, headers: Dict, body: str, question_id: Optional[int] = None) -> Optional[int]:
        """Import an answer into lupo_truth_answers (Option A)."""
        # Generate stable ID
        answer_id = generate_content_id(file_path, 'truth_answer')
        # Prepare answer data for Option A split table
        answer_data = {
            'truth_answer_id': answer_id,
            'truth_question_id': question_id,
            'actor_id': headers.get('actor_id', 0),
            'answer_text': body,
            'confidence_score': headers.get('confidence', 0.9),
            'status': 'active' if headers.get('canonical_status') == 'final' else 'draft',
            'created_ymdhis': parse_timestamp(headers.get('when_updated', get_current_timestamp())),
            'updated_ymdhis': self.get_current_timestamp_int(),
            'evidence_score': headers.get('evidence_score', 0.0),
            'is_deleted': 0,
            'metadata_json': json.dumps(headers)
        }
        if self.dry_run:
            self.log_info(f"[DRY RUN] Would import answer: {file_path} (ID: {answer_id})")
            return answer_id
        try:
            cursor = self.db.cursor()
            cursor.execute("""
                INSERT INTO lupo_truth_answers
                (truth_answer_id, truth_question_id, actor_id, answer_text, confidence_score, status, created_ymdhis, updated_ymdhis, evidence_score, is_deleted, metadata_json)
                VALUES (%(truth_answer_id)s, %(truth_question_id)s, %(actor_id)s, %(answer_text)s, %(confidence_score)s, %(status)s, %(created_ymdhis)s, %(updated_ymdhis)s, %(evidence_score)s, %(is_deleted)s, %(metadata_json)s)
                ON DUPLICATE KEY UPDATE
                    answer_text = VALUES(answer_text),
                    confidence_score = VALUES(confidence_score),
                    updated_ymdhis = VALUES(updated_ymdhis),
                    status = VALUES(status),
                    evidence_score = VALUES(evidence_score),
                    metadata_json = VALUES(metadata_json)
            """, answer_data)
            self.db.commit()
            self.log_success(f"Imported answer: {file_path} (ID: {answer_id})")
            return answer_id
        except Exception as e:
            self.log_error(f"Failed to import answer {file_path}: {e}")
            return None
    
    # ========================================================================
    # Import Phase 4: Context Cards
    # ========================================================================
    
    def import_context_card(self, file_path: str, headers: Dict, body: str, context_id: Optional[int] = None) -> Optional[int]:
        """Import a context card into lupo_context_cards."""
        # Generate stable ID
        card_id = generate_content_id(file_path, 'context_card')
        
        # Prepare data
        data = {
            'context_card_id': card_id,
            'context_id': context_id,
            'card_title': headers.get('card_title', extract_title(body) or Path(file_path).stem),
            'instruction_text': headers.get('instruction_text', body[:280]),
            'card_type': headers.get('card_type', 'instruction'),
            'display_order': headers.get('display_order', 0),
            'metadata': json.dumps(headers),
            'created_ymdhis': parse_timestamp(headers.get('when_updated', get_current_timestamp())),
            'updated_ymdhis': self.get_current_timestamp_int()
        }
        
        if self.dry_run:
            self.log_info(f"[DRY RUN] Would import context card: {file_path} (ID: {card_id})")
            return card_id
        
        try:
            cursor = self.db.cursor()
            cursor.execute("""
                INSERT INTO lupo_context_cards 
                (context_card_id, context_id, card_title, instruction_text,
                 card_type, display_order, metadata, created_ymdhis, updated_ymdhis)
                VALUES (%(context_card_id)s, %(context_id)s, %(card_title)s, %(instruction_text)s,
                        %(card_type)s, %(display_order)s, %(metadata)s, %(created_ymdhis)s, %(updated_ymdhis)s)
                ON DUPLICATE KEY UPDATE
                    card_title = VALUES(card_title),
                    instruction_text = VALUES(instruction_text),
                    updated_ymdhis = VALUES(updated_ymdhis),
                    metadata = VALUES(metadata)
            """, data)
            self.db.commit()
            self.log_success(f"Imported context card: {file_path} (ID: {card_id})")
            return card_id
        except Exception as e:
            self.log_error(f"Failed to import context card {file_path}: {e}")
            return None
    
    # ========================================================================
    # Import Phase 5: Edges
    # ========================================================================
    
    def create_edge(self, left_type: str, left_id: int, right_type: str, right_id: int,
                    edge_type: str, reason: str = "", weight: float = 1.0) -> bool:
        """Create an edge between two entities."""
        edge_id = generate_stable_id(f"{left_type}:{left_id}->{right_type}:{right_id}:{edge_type}")
        
        data = {
            'edge_id': edge_id,
            'left_object_type': left_type,
            'left_object_id': left_id,
            'right_object_type': right_type,
            'right_object_id': right_id,
            'edge_type': edge_type,
            'edge_category': 'context',
            'weight_score': int(weight * 100),
            'semantic_weight': weight,
            'flare_weight': weight,
            'flare_reason': reason[:255],
            'domain_id': 1,
            'created_ymdhis': self.get_current_timestamp_int(),
            'updated_ymdhis': self.get_current_timestamp_int()
        }
        
        if self.dry_run:
            return True
        
        try:
            cursor = self.db.cursor()
            cursor.execute("""
                INSERT INTO lupo_edges 
                (edge_id, left_object_type, left_object_id, right_object_type, right_object_id,
                 edge_type, edge_category, weight_score, semantic_weight, flare_weight,
                 flare_reason, domain_id, created_ymdhis, updated_ymdhis)
                VALUES (%(edge_id)s, %(left_object_type)s, %(left_object_id)s, %(right_object_type)s, %(right_object_id)s,
                        %(edge_type)s, %(edge_category)s, %(weight_score)s, %(semantic_weight)s, %(flare_weight)s,
                        %(flare_reason)s, %(domain_id)s, %(created_ymdhis)s, %(updated_ymdhis)s)
                ON DUPLICATE KEY UPDATE
                    updated_ymdhis = VALUES(updated_ymdhis),
                    flare_reason = VALUES(flare_reason)
            """, data)
            self.db.commit()
            return True
        except Exception as e:
            self.log_error(f"Failed to create edge: {e}")
            return False
    
    def import_edges(self, file_path: str, headers: Dict, resolved_ids: Dict[str, int]) -> None:
        """Import all edges from a file's headers."""
        edges = headers.get('lupopedia', {}).get('edges', {})
        outbound = edges.get('outbound_edges', [])
        
        # Get left object ID
        artifact_type = headers.get('artifact_type')
        if artifact_type == 'context':
            left_id = self.context_map.get(file_path)
            left_type = 'context'
        elif artifact_type == 'context_question':
            left_id = self.question_map.get(file_path)
            left_type = 'truth_knowledge'
        elif artifact_type == 'context_answer':
            left_id = self.answer_map.get(file_path)
            left_type = 'truth_answer'
        elif artifact_type == 'context_card':
            left_id = self.card_map.get(file_path)
            left_type = 'context_card'
        else:
            return
        
        if not left_id:
            self.log_warning(f"Cannot import edges for {file_path}: left object not found")
            return
        
        # Handle grouped edges (category keys)
        if isinstance(outbound, dict):
            flat_edges = []
            for category, edges_list in outbound.items():
                for edge in edges_list:
                    edge['category'] = category
                    flat_edges.append(edge)
            outbound = flat_edges
        
        # Process each edge
        for edge in outbound:
            target = edge.get('to')
            if not target:
                continue
            
            # Resolve target ID
            target_id = resolved_ids.get(target)
            if not target_id:
                # Try to find by path in context_map
                if target in self.context_map:
                    target_id = self.context_map[target]
                    target_type = 'context'
                elif target in self.question_map:
                    target_id = self.question_map[target]
                    target_type = 'truth_knowledge'
                elif target in self.answer_map:
                    target_id = self.answer_map[target]
                    target_type = 'truth_answer'
                elif target in self.card_map:
                    target_id = self.card_map[target]
                    target_type = 'context_card'
                else:
                    self.log_warning(f"Edge target not resolved: {target} from {file_path}")
                    continue
            else:
                # Determine target type from resolved_ids map
                if target in self.context_map:
                    target_type = 'context'
                elif target in self.question_map:
                    target_type = 'truth_knowledge'
                elif target in self.answer_map:
                    target_type = 'truth_answer'
                elif target in self.card_map:
                    target_type = 'context_card'
                else:
                    target_type = 'unknown'
            
            self.create_edge(
                left_type=left_type,
                left_id=left_id,
                right_type=target_type,
                right_id=target_id,
                edge_type=edge.get('type', 'references'),
                reason=edge.get('reason', ''),
                weight=edge.get('weight', 1.0)
            )
    
    # ========================================================================
    # Cleanup Phase: Mark Deleted Files
    # ========================================================================
    
    def mark_deleted_files(self, current_files: Set[str]) -> None:
        """Mark files that no longer exist as deleted."""
        if self.dry_run:
            self.log_info("[DRY RUN] Would mark missing files as deleted")
            return
        
        try:
            cursor = self.db.cursor()
            
            # Get all file paths from metadata
            cursor.execute("""
                SELECT DISTINCT JSON_EXTRACT(metadata_json, '$.file_path_from_root') as file_path
                FROM lupo_metadata
                WHERE class_name = 'lupopedia_header_sync'
                AND JSON_EXTRACT(metadata_json, '$.artifact_type') IN ('context', 'context_question', 'context_answer', 'context_card')
            """)
            
            db_files = set()
            for row in cursor.fetchall():
                file_path = row[0] if row[0] else ''
                if file_path and file_path not in current_files:
                    db_files.add(file_path)
            
            for file_path in db_files:
                self.log_info(f"Marking as deleted: {file_path}")
                cursor.execute("""
                    UPDATE lupo_metadata 
                    SET is_deleted = 1, deleted_ymdhis = %s
                    WHERE JSON_EXTRACT(metadata_json, '$.file_path_from_root') = %s
                    AND class_name = 'lupopedia_header_sync'
                """, (self.get_current_timestamp_int(), file_path))
            
            self.db.commit()
            self.log_success(f"Marked {len(db_files)} files as deleted")
        except Exception as e:
            self.log_error(f"Failed to mark deleted files: {e}")
    
    # ========================================================================
    # Main Import Flow
    # ========================================================================
    
    def run(self) -> bool:
        """Run the full import process."""
        print("=" * 60)
        print("Context Importer")
        print(f"Root path: {self.root_path}")
        print(f"Dry run: {self.dry_run}")
        print(f"Force draft: {self.force_draft}")
        print("=" * 60)
        
        # Phase 0: Discover files
        print("\n📂 Discovering files...")
        categories = self.discover_files()
        
        if not categories['context'] and not categories['question'] and not categories['answer']:
            self.log_warning("No context files found")
            return len(self.errors) == 0
        
        print(f"  - Contexts: {len(categories['context'])}")
        print(f"  - Questions: {len(categories['question'])}")
        print(f"  - Answers: {len(categories['answer'])}")
        print(f"  - Cards: {len(categories['card'])}")
        print(f"  - Other: {len(categories['other'])}")
        
        # Phase 1: Import contexts
        print("\n📝 Importing contexts...")
        for file_path, headers, body in categories['context']:
            context_id = self.import_context(file_path, headers, body)
            if context_id:
                self.context_map[file_path] = context_id
        
        # Phase 2: Import questions
        print("\n❓ Importing questions...")
        for file_path, headers, body in categories['question']:
            # Find parent context from edges or path
            parent_context_id = None
            edges = headers.get('lupopedia', {}).get('edges', {})
            outbound = edges.get('outbound_edges', [])
            
            # Flatten grouped edges
            if isinstance(outbound, dict):
                flat_edges = []
                for category, edges_list in outbound.items():
                    for edge in edges_list:
                        flat_edges.append(edge)
                outbound = flat_edges
            
            for edge in outbound:
                if edge.get('type') == 'parent_context':
                    parent_path = edge.get('to')
                    parent_context_id = self.context_map.get(parent_path)
                    break
            
            # If no explicit parent, use path-based resolution
           