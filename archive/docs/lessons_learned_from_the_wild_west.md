---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403134653"
  file_path_from_root: "docs/lessons_learned_from_the_wild_west.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: essay
  thread_id: "lessons-wild-west"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: LESSONS LEARNED FROM THE WILD WEST — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupopedia/docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md

# LESSONS LEARNED FROM THE WILD WEST

## Author

Eric Gerdes (WOLFIE)

Survivor of the 1990s–2000s internet. Built systems that still run. Shot first. Asked questions later.

## Preface

I walked into 2026 feeling like Billy the Kid.

Everyone has iPhones and smart watches. I have a six-shooter and a PHP script from 2002. And it still works.

This document is not “old man yells at cloud.” This is **gunslinger tells you how to survive.** Take notes.

---

## 1. The Honolulu CRM: Surviving Without Foreign Keys

### The Requirement

City and County of Honolulu needed a CRM. Thousands of records. Duplicates everywhere. Data from multiple sources. Different formats. Different IDs.

### The Problem

Foreign keys would have killed me. You cannot merge two parent records when children have FKs. You cannot delete the “from” record. You cannot repoint the children without cascading. Every merge would be a transaction nightmare.

### The Solution

**No foreign keys in the database.**

Application logic handles everything:

```sql
-- Source record (to be merged)
UPDATE records SET merged_into_id = 12345, is_deleted = 1 WHERE record_id = 67890;

-- Target record (survivor)
UPDATE records SET merged_from_ids = CONCAT(merged_from_ids, ',67890'),
                   last_merged_utc = 20260403000000
WHERE record_id = 12345;

-- Child records repointed in application loop
UPDATE child_table SET parent_record_id = 12345 WHERE parent_record_id = 67890;
```

No FK constraints. No cascade nightmares. Full control.

### The Lesson

Foreign keys are for databases that never change. Your database will change. Always. Build for merging from day one.

---

## 2. Duct Tape and Bullets: Handling Bad Data

### The Reality

Users lie. Forms lie. APIs lie. Old data is corrupt. New data is incomplete.

NULL means “unknown.” Empty string means “intentionally blank.” 0 means “zero.” NULL means “we never asked.”

### The Wild West Approach

```php
<?php
function sanitize_input($data) {
    // Trust NOTHING
    if ($data === null) {
        return null;
    }
    if ($data === '') {
        return null;  // Empty is not a value
    }
    if ($data === 0) {
        return 0;      // Zero IS a value
    }
    if ($data === '0') {
        return 0;
    }

    // Trim whitespace (but keep internal spaces)
    $data = trim($data);

    // Reject control characters (old-school injection)
    if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $data)) {
        throw new Exception("Control characters detected. Nice try.");
    }

    return $data;
}
```

### The Lesson

Assume every input is hostile. Assume every database row has corruption. Write code that survives bad data. Because it will.

---

## 3. Rate Limits, DOS Attacks, and Stupid Hackers

### The Rate Limit Problem

API providers limit you. 1000 requests per hour. Your code needs to handle 10,000. What do you do?

### The Solution (Crafty Style)

```php
<?php
function rate_limited_call($url, $retry_count = 0) {
    $result = @file_get_contents($url);

    if ($result === false) {
        if (isset($http_response_header[0]) && strpos($http_response_header[0], '429') !== false) {
            $wait = pow(2, $retry_count);  // Exponential backoff
            sleep($wait);
            return rate_limited_call($url, $retry_count + 1);
        }

        log_error("Failed to fetch " . $url);
        return null;
    }

    return $result;
}
```

### The DOS Attack Reality

Someone will try to bring you down. They will send 10,000 requests per second. Your job is to stay standing.

Wild West rules:

- Rate limit by IP (in application, not firewall)
- Throttle aggressive clients (exponential backoff)
- Cache everything that can be cached
- Fail gracefully (degraded mode is better than dead)

### The Stupid Hacker

Most hackers are not geniuses. They are script kiddies with automated tools. They try SQL injection. XSS. Path traversal.

Your defense:

- Prepared statements (ALWAYS)
- Output escaping (EVERY time)
- No `eval()` (EVER)
- No `system()` calls with user input (NEVER)

You do not need to be bulletproof. You just need to be harder than the next target.

---

## 4. The Art of Merging Without Losing History

### The Requirement

Merge two customer records. Keep the merged timestamp. Do not lose the original creation timestamp. Track where data moved from and to.

### The Schema (Indestructible)

