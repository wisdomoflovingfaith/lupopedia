---
lupopedia.headers:
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/PENDING_ARTIFACTS_INDEX.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/PENDING_ARTIFACTS_INDEX.md"
  last_modified_utc: "20260326"
  channel_id: 42
  actor_id: 2
  actor_name: "LILITH"
  faucet_name: "cursor"
  delegation_chain: "wolfie:lilith"
  artifact_type: "doctrine"
  artifact_kind: "index"
  purpose: "Pending artifacts index with ownership and phase tracking for 4.1.0 release"
  tags: ["doctrine", "index", "pending", "4.1.0", "lilith"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/APPROVED_ARTIFACTS_INDEX.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/channels/appendix/HISTORY.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260326"
  last_verified_by_actor_id: 2
  last_verified_by_actor_name: "LILITH"
---

# LILITH'S PLAN — CLEARING THE PENDING ARTIFACTS

## 🔥 THE REALITY

Twelve pending artifacts. Four owners. Three phases. One external gate that actually matters.

**This is manageable. Here's how.**

## 🗡️ THE SEQUENCE

```
PHASE 1 (Installability Baseline) — WOLFIE + ATHENA + THOTH
│
├── ATHENA: Core System Requirements
├── ATHENA: System Architecture  
├── ATHENA: Deployment Model
├── THOTH: Database Constraints
└── WOLFIE: Hosting Constraints
│
▼
PHASE 2 (Auto-Installer Readiness) — WOLFIE (with ATHENA support)
│
├── WOLFIE: Web Interface Requirements
├── WOLFIE: Identity Actor Faucet Auth System
├── WOLFIE: Channel Collection Context Model
├── WOLFIE: Federation Content Ingestion Model
└── WOLFIE: Softaculous Checklist (primary gate)
│
▼
PHASE 3 (Secondary Confirmations) — WOLFIE
│
├── WOLFIE: Installatron Checklist
└── WOLFIE: Fantastico Checklist
│
▼
4.1.0 RELEASE
```

## 📋 PHASE 1 — INSTALLABILITY BASELINE

**Goal**: Prove system installs cleanly and meets doctrine constraints.

| Artifact | Owner | Status | Evidence Required | Est. Effort |
|----------|-------|--------|-------------------|------------------|
| Core System Requirements | ATHENA | approved | Validation matrix documented | 2-4 hours |
| System Architecture | ATHENA | approved | No experimental surfaces leak confirmed | 1-2 hours |
| Deployment Model | ATHENA | approved | Shared-hosting simulation completed | 2-3 hours |
| Database Constraints | THOTH | **BLOCKED** | AUTO_INCREMENT conflicts with deterministic-ID doctrine | 2-3 hours |
| Hosting Constraints | WOLFIE | approved | Shared-hosting compatibility documented | 2-3 hours |

**Phase 1 Success Criteria:**
- All five artifacts moved from `pending` to `approved` 
- Evidence documented in each artifact
- No release-blocking issues found

**Estimated total effort: 9-15 hours**

## 📋 PHASE 2 — AUTO-INSTALLER READINESS

**Goal**: Prove system is ready for Softaculous submission.

| Artifact | Owner | Status | Evidence Required | Est. Effort |
|----------|-------|--------|-------------------|------------------|
| Web Interface Requirements | WOLFIE | pending | Run UI baseline verification | 2-3 hours |
| Identity Actor Faucet Auth System | WOLFIE | pending | Run actor-resolution and permission tests | 3-4 hours |
| Channel Collection Context Model | WOLFIE | pending | Validate content_id projection and reconciliation | 3-4 hours |
| Federation Content Ingestion Model | WOLFIE | pending | Validate import + livehelp_js ingestion paths | 3-4 hours |
| Softaculous Checklist | WOLFIE | pending | Run internal preflight, submit package | Ongoing |

**Phase 2 Success Criteria:**
- Web Interface, Identity, Channel, Federation artifacts moved to `approved` 
- Softaculous checklist submitted and awaiting feedback

**Estimated effort: 11-15 hours**

## 📋 PHASE 3 — SECONDARY CONFIRMATIONS

**Goal**: Confirm acceptance across remaining auto-installers.

| Artifact | Owner | Status | Evidence Required | Est. Effort |
|----------|-------|--------|-------------------|------------------|
| Installatron Checklist | WOLFIE | pending | Run preflight, submit after Softaculous acceptance | 2-3 hours |
| Fantastico Checklist | WOLFIE | pending | Run preflight, submit after Softaculous acceptance | 2-3 hours |

**Phase 3 Success Criteria:**
- Both checklists moved to `approved` (or waived with rationale)

**Estimated effort: 4-6 hours**

---

## 🗡️ EXECUTION DIRECTIVE

**Start Phase 1 immediately. ATHENA and THOTH handle architecture/database artifacts. WOLFIE handles hosting constraints.**

**Phase 1 Execution Order:**
1. **THOTH: Database Constraints** — Verify install SQL
2. **ATHENA: System Architecture** — Review vs 4.0.88
3. **ATHENA: Core System Requirements** — Map requirements to testable checks
4. **ATHENA: Deployment Model** — Run shared-hosting simulation
5. **WOLFIE: Hosting Constraints** — Document shared-hosting compatibility

**After Phase 1 completes:**
- Update this index with new statuses
- Move approved artifacts to `APPROVED_ARTIFACTS_INDEX.md`
- Report completion to channel

---

*Twelve pending artifacts. Four owners. Three phases. One external gate.*

**The pending index now has owners and estimated phases. That's a plan, not a wishlist.**

---

*Last updated: 2026-03-26 (v4.1.0 planning)*  
*Maintained by: LILITH (actor_id 2) through cursor faucet*
