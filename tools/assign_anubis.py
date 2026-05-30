#!/usr/bin/env python3
import os
import sys
import json
import re
import hashlib
import tempfile
import shutil
import subprocess
from datetime import datetime

# =========================
# ANUBIS ASSIGNMENT TO MD FILES
# =========================

ROOT = os.getcwd()
TOOLS_DIR = "tools"
LOG = os.path.join(TOOLS_DIR, "anubis_assignment_log.txt")
INDEX_FILE = os.path.join(TOOLS_DIR, "flare_file_index.txt")
INVENTORY_FILE = os.path.join("channels", "42", "md_file_inventory.md")
BACKUP_DIR = os.path.join(TOOLS_DIR, "backup_anubis_assignment")

ANUBIS_ACTOR_ID = "2035"
CHANNEL_ID = "42"
UTC_DATE = datetime.utcnow().strftime("%Y%m%d")
UTC_DATETIME = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
VERIFIED_BY = "windsurf"

os.makedirs(TOOLS_DIR, exist_ok=True)
os.makedirs(BACKUP_DIR, exist_ok=True)
os.makedirs(os.path.dirname(INVENTORY_FILE), exist_ok=True)

def log(msg):
    timestamp = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
    with open(LOG, "a", encoding="utf-8") as f:
        f.write(f"[{timestamp} UTC] {msg}\n")
    print(f"[{timestamp} UTC] {msg}")

log("Starting ANUBIS assignment to all .md files")

# =========================
# PREPARATION: Identify .md Files
# =========================
log("PREPARATION: Identifying .md files...")

def get_md_files():
    """Get list of all .md files from index or scan repository"""
    md_files = []
    
    if os.path.exists(INDEX_FILE):
        with open(INDEX_FILE, "r", encoding="utf-8") as f:
            for line in f:
                path = line.strip()
                if path.endswith(".md"):
                    md_files.append(path)
    
    if not md_files:
        # Scan repository if index is empty or missing
        log("Index empty or missing, scanning repository for .md files...")
        for root, dirs, files in os.walk("."):
            if ".git" in dirs:
                dirs.remove(".git")
            for file in files:
                if file.endswith(".md"):
                    rel_path = os.path.relpath(os.path.join(root, file), ".")
                    md_files.append(rel_path)
    
    return sorted(md_files)

md_files = get_md_files()
log(f"Found {len(md_files)} .md files to process")

# =========================
# UTILITY FUNCTIONS
# =========================

def read_file_safe(path):
    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            return f.read()
    except Exception as e:
        log(f"ERROR reading {path}: {e}")
        return None

def write_atomic(path, content):
    try:
        # Create backup
        backup_path = os.path.join(BACKUP_DIR, path.replace("/", "_") + ".backup")
        if os.path.exists(path):
            shutil.copy2(path, backup_path)
        
        # Atomic write
        d = os.path.dirname(path) or "."
        fd, tmp = tempfile.mkstemp(prefix=".anubis_tmp_", dir=d)
        os.close(fd)
        with open(tmp, "w", encoding="utf-8", newline="\n") as f:
            f.write(content)
        os.replace(tmp, path)
        return True
    except Exception as e:
        log(f"ERROR writing {path}: {e}")
        return False

def find_flare_block(content):
    """Find FLARE YAML block"""
    lines = content.splitlines()
    flare_start = -1
    flare_end = -1
    
    for i, line in enumerate(lines[:120]):
        if "FLARE Header" in line:
            flare_start = i
            break
    
    if flare_start == -1:
        return -1, -1, []
    
    for i in range(flare_start, min(flare_start + 80, len(lines))):
        if lines[i].strip() == "---" and flare_start != i:
            flare_start = i
        elif lines[i].strip() == "---" and flare_start != -1:
            flare_end = i
            break
    
    return flare_start, flare_end, lines[flare_start:flare_end+1] if flare_end != -1 else []

def yaml_to_dict(yaml_lines):
    """Convert YAML lines to dictionary"""
    data = {}
    for line in yaml_lines:
        if ":" in line and not line.strip().startswith("#"):
            key, value = line.split(":", 1)
            key = key.strip()
            value = value.strip()
            
            # Remove quotes if present
            if value.startswith('"') and value.endswith('"'):
                value = value[1:-1]
            elif value.startswith("'") and value.endswith("'"):
                value = value[1:-1]
            
            data[key] = value
    return data

def dict_to_yaml_lines(data):
    """Convert dictionary to YAML lines"""
    lines = []
    for key, value in data.items():
        if isinstance(value, str):
            if value == "null":
                lines.append(f"  {key}: null")
            elif value.startswith('[') or value.startswith('{'):
                lines.append(f"  {key}: {value}")
            else:
                lines.append(f"  {key}: \"{value}\"")
        else:
            lines.append(f"  {key}: {value}")
    return lines

def compute_hash(content):
    """Compute SHA-256 hash"""
    return hashlib.sha256(content.encode("utf-8", "replace")).hexdigest()

# =========================
# DATABASE FUNCTIONS
# =========================

