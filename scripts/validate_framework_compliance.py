#!/usr/bin/env python3
"""
Framework Compliance Validation Script

Validates compliance with PRD 30 and PRD 31:
- Channel content compliance (no documentation in channels)
- Implementation folder structure completeness
- Question lifecycle integrity
- Cross-link validation
- Template usage compliance

Constitutionally compliant - pure file system validation, no database dependencies.
"""

import os
import sys
import re
import yaml
import argparse
from pathlib import Path
from datetime import datetime

def load_yaml_header(file_path):
    """Load YAML header from markdown file."""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        if content.startswith('---'):
            end = content.find('---', 3)
            if end != -1:
                yaml_content = content[3:end].strip()
                return yaml.safe_load(yaml_content)
    except Exception as e:
        print(f"ERROR: Could not parse YAML header in {file_path}: {e}")
    
    return None

def validate_channel_content(channel_path):
    """Validate that channel contains only coordination content."""
    errors = []
    warnings = []
    
    if not os.path.exists(channel_path):
        return [f"Channel path not found: {channel_path}"], []
    
    # Check for forbidden content patterns
    forbidden_patterns = [
        (r'# (Doctrine|Policy|Specification|Documentation)', "Documentation content in channel"),
        (r'## (Technical Details|Implementation|Reference)', "Technical documentation in channel"),
        (r'```(sql|php|javascript|python)', "Code blocks in channel (should be in docs)"),
    ]
    
    # Allowed message patterns
    allowed_patterns = [
        r'# STATUS_REPORT_',
        r'# PROGRESS_UPDATE_',
        r'# CRITICAL_COORDINATION_',
        r'# AGENT_HANDOFF_',
    ]
    
    for root, dirs, files in os.walk(channel_path):
        for file in files:
            if file.endswith('.md'):
                file_path = os.path.join(root, file)
                
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                # Check for forbidden patterns
                for pattern, message in forbidden_patterns:
                    if re.search(pattern, content, re.IGNORECASE):
                        errors.append(f"{file}: {message}")
                
                # Check if content has allowed pattern
                has_allowed = any(re.search(pattern, content) for pattern in allowed_patterns)
                
                if not has_allowed and len(content) > 200:  # Skip small files
                    warnings.append(f"{file}: Content doesn't match allowed message patterns")
    
    return errors, warnings

def validate_implementation_structure(impl_path):
    """Validate complete implementation folder structure."""
    errors = []
    warnings = []
    
    required_folders = [
        "questions",
        "questions/critical",
        "questions/optimization",
        "questions/clarification",
        "answers",
        "decisions",
        "comments",
        "templates"
    ]
    
    # Check required folders exist
    for folder in required_folders:
        folder_path = os.path.join(impl_path, folder)
        if not os.path.exists(folder_path):
            errors.append(f"Missing required folder: {folder}")
    
    # Check for THREAD_INDEX.md files
    index_folders = ["questions", "answers", "decisions", "comments"]
    for folder in index_folders:
        index_path = os.path.join(impl_path, folder, "THREAD_INDEX.md")
        if not os.path.exists(index_path):
            errors.append(f"Missing THREAD_INDEX.md in {folder}/")
    
    # Check README.md has Related Artifacts section
    readme_path = os.path.join(impl_path, "README.md")
    if os.path.exists(readme_path):
        with open(readme_path, 'r') as f:
            content = f.read()
        
        if "## Related Artifacts" not in content:
            warnings.append("README.md missing Related Artifacts section")
        
        if "Question Status" not in content:
            warnings.append("README.md missing Question Status section")
    else:
        errors.append("Missing README.md")
    
    return errors, warnings

def validate_question_lifecycle(impl_path):
    """Validate question lifecycle completeness."""
    errors = []
    warnings = []
    
    questions_dir = os.path.join(impl_path, "questions")
    
    if not os.path.exists(questions_dir):
        return ["questions directory not found"], []
    
    # Check each question level
    for level in ["critical", "optimization", "clarification"]:
        level_dir = os.path.join(questions_dir, level)
        
        if os.path.exists(level_dir):
            question_files = [f for f in os.listdir(level_dir) 
                            if f.endswith('.md') and 'QUESTION' in f]
            
            for qfile in question_files:
                qpath = os.path.join(level_dir, qfile)
                
                # Check question header
                header = load_yaml_header(qpath)
                if not header:
                    errors.append(f"{qfile}: Missing or invalid YAML header")
                    continue
                
                headers = header.get('lupopedia.headers', {})
                
                # Validate required fields
                required_fields = ['question_id', 'level', 'status', 'implementation_id']
                for field in required_fields:
                    if field not in headers:
                        errors.append(f"{qfile}: Missing required field {field}")
                
                # Check status is valid
                status = headers.get('status')
                if status not in ['open', 'discussion', 'answered', 'closed']:
                    warnings.append(f"{qfile}: Unusual status '{status}'")
                
                # Look for corresponding answer if status is answered or closed
                if status in ['answered', 'closed']:
                    answer_pattern = qfile.replace('QUESTION', 'ANSWER')
                    answer_path = os.path.join(impl_path, "answers", answer_pattern)
                    
                    if not os.path.exists(answer_path):
                        warnings.append(f"{qfile}: Question marked {status} but no answer found")
    
    return errors, warnings

