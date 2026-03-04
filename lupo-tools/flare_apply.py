#!/usr/bin/env python3
import os
import sys
import subprocess
import json
import hashlib
import tempfile
import re
from datetime import datetime, timedelta

# =========================
# FLARE SYSTEM-WIDE APPLY
# =========================

ROOT = os.getcwd()
TOOLS_DIR = "tools"
LOG = os.path.join(TOOLS_DIR, "flare_processing_log.txt")
INDEX = os.path.join(TOOLS_DIR, "flare_md_index.txt")

SYSTEM_VERSION = "4.0.56"
UTC_DATE = datetime.utcnow().strftime("%Y%m%d")
VERIFIED_BY = "antigravity"
MOOD_RGB_DEFAULT = "4169E1"
Lupo_AGENT = "antigravity"
ACTOR_ID_DEFAULT = "1004"

os.makedirs(TOOLS_DIR, exist_ok=True)

def log(msg):
    timestamp = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
    with open(LOG, "a", encoding="utf-8") as f:
        f.write(f"[{timestamp} UTC] {msg}\n")
    print(f"[{timestamp} UTC] {msg}")

log(f"Starting FLARE processing in repo root: {ROOT}")
log(f"system_version={SYSTEM_VERSION} utc_date={UTC_DATE}")

# -------------------------
# Phase 1: Index all .md
# -------------------------
log("PHASE 1: Scanning for .md files (recursive, excluding .git)...")

def find_markdown_files():
    paths = []
    for root, dirs, files in os.walk("."):
        if ".git" in dirs:
            dirs.remove(".git")
        for file in files:
            if file.endswith(".md"):
                rel_path = os.path.relpath(os.path.join(root, file), ".")
                if rel_path not in paths:  # avoid duplicates
                    paths.append(rel_path)
    return sorted(paths, key=lambda x: x.lower())

paths = find_markdown_files()

with open(INDEX, "w", encoding="utf-8", newline="\n") as f:
    for path in paths:
        f.write(path + "\n")

log(f"PHASE 1: Index written to {INDEX}")
log(f"PHASE 1: Total .md files found: {len(paths)}")

# -------------------------
# Phase 2: Apply headers
# -------------------------
log("PHASE 2: Processing each file in index...")

def read_text(path):
    with open(path, "r", encoding="utf-8", errors="replace") as f:
        return f.read()

def write_atomic(path, content):
    d = os.path.dirname(path) or "."
    fd, tmp = tempfile.mkstemp(prefix=".flare_tmp_", dir=d)
    os.close(fd)
    with open(tmp, "w", encoding="utf-8", newline="\n") as f:
        f.write(content)
    os.replace(tmp, path)

def detect_existing_flare_header(text):
    head = "\n".join(text.splitlines()[:120])
    if "# FLARE Header" in head:
        return True
    if re.search(r"(?ms)^---\s*\n.*^\s*flare\.headers:\s*\n", head):
        return True
    return False

def validate_flare_header(text):
    head = "\n".join(text.splitlines()[:180])
    issues = []
    if not re.search(r"(?m)^\s*flare\.headers:\s*$", head):
        issues.append("missing flare.headers")
    if "file_path_from_root:" not in head:
        issues.append("missing file_path_from_root")
    if "last_updated_utc:" not in head:
        issues.append("missing last_updated_utc")
    if "system_version:" not in head:
        issues.append("missing system_version")
    if "file_hash:" not in head:
        issues.append("missing file_hash")
    return issues

def infer_channel_actor(path):
    m = re.search(r"(?:^|/)channels/(\d+)/(?:actors)/(\d+)(?:/|$)", path)
    if m:
        return int(m.group(1)), int(m.group(2))
    m2 = re.search(r"(?:^|/)channels/(\d+)(?:/|$)", path)
    if m2:
        return int(m2.group(1)), None
    return None, None

def detect_anubis_flare_ingestion(path, original_text):
    """Detect if this is an ANUBIS FLARE ingestion request"""
    # Check if path indicates ANUBIS (actor 19) and contains FLARE ingestion keywords
    if "actors/19/" in path and ("flare_ingestion" in original_text.lower() or "ingestion" in original_text.lower()):
        return True
    return False

def apply_anubis_flare_ingestion(path, original_text):
    """Apply ANUBIS FLARE ingestion processing"""
    if not detect_anubis_flare_ingestion(path, original_text):
        return None
    
    # This would call the ANUBIS faucet for FLARE ingestion
    # For now, return a marker that this should be processed by ANUBIS
    return {
        "processed_by": "anubis_flare_ingestion",
        "actor_id": 19,
        "file_path": path,
        "status": "queued_for_processing"
    }

