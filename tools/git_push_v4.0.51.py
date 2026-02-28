#!/usr/bin/env python3
"""
Git Push Script - v4.0.51 Finalization
Pushes all changes to GitHub for version 4.0.51
"""

import subprocess
import os
import sys
from datetime import datetime, timezone

# Configuration
VERSION = "4.0.51"
UTC_DATE = datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
COMMIT_MESSAGE = f"Finalize v4.0.51: Complete FLARE header application to all .md files, ready for v4.0.52 development"
TAG_MESSAGE = f"v4.0.51 finalized - FLARE coverage complete, ready for v4.0.52 optimization"

def run_git_command(cmd):
    """Run git command and return result"""
    try:
        result = subprocess.run(cmd, cwd=r'C:\ServBay\www\servbay\lupopedia', 
                              capture_output=True, text=True, shell=True)
        print(f"Command: {' '.join(cmd)}")
        print(f"Output: {result.stdout}")
        if result.stderr:
            print(f"Error: {result.stderr}")
        return result.returncode == 0
    except Exception as e:
        print(f"Error running git command: {e}")
        return 1

def main():
    """Main execution"""
    print(f"Git Push - v4.0.51 Finalization")
    print(f"UTC Date: {UTC_DATE}")
    print()
    
    # Stage all files
    print("Staging all files...")
    stage_result = run_git_command(['git', 'add', '.'])
    
    if stage_result:
        print("✅ All files staged successfully")
        
        # Commit changes
        print("Committing changes...")
        commit_result = run_git_command(['git', 'commit', '-m', COMMIT_MESSAGE])
        
        if commit_result:
            print("✅ Commit successful")
            
            # Create tag
            print("Creating tag...")
            tag_result = run_git_command(['git', 'tag', '-a', VERSION, '-m', TAG_MESSAGE])
            
            if tag_result:
                print("✅ Tag created")
            
                # Push to origin
                print("Pushing to origin...")
                push_result = run_git_command(['git', 'push', 'origin', 'main'])
                
                if push_result:
                    print("✅ Push to origin/main successful")
                    
                    # Push tags
                    print("Pushing tags...")
                    tags_result = run_git_command(['git', 'push', 'origin', '--tags'])
                    
                    if tags_result:
                        print("✅ Tags pushed successfully")
                    else:
                        print("❌ Tags push failed")
                else:
                    print("❌ Push to origin failed")
            else:
                print("❌ Tag creation failed")
    else:
        print("❌ Staging failed")
    
    # Summary
    print(f"\n=== PUSH SUMMARY ===")
    print(f"Version: {VERSION}")
    print(f"UTC Date: {UTC_DATE}")
    print(f"Staging: {'✅' if stage_result else '❌'}")
    print(f"Commit: {'✅' if commit_result else '❌'}")
    print(f"Tag: {'✅' if tag_result else '❌'}")
    print(f"Push: {'✅' if push_result else '❌'}")
    print(f"Tags: {'✅' if 'tags_result' else '❌'}")
    
    return 0 if (stage_result and commit_result and tag_result and push_result and tags_result) else 1

if __name__ == "__main__":
    sys.exit(main())
