#!/usr/bin/env bash
set -euo pipefail

# =========================
# FLARE SYSTEM-WIDE APPLY
# =========================

ROOT="$(pwd)"
TOOLS_DIR="tools"
LOG="$TOOLS_DIR/flare_processing_log.txt"
INDEX="$TOOLS_DIR/flare_md_index.txt"

SYSTEM_VERSION="4.0.50"
UTC_DATE="$(date -u +%Y%m%d)"
VERIFIED_BY="windsurf"
MOOD_RGB_DEFAULT="4169E1"
Lupo_AGENT="windsurf"
ACTOR_ID_DEFAULT="1002"

mkdir -p "$TOOLS_DIR"
: > "$LOG"

log() { printf "[%s UTC] %s\n" "$(date -u +%Y-%m-%dT%H:%M:%SZ)" "$*" | tee -a "$LOG" >/dev/null; }

log "Starting FLARE processing in repo root: $ROOT"
log "system_version=$SYSTEM_VERSION utc_date=$UTC_DATE"

# -------------------------
# Phase 1: Index all .md
# -------------------------
log "PHASE 1: Scanning for .md files (recursive, excluding .git)..."

# Find markdown files, repo-relative, sorted case-insensitive, no dups
# - Excludes .git directory
# - Preserves original casing in output
if ! find . -type d -name .git -prune -o -type f -name "*.md" -print0 2>>"$LOG" \
  | python3 - <<'PY' "$INDEX"
import sys, os
out = sys.argv[1]
paths = []
data = sys.stdin.buffer.read().split(b"\0")
for p in data:
    if not p: continue
    s = p.decode("utf-8", "replace")
    if s.startswith("./"): s = s[2:]
    if not s: continue
    paths.append(s)
# de-dupe while preserving original casing by first occurrence
seen = set()
uniq = []
for p in paths:
    k = p.lower()
    if k in seen: continue
    seen.add(k)
    uniq.append(p)
uniq.sort(key=lambda x: x.lower())
os.makedirs(os.path.dirname(out), exist_ok=True)
with open(out, "w", encoding="utf-8", newline="\n") as f:
    for p in uniq:
        f.write(p.rstrip("\n") + "\n")
print(len(uniq))
PY
then
  COUNT="$(wc -l < "$INDEX" | tr -d ' ')"
  log "PHASE 1: Index written to $INDEX"
  log "PHASE 1: Total .md files found: $COUNT"
else
  log "PHASE 1: ERROR during scan. See log for details."
fi

# -------------------------
# Phase 2: Apply headers
# -------------------------
log "PHASE 2: Processing each file in index..."

python3 - <<'PY' "$INDEX" "$LOG" "$UTC_DATE" "$SYSTEM_VERSION" "$VERIFIED_BY" "$MOOD_RGB_DEFAULT" "$Lupo_AGENT" "$ACTOR_ID_DEFAULT"
import sys, os, re, json, hashlib, tempfile, time
from datetime import datetime

index_path, log_path, utc_date, system_version, verified_by, mood_default, lupo_agent, actor_default = sys.argv[1:]

def log(msg):
    ts = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
    with open(log_path, "a", encoding="utf-8") as f:
        f.write(f"[{ts} UTC] {msg}\n")

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
    # Check near the beginning only
    head = "\n".join(text.splitlines()[:120])
    if "# FLARE Header" in head:
        return True
    # YAML-style block that includes flare.headers:
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
    # channels/<channel_id>/actors/<actor_id>/...
    m = re.search(r"(?:^|/)channels/(\d+)/(?:actors)/(\d+)(?:/|$)", path)
    if m:
        return int(m.group(1)), int(m.group(2))
    m2 = re.search(r"(?:^|/)channels/(\d+)(?:/|$)", path)
    if m2:
        return int(m2.group(1)), None
    return None, None

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
    # first non-empty line that looks like a title
    for line in body_text.splitlines():
        s = line.strip()
        if not s: 
            continue
        if s.startswith("#"):
            return s.lstrip("#").strip() or f"Documentation for {os.path.basename(path)}"
        return f"Documentation for {os.path.basename(path)}"
    return f"Documentation for {os.path.basename(path)}"

def compute_hash_excluding_new_header(original_text):
    # Since header is inserted only when missing, hash the pre-header content = original entire file
    return hashlib.sha256(original_text.encode("utf-8", "replace")).hexdigest()

