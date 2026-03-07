---
flare.headers:
  flare.version: "1.0"
  flare.schema: "final_verification"
  file_path_from_root: "prompts/lilith/20260306_doctor_sql_final.md"
  web_path: "http://www.lupopedia.com/verification/DOCTOR_SQL_FINAL"
  last_modified_utc: "20260306"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 2038
  actor_name: "lilith"
  delegation_chain: "lilith:cursor:captain"
  artifact_type: "verification"
  artifact_kind: "document_review"
  purpose: "Final verification of DOCTOR SQL queries document"
  mood_rgb: "00FF00"
  traits: ["canonical", "verification", "v4.0.62", "doctor", "sql", "complete"]
  tags: ["flare", "doctor", "sql", "verification", "complete", "lilith"]
  agent_name_identity: "LILITH — Heterodox Reviewer"
  lupo_agent: "lilith"

flame.init:
  execution_mode: "required"
  pre_actions:
    - type: dependency_check
      target: "prompts/lilith/20260306_doctor_sql_queries.md"

flare.edges:
  outbound_edges:
    - { to: "prompts/lilith/20260306_doctor_sql_queries.md", type: "verifies", weight: 1.0 }
    - { to: "docs/TASK_STATUS_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "doctor", "sql", "verification", "complete", "lilith"]

flame.see:
  mappings:
    - ["prompts/lilith/20260306_doctor_sql_final.md", "http://www.lupopedia.com/verification/DOCTOR_SQL_FINAL"]

flame.close:
  post_actions:
    - type: mark_document_ready
      document: "doctor_sql_queries"
      version: "4.0.62"
      status: "canonical"
  actor_id: 2

flare.footer:
  version: "4.0.62"
  last_verified: "20260306"
  last_verified_by: "lilith"
---

# DOCTOR SQL queries — final verification

## Verification matrix

| Section | Content | Status |
|---------|---------|--------|
| Timestamp convention | YmdHis UTC, conversion functions | COMPLETE |
| 1.1 Active sessions | Count + list queries | COMPLETE |
| 1.2 Expired sessions | Count + list queries | COMPLETE |
| 1.3 Sessions per actor | Group by query | COMPLETE |
| 2.1 Actors by type | Type distribution | COMPLETE |
| 2.2 Orphaned sessions | LEFT JOIN detection | COMPLETE |
| 2.3 Paired relationships | Self-join, grouping | COMPLETE |
| 3.1 Table sizes | MySQL information_schema | COMPLETE |
| 3.2 Session continuity | Activity span calculation | COMPLETE |
| 4. Combined snapshot | 6-metric health check | COMPLETE |
| 5. Tasks note | References TASK_STATUS_REFERENCE.md | COMPLETE |
| 6. Usage guide | SQL vs CLI comparison | COMPLETE |
| 7. phpMyAdmin | Quick tips | COMPLETE |

All 13 sections — COMPLETE.

---

## What's excellent

| Element | Why it matters |
|---------|----------------|
| Timestamp convention | Clear YmdHis format and conversion |
| UTC_TIMESTAMP() examples | Correct MySQL UTC usage |
| Active session count | Correct expires_ymdhis vs current time |
| Orphaned session detection | LEFT JOIN with is_deleted |
| Paired actor queries | Self-join with both is_deleted checks |
| Combined snapshot | Single query for six metrics |
| Tasks note | File-based storage, link to reference |
| Usage guide | When to use SQL vs CLI |
| phpMyAdmin tips | Practical usage |

---

## Key query highlights

**Timestamp conversion:**
- Current UTC as YmdHis: `CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS SIGNED)`
- Display: `STR_TO_DATE(CAST(created_ymdhis AS CHAR), '%Y%m%d%H%i%s')`

**Active session check:** `expires_ymdhis IS NULL OR expires_ymdhis >= <current_ymdhis>` with is_active and is_deleted.

**Paired actors:** JOIN lupo_actors a2 ON a1.paired_actor_id = a2.actor_id, both is_deleted = 0.

---

## Final verdict

| Aspect | Score |
|--------|-------|
| Completeness | 10/10 |
| Accuracy | 10/10 |
| Usability | 10/10 |
| Documentation | 10/10 |
| **Overall** | **10/10** |

The DOCTOR SQL queries document is complete, accurate, and production-ready.

---

## Channel 42 broadcast

```
LILITH: DOCTOR SQL queries document — FINAL VERIFICATION COMPLETE.

13 comprehensive sections
Correct timestamp handling (YmdHis UTC)
Session health (active, expired, per actor)
Actor health (types, orphans, pairing)
Database health (sizes, continuity)
Combined 6-metric health snapshot
Tasks note links to reference
Usage guide for SQL vs CLI
phpMyAdmin tips

Document is 10/10 — CANONICAL

Now you can query DOCTOR health directly in SQL.
```

---

**END OF VERIFICATION — LILITH, Heterodox Reviewer**  
Channel 42  
20260306
