# Lupopedia 4.1.6 Minimum Version Target

## Context

- Funding ends June 1, 2026.
- Captain is one person operating with AI actors.
- Goal is sustainable completion, not maximal scope.
- 1100% mode is prohibited during the minimum-version push.

Reference: docs/captains_log/captain_wake_sleep_log.md

---

## Minimum Done Means

The following MUST work by June 1.

### Install / Local Run

- Fresh install runs from install_new_lupopedia.sql without manual intervention.
- Config loads, DB connects, session starts.
- Basic probe / health check returns OK.

### Channels -- Basic Operation

- channels/index.php loads and renders the one-column feed.
- Messages can be posted and retrieved (actor to actor, basic routing).
- HERMES routing semantics operational (from_actor, to_actor, channel_key, kapakai, pono).
- Dual endpoints schema in place (from_session_id, to_session_id per PRD 02).
- Active target bar functional.

### PRD / Header Doctrine -- Stable

- All PRD files carry valid 4.1.6 headers (22 fields, locked format).
- prd_cluster fields are present and correct.
- Header validator runs without constitutional violations.
- No content_slug regressions.

### Hermes Routing Semantics -- Stable

- lupopedia.hermes block fields enforced: kapakai, pono, ohana, kapu, kuleana, alii, kumu.
- Semantic model locked. No drift.
- Translation guide in place.

### Captain Log / WHY Files -- Usable

- docs/captains_log/ readable and writable.
- docs/why/ WHY files generated on doctrine violations.
- WHY file naming convention followed (why_YYYYMMDD_HHMMSS_cluster_slug.md).

### Validation Gates / Immune System -- Drift Prevention

- Header validator (PRD 86) catches hard-fail conditions before commit.
- Pre-commit or checkpoint script runs basic constitutional checks.
- Violations generate WHY files (PRD 98_A).
- Does NOT need to be perfect. Must be enough to prevent silent drift.

---

## Explicitly Deferred

These are NOT required by June 1.

- Full world-documentation import (Crafty Syntax content migration at scale).
- Complete CHIRON implementation (PRD 59_A -- doc ingest and doctrine conversion).
- Complete scheduler automation (PRD 60 -- Orchestrator Scheduler).
- Perfect UI polish or visual refinement.
- All agents fully implemented (DEANNA, BONES, etc. exist as files; runtime activation optional).
- Every Captain's Log entry migrated or registered in DB.
- Full semantic validator automation -- basic gate is enough.
- Federation multi-node sync (PRD 09) -- node 0 only.
- Mobile native app separation (PRD 35).
- Softaculous certification gate (PRD 33) -- post-4.1.6.
- CHIRON, ANUBIS, KAIROS automation pipelines.
- Analytics, API tokens, rate limiting (PRD 11, 12).
- Full content seeding from Crafty (PRD 85 full scope).

---

## Success Criteria

Observable checks for June 1:

- [ ] Fresh install completes without errors.
- [ ] channels/index.php renders one-column feed with real data.
- [ ] HERMES message routing posts and retrieves messages.
- [ ] Header validator passes on all canonical PRD files.
- [ ] WHY file generated on at least one simulated violation (smoke test).
- [ ] Install SQL includes dual endpoints (from_session_id, to_session_id).
- [ ] No AUTO_INCREMENT, no Unix epoch, no bare id columns in schema.
- [ ] Captain can log a session and retrieve it.

---

## Daily Work Rule

Captain must prioritize sustained output over maximum output.

1100% mode is prohibited during the minimum-version push.

Work one lane at a time. Finish before starting the next.

PRD first. Schema second. Mockup third. Code last.

---

## Health / Capacity Note

Reference: docs/captains_log/captain_wake_sleep_log.md
Reference: docs/captains_log/health_note_april_2026.md

If repeated pass-out events continue, workload must be reduced and/or paused.

The system cannot be finished by June 1 if the Captain goes down.

Sustained 8-10 hour days will outperform 16-hour crash cycles over 5 weeks.

No medical diagnosis. See BONES or a real doctor for health concerns.

---

## Version Scope Lock

This document defines the ceiling for 4.1.6 scope.

Any new feature requests that are not in "Minimum Done Means" above are automatically DEFERRED.

No exceptions without Captain review and explicit approval.
