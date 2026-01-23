#!/usr/bin/env python3
"""
Generate Toon Files Script (Python Version)

WOLFIE Headers v4.0.0:
- wolfie.headers.version: 4.0.0
- dialog.speaker: CURSOR
- dialog.target: @everyone
- dialog.message: "Updated generate_toon_files.py to generate .toon files instead of .json files. Files are TOON format, not JSON. See TOON_DOCTRINE.md."

Exports all database tables to TOON format files containing:
- table_name: The table name
- fields: Array of column definitions reconstructed from INFORMATION_SCHEMA
- data: Array of row data (limited to configurable rows per table)
- primary_key: Primary key metadata (lupo_* tables only) - current PK, expected PK, rename flag
- unsigned_columns: List of columns with UNSIGNED type (lupo_* tables only)
- indexes: List of indexes with names, columns, unique flags (lupo_* tables only)
- unique_constraints: List of unique constraints (lupo_* tables only)

Note: Files are saved with both .toon and .txt extensions (same content, TOON format, not JSON).
See docs/TOON_DOCTRINE.md for TOON format rules.

Usage: python database/generate_toon_files.py [--limit=5000]

Environment variables (optional):
- DB_HOST (default: localhost)
- DB_USER (default: root)
- DB_PASS (default: '')
- DB_NAME (default: lupopedia)

@version 1.2.0 (INFO_SCHEMA Edition - Migration Metadata)
@author Captain Wolfie

Version 1.2.0 Changes:
- Added primary key detection and expected PK name calculation
- Added UNSIGNED column detection
- Added index information extraction
- Added unique constraint extraction
- Metadata only added for lupo_* prefixed tables (livehelp_* tables ignored)
"""

import os
import sys
import json
import argparse
import base64
from datetime import datetime, date, time
from typing import List, Dict, Any, Optional
import mysql.connector
from mysql.connector import Error


def get_db_connection():
    """Create and return MySQL database connection."""
    host = os.getenv('DB_HOST', 'localhost')
    user = os.getenv('DB_USER', 'root')
    password = os.getenv('DB_PASS', 'ServBay.dev')
    database = os.getenv('DB_NAME', 'lupopedia')
    
    


    if not database:
        print("Error: DB_NAME is required.", file=sys.stderr)
        sys.exit(1)
    
    try:
        conn = mysql.connector.connect(
            host=host,
            user=user,
            password=password,
            database=database,
            charset='utf8mb4',
            collation='utf8mb4_unicode_ci'
        )
        return conn
    except Error as e:
        print(f"Database connection error: {e}", file=sys.stderr)
        print(f"Attempted connection: {user}@{host}/{database}", file=sys.stderr)
        sys.exit(1)


def get_column_definitions(cursor, db_name: str, table_name: str) -> List[str]:
    """
    Get column definitions using INFORMATION_SCHEMA.
    
    Args:
        cursor: MySQL cursor
        db_name: Database name
        table_name: Table name
    
    Returns:
        List of column definition strings
    """
    sql = """
        SELECT 
            CONCAT(
                '`', COLUMN_NAME, '` ',
                COLUMN_TYPE,
                IF(IS_NULLABLE = 'NO', ' NOT NULL', ''),
                IF(COLUMN_DEFAULT IS NOT NULL, 
                   CONCAT(' DEFAULT ', 
                          IF(COLUMN_DEFAULT = 'CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP', 
                             IF(DATA_TYPE IN ('char', 'varchar', 'text', 'tinytext', 'mediumtext', 'longtext', 'enum', 'set'), 
                                CONCAT(QUOTE(COLUMN_DEFAULT)), COLUMN_DEFAULT))),
                   ''),
                IF(EXTRA != '', CONCAT(' ', EXTRA), ''),
                IF(COLUMN_COMMENT != '', CONCAT(' COMMENT ', QUOTE(COLUMN_COMMENT)), '')
            ) AS def
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s
        ORDER BY ORDINAL_POSITION
    """
    
    cursor.execute(sql, (db_name, table_name))
    results = cursor.fetchall()
    # Handle both dictionary and tuple results
    if results and isinstance(results[0], dict):
        return [row['def'] for row in results]
    else:
        return [row[0] for row in results]


