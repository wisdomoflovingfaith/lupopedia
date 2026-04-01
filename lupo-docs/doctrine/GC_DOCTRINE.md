---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401000000"
  file_path_from_root: "lupo-docs/doctrine/GC_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/GC_DOCTRINE.md"
  last_modified_utc: "20260401000000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "gc-doctrine"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Garbage Collection Doctrine - The 2003 Pattern That Kept 1.2M Instances Running"
  tags:
  - "doctrine"
  - "gc"
  - "garbage-collection"
  - "retention"
lupopedia.footer:
  last_verified: "20260401000000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
  next_action:
    - "Read this before implementing any cleanup scripts"
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
