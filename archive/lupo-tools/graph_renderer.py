"""
graph_renderer.py - (v4.0.41 Alpha)
Purpose: Renders semantic graphs from FLIP headers/footers using NetworkX.
Author: Antigravity (actor_id 1003)
"""

import sys
import yaml
import json
import os
import glob
try:
    import networkx as nx
except ImportError:
    nx = None

class FlipGraphRenderer:
    def __init__(self, repo_path):
        self.repo_path = repo_path
        self.graph = nx.DiGraph() if nx else None
        self.nodes = {}

    def scan_files(self):
        """Scans all MD, PHP, SQL files for FLIP headers/footers."""
        patterns = ["**/*.md", "**/*.php", "**/*.sql"]
        for pattern in patterns:
            for file_path in glob.glob(os.path.join(self.repo_path, pattern), recursive=True):
                self._extract_metadata(file_path)

    def _extract_metadata(self, file_path):
        """Extracts YAML blocks and adds to graph."""
        rel_path = os.path.relpath(file_path, self.repo_path)
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                # Simplified YAML block splitting for prototype
                if '---' in content:
                    parts = content.split('---')
                    for part in parts:
                        if 'flip.footer' in part or 'wolfie.headers' in part:
                            data = yaml.safe_load(part)
                            self._process_data(rel_path, data)
        except Exception:
            pass

    def _process_data(self, node_id, data):
        if not self.graph: return
        
        # Add node
        mood = data.get('mood_vector', 'FFFFFF')
        self.graph.add_node(node_id, mood=mood)
        
        # Add edges from footer
        if 'flip.footer' in data:
            footer = data['flip.footer']
            if 'inbound_edges' in footer:
                for edge in footer['inbound_edges']:
                    source = edge.get('source')
                    if source:
                        self.graph.add_edge(source, node_id, type=edge.get('edge_type'))

    def render_json(self):
        """Returns JSON representation for VSX integration."""
        if not self.graph:
            return json.dumps({"error": "NetworkX not available"})
        
        return json.dumps(nx.node_link_data(self.graph))

if __name__ == "__main__":
    renderer = FlipGraphRenderer(".")
    renderer.scan_files()
    print(renderer.render_json())