def build_header(path, original_text):
    schema = infer_schema(path)
    channel_id, actor_id = infer_channel_actor(path)
    purpose = infer_purpose(path, original_text)

    # Conservative defaults
    flare_version = "1.0"
    artifact_type = "guide"
    artifact_kind = "documentation"
    mood_rgb = mood_default

    # delegation_chain is ambiguous in your doctrine; use a conservative, explicit chain and log it.
    delegation_chain = f"{actor_id if actor_id else actor_default}:10000"

    tags = []
    # simple deterministic tags from path
    parts = [p for p in re.split(r"[\\/]+", path) if p]
    for t in parts[:6]:
        tt = re.sub(r"[^a-zA-Z0-9_-]+", "", t)
        if tt:
            tags.append(tt.lower())
    # dedupe tags preserving order
    seen=set(); tags=[t for t in tags if not (t in seen or seen.add(t))]

    file_hash = compute_hash_excluding_new_header(original_text)

    header = (
f"# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)\n\n"
f"---\n"
f"flare.headers:\n"
f"  flare.version: \"{flare_version}\"\n"
f"  flare.schema: \"{schema}\"\n"
f"  flare.edges: []\n"
f"  file_path_from_root: \"{path}\"\n"
f"  file_hash: \"{file_hash}\"\n"
f"  last_updated_utc: \"{utc_date}\"\n"
f"  system_version: \"{system_version}\"\n"
    )
    if channel_id is not None:
        header += f"  channel_id: {channel_id}\n"
    else:
        header += f"  channel_id: 1\n"
    header += f"  actor_id: {actor_id if actor_id is not None else int(actor_default)}\n"
    header += (
f"  delegation_chain: \"{delegation_chain}\"\n"
f"  artifact_type: \"{artifact_type}\"\n"
f"  artifact_kind: \"{artifact_kind}\"\n"
f"  purpose: \"{purpose}\"\n"
f"  mood_rgb: \"{mood_rgb}\"\n"
f"  traits: [\"flare\", \"indexed\", \"v{system_version}\"]\n"
f"  tags: {json.dumps(tags)}\n"
f"  lupo_agent: \"{lupo_agent}\"\n\n"
f"flare.footer:\n"
f"  last_verified: \"{utc_date}\"\n"
f"  last_verified_by: \"{verified_by}\"\n"
f"---\n\n"
    )
    return header

def update_meta_flare_json(md_path):
    parent = os.path.dirname(md_path) or "."
    meta_dir = os.path.join(parent, "meta")
    os.makedirs(meta_dir, exist_ok=True)
    meta_path = os.path.join(meta_dir, "flare.json")
    entry = {"file": md_path, "flare_applied": True, "timestamp": utc_date}

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

    # upsert
    by_file = {f.get("file"): f for f in data["files"] if isinstance(f, dict) and "file" in f}
    by_file[md_path] = entry
    # deterministic sort
    files_sorted = [by_file[k] for k in sorted(by_file.keys(), key=lambda x: (x or "").lower())]
    data["files"] = files_sorted

    write_atomic(meta_path, json.dumps(data, indent=2, sort_keys=True) + "\n")

added = 0
skipped_existing = 0
errors = 0
existing_with_issues = 0

with open(index_path, "r", encoding="utf-8", errors="replace") as f:
    paths = [line.strip("\n") for line in f if line.strip()]

for rel in paths:
    if not rel or rel.endswith("/"):
        continue
    if not os.path.exists(rel):
        log(f"PHASE 2: ERROR missing file: {rel}")
        errors += 1
        continue
    try:
        original = read_text(rel)
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
        new_text = header + original.lstrip("\ufeff")  # strip BOM if present
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

# Write a tiny machine-readable summary footer into the log (last line)
log(f"SUMMARY processed={len(paths)} added={added} skipped={skipped_existing} issues={existing_with_issues} errors={errors}")
PY

# -------------------------
# Phase 3: Git stage+commit
# -------------------------
log "PHASE 3: Staging and committing changes..."

if git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  git add -A 2>>"$LOG" || true
  if git diff --cached --quiet; then
    log "PHASE 3: No staged changes detected; skipping commit."
  else
    git commit -m "FLARE: Indexed all markdown files and applied FLARE headers system-wide" 2>>"$LOG" \
      && log "PHASE 3: Commit created (local only)."
  fi
else
  log "PHASE 3: ERROR not a git repository; skipping git add/commit."
fi

log "DONE."
echo "OK"
