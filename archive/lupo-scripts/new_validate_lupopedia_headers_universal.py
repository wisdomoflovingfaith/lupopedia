#!/usr/bin/env python3
"""
new_validate_lupopedia_headers_universal.py
A modernized validator for Lupopedia headers with --fix support.
Supports .md, .py, .php, .js, .txt files.
"""
import argparse
import os
import sys
import re
from datetime import datetime

VALID_TRUST_TIERS = {'seed', 'canonical', 'staging', 'archive'}
VALID_ARTIFACT_TYPES = {'prd', 'doctrine', 'documentation', 'implementation', 'discussion', 'changelog', 'architecture', 'specification'}

def extract_header_from_markdown(content):
    """Extract YAML header from .md or .txt file."""
    match = re.search(r'^---\s*\nlupopedia\.headers:(.*?)\n---', content, re.DOTALL | re.MULTILINE)
    if not match:
        return None
    return match.group(1)

def extract_header_from_python(content):
    """Extract header from Python # comment grid."""
    match = re.search(r'^#\s*-{10,}\s*\n#\s*lupopedia\.headers:(.*?)\n#\s*-{10,}', content, re.DOTALL | re.MULTILINE)
    if not match:
        return None
    return match.group(1)

def extract_header_from_php(content):
    """Extract header from PHP # comment grid or /** docblock."""
    # Try # grid first
    match = re.search(r'<\?php\s*\n#\s*-{10,}\s*\n#\s*lupopedia\.headers:(.*?)\n#\s*-{10,}', content, re.DOTALL | re.MULTILINE)
    if match:
        return match.group(1)
    # Try /** docblock
    match = re.search(r'/\*\*\s*\n\s*\*\s*lupopedia\.headers:(.*?)\n\s*\*/', content, re.DOTALL | re.MULTILINE)
    if match:
        return match.group(1)
    return None

def extract_header(content, file_ext):
    """Extract header block based on file extension."""
    if file_ext in ('.md', '.txt'):
        return extract_header_from_markdown(content)
    elif file_ext == '.py':
        return extract_header_from_python(content)
    elif file_ext == '.php':
        return extract_header_from_php(content)
    return None

def parse_header_block(block):
    """Parse YAML header block into dict."""
    header = {}
    # Simple key: value parsing (handles quoted values)
    for line in block.split('\n'):
        line = line.strip()
        if not line or line.startswith('#'):
            continue
        match = re.match(r'^([a-z_]+):\s*(.*?)$', line)
        if match:
            key, value = match.groups()
            value = value.strip().strip('"').strip("'")
            if value.lower() == 'null':
                value = None
            header[key] = value
    return header

