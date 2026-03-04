#!/usr/bin/env python3
import os
import sys
import json
import yaml
import re
import hashlib
import subprocess
from datetime import datetime

# Configuration
INDEX_PATH = "artifacts/index/flame_see_index.json"
REPO_ROOT = os.getcwd()

def normalize_url(url):
    """Normalize URL: lowercase host, strip trailing slash, http/https equivalence."""
    url = url.strip()
    # Replace https with http for matching consistency (canonicalizing internally to http as per directive example)
    url = url.replace("https://", "http://")
    url = url.lower()
    if url.endswith("/"):
        url = url[:-1]
    return url

def get_file_hash(path):
    """Get SHA1 hash of file content."""
    try:
        with open(path, "rb") as f:
            return hashlib.sha1(f.read()).hexdigest()
    except:
        return None

def build_index():
    """Scan all .md files and build the flame.see index."""
    print("Building flame.see index...")
    index = {}
    md_files = []
    
    # Use flare_md_index.txt if it exists for speed, otherwise scan
    md_index_file = "lupo-tools/flare_md_index.txt"
    if os.path.exists(md_index_file):
        with open(md_index_file, "r", encoding="utf-8") as f:
            md_files = [line.strip() for line in f if line.strip()]
    else:
        for root, dirs, files in os.walk("."):
            if any(x in root for x in [".git", "vendor", "node_modules", "build"]):
                continue
            for file in files:
                if file.endswith(".md"):
                    md_files.append(os.path.relpath(os.path.join(root, file), "."))

    for path in md_files:
        try:
            with open(path, "r", encoding="utf-8", errors="replace") as f:
                content = f.read()
            
            # Extract YAML frontmatter
            if content.startswith("---"):
                parts = content.split("---", 2)
                if len(parts) >= 3:
                    data = yaml.safe_load(parts[1])
                    if data and "flame.see" in data:
                        see_block = data["flame.see"]
                        if see_block and "mappings" in see_block:
                            for mapping in see_block["mappings"]:
                                if isinstance(mapping, list) and len(mapping) >= 2:
                                    md_path, url = mapping
                                    norm_url = normalize_url(url)
                                    if norm_url not in index:
                                        index[norm_url] = []
                                    index[norm_url].append({
                                        "path": md_path,
                                        "declared_url": url,
                                        "file_hash": get_file_hash(path),
                                        "mtime": int(os.path.getmtime(path))
                                    })
        except Exception as e:
            print(f"Error parsing {path}: {e}")

    # Ensure directory exists
    os.makedirs(os.path.dirname(INDEX_PATH), exist_ok=True)
    with open(INDEX_PATH, "w", encoding="utf-8") as f:
        json.dump(index, f, indent=2)
    print(f"Index built with {len(index)} unique URLs.")
    return index

def load_index(reindex=False):
    if reindex or not os.path.exists(INDEX_PATH):
        return build_index()
    try:
        with open(INDEX_PATH, "r", encoding="utf-8") as f:
            return json.load(f)
    except:
        return build_index()

def resolve_url(url, index):
    norm_url = normalize_url(url)
    matches = index.get(norm_url, [])
    
    if matches:
        return matches, 1.0
    
    # Try fuzzy matches (case insensitive path portion, trailing slash variations etc already handled by normalize)
    # But if normalize didn't find it, we can try matching just the path portion
    best_matches = []
    max_score = 0.0
    
    for idx_url, meta in index.items():
        score = 0.0
        if norm_url in idx_url or idx_url in norm_url:
            score = 0.5
        
        if score > 0:
            if score > max_score:
                max_score = score
                best_matches = meta
            elif score == max_score:
                best_matches.extend(meta)
                
    return best_matches, max_score

def main():
    import argparse
    parser = argparse.ArgumentParser(description="Lupopedia flame.see URL resolver")
    parser.add_argument("input", nargs="?", help="URL or path to resolve")
    parser.add_argument("--json", action="store_true", help="Output in JSON format")
    parser.add_argument("--cat", action="store_true", help="Print file content")
    parser.add_argument("--open", action="store_true", help="Open in default editor")
    parser.add_argument("--reindex", action="store_true", help="Rebuild index")
    parser.add_argument("--first", action="store_true", help="Pick first match if multiple")
    
    args = parser.parse_args()

    index = load_index(args.reindex)
    
    if not args.input:
        if args.reindex:
            sys.exit(0)
        parser.print_help()
        sys.exit(1)

    # 1. Check if it's a direct path
    if os.path.exists(args.input) and args.input.endswith(".md"):
        result_path = args.input
        matches = [{"path": result_path, "score": 1.0}]
    else:
        # 2. Resolve as URL
        matches, score = resolve_url(args.input, index)
        if not matches:
            if args.json:
                print(json.dumps({"input": args.input, "matches": []}))
            else:
                print(f"Error: Could not resolve URL '{args.input}'")
            sys.exit(1)

    if args.json:
        print(json.dumps({"input": args.input, "matches": matches}))
        sys.exit(0)

    if len(matches) > 1 and not args.first:
        print(f"Multiple matches found for '{args.input}':")
        for i, m in enumerate(matches):
            print(f"  [{i}] {m['path']} (URL: {m.get('declared_url')})")
        print("\nUse --first to pick the most relevant or specify a more exact URL.")
        sys.exit(2)

    target = matches[0]["path"]
    
    if args.cat:
        with open(target, "r", encoding="utf-8", errors="replace") as f:
            print(f.read())
    elif args.open:
        # Try to use environment's editor or default
        editor = os.environ.get("EDITOR", "less")
        subprocess.run([editor, target])
    else:
        print(target)

if __name__ == "__main__":
    main()
