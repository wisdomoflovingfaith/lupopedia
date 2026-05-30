#!/usr/bin/env python3
"""
Pre-commit hook for Lupopedia Five-Layer Documentation Architecture
Validates implementation folders before allowing commits.
"""

import sys
import os
from pathlib import Path

# Add the scripts directory to Python path
script_dir = Path(__file__).parent
sys.path.insert(0, str(script_dir))

from validate_implementation import ImplementationValidator

def main():
    """Run validation before commit"""
    root_path = Path.cwd()
    
    # If we're in a subdirectory, go to repo root
    if not (root_path / "docs").exists():
        root_path = root_path.parent
    
    print("Lupopedia Pre-commit Validation")
    print("=" * 40)
    
    validator = ImplementationValidator(root_path)
    passed = validator.validate_all_implementations()
    report = validator.generate_report()
    
    print(f"\nValidation Result: {'PASSED' if passed else 'FAILED'}")
    print(f"Errors: {report['summary']['total_errors']}")
    print(f"Warnings: {report['summary']['total_warnings']}")
    
    if report['errors']:
        print("\nErrors found. Commit blocked.")
        print("Fix the following issues before committing:")
        for error in report['errors']:
            print(f"  - {error}")
        print("\nRun 'python scripts/validate_implementation.py' for full details")
        return 1
    
    if report['warnings']:
        print("\nWarnings found (commit allowed):")
        for warning in report['warnings']:
            print(f"  - {warning}")
    
    print("\nValidation passed. Commit allowed.")
    return 0

if __name__ == "__main__":
    sys.exit(main())
