import os
import re
import argparse
import datetime
import glob
from difflib import unified_diff

# Canonical fields in PRD 16 order
CANONICAL_FIELDS = [
    "header_format_version",
    "file_path_from_root",
    "web_path",
    "status",
    "when_updated",
    "trust_tier",
    "questions_toon",
    "memory_toon",
    "atoms_toon",
    "transcript_jsonl",
    "artifact_type",
    "artifact_kind",
    "channel_key",
    "federation_node_id",
    "thread_id",
    "content_id",
    "content_parent_id",
    "default_collection_id",
    "lupopedia.schema",
    "prd_cluster",
    "title",
    "summary"
]

LEGACY_FIELDS = ["pk_id", "pk_slug", "parent_pk_id", "content_slug"]

EXCLUDE_DIRS = [
    "docs/prd/prd_backup_1777054781",
    "docs/prd/archive",
    "docs/prd/decisions"
]

def get_utc_now():
    return datetime.datetime.now(datetime.timezone.utc).strftime("%Y%m%d%H%M%S")

def extract_own_token(filename):
    # Pattern: NUMBER_LETTER-ROMAN or NUMBER_LETTER
    # Example: 00_A-i, 02_B-i, 16_A, 50_A-ii
    match = re.match(r"^(\d+)_([A-F](?:-[ivxldcm]+)?)(?:_|$)", filename)
    if match:
        return f"{match.group(1)}_{match.group(2)}"
    return None

def normalize_cluster(cluster_str, own_token, safe_mode=False):
    if not cluster_str:
        return own_token, False
    
    parts = cluster_str.split("_")
    
    # Check if cluster is basically a single token or already valid-ish
    if len(parts) < 2:
        return own_token if not cluster_str else f"{own_token}_{cluster_str}" if own_token not in cluster_str else cluster_str, True

    new_parts = []
    warning = False
    
    # Process in pairs
    for i in range(0, len(parts) - 1, 2):
        num = parts[i]
        let = parts[i+1]
        
        # Basic validation: num should be digits, let should start with A-F
        if not re.match(r"^\d+$", num) or not re.match(r"^[A-F]", let):
            # Ambiguous or invalid pair structure
            if safe_mode:
                new_parts.append(num)
                new_parts.append(let)
                warning = True
                continue
            else:
                # If not safe mode, we might have been too aggressive before.
                # But here we should probably just keep what we have and warn.
                new_parts.append(num)
                new_parts.append(let)
                warning = True
                continue

        # Check if it already has a Roman numeral
        if "-" in let:
            new_parts.append(num)
            new_parts.append(let)
        else:
            # Upgrade to -i by default if it matches own_token's base but lacks roman
            own_base = own_token.split("-")[0] if "-" in own_token else own_token
            current_base = f"{num}_{let}"
            
            if current_base == own_base:
                new_parts.append(num)
                # Use the Roman numeral from the filename if available
                if "-" in own_token:
                    new_parts.append(f"{let}-{own_token.split('-')[1]}")
                else:
                    new_parts.append(f"{let}-i")
            else:
                new_parts.append(num)
                new_parts.append(f"{let}-i")

    # Handle trailing odd part if any
    if len(parts) % 2 != 0:
        new_parts.append(parts[-1])
        warning = True

    # Ensure own_token is present in the cluster
    # We check if the pair (num, let-roman) matches own_token
    found_own = False
    for i in range(0, len(new_parts) - 1, 2):
        if f"{new_parts[i]}_{new_parts[i+1]}" == own_token:
            found_own = True
            break
    
    if not found_own:
        # If not found, prepend it as it defines the file's primary identity
        final_cluster = f"{own_token}_" + "_".join(new_parts)
    else:
        final_cluster = "_".join(new_parts)

    return final_cluster, warning

def parse_header(content):
    if not content.startswith("---"):
        return None, None, content
    
    parts = content.split("---", 2)
    if len(parts) < 3:
        return None, None, content
    
    header_block = parts[1]
    body = parts[2]
    
    # Manual parse because we want to be zero-dependency and preserve some formatting if possible
    # but we'll rebuild it from scratch anyway.
    headers = {}
    current_key = None
    
    # Look for lupopedia.headers:
    if "lupopedia.headers:" not in header_block:
        return None, None, content
    
    lines = header_block.split("\n")
    for line in lines:
        line = line.strip()
        if not line or line == "lupopedia.headers:":
            continue
        
        match = re.match(r"^([\w\.]+):\s*(.*)$", line)
        if match:
            key = match.group(1)
            val = match.group(2).strip()
            # Strip quotes
            if (val.startswith('"') and val.endswith('"')) or (val.startswith("'") and val.endswith("'")):
                val = val[1:-1]
            if val.lower() == "null":
                val = None
            headers[key] = val
            
    return headers, header_block, body

def format_value(val, field_name=None):
    if val is None or str(val).lower() == "null":
        return "null"
    
    # Special case for federation_node_id - should be a number (no quotes)
    if field_name == "federation_node_id":
        try:
            return str(int(val))
        except (ValueError, TypeError):
            return "0"

    # If it's a string, we usually want to quote it for safety in YAML, 
    # but Lupopedia style varies. PRD 16 template quotes strings.
    if isinstance(val, str):
        # If it's already quoted, don't double quote
        if (val.startswith('"') and val.endswith('"')) or (val.startswith("'") and val.endswith("'")):
            return val
        return f'"{val}"'
    
    return str(val)

