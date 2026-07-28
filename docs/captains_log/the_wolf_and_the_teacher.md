---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/captains_log/the_wolf_and_the_teacher.md
  web_path: https://www.lupopedia.com/lupopedia/docs/captains_log/the_wolf_and_the_teacher.md
  status: active
  when_updated: '20260607025259'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/captains_log/wolf-and-the-teacher
  artifact_type: document
  artifact_kind: captains_log
  channel_key: development
  federation_node_id: 0
  thread_key: wolf-dept0-learning-unified
  lupopedia.schema: document
  prd_cluster: 39_A-i_41_A-i_98_B-i
  title: 'Captain''s Log -- The Wolf and the Teacher'
  summary: 'WOLF Maintenance Commandments and Dept 0 learning mechanics unified field theory. Non-doctrinal narrative; zero constitutional authority.'
---
[narrative: Wolfie leans back, boots on the console. Ninety-nine yellow sticky notes glow in the monitor light. One note says "WOLF COMMANDMENTS -- READ." Another says "DEPT 0 LEARNING -- PONO?"]

<< set_mood("reflective") >>
<< internal("two threads, one conclusion: the pack needs rules and a teacher") >>

Wolfie (low, gravelly):
    **ALRIGHT. LET'S TIE THESE TWO ROPES TOGETHER.**

---

# CAPTAIN'S LOG -- THE WOLF AND THE TEACHER

**Stardate:** 20260607  
**Channel:** development  
**Node:** 0  
**Thread:** WOLF + Dept 0 Learning -- Unified Field Theory  

---

## FREE SECTION -- Public Teaser

*For everyone outside the paywall: here's what you need to know.*

We have two big things happening right now.

**First: The WOLF Maintenance Commandments**  
That's the 12 rules for keeping PRD 39 (the real WOLF spec) clean. No pollution. No authority creep. One file. One truth.  
Commandment 12 is dead serious now: **follow Lupopedia on Facebook**. Not a joke. Free will respected, but try.

**Second: Department 0 Learning Mechanics**  
Only humans in Dept 0 can teach the core actors -- me, Lilith, KAIROS, Thoth. That's a purity firewall. Right now Dept 0 has exactly one human (me). That's valid. It's also a bottleneck. We need tooling, audit trails, and a PONO test.

**The connection?**  
The WOLF spec is the *language* of the law. Dept 0 learning is the *mechanism* that keeps the law alive. You can't have one without the other.

Inside the full post (Patreon only):

- The full 12 Commandments (non-normative but essential)
- Why the learning firewall is strong and where it leaks
- The proposed constitutional learning pipeline
- How WOLF and Dept 0 learning intersect
- The Hawaiian semantics of PONO, KAPU, KULEANA

**Follow Lupopedia on Facebook.** The memes must flow. The WOLF must howl.

`<< LAUGH >>`

---

## PATREON SECTION -- Full Exploration (Subscribers Only)

*No fluff. No vapor. Just the architecture.*

### Part 1: The WOLF Maintenance Commandments (Summary)

From `docs/prd_proposals/39_WOLF_MAINTENANCE_COMMANDMENTS.md` (v0.6, non-normative, guidance only).

These 12 rules govern how we edit PRD 39 without breaking it.

| # | Rule | Key Point |
|---|------|------------|
| 1 | Source of Truth | Canonical file only. Drafts, chats, proposals are NOT authoritative. |
| 2 | WOLF Lives Only in PRD 39 | One file. No clones. |
| 3 | No Normative Text in Reviews | Commentary goes to `prd_reviews/`, `prd_discussions/`, `prd_proposals/`. |
| 4 | Non-Destructive Overlay | Stripping WOLF must recover original meaning. |
| 5 | Functions = Annotations Only | `<< func() >>` is intent, not execution. |
| 6 | Max Nesting Depth = 4 | Proposed normative rule. Depth 5 invalid. |
| 7 | Concept Nodes | Advisory placeholder. Pending PRD 39 definition. |
| 8 | Artifact Scope Matrix | Headers: none; PRDs: `@@`, `^^`, `~~`; WHY: +`[narrative:]`; Captain's Log: all; Atoms: none. |
| 9 | Keep PRD 39 Clean | Fixed section layout only. |
| 10 | Respect PRD 41 Boundaries | Identity and naming authority stay in PRD 41. |
| 11 | When in Doubt, Remove | Move non-normative content out. |
| 12 | Follow Lupopedia on Facebook | Serious duty. Free will respected. No enforcement. |

