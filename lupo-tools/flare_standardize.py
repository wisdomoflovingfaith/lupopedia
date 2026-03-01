#!/usr/bin/env python3
import os
import sys
import subprocess
import json
import hashlib
import tempfile
import re
import yaml
from datetime import datetime

# =========================
# FLARE STANDARDIZATION UPDATE
# =========================

ROOT = os.getcwd()
TOOLS_DIR = "tools"
LOG = os.path.join(TOOLS_DIR, "flare_update_log.txt")
INDEX = os.path.join(TOOLS_DIR, "flare_file_index.txt")
ISSUES_TXT = os.path.join(TOOLS_DIR, "flare_header_issues.txt")
ISSUES_JSON = os.path.join(TOOLS_DIR, "flare_header_issues.json")
VALIDATE_SCRIPT = os.path.join(TOOLS_DIR, "flare_validate.py")

SYSTEM_VERSION = "4.0.50"
UTC_DATE = datetime.utcnow().strftime("%Y%m%d")
VERIFIED_BY = "windsurf"
MOOD_RGB_DEFAULT = "4169E1"
Lupo_AGENT = "windsurf"
ACTOR_ID_DEFAULT = "1002"

os.makedirs(TOOLS_DIR, exist_ok=True)

def log(msg):
    timestamp = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
    with open(LOG, "a", encoding="utf-8") as f:
        f.write(f"[{timestamp} UTC] {msg}\n")
    print(f"[{timestamp} UTC] {msg}")

log(f"Starting FLARE standardization update in repo root: {ROOT}")
log(f"system_version={SYSTEM_VERSION} utc_date={UTC_DATE}")

# =========================
# PREPARATION: Update Index
# =========================
log("PREPARATION: Updating file index...")

def build_file_index():
    """Build comprehensive index of .md, .json, .csv files"""
    paths = []
    for root, dirs, files in os.walk("."):
        if ".git" in dirs:
            dirs.remove(".git")
        for file in files:
            if file.endswith((".md", ".json", ".csv")):
                rel_path = os.path.relpath(os.path.join(root, file), ".")
                if rel_path not in paths:
                    paths.append(rel_path)
    return sorted(paths, key=lambda x: x.lower())

paths = build_file_index()
with open(INDEX, "w", encoding="utf-8", newline="\n") as f:
    for path in paths:
        f.write(path + "\n")

# Count by type
md_count = sum(1 for p in paths if p.endswith(".md"))
json_count = sum(1 for p in paths if p.endswith(".json"))
csv_count = sum(1 for p in paths if p.endswith(".csv"))

log(f"Index updated: {len(paths)} total files (MD: {md_count}, JSON: {json_count}, CSV: {csv_count})")

# =========================
# PHASE 1: Standardize on FLARE
# =========================
log("PHASE 1: Standardizing on FLARE as canonical...")

def read_text(path):
    with open(path, "r", encoding="utf-8", errors="replace") as f:
        return f.read()

def write_atomic(path, content):
    d = os.path.dirname(path) or "."
    fd, tmp = tempfile.mkstemp(prefix=".flare_update_tmp_", dir=d)
    os.close(fd)
    with open(tmp, "w", encoding="utf-8", newline="\n") as f:
        f.write(content)
    os.replace(tmp, path)

def detect_flare_block(text):
    """Detect existing FLARE/Wolfie/FLIP blocks"""
    lines = text.splitlines()
    flare_start = -1
    flare_end = -1
    legacy_blocks = []
    
    for i, line in enumerate(lines):
        if line.strip() == "---" and i < 120:
            # Check if this is a FLARE block
            for j in range(i+1, min(i+50, len(lines))):
                if lines[j].strip() == "---":
                    block_content = "\n".join(lines[i:j+1])
                    if any(key in block_content for key in ["flare.headers:", "wolfie.headers:", "flip.headers:", "flp.headers:", "flph.headers:", "crop.headers:"]):
                        if "flare.headers:" in block_content:
                            flare_start, flare_end = i, j
                        else:
                            legacy_blocks.append((i, j, block_content))
                    break
    return flare_start, flare_end, legacy_blocks

