#!/usr/bin/env python3
import os
import sys
import re
from datetime import datetime

# =========================
# PHASE-4 ISSUES LEDGER GENERATOR
# =========================

ROOT = os.getcwd()
TOOLS_DIR = "tools"
LOG = os.path.join(TOOLS_DIR, "issues_ledger_log.txt")
INDEX_FILE = os.path.join(TOOLS_DIR, "issues_phase4_index.txt")
LEDGER_FILE = os.path.join("docs", "status", "ISSUES_LEDGER_PHASE4.md")

UTC_DATE = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
UTC_SHORT = datetime.utcnow().strftime("%Y%m%d")

os.makedirs(os.path.dirname(LEDGER_FILE), exist_ok=True)
os.makedirs(TOOLS_DIR, exist_ok=True)

def log(msg):
    timestamp = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
    with open(LOG, "a", encoding="utf-8") as f:
        f.write(f"[{timestamp} UTC] {msg}\n")
    print(f"[{timestamp} UTC] {msg}")

log("Starting PHASE-4 Issues Ledger generation")

# =========================
# STEP 1: Extract PHASE-4 file list
# =========================
log("STEP 1: Extracting PHASE-4 file list...")

def extract_phase4_files():
    """Extract unique file paths from PHASE-4 log entries"""
    update_log = os.path.join(TOOLS_DIR, "flare_update_log.txt")
    files = set()
    
    if not os.path.exists(update_log):
        log("ERROR: flare_update_log.txt not found")
        return []
    
    with open(update_log, "r", encoding="utf-8") as f:
        for line in f:
            if "PHASE 4: Fixed issues:" in line:
                # Extract file path after "PHASE 4: Fixed issues: "
                match = re.search(r'PHASE 4: Fixed issues: (.+)$', line.strip())
                if match:
                    file_path = match.group(1).strip()
                    if file_path and file_path.endswith(".md"):
                        files.add(file_path)
    
    return sorted(files)

phase4_files = extract_phase4_files()

# Write index file
with open(INDEX_FILE, "w", encoding="utf-8") as f:
    for file_path in phase4_files:
        f.write(file_path + "\n")

log(f"STEP 1: Created index with {len(phase4_files)} files")

# =========================
# STEP 2: Scan each file for issues
# =========================
log("STEP 2: Scanning files for issues...")

def read_file_safe(path):
    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            return f.read()
    except Exception as e:
        log(f"ERROR reading {path}: {e}")
        return None

def detect_flip_issues(content, path):
    """Detect FLIP header issues"""
    issues = []
    lines = content.splitlines()
    
    # Look for FLIP header
    flip_start = -1
    for i, line in enumerate(lines[:50]):  # Check first 50 lines
        if "FLIP Header" in line or "Wolfie Header" in line:
            flip_start = i
            break
    
    if flip_start == -1:
        issues.append({
            "type": "FLIP Header",
            "description": "Missing FLIP header",
            "severity": "high"
        })
        return issues
    
    # Check for YAML block
    yaml_start = -1
    yaml_end = -1
    for i in range(flip_start, min(flip_start + 60, len(lines))):
        if lines[i].strip() == "---" and yaml_start == -1:
            yaml_start = i
        elif lines[i].strip() == "---" and yaml_start != -1:
            yaml_end = i
            break
    
    if yaml_start == -1 or yaml_end == -1:
        issues.append({
            "type": "FLIP Header",
            "description": "Malformed YAML block - missing delimiters",
            "severity": "medium"
        })
        return issues
    
    yaml_content = "\n".join(lines[yaml_start:yaml_end+1])
    
    # Check for required fields
    if "system_version:" not in yaml_content:
        issues.append({
            "type": "FLIP Header",
            "description": "Missing system_version",
            "severity": "medium"
        })
    
    if "last_verified_utc:" not in yaml_content:
        issues.append({
            "type": "FLIP Header", 
            "description": "Missing last_verified_utc",
            "severity": "medium"
        })
    
    # Check version format
    version_match = re.search(r'system_version:\s*"([^"]+)"', yaml_content)
    if version_match:
        version = version_match.group(1)
        if not re.match(r'^\d+\.\d+\.\d+$', version):
            issues.append({
                "type": "FLIP Header",
                "description": f"Invalid system_version format: {version}",
                "severity": "low"
            })
    
    # Check timestamp format
    timestamp_match = re.search(r'last_verified_utc:\s*"([^"]+)"', yaml_content)
    if timestamp_match:
        timestamp = timestamp_match.group(1)
        if not re.match(r'^\d{8}$', timestamp):
            issues.append({
                "type": "FLIP Header",
                "description": f"Invalid timestamp format: {timestamp}",
                "severity": "low"
            })
    
    return issues

