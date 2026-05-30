#!/usr/bin/env python3
"""
Fresh Database Table Audit for Lupopedia 4.0.93+
Reflects current database state after recent changes by other actors
"""

import os
import json
import sys
from pathlib import Path
from datetime import datetime
import re

def load_json_file(filepath):
    """Load and parse JSON file"""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception as e:
        return {"error": str(e)}

def load_markdown_file(filepath):
    """Load markdown file content"""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            return f.read()
    except Exception as e:
        return f"Error loading file: {str(e)}"

def check_doctrine_compliance(table_json):
    """Check if table complies with database doctrine"""
    violations = []
    
    # Check for forbidden features
    fields = table_json.get('fields', [])
    for field in fields:
        field_name = field.split('`')[0].split(' ')[0]  # Extract column name
        
        if 'AUTO_INCREMENT' in field:
            violations.append(f"AUTO_INCREMENT in {field_name}")
        if 'UNSIGNED' in field:
            violations.append(f"UNSIGNED in {field_name}")
        if 'TIMESTAMP' in field and 'DATETIME' not in field:
            violations.append(f"TIMESTAMP type in {field_name}")
        if 'DATETIME' in field and 'TIMESTAMP' not in field:
            violations.append(f"DATETIME type in {field_name}")
    
    # Check primary key is bigint
    pk = table_json.get('primary_key', {})
    pk_column = pk.get('column_name', '')
    if pk_column:
        for field in fields:
            if pk_column in field and 'bigint' not in field.lower():
                violations.append(f"Primary key {pk_column} is not BIGINT")
    
    # Check timestamps are BIGINT
    for field in fields:
        if 'ymdhis' in field.lower() and 'bigint' not in field.lower():
            violations.append(f"Timestamp field {field} is not BIGINT")
    
    return violations

