#!/usr/bin/env python3
"""
Generate ALTER statements from TOON files for migration 4.2.0

Scans all lupo_* TOON files and generates:
1. ALTER statements to remove UNSIGNED from columns
2. ALTER statements to rename primary keys to follow "singular + _id" convention
3. CREATE TABLE statement for missing tables like lupo_collection_tab_paths

WOLFIE Headers v4.0.2:
- wolfie.headers.version: 4.0.2
- dialog.speaker: CURSOR
- dialog.target: @everyone
- dialog.message: "Generated script to create migration SQL from TOON metadata"

@version 1.0.0
@author Captain Wolfie
"""

import json
import os
import sys
from pathlib import Path
from typing import List, Dict, Any, Tuple

def calculate_expected_pk_name(table_name: str) -> str:
    """
    Calculate expected primary key name: singular of table name + _id
    
    This function matches the logic in generate_toon_files.py
    """
    # Remove lupo_ prefix if present
    name = table_name.replace('lupo_', '', 1) if table_name.startswith('lupo_') else table_name
    
    # Handle plural to singular - process from right to left (last word)
    # Split on underscore to handle multi-word table names
    parts = name.split('_')
    if len(parts) > 0:
        last_word = parts[-1]
        singular_last = _plural_to_singular(last_word)
        parts[-1] = singular_last
        name = '_'.join(parts)
    
    return f"{name}_id"

def _plural_to_singular(word: str) -> str:
    """Convert plural word to singular (same logic as generate_toon_files.py)."""
    # Known mappings for tricky cases
    known_mappings = {
        'roles': 'role',
        'likes': 'like',
        'redirects': 'redirect',
        'fragments': 'fragment',
        'messages': 'message',
        'edges': 'edge',
        'bodies': 'body',
    }
    
    if word in known_mappings:
        return known_mappings[word]
    
    # Rule 1: Words ending in "ies" → "y"
    if word.endswith('ies'):
        return word[:-3] + 'y'
    # Rule 2: Words ending in "es" (not "ies" or "ss")
    elif word.endswith('es') and not word.endswith('ies') and not word.endswith('ss'):
        # Common pattern: words ending in "les", "kes", "tes", etc. → remove "s" only
        if word.endswith(('les', 'kes', 'tes', 'ges', 'mes', 'nes', 'pes', 'res', 'ses', 'ves', 'xes', 'zes')):
            if len(word) > 3:
                return word[:-1]  # Remove "s" only
        return word[:-2]  # Remove "es"
    # Rule 3: Words ending in "s" (not "ss" or "es") → remove "s"
    elif word.endswith('s') and not word.endswith('ss') and not word.endswith('es'):
        return word[:-1]
    
    return word  # Return as-is if no plural detected

def load_toon_file(filepath: str) -> Dict[str, Any]:
    """Load and parse a TOON file."""
    with open(filepath, 'r', encoding='utf-8') as f:
        return json.load(f)

def get_all_toon_files() -> List[str]:
    """Get all lupo_*.toon files."""
    toon_dir = Path(__file__).parent.parent / 'toon_data'
    return sorted([str(toon_dir / f) for f in os.listdir(toon_dir) 
                   if f.startswith('lupo_') and f.endswith('.toon')])

def parse_column_type(field_def: str) -> Tuple[str, bool]:
    """
    Parse column type and check if UNSIGNED.
    Returns: (type_without_unsigned, has_unsigned)
    """
    field_def = field_def.strip()
    has_unsigned = ' unsigned' in field_def.lower()
    if has_unsigned:
        # Remove unsigned (case insensitive)
        parts = field_def.split()
        type_without_unsigned = ' '.join([p for p in parts if p.lower() != 'unsigned'])
    else:
        type_without_unsigned = field_def
    
    return type_without_unsigned, has_unsigned

def extract_column_name_and_full_def(field_def: str) -> Tuple[str, str]:
    """Extract column name and full definition."""
    # Field def format: `column_name` TYPE ...
    if field_def.startswith('`'):
        end_quote = field_def.find('`', 1)
        if end_quote > 0:
            col_name = field_def[1:end_quote]
            return col_name, field_def
    return '', field_def

