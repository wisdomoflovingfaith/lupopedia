#!/usr/bin/env python3
"""
Agent Alias Resolver - Normalizes agent names across contexts

Handles:
- Core agent aliases (gemni → gemini, vish → vishwakarma)
- Cascade agent variants (cascade, castcade, windsurf)
- Role-based naming (rose ↔ dialog, thoth ↔ truth, lilith ↔ audit, ara ↔ grok)
- Shorthand and human typing drift
"""

import json
import sys
from pathlib import Path
from typing import Dict, Optional, List


class AgentAliasResolver:
    """Resolves agent name variants to canonical names"""
    
    def __init__(self, alias_file: Optional[Path] = None):
        """Initialize resolver with alias configuration"""
        if alias_file is None:
            alias_file = Path(__file__).parent.parent / "lupo-config" / "naming_aliases.json"
        
        self.alias_file = alias_file
        self.aliases = self._load_aliases()
        self.reverse_map = self._build_reverse_map()
    
    def _load_aliases(self) -> Dict:
        """Load alias configuration from JSON file"""
        try:
            with open(self.alias_file, 'r', encoding='utf-8') as f:
                return json.load(f)
        except FileNotFoundError:
            sys.stderr.write(f"Warning: Alias file not found: {self.alias_file}\n")
            return self._get_fallback_aliases()
        except json.JSONDecodeError as e:
            sys.stderr.write(f"Error parsing alias file: {e}\n")
            return self._get_fallback_aliases()
    
    def _get_fallback_aliases(self) -> Dict:
        """Fallback aliases if file cannot be loaded"""
        return {
            "core_agents": {
                "gemini": {"canonical": "gemini", "variants": ["gemni"]},
                "vishwakarma": {"canonical": "vishwakarma", "variants": ["vish"]}
            },
            "cascade_agent": {
                "cascade": {"canonical": "cascade", "variants": ["castcade", "windsurf"]}
            },
            "role_agent_drift": {
                "rose": {"canonical": "rose", "variants": ["dialog"]},
                "thoth": {"canonical": "thoth", "variants": ["truth"]},
                "lilith": {"canonical": "lilith", "variants": ["audit"]},
                "ara": {"canonical": "ara", "variants": ["grok"]}
            }
        }
    
    def _build_reverse_map(self) -> Dict[str, str]:
        """Build reverse lookup map for fast variant resolution"""
        reverse_map = {}
        
        # Process all alias groups
        for group in self.aliases.values():
            if isinstance(group, dict):
                for canonical_name, config in group.items():
                    if isinstance(config, dict) and "variants" in config:
                        # Force canonical to lowercase within alias domain ONLY
                        # WARNING: This applies to agent identity domain, NOT PRD filenames
                        canonical_lower = canonical_name.lower()
                        
                        # Map canonical name to itself
                        if canonical_lower in reverse_map:
                            sys.stderr.write(f"WARNING: Duplicate canonical alias: {canonical_lower}\n")
                        reverse_map[canonical_lower] = canonical_lower
                        
                        # Map all variants to canonical
                        for variant in config["variants"]:
                            variant_lower = variant.lower()
                            if variant_lower in reverse_map:
                                sys.stderr.write(f"WARNING: Duplicate alias: {variant_lower} (maps to {reverse_map[variant_lower]} and {canonical_lower})\n")
                            reverse_map[variant_lower] = canonical_lower
        
        return reverse_map
    
    def resolve(self, name: str, debug: bool = False) -> str:
        """Resolve agent name variant to canonical name"""
        if not name:
            return name
        
        # Case-insensitive lookup
        lookup_name = name.lower()
        
        # Direct match only - NO PREFIX MATCHING for deterministic behavior
        if lookup_name in self.reverse_map:
            resolved = self.reverse_map[lookup_name]
            if debug and name.lower() != resolved:
                print(f"[ALIAS] {name} → {resolved}")
            return resolved
        
        # No match found - return original unchanged
        return name
    
    def get_variants(self, canonical_name: str) -> List[str]:
        """Get all variants for a canonical name"""
        canonical_lower = canonical_name.lower()
        variants = []
        
        for group in self.aliases.values():
            if isinstance(group, dict):
                for name, config in group.items():
                    if isinstance(config, dict) and name.lower() == canonical_lower:
                        variants = [canonical_name] + config.get("variants", [])
                        break
        
        return variants
    
    def list_all_mappings(self) -> Dict[str, List[str]]:
        """Get complete mapping of canonical names to their variants"""
        mappings = {}
        
        for group in self.aliases.values():
            if isinstance(group, dict):
                for canonical_name, config in group.items():
                    if isinstance(config, dict) and "variants" in config:
                        mappings[canonical_name] = [canonical_name] + config["variants"]
        
        return mappings
    
    def test_resolution(self, test_names: List[str]) -> Dict[str, str]:
        """Test name resolution for a list of names"""
        results = {}
        for name in test_names:
            resolved = self.resolve(name)
            results[name] = resolved
        return results


def main():
    """CLI interface for testing alias resolution"""
    import argparse
    
    parser = argparse.ArgumentParser(description="Resolve agent name aliases")
    parser.add_argument("name", nargs="?", help="Agent name to resolve")
    parser.add_argument("--list", action="store_true", help="List all mappings")
    parser.add_argument("--test", action="store_true", help="Run test suite")
    parser.add_argument("--variants", help="Show variants for canonical name")
    parser.add_argument("--debug", action="store_true", help="Show debug output for alias resolution")
    
    args = parser.parse_args()
    
    resolver = AgentAliasResolver()
    
    if args.list:
        print("All Agent Alias Mappings:")
        print("=" * 50)
        for canonical, variants in resolver.list_all_mappings().items():
            print(f"{canonical}: {', '.join(variants)}")
    
    elif args.variants:
        variants = resolver.get_variants(args.variants)
        if variants:
            print(f"Variants for '{args.variants}': {', '.join(variants)}")
        else:
            print(f"No variants found for '{args.variants}'")
    
    elif args.test:
        test_names = [
            "gemni", "gemini", "vish", "vishwakarma",
            "cascade", "castcade", "windsurf",
            "rose", "dialog", "thoth", "truth",
            "lilith", "audit", "ara", "grok",
            "unknown_agent"
        ]
        results = resolver.test_resolution(test_names)
        print("Alias Resolution Test Results:")
        print("=" * 50)
        for input_name, resolved in results.items():
            status = "✓" if input_name != resolved else " "
            print(f"{status} {input_name:15} → {resolved}")
    
    elif args.name:
        resolved = resolver.resolve(args.name, debug=args.debug)
        if args.name != resolved:
            print(f"{args.name} → {resolved}")
        else:
            print(f"{args.name} (no resolution needed)")
    
    else:
        parser.print_help()


if __name__ == "__main__":
    main()