def json_safe_value(value: Any, column_type: str) -> Any:
    """
    Convert value to TOON-safe format (handle NULLs, binary, datetime, Decimal).
    
    Args:
        value: The value to convert
        column_type: Column type from schema (for binary check)
    
    Returns:
        TOON-safe value (uses JSON serialization but is TOON format)
    """
    if value is None:
        return None
    
    # Handle datetime objects
    if isinstance(value, (datetime, date, time)):
        return value.isoformat()
    
    # Handle Decimal objects (from mysql.connector)
    if hasattr(value, '__class__') and value.__class__.__name__ == 'Decimal':
        return float(value)
    
    # Base64 encode binary/blob types
    if column_type and ('blob' in column_type.lower() or 'binary' in column_type.lower()):
        if isinstance(value, bytes):
            return base64.b64encode(value).decode('utf-8')
        elif isinstance(value, (bytearray, memoryview)):
            return base64.b64encode(bytes(value)).decode('utf-8')
    
    # Handle bytes that aren't explicitly binary columns
    if isinstance(value, bytes):
        try:
            return value.decode('utf-8')
        except UnicodeDecodeError:
            return base64.b64encode(value).decode('utf-8')
    
    return value


def get_table_list(cursor, db_name: str) -> List[str]:
    """Get list of all tables in the database."""
    cursor.execute("SHOW TABLES")
    results = cursor.fetchall()
    # SHOW TABLES returns results as tuples or dicts depending on cursor type
    if results and isinstance(results[0], dict):
        # Dictionary cursor - get the first (and only) value from each row
        return [list(row.values())[0] for row in results]
    else:
        # Regular cursor - get first element of each tuple
        return [row[0] for row in results]


def extract_column_type_from_def(field_def: str, column_name: str) -> str:
    """
    Extract column type from field definition string.
    
    Args:
        field_def: Full column definition (e.g., "`actor_id` bigint NOT NULL")
        column_name: Column name to match
    
    Returns:
        Column type string or empty string
    """
    if field_def.startswith(f'`{column_name}`'):
        # Extract type part (everything after backtick and space, before NOT NULL/DEFAULT/etc)
        parts = field_def.split(' ', 2)
        if len(parts) >= 2:
            return parts[1]  # The type part
    return ''