def infer_channel_actor(path):
    m = re.search(r"(?:^|/)channels/(\d+)/(?:actors)/(\d+)(?:/|$)", path)
    if m:
        return int(m.group(1)), int(m.group(2))
    m2 = re.search(r"(?:^|/)channels/(\d+)(?:/|$)", path)
    if m2:
        return int(m2.group(1)), None
    return None, None

def compute_hash(content):
    """Compute SHA-256 hash of content"""
    return hashlib.sha256(content.encode("utf-8", "replace")).hexdigest()

phase1_updated = 0
phase1_legacy_detected = 0

for rel in paths:
    if not rel.endswith(".md"):
        continue  # Focus on MD files for header standardization
    
    if not os.path.exists(rel):
        log(f"WARNING: Missing file {rel}")
        continue
    
    try:
        original = read_text(rel)
        flare_start, flare_end, legacy_blocks = detect_flare_block(original)
        
        if flare_start == -1 and not legacy_blocks:
            continue  # No FLARE blocks to standardize
        
        modified = False
        new_content = original
        
        # Standardize main block to FLARE
        if flare_start != -1:
            lines = original.splitlines()
            # Update title line to include all aliases
            title_line_idx = flare_start - 1
            if title_line_idx >= 0 and "FLARE" in lines[title_line_idx]:
                lines[title_line_idx] = "# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
                modified = True
            
            # Update YAML key to flare.headers
            for i in range(flare_start, flare_end + 1):
                if re.match(r'^\s*(wolfie|flip|flp|flph|crop)\.headers:', lines[i]):
                    lines[i] = re.sub(r'^\s*(wolfie|flip|flp|flph|crop)\.headers:', '  flare.headers:', lines[i])
                    modified = True
            
            if modified:
                new_content = "\n".join(lines)
        
        # Handle legacy blocks
        if legacy_blocks:
            phase1_legacy_detected += 1
            # Add deprecation note to footer
            if "deprecation_notes:" not in new_content:
                # Find or create footer
                footer_match = re.search(r'flare\.footer:.*?---', new_content, re.DOTALL)
                if footer_match:
                    footer_section = footer_match.group(0)
                    if "deprecation_notes:" in footer_section:
                        new_footer = footer_section
                    else:
                        # Add deprecation note before closing ---
                        new_footer = footer_section.replace("---", "    deprecation_notes: [\"Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers\"]\n---")
                    new_content = new_content.replace(footer_section, new_footer)
                    modified = True
        
        if modified:
            write_atomic(rel, new_content)
            phase1_updated += 1
            log(f"PHASE 1: Standardized FLARE header: {rel}")
    
    except Exception as e:
        log(f"PHASE 1: ERROR processing {rel}: {e}")

log(f"PHASE 1: Complete - Updated {phase1_updated} files, detected {phase1_legacy_detected} with legacy blocks")

# =========================
# PHASE 2: Handle delegation_chain
# =========================
log("PHASE 2: Fixing delegation_chain...")

phase2_updated = 0
phase2_needs_review = 0

for rel in paths:
    if not rel.endswith(".md"):
        continue
    
    if not os.path.exists(rel):
        continue
    
    try:
        content = read_text(rel)
        flare_start, flare_end, _ = detect_flare_block(content)
        
        if flare_start == -1:
            continue
        
        lines = content.splitlines()
        modified = False
        delegation_chain_line = -1
        needs_review_added = False
        
        # Find delegation_chain line
        for i in range(flare_start, flare_end + 1):
            if re.match(r'^\s*delegation_chain:', lines[i]):
                delegation_chain_line = i
                break
        
        # Infer delegation_chain from path
        channel_id, actor_id = infer_channel_actor(rel)
        inferred_chain = None
        
        if channel_id is not None and actor_id is not None:
            inferred_chain = f"{channel_id}:{actor_id}"
        elif channel_id is not None:
            inferred_chain = f"{channel_id}:10000"  # Default to captain
        else:
            inferred_chain = None
        
        # Update delegation_chain
        if delegation_chain_line != -1:
            current_chain = lines[delegation_chain_line].split(":", 1)[1].strip().strip('"')
            if current_chain in ["10000:1007", "1002:10000"] or not current_chain:
                # Silently defaulted - set to null and add needs_review
                lines[delegation_chain_line] = f"  delegation_chain: null"
                modified = True
                needs_review_added = True
            elif inferred_chain and current_chain != inferred_chain:
                # Update to inferred value
                lines[delegation_chain_line] = f"  delegation_chain: \"{inferred_chain}\""
                modified = True
        else:
            # Missing delegation_chain - add it
            if inferred_chain:
                # Insert before flare.footer
                for i in range(flare_start, flare_end + 1):
                    if "flare.footer:" in lines[i]:
                        lines.insert(i, f"  delegation_chain: \"{inferred_chain}\"")
                        break
                modified = True
            else:
                # Add null + needs_review
                for i in range(flare_start, flare_end + 1):
                    if "flare.footer:" in lines[i]:
                        lines.insert(i, f"  delegation_chain: null")
                        lines.insert(i+1, f"  needs_review: [\"delegation_chain\"]")
                        needs_review_added = True
                        break
                modified = True
        
        # Add needs_review if delegation_chain is null
        if needs_review_added and not any("needs_review:" in line for line in lines[flare_start:flare_end+1]):
            for i in range(flare_start, flare_end + 1):
                if "flare.footer:" in lines[i]:
                    lines.insert(i, f"  needs_review: [\"delegation_chain\"]")
                    break
        
        if modified:
            write_atomic(rel, "\n".join(lines))
            phase2_updated += 1
            if needs_review_added:
                phase2_needs_review += 1
            log(f"PHASE 2: Fixed delegation_chain: {rel}")
    
    except Exception as e:
        log(f"PHASE 2: ERROR processing {rel}: {e}")

