import argparse
import glob
import json
import os
import re

try:
    import yaml
except ImportError:
    yaml = None


SEED_PRDS = {0, 41}
CHANNEL_OVERRIDES = {16: "headers", 38: "memory"}


def clean_slug_from_filename(stem):
    # Keep PRD number prefix; 02_channels -> 02-channels
    return stem.lower().replace("_", "-")


def extract_prd_id(filename):
    match = re.match(r"^(\d+)_", filename)
    return int(match.group(1)) if match else None


def parse_front_matter(content):
    match = re.match(r"^---\n(.*?)\n---\n", content, re.S)
    if not match:
        return None, content
    block = match.group(1)
    body = content[match.end() :]
    if yaml is None:
        return None, body
    try:
        parsed = yaml.safe_load(block)
        return parsed if isinstance(parsed, dict) else None, body
    except Exception:
        return None, body


def has_v4098_header(parsed):
    if not isinstance(parsed, dict):
        return False
    headers = parsed.get("lupopedia.headers")
    if not isinstance(headers, dict):
        return False
    return str(headers.get("header_format_version", "")).strip() == "4.0.98"


def choose_value(parsed, key, fallback):
    if isinstance(parsed, dict):
        headers = parsed.get("lupopedia.headers")
        if isinstance(headers, dict) and key in headers:
            value = headers.get(key)
            if value is not None and str(value).strip() != "":
                return str(value)
    return fallback


def build_header(path, prd_id, slug, title, timestamp, parsed):
    rel_path = path.replace("\\", "/")
    trust_tier_default = "seed" if prd_id in SEED_PRDS else "canonical"
    trust_tier = choose_value(parsed, "trust_tier", trust_tier_default)
    channel_key_default = CHANNEL_OVERRIDES.get(prd_id, "development")
    channel_key = choose_value(parsed, "channel_key", channel_key_default)

    if trust_tier == "seed":
        memory_toon_default = f"memory/development/seed/1026/04/{slug}.toon"
    else:
        memory_toon_default = f"memory/development/canonical/1026/04/{slug}.toon"
    # Accept legacy memory_key from existing headers; prefer memory_toon
    memory_toon = choose_value(parsed, "memory_toon", choose_value(parsed, "memory_key", memory_toon_default))
    # Accept legacy dialog_transcript from existing headers; prefer transcript_jsonl
    transcript_jsonl = choose_value(
        parsed, "transcript_jsonl", choose_value(parsed, "dialog_transcript", f"0/development/prd_files/{slug}")
    )

    lines = [
        "lupopedia.headers:",
        '  header_format_version: "4.1.0"',
        f'  file_path_from_root: "{rel_path}"',
        f'  web_path: "http://www.lupopedia.com/lupopedia/{rel_path}"',
        '  status: "active"',
        f'  when_updated: "{timestamp}"',
        f'  trust_tier: "{trust_tier}"',
        "  questions_toon: null",  # was last_modified_utc (renamed PRD 16 v4.0.99)
        f'  memory_toon: "{memory_toon}"',
        "  atoms_toon: null",  # was module (renamed PRD 16 v4.0.99)
        f'  transcript_jsonl: "{transcript_jsonl}"',
        "  artifact_type: prd",
        "  artifact_kind: specification",
        f'  channel_key: "{channel_key}"',
        "  federation_node_id: 0",
        '  thread_id: ""',
        "  content_id: null",
        f"  pk_id: {prd_id}",
        f'  pk_slug: "{slug}"',
        '  parent_pk_id: ""',
        "  lupopedia.schema: prd",
        f'  title: "{title}"',
        f'  summary: "PRD {prd_id}: {slug}"',
    ]
    return "---\n" + "\n".join(lines) + "\n---\n"


def extract_purpose_from_body(body, prd_id, slug):
    lines = body.split("\n")
    in_heading = True
    for line in lines:
        if in_heading:
            if line.startswith("#"):
                continue
            in_heading = False
        line = line.strip()
        if line and not line.startswith("#"):
            if len(line) > 200:
                return line[:197] + "..."
            return line
    return f"PRD {prd_id}: {slug.replace('-', ' ').title()}"


def load_existing_sidecar_fields(sidecar_path):
    if not os.path.exists(sidecar_path):
        return {}
    try:
        with open(sidecar_path, "r", encoding="utf-8") as handle:
            existing = json.load(handle)
        if isinstance(existing, dict):
            return existing
    except Exception:
        pass
    return {}