def infer_schema(path):
    p = path.lower()
    if p.endswith("help.md") or "/help/" in p:
        return "help"
    if "/status/" in p or p.endswith("status.md"):
        return "status"
    if "/tasks/" in p or p.endswith("task.md"):
        return "task"
    if "/threads/" in p:
        return "thread"
    if "/doctrine/" in p:
        return "doctrine"
    if "/channels/" in p:
        return "channel_doc"
    if "/artifacts/" in p:
        return "artifact_doc"
    return "documentation"

def infer_purpose(path, body_text):
    for line in body_text.splitlines():
        s = line.strip()
        if not s:
            continue
        if s.startswith("#"):
            return s.lstrip("#").strip() or f"Documentation for {os.path.basename(path)}"
        return f"Documentation for {os.path.basename(path)}"
    return f"Documentation for {os.path.basename(path)}"

def compute_hash_excluding_new_header(original_text):
    return hashlib.sha256(original_text.encode("utf-8", "replace")).hexdigest()

def build_header(path, original_text):
    schema = infer_schema(path)
    channel_id, actor_id = infer_channel_actor(path)
    purpose = infer_purpose(path, original_text)

    flare_version = "1.0"
    artifact_type = "guide"
    artifact_kind = "documentation"
    mood_rgb = MOOD_RGB_DEFAULT
    
    current_actor = actor_id if actor_id is not None else int(ACTOR_ID_DEFAULT)
    delegation_chain = f"{current_actor}:10000"

    tags = []
    parts = [p for p in re.split(r"[\\/]+", path) if p]
    for t in parts[:6]:
        tt = re.sub(r"[^a-zA-Z0-9_-]+", "", t)
        if tt:
            tags.append(tt.lower())
    seen = set()
    tags = [t for t in tags if not (t in seen or seen.add(t))]

    file_hash = compute_hash_excluding_new_header(original_text)

    header = (
f"# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)\n\n"
f"---\n"
f"flame.init:\n"
f"  requirements:\n"
f"    flare:\n"
f"      version: \">=4.0.55\"\n"
f"  execution_mode: \"advisory\"\n"
f"  pre_actions:\n"
f"    - type: dependency_check\n"
f"      path: \"lupo-includes/bootstrap.php\"\n\n"
f"flare.conditional:\n"
f"  guards:\n"
f"    execution_mode: \"advisory\"\n"
f"    allow:\n"
f"      actor_ids: [0, {current_actor}]\n"
f"      agent_names: [\"system\", \"{Lupo_AGENT}\"]\n"
f"    deny:\n"
f"      actor_ids: []\n"
f"    time_window:\n"
f"      not_before_utc: \"{datetime.utcnow().strftime('%Y-%m-%dT00:00:00Z')}\"\n"
f"      not_after_utc: \"{(datetime.utcnow() + timedelta(days=7)).strftime('%Y-%m-%dT00:00:00Z')}\"\n"
f"    conditions:\n"
f"      - type: feature_flag_enabled\n"
f"        flag: \"FLAME_V1\"\n"
f"  brief:\n"
f"    who:\n"
f"      owner_actor_id: {current_actor}\n"
f"      intended_actors: [0, {current_actor}]\n"
f"      audience: [\"agents\"]\n"
f"    what:\n"
f"      artifact_type: \"{artifact_type}\"\n"
f"      objective: \"{purpose}\"\n"
f"    where:\n"
f"      repo_paths: [\"{path}\"]\n"
f"      runtime_scope: \"cli\"\n"
f"      channels:\n"
f"        primary_channel_id: {channel_id if channel_id is not None else 1}\n"
f"    when:\n"
f"      urgency: \"standard\"\n"
f"      effective_utc: \"{datetime.utcnow().strftime('%Y-%m-%dT%H:%M:%SZ')}\"\n"
f"    why:\n"
f"      rationale: \"Standard artifact generation\"\n"
f"    how:\n"
f"      method: \"FLARE automated application\"\n"
f"      success_criteria: [\"header applied correctly\"]\n\n"
f"flare.headers:\n"
f"  flare.version: \"{flare_version}\"\n"
f"  flare.schema: \"{schema}\"\n"
f"  file_path_from_root: \"{path}\"\n"
f"  file_hash: \"{file_hash}\"\n"
f"  last_updated_utc: \"{UTC_DATE}\"\n"
f"  system_version: \"{SYSTEM_VERSION}\"\n"
f"  channel_id: {channel_id if channel_id is not None else 1}\n"
f"  actor_id: {current_actor}\n"
f"  delegation_chain: \"{delegation_chain}\"\n"
f"  artifact_type: \"{artifact_type}\"\n"
f"  artifact_kind: \"{artifact_kind}\"\n"
f"  purpose: \"{purpose}\"\n"
f"  mood_rgb: \"{mood_rgb}\"\n"
f"  traits: [\"flare\", \"indexed\", \"v{SYSTEM_VERSION}\"]\n"
f"  tags: {json.dumps(tags)}\n"
f"  lupo_agent: \"{Lupo_AGENT}\"\n\n"
f"flare.edges:\n"
f"  outbound_edges: []\n\n"
f"flare.footer:\n"
f"  last_verified: \"{UTC_DATE}\"\n"
f"  last_verified_by: \"{VERIFIED_BY}\"\n\n"
f"flame.close:\n"
f"  post_actions:\n"
f"    - type: register_completion\n"
f"      channel_id: 0\n"
f"  actor_id: {current_actor}\n"
f"---\n\n"
    )
    return header

