#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/flip_header_audit.py"
#   last_modified_utc: "20260324175617"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
FLIP Header Audit: Enhanced version with metadata extraction, validation, and offline navigation.

Features:
- Add missing FLIP headers to doctrine .md files
- Extract and validate FLIP metadata
- Generate offline navigation JSON for VSX extension
- Comprehensive validation reports
- SQL generation for seed_lupopedia.sql

Run from repo root: python scripts/flip_header_audit.py
"""
import os
import re
import json
from pathlib import Path
from datetime import datetime
from typing import Dict, List, Optional, Tuple

SIGNATURE = "wolfie.headers: explicit architecture with structured clarity for every file."

def path_to_web(path):
    """Derive web block (canonical, slug, base_path) from file_path_from_root. 4.0.17 Web Path Header Extension."""
    p = path.replace("\\", "/").strip()
    if p.startswith("docs/"):
        p = p[5:]
    if p.endswith(".md"):
        p = p[:-3]
    if not p:
        return "/", "", "/"
    canonical = "/" + p
    parts = p.split("/")
    slug = parts[-1] if parts else p
    base_path = "/" + "/".join(parts[:-1]) if len(parts) > 1 else "/"
    return canonical, slug, base_path

FLIP_HEADER_TEMPLATE = """---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: {path}
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "{timestamp}"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: {web_canonical}
  aliases:
    - /docs/{web_slug}
    - /qa/{web_slug_plus}
  slug: {web_slug}
  slug_encoding: underscore
  base_path: {web_base_path}
  url_pattern: "/{{base}}/{{slug}}"
---

