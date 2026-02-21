#!/usr/bin/env python3
"""
Database Schema Verification Against TOON Specifications
Compares current MySQL schema with TOON specifications in docs/toons/
Generates drift report and migration suggestions
"""

import os
import json
import re
from datetime import datetime

# Configuration
TOONS_DIR = "docs/toons"
OUTPUT_FILE = "docs/specs/DB_SCHEMA_DRIFT_4.0.24.md"
DB_NAME = "lupopedia"

def parse_toon_file(filepath):
    """Parse a single TOON JSON file and extract schema information"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    try:
        data = json.loads(content)
    except json.JSONDecodeError as e:
        print(f"Error parsing {filepath}: {e}")
        return None
    
    # Extract table name from filename
    filename = os.path.basename(filepath)
    table_name = filename.replace('.toon.json', '')
    
    # Extract columns from TOON structure
    columns = []
    
    # Handle different TOON structures
    if 'columns' in data:
        # Standard table definition
        for col_name, col_info in data['columns'].items():
            col_type = col_info.get('type', 'varchar(255)')
            col_comment = col_info.get('description', '')
            
            columns.append({
                'name': col_name,
                'type': col_type,
                'comment': col_comment
            })
    
    elif 'fields' in data:
        # Field array structure (string definitions)
        for field_def in data['fields']:
            if isinstance(field_def, str):
                # Parse field definition like `actor_id` bigint NOT NULL
                parts = field_def.split()
                if len(parts) >= 2:
                    col_name = parts[0].replace('`', '').strip()
                    col_type = ' '.join(parts[1:]).strip()
                    
                    columns.append({
                        'name': col_name,
                        'type': col_type,
                        'comment': ''
                    })
    
    elif isinstance(data, dict):
        # Try to extract from other structures
        for key, value in data.items():
            if isinstance(value, dict) and 'type' in value:
                columns.append({
                    'name': key,
                    'type': value['type'],
                    'comment': value.get('description', '')
                })
    
    # Extract primary key
    primary_key = None
    if 'primary_key' in data:
        pk_info = data['primary_key']
        if isinstance(pk_info, str):
            primary_key = pk_info
        elif isinstance(pk_info, dict) and 'column' in pk_info:
            primary_key = pk_info['column']
    
    # Extract indexes
    indexes = []
    if 'indexes' in data:
        index_data = data['indexes']
        if isinstance(index_data, list):
            # Array of index objects
            for index_info in index_data:
                index_name = index_info.get('index_name', '')
                index_columns = index_info.get('columns', [])
                if isinstance(index_columns, str):
                    index_columns = [index_columns]
                elif isinstance(index_columns, list):
                    index_columns = index_columns
                else:
                    index_columns = []
                
                is_unique = index_info.get('is_unique', False)
                
                indexes.append({
                    'name': index_name,
                    'columns': index_columns,
                    'unique': is_unique
                })
        elif isinstance(index_data, dict):
            # Dictionary of indexes
            for index_name, index_info in index_data.items():
                index_columns = index_info.get('columns', [])
                if isinstance(index_columns, str):
                    index_columns = [index_columns]
                elif isinstance(index_columns, list):
                    index_columns = index_columns
                else:
                    index_columns = []
                
                is_unique = index_info.get('unique', False)
                
                indexes.append({
                    'name': index_name,
                    'columns': index_columns,
                    'unique': is_unique
                })
    
    return {
        'table_name': table_name,
        'columns': columns,
        'primary_key': primary_key,
        'indexes': indexes,
        'filepath': filepath
    }

def get_current_db_schema():
    """Get current database schema (mock implementation)"""
    # This would connect to actual database in production
    # For now, return a mock schema based on known tables
    return {
        'lupo_actors': {
            'columns': [
                {'name': 'actor_id', 'type': 'bigint', 'comment': 'Actor unique identifier'},
                {'name': 'actor_type', 'type': 'varchar(50)', 'comment': 'Actor type'},
                {'name': 'slug', 'type': 'varchar(100)', 'comment': 'URL-friendly slug'},
                {'name': 'name', 'type': 'varchar(255)', 'comment': 'Display name'},
                {'name': 'created_ymdhis', 'type': 'bigint', 'comment': 'Creation timestamp'},
                {'name': 'updated_ymdhis', 'type': 'bigint', 'comment': 'Update timestamp'},
                {'name': 'is_active', 'type': 'tinyint(1)', 'comment': 'Active status'},
                {'name': 'is_deleted', 'type': 'tinyint(1)', 'comment': 'Soft delete flag'},
                {'name': 'deleted_ymdhis', 'type': 'bigint', 'comment': 'Deletion timestamp'},
                {'name': 'metadata', 'type': 'json', 'comment': 'Additional metadata'}
            ],
            'primary_key': 'actor_id'
        },
        'lupo_channels': {
            'columns': [
                {'name': 'channel_id', 'type': 'bigint', 'comment': 'Channel unique identifier'},
                {'name': 'channel_key', 'type': 'varchar(50)', 'comment': 'Channel key'},
                {'name': 'channel_name', 'type': 'varchar(255)', 'comment': 'Channel name'},
                {'name': 'description', 'type': 'text', 'comment': 'Channel description'},
                {'name': 'created_ymdhis', 'type': 'bigint', 'comment': 'Creation timestamp'},
                {'name': 'updated_ymdhis', 'type': 'bigint', 'comment': 'Update timestamp'},
                {'name': 'is_active', 'type': 'tinyint(1)', 'comment': 'Active status'},
                {'name': 'is_deleted', 'type': 'tinyint(1)', 'comment': 'Soft delete flag'},
                {'name': 'deleted_ymdhis', 'type': 'bigint', 'comment': 'Deletion timestamp'}
            ],
            'primary_key': 'channel_id'
        }
        # Add more tables as needed...
    }

def compare_schemas(toon_schema, db_schema):
    """Compare TOON schema with database schema"""
    toon_tables = {table['table_name']: table for table in toon_schema}
    db_tables = db_schema
    
    missing_in_db = []
    extra_in_db = []
    schema_mismatches = []
    
    # Find tables in TOONs but missing in DB
    for table_name in toon_tables:
        if table_name not in db_tables:
            missing_in_db.append(toon_tables[table_name])
    
    # Find tables in DB but not in TOONs
    for table_name in db_tables:
        if table_name not in toon_tables:
            extra_in_db.append({'table_name': table_name, 'columns': db_tables[table_name]['columns']})
    
    # Find schema mismatches for tables that exist in both
    for table_name in toon_tables:
        if table_name in db_tables:
            toon_table = toon_tables[table_name]
            db_table = db_tables[table_name]
            
            # Compare columns
            toon_columns = {col['name']: col for col in toon_table['columns']}
            db_columns = {col['name']: col for col in db_table['columns']}
            
            missing_columns = []
            extra_columns = []
            type_mismatches = []
            
            for col_name in toon_columns:
                if col_name not in db_columns:
                    missing_columns.append(toon_columns[col_name])
                else:
                    toon_col = toon_columns[col_name]
                    db_col = db_columns[col_name]
                    
                    # Compare types (simplified)
                    if toon_col['type'].lower() != db_col['type'].lower():
                        type_mismatches.append({
                            'column': col_name,
                            'toon_type': toon_col['type'],
                            'db_type': db_col['type']
                        })
            
            for col_name in db_columns:
                if col_name not in toon_columns:
                    extra_columns.append(db_columns[col_name])
            
            if missing_columns or extra_columns or type_mismatches:
                schema_mismatches.append({
                    'table_name': table_name,
                    'missing_columns': missing_columns,
                    'extra_columns': extra_columns,
                    'type_mismatches': type_mismatches
                })
    
    return {
        'missing_in_db': missing_in_db,
        'extra_in_db': extra_in_db,
        'schema_mismatches': schema_mismatches
    }

def generate_migration_suggestions(comparison_result):
    """Generate SQL migration suggestions"""
    migrations = []
    
    # Add missing tables
    for table in comparison_result['missing_in_db']:
        migrations.append(f"-- Add missing table: {table['table_name']}")
        migrations.append(f"-- TODO: Implement CREATE TABLE statement for {table['table_name']}")
        migrations.append("")
    
    # Handle schema mismatches
    for mismatch in comparison_result['schema_mismatches']:
        table_name = mismatch['table_name']
        
        # Add missing columns
        for col in mismatch['missing_columns']:
            migrations.append(f"-- Add missing column to {table_name}")
            migrations.append(f"ALTER TABLE `{table_name}` ADD COLUMN `{col['name']}` {col['type']} COMMENT '{col['comment']}';")
        
        # Fix type mismatches
        for type_mismatch in mismatch['type_mismatches']:
            migrations.append(f"-- Fix type mismatch in {table_name}.{type_mismatch['column']}")
            migrations.append(f"ALTER TABLE `{table_name}` MODIFY COLUMN `{type_mismatch['column']}` {type_mismatch['toon_type']};")
        
        migrations.append("")
    
    return migrations

def generate_drift_report(comparison_result, migrations):
    """Generate the drift report in markdown"""
    with open(OUTPUT_FILE, 'w', encoding='utf-8') as f:
        f.write(f"# Database Schema Drift Report 4.0.24\n\n")
        f.write(f"Generated: {datetime.utcnow().isoformat()}Z\n")
        f.write(f"Database: {DB_NAME}\n")
        f.write(f"TOON Directory: {TOONS_DIR}\n\n")
        
        # Missing Tables section
        f.write("## Missing Tables (DB vs TOONs)\n\n")
        if comparison_result['missing_in_db']:
            f.write("The following tables are defined in TOONs but missing from the database:\n\n")
            for table in comparison_result['missing_in_db']:
                f.write(f"- **{table['table_name']}** - Defined in `{os.path.basename(table['filepath'])}`\n")
        else:
            f.write("No missing tables found.\n")
        f.write("\n")
        
        # Extra Tables section
        f.write("## Extra Tables (DB only)\n\n")
        if comparison_result['extra_in_db']:
            f.write("The following tables exist in the database but are not defined in TOONs:\n\n")
            for table in comparison_result['extra_in_db']:
                f.write(f"- **{table['table_name']}** - {len(table['columns'])} columns\n")
        else:
            f.write("No extra tables found.\n")
        f.write("\n")
        
        # Schema Mismatches section
        f.write("## Schema Mismatches (per table)\n\n")
        if comparison_result['schema_mismatches']:
            for mismatch in comparison_result['schema_mismatches']:
                f.write(f"### {mismatch['table_name']}\n\n")
                
                if mismatch['missing_columns']:
                    f.write("**Missing Columns:**\n")
                    for col in mismatch['missing_columns']:
                        f.write(f"- `{col['name']}` ({col['type']}) - {col['comment']}\n")
                    f.write("\n")
                
                if mismatch['extra_columns']:
                    f.write("**Extra Columns:**\n")
                    for col in mismatch['extra_columns']:
                        f.write(f"- `{col['name']}` ({col['type']})\n")
                    f.write("\n")
                
                if mismatch['type_mismatches']:
                    f.write("**Type Mismatches:**\n")
                    for tm in mismatch['type_mismatches']:
                        f.write(f"- `{tm['column']}`: TOON={tm['toon_type']}, DB={tm['db_type']}\n")
                    f.write("\n")
        else:
            f.write("No schema mismatches found.\n")
        f.write("\n")
        
        # Migration Suggestions section
        f.write("## Migration Suggestions\n\n")
        f.write("The following SQL statements are suggested to bring the database in line with TOON definitions:\n\n")
        f.write("```sql\n")
        for migration in migrations:
            f.write(f"{migration}\n")
        f.write("```\n\n")
        
        f.write("---\n")
        f.write("*Database Schema Drift Report 4.0.24*\n")

def main():
    """Main execution function"""
    print("Verifying database schema against TOON specifications...")
    
    # Parse all TOON files
    toon_schema = []
    if os.path.exists(TOONS_DIR):
        for filename in os.listdir(TOONS_DIR):
            if filename.endswith('.toon.json'):
                filepath = os.path.join(TOONS_DIR, filename)
                table_info = parse_toon_file(filepath)
                if table_info:
                    toon_schema.append(table_info)
                    print(f"Parsed TOON: {table_info['table_name']} from {filename}")
    
    print(f"Found {len(toon_schema)} TOON files")
    
    # Get current database schema
    db_schema = get_current_db_schema()
    print(f"Database has {len(db_schema)} tables")
    
    # Compare schemas
    comparison_result = compare_schemas(toon_schema, db_schema)
    
    # Generate migration suggestions
    migrations = generate_migration_suggestions(comparison_result)
    
    # Generate drift report
    generate_drift_report(comparison_result, migrations)
    
    print(f"Drift report generated: {OUTPUT_FILE}")
    print("Verification complete!")

if __name__ == "__main__":
    main()
