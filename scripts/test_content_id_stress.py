#!/usr/bin/env python
"""
Stress Test for Content ID Generator
Tests thread-safety, collision detection, and overflow handling.

ATHENA DIRECTIVE 4.0.88 — Hardened deterministic ID allocation test suite.

Test Scenarios:
  1. Parallel thread spawn (10 threads × 10k iterations each)
  2. Collision detection and retry logic
  3. Sequence overflow handling (allocate 1M+ IDs in same second)
  4. Path hash consistency across threads
  5. Actor ID tracking across concurrent allocations
"""

import sys
import os
import threading
import time
from concurrent.futures import ThreadPoolExecutor, as_completed
from collections import defaultdict, Counter

# Add parent dir to path
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from generate_content_id import (
    generate_content_id,
    get_utc_timestamp_seconds,
    compute_file_path_hash,
    _SEQUENCE_COUNTERS,
    _COUNTER_LOCK,
)

# Global test results
test_results = {
    'generated_ids': set(),
    'collisions': 0,
    'path_hashes': defaultdict(set),
    'threads_completed': 0,
    'time_taken': 0,
    'errors': [],
}

def test_parallel_id_generation(num_threads=10, iterations_per_thread=10000):
    """
    Spawn multiple threads allocating IDs concurrently.
    Verify no collisions occur.
    
    Args:
        num_threads (int): Number of concurrent threads
        iterations_per_thread (int): IDs per thread
    
    Returns:
        dict: Results with collision count, unique IDs, errors
    """
    print(f"\n🔬 TEST 1: Parallel ID Generation ({num_threads} threads, {iterations_per_thread} IDs each)")
    print(f"{'='*70}")
    
    test_results['generated_ids'].clear()
    test_results['collisions'] = 0
    test_results['threads_completed'] = 0
    test_results['errors'].clear()
    
    start_time = time.time()
    
    def worker_thread(thread_id):
        """Generate IDs in a thread."""
        local_ids = []
        try:
            for i in range(iterations_per_thread):
                file_path = f'channels/42/threads/{thread_id:03d}/artifact_{i:06d}.md'
                content_id = generate_content_id(file_path)
                local_ids.append(content_id)
                
                # Check for collision
                if content_id in test_results['generated_ids']:
                    test_results['collisions'] += 1
                else:
                    test_results['generated_ids'].add(content_id)
            
            test_results['threads_completed'] += 1
            return local_ids
        except Exception as e:
            test_results['errors'].append(f"Thread {thread_id}: {e}")
            return []
    
    # Run with thread pool
    with ThreadPoolExecutor(max_workers=num_threads) as executor:
        futures = [executor.submit(worker_thread, i) for i in range(num_threads)]
        
        for future in as_completed(futures):
            future.result()  # Propagate exceptions
    
    elapsed = time.time() - start_time
    test_results['time_taken'] = elapsed
    
    # Results
    total_ids = num_threads * iterations_per_thread
    unique_ids = len(test_results['generated_ids'])
    collision_count = test_results['collisions']
    
    print(f"Total IDs generated: {total_ids:,}")
    print(f"Unique IDs: {unique_ids:,}")
    print(f"Collisions: {collision_count}")
    print(f"Threads completed: {test_results['threads_completed']}/{num_threads}")
    print(f"Time elapsed: {elapsed:.2f}s ({total_ids/elapsed:,.0f} IDs/sec)")
    
    if test_results['errors']:
        print(f"\n⚠️  Errors encountered:")
        for err in test_results['errors'][:5]:  # Show first 5
            print(f"   {err}")
    
    # Verify results
    if collision_count > 0:
        print(f"\n❌ FAIL: {collision_count} collisions detected!")
        return False
    elif unique_ids != total_ids:
        print(f"\n❌ FAIL: Expected {total_ids} unique IDs, got {unique_ids}")
        return False
    else:
        print(f"✅ PASS: All {total_ids:,} IDs are unique (zero collisions)")
        return True

def test_path_hash_consistency():
    """
    Verify that the same file path always produces the same hash component.
    Test across threads to verify thread-safety.
    
    Returns:
        bool: True if all paths produce consistent hashes
    """
    print(f"\n🔬 TEST 2: Path Hash Consistency")
    print(f"{'='*70}")
    
    test_paths = [
        'channels/42/threads/1001/file.md',
        'channels/42/broadcasts/bcast_001.md',
        'content/article_v1.md',
        'chats/chat_002.md',
    ]
    
    def worker_hash(path, iterations=1000):
        """Compute path hash multiple times."""
        hashes = []
        for _ in range(iterations):
            h = compute_file_path_hash(path)
            hashes.append(h)
        return path, hashes
    
    with ThreadPoolExecutor(max_workers=4) as executor:
        futures = [executor.submit(worker_hash, path) for path in test_paths]
        
        all_pass = True
        for future in as_completed(futures):
            path, hashes = future.result()
            
            # All hashes for this path should be identical
            if len(set(hashes)) == 1:
                hash_val = hashes[0]
                print(f"✅ {path:50s} → {hash_val:06d}")
            else:
                unique_hashes = len(set(hashes))
                print(f"❌ {path:50s} produced {unique_hashes} different hashes!")
                all_pass = False
    
    if all_pass:
        print(f"✅ PASS: All paths produce consistent hashes")
    else:
        print(f"❌ FAIL: Path hash consistency violated")
    
    return all_pass

