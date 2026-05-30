---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/GC_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/GC_DOCTRINE.md"
  status: ""
  when_updated: "20260401000000"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_id: "gc-doctrine"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# Garbage Collection Doctrine

## The Core Principle

**Spread the load. Never spike. Always have a fallback.**

The 2003 `gc.php` pattern proved that a system can run for 10 years without its architect if garbage collection is:

1. **Randomized** — 1% chance per request spreads load
2. **Self-limiting** — stops after N deletions per run
3. **Multi-tier** — raw → daily → monthly → yearly
4. **Soft-delete aware** — never hard delete without archiving

## Why This Works

| Problem | 2003 Solution | Modern "Solution" |
|---------|---------------|-------------------|
| Server spikes | Random execution | Cron at midnight (spike) |
| Table locks | LIMIT 10000 per run | DELETE all (lock table) |
| Data loss | Archive to monthly tables | Delete permanently |
| Performance | OPTIMIZE after cleanup | Never optimize |
| Shared hosting | Works everywhere | Requires cron access |

## The Pattern

```php
// Called on every request
function maybe_run_gc() {
    // 1% chance
    if (rand(1, 100) != 7) return;
    
    $deleted = 0;
    $max = 10000;
    
    // Delete in batches
    $sql = "DELETE FROM table WHERE condition LIMIT " . ($max - $deleted);
    $deleted += execute($sql);
    
    if ($deleted > 0) {
        optimize_table();
    }
}
```

## Why This Belongs in Lupopedia

Your 2003 GC pattern is **proven at scale** (1.2M installations, 10 years unattended). It's not "legacy code" — it's **timeless architecture**.

The new GC system preserves:
- Random execution pattern
- Self-limiting batches
- Multi-tier aggregation
- Soft delete awareness
- Table optimization after cleanup

**If it worked for 22 years, don't "modernize" it. Modernize around it.**

---

**WOLFIE's Law:** *If it ran unattended for a decade, it's not legacy. It's proven.*