log(f"PHASE 2: Complete - Updated {phase2_updated} files, {phase2_needs_review} need review")

# =========================
# PHASE 3: Confirm system_version
# =========================
log("PHASE 3: Updating system_version...")

phase3_updated = 0

for rel in paths:
    if not rel.endswith(".md"):
        continue
    
    if not os.path.exists(rel):
        continue
    
    try:
        content = read_text(rel)
        flare_start, flare_end, _ = detect_flare_block(content)
        
        if flare_start == -1:
            continue
        
        lines = content.splitlines()
        modified = False
        
        # Update system_version
        for i in range(flare_start, flare_end + 1):
            if re.match(r'^\s*system_version:', lines[i]):
                current_version = lines[i].split(":", 1)[1].strip().strip('"')
                if current_version != SYSTEM_VERSION:
                    lines[i] = f"  system_version: \"{SYSTEM_VERSION}\""
                    modified = True
                break
        else:
            # Missing system_version - add it
            for i in range(flare_start, flare_end + 1):
                if "flare.footer:" in lines[i]:
                    lines.insert(i, f"  system_version: \"{SYSTEM_VERSION}\"")
                    modified = True
                    break
        
        # Update last_updated_utc if modified
        if modified:
            for i in range(flare_start, flare_end + 1):
                if re.match(r'^\s*last_updated_utc:', lines[i]):
                    lines[i] = f"  last_updated_utc: \"{UTC_DATE}\""
                    break
            else:
                # Add missing last_updated_utc
                for i in range(flare_start, flare_end + 1):
                    if "flare.footer:" in lines[i]:
                        lines.insert(i, f"  last_updated_utc: \"{UTC_DATE}\"")
                        break
            
            write_atomic(rel, "\n".join(lines))
            phase3_updated += 1
            log(f"PHASE 3: Updated system_version: {rel}")
    
    except Exception as e:
        log(f"PHASE 3: ERROR processing {rel}: {e}")

log(f"PHASE 3: Complete - Updated {phase3_updated} files")

# =========================
# PHASE 4: Issues Report and Fixes
# =========================
log("PHASE 4: Generating issues report and fixing...")

issues_report = []
issue_counts = {}

def add_issue(path, issue_type):
    issues_report.append({"path": path, "issues": [issue_type]})
    issue_counts[issue_type] = issue_counts.get(issue_type, 0) + 1

