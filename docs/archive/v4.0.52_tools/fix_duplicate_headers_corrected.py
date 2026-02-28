#!/usr/bin/env python3
import os
import sys
import re
import tempfile
from datetime import datetime

# =========================
# FLARE DUPLICATE HEADER FIX - CORRECTED
# =========================

ROOT = os.getcwd()
TOOLS_DIR = "tools"
LOG = os.path.join(TOOLS_DIR, "duplicate_header_fix_corrected_log.txt")

UTC_DATE = datetime.utcnow().strftime("%Y%m%d")
UTC_DATETIME = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")

os.makedirs(TOOLS_DIR, exist_ok=True)

def log(msg):
    timestamp = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
    with open(LOG, "a", encoding="utf-8") as f:
        f.write(f"[{timestamp} UTC] {msg}\n")
    print(f"[{timestamp} UTC] {msg}")

log("Starting corrected FLARE duplicate header fix")

def read_file_safe(path):
    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            return f.read()
    except Exception as e:
        log(f"ERROR reading {path}: {e}")
        return None

def write_atomic(path, content):
    try:
        d = os.path.dirname(path) or "."
        fd, tmp = tempfile.mkstemp(prefix=".flare_fix_tmp_", dir=d)
        os.close(fd)
        with open(tmp, "w", encoding="utf-8", newline="\n") as f:
            f.write(content)
        os.replace(tmp, path)
        return True
    except Exception as e:
        log(f"ERROR writing {path}: {e}")
        return False

def fix_duplicate_headers(content, path):
    """Fix duplicate FLARE headers by keeping only the first complete one"""
    lines = content.splitlines()
    
    # Find all FLARE header blocks more carefully
    flare_blocks = []
    
    for i, line in enumerate(lines):
        if "FLARE Header" in line:
            # Look for the start of YAML block (should be next line or within few lines)
            yaml_start = -1
            yaml_end = -1
            
            for j in range(i, min(i + 10, len(lines))):
                if lines[j].strip() == "---":
                    if yaml_start == -1:
                        yaml_start = j
                    else:
                        yaml_end = j
                        break
            
            if yaml_start != -1 and yaml_end != -1:
                # This is a complete FLARE block
                flare_blocks.append((i, yaml_start, yaml_end))
    
    if len(flare_blocks) <= 1:
        return content, 0  # No duplicates to fix
    
    log(f"Found {len(flare_blocks)} FLARE blocks in {path}")
    
    # Keep the first complete FLARE block, remove others
    first_block = flare_blocks[0]
    keep_start = first_block[0]
    keep_end = first_block[2]
    
    # Build new content
    new_lines = []
    
    # Keep everything before the first FLARE header title
    new_lines.extend(lines[:keep_start])
    
    # Keep the first complete FLARE block (title + YAML)
    new_lines.extend(lines[keep_start:keep_end + 1])
    
    # Find the start of the body content (after all FLARE blocks)
    last_flare_end = flare_blocks[-1][2]
    body_start = last_flare_end + 1
    
    # Skip any additional FLARE headers and keep the body
    new_lines.extend(lines[body_start:])
    
    new_content = "\n".join(new_lines)
    
    # Ensure proper spacing
    if not new_content.endswith("\n"):
        new_content += "\n"
    
    return new_content, len(flare_blocks) - 1

# Process all markdown files
fixed_files = 0
total_duplicates = 0

for root, dirs, files in os.walk("."):
    if ".git" in dirs:
        dirs.remove(".git")
    
    for file in files:
        if file.endswith(".md"):
            path = os.path.join(root, file)
            rel_path = os.path.relpath(path, ".")
            
            content = read_file_safe(path)
            if content is None:
                continue
            
            # Count FLARE headers
            flare_count = content.count("FLARE Header")
            
            if flare_count > 1:
                fixed_content, duplicates_removed = fix_duplicate_headers(content, rel_path)
                
                if duplicates_removed > 0:
                    if write_atomic(path, fixed_content):
                        fixed_files += 1
                        total_duplicates += duplicates_removed
                        log(f"Fixed {rel_path}: removed {duplicates_removed} duplicate headers")
                    else:
                        log(f"ERROR fixing {rel_path}")

log(f"Duplicate header fix complete: {fixed_files} files fixed, {total_duplicates} duplicates removed")

# Commit the fixes
try:
    import subprocess
    subprocess.run(["git", "add", "-A"], check=True, capture_output=True, text=True)
    
    result = subprocess.run(["git", "diff", "--cached", "--quiet"], 
                          capture_output=True, text=True)
    
    if result.returncode == 0:
        log("No changes to commit")
    else:
        subprocess.run(["git", "commit", "-m", "FLARE: Fixed duplicate headers with corrected script"], 
                      check=True, capture_output=True, text=True)
        log("Committed duplicate header fixes")
        
except Exception as e:
    log(f"Git operation failed: {e}")

log("DONE.")
print("OK")
