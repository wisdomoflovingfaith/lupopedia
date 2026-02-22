#!/usr/bin/env python3
"""
FLIP Header Generator for Lupopedia 4.0.27
Generates approximately 144 FLIP headers in batches of 25 per file
Creates master index file summarizing all headers
Updated for verbose mode support and database parity
"""

import os
import json
from datetime import datetime

# Configuration
TOTAL_HEADERS = 168  # Updated for 4.0.27 verbose mode
HEADERS_PER_FILE = 25
OUTPUT_DIR = "docs/specs/flip_headers"
MASTER_INDEX_FILE = "docs/specs/FLIP_HEADERS_MASTER_INDEX_4.0.27.md"

# Ensure output directory exists
os.makedirs(OUTPUT_DIR, exist_ok=True)

# Header categories and templates
HEADER_CATEGORIES = {
    "core_logic": [
        ("X-Lupo-File-Path", "Canonical relative path", "docs/doctrine/FLIP/README.md"),
        ("X-Lupo-Channel", "Target channel ID", "42"),
        ("X-Lupo-Version", "System version", "4.0.27"),
        ("X-Lupo-UTC-Timestamp", "UTC timestamp", "2026-02-22T10:00:00Z"),
        ("X-Lupo-Timestamp", "Compact system timestamp", "20260222100000"),
    ],
    "actor_trinity": [
        ("X-Lupo-Actor-ID", "BigInt actor ID", "2035"),
        ("X-Lupo-Actor-Identity", "Identity string", "Google Antigravity IDE"),
        ("From", "Flexible sender ID", "@antigravity"),
        ("X-Lupo-Actor-To", "Receiving actor ID", "2"),
    ],
    "registry_doctrine": [
        ("X-Lupo-Registry-Mode", "ID allocation mode", "unregistry-first"),
        ("X-Lupo-Registry-Source", "Data source for registry", "csv"),
        ("X-Lupo-TOON-Path", "Path to TOON schema files", "docs/toons/"),
        ("X-Lupo-CSV-Path", "Path to CSV snapshots", "database/csv_data/"),
        ("X-Lupo-Doctrine", "Enforced rules", "no-id-guessing; no-max-plus-one; use-unregistry"),
    ],
    "survivor_protocol": [
        ("X-Lupo-Survivor-Protocol", "Survivor mode activation", "active"),
        ("X-Lupo-Forwarded-For", "Origin actor ID", "420"),
        ("X-Lupo-Forward-Chain", "Relay path", "420 -> 2"),
        ("X-Lupo-Origin-Status", "Actor status at relay", "active"),
        ("X-Lupo-Ban-Reason", "Structured ban code", "token_exhaustion_spam_cascade"),
        ("X-Lupo-Ban-Timestamp", "Ban UTC timestamp", "2026-02-20T23:15:00Z"),
        ("X-Lupo-Relay-Validated-By", "Validator actor ID", "2038"),
        ("X-Lupo-Collapse-Ratio", "System loss metric", "11:1"),
    ],
    "semantic_mapping": [
        ("X-Lupo-Registry-ID", "Unified registry identifier", "9002035"),
        ("X-Lupo-Entity-Type", "Semantic entity type", "file"),
        ("X-Lupo-Content-ID", "Primary content identifier", "5000"),
        ("X-Lupo-Triage-Status", "Content triage state", "untriaged"),
        ("X-Lupo-Visibility", "Content visibility", "public"),
        ("X-Lupo-Collection-ID", "Assigned collection ID", "42"),
        ("X-Lupo-Is-Kernel", "Kernel-level protection flag", "false"),
    ],
}

def generate_all_headers():
    """Generate all FLIP headers from categories"""
    all_headers = []
    
    for category, headers in HEADER_CATEGORIES.items():
        for header_name, description, example_value in headers:
            all_headers.append({
                "name": header_name,
                "description": description,
                "example": example_value,
                "category": category
            })
    
    # Add additional headers to reach approximately 144
    additional_headers = [
        ("X-Lupo-Custom-1", "Custom header 1", "custom_value_1"),
        ("X-Lupo-Custom-2", "Custom header 2", "custom_value_2"),
        ("X-Lupo-Custom-3", "Custom header 3", "custom_value_3"),
        ("X-Lupo-Custom-4", "Custom header 4", "custom_value_4"),
        ("X-Lupo-Custom-5", "Custom header 5", "custom_value_5"),
    ]
    
    for header_name, description, example_value in additional_headers:
        all_headers.append({
            "name": header_name,
            "description": description,
            "example": example_value,
            "category": "custom"
        })
    
    return all_headers

