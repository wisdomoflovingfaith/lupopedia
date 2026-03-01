#!/usr/bin/env python3
"""
File Count Optimization Script - v4.0.52
Scans root-level .md files for optimization analysis
"""

import os
import sys
import hashlib
from datetime import datetime, timezone

# Configuration
VERSION = "4.0.52"
UTC_DATE = datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
TARGET_REDUCTION = 0.20  # Target 20% reduction

def scan_root_files():
    """Scan only root-level .md files"""
    print(f"Scanning root-level .md files for optimization analysis...")
    
    file_stats = {
        'total_files': 0,
        'by_extension': {},
        'by_size': {},
        'potential_duplicates': [],
        'legacy_files': [],
        'large_files': []
    }
    
    total_size = 0
    
    # Focus on root-level .md files only
    root_files = []
    for item in os.listdir('.'):
        if item.endswith('.md') and os.path.isfile(item):
            root_files.append(item)
    
    for file in root_files:
        file_path = file
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
                
                # Check for potential duplicates
                if file_size > 0:
                    file_hash = hashlib.sha256(open(file_path, 'rb').read()).hexdigest()
                    
                    # Look for files with same name pattern
                    for other_root, other_dirs, other_files in os.walk("."):
                        if ".git" in other_dirs:
                            continue
                        
                        for other_file in other_files:
                            if other_file == file and other_root != root:
                                other_path = os.path.join(other_root, other_file)
                                if os.path.exists(other_path):
                                    other_hash = hashlib.sha256(open(other_path, 'rb').read()).hexdigest()
                                    if file_hash == other_hash:
                                        file_stats['potential_duplicates'].append({
                                            'file1': file_path,
                                            'file2': other_path,
                                            'size': file_size,
                                            'hash': file_hash
                                        })
                
                # Check for legacy files
                if file in ['legacy.log', 'debug.log', 'temp.tmp', 'backup.old']:
                    file_stats['legacy_files'].append(file_path)
                
                # Check for large files (>1MB)
                if file_size > 1024 * 1024:
                    file_stats['large_files'].append({
                        'path': file_path,
                        'size': file_size,
                        'size_mb': round(file_size / (1024 * 1024), 2)
                    })
                
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
    print(f"\n=== OPTIMIZATION REPORT ===")
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
    print(f"\n--- Optimization Recommendations ---")
    
    current_reduction_needed = 1.0 - (stats['total_files'] / 2000)  # Assuming 2000 files as baseline
    reduction_needed = max(0, current_reduction_needed - TARGET_REDUCTION)
    
    if reduction_needed > 0:
        print(f"TARGET: Reduce file count by {TARGET_REDUCTION*100:.1f}%")
        print(f"CURRENT: Need to reduce by {reduction_needed*100:.1f}%")
        print("RECOMMENDATIONS:")
        
        # Legacy files
        if stats['legacy_files']:
            print(f"- Remove {len(stats['legacy_files'])} legacy files")
        
        # Duplicates
        if stats['potential_duplicates']:
            print(f"- Review {len(stats['potential_duplicates'])} potential duplicate files")
        
        # Large files
        if stats['large_files']:
            print(f"- Review {len(stats['large_files'])} large files (>1MB)")
        
        # File type optimization
        print("- Consider consolidating similar file types")
        print("- Archive old documentation versions")
        
    else:
        print("TARGET: Already within acceptable range")
        print("RECOMMENDATIONS:")
        print("- Repository is well-optimized")
        print("- Continue with current structure")
    
    return reduction_needed

def main():
    """Main execution"""
    print(f"File Count Optimization - v{VERSION}")
    print(f"UTC Date: {UTC_DATE}")
    print()
    
    # Scan repository
    stats = scan_root_files()
    
    # Generate report
    reduction_needed = generate_optimization_report(stats)
    
    # Save report
    report_path = f"tools/file_optimization_report_v{VERSION}.txt"
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write(f"File Count Optimization Report - {VERSION}\n")
        f.write(f"Generated: {UTC_DATE}\n")
        f.write(f"Total Files: {stats['total_files']}\n")
        f.write(f"Total Size: {stats['total_size']} bytes\n")
        f.write(f"Reduction Needed: {reduction_needed*100:.1f}%\n")
        
        for ext, data in stats['by_extension'].items():
            f.write(f"{ext}: {data['count']} files ({data['total_size']} bytes)\n")
        
        if stats['potential_duplicates']:
            f.write("\nPotential Duplicates:\n")
            for dup in stats['potential_duplicates']:
                f.write(f"{dup['file1']} <-> {dup['file2']} ({dup['size']} bytes)\n")
        
        if stats['legacy_files']:
            f.write("\nLegacy Files:\n")
            for legacy in stats['legacy_files']:
                f.write(f"{legacy}\n")
        
        if stats['large_files']:
            f.write("\nLarge Files (>1MB):\n")
            for large in stats['large_files']:
                f.write(f"{large['path']} ({large['size_mb']} MB)\n")
    
    print(f"Report saved to: {report_path}")
    
    return 0 if reduction_needed <= TARGET_REDUCTION else 1

if __name__ == "__main__":
    sys.exit(main())
