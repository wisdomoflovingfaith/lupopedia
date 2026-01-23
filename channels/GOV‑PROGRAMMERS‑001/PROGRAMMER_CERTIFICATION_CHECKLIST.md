# PROGRAMMER CERTIFICATION CHECKLIST
Channel 3 — PROGRAMMERS OF LUPOPEDIA
Version 2026.1.0.3

This checklist verifies that a contributor meets the minimum engineering standards required to work on Lupopedia. All items must be satisfied before granting access to development channels, schema, migrations, or system‑level code.

## ✅ SECTION 1 — FOUNDATIONAL COMPUTER SCIENCE

### 1. Data Structures (must demonstrate understanding + implementation ability)
☐ Arrays

☐ Linked lists

☐ Stacks

☐ Queues

☐ Hash tables

☐ Trees (binary, balanced)

☐ Graphs

☐ Heaps / priority queues

**Pass condition**: Candidate can explain AND implement at least 6 of these without libraries.

### 2. Algorithms
☐ Sorting (quicksort, mergesort, heapsort)

☐ Searching (binary search, DFS, BFS)

☐ Recursion fundamentals

☐ Dynamic programming basics

☐ Graph traversal

☐ Big‑O complexity reasoning

**Pass condition**: Candidate can derive complexity without guessing.

### 3. Memory & Low‑Level Concepts
☐ Stack vs heap

☐ Pointers / references

☐ Memory allocation

☐ Garbage collection behavior

☐ Cache locality

☐ Call stack + recursion depth

**Pass condition**: Candidate can explain memory flow during a function call.

## ✅ SECTION 2 — SYSTEMS KNOWLEDGE

### 4. Operating Systems
☐ Processes vs threads

☐ Scheduling basics

☐ File systems

☐ Concurrency & race conditions

☐ Synchronization primitives

**Pass condition**: Candidate can explain a race condition and how to fix it.

### 5. Networking
☐ TCP vs UDP

☐ HTTP request lifecycle

☐ DNS resolution

☐ Latency & packet flow basics

**Pass condition**: Candidate can describe how a browser loads a webpage.

### 6. Database Fundamentals
☐ Relational schema design

☐ Normalization

☐ Indexing

☐ Transactions & ACID

☐ Query planning basics

**Pass condition**: Candidate can design a normalized table set for a simple domain.

## ✅ SECTION 3 — PROGRAMMING ABILITY

### 7. Framework‑Free Coding
☐ Can write working code without frameworks

☐ Can debug without AI assistance

☐ Can reason about architecture

☐ Can read and understand schema

☐ Can follow doctrine and invariants

**Pass condition**: Candidate completes a small coding task using only core language features.

### 8. Problem‑Solving & Reasoning
☐ Can break down a problem into steps

☐ Can explain decisions clearly

☐ Can identify edge cases

☐ Can reason about failure modes

**Pass condition**: Candidate demonstrates structured thinking, not guesswork.

## ✅ SECTION 4 — LUPOPEDIA‑SPECIFIC REQUIREMENTS

### 9. Understanding of Semantic OS Concepts
☐ Channels

☐ TOON files

☐ Doctrine

☐ Schema‑first design

☐ Deterministic behavior

☐ Multi‑agent orchestration

**Pass condition**: Candidate can explain why Lupopedia cannot tolerate framework‑only developers.

### 10. Governance & Safety
☐ Understands non‑negotiable doctrines

☐ Understands protected channels

☐ Understands version governance

☐ Understands machine‑editable sections

☐ Understands schema authority (TOON > SQL > code)

**Pass condition**: Candidate can articulate how drift is prevented.

## ✅ SECTION 5 — FINAL VERIFICATION

### 11. In‑Person Evaluation by System Architect
☐ Candidate interviewed by Eric Robin Gerdes

☐ Candidate passed fundamentals test

☐ Candidate demonstrated real programming ability

☐ Candidate demonstrated architectural reasoning

☐ Candidate approved for contribution

### 12. Database Doctrine Compliance (Lupopedia‑Specific)
Contributors must demonstrate full understanding and acceptance of Lupopedia Database Doctrine:

#### Prohibited at the Database Level
☐ NO foreign keys

☐ NO stored procedures

☐ NO stored functions

☐ NO triggers

☐ NO cascading deletes

☐ NO database‑enforced relationships

☐ NO computed/generated columns

☐ NO automatic timestamp fields

#### Required Approach
☐ All integrity, relationships, and logic must be implemented in application code, not in database

☐ Developer must understand why FK keys break:
- merging
- repairing
- orphan handling
- federation
- portability
- schema evolution

☐ Developer must understand that database is passive storage, not a logic engine

☐ Developer must be able to explain doctrine back in their own words

**Pass Condition**: Candidate demonstrates clear understanding of why Lupopedia uses a pure data store model and can articulate dangers of database‑level logic in a federated, merge‑heavy, doctrine‑driven system.

## 🟩 FINAL STATUS

### Final Status:
☐ CERTIFIED

☐ NOT CERTIFIED

---

## 🟧 Why Database Doctrine Matters

This doctrine is not a preference — it's a structural invariant of Lupopedia:

- You merge databases
- You repair orphaned rows  
- You reassign parents
- You collapse duplicates
- You run federation
- You run migrations
- You run doctrine‑driven schema evolution

Foreign keys, triggers, and stored logic destroy your ability to do any of that safely.

Anyone who doesn't understand this will break your system.

This checklist ensures they never get that chance.