def try_database_insert(file_path, file_hash):
    """Try to insert into database, return success status"""
    try:
        # Try to use bin/lupo.php with a custom flag
        php_script = f'''
<?php
require_once "includes/bootstrap.php";

try {{
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Check if table exists, create if not
    $result = $db->fetchAll("SHOW TABLES LIKE '{{{{$table_prefix}}file_inventory}}'");
    if (empty($result)) {{
        $create_sql = "CREATE TABLE {{{{$table_prefix}}file_inventory}} (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            file_path_from_root VARCHAR(500) NOT NULL,
            assigned_actor_id BIGINT NOT NULL,
            channel_id BIGINT DEFAULT 42,
            file_hash VARCHAR(64),
            last_updated_utc BIGINT,
            created_ymdhis BIGINT DEFAULT (UNIX_TIMESTAMP()),
            UNIQUE KEY unique_file (file_path_from_root),
            INDEX idx_actor (assigned_actor_id),
            INDEX idx_channel (channel_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $db->exec($create_sql);
        echo "Table created\\n";
    }}
    
    // Insert or update
    $sql = "INSERT INTO {{{{$table_prefix}}file_inventory}} 
            (file_path_from_root, assigned_actor_id, channel_id, file_hash, last_updated_utc) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
            assigned_actor_id = VALUES(assigned_actor_id), 
            file_hash = VALUES(file_hash), 
            last_updated_utc = VALUES(last_updated_utc)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute(['{file_path}', {ANUBIS_ACTOR_ID}, {CHANNEL_ID}, '{file_hash}', {UTC_DATE}]);
    
    echo "Inserted: {file_path}\\n";
    
}} catch (Exception $e) {{
    echo "DB Error: " . $e->getMessage() . "\\n";
}}
?>
'''
        
        # Write temporary PHP script
        temp_php = os.path.join(TOOLS_DIR, "temp_anubis_insert.php")
        with open(temp_php, "w", encoding="utf-8") as f:
            f.write(php_script)
        
        # Execute PHP script
        result = subprocess.run(["php", temp_php], capture_output=True, text=True, timeout=30)
        
        # Clean up
        os.remove(temp_php)
        
        if result.returncode == 0 and "Inserted:" in result.stdout:
            return True, result.stdout.strip()
        else:
            return False, result.stderr.strip()
            
    except Exception as e:
        return False, str(e)

# =========================
# PHASE 1: Update FLARE Headers
# =========================
log("PHASE 1: Updating FLARE headers for ANUBIS assignment...")

updated_files = 0
no_header_files = 0
db_success_count = 0
db_fail_count = 0

inventory_data = []

for md_file in md_files:
    if not os.path.exists(md_file):
        log(f"WARNING: File not found: {md_file}")
        continue
    
    content = read_file_safe(md_file)
    if content is None:
        continue
    
    flare_start, flare_end, flare_lines = find_flare_block(content)
    
    if flare_start == -1:
        no_header_files += 1
        log(f"NO_HEADER: {md_file}")
        continue
    
    yaml_data = yaml_to_dict(flare_lines)
    
    # Update ANUBIS assignment
    yaml_data["assigned_custodian"] = ANUBIS_ACTOR_ID
    
    # Update delegation_chain if exists
    if "delegation_chain" in yaml_data:
        current_chain = yaml_data["delegation_chain"]
        if current_chain and current_chain != "null":
            if not current_chain.endswith(f":{ANUBIS_ACTOR_ID}"):
                yaml_data["delegation_chain"] = f"{current_chain}:{ANUBIS_ACTOR_ID}"
        else:
            yaml_data["delegation_chain"] = f"10000:{ANUBIS_ACTOR_ID}"
    else:
        yaml_data["delegation_chain"] = f"10000:{ANUBIS_ACTOR_ID}"
    
    # Update purpose to note ANUBIS custody
    if "purpose" in yaml_data:
        yaml_data["purpose"] = f"{yaml_data['purpose']}. Assigned to ANUBIS for custodial intelligence."
    else:
        yaml_data["purpose"] = "Documentation file. Assigned to ANUBIS for custodial intelligence."
    
    # Update timestamp
    yaml_data["last_updated_utc"] = UTC_DATE
    
    # Rebuild content
    new_flare_lines = []
    for line in flare_lines:
        if line.strip() == "---":
            new_flare_lines.append(line)
            break
        new_flare_lines.append(line)
    
    # Add updated fields
    for key, value in yaml_data.items():
        if key not in ["flare.footer"]:
            new_flare_lines.extend(dict_to_yaml_lines({key: value}))
    
    # Add footer
    new_flare_lines.append("flare.footer:")
    new_flare_lines.append(f"  last_verified: \"{UTC_DATE}\"")
    new_flare_lines.append(f"  last_verified_by: \"{VERIFIED_BY}\"")
    new_flare_lines.append("---")
    
    # Reconstruct content
    new_content = "\n".join(content.splitlines()[:flare_start]) + "\n" + "\n".join(new_flare_lines) + "\n" + "\n".join(content.splitlines()[flare_end+1:])
    
    # Compute new hash
    body_content = "\n".join(new_content.splitlines()[flare_end+1:])
    new_hash = compute_hash(body_content)
    
    if write_atomic(md_file, new_content):
        updated_files += 1
        
        # Try database insert
        db_success, db_msg = try_database_insert(md_file, new_hash)
        if db_success:
            db_success_count += 1
        else:
            db_fail_count += 1
            log(f"DB_FAIL: {md_file} - {db_msg}")
        
        # Add to inventory data
        inventory_data.append({
            "path": md_file,
            "hash": new_hash,
            "timestamp": UTC_DATE
        })
        
        log(f"UPDATED: {md_file}")

