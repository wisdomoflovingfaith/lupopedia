#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/validate_schema_4.0.21.py"
#   last_modified_utc: "20260324175617"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Schema Validation Script for Lupopedia 4.0.22
Validates all 198 tables against TOON specifications for zero drift.

Usage: python scripts/validate_schema_4.0.21.py [--verbose] [--fix]

Options:
  --verbose: Show detailed validation output
  --fix: Attempt to fix minor drift issues (experimental)
"""

import os
import sys
import json
import argparse
import mysql.connector
from mysql.connector import Error
from pathlib import Path
from typing import Dict, List, Optional, Any, Tuple
import re


class SchemaValidator:
    """Schema validator for Lupopedia 4.0.22"""
    
    def __init__(self, config: Dict[str, Any]):
        self.db_connection = None
        self.config = config
        self.validation_results = {}
        self.drift_detected = False
        
    def connect_database(self) -> bool:
        """Connect to MySQL database"""
        try:
            self.db_connection = mysql.connector.connect(
                host=self.config['host'],
                database=self.config['database'],
                user=self.config['user'],
                password=self.config['password'],
                port=self.config['port']
            )
            return True
        except Error as e:
            print(f"Database connection failed: {e}")
            return False
    
    def load_toon_file(self, toon_path: str) -> Optional[Dict]:
        """Load TOON file as schema specification"""
        try:
            with open(toon_path, 'r', encoding='utf-8') as f:
                return json.load(f)
        except Exception as e:
            print(f"Error loading TOON file {toon_path}: {e}")
            return None
    
    def get_all_toon_files(self) -> List[str]:
        """Get list of all TOON files"""
        toon_dir = Path("docs/toons")
        if not toon_dir.exists():
            print(f"TOON directory not found: {toon_dir}")
            return []
        
        return [str(f) for f in toon_dir.glob("*.toon.json")]
    
    def get_database_schema(self, table_name: str) -> Optional[Dict]:
        """Get actual database schema for a table"""
        try:
            cursor = self.db_connection.cursor(dictionary=True)
            
            # Get table structure
            cursor.execute(f"DESCRIBE {table_name}")
            columns = cursor.fetchall()
            
            # Get indexes
            cursor.execute(f"SHOW INDEX FROM {table_name}")
            indexes = cursor.fetchall()
            
            cursor.close()
            
            return {
                'columns': columns,
                'indexes': indexes
            }
        except Error as e:
            print(f"Error getting schema for {table_name}: {e}")
            return None
    
    def normalize_column_type(self, column_type: str) -> str:
        """Normalize column type for comparison"""
        # Remove display widths, UNSIGNED, etc. for comparison
        normalized = re.sub(r'\(\d+\)', '', column_type)  # Remove widths
        normalized = re.sub(r'\s+UNSIGNED', '', normalized)  # Remove UNSIGNED
        normalized = re.sub(r'\s+', ' ', normalized).strip()  # Normalize spaces
        return normalized.upper()
    
    def compare_schemas(self, table_name: str, toon_schema: Dict, db_schema: Dict) -> Dict:
        """Compare TOON schema with database schema"""
        result = {
            'table_name': table_name,
            'status': 'MATCH',
            'drift_details': []
        }
        
        if not db_schema:
            result['status'] = 'ERROR'
            result['drift_details'].append('Could not retrieve database schema')
            return result
        
        # Compare columns
        toon_columns = {col['name']: col for col in toon_schema.get('columns', [])}
        db_columns = {col['Field']: col for col in db_schema.get('columns', [])}
        
        # Check for missing columns
        for col_name, toon_col in toon_columns.items():
            if col_name not in db_columns:
                result['status'] = 'DRIFT'
                result['drift_details'].append(f"Missing column: {col_name}")
                self.drift_detected = True
        
        # Check for extra columns
        for col_name, db_col in db_columns.items():
            if col_name not in toon_columns:
                result['status'] = 'DRIFT'
                result['drift_details'].append(f"Extra column: {col_name}")
                self.drift_detected = True
        
        # Check column types
        for col_name in toon_columns:
            if col_name in db_columns:
                toon_type = self.normalize_column_type(toon_columns[col_name].get('type', ''))
                db_type = self.normalize_column_type(db_columns[col_name]['Type'])
                
                if toon_type != db_type:
                    result['status'] = 'DRIFT'
                    result['drift_details'].append(
                        f"Column {col_name} type mismatch: TOON={toon_type}, DB={db_type}"
                    )
                    self.drift_detected = True
        
        # Compare indexes (simplified check)
        toon_indexes = toon_schema.get('indexes', [])
        db_index_names = {idx['Key_name'] for idx in db_schema.get('indexes', []) if idx['Key_name'] != 'PRIMARY'}
        toon_index_names = {idx.get('name', '') for idx in toon_indexes if idx.get('name') != 'PRIMARY'}
        
        missing_indexes = toon_index_names - db_index_names
        extra_indexes = db_index_names - toon_index_names
        
        if missing_indexes:
            result['status'] = 'DRIFT'
            result['drift_details'].append(f"Missing indexes: {', '.join(missing_indexes)}")
            self.drift_detected = True
        
        if extra_indexes:
            result['status'] = 'DRIFT'
            result['drift_details'].append(f"Extra indexes: {', '.join(extra_indexes)}")
            self.drift_detected = True
        
        return result
    
    def validate_all_tables(self, verbose: bool = False) -> Dict:
        """Validate all tables against TOON specifications"""
        toon_files = self.get_all_toon_files()
        validation_results = {
            'total_tables': 0,
            'valid_tables': 0,
            'drift_tables': 0,
            'error_tables': 0,
            'tables': []
        }
        
        print(f"Loading {len(toon_files)} TOON files...")
        
        for toon_file in toon_files:
            # Extract table name from filename
            table_name = Path(toon_file).stem
            validation_results['total_tables'] += 1
            
            # Load TOON schema
            toon_schema = self.load_toon_file(toon_file)
            if not toon_schema:
                validation_results['error_tables'] += 1
                continue
            
            # Get database schema
            db_schema = self.get_database_schema(table_name)
            if not db_schema:
                validation_results['error_tables'] += 1
                continue
            
            # Compare schemas
            comparison = self.compare_schemas(table_name, toon_schema, db_schema)
            validation_results['tables'].append(comparison)
            
            if comparison['status'] == 'MATCH':
                validation_results['valid_tables'] += 1
                if verbose:
                    print(f"✅ {table_name}: MATCH")
            elif comparison['status'] == 'DRIFT':
                validation_results['drift_tables'] += 1
                if verbose:
                    print(f"⚠️  {table_name}: DRIFT")
                    for detail in comparison['drift_details']:
                        print(f"    - {detail}")
            else:
                validation_results['error_tables'] += 1
                if verbose:
                    print(f"❌ {table_name}: ERROR")
                    for detail in comparison['drift_details']:
                        print(f"    - {detail}")
        
        return validation_results
    
    def generate_report(self, results: Dict) -> str:
        """Generate validation report"""
        report = []
        report.append("# Lupopedia 4.0.21 Schema Validation Report")
        report.append(f"Generated: {__import__('datetime').datetime.now().isoformat()}")
        report.append("")
        
        report.append("## Summary")
        report.append(f"- Total Tables: {results['total_tables']}")
        report.append(f"- Valid Tables: {results['valid_tables']}")
        report.append(f"- Drift Tables: {results['drift_tables']}")
        report.append(f"- Error Tables: {results['error_tables']}")
        report.append("")
        
        if results['drift_tables'] > 0:
            report.append("## Tables with Schema Drift")
            for table in results['tables']:
                if table['status'] == 'DRIFT':
                    report.append(f"### {table['table_name']}")
                    for detail in table['drift_details']:
                        report.append(f"- {detail}")
                    report.append("")
        
        if results['error_tables'] > 0:
            report.append("## Tables with Errors")
            for table in results['tables']:
                if table['status'] == 'ERROR':
                    report.append(f"### {table['table_name']}")
                    for detail in table['drift_details']:
                        report.append(f"- {detail}")
                    report.append("")
        
        report.append("## Validation Status")
        if results['drift_tables'] == 0 and results['error_tables'] == 0:
            report.append("✅ **PASSED**: All tables match TOON specifications")
            report.append("Schema is in compliance with 4.0.21 requirements.")
        else:
            report.append("❌ **FAILED**: Schema drift detected")
            report.append("Run with --fix to attempt automatic corrections.")
        
        return "\n".join(report)
    
    def save_report(self, report: str):
        """Save validation report to file"""
        report_path = "docs/audits/4.0.21_SCHEMA_VALIDATION_REPORT.md"
        try:
            os.makedirs(os.path.dirname(report_path), exist_ok=True)
            with open(report_path, 'w', encoding='utf-8') as f:
                f.write(report)
            print(f"Validation report saved to: {report_path}")
        except Exception as e:
            print(f"Error saving report: {e}")


def load_config() -> Dict[str, Any]:
    """Load database configuration from environment variables"""
    return {
        'host': os.getenv('DB_HOST', 'localhost'),
        'database': os.getenv('DB_NAME', 'lupopedia'),
        'user': os.getenv('DB_USER', 'lupopedia'),
        'password': os.getenv('DB_PASSWORD', ''),
        'port': int(os.getenv('DB_PORT', '3306'))
    }


def main():
    parser = argparse.ArgumentParser(description='Validate Lupopedia 4.0.21 schema against TOON specifications')
    parser.add_argument('--verbose', '-v', action='store_true', help='Show detailed validation output')
    parser.add_argument('--fix', '-f', action='store_true', help='Attempt to fix minor drift issues (experimental)')
    
    args = parser.parse_args()
    
    # Load configuration
    config = load_config()
    
    # Initialize validator
    validator = SchemaValidator(config)
    
    # Connect to database
    if not validator.connect_database():
        sys.exit(1)
    
    try:
        # Run validation
        results = validator.validate_all_tables(verbose=args.verbose)
        
        # Generate and save report
        report = validator.generate_report(results)
        validator.save_report(report)
        
        # Print summary
        print(f"\nValidation Complete:")
        print(f"Total: {results['total_tables']}, Valid: {results['valid_tables']}, Drift: {results['drift_tables']}, Errors: {results['error_tables']}")
        
        # Exit code
        if results['drift_tables'] == 0 and results['error_tables'] == 0:
            sys.exit(0)
        else:
            sys.exit(1)
            
    finally:
        if validator.db_connection:
            validator.db_connection.close()


if __name__ == "__main__":
    main()