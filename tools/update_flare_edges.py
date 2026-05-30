#!/usr/bin/env python3
"""
FLARE Batch Update Tool

Batch operations for FLARE edge management:
- Scan all .md files for edge suggestions
- Validate existing edges
- Generate bulk update reports
- Apply approved changes with authorization

Usage:
    python tools/update_flare_edges.py --scan docs/
    python tools/update_flare_edges.py --validate docs/
    python tools/update_flare_edges.py --update docs/ --actor-id 10000
"""

import os
import re
import json
import yaml
import argparse
import sys
from pathlib import Path
from typing import List, Dict, Tuple, Optional, Set
import logging
from datetime import datetime

# Setup logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class FlareBatchUpdater:
    def __init__(self, project_root: str, actor_id: Optional[int] = None):
        self.project_root = Path(project_root)
        self.actor_id = actor_id
        self.actors_file = self.project_root / "actors" / "registry.json"
        self.actors = self._load_actors()
        
    def _load_actors(self) -> Dict:
        """Load actors registry for authorization"""
        try:
            with open(self.actors_file, 'r', encoding='utf-8') as f:
                return json.load(f)
        except Exception as e:
            logger.error(f"Error loading actors registry: {e}")
            return {}
    
    def _can_update_doctrine(self) -> bool:
        """Check if current actor can update doctrine files"""
        if not self.actor_id:
            return False
            
        # Only high-level actors can update doctrine
        high_level_actors = {1, 10000}  # WOLFIE, Captain
        return self.actor_id in high_level_actors
    
    def scan_directory(self, directory: str) -> Dict:
        """Scan directory for edge suggestions"""
        directory = Path(directory)
        results = {
            'scanned_files': 0,
            'files_with_suggestions': 0,
            'total_suggestions': 0,
            'files': []
        }
        
        # Find all .md files
        md_files = list(directory.rglob("*.md"))
        
        for file_path in md_files:
            try:
                suggestions = self._get_file_suggestions(file_path)
                
                file_result = {
                    'path': str(file_path.relative_to(self.project_root)),
                    'suggestions': suggestions,
                    'suggestion_count': len(suggestions)
                }
                
                results['files'].append(file_result)
                results['scanned_files'] += 1
                
                if suggestions:
                    results['files_with_suggestions'] += 1
                    results['total_suggestions'] += len(suggestions)
                    
            except Exception as e:
                logger.error(f"Error scanning {file_path}: {e}")
                
        return results
    
    def validate_directory(self, directory: str) -> Dict:
        """Validate existing edges in directory"""
        directory = Path(directory)
        results = {
            'scanned_files': 0,
            'files_with_edges': 0,
            'total_edges': 0,
            'broken_edges': 0,
            'issues': []
        }
        
        md_files = list(directory.rglob("*.md"))
        
        for file_path in md_files:
            try:
                validation_result = self._validate_file_edges(file_path)
                
                results['scanned_files'] += 1
                
                if validation_result['has_edges']:
                    results['files_with_edges'] += 1
                    results['total_edges'] += validation_result['edge_count']
                    
                    if validation_result['broken_edges']:
                        results['broken_edges'] += len(validation_result['broken_edges'])
                        results['issues'].extend(validation_result['broken_edges'])
                        
            except Exception as e:
                logger.error(f"Error validating {file_path}: {e}")
                
        return results
    
    def update_directory(self, directory: str, dry_run: bool = True) -> Dict:
        """Update files with suggested edges"""
        if not self._can_update_doctrine():
            raise PermissionError("Actor ID not authorized to update doctrine files")
            
        directory = Path(directory)
        results = {
            'scanned_files': 0,
            'updated_files': 0,
            'total_edges_added': 0,
            'updates': []
        }
        
        md_files = list(directory.rglob("*.md"))
        
        for file_path in md_files:
            try:
                update_result = self._update_file_edges(file_path, dry_run)
                
                results['scanned_files'] += 1
                
                if update_result['updated']:
                    results['updated_files'] += 1
                    results['total_edges_added'] += update_result['edges_added']
                    results['updates'].append(update_result)
                    
            except Exception as e:
                logger.error(f"Error updating {file_path}: {e}")
                
        return results
    
    def _get_file_suggestions(self, file_path: Path) -> List[Dict]:
        """Get edge suggestions for a file (simplified version)"""
        suggestions = []
        
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
        except Exception as e:
            logger.error(f"Error reading {file_path}: {e}")
            return suggestions
            
        # Extract internal links
        link_pattern = r'\[([^\]]+)\]\(([^)]+)\)'
        links = re.findall(link_pattern, content)
        
        for text, target in links:
            if target.startswith(('http://', 'https://', '#', 'mailto:')):
                continue
                
            # Convert to project-relative path
            if not target.startswith('/'):
                current_dir = file_path.parent.relative_to(self.project_root)
                target = str(current_dir / target)
                
            target_path = self.project_root / target
            
            if target_path.exists() and target_path.suffix == '.md':
                suggestions.append({
                    'to': target,
                    'type': 'references',
                    'weight': 0.8,
                    'reason': f'Internal link: "{text}"',
                    'source': 'content_analysis'
                })
                
        return suggestions
    
    def _validate_file_edges(self, file_path: Path) -> Dict:
        """Validate existing edges in a file"""
        result = {
            'has_edges': False,
            'edge_count': 0,
            'broken_edges': []
        }
        
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
        except Exception as e:
            logger.error(f"Error reading {file_path}: {e}")
            return result
            
        # Parse YAML frontmatter
        if content.startswith('---'):
            try:
                # Find the end of frontmatter
                end_index = content.find('---', 3)
                if end_index == -1:
                    return result
                    
                frontmatter = content[3:end_index].strip()
                data = yaml.safe_load(frontmatter)
                
                # Check for flare.footer with outbound_edges
                if 'flare' in data and 'footer' in data['flare']:
                    footer = data['flare']['footer']
                    
                    if 'outbound_edges' in footer:
                        result['has_edges'] = True
                        edges = footer['outbound_edges']
                        result['edge_count'] = len(edges)
                        
                        # Validate each edge
                        for edge in edges:
                            if isinstance(edge, dict) and 'to' in edge:
                                target_path = self.project_root / edge['to']
                                if not target_path.exists():
                                    result['broken_edges'].append({
                                        'file': str(file_path.relative_to(self.project_root)),
                                        'edge': edge,
                                        'issue': 'Target file not found'
                                    })
                                    
            except yaml.YAMLError as e:
                logger.error(f"Error parsing YAML in {file_path}: {e}")
                
        return result
    
    def _update_file_edges(self, file_path: Path, dry_run: bool = True) -> Dict:
        """Update file with suggested edges"""
        result = {
            'updated': False,
            'edges_added': 0,
            'file_path': str(file_path.relative_to(self.project_root))
        }
        
        # Get suggestions
        suggestions = self._get_file_suggestions(file_path)
        if not suggestions:
            return result
            
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
        except Exception as e:
            logger.error(f"Error reading {file_path}: {e}")
            return result
            
        # Parse existing frontmatter
        if not content.startswith('---'):
            return result
            
        try:
            end_index = content.find('---', 3)
            if end_index == -1:
                return result
                
            frontmatter = content[3:end_index].strip()
            data = yaml.safe_load(frontmatter) or {}
            
            # Ensure flare.footer exists
            if 'flare' not in data:
                data['flare'] = {}
            if 'footer' not in data['flare']:
                data['flare']['footer'] = {}
                
            footer = data['flare']['footer']
            
            # Get existing outbound_edges
            existing_edges = footer.get('outbound_edges', [])
            existing_targets = {edge['to'] for edge in existing_edges if isinstance(edge, dict)}
            
            # Add new suggestions that don't exist
            new_edges = []
            for suggestion in suggestions:
                if suggestion['to'] not in existing_targets:
                    edge = {
                        'to': suggestion['to'],
                        'type': suggestion['type'],
                        'weight': suggestion['weight']
                    }
                    new_edges.append(edge)
                    
            if new_edges:
                # Add metadata
                footer['outbound_edges'] = existing_edges + new_edges
                footer['outbound_edges'].sort(key=lambda x: x.get('weight', 0), reverse=True)
                
                # Add update metadata
                if self.actor_id:
                    footer['last_updated_by'] = self.actor_id
                footer['last_updated_utc'] = datetime.now().strftime('%Y%m%d%H%M%S')
                
                # Generate new content
                new_frontmatter = yaml.dump(data, default_flow_style=False, sort_keys=False)
                new_content = f"---\n{new_frontmatter}---{content[end_index+3:]}"
                
                if not dry_run:
                    with open(file_path, 'w', encoding='utf-8') as f:
                        f.write(new_content)
                        
                result['updated'] = True
                result['edges_added'] = len(new_edges)
                result['new_edges'] = new_edges
                
        except Exception as e:
            logger.error(f"Error updating {file_path}: {e}")
            
        return result
    
    def generate_report(self, scan_results: Dict, validate_results: Optional[Dict] = None) -> str:
        """Generate a comprehensive report"""
        report = []
        report.append("# FLARE Edge Management Report")
        report.append(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
        report.append("")
        
        # Scan results
        report.append("## Scan Results")
        report.append(f"- Files scanned: {scan_results['scanned_files']}")
        report.append(f"- Files with suggestions: {scan_results['files_with_suggestions']}")
        report.append(f"- Total suggestions: {scan_results['total_suggestions']}")
        report.append("")
        
        # Top files with most suggestions
        top_files = sorted(scan_results['files'], key=lambda x: x['suggestion_count'], reverse=True)[:10]
        if top_files:
            report.append("### Top Files by Suggestion Count")
            for file_result in top_files:
                report.append(f"- {file_result['path']}: {file_result['suggestion_count']} suggestions")
            report.append("")
        
        # Validation results
        if validate_results:
            report.append("## Validation Results")
            report.append(f"- Files with edges: {validate_results['files_with_edges']}")
            report.append(f"- Total edges: {validate_results['total_edges']}")
            report.append(f"- Broken edges: {validate_results['broken_edges']}")
            report.append("")
            
            if validate_results['issues']:
                report.append("### Broken Edges")
                for issue in validate_results['issues'][:20]:  # Limit to first 20
                    report.append(f"- {issue['file']}: {issue['edge']['to']} ({issue['issue']})")
                report.append("")
        
        # Recommendations
        report.append("## Recommendations")
        if scan_results['total_suggestions'] > 0:
            report.append(f"- Consider applying {scan_results['total_suggestions']} edge suggestions")
            report.append("- Run with --update to apply changes (requires authorization)")
        else:
            report.append("- No edge suggestions found")
            
        if validate_results and validate_results['broken_edges'] > 0:
            report.append(f"- Fix {validate_results['broken_edges']} broken edges")
            
        report.append("")
        report.append("---")
        report.append("*Report generated by FLARE Batch Update Tool*")
        
        return "\n".join(report)

def main():
    parser = argparse.ArgumentParser(description='FLARE Batch Update Tool')
    parser.add_argument('--scan', help='Directory to scan for suggestions')
    parser.add_argument('--validate', help='Directory to validate edges')
    parser.add_argument('--update', help='Directory to update with suggestions')
    parser.add_argument('--actor-id', type=int, help='Actor ID for authorization (required for updates)')
    parser.add_argument('--dry-run', action='store_true', help='Dry run mode (do not make changes)')
    parser.add_argument('--project-root', default='.', help='Project root directory')
    parser.add_argument('--output', help='Output file for report')
    
    args = parser.parse_args()
    
    updater = FlareBatchUpdater(args.project_root, args.actor_id)
    
    if args.scan:
        logger.info(f"Scanning directory: {args.scan}")
        scan_results = updater.scan_directory(args.scan)
        
        if args.validate:
            logger.info(f"Validating directory: {args.validate}")
            validate_results = updater.validate_directory(args.validate)
        else:
            validate_results = None
            
        report = updater.generate_report(scan_results, validate_results)
        
        if args.output:
            with open(args.output, 'w', encoding='utf-8') as f:
                f.write(report)
            logger.info(f"Report saved to: {args.output}")
        else:
            print(report)
            
    elif args.update:
        if not args.actor_id:
            logger.error("--actor-id required for updates")
            sys.exit(1)
            
        logger.info(f"Updating directory: {args.update}")
        results = updater.update_directory(args.update, dry_run=args.dry_run)
        
        print(f"Files scanned: {results['scanned_files']}")
        print(f"Files updated: {results['updated_files']}")
        print(f"Edges added: {results['total_edges_added']}")
        
        if args.dry_run:
            print("(Dry run mode - no changes made)")
            
    else:
        parser.print_help()

if __name__ == '__main__':
    main()
