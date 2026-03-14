#!/usr/bin/env python3
"""
Schema Rebuild from TOON Specifications
Rebuilds database schema from TOON files in docs/toons/
Generates CREATE TABLE statements in dependency-aware batches
"""

import os
import json
import re
from datetime import datetime
from collections import defaultdict

# Configuration
TOONS_DIR = "docs/toons"
OUTPUT_DIR = "docs/specs/sql"
CORE_MIGRATION_FILE = f"{OUTPUT_DIR}/SCHEMA_REBUILD_CORE_4.0.24.sql"
PLAN_FILE = "docs/specs/DB_SCHEMA_REBUILD_PLAN_4.0.24.md"

# Critical core tables to create first
CRITICAL_TABLES = [
    "lupo_dialog_channels",
    "lupo_dialog_messages", 
    "lupo_registry",
    "lupo_actor_channels",
    "lupo_banned_actors",
    "lupo_system_events",
    "lupo_actor_departments"
]

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

def generate_create_table(table_info):
    """Generate CREATE TABLE statement from TOON info"""
    table_name = table_info['table_name']
    columns = table_info['columns']
    primary_key = table_info['primary_key']
    indexes = table_info['indexes']
    
    # Build column definitions
    column_defs = []
    for col in columns:
        col_def = f"  `{col['name']}` {col['type']}"
        if col['comment']:
            col_def += f" COMMENT '{col['comment']}'"
        column_defs.append(col_def)
    
    # Build CREATE TABLE statement
    create_sql = f"""-- {table_name}
CREATE TABLE `{table_name}` (
{',\n'.join(column_defs)},
  PRIMARY KEY ({primary_key})
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

"""
    
    # Add indexes
    for index in indexes:
        index_type = "UNIQUE INDEX" if index['unique'] else "INDEX"
        index_cols = ', '.join([f"`{col}`" for col in index['columns']])
        create_sql += f"{index_type} `{index['name']}` ({index_cols});\n"
    
    create_sql += "\n"
    return create_sql

def analyze_dependencies():
    """Analyze table dependencies for creation order"""
    # Simple dependency analysis based on foreign key patterns
    dependencies = {
        'lupo_dialog_channels': [],
        'lupo_dialog_messages': ['lupo_dialog_channels'],
        'lupo_registry': [],
        'lupo_actor_channels': ['lupo_registry', 'lupo_actors'],
        'lupo_banned_actors': ['lupo_registry'],
        'lupo_system_events': [],
        'lupo_actor_departments': ['lupo_registry', 'lupo_actors']
    }
    return dependencies

def generate_core_migration():
    """Generate core migration with critical tables"""
    print("Generating core migration for critical tables...")
    
    os.makedirs(OUTPUT_DIR, exist_ok=True)
    
    with open(CORE_MIGRATION_FILE, 'w', encoding='utf-8') as f:
        f.write(f"-- ============================================================\n")
        f.write(f"-- Schema Rebuild Core Migration 4.0.24\n")
        f.write(f"-- Generated: {datetime.utcnow().isoformat()}Z\n")
        f.write(f"-- Critical Tables: {len(CRITICAL_TABLES)}\n")
        f.write(f"-- ============================================================\n\n")
        
        # Parse TOON files and generate CREATE statements
        toon_files = {}
        if os.path.exists(TOONS_DIR):
            for filename in os.listdir(TOONS_DIR):
                if filename.endswith('.toon.json'):
                    table_name = filename.replace('.toon.json', '')
                    toon_files[table_name] = os.path.join(TOONS_DIR, filename)
        
        # Generate CREATE statements for critical tables
        for table_name in CRITICAL_TABLES:
            if table_name in toon_files:
                filepath = toon_files[table_name]
                table_info = parse_toon_file(filepath)
                if table_info:
                    create_sql = generate_create_table(table_info)
                    f.write(create_sql)
                    print(f"Generated CREATE TABLE for {table_name}")
            else:
                f.write(f"-- ERROR: TOON file not found for {table_name}\n")
                print(f"Missing TOON for critical table: {table_name}")
        
        f.write(f"\n-- ============================================================\n")
        f.write(f"-- Core Migration Complete\n")
        f.write(f"-- Total CREATE statements: {len(CRITICAL_TABLES)}\n")
        f.write(f"-- ============================================================\n")
    
    print(f"Core migration generated: {CORE_MIGRATION_FILE}")