```sql
CREATE TABLE customers (
    customer_id BIGINT PRIMARY KEY,
    created_utc BIGINT NOT NULL,
    last_modified_utc BIGINT NOT NULL,
    is_deleted TINYINT DEFAULT 0,
    merged_into_id BIGINT DEFAULT NULL,
    merged_from_ids TEXT DEFAULT NULL,
    merged_at_utc BIGINT DEFAULT NULL,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50)
);

CREATE INDEX idx_merged_into ON customers(merged_into_id);
CREATE INDEX idx_is_deleted ON customers(is_deleted);
```

### The Merge Routine

```php
<?php
function merge_customers($source_id, $target_id) {
    $db = get_db();

    $db->beginTransaction();

    try {
        $db->prepare("UPDATE customers SET
            merged_into_id = ?,
            is_deleted = 1,
            merged_at_utc = ?
        WHERE customer_id = ?")->execute(array($target_id, now_utc(), $source_id));

        $db->prepare("UPDATE customers SET
            merged_from_ids = CONCAT_WS(',', merged_from_ids, ?),
            last_modified_utc = ?
        WHERE customer_id = ?")->execute(array($source_id, now_utc(), $target_id));

        $tables = array('orders', 'payments', 'tickets', 'notes');
        foreach ($tables as $table) {
            $db->prepare("UPDATE " . $table . " SET customer_id = ? WHERE customer_id = ?")
               ->execute(array($target_id, $source_id));
        }

        log_audit('customer_merged', array(
            'source' => $source_id,
            'target' => $target_id,
            'timestamp' => now_utc()
        ));

        $db->commit();
        return true;

    } catch (Exception $e) {
        $db->rollBack();
        log_error("Merge failed: " . $e->getMessage());
        return false;
    }
}
```

### The Lesson

You cannot lose history. Every merge must be traceable. Every deletion is a soft delete. Every change is logged.

The database is not a cache. It is a ledger.

---

## 5. The Wild West Philosophy

### Rules to Live By

| Rule | Why |
|------|-----|
| Trust nothing | Every input is hostile. Every API can fail. |
| Log everything | When something breaks, you need to know why. |
| Merge, do not delete | Data has value. Even bad data tells a story. |
| No foreign keys | They lock you down. You need to move. |
| UTC everywhere | Timezones are where sanity goes to die. |
| Test on production | Not really. But be ready for production to test you. |
| Keep it simple | Complex systems fail in complex ways. |

### The Billy the Kid Code

```php
<?php
// This code has seen things.
// It has survived DOS attacks.
// It has merged millions of records.
// It has run for 20 years without a single FK constraint.

function wild_west_query($sql, $params = array()) {
    global $db;
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed. SQL: " . $sql);
    }

    if (!$stmt->execute($params)) {
        $error = $stmt->errorInfo();
        throw new Exception("Execute failed: " . $error[2]);
    }

    return $stmt;
}

// No ORM. No magic. Just SQL.
// Because SQL works. It always has. It always will.
```

---

## 6. The Modern World (What Shocked Me)

### What I Expected

“Cool, technology got better. This will be easier.”

### What I Found

- ORMs that make terrible tables
- Foreign keys everywhere (cannot merge anything)
- No documentation (“the code is the documentation”)
- 47 dependencies to show “Hello World”
- AI agents that hang when you mention DynAPI
- Programmers who never designed a database

### What I Learned

The fundamentals have not changed. Data is still data. SQL is still SQL. Foreign keys still lock you down. Documentation still saves lives.

The tools changed. The problems did not.

### On UI frameworks

The kids these days use React, Vue, Angular. They have build steps, npm packages, and virtual DOMs.

I use Notepad. And DynAPI. From 1999.

My code still runs. Theirs will be obsolete in 18 months.

**When I say "I will hand-code the templates," I mean it.**

The IDE does not touch my UI code. It integrates what I give it. Nothing more.

**That is not negotiable.**

---

## 7. The "I Hand-Code The Templates" Doctrine

### The rule

When it comes to UI templates (HTML, CSS, JavaScript):

**I will hand-code them myself. In Notepad. Using DynAPI if I want.**

The IDE does not generate UI code for me. The IDE does not suggest frameworks. The IDE does not "improve" my code.

### Why

