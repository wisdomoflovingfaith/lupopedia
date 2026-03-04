#!/usr/bin/env python3
import os
import sys
import json
import yaml
import re
from datetime import datetime

def validate_flare_file(path, **kwargs):
    """Validate a single FLARE file with v4.0.56 high-fidelity rules"""
    errors = []
    warnings = []
    
    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            content = f.read()
        
        # Find FLARE block
        lines = content.splitlines()
        flare_start = -1
        flare_end = -1
        
        for i, line in enumerate(lines):
            if line.strip() == "---" and i < 120:
                for j in range(i+1, min(i+100, len(lines))):
                    if lines[j].strip() == "---":
                        block_content = "\n".join(lines[i:j+1])
                        if "flare.headers:" in block_content:
                            flare_start, flare_end = i, j
                            break
                if flare_start != -1:
                    break
        
        if flare_start == -1:
            errors.append("No FLARE header found")
            return errors, warnings
        
        header_lines = lines[flare_start:flare_end+1]
        raw_yaml = "\n".join(header_lines).strip("---").strip()
        
        try:
            data = yaml.safe_load(raw_yaml)
        except Exception as e:
            errors.append(f"YAML Syntax Error: {e}")
            return errors, warnings

        # 1. ENFORCE CANONICAL ORDER
        keys = list(data.keys())
        expected_order = ["flame.init", "flare.conditional", "flare.headers", "flare.edges", "flare.footer", "flame.see", "flame.close"]
        
        # We only check order for keys that ARE present
        present_expected = [k for k in expected_order if k in keys]
        actual_order = [k for k in keys if k in expected_order]
        
        if present_expected != actual_order:
            errors.append(f"Header order mismatch. Expected: {', '.join(present_expected)}")

        # 1.1 FLAME.SEE VALIDATION (Basic)
        if "flame.see" in data:
            see = data["flame.see"]
            if not isinstance(see, dict) or "mappings" not in see:
                errors.append("flame.see must be an object with 'mappings' list")
            else:
                for mapping in see.get("mappings", []):
                    if not isinstance(mapping, list) or len(mapping) < 2:
                        errors.append(f"Invalid mapping in flame.see: {mapping}")
                    else:
                        path_entry, url_entry = mapping[0], mapping[1]
                        # Check Path match
                        if path_entry != actual_path:
                            warnings.append(f"flame.see mapping path '{path_entry}' does not match file_path_from_root '{actual_path}'")
                        
                        # Add to global URL tracker (passed in via extra arg or handled in main)
                        if "url_tracker" in kwargs:
                            from urllib.parse import urlparse
                            norm_url = url_entry.lower().rstrip("/")
                            if norm_url in kwargs["url_tracker"]:
                                kwargs["url_tracker"][norm_url].append(path)
                            else:
                                kwargs["url_tracker"][norm_url] = [path]

        # 2. TARGETED MANDATORY RULES (Safety Rule)
        headers = data.get("flare.headers", {})
        artifact_kind = headers.get("artifact_kind", "unknown")
        sys_version = str(headers.get("system_version", "0.0.0"))
        
        is_active_artifact = artifact_kind in ["prompt", "documentation_task", "agent_instruction", "artifact", "thread"]
        
        try:
            v_parts = [int(x) for x in re.findall(r'\d+', sys_version)]
            version_num = v_parts[0] * 10000 + v_parts[1] * 100 + (v_parts[2] if len(v_parts) > 2 else 0)
            
            if version_num >= 40055 and is_active_artifact:
                if "flame.init" not in data:
                    errors.append(f"Missing mandatory flame.init for active artifact_kind '{artifact_kind}' (v{sys_version})")
                if "flare.conditional" not in data:
                    # Optional for now to avoid breaking existing development artifacts immediately, 
                    # but highly recommended.
                    warnings.append(f"Active artifact_kind '{artifact_kind}' (v{sys_version}) lacks flare.conditional block.")
                if "flame.close" not in data:
                    errors.append(f"Missing mandatory flame.close for active artifact_kind '{artifact_kind}' (v{sys_version})")
        except:
            pass

        # 2.1 FLARE.CONDITIONAL VALIDATION
        if "flare.conditional" in data:
            cond = data["flare.conditional"]
            if not isinstance(cond, dict):
                errors.append("flare.conditional must be an object")
            else:
                if "guards" not in cond:
                    errors.append("Missing 'guards' in flare.conditional")
                if "brief" not in cond:
                    errors.append("Missing 'brief' in flare.conditional")

        # 3. TYPED ACTIONS VALIDATION
        if "flame.init" in data:
            init = data["flame.init"]
            if not isinstance(init, dict):
                errors.append("flame.init must be an object")
            else:
                pre_actions = init.get("pre_actions", [])
                if not isinstance(pre_actions, list):
                    errors.append("flame.init.pre_actions must be a list")
                else:
                    for action in pre_actions:
                        if not isinstance(action, dict):
                            errors.append(f"Untyped action in flame.init: {action}. Actions must be objects.")

        if "flame.close" in data:
            close = data["flame.close"]
            if not isinstance(close, dict):
                errors.append("flame.close must be an object")
            else:
                post_actions = close.get("post_actions", [])
                if not isinstance(post_actions, list):
                    errors.append("flame.close.post_actions must be a list")
                else:
                    for action in post_actions:
                        if not isinstance(action, dict):
                            errors.append(f"Untyped action in flame.close: {action}. Actions must be objects.")

        # 4. PATH VALIDATION
        expected_path = os.path.relpath(path, ".").replace("\\", "/")
        actual_path = str(headers.get("file_path_from_root", "")).replace("\\", "/")
        if actual_path and actual_path != expected_path:
            errors.append(f"Path mismatch: {actual_path} != {expected_path}")

    except Exception as e:
        errors.append(f"Unexpected validation error: {e}")
    
    return errors, warnings

def main():
    ci_mode = "--ci" in sys.argv
    
    # Use flare_md_index.txt generated by flare_apply.py
    index_file = "tools/flare_md_index.txt"
    if not os.path.exists(index_file):
        # Fallback to manual scan if index doesn't exist
        paths = []
        for root, dirs, files in os.walk("."):
            if ".git" in dirs: dirs.remove(".git")
            for file in files:
                if file.endswith(".md"):
                    paths.append(os.path.join(root, file))
    else:
        with open(index_file, "r", encoding="utf-8") as f:
            paths = [line.strip() for line in f if line.strip()]
    
    total_errors = 0
    total_warnings = 0
    url_tracker = {}
    
    for path in paths:
        if not os.path.exists(path): continue
        
        errors, warnings = validate_flare_file(path, url_tracker=url_tracker)
        
        if errors:
            print(f"ERRORS in {path}:")
            for error in errors:
                print(f"  - {error}")
            total_errors += len(errors)
        
        if warnings:
            print(f"WARNINGS in {path}:")
            for warning in warnings:
                print(f"  - {warning}")
            total_warnings += len(warnings)
    
    # Report URL collisions
    for url, owners in url_tracker.items():
        if len(owners) > 1:
            print(f"COLLISION: URL '{url}' claimed by multiple files:")
            for owner in owners:
                print(f"  - {owner}")
            total_errors += 1 # Collision is an error
    
    print(f"\nValidation complete: {total_errors} errors, {total_warnings} warnings")
    
    if ci_mode and total_errors > 0:
        sys.exit(1)

if __name__ == "__main__":
    main()
