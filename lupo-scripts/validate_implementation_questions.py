#!/usr/bin/env python3
"""
Validate Implementation Questions Script

Validates that implementation questions follow constitutional requirements:
- Proper deterministic IDs
- Correct header structure
- Valid level assignments
- Proper thread index updates

Constitutionally compliant - pure file system validation, no database dependencies.
"""

import os
import sys
import re
import yaml
from pathlib import Path

def load_yaml_header(file_path):
    """Load YAML header from markdown file."""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Extract YAML header
        if content.startswith('---'):
            end = content.find('---', 3)
            if end != -1:
                yaml_content = content[3:end].strip()
                return yaml.safe_load(yaml_content)
    except Exception as e:
        print(f"ERROR: Could not parse YAML header in {file_path}: {e}")
    
    return None

def validate_question_file(file_path, level):
    """Validate a single question file."""
    errors = []
    warnings = []
    
    # Check filename format
    filename = os.path.basename(file_path)
    if not re.match(r'^\d{8}_\d{6}_QUESTION_[a-zA-Z0-9_]+\.md$', filename):
        errors.append(f"Invalid filename format: {filename}")
    
    # Load and validate header
    header = load_yaml_header(file_path)
    if not header:
        errors.append("Could not load YAML header")
        return errors, warnings
    
    # Check required header fields
    required_fields = [
        'header_format_version',
        'lupopedia.schema',
        'when_updated',
        'file_path_from_root',
        'web_path',
        'federation_node_id',
        'channel_id',
        'thread_id',
        'question_id',
        'implementation_id',
        'level',
        'status',
        'actor_id',
        'actor_name',
        'delegation_chain',
        'artifact_type',
        'artifact_kind',
        'purpose',
        'tags'
    ]
    
    for field in required_fields:
        if field not in header.get('lupopedia.headers', {}):
            errors.append(f"Missing required header field: {field}")
    
    # Validate specific field values
    headers = header.get('lupopedia.headers', {})
    
    if headers.get('lupopedia.schema') != 'implementation':
        errors.append("lupopedia.schema must be 'implementation'")
    
    if headers.get('artifact_type') != 'implementation':
        errors.append("artifact_type must be 'implementation'")
    
    if headers.get('artifact_kind') != 'question':
        errors.append("artifact_kind must be 'question'")
    
    if headers.get('level') != level:
        errors.append(f"Header level ({headers.get('level')}) doesn't match folder level ({level})")
    
    if headers.get('status') not in ['open', 'answered', 'deferred']:
        warnings.append(f"Unusual status: {headers.get('status')}")
    
    # Validate question_id format
    question_id = headers.get('question_id')
    if not question_id or not isinstance(question_id, int) or question_id < 2026010100000000:
        errors.append(f"Invalid question_id: {question_id}")
    
    # Validate when_updated format
    when_updated = headers.get('when_updated')
    if not re.match(r'^\d{14}$', str(when_updated)):
        errors.append(f"Invalid when_updated format: {when_updated}")
    
    # Check for edges
    if 'lupopedia.edges' not in header:
        warnings.append("Missing lupopedia.edges section")
    
    return errors, warnings

def validate_thread_index(index_file):
    """Validate thread index file."""
    errors = []
    warnings = []
    
    if not os.path.exists(index_file):
        errors.append("THREAD_INDEX.md does not exist")
        return errors, warnings
    
    # Check that it contains required sections
    with open(index_file, 'r') as f:
        content = f.read()
    
    required_sections = [
        "## Critical Questions (HALT Implementation)",
        "## Optimization Questions (Document and Continue)",
        "## Clarification Questions (Document Assumption)"
    ]
    
    for section in required_sections:
        if section not in content:
            warnings.append(f"Missing section: {section}")
    
    return errors, warnings

def validate_implementation_directory(impl_path):
    """Validate an entire implementation directory."""
    impl_name = os.path.basename(impl_path)
    print(f"\n🔍 Validating implementation: {impl_name}")
    
    total_errors = 0
    total_warnings = 0
    
    # Check questions directory structure
    questions_dir = os.path.join(impl_path, 'questions')
    if not os.path.exists(questions_dir):
        print(f"  ❌ No questions/ directory found")
        return 1, 0
    
    # Validate each level
    for level in ['critical', 'optimization', 'clarification']:
        level_dir = os.path.join(questions_dir, level)
        
        if os.path.exists(level_dir):
            print(f"\n  📁 Checking {level} questions:")
            
            # Validate thread index
            index_file = os.path.join(questions_dir, 'THREAD_INDEX.md')
            index_errors, index_warnings = validate_thread_index(index_file)
            total_errors += len(index_errors)
            total_warnings += len(index_warnings)
            
            for error in index_errors:
                print(f"    ❌ Index: {error}")
            for warning in index_warnings:
                print(f"    ⚠️  Index: {warning}")
            
            # Validate question files
            question_files = [f for f in os.listdir(level_dir) 
                            if f.endswith('.md') and 'QUESTION' in f]
            
            if not question_files:
                print(f"    ℹ️  No {level} questions found")
            else:
                for qfile in question_files:
                    qpath = os.path.join(level_dir, qfile)
                    errors, warnings = validate_question_file(qpath, level)
                    total_errors += len(errors)
                    total_warnings += len(warnings)
                    
                    if errors:
                        print(f"    ❌ {qfile}:")
                        for error in errors:
                            print(f"       - {error}")
                    
                    if warnings:
                        print(f"    ⚠️  {qfile}:")
                        for warning in warnings:
                            print(f"       - {warning}")
                    
                    if not errors and not warnings:
                        print(f"    ✅ {qfile}")
    
    return total_errors, total_warnings

def main():
    """Main validation function."""
    if len(sys.argv) < 2:
        print("Usage: python validate_implementation_questions.py <implementation_directory> [implementation_directory2 ...]")
        print("       python validate_implementation_questions.py --all")
        sys.exit(1)
    
    if sys.argv[1] == '--all':
        # Validate all implementations
        script_dir = os.path.dirname(os.path.abspath(__file__))
        impl_dir = os.path.join(script_dir, "..", "lupo-docs", "implementations")
        implementations = [os.path.join(impl_dir, d) for d in os.listdir(impl_dir) 
                          if os.path.isdir(os.path.join(impl_dir, d)) and d != '_template']
    else:
        # Validate specific implementations
        script_dir = os.path.dirname(os.path.abspath(__file__))
        implementations = [os.path.join(script_dir, "..", "lupo-docs", "implementations", d) 
                          for d in sys.argv[1:]]
    
    total_errors = 0
    total_warnings = 0
    
    for impl_path in implementations:
        impl_path = Path(impl_path)
        if not impl_path.exists():
            print(f"❌ Implementation directory not found: {impl_path}")
            continue
        
        errors, warnings = validate_implementation_directory(impl_path)
        total_errors += errors
        total_warnings += warnings
    
    # Summary
    print(f"\n{'='*50}")
    print(f"📊 Validation Summary:")
    print(f"   Errors: {total_errors}")
    print(f"   Warnings: {total_warnings}")
    
    if total_errors > 0:
        print(f"\n❌ Validation FAILED with {total_errors} errors")
        sys.exit(1)
    elif total_warnings > 0:
        print(f"\n⚠️  Validation PASSED with {total_warnings} warnings")
    else:
        print(f"\n✅ Validation PASSED - All questions are compliant")

if __name__ == "__main__":
    main()
