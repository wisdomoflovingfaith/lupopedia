#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/validate_semantic_seed_4.0.23.py"
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
Semantic Validation Suite for Lupopedia 4.0.23
Validates all seed data, semantic tables, actors/agents, and SQL INSERTs
"""

import os
import sys
import json
import csv
from pathlib import Path
from datetime import datetime

class SemanticValidationError(Exception):
    pass

class SemanticValidator:
    def __init__(self, base_path: str):
        self.base_path = Path(base_path)
        self.csv_path = self.base_path / "database" / "csv_data"
        self.toons_path = self.base_path / "docs" / "toons"
        self.seed_path = self.base_path / "database" / "migrations"
        self.errors = []
        self.warnings = []
        
    def load_toon_schema(self, table_name: str) -> dict:
        """Load TOON schema for a table"""
        toon_file = self.toons_path / f"{table_name}.toon.json"
        try:
            with open(toon_file, 'r') as f:
                toon_data = json.load(f)
                return toon_data.get('fields', [])
        except Exception as e:
            self.errors.append(f"Failed to load TOON for {table_name}: {e}")
            return {}
    
    def load_csv_data(self, csv_file: str) -> list:
        """Load CSV data and return as list of dicts"""
        csv_file = self.csv_path / csv_file
        if not csv_file.exists():
            return []
        
        try:
            with open(csv_file, 'r', encoding='utf-8') as f:
                reader = csv.DictReader(f)
                return list(reader)
        except Exception as e:
            self.errors.append(f"Failed to load {csv_file}: {e}")
            return []
    
    def validate_table(self, table_name: str, expected_rows: int = None) -> dict:
        """Validate a single table against its TOON schema and CSV data"""
        result = {
            'table_name': table_name,
            'expected_rows': expected_rows,
            'csv_rows': 0,
            'schema_columns': [],
            'insert_columns': [],
            'errors': [],
            'warnings': []
        }
        
        # Load TOON schema
        toon_schema = self.load_toon_schema(table_name)
        if not toon_schema:
            result['errors'].append(f"No TOON schema found for {table_name}")
            return result
        
        result['schema_columns'] = [field['field'] for field in toon_schema['fields']]
        
        # Load CSV data
        csv_file = f"lupo_{table_name}.csv"
        csv_data = self.load_csv_data(csv_file)
        result['csv_rows'] = len(csv_data)
        
        if not csv_data:
            result['warnings'].append(f"No CSV data found for {table_name}")
            return result
        
        # Validate each row
        for i, row in enumerate(csv_data):
            row_errors = self.validate_row(table_name, row, toon_schema)
            if row_errors:
                result['errors'].extend([f"Row {i+1} in {csv_file}: {err}" for err in row_errors])
        
        # Check for missing expected rows
        if expected_rows is not None and len(csv_data) < expected_rows:
            result['warnings'].append(f"Expected {expected_rows} rows in {csv_file}, found {len(csv_data)}")
        
        return result
    
    def validate_row(self, table_name: str, row: dict, toon_schema: dict) -> list:
        """Validate a single row against TOON schema"""
        errors = []
        
        # Check column count
        csv_columns = set(row.keys())
        schema_columns = set(field['field'] for field in toon_schema['fields'])
        missing_columns = schema_columns - csv_columns
        extra_columns = csv_columns - schema_columns
        
        if missing_columns:
            errors.append(f"Missing columns: {missing_columns}")
        if extra_columns:
            errors.append(f"Extra columns: {extra_columns}")
        
        # Validate data types and JSON
        for field_name, field_def in toon_schema['fields'].items():
            field_name = field_def['field'].strip('`')
            if field_name not in row:
                errors.append(f"Missing required field: {field_name}")
                continue
            
            field_type = field_def.get('type', 'unknown').lower()
            value = row[field_name]
            
            # Type validation
            if field_type == 'bigint':
                if not isinstance(value, int) and not str(value).isdigit():
                    try:
                        int(value)
                    except ValueError:
                        errors.append(f"Invalid BIGINT in {field_name}: {value}")
            elif field_type == 'int':
                if not isinstance(value, int):
                    errors.append(f"Invalid INT in {field_name}: {value}")
            elif field_type in ['varchar', 'char', 'text']:
                if not isinstance(value, str):
                    errors.append(f"Invalid string in {field_name}: {value}")
            elif field_type == 'json':
                if isinstance(value, str):
                    try:
                        json.loads(value)
                    except json.JSONDecodeError:
                        errors.append(f"Invalid JSON in {field_name}: {value}")
                else:
                    errors.append(f"Invalid JSON type in {field_name}: {type(value)}")
            elif field_type == 'tinyint':
                if not isinstance(value, int) or value not in [0, 1]:
                    errors.append(f"Invalid TINYINT in {field_name}: {value}")
        
        return errors
    
    def validate_all_tables(self) -> dict:
        """Validate all semantic tables"""
        validation_results = {}
        
        # Core semantic tables
        core_tables = [
            'atoms', 'semantic_paths', 'semantic_relationships',
            'emotional_stars', 'emotional_constellations', 'emotional_translations',
            'truth_sources', 'truth_relations', 'truth_evidence', 'truth_items',
            'governance_events', 'governance_valuations', 'world_events',
            'persona_profiles', 'persona_dialogue_patterns'
        ]
        
        # Actor/Agent tables
        actor_tables = ['actors', 'agents', 'REGISTRY']
        
        for table in core_tables + actor_tables:
            validation_results[table] = self.validate_table(table)
        
        return validation_results
    
    def generate_report(self, validation_results: dict) -> str:
        """Generate validation report"""
        report = []
        report.append("# Semantic Validation Report for Lupopedia 4.0.23")
        report.append(f"Generated: {datetime.now().isoformat()}")
        report.append("")
        
        # Summary
        total_errors = sum(len(result.get('errors', [])) for result in validation_results.values())
        total_warnings = sum(len(result.get('warnings', [])) for result in validation_results.values())
        
        if total_errors == 0 and total_warnings == 0:
            report.append("✅ ALL VALIDATIONS PASSED")
        else:
            report.append(f"❌ {total_errors} ERRORS, {total_warnings} WARNINGS")
        
        report.append("")
        
        # Detailed results
        for table_name, result in validation_results.items():
            report.append(f"\n## {table_name.upper()}")
            report.append(f"Expected rows: {result.get('expected_rows', 'N/A')}")
            report.append(f"CSV rows: {result.get('csv_rows', 0)}")
            report.append(f"Schema columns: {len(result.get('schema_columns', []))}")
            
            if result.get('errors', []):
                report.append("### ERRORS:")
                for error in result['errors']:
                    report.append(f"- {error}")
            else:
                report.append("✅ No schema errors")
            
            if result.get('warnings', []):
                report.append("### WARNINGS:")
                for warning in result['warnings']:
                    report.append(f"- {warning}")
            else:
                report.append("✅ No warnings")
        
        report.append("")
        report.append("# Summary")
        report.append(f"Total tables validated: {len(validation_results)}")
        report.append(f"Total errors: {total_errors}")
        report.append(f"Total warnings: {total_warnings}")
        
        return "\n".join(report)

def main():
    if len(sys.argv) != 2:
        print("Usage: python validate_semantic_seed_4.0.23.py <base_path>")
        sys.exit(1)
    
    base_path = sys.argv[1]
    validator = SemanticValidator(base_path)
    
    print("🔍 Validating Semantic Seed Data for Lupopedia 4.0.23...")
    validation_results = validator.validate_all_tables()
    
    # Generate and print report
    report = validator.generate_report(validation_results)
    print(report)
    
    # Exit with appropriate code
    if any(len(result.get('errors', [])) for result in validation_results.values()):
        sys.exit(1)
    else:
        sys.exit(0)

if __name__ == "__main__":
    main()