def validate_cross_links(impl_path):
    """Validate cross-link integrity between artifacts."""
    errors = []
    warnings = []
    
    # Check README.md links
    readme_path = os.path.join(impl_path, "README.md")
    if os.path.exists(readme_path):
        with open(readme_path, 'r') as f:
            content = f.read()
        
        # Check for PRD link
        if '[PRD]:' not in content:
            warnings.append("README.md missing PRD reference")
        
        # Check for channel reference
        if 'Channel:' not in content:
            warnings.append("README.md missing channel reference")
    
    # Check question links to PRD
    questions_dir = os.path.join(impl_path, "questions")
    if os.path.exists(questions_dir):
        for root, dirs, files in os.walk(questions_dir):
            for file in files:
                if file.endswith('.md') and 'QUESTION' in file:
                    qpath = os.path.join(root, file)
                    header = load_yaml_header(qpath)
                    
                    if header and 'lupopedia.edges' in header:
                        edges = header['lupopedia.edges'].get('outbound_edges', [])
                        has_prd_link = any(edge.get('type') == 'questions' for edge in edges)
                        
                        if not has_prd_link:
                            warnings.append(f"{file}: Question missing link to PRD")
    
    return errors, warnings

def validate_template_usage(impl_path):
    """Validate that templates are being used consistently."""
    errors = []
    warnings = []
    
    templates_dir = os.path.join(impl_path, "templates")
    
    if not os.path.exists(templates_dir):
        warnings.append("No templates directory found")
        return errors, warnings
    
    # Check for required templates
    required_templates = [
        "QUESTION_CRITICAL_TEMPLATE.md",
        "QUESTION_OPTIMIZATION_TEMPLATE.md", 
        "QUESTION_CLARIFICATION_TEMPLATE.md",
        "ANSWER_TEMPLATE.md"
    ]
    
    for template in required_templates:
        template_path = os.path.join(templates_dir, template)
        if not os.path.exists(template_path):
            warnings.append(f"Missing template: {template}")
    
    # Check if questions follow template structure
    questions_dir = os.path.join(impl_path, "questions")
    if os.path.exists(questions_dir):
        for level in ["critical", "optimization", "clarification"]:
            level_dir = os.path.join(questions_dir, level)
            
            if os.path.exists(level_dir):
                question_files = [f for f in os.listdir(level_dir) 
                                if f.endswith('.md') and 'QUESTION' in f]
                
                for qfile in question_files:
                    qpath = os.path.join(level_dir, qfile)
                    with open(qpath, 'r') as f:
                        content = f.read()
                    
                    # Check for required sections
                    required_sections = ["## Context", "## Question"]
                    for section in required_sections:
                        if section not in content:
                            warnings.append(f"{qfile}: Missing section '{section}'")
    
    return errors, warnings

def validate_channel_docs_sync(impl_path, channels_base_path):
    """Validate channel-docs synchronization for critical questions."""
    errors = []
    warnings = []
    
    # Get implementation ID from path
    impl_name = os.path.basename(impl_path)
    
    # Check critical questions for channel references
    critical_dir = os.path.join(impl_path, "questions", "critical")
    if os.path.exists(critical_dir):
        for file in os.listdir(critical_dir):
            if file.endswith('.md') and 'QUESTION' in file:
                qpath = os.path.join(critical_dir, file)
                header = load_yaml_header(qpath)
                
                if header and 'lupopedia.edges' in header:
                    edges = header['lupopedia.edges'].get('outbound_edges', [])
                    has_channel_link = any('channel' in edge.get('to', '').lower() for edge in edges)
                    
                    if not has_channel_link:
                        warnings.append(f"{file}: Critical question missing channel synchronization link")
    
    return errors, warnings

