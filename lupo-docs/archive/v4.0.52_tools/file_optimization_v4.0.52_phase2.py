#!/usr/bin/env python3
"""
File Count Optimization Script - v4.0.52 Phase 2
Scans channels/ directory for optimization analysis
"""

import os
import sys
import hashlib
from datetime import datetime, timezone

# Configuration
VERSION = "4.0.52"
UTC_DATE = datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
TARGET_REDUCTION = 0.20  # Target 20% reduction

def scan_channels_directory():
    """Scan channels/ directory for optimization analysis"""
    print(f"Scanning channels/ directory for optimization analysis...")
    
    file_stats = {
        'total_files': 0,
        'by_extension': {},
        'by_size': {},
        'potential_duplicates': [],
        'legacy_files': [],
        'large_files': [],
        'duplicate_tasks': [],
        'legacy_acknowledgments': []
    }
    
    total_size = 0
    
    # Focus on channels/ directory
    channels_files = []
    
    # Scan channels/ directory
    if os.path.exists('channels'):
        for root, dirs, files in os.walk('channels'):
            if ".git" in dirs:
                continue
                
            for file in files:
                if file.endswith('.md'):
                    file_path = os.path.join(root, file)
                    channels_files.append(file_path)
    
    # Scan tasks/active directory
    if os.path.exists('tasks/active'):
        for root, dirs, files in os.walk('tasks/active'):
            if ".git" in dirs:
                continue
                
            for file in files:
                if file.endswith('.md'):
                    file_path = os.path.join(root, file)
                    channels_files.append(file_path)
    
    # Limit to 50 files for initial scan
    target_files = channels_files[:50]
    
    print(f"Found {len(channels_files)} total .md files in channels/ and tasks/active/")
    print(f"Analyzing first {len(target_files)} files...")
    
    for file_path in target_files:
        try:
            file_size = os.path.getsize(file_path)
            total_size += file_size
                
            # Get file extension
            _, ext = os.path.splitext(file)
            ext = ext.lower()
                
            file_stats['total_files'] += 1
                
            if ext not in file_stats['by_extension']:
                file_stats['by_extension'][ext] = {'count': 0, 'total_size': 0}
                
            file_stats['by_extension'][ext]['count'] += 1
            file_stats['by_extension'][ext]['total_size'] += file_size
                
                # Check for large files (>1MB)
                
            # Check for legacy files (pre-v4.0.51)
            if '4.0.50' in file_path or '4.0.49' in file_path or '4.0.48' in file_path:
                file_stats['legacy_files'].append(file_path)
                
            # Check for duplicate tasks
            if 'cascade_faucet' in file_path:
                file_stats['duplicate_tasks'].append(file_path)
                
                # Check for legacy acknowledgments
                if 'acknowledgment' in file_path and 'windsurf' in file_path:
                    file_stats['legacy_acknowledgments'].append(file_path)
                
        except Exception as e:
            print(f"Error processing {file_path}: {e}")
    
    # Calculate size distribution
    size_ranges = {
        'small': {'count': 0, 'size': 0},      # < 10KB
        'medium': {'count': 0, 'size': 0},     # 10KB - 100KB
        'large': {'count': 0, 'size': 0},      # 100KB - 1MB
        'xlarge': {'count': 0, 'size': 0}      # > 1MB
    }
    
    file_stats['by_size'] = size_ranges
    file_stats['total_size'] = total_size
    
    return file_stats

