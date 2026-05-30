#!/usr/bin/env python3
"""
Validation script for Lupopedia Five-Layer Documentation Architecture
Ensures all implementation folders comply with the required schemas and validation contract.

Exit codes:
0 - All validations pass
1 - Validation failed (errors present)
"""

import os
import re
import yaml
import json
from pathlib import Path
from datetime import datetime

class ImplementationValidator:
    def __init__(self, root_path):
        self.root_path = Path(root_path)
        self.errors = []
        self.warnings = []
        
        # Field requirements by (artifact_type, artifact_kind)
        self.REQUIRED_FIELDS_BY_TYPE = {
            ('prd', 'requirements'): {
                'required': ['prd_id', 'prd_slug', 'title', 'status'],
                'optional': ['parent_edges_ref', 'content_id']
            },
            ('prd', 'architecture'): {
                'required': ['prd_id', 'prd_slug', 'title', 'status'],
                'optional': ['parent_edges_ref', 'content_id']
            },
            ('implementation', 'documentation'): {
                'required': ['parent_prd', 'status', 'version'],
                'optional': ['content_id', 'doc_arch_version']
            },
            ('implementation', 'README'): {
                'required': ['parent_prd', 'status'],
                'optional': ['content_id']
            },
            ('doctrine', 'constitutional'): {
                'required': [],  # No PRD-specific fields
                'optional': ['content_id']
            },
            ('doctrine', 'reference'): {
                'required': [],
                'optional': ['content_id']
            },
            ('doctrine', 'decisions'): {
                'required': [],
                'optional': ['content_id']
            },
            ('discussion', 'thread'): {
                'required': ['channel_id', 'thread_id'],
                'optional': ['context_id']
            },
            ('discussion', 'message'): {
                'required': ['channel_id', 'thread_id'],
                'optional': ['context_id']
            },
            ('changelog', 'version_specific'): {
                'required': [],  # No PRD ID needed
                'optional': ['content_id']
            },
            ('documentation', 'table_schema'): {
                'required': [],  # No PRD ID needed
                'optional': ['content_id']
            },
            ('documentation', 'implementation'): {
                'required': ['parent_prd'],  # Links back to PRD
                'optional': ['content_id']
            }
        }
        
    def validate_required_fields_by_type(self, headers, artifact_type, artifact_kind, impl_name):
        """Validate required fields based on artifact type/kind."""
        key = (artifact_type, artifact_kind)
        rules = self.REQUIRED_FIELDS_BY_TYPE.get(key, {'required': [], 'optional': []})
        
        # Check required fields
        for field in rules['required']:
            if field not in headers or not headers[field]:
                self.errors.append(f"{impl_name}: Missing required field '{field}' for {artifact_type}/{artifact_kind}")
        
        # Special validation for PRD files
        if artifact_type == 'prd':
            if 'prd_id' not in headers:
                self.errors.append(f"{impl_name}: PRD files must have prd_id")
            if 'prd_slug' not in headers:
                self.errors.append(f"{impl_name}: PRD files must have prd_slug")
        
        # Special validation for discussion threads
        if artifact_type == 'discussion':
            if 'channel_id' not in headers:
                self.errors.append(f"{impl_name}: Discussion files must have channel_id")
            if 'thread_id' not in headers:
                self.errors.append(f"{impl_name}: Discussion files must have thread_id")
        
        # Special validation for implementation files
        if artifact_type == 'implementation':
            if 'parent_prd' not in headers:
                self.errors.append(f"{impl_name}: Implementation files must have parent_prd")
        
        return len(self.errors) == 0
    
    def get_headers_from_frontmatter(self, data):
        """Extract headers from frontmatter (supports nested and flat)"""
        if 'lupopedia' in data and 'headers' in data['lupopedia']:
            return data['lupopedia']['headers']
        return data
        
    def validate_prd_slug_format(self, prd_slug, impl_name):
        """Validate prd_slug format (lowercase, underscores, no spaces)"""
        if not re.match(r'^[a-z][a-z0-9_]*$', prd_slug):
            self.errors.append(f"{impl_name}: prd_slug '{prd_slug}' must be lowercase with underscores (no spaces, no hyphens)")
            
    def validate_status_values(self, status, valid_values, context, impl_name):
        """Validate status against allowed values"""
        if status not in valid_values:
            self.errors.append(f"{impl_name}: Invalid {context} status '{status}'. Must be one of: {valid_values}")
        
    def validate_duplicate_prd_ids(self):
        """Check for duplicate prd_id values across all PRDs"""
        prd_ids = {}
        prd_dir = self.root_path / "docs/prd"
        
        if not prd_dir.exists():
            return
            
        for prd_file in prd_dir.glob("*.md"):
            try:
                with open(prd_file, 'r', encoding='utf-8') as f:
                    content = f.read()
                    # Extract frontmatter
                    yaml_match = re.search(r'^---\n(.*?)\n---', content, re.MULTILINE | re.DOTALL)
                    if yaml_match:
                        header = yaml.safe_load(yaml_match.group(1))
                        
                        # Use safe header extraction
                        headers = self.get_headers_from_frontmatter(header)
                        prd_id = headers.get('prd_id')
                        artifact_type = headers.get('artifact_type')
                        artifact_kind = headers.get('artifact_kind')
                        
                        # Handle missing artifact_type gracefully
                        if artifact_type is None:
                            # Default based on filename
                            if prd_file.name.startswith(('0', '1', '2', '3', '4', '5', '6', '7', '8', '9')):
                                if prd_file.name == '00_root_constitutional_system_requirements.md':
                                    artifact_type = 'doctrine'
                                else:
                                    artifact_type = 'prd'
                            else:
                                artifact_type = 'documentation'
                        
                        # Validate prd_slug format if present
                        prd_slug = headers.get('prd_slug')
                        if prd_slug:
                            self.validate_prd_slug_format(prd_slug, prd_file.name)
                        
                        # Validate status for PRDs
                        if artifact_type == 'prd':
                            status = headers.get('status')
                            if status:
                                valid_prd_statuses = ['draft', 'review', 'approved', 'implemented', 'active', 'deprecated']
                                self.validate_status_values(status, valid_prd_statuses, 'PRD', prd_file.name)
                        
                        # Check for duplicates only
                        if prd_id:
                            if prd_id in prd_ids:
                                self.errors.append(f"Duplicate prd_id {prd_id} found in {prd_file.name} and {prd_ids[prd_id].name}")
                            else:
                                prd_ids[prd_id] = prd_file
            except Exception as e:
                self.errors.append(f"Error checking {prd_file.name}: {str(e)}")
                
    def validate_all_implementations(self):
        """Validate all implementation folders"""
        implementations_dir = self.root_path / "docs/implementations"
        
        if not implementations_dir.exists():
            self.errors.append("Implementations directory not found")
            return False
            
        for item in implementations_dir.iterdir():
            if item.is_dir() and not item.name.startswith('_') and not item.name.startswith('.'):
                self.validate_implementation_folder(item)
                
        return len(self.errors) == 0
        
    def validate_implementation_folder(self, impl_path):
        """Validate a single implementation folder"""
        print(f"\nValidating: {impl_path.name}")
        
        # Check required files exist
        required_files = ['README.md', 'authors.md', 'edges.md']
        required_dirs = ['discussions']
        
        for req_file in required_files:
            file_path = impl_path / req_file
            if not file_path.exists():
                self.errors.append(f"{impl_path.name}: Missing required file {req_file}")
                
        for req_dir in required_dirs:
            dir_path = impl_path / req_dir
            if not dir_path.exists():
                self.errors.append(f"{impl_path.name}: Missing required directory {req_dir}")
            else:
                # Check THREAD_INDEX.md exists in discussions
                thread_index = dir_path / "THREAD_INDEX.md"
                if not thread_index.exists():
                    self.errors.append(f"{impl_path.name}: Missing discussions/THREAD_INDEX.md")
        
        # Validate README front-matter
        readme_path = impl_path / "README.md"
        if readme_path.exists():
            self.validate_readme_frontmatter(readme_path, impl_path.name)
            
        # Validate authors.md schema
        authors_path = impl_path / "authors.md"
        if authors_path.exists():
            self.validate_authors_schema(authors_path, impl_path.name)
            
        # Validate edges.md sections
        edges_path = impl_path / "edges.md"
        if edges_path.exists():
            self.validate_edges_sections(edges_path, impl_path.name)
            
    def validate_readme_frontmatter(self, readme_path, impl_name):
        """Validate README front-matter schema"""
        try:
            content = readme_path.read_text(encoding='utf-8')
            
            # Extract front-matter
            if content.startswith('---'):
                try:
                    end = content.find('---', 3)
                    if end == -1:
                        self.errors.append(f"{impl_name}: Invalid front-matter format in README")
                        return
                        
                    front_matter = content[3:end]
                    data = yaml.safe_load(front_matter)
                    
                    # Use safe header extraction
                    headers = self.get_headers_from_frontmatter(data)
                    if not headers:
                        self.errors.append(f"{impl_name}: README missing lupopedia.headers")
                        return
                    
                    # Get artifact type and kind for conditional validation
                    artifact_type = headers.get('artifact_type', 'implementation')
                    artifact_kind = headers.get('artifact_kind', 'documentation')
                    
                    # Handle missing artifact_type gracefully
                    if artifact_type is None:
                        self.warnings.append(f"{impl_name}: Missing artifact_type, using 'implementation' as default")
                        artifact_type = 'implementation'
                    
                    # Validate fields based on type
                    self.validate_required_fields_by_type(headers, artifact_type, artifact_kind, impl_name)
                            
                    # Validate status values for implementation files
                    if artifact_type == 'implementation':
                        valid_statuses = ['not_started', 'in_progress', 'complete', 'blocked', 'deprecated']
                        status = headers.get('status')
                        if status:
                            self.validate_status_values(status, valid_statuses, 'implementation', impl_name)
                        
                    # Validate parent_prd exists for implementation files
                    if 'parent_prd' in headers:
                        prd_path_str = headers['parent_prd']
                        # Handle absolute repo paths (starting with /)
                        if prd_path_str.startswith('/'):
                            prd_path_str = prd_path_str[1:]  # Remove leading slash
                        prd_path = self.root_path / prd_path_str
                        if not prd_path.exists():
                            self.errors.append(f"{impl_name}: parent_prd points to non-existent file: {headers['parent_prd']}")
                            
                except ValueError:
                    self.errors.append(f"{impl_name}: Invalid front-matter format in README")
                    
        except Exception as e:
            self.errors.append(f"{impl_name}: Error reading README: {str(e)}")
            
    def validate_authors_schema(self, authors_path, impl_name):
        """Validate authors.md table schema"""
        try:
            content = authors_path.read_text(encoding='utf-8')
            
            # Look for table with required columns
            lines = content.split('\n')
            table_start = -1
            
            for i, line in enumerate(lines):
                if '| actor_id | actor_type | role | scope | first_contribution_utc | last_contribution_utc |' in line:
                    table_start = i
                    break
                    
            if table_start == -1:
                self.errors.append(f"{impl_name}: authors.md missing required table header")
                return
                
            # Check for at least one data row
            if len(lines) <= table_start + 2:
                self.warnings.append(f"{impl_name}: authors.md table appears to be empty")
                
        except Exception as e:
            self.errors.append(f"{impl_name}: Error validating authors.md: {str(e)}")
            
    def validate_edges_sections(self, edges_path, impl_name):
        """Validate edges.md has required sections"""
        try:
            content = edges_path.read_text(encoding='utf-8')
            
            required_sections = [
                '## Database Edges',
                '## Code Edges',
                '## Documentation Edges',
                '## UI Edges',
                '## External Edges'
            ]
            
            for section in required_sections:
                if section not in content:
                    self.errors.append(f"{impl_name}: edges.md missing required section: {section}")
                    
        except Exception as e:
            self.errors.append(f"{impl_name}: Error validating edges.md: {str(e)}")
            
    def generate_report(self):
        """Generate validation report"""
        report = {
            'timestamp': datetime.now().isoformat(),
            'errors': self.errors,
            'warnings': self.warnings,
            'summary': {
                'total_errors': len(self.errors),
                'total_warnings': len(self.warnings),
                'passed': len(self.errors) == 0
            }
        }
        
        return report

if __name__ == "__main__":
    import sys
    
    root_path = sys.argv[1] if len(sys.argv) > 1 else "."
    validator = ImplementationValidator(root_path)
    
    print("Lupopedia Implementation Validator")
    print("=" * 40)
    
    # Check for duplicate PRD IDs first
    validator.validate_duplicate_prd_ids()
    
    passed = validator.validate_all_implementations()
    report = validator.generate_report()
    
    print("\n" + "=" * 40)
    print(f"Validation Result: {'PASSED' if passed else 'FAILED'}")
    print(f"Errors: {report['summary']['total_errors']}")
    print(f"Warnings: {report['summary']['total_warnings']}")
    
    if report['errors']:
        print("\nErrors:")
        for error in report['errors']:
            print(f"  - {error}")
            
    if report['warnings']:
        print("\nWarnings:")
        for warning in report['warnings']:
            print(f"  - {warning}")
            
    # Save report
    report_path = Path(root_path) / "validation_report.json"
    report_path.write_text(json.dumps(report, indent=2))
    print(f"\nReport saved to: {report_path}")
    
    sys.exit(0 if passed else 1)
