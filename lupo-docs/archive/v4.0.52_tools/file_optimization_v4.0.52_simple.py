#!/usr/bin/env python3
"""
File Count Optimization Report - v4.0.52
Root-level .md files analysis for FILEOPT-2026-02-27-001
"""

import os
import sys
from datetime import datetime, timezone

# Configuration
VERSION = "4.0.52"
UTC_DATE = datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")

def main():
    """Main execution"""
    print(f"File Count Optimization Report - v{VERSION}")
    print(f"UTC Date: {UTC_DATE}")
    print()
    
    # Get root-level .md files
    root_files = [f for f in os.listdir('.') if f.endswith('.md') and os.path.isfile(f)]
    
    print(f"=== ROOT-LEVEL .md FILES ANALYSIS ===")
    print(f"Total root .md files: {len(root_files)}")
    
    # Analyze files
    large_files = []
    legacy_files = []
    duplicates = []
    
    for file in root_files:
        file_path = file
        try:
            file_size = os.path.getsize(file_path)
            
            # Check for large files (>1MB)
            if file_size > 1024 * 1024:
                large_files.append({
                    'path': file_path,
                    'size_mb': round(file_size / (1024 * 1024), 2)
                })
            
            # Check for legacy files
            if file in ['legacy.log', 'debug.log', 'temp.tmp', 'backup.old']:
                legacy_files.append(file_path)
            
        except Exception as e:
            print(f"Error processing {file_path}: {e}")
    
    # Check for potential duplicates (same names)
    file_names = [f.lower() for f in root_files]
    for name in set(file_names):
        if file_names.count(name) > 1:
            duplicates.append(name)
    
    print(f"\n--- FILE ANALYSIS ---")
    print(f"Large files (>1MB): {len(large_files)}")
    for large in large_files:
        print(f"  - {large['path']} ({large['size_mb']} MB)")
    
    print(f"Legacy files: {len(legacy_files)}")
    for legacy in legacy_files:
        print(f"  - {legacy}")
    
    print(f"Potential duplicates: {len(duplicates)}")
    for dup in duplicates:
        print(f"  - {dup}")
    
    # Recommendations
    print(f"\n--- OPTIMIZATION RECOMMENDATIONS ---")
    print("TARGET: Reduce root-level .md files by 10-20%")
    print("CURRENT ANALYSIS:")
    
    if len(root_files) > 50:
        print(f"- Found {len(root_files)} root .md files - exceeds target of 50")
        print("RECOMMENDATIONS:")
        print("- Consolidate related documentation files")
        print("- Archive old version files (e.g., CHANGELOG_*.md)")
        print("- Merge similar content files")
        print("- Remove duplicate or legacy files")
    else:
        print(f"- Found {len(root_files)} root .md files - within target range")
        print("RECOMMENDATIONS:")
        print("- Current root structure is acceptable")
        print("- Continue with phase 2: channels/ directory analysis")
    
    # Save report
    report_path = f"tools/file_optimization_report_v{VERSION}.txt"
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write(f"File Count Optimization Report - {VERSION}\n")
        f.write(f"Generated: {UTC_DATE}\n")
        f.write(f"Total root .md files: {len(root_files)}\n")
        f.write(f"Large files: {len(large_files)}\n")
        f.write(f"Legacy files: {len(legacy_files)}\n")
        f.write(f"Potential duplicates: {len(duplicates)}\n")
        
        f.write("\nRecommendations:\n")
        if len(root_files) > 50:
            f.write("- Consolidate related documentation files\n")
            f.write("- Archive old version files\n")
            f.write("- Merge similar content files\n")
            f.write("- Remove duplicate or legacy files\n")
        else:
            f.write("- Current root structure is acceptable\n")
            f.write("- Continue with phase 2: channels/ directory analysis\n")
    
    print(f"Report saved to: {report_path}")
    
    return 0 if len(root_files) <= 50 else 1

if __name__ == "__main__":
    sys.exit(main())