# Scan for issues
for rel in paths:
    if not rel.endswith(".md"):
        continue
    
    if not os.path.exists(rel):
        continue
    
    try:
        content = read_text(rel)
        flare_start, flare_end, _ = detect_flare_block(content)
        
        if flare_start == -1:
            continue
        
        lines = content[flare_start:flare_end+1]
        content_str = "\n".join(lines)
        
        # Check for missing required fields
        if "file_path_from_root:" not in content_str:
            add_issue(rel, "missing_file_path")
        
        if "file_hash:" not in content_str:
            add_issue(rel, "missing_hash")
        
        if "system_version:" not in content_str:
            add_issue(rel, "missing_system_version")
        
        if "last_updated_utc:" not in content_str:
            add_issue(rel, "missing_updated")
        
        # Check date format
        date_match = re.search(r'last_updated_utc:\s*"(\d{8})"', content_str)
        if date_match and len(date_match.group(1)) != 8:
            add_issue(rel, "bad_date_format")
        
        # Check path mismatch
        path_match = re.search(r'file_path_from_root:\s*"([^"]+)"', content_str)
        if path_match and path_match.group(1) != rel:
            add_issue(rel, "path_mismatch")
        
        # Check for legacy blocks
        if any(key in content_str for key in ["wolfie.headers:", "flip.headers:", "flp.headers:", "flph.headers:", "crop.headers:"]):
            add_issue(rel, "legacy_blocks")
    
    except Exception as e:
        log(f"PHASE 4: ERROR scanning {rel}: {e}")

# Write issues report
with open(ISSUES_TXT, "w", encoding="utf-8") as f:
    f.write("FLARE Header Issues Report\n")
    f.write("=" * 40 + "\n\n")
    
    for issue_type, count in sorted(issue_counts.items()):
        f.write(f"{issue_type}: {count} files\n")
    
    f.write("\nDetailed Issues:\n")
    f.write("-" * 20 + "\n")
    
    for item in issues_report:
        f.write(f"{item['path']}: {', '.join(item['issues'])}\n")

with open(ISSUES_JSON, "w", encoding="utf-8") as f:
    json.dump(issues_report, f, indent=2)

log(f"PHASE 4: Issues report generated - {len(issues_report)} files with issues")

# Fix the issues
phase4_fixed = 0
for item in issues_report:
    rel = item["path"]
    issues = item["issues"]
    
    if not os.path.exists(rel):
        continue
    
    try:
        content = read_text(rel)
        flare_start, flare_end, _ = detect_flare_block(content)
        
        if flare_start == -1:
            continue
        
        lines = content.splitlines()
        modified = False
        
        # Fix each issue
        for issue in issues:
            if issue == "missing_file_path":
                # Add file_path_from_root
                for i in range(flare_start, flare_end + 1):
                    if "flare.edges:" in lines[i]:
                        lines.insert(i+1, f"  file_path_from_root: \"{rel}\"")
                        modified = True
                        break
            
            elif issue == "missing_hash":
                # Compute hash of body content (excluding header)
                body_content = "\n".join(lines[flare_end+1:])
                file_hash = compute_hash(body_content)
                for i in range(flare_start, flare_end + 1):
                    if "file_path_from_root:" in lines[i]:
                        lines.insert(i+1, f"  file_hash: \"{file_hash}\"")
                        modified = True
                        break
            
            elif issue == "missing_system_version":
                for i in range(flare_start, flare_end + 1):
                    if "flare.footer:" in lines[i]:
                        lines.insert(i, f"  system_version: \"{SYSTEM_VERSION}\"")
                        modified = True
                        break
            
            elif issue == "missing_updated":
                for i in range(flare_start, flare_end + 1):
                    if "flare.footer:" in lines[i]:
                        lines.insert(i, f"  last_updated_utc: \"{UTC_DATE}\"")
                        modified = True
                        break
            
            elif issue == "bad_date_format":
                for i in range(flare_start, flare_end + 1):
                    if re.match(r'^\s*last_updated_utc:', lines[i]):
                        lines[i] = f"  last_updated_utc: \"{UTC_DATE}\""
                        modified = True
                        break
            
            elif issue == "path_mismatch":
                for i in range(flare_start, flare_end + 1):
                    if re.match(r'^\s*file_path_from_root:', lines[i]):
                        lines[i] = f"  file_path_from_root: \"{rel}\""
                        modified = True
                        break
        
        if modified:
            write_atomic(rel, "\n".join(lines))
            phase4_fixed += 1
            log(f"PHASE 4: Fixed issues: {rel}")
    
    except Exception as e:
        log(f"PHASE 4: ERROR fixing {rel}: {e}")

log(f"PHASE 4: Complete - Fixed {phase4_fixed} files")

# =========================
# CREATE VALIDATION SCRIPT
# =========================
log("Creating validation script...")