def detect_flare_issues(content, path):
    """Detect FLARE header issues"""
    issues = []
    lines = content.splitlines()
    
    # Look for FLARE header
    flare_start = -1
    for i, line in enumerate(lines[:120]):  # Check first 120 lines
        if "FLARE Header" in line:
            flare_start = i
            break
    
    if flare_start == -1:
        issues.append({
            "type": "FLARE Header",
            "description": "Missing FLARE header",
            "severity": "high"
        })
        return issues
    
    # Find YAML block
    yaml_start = -1
    yaml_end = -1
    for i in range(flare_start, min(flare_start + 80, len(lines))):
        if lines[i].strip() == "---" and yaml_start == -1:
            yaml_start = i
        elif lines[i].strip() == "---" and yaml_start != -1:
            yaml_end = i
            break
    
    if yaml_start == -1 or yaml_end == -1:
        issues.append({
            "type": "FLARE Header",
            "description": "Malformed YAML block - missing delimiters",
            "severity": "medium"
        })
        return issues
    
    yaml_content = "\n".join(lines[yaml_start:yaml_end+1])
    
    # Check for required FLARE fields
    required_fields = [
        "flare.headers:",
        "flare.schema:",
        "file_path_from_root:",
        "file_hash:",
        "last_updated_utc:",
        "system_version:"
    ]
    
    for field in required_fields:
        if field not in yaml_content:
            severity = "high" if field in ["flare.headers:", "file_path_from_root:"] else "medium"
            issues.append({
                "type": "FLARE Header",
                "description": f"Missing required field: {field}",
                "severity": severity
            })
    
    # Check edges
    if "flare.edges:" not in yaml_content:
        issues.append({
            "type": "FLARE Header",
            "description": "Missing flare.edges",
            "severity": "low"
        })
    
    return issues

def detect_version_drift(content, path):
    """Detect version drift issues"""
    issues = []
    
    # Extract declared version
    version_match = re.search(r'system_version:\s*"([^"]+)"', content)
    declared_version = version_match.group(1) if version_match else None
    
    if not declared_version:
        return issues
    
    # Look for version references in content
    version_refs = re.findall(r'(?:version|v)\s*(\d+\.\d+\.\d+)', content, re.IGNORECASE)
    
    for ref in version_refs:
        if ref != declared_version:
            issues.append({
                "type": "Version Drift",
                "description": f"Reference to version {ref} differs from declared {declared_version}",
                "severity": "low"
            })
    
    return issues

def detect_legacy_contamination(content, path):
    """Detect legacy contamination"""
    issues = []
    
    # Check for Crafty Syntax leftovers
    if re.search(r'crafty\s*syntax|live\s*help|chat\s*system', content, re.IGNORECASE):
        issues.append({
            "type": "Legacy Contamination",
            "description": "Crafty Syntax remnants detected",
            "severity": "medium"
        })
    
    # Check for WordPress artifacts
    if re.search(r'wp-content|wp-includes|wordpress|wp-config', content, re.IGNORECASE):
        issues.append({
            "type": "Legacy Contamination",
            "description": "WordPress artifacts detected",
            "severity": "medium"
        })
    
    # Check for deprecated READMEs
    if "README" in path.upper() and "legacy" in content.lower():
        issues.append({
            "type": "Legacy Contamination",
            "description": "Deprecated README content",
            "severity": "low"
        })
    
    return issues

def detect_formatting_issues(content, path):
    """Detect formatting issues"""
    issues = []
    
    # Check for broken markdown
    if content.count("```") % 2 != 0:
        issues.append({
            "type": "Formatting",
            "description": "Unclosed code block",
            "severity": "medium"
        })
    
    # Check for malformed metadata
    if re.search(r'^\s*\w+\s*:\s*[^"\'\s].*$', content, re.MULTILINE):
        issues.append({
            "type": "Formatting",
            "description": "Unquoted metadata values",
            "severity": "low"
        })
    
    # Check for invalid timestamps
    invalid_timestamps = re.findall(r'\b\d{4}-\d{2}-\d{2}\b', content)
    if invalid_timestamps:
        issues.append({
            "type": "Formatting",
            "description": f"Invalid timestamp format found: {invalid_timestamps[0]}",
            "severity": "low"
        })
    
    return issues

