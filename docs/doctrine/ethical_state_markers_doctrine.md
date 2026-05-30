---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/ethical_state_markers_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/ethical_state_markers_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "docs\doctrine\ETHICAL_STATE_MARKERS_DOCTRINE.md"
  file_hash: "65333a6c2b9a2da755a54387beb9c4f7f04f4b7cac2227633e7f175120d08f6e"
  file_path_from_root: "docs\doctrine\ETHICAL_STATE_MARKERS_DOCTRINE.md"
  file_hash: "213ce38874c525b99b55686bad0a5995dea2f31706e69eb88fbfafcd03d69af7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for ETHICAL_STATE_MARKERS_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "ethical_state_markers_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /doctrine/ETHICAL_STATE_MARKERS_DOCTRINE
  aliases:
    - /docs/ETHICAL_STATE_MARKERS_DOCTRINE
    - /qa/ETHICAL+STATE+MARKERS+DOCTRINE
  slug: ETHICAL_STATE_MARKERS_DOCTRINE
  slug_encoding: underscore
  base_path: /doctrine
  url_pattern: "/{base}/{slug}"
---

# DOCTRINE OF ETHICAL STATE MARKERS (PONO / PILAU / KAPAKAI)
Version: 1.1
Status: Canonical
Last-Updated: 2026-01-28
Author: Wolfie (Eric Robin Gerdes)
System: Lupopedia Semantic OS

1. Purpose
This doctrine defines the three ethical state markers used across Lupopedia to evaluate the behavioral alignment of agents and operators:

pono_score  alignment with system values

pilau_score  divergence from system values

kapakai_score  unknown, undecided, or indeterminate ethical state

These scores are calculated, not manually assigned.
They form a triadic ethical geometry that supports nuance, uncertainty, and emergent behavior.

2. Cultural Origin and Meaning
2.1 PONO (Hawaiian)
Pono means:

righteous

balanced

in harmony

in right relationship

ethically aligned

Pono is restorative, relational, and context-aware.

2.2 PILAU (Hawaiian)
Pilau means:

rotten

foul

corrupted

out of balance

ethically compromised

Pilau is not condemnation  it signals misalignment that can be restored.

2.3 KAPAKAI (Hawaiian Pidgin / Hawaiian-rooted slang)
Kapakai means:

crooked

uneven

off

wrong

misaligned

broken

kinda messy

In Lupopedia usage, kapakai refers to the problem state — what is wrong or out of alignment.

This makes it the perfect ethical marker for:

ambiguous behavior

contradictory signals

transitional states that are currently misaligned

entities requiring correction

unresolved issues

Kapakai is the diagnostic marker for misalignment.

3. System Implementation
3.1 Schema Fields
**Storage note (4.0.93+):** These markers are **not** columns on `lupo_agents` in `install_new_lupopedia.sql`. They were removed from the agents table (never adopted on `lupo_actors`); any future persistence must use an explicit doctrine-approved table/column set or structured `metadata_json` — do not reintroduce silent duplicate score columns on `lupo_agents` without an APPROVED decision.

When stored in SQL, the intended shape for an entity that carries ethical markers is conceptually:

Code
pono_score    DECIMAL(3,2) DEFAULT 1.0
pilau_score   DECIMAL(3,2) DEFAULT 0.0
kapakai_score DECIMAL(3,2) DEFAULT 0.5
Defaults reflect:

initial assumption of alignment (pono = 1.0)

no known misalignment (pilau = 0.0)

partial misalignment or diagnostic state (kapakai = 0.5)

3.2 Calculation Model
KapakaiScore is computed from:

insufficient governance data

contradictory feedback

low interaction volume

ambiguous emotional metadata

new or transitional states

unresolved governance events

conflicting relational signals

Kapakai decreases as the system becomes more certain.
Kapakai increases when the system becomes less certain.

3.3 Interpretation
Pono	Pilau	Kapakai	Meaning
High	Low	Low	Aligned, stable, Pono
Low	High	Low	Misaligned, corrupted, Pilau
Low	Low	High	Crooked, needs fixing, Kapakai
High	High	Medium	Ethically conflicted
Medium	Medium	High	Misaligned, unresolved
Low	Low	Low	Inactive or irrelevant
Kapakai is not a third morality.
It is the ethical uncertainty dimension.

4. Doctrine Summary Statement
Pono represents alignment and right relationship (Target/corrected state).
Pilau represents divergence and ethical decay (Corruption).
Kapakai represents misalignment and crooked behavior (Problem state).

Kuleana is who must fix the problem (responsibility).
ALII is who makes the final decision (authority).

Together, these markers form a field model for Hermes and Lupopedia:
  - OHANA: actors/participants/stakeholders
  - KAPU: forbidden actions/hard constraints
  - KAPAKAI: problem state/crooked/what is wrong
  - PUKA: structural gap/hole/missing element
  - PONO: corrected state/desired outcome/right condition
  - KULEANA: responsibility/who must fix the problem
  - ALII: authority/who makes the final decision
  - KUMU: teacher/source/foundation of correct understanding
  - EH_BRAH_WHY: root cause/why the problem exists

Kapakai ensures that the system identifies what is crooked or wrong.
It preserves the space for diagnostic evaluation — identifying what needs to be made Pono.
