#!/usr/bin/env python3
"""
Wolfie Header Generator v4.2
Read-only projection generator for Lupopedia database content.

This script reads from lupo_contents and lupo_edges tables to generate
Wolfie Headers v4.2 for filesystem-backed content files.

The generator NEVER:
- Modifies the database schema
- Drops columns or fields
- Writes back to the database
- Infers schema changes

The generator ONLY:
- Reads metadata from database
- Renders read-only headers
- Inserts headers into files
"""

import os
import sys
import argparse
import json
import re
from typing import Dict, List, Optional, Any
from pathlib import Path
import mysql.connector
from mysql.connector import Error
import yaml


class HeaderGenerator:
    """Wolfie Header Generator v4.2"""
    
    def __init__(self):
        self.db_connection = None
        self.config = self._load_config()
    
    def _load_config(self) -> Dict[str, Any]:
        """Load database configuration from environment variables"""
        return {
            'host': os.getenv('DB_HOST', 'localhost'),
            'database': os.getenv('DB_NAME', 'lupopedia'),
            'user': os.getenv('DB_USER', 'lupopedia'),
            'password': os.getenv('DB_PASSWORD', ''),
            'port': int(os.getenv('DB_PORT', '3306'))
        }
    
    def connect_database(self) -> bool:
        """Connect to the Lupopedia database"""
        try:
            self.db_connection = mysql.connector.connect(**self.config)
            if self.db_connection.is_connected():
                print(f"Connected to database: {self.config['database']}")
                return True
        except Error as e:
            print(f"Database connection error: {e}")
            return False
        return False
    
    def disconnect_database(self):
        """Close database connection"""
        if self.db_connection and self.db_connection.is_connected():
            self.db_connection.close()
            print("Database connection closed")
    
    def get_content_by_path(self, file_path_from_root: str) -> Optional[Dict[str, Any]]:
        """
        Query lupo_contents by file_path_from_root
        
        Args:
            file_path_from_root: Relative path from repository root
            
        Returns:
            Content row data or None if not found
        """
        if not self.db_connection:
            return None
        
        query = """
        SELECT 
            content_id,
            file_path_from_root,
            title,
            slug,
            description,
            body,
            content_type,
            format,
            status,
            visibility,
            version_number,
            dialog_notes,
            tags,
            content_sections,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis,
            content_parent_id,
            federation_node_id,
            group_id,
            user_id,
            custom_path,
            seo_keywords,
            content_url,
            default_collection_id,
            source_url,
            source_title,
            is_template,
            view_count,
            share_count,
            utc_cycle,
            triage_status,
            triage_notes,
            is_active
        FROM lupo_contents 
        WHERE file_path_from_root = %s AND is_deleted = 0
        """
        
        try:
            cursor = self.db_connection.cursor(dictionary=True)
            cursor.execute(query, (file_path_from_root,))
            result = cursor.fetchone()
            cursor.close()
            return result
        except Error as e:
            print(f"Error querying content: {e}")
            return None
    
    def get_edges_for_content(self, content_id: int) -> List[Dict[str, Any]]:
        """
        Query lupo_edges for all edges where the content_id appears
        
        Args:
            content_id: The content ID to find edges for
            
        Returns:
            List of edge rows
        """
        if not self.db_connection:
            return []
        
        query = """
        SELECT 
            edge_id,
            relationship_type,
            edge_type,
            left_object_type,
            left_object_id,
            right_object_type,
            right_object_id,
            bidirectional,
            context_scope,
            semantic_weight,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_edges 
        WHERE (left_object_type = 'content' AND left_object_id = %s)
           OR (right_object_type = 'content' AND right_object_id = %s)
           AND is_deleted = 0
        ORDER BY created_ymdhis DESC
        """
        
        try:
            cursor = self.db_connection.cursor(dictionary=True)
            cursor.execute(query, (content_id, content_id))
            result = cursor.fetchall()
            cursor.close()
            return result
        except Error as e:
            print(f"Error querying edges: {e}")
            return []
    
    def extract_content_sections_from_body(self, body: str) -> List[Dict[str, str]]:
        """
        Extract content sections from file body if not cached in database
        
        Args:
            body: The file body content
            
        Returns:
            List of section dictionaries with title and anchor
        """
        if not body:
            return []
        
        sections = []
        # Match markdown headers (# ## ### etc.)
        header_pattern = r'^(#{1,6})\s+(.+)$'
        
        for line in body.split('\n'):
            match = re.match(header_pattern, line.strip())
            if match:
                level = len(match.group(1))
                title = match.group(2).strip()
                # Create anchor from title
                anchor = '#' + re.sub(r'[^a-zA-Z0-9\s-]', '', title).lower().replace(' ', '-')
                sections.append({
                    'title': title,
                    'anchor': anchor
                })
        
        return sections
    
    def render_header(self, content_data: Dict[str, Any], edges: List[Dict[str, Any]]) -> str:
        """
        Render a Wolfie Header v4.2 block
        
        Args:
            content_data: Content row from lupo_contents
            edges: List of edge rows from lupo_edges
            
        Returns:
            Formatted header string
        """
        # Get content sections from database or extract from body
        content_sections = content_data.get('content_sections')
        if content_sections:
            try:
                content_sections = json.loads(content_sections) if isinstance(content_sections, str) else content_sections
            except (json.JSONDecodeError, TypeError):
                content_sections = []
        else:
            content_sections = self.extract_content_sections_from_body(content_data.get('body', ''))
        
        # Get tags from database
        tags = content_data.get('tags')
        if tags:
            try:
                tags = json.loads(tags) if isinstance(tags, str) else tags
            except (json.JSONDecodeError, TypeError):
                tags = []
        else:
            tags = []
        
        # Render content sections
        sections_yaml = ""
        if content_sections:
            sections_yaml = "content_sections:\n"
            for section in content_sections:
                sections_yaml += f"  - title: \"{section.get('title', '')}\"\n"
                sections_yaml += f"    anchor: \"{section.get('anchor', '')}\"\n"
        
        # Render edges
        edges_yaml = ""
        if edges:
            edges_yaml = "edges:\n"
            for edge in edges:
                edges_yaml += f"  - type: {edge.get('relationship_type', '')}\n"
                edges_yaml += f"    edge_type: {edge.get('edge_type', '')}\n"
                edges_yaml += f"    left: {edge.get('left_object_type', '')}:{edge.get('left_object_id', '')}\n"
                edges_yaml += f"    right: {edge.get('right_object_type', '')}:{edge.get('right_object_id', '')}\n"
                edges_yaml += f"    bidirectional: {bool(edge.get('bidirectional', 0))}\n"
                if edge.get('context_scope'):
                    edges_yaml += f"    context: {edge.get('context_scope', '')}\n"
                if edge.get('semantic_weight') is not None:
                    edges_yaml += f"    semantic_weight: {float(edge.get('semantic_weight', 0.0)):.2f}\n"
        
        # Render tags
        tags_yaml = ""
        if tags:
            tags_yaml = "tags: [" + ", ".join([f'"{tag}"' for tag in tags]) + "]\n"
        
        # Build complete header
        header = f"""/* wolfie_header v4.2
   # This header is generated from the Lupopedia database.
   # Do not edit this header manually.
   # All metadata and relationships are maintained in the database.
   # This header is a read-only projection for grep and human reference.

   file_path_from_root: {content_data.get('file_path_from_root', '')}

{sections_yaml}   version_number: {content_data.get('version_number', 1)}
   dialog_notes: {content_data.get('dialog_notes', '')}
   status: {content_data.get('status', 'draft')}
{tags_yaml}
{edges_yaml}*/
"""
        
        return header
    
    def insert_header_into_file(self, file_path: str, header: str) -> bool:
        """
        Insert header at the top of the file
        
        Args:
            file_path: Path to the file
            header: Header content to insert
            
        Returns:
            True if successful, False otherwise
        """
        try:
            file_path_obj = Path(file_path)
            if not file_path_obj.exists():
                print(f"File not found: {file_path}")
                return False
            
            # Read existing content
            with open(file_path_obj, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Remove existing Wolfie header if present
            header_pattern = r'/\* wolfie_header v4\.2.*?\*/'
            content = re.sub(header_pattern, '', content, flags=re.DOTALL)
            
            # Insert new header at the top
            new_content = header + '\n\n' + content.strip()
            
            # Write back to file
            with open(file_path_obj, 'w', encoding='utf-8') as f:
                f.write(new_content)
            
            print(f"Header inserted into: {file_path}")
            return True
            
        except Exception as e:
            print(f"Error inserting header into {file_path}: {e}")
            return False
    
    def generate_header_for_path(self, file_path_from_root: str) -> bool:
        """
        Generate header for a single file path
        
        Args:
            file_path_from_root: Relative path from repository root
            
        Returns:
            True if successful, False otherwise
        """
        print(f"Generating header for: {file_path_from_root}")
        
        # Get content data
        content_data = self.get_content_by_path(file_path_from_root)
        if not content_data:
            print(f"Content not found for path: {file_path_from_root}")
            return False
        
        # Get edges
        edges = self.get_edges_for_content(content_data['content_id'])
        
        # Render header
        header = self.render_header(content_data, edges)
        
        # Insert into file
        full_file_path = Path(file_path_from_root)
        return self.insert_header_into_file(str(full_file_path), header)
    
    def generate_headers_for_all(self) -> int:
        """
        Generate headers for all filesystem-backed content
        
        Returns:
            Number of headers generated
        """
        if not self.db_connection:
            return 0
        
        # Get all content with file paths
        query = """
        SELECT file_path_from_root 
        FROM lupo_contents 
        WHERE file_path_from_root IS NOT NULL 
        AND file_path_from_root != ''
        AND is_deleted = 0
        ORDER BY file_path_from_root
        """
        
        try:
            cursor = self.db_connection.cursor()
            cursor.execute(query)
            results = cursor.fetchall()
            cursor.close()
            
            generated_count = 0
            for (file_path,) in results:
                if self.generate_header_for_path(file_path):
                    generated_count += 1
            
            print(f"Generated {generated_count} headers")
            return generated_count
            
        except Error as e:
            print(f"Error getting all content paths: {e}")
            return 0


def main():
    """Main function with command line interface"""
    parser = argparse.ArgumentParser(description='Wolfie Header Generator v4.2')
    parser.add_argument('--path', type=str, help='Generate header for specific file path')
    parser.add_argument('--all', action='store_true', help='Generate headers for all filesystem-backed content')
    
    args = parser.parse_args()
    
    if not args.path and not args.all:
        parser.print_help()
        return
    
    generator = HeaderGenerator()
    
    # Connect to database
    if not generator.connect_database():
        print("Failed to connect to database")
        return
    
    try:
        if args.path:
            generator.generate_header_for_path(args.path)
        elif args.all:
            generator.generate_headers_for_all()
    finally:
        generator.disconnect_database()


if __name__ == '__main__':
    main()