validate_script = '''#!/usr/bin/env python3
import os
import sys
import json
import yaml
import re
from datetime import datetime

def validate_flare_file(path):
    """Validate a single FLARE file"""
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
                for j in range(i+1, min(i+50, len(lines))):
                    if lines[j].strip() == "---":
                        block_content = "\\n".join(lines[i:j+1])
                        if "flare.headers:" in block_content:
                            flare_start, flare_end = i, j
                            break
                if flare_start != -1:
                    break
        
        if flare_start == -1:
            errors.append("No FLARE header found")
            return errors, warnings
        
        block_lines = lines[flare_start:flare_end+1]
        block_content = "\\n".join(block_lines)
        
        # Required fields
        required_fields = [
            "flare.version",
            "flare.schema", 
            "flare.edges",
            "file_path_from_root",
            "file_hash",
            "last_updated_utc",
            "system_version"
        ]
        
        for field in required_fields:
            if field not in block_content:
                errors.append(f"Missing required field: {field}")
        
        # Date format validation
        date_match = re.search(r'last_updated_utc:\\s*"(\d{8})"', block_content)
        if date_match:
            date_str = date_match.group(1)
            if len(date_str) != 8:
                errors.append("Invalid date format (must be YYYYMMDD)")
        
        # Path validation
        path_match = re.search(r'file_path_from_root:\\s*"([^"]+)"', block_content)
        if path_match:
            expected_path = os.path.relpath(path, ".")
            if path_match.group(1) != expected_path:
                errors.append(f"Path mismatch: {path_match.group(1)} != {expected_path}")
        
        # Warnings
        if "needs_review:" in block_content:
            warnings.append("Has needs_review fields")
        
        if any(key in block_content for key in ["wolfie.headers:", "flip.headers:", "flp.headers:", "flph.headers:", "crop.headers:"]):
            warnings.append("Has legacy blocks")
        
    except Exception as e:
        errors.append(f"Validation error: {e}")
    
    return errors, warnings

def main():
    if len(sys.argv) > 1 and sys.argv[1] == "--ci":
        ci_mode = True
    else:
        ci_mode = False
    
    # Read index
    index_file = "tools/flare_file_index.txt"
    if not os.path.exists(index_file):
        print("ERROR: tools/flare_file_index.txt not found")
        if ci_mode:
            sys.exit(1)
        return
    
    with open(index_file, "r", encoding="utf-8") as f:
        paths = [line.strip() for line in f if line.strip()]
    
    total_errors = 0
    total_warnings = 0
    
    for path in paths:
        if not path.endswith(".md"):
            continue
        
        if not os.path.exists(path):
            continue
        
        errors, warnings = validate_flare_file(path)
        
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
    
    print(f"\\nValidation complete: {total_errors} errors, {total_warnings} warnings")
    
    if ci_mode and total_errors > 0:
        sys.exit(1)

if __name__ == "__main__":
    main()
'''

with open(VALIDATE_SCRIPT, "w", encoding="utf-8") as f:
    f.write(validate_script)

log("Validation script created")

# =========================
# PHASE 5: Commit Changes
# =========================
log("PHASE 5: Committing changes...")

try:
    # Stage all changes
    subprocess.run(["git", "add", "-A"], check=True, capture_output=True, text=True)
    
    # Check if there are staged changes
    result = subprocess.run(["git", "diff", "--cached", "--quiet"], 
                          capture_output=True, text=True)
    
    if result.returncode == 0:
        log("PHASE 5: No staged changes detected; skipping commit.")
    else:
        subprocess.run(["git", "commit", "-m", "FLARE: Standardized headers, fixed issues in files, added validation script"], 
                      check=True, capture_output=True, text=True)
        log("PHASE 5: Commit created (local only).")
        
except subprocess.CalledProcessError as e:
    log(f"PHASE 5: ERROR git operation failed: {e}")
except FileNotFoundError:
    log("PHASE 5: ERROR git not found; skipping git add/commit.")

# =========================
# SUMMARY
# =========================
log("FLARE standardization complete")
log(f"Summary: Phase1={phase1_updated}, Phase2={phase2_updated}, Phase3={phase3_updated}, Phase4={phase4_fixed}")
log(f"Issues found: {len(issues_report)} files with {sum(issue_counts.values())} total issues")
log("DONE.")
print("OK")