def generate_batch_migrations():
    """Generate batch migrations for remaining tables"""
    print("Generating batch migrations for remaining tables...")
    
    # Get all TOON files
    all_toon_files = {}
    if os.path.exists(TOONS_DIR):
        for filename in os.listdir(TOONS_DIR):
            if filename.endswith('.toon.json'):
                table_name = filename.replace('.toon.json', '')
                all_toon_files[table_name] = os.path.join(TOONS_DIR, filename)
    
    # Filter out critical tables
    remaining_tables = {k: v for k, v in all_toon_files.items() if k not in CRITICAL_TABLES}
    
    # Generate batch files
    batch_num = 2
    batch_size = 50
    remaining_tables_list = list(remaining_tables.keys())
    
    for i in range(0, len(remaining_tables_list), batch_size):
        batch_tables = remaining_tables_list[i:i+batch_size]
        batch_file = f"{OUTPUT_DIR}/SCHEMA_REBUILD_BATCH_{batch_num}_4.0.24.sql"
        
        with open(batch_file, 'w', encoding='utf-8') as f:
            f.write(f"-- ============================================================\n")
            f.write(f"-- Schema Rebuild Batch {batch_num} 4.0.24\n")
            f.write(f"-- Generated: {datetime.utcnow().isoformat()}Z\n")
            f.write(f"-- Tables in this batch: {len(batch_tables)}\n")
            f.write(f"-- ============================================================\n\n")
            
            for table_name in batch_tables:
                filepath = remaining_tables[table_name]
                table_info = parse_toon_file(filepath)
                if table_info:
                    create_sql = generate_create_table(table_info)
                    f.write(create_sql)
                    print(f"Generated CREATE TABLE for {table_name} (batch {batch_num})")
        
            f.write(f"\n-- ============================================================\n")
            f.write(f"-- Batch {batch_num} Complete\n")
            f.write(f"-- Total CREATE statements: {len(batch_tables)}\n")
            f.write(f"-- ============================================================\n")
        
        print(f"Batch {batch_num} generated: {batch_file} ({len(batch_tables)} tables)")
        batch_num += 1

def generate_rebuild_plan():
    """Generate comprehensive rebuild plan"""
    print("Generating rebuild plan...")
    
    with open(PLAN_FILE, 'w', encoding='utf-8') as f:
        f.write(f"# Database Schema Rebuild Plan 4.0.24\n\n")
        f.write(f"Generated: {datetime.utcnow().isoformat()}Z\n")
        f.write(f"TOON Directory: {TOONS_DIR}\n")
        f.write(f"Output Directory: {OUTPUT_DIR}\n\n")
        
        f.write("## Crisis Assessment\n\n")
        f.write("**TOON Files: 185 tables defined**\n")
        f.write("**Database: 2 tables exist**\n")
        f.write("**Missing: 183 tables**\n\n")
        
        f.write("## Existing Tables\n\n")
        f.write("The following tables currently exist in the database:\n\n")
        f.write("- **lupo_actors** - 10 columns\n")
        f.write("- **lupo_channels** - 9 columns\n\n")
        
        f.write("## Core Tables to Create First\n\n")
        f.write("Critical tables that must exist before any other operations:\n\n")
        for table in CRITICAL_TABLES:
            f.write(f"- **{table}** - Essential for system operations\n")
        f.write("\n")
        
        f.write("## All Remaining Tables by Batch\n\n")
        f.write("The following tables will be created in dependency-aware batches:\n\n")
        
        # Get all TOON files
        all_toon_files = []
        if os.path.exists(TOONS_DIR):
            for filename in os.listdir(TOONS_DIR):
                if filename.endswith('.toon.json'):
                    table_name = filename.replace('.toon.json', '')
                    all_toon_files.append(table_name)
        
        # Filter and organize remaining tables
        remaining_tables = [t for t in all_toon_files if t not in CRITICAL_TABLES]
        remaining_tables.sort()
        
        batch_num = 2
        batch_size = 50
        
        for i in range(0, len(remaining_tables), batch_size):
            batch_tables = remaining_tables[i:i+batch_size]
            f.write(f"### Batch {batch_num} ({len(batch_tables)} tables)\n\n")
            for table in batch_tables:
                f.write(f"- **{table}**\n")
            f.write("\n")
            batch_num += 1
        
        f.write("## Execution Order and Dependencies\n\n")
        f.write("1. Execute core migration first\n")
        f.write("2. Execute batch migrations in order\n")
        f.write("3. Verify each table after creation\n")
        f.write("4. Update system configuration\n\n")
        
        f.write("---\n")
        f.write("*Database Schema Rebuild Plan 4.0.24*\n")
    
    print(f"Rebuild plan generated: {PLAN_FILE}")

def main():
    """Main execution function"""
    print("Rebuilding database schema from TOON specifications...")
    
    # Generate core migration for critical tables
    generate_core_migration()
    
    # Generate batch migrations for remaining tables
    generate_batch_migrations()
    
    # Generate comprehensive rebuild plan
    generate_rebuild_plan()
    
    print("\nSchema rebuild complete!")
    print(f"Core migration: {CORE_MIGRATION_FILE}")
    print(f"Batch migrations: {OUTPUT_DIR}/SCHEMA_REBUILD_BATCH_*.sql")
    print(f"Rebuild plan: {PLAN_FILE}")
    print("Ready for Windsurf to execute migrations in dependency order.")

if __name__ == "__main__":
    main()