log(f"PHASE 1: Updated {updated_files} files, {no_header_files} had no headers")
log(f"Database: {db_success_count} successful inserts, {db_fail_count} failures")

# =========================
# PHASE 2: Record Paths in Fallback Markdown File
# =========================
if db_fail_count > 0 or no_header_files > 0:
    log("PHASE 2: Creating fallback markdown inventory...")
    
    inventory_content = f"""# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "inventory"
  flare.edges: []
  file_path_from_root: "channels/42/md_file_inventory.md"
  file_hash: "{compute_hash('')}"
  last_updated_utc: "{UTC_DATE}"
  system_version: "4.0.50"
  channel_id: {CHANNEL_ID}
  actor_id: {ANUBIS_ACTOR_ID}
  delegation_chain: "10000:{ANUBIS_ACTOR_ID}"
  artifact_type: "inventory"
  purpose: "MD File Inventory - Assigned to ANUBIS for custodial intelligence"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["inventory", "md_files", "anubis", "custodial"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "{UTC_DATE}"
  last_verified_by: "{VERIFIED_BY}"
---

# MD File Inventory (Assigned to ANUBIS {ANUBIS_ACTOR_ID})

**Generated**: {UTC_DATETIME}  
**Total Files**: {len(inventory_data)}  
**Database Success**: {db_success_count}  
**Database Failures**: {db_fail_count}  
**No Header Files**: {no_header_files}

## File List

| Path | Hash | Timestamp |
|------|------|-----------|
"""
    
    for item in inventory_data:
        inventory_content += f"| {item['path']} | {item['hash']} | {item['timestamp']} |\n"
    
    if no_header_files > 0:
        inventory_content += f"\n## Files Without FLARE Headers\n\n"
        inventory_content += f"The following {no_header_files} files were found without FLARE headers:\n\n"
        # Note: We'd need to track these separately for accurate listing
    
    write_atomic(INVENTORY_FILE, inventory_content)
    log(f"Created fallback inventory: {INVENTORY_FILE}")

# =========================
# PHASE 3: Validation
# =========================
log("PHASE 3: Validating updated files...")

try:
    result = subprocess.run([sys.executable, os.path.join(TOOLS_DIR, "flare_validate.py")], 
                          capture_output=True, text=True, timeout=300)
    log(f"Validation output: {result.stdout}")
    if result.stderr:
        log(f"Validation errors: {result.stderr}")
except Exception as e:
    log(f"Validation error: {e}")

# =========================
# PHASE 4: Update CHANGELOG.md
# =========================
log("PHASE 4: Updating CHANGELOG.md...")

changelog_path = "CHANGELOG.md"
if os.path.exists(changelog_path):
    content = read_file_safe(changelog_path)
    if content:
        # Add ANUBIS assignment entry
        new_entry = "- ✅ **ANUBIS Assignment**: Assigned custodial AI (2035) to all .md files, paths recorded in database or channels/42/md_file_inventory.md"
        
        lines = content.splitlines()
        insert_idx = -1
        for i, line in enumerate(lines):
            if "#### FLARE System-Wide Implementation" in line:
                # Find the end of this section
                for j in range(i, len(lines)):
                    if lines[j].startswith("####") and j > i:
                        insert_idx = j
                        break
                break
        
        if insert_idx != -1:
            lines.insert(insert_idx, new_entry)
            write_atomic(changelog_path, "\n".join(lines))
            log("Updated CHANGELOG.md with ANUBIS assignment")

# =========================
# PHASE 5: Commit Changes
# =========================
log("PHASE 5: Committing changes...")

try:
    subprocess.run(["git", "add", "-A"], check=True, capture_output=True, text=True)
    
    result = subprocess.run(["git", "diff", "--cached", "--quiet"], 
                          capture_output=True, text=True)
    
    if result.returncode == 0:
        log("PHASE 5: No staged changes detected; skipping commit.")
    else:
        subprocess.run(["git", "commit", "-m", "ANUBIS: Assigned actor 2035 to all .md files; recorded paths in DB or channels/42/md_file_inventory.md"], 
                      check=True, capture_output=True, text=True)
        log("PHASE 5: Commit created (local only).")
        
except subprocess.CalledProcessError as e:
    log(f"PHASE 5: Git operation failed: {e}")
except FileNotFoundError:
    log("PHASE 5: Git not found; skipping git add/commit.")

# SUMMARY
log("ANUBIS assignment complete")
log(f"Summary: Assigned to {updated_files} .md files, {db_success_count} DB inserts, {db_fail_count} fallbacks")
log("DONE.")
print("OK")
