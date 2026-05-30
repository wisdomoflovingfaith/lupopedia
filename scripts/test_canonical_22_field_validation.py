#!/usr/bin/env python3
"""
Minimal regression tests for canonical 22-field PRD 16 v4.1.4 header validation.

Tests verify:
- PASS: canonical 22-field header with prd_cluster, no content_slug
- FAIL: header with content_slug
- FAIL: header with pk_slug  
- FAIL: header with prd_slug
- FAIL: header missing prd_cluster
- FAIL: header with wrong canonical count
- FAIL: header with field order reflecting stale pre-4.1.4 contract
"""

import sys
import os
import tempfile
from pathlib import Path

# Resolve repo root dynamically
THIS_FILE = Path(__file__).resolve()
REPO_ROOT = THIS_FILE.parents[2]

# Add repo root to path for imports
sys.path.insert(0, str(REPO_ROOT))

import validate_lupopedia_headers_universal

def create_test_header(content, filename="test.md"):
    """Create a temporary test file with the given header content."""
    fd, path = tempfile.mkstemp(suffix='.md', text=True)
    os.close(fd)
    try:
        with open(path, 'w', encoding='utf-8', newline='\n') as f:
            f.write(content)
        return path
    except:
        try:
            os.unlink(path)
        except:
            pass
        raise

def cleanup_test_file(path):
    """Clean up temporary test file."""
    try:
        os.unlink(path)
    except OSError:
        pass

def test_canonical_22_field_pass():
    """PASS: canonical 22-field header with prd_cluster, no content_slug."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Canonical 22-field test header"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        # Call the main validation function from the module
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return result
    finally:
        cleanup_test_file(filepath)

def test_content_slug_fail():
    """FAIL: header with content_slug."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  content_slug: "test-slug"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with removed content_slug"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail
    finally:
        cleanup_test_file(filepath)

def test_pk_slug_fail():
    """FAIL: header with pk_slug."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with pk_slug"
  pk_slug: "test-slug"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail
    finally:
        cleanup_test_file(filepath)

def test_prd_slug_fail():
    """FAIL: header with prd_slug."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with prd_slug"
  prd_slug: "test-slug"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail
    finally:
        cleanup_test_file(filepath)

def test_missing_prd_cluster_fail():
    """FAIL: header missing prd_cluster."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  title: "Test Header"
  summary: "Header missing prd_cluster"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail
    finally:
        cleanup_test_file(filepath)

def test_wrong_order_fail():
    """FAIL: header with wrong field order."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  trust_tier: "canonical"  # wrong position - should be after when_updated
  when_updated: "20260422030000"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with wrong field order"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail
    finally:
        cleanup_test_file(filepath)

def test_wrong_count_fail():
    """FAIL: header with wrong field count."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  # missing default_collection_id - wrong count
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with wrong field count"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail
    finally:
        cleanup_test_file(filepath)

def test_content_slug_non_strict_fail():
    """FAIL: content_slug should fail even in non-strict mode for 4.1.4 headers."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with content_slug in non-strict mode"
  content_slug: "test-slug"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=False)
        return not result  # Should fail even in non-strict mode
    finally:
        cleanup_test_file(filepath)


def test_md_duplicate_prd_cluster_fail():
    """FAIL: duplicate prd_cluster fields."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  prd_cluster: "DUPLICATE_CLUSTER"
  title: "Test Header"
  summary: "Header with duplicate prd_cluster"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail on duplicate fields
    finally:
        cleanup_test_file(filepath)

def test_malformed_header_missing_delimiters_fail():
    """FAIL: malformed header block missing closing delimiter."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header missing closing delimiter"

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail on malformed header
    finally:
        cleanup_test_file(filepath)

def test_23_field_count_fail():
    """FAIL: header with 23 fields (extra field)."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with 23 fields"
  extra_field: "this makes 23 fields"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail on wrong field count
    finally:
        cleanup_test_file(filepath)

def test_missing_summary_fail():
    """FAIL: header missing required summary field."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, errors = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        # Should fail and contain error about missing field or wrong count
        error_text = " ".join(errors) if errors else ""
        return not result and ("HDR_MISSING_KEY" in error_text or "HDR_KEY_COUNT" in error_text)
    finally:
        cleanup_test_file(filepath)

def test_multiple_removed_fields_fail():
    """FAIL: header with multiple removed fields."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with multiple removed fields"
  content_slug: "test-slug"
  pk_slug: "test-pk"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, errors = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        error_text = " ".join(errors) if errors else ""
        return not result and ("HDR_REMOVED_FIELD" in error_text or "HDR_KEY_COUNT" in error_text)
    finally:
        cleanup_test_file(filepath)

def test_incorrect_field_type_fail():
    """FAIL: header with incorrect field type (string instead of int)."""
    header = """---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "test.md"
  web_path: "https://www.lupopedia.com/lupopedia/test.md"
  status: "active"
  when_updated: "20260422030000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: "implementation"
  artifact_kind: "tool"
  channel_key: "development"
  federation_node_id: "not-an-integer"
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
  title: "Test Header"
  summary: "Header with incorrect field type"
---

Test content here.
"""
    filepath = create_test_header(header)
    try:
        result, _ = validate_lupopedia_headers_universal.validate_yaml_file(filepath, header, strict_mode=True)
        return not result  # Should fail on type validation if enforced
    finally:
        cleanup_test_file(filepath)

def run_tests():
    """Run all regression tests."""
    print("Running canonical 22-field regression tests...")
    print("=" * 60)
    
    tests = [
        ("Canonical 22-field header (PASS)", test_canonical_22_field_pass),
        ("FAIL: header with content_slug", test_content_slug_fail),
        ("FAIL: header with pk_slug", test_pk_slug_fail),
        ("FAIL: header with prd_slug", test_prd_slug_fail),
        ("FAIL: header missing prd_cluster", test_missing_prd_cluster_fail),
        ("FAIL: header with wrong field order", test_wrong_order_fail),
        ("FAIL: header with wrong field count", test_wrong_count_fail),
        ("FAIL: content_slug in non-strict mode", test_content_slug_non_strict_fail),
        ("FAIL: duplicate prd_cluster fields", test_md_duplicate_prd_cluster_fail),
        ("FAIL: malformed header missing delimiters", test_malformed_header_missing_delimiters_fail),
        ("FAIL: header with 23 fields", test_23_field_count_fail),
        ("FAIL: header missing summary field", test_missing_summary_fail),
        ("FAIL: multiple removed fields", test_multiple_removed_fields_fail),
        ("FAIL: incorrect field type", test_incorrect_field_type_fail),
    ]
    
    passed = 0
    failed = 0
    
    for name, test_func in tests:
        try:
            result = test_func()
            if result:
                print(f"✓ PASS: {name}")
                passed += 1
            else:
                print(f"✗ FAIL: {name}")
                failed += 1
        except Exception as e:
            print(f"✗ ERROR: {name} - {e}")
            failed += 1
    
    print("=" * 60)
    print(f"Results: {passed} passed, {failed} failed")
    
    if failed > 0:
        print("Some tests failed! ✗")
        return 1
    else:
        print("All tests passed! ✓")
        return 0

if __name__ == "__main__":
    success = run_tests()
    sys.exit(0 if success == 0 else 1)