def write_sidecar(path, prd_id, slug, timestamp, body):
    rel_path = path.replace("\\", "/")
    trust_tier = "seed" if prd_id in SEED_PRDS else "canonical"
    channel_key = CHANNEL_OVERRIDES.get(prd_id, "development")
    year = timestamp[:4]
    month = timestamp[4:6]
    stem = os.path.splitext(os.path.basename(path))[0]
    sidecar_path = f"memory/headers/prd/{year}/{month}/{stem}.metadata.json"
    existing = load_existing_sidecar_fields(sidecar_path)
    existing_edges = existing.get("edges", []) if isinstance(existing.get("edges", []), list) else []
    purpose = extract_purpose_from_body(body, prd_id, slug)
    if isinstance(existing.get("purpose"), str) and existing.get("purpose").strip():
        purpose = existing.get("purpose").strip()
    # Accept legacy dialog_transcript key from existing sidecar; prefer transcript_jsonl
    transcript_jsonl = f"0/development/prd_files/{slug}"
    if isinstance(existing.get("transcript_jsonl"), str) and existing.get("transcript_jsonl").strip():
        transcript_jsonl = existing.get("transcript_jsonl").strip()
    elif isinstance(existing.get("dialog_transcript"), str) and existing.get("dialog_transcript").strip():
        transcript_jsonl = existing.get("dialog_transcript").strip()
    payload = {
        "id": f"hdr-{slug}",
        "type": "header_metadata",
        "file_path_from_root": rel_path,
        "channel_key": channel_key,
        "trust_tier": trust_tier,
        "purpose": purpose,
        "status": "active",
        "tags": ["tag-prd", f"tag-{slug}"],
        "author": {"type": "actor", "id": 102, "name": "CURSOR"},
        "delegation_chain": "cursor:root",
        "edges": existing_edges,
        "footer": {
            "last_verified": timestamp,
            "verified_by": {"type": "actor", "id": 102, "name": "CURSOR"},
        },
        "init": [],
        "transcript_jsonl": transcript_jsonl,
    }
    os.makedirs(os.path.dirname(sidecar_path), exist_ok=True)
    with open(sidecar_path, "w", encoding="utf-8") as handle:
        json.dump(payload, handle, indent=2)
        handle.write("\n")
    return sidecar_path


def rewrite_file(path, timestamp, force, write_sidecars, dry_run):
    with open(path, "r", encoding="utf-8", errors="replace") as handle:
        content = handle.read()

    parsed, body = parse_front_matter(content)
    if has_v4098_header(parsed) and not force:
        print(f"[SKIP] {path} already has v4.0.98 header")
        return False

    filename = os.path.basename(path)
    prd_id = extract_prd_id(filename)
    if prd_id is None:
        print(f"[WARN] {path} does not match PRD filename pattern")
        return False

    heading = re.search(r"^#\s+(.+)$", body, re.M)
    title = heading.group(1).strip() if heading else os.path.splitext(filename)[0]
    title = title.replace('"', '\\"')
    slug = clean_slug_from_filename(os.path.splitext(filename)[0])

    header = build_header(path, prd_id, slug, title, timestamp, parsed)
    if dry_run:
        print(f"[DRY RUN] Would rewrite header: {path}")
        if write_sidecars:
            print(f"[DRY RUN] Would write sidecar for: {path}")
        return True

    with open(path, "w", encoding="utf-8") as handle:
        handle.write(header + body.lstrip("\n"))
    print(f"[OK] Rewrote header: {path}")

    if write_sidecars:
        sidecar_path = write_sidecar(path, prd_id, slug, timestamp, body)
        print(f"[OK] Wrote sidecar: {sidecar_path}")
    return True


def main():
    parser = argparse.ArgumentParser(
        description="Normalize PRD headers to v4.0.98 with optional sidecar generation."
    )
    parser.add_argument("timestamp", help="UTC timestamp YYYYMMDDHHIISS")
    parser.add_argument(
        "--force",
        action="store_true",
        help="Rewrite even if header_format_version is already 4.0.98",
    )
    parser.add_argument(
        "--write-sidecars",
        action="store_true",
        help="Also generate/update PRD sidecar metadata JSON files",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Preview header and sidecar updates without writing files",
    )
    args = parser.parse_args()

    if yaml is None:
        print("[ERROR] PyYAML is required. Run: pip install pyyaml")
        return 1

    if not re.match(r"^\d{14}$", args.timestamp):
        print("[ERROR] Timestamp must be 14 digits: YYYYMMDDHHIISS")
        return 1

    files = sorted(glob.glob("docs/prd/[0-9][0-9]_*.md"))
    print(f"[INFO] Found {len(files)} PRD files")
    rewritten = 0
    for path in files:
        if rewrite_file(path, args.timestamp, args.force, args.write_sidecars, args.dry_run):
            rewritten += 1

    if args.dry_run:
        print(f"[OK] Processed {len(files)} PRD files; would rewrite {rewritten}")
    else:
        print(f"[OK] Processed {len(files)} PRD files; rewrote {rewritten}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