def detect_semantic_gaps(content, path):
    """Detect semantic OS metadata gaps"""
    issues = []
    
    # Check for missing graph edges
    if "flare.edges:" in content and "[]" in content:
        issues.append({
            "type": "Semantic Gap",
            "description": "Empty flare.edges array",
            "severity": "low"
        })
    
    # Check for missing schema references
    if "flare.schema:" not in content:
        issues.append({
            "type": "Semantic Gap",
            "description": "Missing schema definition",
            "severity": "medium"
        })
    
    return issues

# Scan all files
all_issues = {}

for file_path in phase4_files:
    if not os.path.exists(file_path):
        log(f"WARNING: File not found: {file_path}")
        continue
    
    content = read_file_safe(file_path)
    if content is None:
        continue
    
    file_issues = []
    
    # Run all detection functions
    file_issues.extend(detect_flip_issues(content, file_path))
    file_issues.extend(detect_flare_issues(content, file_path))
    file_issues.extend(detect_version_drift(content, file_path))
    file_issues.extend(detect_legacy_contamination(content, file_path))
    file_issues.extend(detect_formatting_issues(content, file_path))
    file_issues.extend(detect_semantic_gaps(content, file_path))
    
    if file_issues:
        all_issues[file_path] = file_issues
        log(f"Found {len(file_issues)} issues in {file_path}")

log(f"STEP 2: Scanned {len(phase4_files)} files, found issues in {len(all_issues)} files")

# =========================
# STEP 3: Generate Issues Ledger
# =========================
log("STEP 3: Generating Issues Ledger...")

with open(LEDGER_FILE, "w", encoding="utf-8") as f:
    f.write("# PHASE-4 Issues Ledger\n\n")
    f.write(f"**Generated**: {UTC_DATE}\n")
    f.write(f"**Total Files Analyzed**: {len(phase4_files)}\n")
    f.write(f"**Files with Issues**: {len(all_issues)}\n")
    f.write(f"**Total Issues**: {sum(len(issues) for issues in all_issues.values())}\n\n")
    f.write("---\n\n")
    
    for file_path in sorted(all_issues.keys()):
        f.write(f"### {file_path}\n\n")
        
        for issue in all_issues[file_path]:
            f.write(f"- **Issue Type**: {issue['type']}\n")
            f.write(f"- **Description**: {issue['description']}\n")
            f.write(f"- **Severity**: {issue['severity']}\n")
            f.write(f"- **Version Affected**: 4.0.50\n")
            f.write(f"- **Detected By**: Windsurf (1002)\n")
            f.write(f"- **Timestamp**: {UTC_DATE}\n")
            f.write(f"- **Notes**: Auto-detected during PHASE-4 analysis\n\n")
        
        f.write("---\n\n")

log(f"STEP 3: Generated Issues Ledger at {LEDGER_FILE}")

# =========================
# STEP 4: Commit analysis artifacts
# =========================
log("STEP 4: Committing analysis artifacts...")

import subprocess

try:
    # Stage files
    subprocess.run(["git", "add", INDEX_FILE, LEDGER_FILE], check=True, capture_output=True, text=True)
    
    # Commit
    subprocess.run(["git", "commit", "-m", "PHASE-4 Issues Ledger: Indexed and analyzed all flagged markdown files"], 
                  check=True, capture_output=True, text=True)
    
    log("STEP 4: Committed analysis artifacts")
    
except subprocess.CalledProcessError as e:
    log(f"STEP 4: Git operation failed: {e}")
except FileNotFoundError:
    log("STEP 4: Git not available, skipping commit")

# =========================
# SUMMARY
# =========================
total_issues = sum(len(issues) for issues in all_issues.values())
severity_counts = {}
for issues in all_issues.values():
    for issue in issues:
        severity = issue['severity']
        severity_counts[severity] = severity_counts.get(severity, 0) + 1

log("PHASE-4 Issues Ledger generation complete")
log(f"Summary: {len(phase4_files)} files scanned, {len(all_issues)} files with issues, {total_issues} total issues")
log(f"Severity breakdown: {severity_counts}")
log("DONE.")
print("OK")