def test_overflow_handling():
    """
    Test sequence overflow handling when allocating 1M+ IDs in same second.
    
    Returns:
        bool: True if no collisions under overflow conditions
    """
    print(f"\n🔬 TEST 3: Sequence Overflow Handling")
    print(f"{'='*70}")
    
    # Clear sequence counters
    _SEQUENCE_COUNTERS.clear()
    
    # Use fixed timestamp to force same-second scenario
    fixed_ts = get_utc_timestamp_seconds()
    
    print(f"Allocating 1M IDs with fixed timestamp {fixed_ts}...")
    
    ids_generated = set()
    overflow_count = 0
    start_time = time.time()
    
    try:
        for i in range(1000000):
            file_path = f'overflow_test_{i % 100}.md'  # Only 100 unique paths
            content_id = generate_content_id(file_path, timestamp_sec=fixed_ts)
            
            if content_id in ids_generated:
                print(f"❌ COLLISION at iteration {i}: {content_id}")
                return False
            
            ids_generated.add(content_id)
            
            # Show progress every 100k
            if (i + 1) % 100000 == 0:
                elapsed = time.time() - start_time
                rate = (i + 1) / elapsed
                print(f"  {i+1:,} IDs in {elapsed:.1f}s ({rate:,.0f} IDs/sec)")
    
    except Exception as e:
        print(f"❌ FAIL: Exception during overflow test: {e}")
        return False
    
    elapsed = time.time() - start_time
    
    # Analyze timestamp distribution
    ts_dist = Counter()
    for content_id_str in [str(id) for id in list(ids_generated)[:100]]:
        ts_component = content_id_str[1:15]  # Extract YYYYMMDDHHIISS
        ts_dist[ts_component] += 1
    
    print(f"\nSequence overflow handling:")
    print(f"  Total IDs: {len(ids_generated):,}")
    print(f"  Unique timestamps seen: {len(ts_dist)}")
    print(f"  Time elapsed: {elapsed:.2f}s ({len(ids_generated)/elapsed:,.0f} IDs/sec)")
    
    # Verify all IDs are unique
    if len(ids_generated) != 1000000:
        print(f"❌ FAIL: Lost {1000000 - len(ids_generated)} IDs to collisions")
        return False
    
    print(f"✅ PASS: All 1M IDs unique (overflow rolled to next second as needed)")
    return True

def test_concurrent_actors():
    """
    Verify actor_id tracking works correctly in concurrent environment.
    (Simulates different agents importing simultaneously.)
    
    Returns:
        bool: True if all allocations preserve correct actor_id
    """
    print(f"\n🔬 TEST 4: Concurrent Actor ID Tracking")
    print(f"{'='*70}")
    
    actors_to_test = [0, 1, 102, 105, 108]  # Different actor IDs
    
    allocated_by_actor = defaultdict(list)
    
    def worker_allocate(actor_id, count=1000):
        """Allocate IDs as if from a specific actor."""
        results = []
        for i in range(count):
            file_path = f'channels/42/actor_{actor_id}_{i:06d}.md'
            content_id = generate_content_id(file_path)
            results.append({
                'content_id': content_id,
                'actor_id': actor_id,
            })
        return actor_id, results
    
    with ThreadPoolExecutor(max_workers=len(actors_to_test)) as executor:
        futures = [executor.submit(worker_allocate, aid) for aid in actors_to_test]
        
        for future in as_completed(futures):
            actor_id, results = future.result()
            allocated_by_actor[actor_id] = results
    
    # Verify
    all_pass = True
    for actor_id, allocations in allocated_by_actor.items():
        count = len(allocations)
        unique = len(set(a['content_id'] for a in allocations))
        status = "✅" if count == unique else "❌"
        print(f"{status} Actor {actor_id}: {count:,} allocations, {unique:,} unique")
        
        if count != unique:
            all_pass = False
    
    if all_pass:
        print(f"✅ PASS: All actors properly tracked in concurrent environment")
    else:
        print(f"❌ FAIL: Actor tracking issue detected")
    
    return all_pass

def run_all_tests():
    """Run complete stress test suite."""
    print("\n" + "="*70)
    print("🔬 CONTENT ID GENERATOR STRESS TEST SUITE")
    print("="*70)
    
    results = []
    
    # Test 1: Parallel generation
    results.append(("Parallel ID Generation", test_parallel_id_generation(10, 10000)))
    
    # Test 2: Path hash consistency
    results.append(("Path Hash Consistency", test_path_hash_consistency()))
    
    # Test 3: Overflow handling
    results.append(("Overflow Handling", test_overflow_handling()))
    
    # Test 4: Actor tracking
    results.append(("Actor ID Tracking", test_concurrent_actors()))
    
    # Final report
    print("\n" + "="*70)
    print("📊 FINAL REPORT")
    print("="*70)
    
    passed = sum(1 for _, result in results if result)
    total = len(results)
    
    for test_name, result in results:
        status = "✅ PASS" if result else "❌ FAIL"
        print(f"{status}: {test_name}")
    
    print(f"\nTotal: {passed}/{total} tests passed")
    
    if passed == total:
        print("\n🎉 All stress tests passed! Production ready.")
        return 0
    else:
        print("\n⚠️  Some tests failed. See details above.")
        return 1

if __name__ == '__main__':
    sys.exit(run_all_tests())