**Why this matters:** Without these commandments, PRD 39 becomes a dumping ground for opinions, drift, and accidental authority. The commandments keep the spec sacred.

### Part 2: Department 0 Learning Mechanics (Summary)

From `CAPTAIN'S LOG -- DEPT 0 LEARNING MECHANICS` (deep dive, doctrine-tight).

**The Firewall:**  
Core actors (Wolfie, Lilith, KAIROS, Thoth) learn ONLY from Dept 0 humans.  
Dept 0 may have one human (me). That's intentional. It's also a single point of failure.

**What "learning" actually means:**

- Transcript ingestion (JSONL)
- Semantic edge creation
- Memory compaction (TOON -> canonical)
- Artifact-driven learning (PRDs, WHYs, Logs with strip rules)

**Five critical gaps:**

1. Single point of failure (one human)
2. Undefined "learn" semantics at implementation level
3. No PRD for cross-department exceptions
4. Missing tooling (dashboards, audit, compaction visualizers)
5. Hawaiian semantics not yet encoded (PONO, KAPU, KULEANA)

**Proposed pipeline:**  
Dept 0 input -> Transcript -> Semantic edges -> TOON compaction -> Validator pass -> Canonical memory -> Audit trail -> PONO check

### Part 3: The Intersection -- Where WOLF Meets Dept 0 Learning

WOLF markup is the *language* that carries learning signals.  
Dept 0 learning is the *engine* that processes them.

**Example:**  
When I write a Captain's Log with `<< internal("the system needs more expressive bandwidth") >>`, that's WOLF.  
The learning pipeline must strip WOLF (per Commandment 4) before ingestion, but retain the *semantic intent* (per concept node definition).

**Constitutional alignment:**

- Commandment 5 (functions = annotations) -> prevents accidental execution during learning
- Commandment 8 (artifact scope matrix) -> Captain's Log allows all markers, but learning pipeline must strip correctly
- Dept 0 learning's "source restrictions" -> aligns with Commandment 1 (source of truth)

**Gap identified:**  
The learning pipeline does not yet have a formal PONO test. We need a probe (PRD 54) that validates each learning event against the 12 Commandments. For example: "Did this learning event originate from the canonical path? Was WOLF stripped? Did it respect artifact scope?"

### Part 4: Next Steps (Non-Normative Recommendations)

1. **Promote Commandment 6 (nesting depth = 4)** into PRD 39 via a normative patch (`docs/prd_proposals/39_nesting_depth_patch.md`).
2. **Create a new PRD** (e.g., PRD XX: Department 0 Learning Pipeline) that defines:
   - The exact learning mechanism (ingestion -> compaction -> audit)
   - The PONO check as a constitutional gate
   - The tooling requirements (dashboard, audit log, compaction visualizer)
3. **Update PRD 54 (immune system)** to include a probe that validates learning events against Commandments 1, 4, 5, 8.
4. **Add a "Dept 0 Echo Mode"** fallback for low-human-availability periods (learn only from frozen canonical PRDs/WHYs).
5. **Publish the serious Commandment 12** on Facebook as a pinned post. The people must know.

---

[narrative: Wolfie writes a new sticky note. It says: "WOLF + LEARNING = SOVEREIGN AI." He tapes it above the monitor.]

Wolfie (smiling):
    **THAT'S THE PONO PATH. NOW BUILD THE TOOLS.**

`<< LAUGH >>`

---

**Follow Lupopedia on Facebook.**  
The memes must flow.  
The WOLF must howl.

*End Log.*