"""

def has_flip_header(content):
    return SIGNATURE in content

def extract_flip_metadata(content: str, filepath: str) -> Optional[Dict]:
    """
    Extract FLIP header metadata from file content.
    Returns dict with parsed fields or None if no valid header found.
    """
    lines = content.split('\n')
    if not lines[0].strip().startswith('---'):
        return None
    
    # Find end of header block
    end_idx = -1
    for i in range(1, min(50, len(lines))):
        if lines[i].strip() == '---':
            end_idx = i
            break
    
    if end_idx == -1:
        return None
    
    # Parse YAML-like key-value pairs
    metadata = {
        'file_path_from_root': filepath.replace('\\', '/'),
        'has_valid_header': False,
        'errors': [],
    }
    
    for line in lines[1:end_idx]:
        line = line.strip()
        if not line or line.startswith('#'):
            continue
        
        if ':' in line:
            key, value = line.split(':', 1)
            key = key.strip()
            value = value.strip().strip('"\'')
            
            # Parse known fields (Canonical 4.0.27)
            if key in ('X-Lupo-Version', 'file.last_modified_system_version'):
                metadata['version'] = value
            elif key in ('X-Lupo-UTC-Timestamp', 'file.last_modified_utc'):
                metadata['modified_utc'] = value
                # Validate UTC timestamp format (YYYYMMDDHHIISS)
                if not re.match(r'^\d{14}$', value):
                    metadata['errors'].append(f'Invalid UTC timestamp: {value}')
            elif key in ('X-Lupo-Channel', 'channel_id'):
                try:
                    metadata['channel_id'] = int(value.split()[0])  # Handle comments
                except ValueError:
                    metadata['errors'].append(f'Invalid channel_id: {value}')
            elif key in ('X-Lupo-Actor-ID', 'actor_id'):
                metadata['actor_id'] = value
            elif key == 'X-Lupo-Actor-Identity':
                metadata['actor_identity'] = value
            elif key == 'status':
                metadata['status'] = value
            elif key == 'thread_id':
                metadata['thread_id'] = value
            elif key == 'tags':
                metadata['tags'] = value
            elif key == 'mood_rgb':
                metadata['mood_rgb'] = value
            elif key.startswith('X-LUPO-') and '.' in key:
                # Database Mapping Layer: X-LUPO-{table}.{column}
                if 'database_mappings' not in metadata:
                    metadata['database_mappings'] = {}
                metadata['database_mappings'][key] = value
    
    # Required fields validation (Core Doctrine)
    required = ['version', 'modified_utc']
    for field in required:
        if field not in metadata:
            metadata['errors'].append(f'Missing required field: {field}')
    
    # Routing Validation (4.0.30 Orphan Prevention)
    if 'channel_id' not in metadata:
        metadata['errors'].append('Missing routing field: channel_id (or X-Lupo-Channel)')
    
    if 'actor_id' not in metadata and 'actor_identity' not in metadata:
        metadata['errors'].append('Missing attribution: actor_id or actor_identity required for routing')
    
    # Path Consistency Check
    if 'X-Lupo-File-Path' in metadata:
        normalized_meta_path = metadata['X-Lupo-File-Path'].replace('\\', '/')
        if normalized_meta_path != metadata['file_path_from_root']:
             metadata['errors'].append(f"Path mismatch: Header='{normalized_meta_path}' vs Actual='{metadata['file_path_from_root']}'")

    metadata['has_valid_header'] = len(metadata['errors']) == 0
    return metadata

def fix_flip_header(filepath, content, metadata):
    """
    Automated fix for existing headers missing required routing fields.
    """
    if not metadata:
        return content
    
    lines = content.split('\n')
    end_idx = -1
    for i in range(1, min(50, len(lines))):
        if lines[i].strip() == '---':
            end_idx = i
            break
    
    if end_idx == -1:
        return content
    
    header_lines = lines[1:end_idx]
    modified = False
    
    # Check for missing routing fields and inject them
    if 'channel_id' not in metadata:
        header_lines.append('X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)')
        modified = True
    
    if 'actor_id' not in metadata and 'actor_identity' not in metadata:
        header_lines.append('X-Lupo-Actor-ID: 2035')
        header_lines.append('X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"')
        modified = True
    
    if 'X-Lupo-File-Path' not in content:
        header_lines.append(f"X-Lupo-File-Path: {filepath}")
        modified = True

    if modified:
        new_header = "---\n" + "\n".join(header_lines) + "\n---"
        return new_header + "\n" + "\n".join(lines[end_idx+1:])
    
    return content

def add_flip_header(filepath, content):
    if content.startswith("---"):
        return content
    path = filepath.replace("\\", "/")
    canonical, slug, base_path = path_to_web(path)
    slug_plus = slug.replace("_", "+")
    new_timestamp = datetime.utcnow().strftime('%Y%m%d%H%M%S')
    header = FLIP_HEADER_TEMPLATE.format(
        path=path,
        timestamp=new_timestamp,
        web_canonical=canonical,
        web_slug=slug,
        web_slug_plus=slug_plus,
        web_base_path=base_path,
    )
    return header + content

def generate_offline_navigation(metadata_list: List[Dict], output_path: Path) -> None:
    """
    Generate JSON navigation file for VSX extension offline mode.
    """
    navigation = {
        'generated_at': datetime.utcnow().strftime('%Y%m%d%H%M%S'),
        'total_files': len(metadata_list),
        'by_status': {},
        'by_channel': {},
        'by_thread': {},
        'files': metadata_list,
    }
    
    # Group by status
    for meta in metadata_list:
        status = meta.get('status', 'Unknown')
        if status not in navigation['by_status']:
            navigation['by_status'][status] = []
        navigation['by_status'][status].append(meta['file_path_from_root'])
    
    # Group by channel
    for meta in metadata_list:
        if 'channel_id' in meta:
            ch = str(meta['channel_id'])
            if ch not in navigation['by_channel']:
                navigation['by_channel'][ch] = []
            navigation['by_channel'][ch].append(meta['file_path_from_root'])
    
    # Group by thread
    for meta in metadata_list:
        if 'thread_id' in meta:
            th = meta['thread_id']
            if th not in navigation['by_thread']:
                navigation['by_thread'][th] = []
            navigation['by_thread'][th].append(meta['file_path_from_root'])
    
    output_path.parent.mkdir(parents=True, exist_ok=True)
    with open(output_path, 'w', encoding='utf-8') as f:
        json.dump(navigation, f, indent=2)
    
    print(f"\n✓ Offline navigation file generated: {output_path}")

def generate_validation_report(metadata_list: List[Dict], output_path: Path) -> None:
    """
    Generate comprehensive validation report.
    """
    valid = [m for m in metadata_list if m.get('has_valid_header', False)]
    invalid = [m for m in metadata_list if not m.get('has_valid_header', False)]
    
    report_lines = [
        "# FLIP Header Validation Report",
        f"Generated: {datetime.utcnow().strftime('%Y-%m-%d %H:%M:%S UTC')}",
        "",
        "## Summary",
        f"- Total files scanned: {len(metadata_list)}",
        f"- Valid FLIP headers: {len(valid)} ({len(valid)/len(metadata_list)*100:.1f}%)",
        f"- Invalid/Missing headers: {len(invalid)} ({len(invalid)/len(metadata_list)*100:.1f}%)",
        "",
    ]
    
    if invalid:
        report_lines.extend([
            "## Files with Issues",
            "",
        ])
        for meta in invalid:
            report_lines.append(f"### {meta['file_path_from_root']}")
            if meta.get('errors'):
                for err in meta['errors']:
                    report_lines.append(f"- ⚠️ {err}")
            else:
                report_lines.append("- ⚠️ No FLIP header found")
            report_lines.append("")
    
    # Statistics by status
    status_counts = {}
    for meta in valid:
        status = meta.get('status', 'Unknown')
        status_counts[status] = status_counts.get(status, 0) + 1
    
    report_lines.extend([
        "## Statistics by Status",
        "",
    ])
    for status, count in sorted(status_counts.items()):
        report_lines.append(f"- {status}: {count}")
    
    # Statistics by channel
    channel_counts = {}
    for meta in valid:
        if 'channel_id' in meta:
            ch = meta['channel_id']
            channel_counts[ch] = channel_counts.get(ch, 0) + 1
    
    if channel_counts:
        report_lines.extend([
            "",
            "## Statistics by Channel",
            "",
        ])
        for ch, count in sorted(channel_counts.items()):
            report_lines.append(f"- Channel {ch}: {count}")
    
    output_path.parent.mkdir(parents=True, exist_ok=True)
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write('\n'.join(report_lines))
    
    print(f"✓ Validation report generated: {output_path}")

def main():
    base = Path(__file__).resolve().parent.parent
    scan_dirs = [
        base / "docs" / "doctrine",
        base / "docs" / "api",
        base / "docs" / "specs",
    ]
    all_md = []
    for d in scan_dirs:
        if d.exists():
            all_md.extend(d.rglob("*.md"))

    missing = []
    has_header = []
    all_metadata = []
    
    print("\n🔍 Scanning FLIP headers...\n")
    
    for p in sorted(all_md):
        rel = str(p.relative_to(base)).replace("\\", "/")
        try:
            content = p.read_text(encoding="utf-8")
        except Exception as e:
            print(f"⚠️ Failed to read {rel}: {e}")
            continue
        
        if has_flip_header(content):
            has_header.append(rel)
            # Extract metadata
            metadata = extract_flip_metadata(content, rel)
            if metadata:
                all_metadata.append(metadata)
        else:
            missing.append(rel)

    print("=" * 60)
    print("Total .md files scanned:", len(all_md))
    print("With valid FLIP header:", len(has_header))
    print("Missing FLIP header:", len(missing))
    print("=" * 60)
    
    if missing:
        print("\n📋 Files missing FLIP header:")
        for m in missing:
            print("  -", m)

    if missing:
        print(f"\n⚙️ Auto-adding FLIP headers to {len(missing)} files...")
        for rel in missing:
            fp = base / rel.replace("/", os.sep)
            try:
                content = fp.read_text(encoding="utf-8")
                new_content = add_flip_header(rel, content)
                fp.write_text(new_content, encoding="utf-8")
                print(f"  ✓ Added: {rel}")
                # Re-parse to include in metadata list
                meta = extract_flip_metadata(new_content, rel)
                if meta:
                    all_metadata.append(meta)
            except Exception as e:
                print(f"  ✗ Failed to add header to {rel}: {e}")
    
    # Auto-fix existing headers
    fixed_count = 0
    for meta in all_metadata:
        if not meta.get('has_valid_header', False):
            rel = meta['file_path_from_root']
            fp = base / rel.replace("/", os.sep)
            try:
                content = fp.read_text(encoding="utf-8")
                fixed_content = fix_flip_header(rel, content, meta)
                if fixed_content != content:
                    fp.write_text(fixed_content, encoding="utf-8")
                    fixed_count += 1
                    # Update metadata for report
                    new_meta = extract_flip_metadata(fixed_content, rel)
                    meta.update(new_meta)
            except Exception as e:
                print(f"  ✗ Failed to fix {rel}: {e}")
    
    if fixed_count:
        print(f"⚙️ Auto-fixed routing fields in {fixed_count} existing headers.")
    
    # Generate offline navigation and validation report
    print("\n📊 Generating reports...\n")
    
    nav_path = base / "exports" / "flip_navigation.json"
    generate_offline_navigation(all_metadata, nav_path)
    
    report_path = base / "exports" / "flip_validation_report.md"
    generate_validation_report(all_metadata, report_path)
    
    print("\n" + "=" * 60)
    print("✅ FLIP Header Audit Complete")
    print("=" * 60)
    print(f"\n📁 Outputs:")
    print(f"  - Navigation: {nav_path}")
    print(f"  - Report: {report_path}")
    print(f"\n💡 Next steps:")
    print(f"  1. Review validation report for issues")
    print(f"  2. VSX extension will use flip_navigation.json for offline mode")
    print(f"  3. Commit updated FLIP headers to git")
    print()

if __name__ == "__main__":
    main()