def calculate_expected_pk_name(table_name: str) -> str:
    """
    Calculate expected primary key name: singular of table name + _id
    
    Examples:
        lupo_agent_registry → agent_registry_id (registry is already singular)
        lupo_anibus_redirects → anibus_redirect_id (redirects → redirect)
        lupo_collection_tabs → collection_tab_id (tabs → tab)
        lupo_truth_topics → truth_topic_id (topics → topic)
        lupo_user_comments → user_comment_id (comments → comment)
        lupo_content_likes → content_like_id (likes → like)
        lupo_actor_channel_roles → actor_channel_role_id (roles → role, not rol!)
        lupo_narrative_fragments → narrative_fragment_id (fragments → fragment)
        lupo_dialog_threads → dialog_thread_id (threads → thread)
        lupo_dialog_message_bodies → dialog_message_body_id (bodies → body)
        lupo_anibus_orphans → anibus_orphan_id (orphans → orphan)
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
    """
    Convert plural word to singular.
    
    Handles common English plural patterns:
    - roles → role (word + s)
    - likes → like (word + s)  
    - redirects → redirect (word + s, ends with "cts" not "ctes")
    - fragments → fragment (word + s, ends with "nts" not "ntes")
    - bodies → body (y → ies)
    - topics → topic (word + s)
    - tabs → tab (word + s)
    - threads → thread (word + s)
    - orphans → orphan (word + s)
    """
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
    
    # Rule 1: Words ending in "ies" → "y" (e.g., "bodies" → "body", "parties" → "party")
    if word.endswith('ies'):
        return word[:-3] + 'y'
    # Rule 2: Words ending in "es" (not "ies" or "ss") 
    # Check if removing just "s" makes more sense (roles → role, likes → like)
    elif word.endswith('es') and not word.endswith('ies') and not word.endswith('ss'):
        # Common pattern: words ending in "les", "kes", "tes", "ges" → remove "s" only
        # (roles → role, likes → like, rates → rate, pages → page)
        if word.endswith(('les', 'kes', 'tes', 'ges', 'mes', 'nes', 'pes', 'res', 'ses', 'ves', 'xes', 'zes')):
            # Check length - must be at least 3 chars after removing "s"
            if len(word) > 3:
                return word[:-1]  # Remove "s" only
        # For other "es" endings (redirects, fragments, messages) → remove "es"
        return word[:-2]  # Remove "es"
    # Rule 3: Words ending in "s" (not "ss" or "es") → remove "s" 
    # (e.g., "tabs" → "tab", "topics" → "topic", "comments" → "comment", "threads" → "thread", "orphans" → "orphan")
    elif word.endswith('s') and not word.endswith('ss') and not word.endswith('es'):
        return word[:-1]
    
    return word  # Return as-is if no plural detected


def get_primary_key(cursor, db_name: str, table_name: str) -> Optional[str]:
    """
    Get primary key column name from INFORMATION_SCHEMA.KEY_COLUMN_USAGE.
    
    Args:
        cursor: MySQL cursor
        db_name: Database name
        table_name: Table name
    
    Returns:
        Primary key column name, or None if no PK exists
    """
    sql = """
        SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = %s 
          AND TABLE_NAME = %s
          AND CONSTRAINT_NAME = 'PRIMARY'
        ORDER BY ORDINAL_POSITION
        LIMIT 1
    """
    cursor.execute(sql, (db_name, table_name))
    result = cursor.fetchone()
    
    if result:
        if isinstance(result, dict):
            return result.get('COLUMN_NAME')
        else:
            return result[0] if len(result) > 0 else None
    return None


def get_unsigned_columns(cursor, db_name: str, table_name: str) -> List[Dict[str, Any]]:
    """
    Get list of columns with UNSIGNED type from INFORMATION_SCHEMA.
    
    Args:
        cursor: MySQL cursor
        db_name: Database name
        table_name: Table name
    
    Returns:
        List of dictionaries with column_name, current_type, expected_type, is_primary_key
    """
    sql = """
        SELECT 
            COLUMN_NAME,
            COLUMN_TYPE,
            CASE 
                WHEN COLUMN_KEY = 'PRI' THEN 1 
                ELSE 0 
            END AS is_primary_key
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = %s 
          AND TABLE_NAME = %s
          AND COLUMN_TYPE LIKE '%unsigned%'
        ORDER BY ORDINAL_POSITION
    """
    cursor.execute(sql, (db_name, table_name))
    results = cursor.fetchall()
    
    unsigned_cols = []
    for row in results:
        if isinstance(row, dict):
            col_type = row['COLUMN_TYPE']
            # Remove UNSIGNED from type to get expected type
            expected_type = col_type.replace(' unsigned', '').replace('UNSIGNED', '').strip()
            unsigned_cols.append({
                "column_name": row['COLUMN_NAME'],
                "current_type": col_type,
                "expected_type": expected_type,
                "is_primary_key": bool(row['is_primary_key'])
            })
        else:
            col_type = row[1]
            expected_type = col_type.replace(' unsigned', '').replace('UNSIGNED', '').strip()
            unsigned_cols.append({
                "column_name": row[0],
                "current_type": col_type,
                "expected_type": expected_type,
                "is_primary_key": bool(row[2])
            })
    
    return unsigned_cols


def get_indexes(cursor, db_name: str, table_name: str) -> List[Dict[str, Any]]:
    """
    Get list of indexes for the table from INFORMATION_SCHEMA.STATISTICS.
    
    Args:
        cursor: MySQL cursor
        db_name: Database name
        table_name: Table name
    
    Returns:
        List of dictionaries with index_name, columns (list), is_unique, index_type
    """
    sql = """
        SELECT 
            INDEX_NAME,
            GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX SEPARATOR ',') AS columns,
            NON_UNIQUE,
            INDEX_TYPE
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = %s 
          AND TABLE_NAME = %s
          AND INDEX_NAME != 'PRIMARY'
        GROUP BY INDEX_NAME, NON_UNIQUE, INDEX_TYPE
        ORDER BY INDEX_NAME
    """
    cursor.execute(sql, (db_name, table_name))
    results = cursor.fetchall()
    
    indexes = []
    for row in results:
        if isinstance(row, dict):
            indexes.append({
                "index_name": row['INDEX_NAME'],
                "columns": row['columns'].split(',') if row['columns'] else [],
                "is_unique": row['NON_UNIQUE'] == 0,
                "index_type": row['INDEX_TYPE']
            })
        else:
            indexes.append({
                "index_name": row[0],
                "columns": row[1].split(',') if row[1] else [],
                "is_unique": row[2] == 0,
                "index_type": row[3]
            })
    
    return indexes


def get_unique_constraints(cursor, db_name: str, table_name: str) -> List[Dict[str, Any]]:
    """
    Get list of unique constraints for the table from INFORMATION_SCHEMA.TABLE_CONSTRAINTS and STATISTICS.
    
    Args:
        cursor: MySQL cursor
        db_name: Database name
        table_name: Table name
    
    Returns:
        List of dictionaries with constraint_name, columns (list)
    """
    sql = """
        SELECT 
            tc.CONSTRAINT_NAME,
            GROUP_CONCAT(kcu.COLUMN_NAME ORDER BY kcu.ORDINAL_POSITION SEPARATOR ',') AS columns
        FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
        JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
          ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
         AND tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA
         AND tc.TABLE_NAME = kcu.TABLE_NAME
        WHERE tc.TABLE_SCHEMA = %s 
          AND tc.TABLE_NAME = %s
          AND tc.CONSTRAINT_TYPE = 'UNIQUE'
          AND tc.CONSTRAINT_NAME != 'PRIMARY'
        GROUP BY tc.CONSTRAINT_NAME
        ORDER BY tc.CONSTRAINT_NAME
    """
    cursor.execute(sql, (db_name, table_name))
    results = cursor.fetchall()
    
    constraints = []
    for row in results:
        if isinstance(row, dict):
            constraints.append({
                "constraint_name": row['CONSTRAINT_NAME'],
                "columns": row['columns'].split(',') if row['columns'] else []
            })
        else:
            constraints.append({
                "constraint_name": row[0],
                "columns": row[1].split(',') if row[1] else []
            })
    
    return constraints


def main():
    """Main execution."""
    parser = argparse.ArgumentParser(description='Generate toon files from database tables')
    parser.add_argument('--limit', type=int, default=1000, 
                       help='Maximum rows to export per table (default: 1000)')
    args = parser.parse_args()
    
    row_limit = args.limit
    db_name = os.getenv('DB_NAME', 'lupopedia')
    db_host = os.getenv('DB_HOST', 'localhost')
    output_dir = os.path.join(os.path.dirname(__file__), 'toon_data')
    
    try:
        print(f"=== Toon File Generator v1.2.0 (INFO_SCHEMA Edition - Migration Metadata) ===")
        print(f"Connecting to database: {db_name}@{db_host} (Limit: {row_limit} rows/table)")
        print(f"Note: Only lupo_* tables get migration metadata (livehelp_* tables ignored)")
        
        # Connect to database
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        
        # Get current database name
        cursor.execute("SELECT DATABASE()")
        current_db = cursor.fetchone()['DATABASE()']
        if not current_db:
            current_db = db_name
        
        # Create output directory if it doesn't exist
        if not os.path.isdir(output_dir):
            os.makedirs(output_dir, mode=0o755, exist_ok=True)
            print(f"Created output directory: {output_dir}")
        
        # Get list of all tables (filter to lupo_* tables only for metadata extraction)
        print("\nFetching table list...")
        try:
            all_tables = get_table_list(cursor, current_db)
            # Only process lupo_* tables for metadata (livehelp_* are old Crafty Syntax tables)
            # But generate TOON files for all tables
            tables = all_tables
        except Exception as e:
            print(f"Error fetching table list: {e}", file=sys.stderr)
            print(f"Database: {current_db}", file=sys.stderr)
            cursor.close()
            conn.close()
            sys.exit(1)
        
        if not tables:
            print("No tables found in database.")
            cursor.close()
            conn.close()
            sys.exit(0)
        
        table_count = len(tables)
        print(f"Found {table_count} table(s)\n")
        
        success_count = 0
        error_count = 0
        skipped_tables = []
        error_tables = []
        
        # Process each table
        for table_name in tables:
            print(f"Processing: {table_name}... ", end='', flush=True)
            
            try:
                # Get column definitions
                field_defs = get_column_definitions(cursor, current_db, table_name)
                
                if not field_defs:
                    print("WARNING: No fields found, skipping...")
                    skipped_tables.append(table_name)
                    error_count += 1
                    continue
                
                # Get full row count
                cursor.execute(f"SELECT COUNT(*) as cnt FROM `{table_name}`")
                total_rows = cursor.fetchone()['cnt']
                
                # Get table data (limited)
                cursor.execute(f"SELECT * FROM `{table_name}` LIMIT {row_limit}")
                data = cursor.fetchall()
                
                # Convert data to TOON-safe format
                toon_data_rows = []
                for row in data:
                    toon_row = {}
                    for key, value in row.items():
                        # Find column type from field definitions (rough but works)
                        col_type = ''
                        for field_def in field_defs:
                            if field_def.startswith(f'`{key}`'):
                                # Extract type part
                                parts = field_def.split(' ', 2)
                                if len(parts) >= 2:
                                    col_type = parts[1]
                                break
                        
                        toon_row[key] = json_safe_value(value, col_type)
                    toon_data_rows.append(toon_row)
                
                # Get metadata for migration support (only for lupo_* tables)
                primary_key_info = {}
                unsigned_columns = []
                indexes = []
                unique_constraints = []
                
                if table_name.startswith('lupo_'):
                    # Get primary key
                    current_pk = get_primary_key(cursor, current_db, table_name)
                    expected_pk = calculate_expected_pk_name(table_name) if current_pk else None
                    
                    if current_pk:
                        primary_key_info = {
                            "column_name": current_pk,
                            "expected_name": expected_pk,
                            "is_correct": current_pk == expected_pk,
                            "needs_rename": current_pk != expected_pk
                        }
                    
                    # Get unsigned columns
                    unsigned_columns = get_unsigned_columns(cursor, current_db, table_name)
                    
                    # Get indexes (optional but helpful)
                    try:
                        indexes = get_indexes(cursor, current_db, table_name)
                    except Exception as e:
                        # Indexes are optional, don't fail if there's an error
                        print(f"  [WARNING: Could not fetch indexes: {e}]", end='', flush=True)
                    
                    # Get unique constraints (optional but helpful)
                    try:
                        unique_constraints = get_unique_constraints(cursor, current_db, table_name)
                    except Exception as e:
                        # Unique constraints are optional, don't fail if there's an error
                        print(f"  [WARNING: Could not fetch unique constraints: {e}]", end='', flush=True)
                
                # Build TOON structure
                toon_data = {
                    'table_name': table_name,
                    'fields': field_defs,
                    'data': toon_data_rows
                }
                
                # Add metadata for lupo_* tables only
                if table_name.startswith('lupo_'):
                    if primary_key_info:
                        toon_data['primary_key'] = primary_key_info
                    if unsigned_columns:
                        toon_data['unsigned_columns'] = unsigned_columns
                    if indexes:
                        toon_data['indexes'] = indexes
                    if unique_constraints:
                        toon_data['unique_constraints'] = unique_constraints
                
                # Write to TOON file
                output_file = os.path.join(output_dir, f'{table_name}.toon')
                with open(output_file, 'w', encoding='utf-8') as f:
                    json.dump(toon_data, f, indent=4, ensure_ascii=False)
                
                # Also write to TXT file (same content, different extension)
                output_file_txt = os.path.join(output_dir, f'{table_name}.txt')
                with open(output_file_txt, 'w', encoding='utf-8') as f:
                    json.dump(toon_data, f, indent=4, ensure_ascii=False)
                
                fetched_rows = len(toon_data_rows)
                trunc_msg = f" (truncated {fetched_rows}/{total_rows})" if fetched_rows < total_rows else ''
                metadata_msg = ""
                if table_name.startswith('lupo_'):
                    meta_parts = []
                    if primary_key_info:
                        meta_parts.append(f"PK={primary_key_info['column_name']}")
                        if primary_key_info['needs_rename']:
                            meta_parts.append(f"→{primary_key_info['expected_name']}")
                    if unsigned_columns:
                        meta_parts.append(f"{len(unsigned_columns)} UNSIGNED")
                    if indexes:
                        meta_parts.append(f"{len(indexes)} indexes")
                    if meta_parts:
                        metadata_msg = f" [{', '.join(meta_parts)}]"
                print(f"OK ({fetched_rows} rows{trunc_msg}, {len(field_defs)} fields{metadata_msg})")
                success_count += 1
                
            except Exception as e:
                import traceback
                print(f"ERROR: {str(e)}")
                print(f"Traceback: {traceback.format_exc()}", file=sys.stderr)
                error_tables.append((table_name, str(e)))
                error_count += 1
        
        # Summary
        print("\n=== Summary ===")
        print(f"Total tables found: {table_count}")
        print(f"Success: {success_count} table(s)")
        print(f"Errors/Skipped: {error_count} table(s)")
        print(f"Output directory: {output_dir}")
        
        if skipped_tables:
            print(f"\n[WARNING] Tables skipped (no fields): {', '.join(skipped_tables)}")
        if error_tables:
            print(f"\n[ERROR] Tables with errors:")
            for table_name, error_msg in error_tables:
                print(f"   - {table_name}: {error_msg}")
        
        # Check for missing tables
        if success_count + error_count < table_count:
            missing = table_count - (success_count + error_count)
            print(f"\n[WARNING] {missing} table(s) were not processed!")
        
        # Verify output files match input tables
        print("\n=== Verification ===")
        existing_toon_files = set()
        existing_txt_files = set()
        if os.path.isdir(output_dir):
            for filename in os.listdir(output_dir):
                if filename.endswith('.toon'):
                    existing_toon_files.add(filename[:-5])  # Remove .toon extension
                elif filename.endswith('.txt'):
                    existing_txt_files.add(filename[:-4])  # Remove .txt extension
        
        # Compare database tables with generated files
        db_tables_set = set(tables)
        missing_toon_files = db_tables_set - existing_toon_files
        missing_txt_files = db_tables_set - existing_txt_files
        extra_toon_files = existing_toon_files - db_tables_set
        extra_txt_files = existing_txt_files - db_tables_set
        
        if missing_toon_files:
            print(f"[WARNING] Missing .toon files ({len(missing_toon_files)}): {', '.join(sorted(missing_toon_files))}")
        if missing_txt_files:
            print(f"[WARNING] Missing .txt files ({len(missing_txt_files)}): {', '.join(sorted(missing_txt_files))}")
        if extra_toon_files:
            print(f"[WARNING] Extra .toon files (not in DB) ({len(extra_toon_files)}): {', '.join(sorted(extra_toon_files))}")
        if extra_txt_files:
            print(f"[WARNING] Extra .txt files (not in DB) ({len(extra_txt_files)}): {', '.join(sorted(extra_txt_files))}")
        if not missing_toon_files and not missing_txt_files and not extra_toon_files and not extra_txt_files:
            print(f"[OK] All {len(tables)} tables have corresponding .toon and .txt files")
        
        print("\nDone!")
        
        cursor.close()
        conn.close()
        
    except Error as e:
        print(f"Database error: {e}", file=sys.stderr)
        sys.exit(1)
    except Exception as e:
        print(f"Error: {e}", file=sys.stderr)
        sys.exit(1)


if __name__ == '__main__':
    main()

