#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/flare_edge_suggester.py"
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
FLARE Edge Suggester Tool

Automatically suggests relationships for FLARE headers based on:
- Markdown content analysis (internal links)
- Database queries (actors, channels, content relationships)
- TOON schema relationships (table foreign keys)
- Semantic similarity (if available)

Usage:
    python scripts/flare_edge_suggester.py --file docs/example.md
    python scripts/flare_edge_suggester.py --file docs/example.md --include-db
    python scripts/flare_edge_suggester.py --batch docs/
"""

import os
import re
import json
import yaml
import argparse
import sys
from pathlib import Path
from typing import List, Dict, Tuple, Optional
import logging

# Setup logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class FlareEdgeSuggester:
    def __init__(self, project_root: str, include_db: bool = False):
        self.project_root = Path(project_root)
        self.include_db = include_db
        self.toon_dir = self.project_root / "docs" / "toons"
        self.actors_file = self.project_root / "actors" / "registry.json"
        
    def suggest_edges(self, file_path: str) -> List[Dict]:
        """Suggest edges for a given file"""
        file_path = Path(file_path)
        if not file_path.exists():
            raise FileNotFoundError(f"File not found: {file_path}")
            
        suggestions = []
        
        # 1. Parse markdown content for internal links
        content_suggestions = self._analyze_markdown_links(file_path)
        suggestions.extend(content_suggestions)
        
        # 2. Check for table documentation relationships
        if file_path.name.startswith("lupo_"):
            table_suggestions = self._analyze_table_relationships(file_path)
            suggestions.extend(table_suggestions)
            
        # 3. Database-driven suggestions (if enabled)
        if self.include_db:
            db_suggestions = self._analyze_database_relationships(file_path)
            suggestions.extend(db_suggestions)
            
        # 4. Sort by weight and deduplicate
        suggestions = self._deduplicate_suggestions(suggestions)
        suggestions.sort(key=lambda x: x['weight'], reverse=True)
        
        return suggestions
    
    def _analyze_markdown_links(self, file_path: Path) -> List[Dict]:
        """Analyze markdown content for internal links"""
        suggestions = []
        
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
        except Exception as e:
            logger.error(f"Error reading {file_path}: {e}")
            return suggestions
            
        # Extract markdown links: [text](path)
        link_pattern = r'\[([^\]]+)\]\(([^)]+)\)'
        links = re.findall(link_pattern, content)
        
        for text, target in links:
            # Skip external links and anchors
            if target.startswith(('http://', 'https://', '#', 'mailto:')):
                continue
                
            # Convert relative paths to absolute from project root
            if target.startswith('./'):
                target = target[2:]
            elif not target.startswith('/'):
                # Relative to current file directory
                current_dir = file_path.parent.relative_to(self.project_root)
                target = str(current_dir / target)
                
            # Normalize path
            target_path = self.project_root / target
            
            # Only suggest if target exists and is a markdown file
            if target_path.exists() and target_path.suffix == '.md':
                # Determine weight based on context
                weight = self._calculate_link_weight(text, content)
                
                suggestions.append({
                    'to': target,
                    'type': 'references',
                    'weight': weight,
                    'reason': f'Internal link: "{text}"',
                    'source': 'content_analysis'
                })
                
        return suggestions
    
    def _calculate_link_weight(self, link_text: str, content: str) -> float:
        """Calculate weight for a link based on context"""
        # Primary references (in headers, first paragraph)
        if content.startswith(f'# {link_text}') or content.split('\n\n')[0].find(link_text) != -1:
            return 1.0
            
        # Important references (bold, emphasized)
        if f'**{link_text}**' in content or f'_{link_text}_' in content:
            return 0.9
            
        # Standard references
        return 0.8
    
    def _analyze_table_relationships(self, file_path: Path) -> List[Dict]:
        """Analyze table documentation for relationships using TOON files"""
        suggestions = []
        
        # Extract table name from filename
        table_match = re.match(r'lupo_(.+)\.md', file_path.name)
        if not table_match:
            return suggestions
            
        table_name = table_match.group(1)
        toon_file = self.toon_dir / f"lupo_{table_name}.toon.json"
        
        if not toon_file.exists():
            logger.warning(f"TOON file not found for table: {table_name}")
            return suggestions
            
        try:
            with open(toon_file, 'r', encoding='utf-8') as f:
                toon_data = json.load(f)
        except Exception as e:
            logger.error(f"Error reading TOON file {toon_file}: {e}")
            return suggestions
            
        # Analyze foreign key relationships
        columns = toon_data.get('columns', {})
        for column_name, column_info in columns.items():
            if column_info.get('references'):
                referenced_table = column_info['references']
                referenced_doc = f"docs/doctrine/database/lupo_{referenced_table}.md"
                
                suggestions.append({
                    'to': referenced_doc,
                    'type': 'table_relationship',
                    'weight': 0.9,
                    'reason': f'Foreign key: {column_name} references {referenced_table}',
                    'source': 'toon_schema'
                })
                
        return suggestions
    
    def _analyze_database_relationships(self, file_path: Path) -> List[Dict]:
        """Analyze database relationships (placeholder for DB integration)"""
        suggestions = []
        
        # This would connect to the database and query:
        # - lupo_contents for related files
        # - lupo_actors for actor relationships
        # - Shared semantic_tags, traits, etc.
        
        # For now, return actor-based suggestions from registry
        if self.actors_file.exists():
            try:
                with open(self.actors_file, 'r', encoding='utf-8') as f:
                    actors = json.load(f)
                    
                # Check if file mentions specific actors
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read().lower()
                    
                for actor in actors:
                    actor_name = actor.get('display_name', '').lower()
                    actor_slug = actor.get('slug', '').lower()
                    
                    if actor_name in content or actor_slug in content:
                        suggestions.append({
                            'to': 'actors/registry.json',
                            'type': 'references',
                            'weight': 0.7,
                            'reason': f'References actor: {actor.get("display_name", "")}',
                            'source': 'actor_registry'
                        })
                        
            except Exception as e:
                logger.error(f"Error reading actors registry: {e}")
                
        return suggestions
    
    def _deduplicate_suggestions(self, suggestions: List[Dict]) -> List[Dict]:
        """Remove duplicate suggestions"""
        seen = set()
        unique_suggestions = []
        
        for suggestion in suggestions:
            key = (suggestion['to'], suggestion['type'])
            if key not in seen:
                seen.add(key)
                unique_suggestions.append(suggestion)
                
        return unique_suggestions
    
    def format_suggestions(self, suggestions: List[Dict]) -> str:
        """Format suggestions as YAML snippet"""
        if not suggestions:
            return "# No edge suggestions found"
            
        yaml_lines = ["# Suggested edges (review before adding):"]
        yaml_lines.append("outbound_edges:")
        
        for suggestion in suggestions:
            edge = {
                'to': suggestion['to'],
                'type': suggestion['type'],
                'weight': suggestion['weight']
            }
            
            # Add metadata as comments
            if 'reason' in suggestion:
                yaml_lines.append(f"  # {suggestion['reason']} ({suggestion.get('source', 'unknown')})")
                
            yaml_lines.append(f"  - {yaml.dump(edge, default_flow_style=False).strip()}")
            
        return '\n'.join(yaml_lines)

def main():
    parser = argparse.ArgumentParser(description='FLARE Edge Suggester')
    parser.add_argument('--file', required=True, help='File to analyze')
    parser.add_argument('--include-db', action='store_true', help='Include database-driven suggestions')
    parser.add_argument('--format', choices=['yaml', 'json'], default='yaml', help='Output format')
    parser.add_argument('--project-root', default='.', help='Project root directory')
    
    args = parser.parse_args()
    
    try:
        suggester = FlareEdgeSuggester(args.project_root, args.include_db)
        suggestions = suggester.suggest_edges(args.file)
        
        if args.format == 'json':
            print(json.dumps(suggestions, indent=2))
        else:
            print(suggester.format_suggestions(suggestions))
            
    except Exception as e:
        logger.error(f"Error: {e}")
        sys.exit(1)

if __name__ == '__main__':
    main()