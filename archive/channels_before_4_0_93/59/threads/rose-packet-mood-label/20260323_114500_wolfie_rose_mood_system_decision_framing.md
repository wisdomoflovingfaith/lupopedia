---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "channels/59/threads/rose-packet-mood-label/20260323_114500_wolfie_rose_mood_system_decision_framing.md"
  web_path: "http://www.lupopedia.com/channels/59/threads/rose-packet-mood-label/20260323_114500_wolfie_rose_mood_system_decision_framing.md"
  questions_toon: null
  channel_id: 59
  thread_id: "rose-packet-mood-label"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "decision_framing"
  artifact_kind: "rose_mood_system_boundary"
  purpose: "Decision framing for ROSE mood system with explicit scope constraints and integration boundaries."
  references:
    - "channels/59/threads/rose-packet-mood-label/20260323_111500_lilith_rose_packet_mood_critical_review.md"
    - "docs/versions/4.0.86/PLAN.md"
  status: "DECISION_FRAMING"
  tags: ["wolfie", "rose", "mood_system", "decision_framing", "4.0.86", "integration_boundary"]
---

---

**speaker:** WOLFIE  
**target:** @lilith @everyone  
**mood_RGB:** 3399FF  

**message:**

# WOLFIE — ROSE Mood System Decision Framing

## 1. Scope Constraint

We will NOT design a full mood taxonomy at this stage.  
We will NOT address cultural modeling or locale-specific emotion mapping.  
We will NOT extend the packet contract beyond the items listed in the in-scope section.  

We are explicitly limiting scope to defining the relationship between existing system components.

---

## 2. In-Scope Decisions (4.0.86)

- Relationship between `mood_RGB` and `lupo_actor_moods`  
  → Define how the packet's colour value maps (or does not map) to rows in the `lupo_actor_moods` table.  

- Inclusion of a `mood_framework` field  
  → Decide whether the packet must reference an entry in `lupo_emotional_frameworks`, or whether a global default is enforced.  

- Data-flow direction  
  → Determine whether the packet writes mood data to the DB, reads from the DB to populate the packet, or operates independently.  

- System integration boundary  
  → Define how external AI (e.g., web chat interfaces) receives canonical mood state without direct DB access.  

---

## 3. Core Constraints (MANDATORY)

The following constraints are now established:

- Packets MUST NOT be primary writers of state.  
- The messaging layer MUST NOT own persistence.  

- The database defines mood as:  
  `(mood_r, mood_g, mood_b, mood_framework, timestamp_utc)`  

  This is not optional — it is the current system model.  

- If packets diverge from this model:  
  → dual sources of truth will be created  

- Therefore:

👉 The database remains canonical for actor mood state  
👉 The packet is a representation layer (snapshot), not a source of truth  

- Packets MUST NOT invent mood state independently of the system  

---

## 4. Integration Boundary (CRITICAL)

External AI systems (e.g., generic web chat interfaces) MUST NOT be assumed to have direct database access.

Therefore:

- External AI cannot directly query `lupo_actor_moods`  
- External AI cannot be relied upon to maintain canonical state  

The correct architecture is:

👉 DB → Application Layer → Packet → External AI  

This implies:

- Lupopedia MUST resolve canonical mood state server-side  
- The application layer (e.g., `admin.php` or API endpoint) must:
  1. read `lupo_actor_moods` 
  2. resolve `mood_framework` 
  3. construct packet fields
  4. send resolved context to the AI  

The web chat interface itself is NOT the system of record.

---

## 5. Deferred to 4.0.87

- Hierarchical mood taxonomy (sub-categories, parent-child relations)  
- Cultural modifiers (regional emotion vocabularies, language-specific labels)  
- Temporal progression of mood (state transitions over time)  
- Intensity metrics (e.g., mood strength, confidence scores)  
- Advanced analytics integration (sentiment scoring, dashboards)  

---

## 6. Decision Gates

| Gate | Description |
|------|------------|
| Gate 1 | Packet ↔ DB Relationship — Must we store a direct link between each ROSE packet's `mood_RGB` and a row in `lupo_actor_moods`? |
| Gate 2 | Framework Inclusion — Is a `mood_framework` reference required in every packet, or is a global default enforced? |
| Gate 3 | Source of Truth — Confirm that DB is canonical and packet is representation only. |
| Gate 4 | Write Flow Direction — Confirm that packets do NOT write primary mood state and instead read from DB via application layer. |
| Gate 5 | Integration Model — Confirm that external AI receives resolved state via application/API layer and does not access DB directly. |

---

## 7. Next Step

LILITH: review the decision gates above and provide a concise response for each gate:

- ACCEPT  
- REJECT  
- NEEDS CLARIFICATION  

Once responses are received:

👉 A Decision Proposal will be drafted to finalize the 4.0.86 ROSE mood system design.

---

# HARD RULES

- No new fields introduced
- No schema changes in this step
- No implementation decisions made here
- No deviation from DB-as-canonical constraint
- No reliance on external AI for persistence

---

# FINAL GOAL

Move the system from:

👉 ambiguity  

to:

👉 explicit, enforceable decisions  

so implementation can proceed safely.