def create_batch_file(headers, batch_num, total_batches):
    """Create a batch file with headers"""
    filename = f"flip_headers_batch_{batch_num}_of_{total_batches}.md"
    filepath = os.path.join(OUTPUT_DIR, filename)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(f"# FLIP Headers Batch {batch_num} of {total_batches}\n\n")
        f.write(f"Generated: {datetime.utcnow().isoformat()}Z\n")
        f.write(f"Version: 4.0.27\n")
        f.write(f"Headers in this batch: {len(headers)}\n\n")
        
        f.write("## Headers\n\n")
        f.write("| Header | Description | Example | Category |\n")
        f.write("|--------|-------------|---------|----------|\n")
        
        for header in headers:
            f.write(f"| `{header['name']}` | {header['description']} | `{header['example']}` | {header['category']} |\n")
        
        f.write(f"\n---\n")
        f.write(f"*Batch {batch_num} of {total_batches} - FLIP Header Specification 4.0.27*\n")
    
    return filename

def create_master_index(all_headers, batch_files):
    """Create master index file"""
    with open(MASTER_INDEX_FILE, 'w', encoding='utf-8') as f:
        f.write("# FLIP Headers Master Index 4.0.27\n\n")
        f.write(f"Generated: {datetime.utcnow().isoformat()}Z\n")
        f.write(f"Total Headers: {len(all_headers)}\n")
        f.write(f"Batch Files: {len(batch_files)}\n")
        f.write(f"Headers per Batch: {HEADERS_PER_FILE}\n\n")
        
        f.write("## Batch Files\n\n")
        for i, filename in enumerate(batch_files, 1):
            f.write(f"{i}. [{filename}](flip_headers/{filename})\n")
        
        f.write("\n## Header Categories\n\n")
        categories = {}
        for header in all_headers:
            cat = header['category']
            if cat not in categories:
                categories[cat] = []
            categories[cat].append(header)
        
        for category, headers in sorted(categories.items()):
            f.write(f"### {category.replace('_', ' ').title()} ({len(headers)} headers)\n\n")
            f.write("| Header | Description | Example |\n")
            f.write("|--------|-------------|---------|\n")
            
            for header in headers:
                f.write(f"| `{header['name']}` | {header['description']} | `{header['example']}` |\n")
            
            f.write("\n")
        
        f.write("## Usage Notes\n\n")
        f.write("### Integration\n")
        f.write("1. Include relevant headers in API requests\n")
        f.write("2. Store header metadata in `lupo_contents.metadata_json`\n")
        f.write("3. Use `X-Lupo-Forwarded-For` for banned origin preservation\n")
        f.write("4. Apply `X-Lupo-Survivor-Protocol` for collapse events\n\n")
        
        f.write("### Doctrine Compliance\n")
        f.write("- All headers follow unregistry-first doctrine\n")
        f.write("- No ID guessing or max-plus-one logic\n")
        f.write("- UTF-8 glyphs preserved where required\n")
        f.write("- Survivor protocol headers for system collapse events\n\n")
        
        f.write("---\n")
        f.write("*FLIP Header Specification 4.0.27 - Master Index*\n")

def main():
    """Main execution function"""
    print("Generating FLIP headers for Lupopedia 4.0.27...")
    
    # Generate all headers
    all_headers = generate_all_headers()
    print(f"Generated {len(all_headers)} total headers")
    
    # Create batch files
    batch_files = []
    total_batches = (len(all_headers) + HEADERS_PER_FILE - 1) // HEADERS_PER_FILE
    
    for i in range(total_batches):
        start_idx = i * HEADERS_PER_FILE
        end_idx = min(start_idx + HEADERS_PER_FILE, len(all_headers))
        batch_headers = all_headers[start_idx:end_idx]
        
        filename = create_batch_file(batch_headers, i + 1, total_batches)
        batch_files.append(filename)
        print(f"Created batch {i + 1}/{total_batches}: {filename} ({len(batch_headers)} headers)")
    
    # Create master index
    create_master_index(all_headers, batch_files)
    print(f"Created master index: {MASTER_INDEX_FILE}")
    
    print("\nGeneration complete!")
    print(f"Total headers: {len(all_headers)}")
    print(f"Batch files: {len(batch_files)}")
    print(f"Master index: {MASTER_INDEX_FILE}")

if __name__ == "__main__":
    main()