def validate_header_value(key, value, file_path):
    """Validate specific header field values (PRD 16 v4.1.0 primary; legacy names WARN only)."""
    errors = []
    warnings = []

    if key == 'header_format_version':
        # 4.1.x is current; 4.0.99 accepted as legacy with warning
        if re.match(r'^4\.1\.\d+$', str(value or '')):
            pass  # current
        elif re.match(r'^4\.0\.\d+$', str(value or '')):
            warnings.append(f'{key} is {value} (legacy); canonical target is 4.1.0 (HDR_FORMAT_VERSION_LEGACY)')
        else:
            errors.append(f'{key} must be 4.1.x (got {value})')

    elif key == 'when_updated':
        if not re.match(r'^\d{14}$', str(value)):
            errors.append(f'{key} must be 14-digit UTC (YYYYMMDDHHIISS), got {value}')
    # last_modified_utc: legacy only; renamed to questions_toon in PRD 16 v4.0.99
    elif key == 'last_modified_utc':
        if not re.match(r'^\d{14}$', str(value)):
            errors.append(f'{key} must be 14-digit UTC (YYYYMMDDHHIISS), got {value}')

    elif key == 'questions_toon':
        if value is not None and value != 'null' and not str(value).endswith('.questions.toon'):
            errors.append(f'questions_toon must be null or end with .questions.toon (HDR_QUESTIONS_TOON_SUFFIX)')

    elif key == 'trust_tier':
        if value not in VALID_TRUST_TIERS:
            errors.append(f'trust_tier must be one of {VALID_TRUST_TIERS}, got {value}')

    elif key == 'artifact_type':
        if value not in VALID_ARTIFACT_TYPES:
            errors.append(f'artifact_type must be one of {VALID_ARTIFACT_TYPES}, got {value}')

    elif key == 'web_path':
        if value and value.startswith('http://'):
            errors.append(f'web_path uses http:// (should be https://): {value}')

    elif key == 'memory_toon':
        # v4.1.0 primary field
        if value and not str(value).endswith('.toon'):
            errors.append(f'memory_toon must end with .toon, got {value} (HDR_MEMORY_KEY_SUFFIX)')
        if value and 'canonical' in str(value):
            match = re.search(r'/canonical/(\d{4})/', str(value))
            if match:
                year = int(match.group(1))
                expected = datetime.now().year - 1000
                if year != expected:
                    errors.append(
                        f'canonical memory_toon year {year} should be {expected} '
                        f'(calendar year - 1000) (HDR_MEMORY_YEAR_OFFSET)'
                    )

    elif key == 'memory_key':
        # legacy field — validation still runs on the value, but caller emits WARN for the name
        if value and not str(value).endswith('.toon'):
            errors.append(f'memory_key must end with .toon, got {value} (HDR_MEMORY_KEY_SUFFIX)')

    elif key == 'atoms_toon':
        # v4.1.0 primary field (was module)
        if value == '':
            errors.append('atoms_toon must be null or non-empty string, not empty string (HDR_ATOMS_TOON_SUFFIX)')

    elif key == 'module':
        # legacy field — accept but caller emits WARN for the name
        if value == '':
            errors.append('module (legacy atoms_toon) must be null or non-empty, not empty string')

    elif key == 'content_id' or key == 'pk_id':
        if value is not None and not str(value).isdigit():
            errors.append(f'{key} must be null or numeric, got {value}')

    return errors  # warnings are emitted by validate_header to keep signature stable

def validate_header(header, file_path):
    """
    Validate header against PRD 16 v4.1.0 canonical schema (22 keys).

    Primary field names are the 4.1.0 names.  Legacy names (memory_key → memory_toon,
    dialog_transcript → transcript_jsonl, module → atoms_toon) are accepted as read-only
    fallback inputs and emit warnings; they do NOT satisfy the required-key check for the
    canonical name without a warning.
    """
    # Canonical 4.1.0 required keys (primary names only)
    required_keys = [
        'header_format_version', 'file_path_from_root', 'web_path', 'status', 'when_updated',
        'trust_tier',
        'questions_toon',      # was last_modified_utc (renamed PRD 16 v4.0.99 §4.2 field 7)
        'memory_toon',         # was memory_key (renamed PRD 16 v4.1.0 §4.2 field 8)
        'atoms_toon',          # was module (renamed PRD 16 v4.0.99 §4.2 field 9)
        'transcript_jsonl',    # was dialog_transcript (renamed PRD 16 v4.1.0 §4.2 field 10)
        'artifact_type', 'artifact_kind', 'channel_key', 'federation_node_id',
        'thread_id', 'content_id', 'pk_id', 'pk_slug',
        'parent_pk_id', 'lupopedia.schema', 'title', 'summary',
    ]
    # Legacy → canonical map for fallback acceptance
    _legacy = {
        'memory_key': 'memory_toon',
        'dialog_transcript': 'transcript_jsonl',
        'module': 'atoms_toon',
    }

    errors = []
    warnings = []

    # Emit warnings for legacy field names present in the header
    for legacy_name, canonical_name in _legacy.items():
        if legacy_name in header and canonical_name not in header:
            warnings.append(
                f'[WARN] {file_path}: deprecated field {legacy_name!r} found '
                f'(HDR_{legacy_name.upper()}_RENAMED): rename to {canonical_name!r} '
                f'per PRD 16 v4.1.0 §4.2'
            )

    # Check for missing canonical keys; accept legacy fallback with warning (already emitted above)
    for key in required_keys:
        if key not in header:
            legacy_present = any(
                leg for leg, can in _legacy.items() if can == key and leg in header
            )
            if legacy_present:
                # Legacy name satisfies presence; warning already emitted above
                pass
            else:
                errors.append(f'Missing required key: {key}')

    # Validate field values (primary names take precedence; legacy names also validated)
    for key, value in header.items():
        value_errors = validate_header_value(key, value, file_path)
        errors.extend(value_errors)

    # Print warnings immediately (keep return type as list[str] for errors only)
    for w in warnings:
        print(w)

    # Validate file_path_from_root matches actual file
    actual_path = file_path.replace('\\', '/')
    if 'file_path_from_root' in header:
        expected = header['file_path_from_root']
        if expected and actual_path != expected and not actual_path.endswith(expected):
            errors.append(f'file_path_from_root "{expected}" does not match actual path "{actual_path}"')

    return errors