def generate_alter_statements() -> Tuple[List[str], List[str], List[str]]:
    """
    Generate ALTER statements for all lupo_* tables.
    Returns: (unsigned_alters, pk_renames, create_tables)
    """
    unsigned_alters = []
    pk_renames = []
    create_tables = []
    
    toon_files = get_all_toon_files()
    
    # Check if lupo_collection_tab_paths exists
    tab_paths_exists = False
    for toon_file in toon_files:
        if 'lupo_collection_tab_paths' in toon_file:
            tab_paths_exists = True
            break
    
    if not tab_paths_exists:
        # Generate CREATE TABLE for lupo_collection_tab_paths
        create_tables.append("""
-- ============================================================================
-- CREATE MISSING TABLE: lupo_collection_tab_paths
-- ============================================================================
CREATE TABLE IF NOT EXISTS `lupo_collection_tab_paths` (
  `collection_tab_path_id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `collection_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL,
  `path` varchar(500) NOT NULL COMMENT 'Full tab path: departments/parks-and-recreation/summer-programs',
  `depth` int NOT NULL COMMENT 'Number of levels (1 = root tab)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  UNIQUE KEY `unique_tab_path` (`collection_id`, `collection_tab_id`, `path`),
  INDEX `idx_collection` (`collection_id`),
  INDEX `idx_collection_tab` (`collection_tab_id`),
  INDEX `idx_path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Full hierarchical paths for tabs - enables fast lookups and semantic edge generation';
""")
    
    for toon_file in toon_files:
        try:
            toon_data = load_toon_file(toon_file)
            table_name = toon_data.get('table_name', '')
            
            if not table_name or not table_name.startswith('lupo_'):
                continue
            
            # Process UNSIGNED columns
            unsigned_cols = toon_data.get('unsigned_columns', [])
            if unsigned_cols:
                alter_parts = []
                for col_info in unsigned_cols:
                    col_name = col_info.get('column_name', '')
                    is_pk = col_info.get('is_primary_key', False)
                    if col_name and not is_pk:  # PK will be handled separately
                        # Get full field definition
                        field_defs = toon_data.get('fields', [])
                        for field_def in field_defs:
                            col_name_extracted, _ = extract_column_name_and_full_def(field_def)
                            if col_name_extracted == col_name:
                                _, has_unsigned = parse_column_type(field_def)
                                if has_unsigned:
                                    # Remove unsigned from definition
                                    expected_type = field_def.replace(' unsigned', '').replace(' UNSIGNED', '')
                                    # Extract just the type part after column name
                                    parts = expected_type.split()
                                    if len(parts) >= 2:
                                        type_part = ' '.join(parts[1:])
                                        alter_parts.append(f"  MODIFY COLUMN `{col_name}` {type_part}")
                                break
                
                if alter_parts:
                    unsigned_alters.append(f"\n-- {table_name}: Remove UNSIGNED from columns")
                    unsigned_alters.append(f"ALTER TABLE `{table_name}`")
                    unsigned_alters.append(",\n".join(alter_parts) + ";")
            
            # Process Primary Key rename
            pk_info = toon_data.get('primary_key', {})
            current_pk = pk_info.get('column_name', '') if pk_info else None
            
            # Calculate correct expected PK name (fixes incorrect TOON metadata)
            if current_pk:
                expected_pk_calculated = calculate_expected_pk_name(table_name)
                needs_rename = (current_pk != expected_pk_calculated)
                
                if needs_rename:
                    expected_pk = expected_pk_calculated
                    # Get full PK definition
                    field_defs = toon_data.get('fields', [])
                    pk_def = ''
                    for field_def in field_defs:
                        col_name_extracted, _ = extract_column_name_and_full_def(field_def)
                        if col_name_extracted == current_pk:
                            pk_def = field_def
                            break
                    
                    if pk_def:
                        # Remove unsigned and extract type
                        pk_type_clean, _ = parse_column_type(pk_def)
                        # Extract type part (everything after column name)
                        parts = pk_type_clean.split()
                        if len(parts) >= 2:
                            type_part = ' '.join(parts[1:])
                            # Ensure AUTO_INCREMENT is preserved
                            if 'auto_increment' not in type_part.lower() and 'AUTO_INCREMENT' not in type_part:
                                type_part += ' AUTO_INCREMENT'
                            
                            pk_renames.append(f"\n-- {table_name}: Rename PK from {current_pk} to {expected_pk}")
                            pk_renames.append(f"ALTER TABLE `{table_name}`")
                            pk_renames.append(f"  DROP PRIMARY KEY,")
                            pk_renames.append(f"  CHANGE COLUMN `{current_pk}` `{expected_pk}` {type_part} FIRST,")
                            pk_renames.append(f"  ADD PRIMARY KEY (`{expected_pk}`);")
            
            # Process Primary Key UNSIGNED removal (if PK doesn't need rename)
            if pk_info and not pk_info.get('needs_rename', False):
                current_pk = pk_info.get('column_name', '')
                unsigned_cols = toon_data.get('unsigned_columns', [])
                for col_info in unsigned_cols:
                    if col_info.get('column_name') == current_pk and col_info.get('is_primary_key', False):
                        # PK has UNSIGNED but name is correct - just remove UNSIGNED
                        field_defs = toon_data.get('fields', [])
                        for field_def in field_defs:
                            col_name_extracted, _ = extract_column_name_and_full_def(field_def)
                            if col_name_extracted == current_pk:
                                expected_type = field_def.replace(' unsigned', '').replace(' UNSIGNED', '')
                                parts = expected_type.split()
                                if len(parts) >= 2:
                                    type_part = ' '.join(parts[1:])
                                    unsigned_alters.append(f"\n-- {table_name}: Remove UNSIGNED from PK {current_pk}")
                                    unsigned_alters.append(f"ALTER TABLE `{table_name}`")
                                    unsigned_alters.append(f"  MODIFY COLUMN `{current_pk}` {type_part} FIRST;")
                                break
                        break
        
        except Exception as e:
            print(f"Error processing {toon_file}: {e}", file=sys.stderr)
            continue
    
    return unsigned_alters, pk_renames, create_tables