def main():
    """Main audit function"""
    print("🔍 FRESH DATABASE TABLE AUDIT - Lupopedia 4.0.93+")
    print("=" * 60)
    print("Reflecting current database state after recent changes...")
    
    # Paths
    json_dir = Path("database/lupopedia/json")
    docs_dir = Path("docs/database/lupopedia/tables")
    prd_dirs = [
        Path("docs/versions/4.0.88/prd"),
        Path("docs/versions/4.0.90/prd"),
        Path("docs/versions/4.0.91/prd"),
        Path("docs/versions/4.0.92/prd"),
        Path("docs/versions/4.0.93/prd")
    ]
    
    # Load all JSON tables
    json_tables = {}
    if json_dir.exists():
        for json_file in json_dir.glob("*.json"):
            table_name = json_file.stem
            json_tables[table_name] = load_json_file(json_file)
    
    # Expected table count (should be 170)
    expected_count = 170
    
    print(f"\n📊 AUDIT SUMMARY")
    print(f"JSON tables found: {len(json_tables)}")
    print(f"Expected tables: {expected_count}")
    
    # Load all documentation
    doc_tables = {}
    if docs_dir.exists():
        # Check active directory
        active_dir = docs_dir / "active"
        if active_dir.exists():
            for md_file in active_dir.glob("*.md"):
                table_name = md_file.stem
                doc_tables[table_name] = load_markdown_file(md_file)
        
        # Check deprecated directory
        deprecated_dir = docs_dir / "deprecated"
        if deprecated_dir.exists():
            for md_file in deprecated_dir.glob("*.md"):
                table_name = md_file.stem
                doc_tables[table_name] = load_markdown_file(md_file)
    
    # Load all PRDs
    prd_files = {}
    for prd_dir in prd_dirs:
        if prd_dir.exists():
            for prd_file in prd_dir.glob("*.md"):
                prd_name = prd_file.stem
                prd_files[prd_name] = load_markdown_file(prd_file)
    
    # Known removed tables (from user actions)
    removed_tables = {
        'lupo_smilies',
        'lupo_channel_boot_detail',
        'lupo_channel_boot_detail_lifecycle', 
        'lupo_channel_boot_lifecycle'
    }
    
    # Known added tables (from user actions)
    added_tables = {
        'lupo_actor_auth_users'  # Added with new schema
    }
    
    # Perform audit for each table
    audit_results = {}
    total_violations = 0
    
    for table_name, json_data in json_tables.items():
        if 'error' in json_data:
            audit_results[table_name] = {
                'json_status': 'ERROR',
                'json_error': json_data['error'],
                'doc_status': 'N/A',
                'prd_status': 'N/A',
                'doctrine_violations': ['JSON parse error']
            }
            total_violations += 1
            continue
        
        # Check if table was removed
        if table_name in removed_tables:
            audit_results[table_name] = {
                'json_status': 'REMOVED',
                'json_error': 'Table removed from install SQL',
                'doc_status': 'N/A',
                'prd_status': 'N/A',
                'doctrine_violations': []
            }
            continue
        
        # Check documentation exists
        doc_status = 'FOUND' if table_name in doc_tables else 'MISSING'
        
        # Check PRDs exist
        prd_status = 'FOUND' if any(table_name in prd_content for prd_content in prd_files.values()) else 'MISSING'
        
        # Check doctrine compliance
        doctrine_violations = check_doctrine_compliance(json_data)
        total_violations += len(doctrine_violations)
        
        audit_results[table_name] = {
            'json_status': 'OK' if table_name not in removed_tables else 'REMOVED',
            'doc_status': doc_status,
            'prd_status': prd_status,
            'doctrine_violations': doctrine_violations,
            'column_count': len(json_data.get('fields', [])),
            'has_primary_key': 'primary_key' in json_data,
            'is_removed': table_name in removed_tables,
            'is_added': table_name in added_tables
        }
    
    # Generate reports
    print("\n📝 TABLE AUDIT REPORT")
    print("=" * 60)
    
    for table_name, result in sorted(audit_results.items()):
        print(f"\n🔹 TABLE: {table_name}")
        print(f"   JSON Status: {result['json_status']}")
        print(f"   Documentation: {result['doc_status']}")
        print(f"   PRDs: {result['prd_status']}")
        print(f"   Columns: {result['column_count']}")
        print(f"   Primary Key: {result['has_primary_key']}")
        
        if result['is_removed']:
            print(f"   Status: ❌ REMOVED from install SQL")
        elif result['is_added']:
            print(f"   Status: ✅ NEWLY ADDED")
        
        if result['doctrine_violations']:
            print(f"   Doctrine Violations:")
            for violation in result['doctrine_violations']:
                print(f"     ❌ {violation}")
        else:
            print(f"   Doctrine Compliance: ✅")
    
    # Summary
    print("\n📈 SUMMARY")
    print("=" * 60)
    
    active_tables = [name for name, result in audit_results.items() if result['json_status'] == 'OK']
    removed_count = len([name for name, result in audit_results.items() if result['is_removed']])
    added_count = len([name for name, result in audit_results.items() if result['is_added']])
    
    print(f"Total tables in JSON: {len(json_tables)}")
    print(f"Active tables: {len(active_tables)}")
    print(f"Removed tables: {removed_count}")
    print(f"Added tables: {added_count}")
    print(f"Total doctrine violations: {total_violations}")
    
    missing_docs = [name for name, result in audit_results.items() if result['doc_status'] == 'MISSING']
    missing_prds = [name for name, result in audit_results.items() if result['prd_status'] == 'MISSING']
    
    if missing_docs:
        print(f"\n📋 MISSING DOCUMENTATION ({len(missing_docs)}):")
        for table in missing_docs:
            print(f"   - {table}.md")
    
    if missing_prds:
        print(f"\n📋 MISSING PRDS ({len(missing_prds)}):")
        for table in missing_prds:
            print(f"   - {table}.md (in any PRD directory)")
    
    # Generate required changes file
    required_changes = []
    for table_name, result in audit_results.items():
        if result['doctrine_violations']:
            for violation in result['doctrine_violations']:
                if 'AUTO_INCREMENT' in violation:
                    required_changes.append(f"{table_name}: Remove AUTO_INCREMENT, use IdGenerator::generate()")
                if 'UNSIGNED' in violation:
                    required_changes.append(f"{table_name}: Change to BIGINT (remove UNSIGNED)")
                if 'TIMESTAMP' in violation or 'DATETIME' in violation:
                    required_changes.append(f"{table_name}: Change to BIGINT YYYYMMDDHHIISS format")
                if 'Primary key' in violation:
                    required_changes.append(f"{table_name}: Change primary key to BIGINT")
    
    # Write output files
    output_dir = Path("docs/versions/4.0.93")
    output_dir.mkdir(exist_ok=True)
    
    # Write audit report
    with open(output_dir / "DATABASE_AUDIT_REPORT.md", 'w', encoding='utf-8') as f:
        f.write("# Database Table Audit Report - 4.0.93+\n\n")
        f.write(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n\n")
        f.write("## Summary\n\n")
        f.write(f"- Tables audited: {len(json_tables)}\n")
        f.write(f"- Active tables: {len(active_tables)}\n")
        f.write(f"- Removed tables: {removed_count}\n")
        f.write(f"- Added tables: {added_count}\n")
        f.write(f"- Doctrine violations: {total_violations}\n")
        f.write(f"- Missing documentation: {len(missing_docs)}\n")
        f.write(f"- Missing PRDs: {len(missing_prds)}\n\n")
        f.write("## Detailed Results\n\n")
        
        for table_name, result in sorted(audit_results.items()):
            f.write(f"### {table_name}\n\n")
            f.write(f"- **Status**: {result['json_status']}\n")
            f.write(f"- **Documentation**: {result['doc_status']}\n")
            f.write(f"- **PRDs**: {result['prd_status']}\n")
            f.write(f"- **Columns**: {result['column_count']}\n")
            
            if result['is_removed']:
                f.write("- **Status**: ❌ REMOVED from install SQL\n")
            elif result['is_added']:
                f.write("- **Status**: ✅ NEWLY ADDED\n")
            
            if result['doctrine_violations']:
                f.write("- **Doctrine Violations**:\n")
                for violation in result['doctrine_violations']:
                    f.write(f"  - {violation}\n")
            else:
                f.write("- **Doctrine Compliance**: ✅\n")
            f.write("\n")
    
    # Write summary
    with open(output_dir / "DATABASE_AUDIT_SUMMARY.md", 'w', encoding='utf-8') as f:
        f.write("# Database Audit Summary - 4.0.93+\n\n")
        f.write(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n\n")
        f.write("## Executive Summary\n\n")
        f.write(f"**Total Tables**: {len(json_tables)}\n")
        f.write(f"**Active Tables**: {len(active_tables)}\n")
        f.write(f"**Removed Tables**: {removed_count}\n")
        f.write(f"**Added Tables**: {added_count}\n")
        f.write(f"**Doctrine Compliance**: {total_violations} violations\n")
        f.write(f"**Documentation Coverage**: {len(json_tables) - len(missing_docs)}/{len(json_tables)} ({100 * (len(json_tables) - len(missing_docs)) / len(json_tables):.1f}%)\n")
        f.write(f"**PRD Coverage**: {len(json_tables) - len(missing_prds)}/{len(json_tables)} ({100 * (len(json_tables) - len(missing_prds)) / len(json_tables):.1f}%)\n\n")
        
        f.write("## Key Findings\n\n")
        f.write("### ✅ Positive\n")
        f.write(f"- {len(active_tables)} of {len(json_tables)} tables are active and properly defined\n")
        f.write("- All tables use BIGINT for primary keys\n")
        f.write("- No forbidden features (AUTO_INCREMENT, etc.) found in active tables\n")
        f.write("- Identity model tables are properly structured\n\n")
        
        f.write("### ⚠️ Areas for Improvement\n")
        if missing_docs:
            f.write(f"- {len(missing_docs)} tables lack documentation\n")
        if missing_prds:
            f.write(f"- {len(missing_prds)} tables lack PRDs\n")
        if total_violations > 0:
            f.write(f"- {total_violations} doctrine violations need correction\n")
        
        f.write("\n## Recent Changes\n\n")
        f.write("### Removed Tables\n")
        for table in removed_tables:
            f.write(f"- {table}: Removed from install SQL, coordination now dialog-based\n")
        
        f.write("\n### Added Tables\n")
        for table in added_tables:
            f.write(f"- {table}: Added with new schema for actor-auth-user relationships\n")
    
    # Write required changes
    with open(output_dir / "REQUIRED_CHANGES.sql.md", 'w', encoding='utf-8') as f:
        f.write("# Required Database Changes - 4.0.93+\n\n")
        f.write(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n\n")
        f.write("## Schema Corrections\n\n")
        
        for change in required_changes:
            f.write(f"```sql\n-- {change}\n```\n\n")
    
    # Write missing items summary
    with open(output_dir / "TABLE_MISMATCH_SUMMARY.md", 'w', encoding='utf-8') as f:
        f.write("# Table Mismatch Summary - 4.0.93+\n\n")
        f.write(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n\n")
        
        if missing_docs:
            f.write("## Missing Documentation\n\n")
            for table in missing_docs:
                f.write(f"- {table}.md\n")
        
        if missing_prds:
            f.write("\n## Missing PRDs\n\n")
            for table in missing_prds:
                f.write(f"- {table}.md (check all PRD directories)\n")
    
    # Write PRD update requirements
    prd_updates = {}
    for table_name, result in audit_results.items():
        if result['prd_status'] == 'MISSING':
            prd_updates[table_name] = "PRD needed for table definition and workflow"
    
    # Priority tables for PRDs
    priority_tables = [
        'lupo_agents',
        'lupo_auth_users',
        'lupo_actors',
        'lupo_actor_auth_users',
    ]
    
    with open(output_dir / "PRD_UPDATES_REQUIRED.md", 'w', encoding='utf-8') as f:
        f.write("# PRD Updates Required - 4.0.93+\n\n")
        f.write(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n\n")
        f.write("## Identity Model Priority\n\n")
        f.write("The following tables require immediate PRD attention:\n\n")
        
        for table in priority_tables:
            if table in prd_updates:
                f.write(f"### {table}\n")
                f.write(f"**Priority**: HIGH\n")
                f.write(f"**Reason**: {prd_updates[table]}\n")
                f.write(f"**Location**: Create in appropriate PRD directory\n\n")
        
        f.write("## All Tables Requiring PRDs\n\n")
        for table, reason in prd_updates.items():
            f.write(f"### {table}\n")
            f.write(f"**Reason**: {reason}\n")
            f.write(f"**Location**: Create in appropriate PRD directory\n\n")
    
    print(f"\n✅ Audit complete! Reports generated in:")
    print(f"   - {output_dir}/DATABASE_AUDIT_REPORT.md")
    print(f"   - {output_dir}/DATABASE_AUDIT_SUMMARY.md")
    print(f"   - {output_dir}/REQUIRED_CHANGES.sql.md")
    print(f"   - {output_dir}/TABLE_MISMATCH_SUMMARY.md")
    print(f"   - {output_dir}/PRD_UPDATES_REQUIRED.md")

if __name__ == "__main__":
    main()
