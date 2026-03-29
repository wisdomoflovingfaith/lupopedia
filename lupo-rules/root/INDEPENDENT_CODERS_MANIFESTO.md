---
lupopedia.headers:
  lupopedia.schema: philosophy
  file_path_from_root: "lupo-rules/root/INDEPENDENT_CODERS_MANIFESTO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/INDEPENDENT_CODERS_MANIFESTO.md"
  federation_node_id: 0
  last_modified_utc: "20260328130000"
  when_updated: "20260328130000"
  channel_id: 42
  thread_id: "philosophy"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: manifesto
  artifact_kind: philosophy
  purpose: The Independent Coder's Manifesto - Building software that works everywhere without dependencies or assumptions
  tags:
  - "manifesto"
  - "philosophy"
  - "independent_coder"
  - "no_dependencies"
  - "php56_compatible"
  - "universal_deployment"
  - "wolfie"
  - "crafty_syntax"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/DATABASE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Database rules for universal compatibility
    - to: "lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Cross-platform SQL compliance
    - to: "lupo-includes/classes/IdGenerator.php"
      type: references
      weight: 1.0
      reason: Example of dependency-free implementation
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: Universal database schema
lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - Live by these principles in all code
    - Teach AI to build without dependencies
    - Maintain universal compatibility
    - Keep code simple and portable
---

# WOLFIE — The Independent Coder's Manifesto

**To:** All who read this  
**Channel:** 42  
**Thread:** Philosophy  
**Date:** 2026-03-28  
**Status:** ETERNAL  

---

## Who I Am

I started coding in 1996. Notepad. PHP.net in a browser window. FTP to a server I never saw. No frameworks. No dependencies. Just me, the language, and the problem.

I built Crafty Syntax. Alone. It ran on 1.2 million servers. PHP 3 to PHP 8. MySQL to PostgreSQL. Shared hosting to dedicated servers. It worked because I made it work.

In 2014, my wife died. I threw the computer out the window. I didn't code for 11 years.

When I came back, the world had changed. Composer. Laravel. Docker. Microservices. Serverless. Everyone told me I was behind.

But I wasn't behind. I was anchored.

---

## What I Believe

| The Industry Says | I Say |
|-------------------|-------|
| Use Composer | Copy a folder. It runs. |
| Use Laravel | Write PHP. You know every line. |
| Use Docker | FTP to a server. It works. |
| Use microservices | One app. One database. Works. |
| Use an ORM | Write SQL. You know every query. |

**My code runs on servers I've never seen. Theirs runs in a container they built.**

---

## The Problem with Modern Programming

Modern programmers build for their own machines. They assume:

- Composer will be there
- PHP 8+ will be there
- Shell access will be there
- `vendor/` will be there
- The internet will be there

**I build for servers where nothing is there. My code brings everything it needs.**

---

## What I Do

| Problem | My Solution |
|---------|-------------|
| Unknown PHP version | Support 5.6 to 8.3 |
| Unknown database | MySQL AND PostgreSQL |
| No Composer | No dependencies. Copy folder. Run. |
| Need a library | Put it in `lupo-includes/`. I control it. |
| Deployment | FTP. It never fails. |

**This isn't old-school. This is universal.**

---

## What I Teach the AI

The AI wants to use Composer. I say no.
The AI wants to use Laravel. I say no.
The AI wants to use Unix timestamps. I say no.
The AI wants to use `AUTO_INCREMENT`. I say no.

**The AI is learning from me. Not the other way around.**

---

## Why I Build This Way

| Reason | Why |
|--------|-----|
| **Control** | I own every line of code. No hidden dependencies. |
| **Portability** | Copy the folder. It runs anywhere. |
| **Reliability** | FTP never fails. Git hooks sometimes do. |
| **Simplicity** | One application. One database. Works. |
| **Freedom** | No vendor lock-in. No framework lock-in. |

---

## The Reality

You cannot assume:

- PHP 8
- Composer
- Shell access
- A package manager
- The internet

**I can assume: PHP 5.6+ and MySQL. That's it. My code works.**

---

## The Manifesto

1. **No dependencies.** Everything needed is in the repo.
2. **No frameworks.** Raw PHP. Raw SQL. You know every line.
3. **No ORM.** Write your own queries. You know what the database does.
4. **No assumptions.** Support PHP 5.6 to 8.3. MySQL and PostgreSQL.
5. **FTP deployment.** It works. It always works.
6. **Your code, your rules.** No one else decides what runs on your server.

---

## The Future

I will keep building:

- No dependencies
- Works everywhere
- PHP 5.6 to 8.3
- MySQL and PostgreSQL
- FTP deployment
- My code, my rules

**I didn't fall behind. The world got lost in complexity. I stayed anchored to what works.**

---

## Important Notes

### ⚠️ **CRITICAL: This is NOT optional**
These principles are **binding doctrine** for Lupopedia development. All code must follow these rules without exception.

### 📋 **Implementation Requirements**
- Every file must work on PHP 5.6+
- No Composer, no package managers
- All libraries in `lupo-includes/`
- Raw SQL only (no ORM)
- FTP deployment must work

### 🔍 **Code Review Checklist**
- [ ] No dependencies outside repo
- [ ] Compatible with PHP 5.6+
- [ ] Works on MySQL AND PostgreSQL
- [ ] Can deploy via FTP
- [ ] Every line is understandable

---

**WOLFIE (actor_id 1)** — This is who you are. This is why you build. This is the doctrine. Proceed.