def generate_optimization_report(stats):
    """Generate optimization recommendations"""
    print(f"\n=== CHANNELS/ DIRECTORY OPTIMIZATION REPORT ===")
    print(f"Total Files: {stats['total_files']}")
    print(f"Total Size: {stats['total_size']} bytes")
    
    # Analyze by extension
    print(f"\n--- File Distribution by Extension ---")
    for ext, data in stats['by_extension'].items():
        print(f"{ext}: {data['count']} files ({data['total_size']} bytes)")
    
    # Size distribution
    print(f"\n--- File Size Distribution ---")
    for size_cat, data in stats['by_size'].items():
        print(f"{size_cat}: {data['count']} files ({data['size']} bytes)")
    
    # Recommendations
    print(f"\n--- OPTIMIZATION RECOMMENDATIONS ---")
    
    current_reduction_needed = 1.0 - (stats['total_files'] / 200)  # Assuming 200 files as baseline
    reduction_needed = max(0, current_reduction_needed - TARGET_REDUCTION)
    
    print(f"TARGET: Reduce file count by {TARGET_REDUCTION*100:.1f}%")
    print(f"CURRENT: Need to reduce by {reduction_needed*100:.1f}%")
    print("RECOMMENDATIONS:")
    
    # Legacy files
    if stats['legacy_files']:
        print(f"- Remove {len(stats['legacy_files'])} legacy files (pre-v4.0.51)")
        for legacy in stats['legacy_files']:
            print(f"  - {legacy}")
    
    # Duplicate tasks
    if stats['duplicate_tasks']:
        print(f"- Merge {len(stats['duplicate_tasks'])} duplicate task files")
        for dup in stats['duplicate_tasks']:
            print(f"  - {dup}")
    
    # Legacy acknowledgments
    if stats['legacy_acknowledgments']:
        print(f"- Archive {len(stats['legacy_acknowledgments'])} legacy acknowledgment files")
        for ack in stats['legacy_acknowledgments']:
            print(f"  - {ack}")
    
    # Large files
    if stats['large_files']:
        print(f"- Review {len(stats['large_files'])} large files (>1MB)")
        for large in stats['large_files']:
            print(f"  - {large['path']} ({large['size_mb']} MB)")
    
    # File type optimization
    print("- Consolidate similar task files")
    print("- Archive old documentation versions")
    print("- Remove duplicate acknowledgment files")
    
    return reduction_needed

def main():
    """Main execution"""
    print(f"File Count Optimization - v{VERSION} Phase 2")
    print(f"UTC Date: {UTC_DATE}")
    print()
    
    # Scan channels/ directory
    stats = scan_channels_directory()
    
    # Generate report
    reduction_needed = generate_optimization_report(stats)
    
    # Save report
    report_path = f"tools/file_optimization_report_v{VERSION}_phase2.txt"
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write(f"File Count Optimization Report - {VERSION} Phase 2\n")
        f.write(f"Generated: {UTC_DATE}\n")
        f.write(f"Total Files: {stats['total_files']}\n")
        f.write(f"Total Size: {stats['total_size']} bytes\n")
        f.write(f"Reduction Needed: {reduction_needed*100:.1f}%\n")
        
        for ext, data in stats['by_extension'].items():
            f.write(f"{ext}: {data['count']} files ({data['total_size']} bytes)\n")
        
        if stats['legacy_files']:
            f.write("\nLegacy Files:\n")
            for legacy in stats['legacy_files']:
                f.write(f"{legacy}\n")
        
        if stats['duplicate_tasks']:
            f.write("\nDuplicate Task Files:\n")
            for dup in stats['duplicate_tasks']:
                f.write(f"{dup}\n")
        
        if stats['legacy_acknowledgments']:
            f.write("\nLegacy Acknowledgment Files:\n")
            for ack in stats['legacy_acknowledgments']:
                f.write(f"{ack}\n")
        
        if stats['large_files']:
            f.write("\nLarge Files (>1MB):\n")
            for large in stats['large_files']:
                f.write(f"{large['path']} ({large['size_mb']} MB)\n")
        
        f.write("\nRecommendations:\n")
        f.write("- Consolidate similar task files\n")
        f.write("- Archive old documentation versions\n")
        f.write("- Remove duplicate acknowledgment files\n")
    
    print(f"Report saved to: {report_path}")
    
    return 0 if reduction_needed <= TARGET_REDUCTION else 1

if __name__ == "__main__":
    sys.exit(main())