def fix_header(file_path):
    """Call the official header adder script with --force."""
    import subprocess
    script_dir = os.path.dirname(os.path.abspath(__file__))
    script_path = os.path.join(script_dir, 'add_lupopedia_header_to_file.py')
    
    if not os.path.exists(script_path):
        return False, f"Header adder script not found: {script_path}"
    
    result = subprocess.run([
        sys.executable, script_path, file_path, '--force'
    ], capture_output=True, text=True)
    
    if result.returncode == 0:
        return True, "Header rewritten successfully"
    else:
        return False, result.stderr

def main():
    parser = argparse.ArgumentParser(description="Validate Lupopedia headers (PRD 16 v4.1.0)")
    parser.add_argument("file_path", help="File to validate (.md, .py, .php, .js, .txt)")
    parser.add_argument("--fix", action="store_true", help="Attempt to fix header if invalid")
    parser.add_argument("--quiet", action="store_true", help="Suppress non-error output")
    args = parser.parse_args()

    if not os.path.exists(args.file_path):
        print(f"[ERROR] File not found: {args.file_path}")
        sys.exit(1)

    file_ext = os.path.splitext(args.file_path)[1].lower()
    if file_ext not in ('.md', '.py', '.php', '.js', '.txt'):
        print(f"[ERROR] Unsupported file type: {file_ext}. Use .md, .py, .php, .js, or .txt")
        sys.exit(1)

    with open(args.file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    header_block = extract_header(content, file_ext)
    if not header_block:
        print(f"[ERROR] {args.file_path}: Could not extract lupopedia.headers block")
        if args.fix:
            print("Attempting to fix header...")
            success, msg = fix_header(args.file_path)
            if success:
                print(f"[FIXED] {msg}")
                sys.exit(0)
            else:
                print(f"[FAIL] {msg}")
                sys.exit(2)
        sys.exit(1)

    header = parse_header_block(header_block)
    errors = validate_header(header, args.file_path)

    if errors:
        print(f"[ERROR] {args.file_path}:")
        for error in errors:
            print(f"  - {error}")
        
        if args.fix:
            print("\nAttempting to fix header...")
            success, msg = fix_header(args.file_path)
            if success:
                print(f"[FIXED] {msg}")
                # Re-validate after fix
                with open(args.file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                header_block = extract_header(content, file_ext)
                if header_block:
                    header = parse_header_block(header_block)
                    errors = validate_header(header, args.file_path)
                    if not errors:
                        print("[PASS] Header is now valid.")
                        sys.exit(0)
                    else:
                        print("[WARN] Header still has issues after fix:")
                        for error in errors:
                            print(f"  - {error}")
                        sys.exit(1)
            else:
                print(f"[FAIL] {msg}")
                sys.exit(2)
        else:
            sys.exit(1)
    
    if not args.quiet:
        print(f"[PASS] {args.file_path}: Header is valid (22 keys, correct values)")
    sys.exit(0)

if __name__ == "__main__":
    main()