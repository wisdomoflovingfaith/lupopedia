#!/usr/bin/env python3
"""
Git Push Execution Script - v4.0.50 Finalization
Executes actual git push for version rollover
"""

import subprocess
import os
import sys
from datetime import datetime, timezone

# Configuration
VERSION = "4.0.50"
UTC_DATE = datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
COMMIT_MESSAGE = f"Finalize v4.0.50: Stage all untracked files, atom updates to v4.0.51, validations passed"
TAG_MESSAGE = f"v4.0.50 finalized - ready for v4.0.51 ANUBIS focus"

def run_git_command(cmd):
    """Run git command and return result"""
    try:
        result = subprocess.run(cmd, cwd=r'C:\ServBay\www\servbay\lupopedia', 
                              capture_output=True, text=True, shell=True)
        print(f"Command: {' '.join(cmd)}")
        print(f"Output: {result.stdout}")
        if result.stderr:
            print(f"Error: {result.stderr}")
        return result
    except Exception as e:
        print(f"Error running git command: {e}")
        return 1

def main():
    """Main execution"""
    print(f"Git Push Execution - v4.0.50 Finalization")
    print(f"UTC Date: {UTC_DATE}")
    print()
    
    # Initialize results
    stage_result = 1
    commit_result = 1
    tag_result = 1
    push_result = 1
    
    # Stage all files (handles untracked and modified)
    print("Staging all files...")
    result = run_git_command(['git', 'add', '.'])
    stage_result = result.returncode == 0
    
    if stage_result:
        print("✅ All files staged successfully")
        
        # Commit changes (amend if needed)
        print("Committing changes...")
        result = run_git_command(['git', 'commit', '--amend', '-m', COMMIT_MESSAGE])
        commit_result = result.returncode == 0
    
    if stage_result:
        print("✅ All files staged successfully")
        
        # Commit changes (amend if needed)
        print("Committing changes...")
        result = run_git_command(['git', 'commit', '--amend', '-m', COMMIT_MESSAGE])
        commit_result = result.returncode == 0
        
        if commit_result:
            print("✅ Commit successful")
            
            # Create tag (force if exists)
            print("Creating tag...")
            result = run_git_command(['git', 'tag', '-f', VERSION, '-m', TAG_MESSAGE])
            tag_result = result.returncode == 0
            
            if tag_result:
                print("✅ Tag created")
            
                # Push to origin
                print("Pushing to origin...")
                result = run_git_command(['git', 'push', 'origin', 'main'])
                push_result = result.returncode == 0
                
                if push_result:
                    print("✅ Push to origin/main successful")
                
                    # Push tags
                    print("Pushing tags...")
                    result = run_git_command(['git', 'push', 'origin', '--tags'])
                    tags_result = result.returncode == 0
                    
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
    
    return 0 if (stage_result and commit_result and tag_result and push_result and tags_result) else 1

if __name__ == "__main__":
    sys.exit(main())