def validate_implementation_compliance(impl_path, channels_base_path=None):
    """Validate complete implementation compliance."""
    impl_name = os.path.basename(impl_path)
    print(f"\n🔍 Validating implementation: {impl_name}")
    
    total_errors = 0
    total_warnings = 0
    
    # Run all validations
    validations = [
        ("Structure", lambda: validate_implementation_structure(impl_path)),
        ("Question Lifecycle", lambda: validate_question_lifecycle(impl_path)),
        ("Cross-Links", lambda: validate_cross_links(impl_path)),
        ("Template Usage", lambda: validate_template_usage(impl_path)),
    ]
    
    if channels_base_path:
        validations.append(("Channel-Docs Sync", lambda: validate_channel_docs_sync(impl_path, channels_base_path)))
    
    for name, validator in validations:
        print(f"\n  📁 Checking {name}:")
        errors, warnings = validator()
        total_errors += len(errors)
        total_warnings += len(warnings)
        
        for error in errors:
            print(f"    ❌ {error}")
        for warning in warnings:
            print(f"    ⚠️  {warning}")
        
        if not errors and not warnings:
            print(f"    ✅ {name} validation passed")
    
    return total_errors, total_warnings

def validate_channel_compliance(channels_base_path):
    """Validate all channels for content compliance."""
    print(f"\n🔍 Validating channel content compliance")
    
    total_errors = 0
    total_warnings = 0
    
    if not os.path.exists(channels_base_path):
        print(f"  ❌ Channels directory not found: {channels_base_path}")
        return 1, 0
    
    # Check each channel
    for item in os.listdir(channels_base_path):
        channel_path = os.path.join(channels_base_path, item)
        
        if os.path.isdir(channel_path) and item.isdigit():
            print(f"\n  📡 Checking channel {item}:")
            errors, warnings = validate_channel_content(channel_path)
            total_errors += len(errors)
            total_warnings += len(warnings)
            
            for error in errors:
                print(f"    ❌ {error}")
            for warning in warnings:
                print(f"    ⚠️  {warning}")
            
            if not errors and not warnings:
                print(f"    ✅ Channel {item} compliance passed")
    
    return total_errors, total_warnings

def main():
    """Main validation function."""
    parser = argparse.ArgumentParser(description="Validate framework compliance")
    parser.add_argument("--implementations", nargs='*', help="Implementation folders to validate")
    parser.add_argument("--channels", help="Channels base directory to validate")
    parser.add_argument("--all", action="store_true", help="Validate all implementations and channels")
    
    args = parser.parse_args()
    
    script_dir = os.path.dirname(os.path.abspath(__file__))
    
    if args.all:
        # Validate all implementations
        impl_base = os.path.join(script_dir, "..", "docs", "implementations")
        channels_base = os.path.join(script_dir, "..", "channels")
        
        implementations = []
        if os.path.exists(impl_base):
            implementations = [os.path.join(impl_base, d) for d in os.listdir(impl_base)
                             if os.path.isdir(os.path.join(impl_base, d)) and d != '_template']
    else:
        implementations = args.implementations or []
        channels_base = args.channels
    
    total_errors = 0
    total_warnings = 0
    
    # Validate channels if specified
    if channels_base:
        errors, warnings = validate_channel_compliance(channels_base)
        total_errors += errors
        total_warnings += warnings
    
    # Validate implementations
    for impl_path in implementations:
        if not os.path.exists(impl_path):
            print(f"❌ Implementation not found: {impl_path}")
            continue
        
        errors, warnings = validate_implementation_compliance(impl_path, channels_base)
        total_errors += errors
        total_warnings += warnings
    
    # Summary
    print(f"\n{'='*60}")
    print(f"📊 Framework Compliance Summary:")
    print(f"   Errors: {total_errors}")
    print(f"   Warnings: {total_warnings}")
    
    if total_errors > 0:
        print(f"\n❌ VALIDATION FAILED with {total_errors} errors")
        print(f"   Fix errors before proceeding with implementation")
        sys.exit(1)
    elif total_warnings > 0:
        print(f"\n⚠️  VALIDATION PASSED with {total_warnings} warnings")
        print(f"   Consider addressing warnings for better compliance")
    else:
        print(f"\n✅ VALIDATION PASSED - Full framework compliance")
    
    print(f"\n📚 Reference Documents:")
    print(f"   - PRD 30: Channel Usage Patterns")
    print(f"   - PRD 31: Implementation Folder Guidelines")
    print(f"   - Quick Reference: CHANNEL_VS_DOCS_QUICK_REFERENCE.md")

if __name__ == "__main__":
    main()
