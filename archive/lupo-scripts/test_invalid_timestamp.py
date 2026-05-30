#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/test_invalid_timestamp.py"
#   questions_toon: null
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
Test script to verify enforcement blocks invalid timestamps
"""

import sys
import os

# Import enforcement
sys.path.append(os.path.dirname(__file__))
from enforce_timestamp_validation import TimestampEnforcementError, safe_write_file

def test_invalid_timestamp():
    """Test that invalid timestamp is blocked"""
    try:
        # Try to create file with invalid timestamp
        filepath = "lupo-channels/42/threads/1044/20260321_250000_invalid_test.md"
        content = "This should be blocked"
        
        safe_write_file(filepath, content)
        print("❌ FAIL: Invalid timestamp was allowed")
        return False
        
    except TimestampEnforcementError as e:
        print(f"✅ PASS: Invalid timestamp blocked - {e}")
        return True
    except Exception as e:
        print(f"❌ FAIL: Unexpected error - {e}")
        return False

def test_valid_timestamp():
    """Test that valid timestamp is allowed"""
    try:
        # Try to create file with valid timestamp
        filepath = "lupo-channels/42/threads/1044/20260321_210000_valid_test.md"
        content = "This should be allowed"
        
        safe_write_file(filepath, content)
        print("✅ PASS: Valid timestamp was allowed")
        
        # Clean up
        os.remove(filepath)
        return True
        
    except Exception as e:
        print(f"❌ FAIL: Valid timestamp was blocked - {e}")
        return False

if __name__ == "__main__":
    print("Testing timestamp enforcement...")
    
    invalid_test = test_invalid_timestamp()
    valid_test = test_valid_timestamp()
    
    if invalid_test and valid_test:
        print("✅ ALL TESTS PASSED - Enforcement working correctly")
        sys.exit(0)
    else:
        print("❌ TESTS FAILED - Enforcement not working")
        sys.exit(1)