def update_meta_flare_json(md_path):
    parent = os.path.dirname(md_path) or "."
    meta_dir = os.path.join(parent, "meta")
    os.makedirs(meta_dir, exist_ok=True)
    meta_path = os.path.join(meta_dir, "flare.json")
    entry = {"file": md_path, "flare_applied": True, "timestamp": UTC_DATE}

    data = {"files": []}
    if os.path.exists(meta_path):
        try:
            raw = read_text(meta_path).strip()
            if raw:
                data = json.loads(raw)
            if not isinstance(data, dict):
                data = {"files": []}
            if "files" not in data or not isinstance(data["files"], list):
                data["files"] = []
        except Exception as e:
            log(f"PHASE 2: WARN meta/flare.json parse failed at {meta_path}: {e}; overwriting with fresh structure.")
            data = {"files": []}

    by_file = {f.get("file"): f for f in data["files"] if isinstance(f, dict) and "file" in f}
    by_file[md_path] = entry
    files_sorted = [by_file[k] for k in sorted(by_file.keys(), key=lambda x: (x or "").lower())]
    data["files"] = files_sorted

    write_atomic(meta_path, json.dumps(data, indent=2, sort_keys=True) + "\n")

added = 0
skipped_existing = 0
errors = 0
existing_with_issues = 0

for rel in paths:
    if not rel or rel.endswith("/"):
        continue
    if not os.path.exists(rel):
        log(f"PHASE 2: ERROR missing file: {rel}")
        errors += 1
        continue
    try:
        original = read_text(rel)
        
        # Check for ANUBIS FLARE ingestion request
        anubis_result = apply_anubis_flare_ingestion(rel, original)
        if anubis_result:
            log(f"PHASE 2: DETECTED ANUBIS FLARE ingestion request: {rel}")
            # Create a marker file for ANUBIS to process
            marker_path = rel.replace(".md", "_anubis_processing.json")
            write_atomic(marker_path, json.dumps(anubis_result, indent=2))
            log(f"PHASE 2: Created ANUBIS processing marker: {marker_path}")
            skipped_existing += 1
            continue
        
        if detect_existing_flare_header(original):
            issues = validate_flare_header(original)
            if issues:
                log(f"PHASE 2: WARN existing FLARE header issues in {rel}: {', '.join(issues)}")
                existing_with_issues += 1
            else:
                log(f"PHASE 2: SKIP (existing FLARE header OK): {rel}")
            skipped_existing += 1
            continue

        header = build_header(rel, original)
        new_text = header + original.lstrip("\ufeff")
        write_atomic(rel, new_text)
        update_meta_flare_json(rel)
        log(f"PHASE 2: ADDED FLARE header: {rel}")
        added += 1

    except PermissionError:
        log(f"PHASE 2: ERROR unwritable file (permission): {rel}")
        errors += 1
    except Exception as e:
        log(f"PHASE 2: ERROR processing {rel}: {e}")
        errors += 1

log(f"PHASE 2: COMPLETE processed={len(paths)} added={added} skipped_existing={skipped_existing} existing_with_issues={existing_with_issues} errors={errors}")

# Write summary
log(f"SUMMARY processed={len(paths)} added={added} skipped={skipped_existing} issues={existing_with_issues} errors={errors}")

# -------------------------
# Phase 3: Git stage+commit
# -------------------------
log("PHASE 3: Staging and committing changes...")

try:
    # Check if git repository
    subprocess.run(["git", "rev-parse", "--is-inside-work-tree"], 
                  check=True, capture_output=True, text=True)
    
    # Stage all changes
    subprocess.run(["git", "add", "-A"], check=True, capture_output=True, text=True)
    
    # Check if there are staged changes
    result = subprocess.run(["git", "diff", "--cached", "--quiet"], 
                          capture_output=True, text=True)
    
    if result.returncode == 0:
        log("PHASE 3: No staged changes detected; skipping commit.")
    else:
        subprocess.run(["git", "commit", "-m", "FLARE: Indexed all markdown files and applied FLARE headers system-wide"], 
                      check=True, capture_output=True, text=True)
        log("PHASE 3: Commit created (local only).")
        
except subprocess.CalledProcessError as e:
    log(f"PHASE 3: ERROR git operation failed: {e}")
except FileNotFoundError:
    log("PHASE 3: ERROR git not found; skipping git add/commit.")

log("DONE.")
print("OK")