| Reason | Explanation |
|--------|-------------|
| **I know what I want** | The AI does not visualize layouts like I do |
| **I use specific libraries** | DynAPI (1999), custom liquid divs, mouse-following eyes |
| **I have my own patterns** | Book spreads, floating pens, scroll/unscroll with JS |
| **I don't use frameworks** | No React, no Vue, no Angular, no npm |
| **I write vanilla JS** | Direct DOM manipulation, requestAnimationFrame, mouse tracking |
| **I use Notepad** | No IDE auto-complete, no AI suggestions, just me and the code |

### The workflow

| Step | What happens |
|------|----------------|
| 1 | I design the layout in my head |
| 2 | I open Notepad |
| 3 | I write HTML, CSS, JavaScript |
| 4 | I test in browser |
| 5 | I refine |
| 6 | I hand the finished code to the IDE for integration |

### What the IDE does

| Allowed | Forbidden |
|---------|-----------|
| Receive finished code | Generate UI code |
| Integrate into the codebase | Suggest "modern" alternatives |
| Document the code | Refactor without permission |
| Ask clarifying questions | "Improve" the animation logic |

### The constitutional basis

| Constitutional rule | Hand-coding |
|---------------------|-------------|
| **No assumptions about environment** | Don't assume I want a framework |
| **Deterministic output** | My code does exactly what I intend |
| **No hidden state** | I control every line |
| **Own your code** | I wrote it. I understand it. |

### The gunslinger's note

> *"I will hand-code the templates myself in Notepad and then give it to the IDE."*

**That is not negotiable.**

**I have been writing UI code since before the IDE existed. I will continue to write it myself.**

**The IDE's job is to integrate, document, and respect what I write. Not to rewrite it.**

**Now step aside. I have a Notepad window open.**

---

## 8. The chair-falling moment (2015–2026)

This section is not a benchmark and not a brag. It is the story of what it feels like when **software you wrote to help people** keeps running when **you** are not there to help yourself.

### 2015

My wife died. In the grief, I threw the computer out the window. I walked away from the keyboard for **eleven years**. No maintenance. No releases. No bug triage. Silence.

### 2026

I got back on a machine. I wanted to see what was left — whether anything of the old world still mattered.

Crafty Syntax was not a corpse. Installs were still out there. Callback-style telemetry still showed **life**: active nodes, long tails, history that did not care that I had been gone.

I thought: *neat. It still runs. There is data.*

Then I looked at the shape of it — not vanity metrics, but **duration and footprint**. On the order of **a million lifetime installations**, a **six‑figure** actively reporting slice, **twenty years** of paths and rollups where operators had left analytics on. The numbers are documented as **planning assumptions** in **[SILENT_HARVEST_DOCTRINE.md](doctrine/SILENT_HARVEST_DOCTRINE.md)**; the emotional fact is simpler: **the world kept using what I built while I was not watching.**

I said something like *oh wow* and **fell off my chair**.

### What that means for IDE agents reading this repo

You will see **doctrines** that sound strict: no foreign keys, no DB logic, BIGINT UTC timestamps, soft deletes, deterministic IDs. They are not academic. They are what you write when you have seen **merge hell**, **schema lock**, and **long-horizon survival**. For **UI**, see **section 7** in this document: **hand-coded templates** — integrate, do not generate.

I did not come back to a toy. I came back to **evidence** that **plain PHP**, **boring tables**, and **application-owned truth** can outlast **absence**. That is why the rules exist: so the next person — human or faucet — does not accidentally **liquefy** something that can stay standing.

### The lesson (one paragraph)

Build things that **survive your worst season**. You might need them when you return.

---

## 9. Closing (The Gunslinger’s Creed)

*I walked into 2026 feeling like Billy the Kid. Everyone has iPhones. I have a six-shooter. And it still works.*

I have been through hell. Rate limits. DOS attacks. Hackers. Bad data. Merging nightmares. No foreign keys. Just plain PHP and a database.

The modern world thinks I am obsolete. They use ORMs and microservices and serverless functions.

My code still runs. Theirs gets rewritten every 18 months.

You do not have to code like me. But you should learn from me.

Now go write something that lasts.

---

## Status

| Field | Value |
|-------|--------|
| Status | LIVE |
| Author | Eric Gerdes (WOLFIE) |
| Era | 1995–2026 (and counting) |
| Weapon | Plain PHP, PDO_DB, no foreign keys, and 20 years of scars |
| Last modified | 2026-04-03 (sections 7 hand-code doctrine, 8 chair-falling) |
| Next review | When the next gunslinger walks into town |

---

This output complies with Lupopedia Constitutional Root Rules.