def build_header(headers, own_token, file_rel_path, timestamp, safe_mode=False):
    new_headers = []
    new_headers.append("lupopedia.headers:")
    
    # 1. header_format_version
    headers["header_format_version"] = "4.1.6"
    # 2. file_path_from_root
    headers["file_path_from_root"] = file_rel_path.replace("\\", "/")
    # 3. web_path
    headers["web_path"] = f"https://www.lupopedia.com/lupopedia/{headers['file_path_from_root']}"
    # 4. status (keep or default)
    if "status" not in headers or not headers["status"]:
        headers["status"] = "active"
    # 5. when_updated
    headers["when_updated"] = timestamp
    
    # prd_cluster logic
    old_cluster = headers.get("prd_cluster", "")
    new_cluster, warning = normalize_cluster(old_cluster, own_token, safe_mode)
    headers["prd_cluster"] = new_cluster
    
    for field in CANONICAL_FIELDS:
        val = headers.get(field)
        
        # artifact_type/kind defaults
        if field == "artifact_type" and not val:
            val = "prd"
        if field == "artifact_kind" and not val:
            val = "specification"
        if field == "lupopedia.schema" and not val:
            val = "prd"
        if field == "federation_node_id" and val is None:
            val = "0"
            
        formatted_val = format_value(val, field)
        new_headers.append(f"  {field}: {formatted_val}")
        
    return "\n".join(new_headers) + "\n", warning

def process_file(file_path, args, timestamp):
    rel_path = os.path.relpath(file_path, start=os.getcwd()).replace("\\", "/")
    
    # Skip excluded directories
    for excl in EXCLUDE_DIRS:
        if rel_path.startswith(excl):
            return "skipped (excluded dir)", None
            
    filename = os.path.basename(file_path)
    own_token = extract_own_token(filename)
    if not own_token:
        return f"skipped (could not extract own_token from {filename})", None
        
    try:
        with open(file_path, "r", encoding="ascii", errors="replace") as f:
            content = f.read()
    except Exception as e:
        return f"error reading: {e}", None

    headers, old_header_block, body = parse_header(content)
    if not headers:
        return "skipped (no lupopedia.headers found)", None

    new_header_block, warning = build_header(headers, own_token, rel_path, timestamp, args.safe_cluster)
    
    new_content = "---\n" + new_header_block + "---" + body
    
    if new_content == content:
        return "unchanged", None

    diff = ""
    if args.dry_run or args.report:
        old_lines = ("---\n" + old_header_block + "---\n").splitlines(keepends=True)
        new_lines = ("---\n" + new_header_block + "---\n").splitlines(keepends=True)
        diff = "".join(unified_diff(old_lines, new_lines, fromfile=rel_path, tofile=rel_path + " (normalized)"))

    if args.write:
        try:
            with open(file_path, "w", encoding="ascii", errors="replace") as f:
                f.write(new_content)
            return "written", (diff, warning)
        except Exception as e:
            return f"error writing: {e}", (diff, warning)
            
    return "changed (dry-run)", (diff, warning)

def main():
    parser = argparse.ArgumentParser(description="Normalize PRD headers to 4.1.6")
    parser.add_argument("--dry-run", action="store_true", help="Print diffs without writing")
    parser.add_argument("--write", action="store_true", help="Apply changes to files")
    parser.add_argument("--report", action="store_true", help="Print summary only")
    parser.add_argument("--safe-cluster", action="store_true", help="Never drop tokens, only upgrade format")
    args = parser.parse_args()

    if not args.dry_run and not args.write and not args.report:
        parser.print_help()
        return

    timestamp = get_utc_now()
    prd_files = glob.glob("docs/prd/*.md")
    
    results = {
        "written": [],
        "changed (dry-run)": [],
        "unchanged": [],
        "skipped": [],
        "warnings": [],
        "errors": []
    }
    
    diffs = []
    
    for file_path in prd_files:
        status, info = process_file(file_path, args, timestamp)
        
        if status == "written":
            results["written"].append(file_path)
            if info:
                diffs.append(info[0])
                if info[1]:
                    results["warnings"].append(f"{file_path}: Legacy cluster '{info[1]}' could not be safely converted.")
        elif status == "changed (dry-run)":
            results["changed (dry-run)"].append(file_path)
            if info:
                diffs.append(info[0])
                if info[1]:
                    results["warnings"].append(f"{file_path}: Legacy cluster could not be safely converted.")
        elif status == "unchanged":
            results["unchanged"].append(file_path)
        elif status.startswith("skipped"):
            results["skipped"].append(f"{file_path} ({status})")
        elif status.startswith("error"):
            results["errors"].append(f"{file_path} ({status})")

    if not args.report:
        if args.dry_run:
            print("=== DRY RUN SUMMARY ===")
        elif args.write:
            print("=== WRITE SUMMARY ===")
            
        print(f"Total files processed: {len(prd_files)}")
        print(f"Changed: {len(results['written']) + len(results['changed (dry-run)'])}")
        print(f"Unchanged: {len(results['unchanged'])}")
        print(f"Skipped: {len(results['skipped'])}")
        print(f"Errors: {len(results['errors'])}")
        
        if results["warnings"]:
            print("\n=== WARNINGS ===")
            for w in results["warnings"]:
                print(f"WARNING: {w}")

        if diffs:
            print("\n=== SAMPLE DIFFS (Max 3) ===")
            for d in diffs[:3]:
                print(d)
    else:
        print(f"Processed: {len(prd_files)}, Changed: {len(results['written']) + len(results['changed (dry-run)'])}, Unchanged: {len(results['unchanged'])}, Skipped: {len(results['skipped'])}, Errors: {len(results['errors'])}, Warnings: {len(results['warnings'])}")

if __name__ == "__main__":
    main()