def main():
    """Generate migration SQL file."""
    import sys
    import os
    
    unsigned_alters, pk_renames, create_tables = generate_alter_statements()
    
    output_file = Path(__file__).parent / 'fix_unsigned_and_pk_naming_4_2_0_from_toon.sql'
    
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("""-- ============================================================================
-- Lupopedia 4.2.0 Migration: Fix UNSIGNED Columns and Primary Key Naming
-- Generated from TOON file metadata
-- ============================================================================
-- Purpose: 
--   1. Remove all UNSIGNED keywords from columns (for PostgreSQL compatibility)
--   2. Rename primary keys to follow "singular table name + _id" convention
--   3. Create missing tables (e.g., lupo_collection_tab_paths)
--
-- Doctrine: 
--   - No UNSIGNED (PostgreSQL doesn't support it)
--   - Primary keys must be singular table name + _id (because no FK keys)
--   - All relationships are application-managed, so naming must be explicit
--
-- SCOPE: 
--   - ONLY lupo_* prefixed tables are included in this migration
--   - livehelp_* prefixed tables are OLD Crafty Syntax tables and are IGNORED
--
-- Generated: 2026-01-10 (from TOON files)
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

""")
        
        if create_tables:
            f.write("-- ============================================================================\n")
            f.write("-- PART 0: CREATE MISSING TABLES\n")
            f.write("-- ============================================================================\n")
            for create_stmt in create_tables:
                f.write(create_stmt)
            f.write("\n")
        
        f.write("-- ============================================================================\n")
        f.write("-- PART 1: REMOVE UNSIGNED FROM ALL COLUMNS (non-PK first)\n")
        f.write("-- ============================================================================\n")
        if unsigned_alters:
            for alter in unsigned_alters:
                f.write(alter + "\n")
        else:
            f.write("-- No UNSIGNED columns found (or all are handled in PK rename section)\n")
        
        f.write("\n-- ============================================================================\n")
        f.write("-- PART 2: RENAME PRIMARY KEYS AND REMOVE UNSIGNED FROM PKs\n")
        f.write("-- ============================================================================\n")
        if pk_renames:
            for rename in pk_renames:
                f.write(rename + "\n")
        else:
            f.write("-- No primary keys need renaming\n")
        
        f.write("""
-- ============================================================================
-- PART 3: VERIFICATION QUERIES
-- ============================================================================

-- Check for remaining UNSIGNED columns (should return empty)
SELECT 
    TABLE_NAME, 
    COLUMN_NAME, 
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME LIKE 'lupo_%'
  AND COLUMN_TYPE LIKE '%unsigned%'
ORDER BY TABLE_NAME, COLUMN_NAME;

-- Check primary key naming (should all follow "singular + _id" convention)
SELECT 
    TABLE_NAME,
    COLUMN_NAME as PRIMARY_KEY
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME LIKE 'lupo_%'
  AND CONSTRAINT_NAME = 'PRIMARY'
ORDER BY TABLE_NAME;

COMMIT;
""")
    
    print(f"Generated migration SQL: {output_file}")
    print(f"  - CREATE TABLE statements: {len(create_tables)}")
    print(f"  - UNSIGNED removal statements: {len([a for a in unsigned_alters if a.strip() and not a.strip().startswith('--')])}")
    print(f"  - PK rename statements: {len([r for r in pk_renames if r.strip() and not r.strip().startswith('--')])}")

if __name__ == '__main__':
    main()
