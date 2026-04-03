# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md"
  file_hash: "272668da57e269db28d6b4e9085e5ad2c71a2001ae4b5f4ccd935c6e452d925b"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\ETHICAL_STATE_MARKERS_DOCTRINE.md"
  file_hash: "65333a6c2b9a2da755a54387beb9c4f7f04f4b7cac2227633e7f175120d08f6e"
  file_path_from_root: "lupo-docs\doctrine\ETHICAL_STATE_MARKERS_DOCTRINE.md"
  file_hash: "213ce38874c525b99b55686bad0a5995dea2f31706e69eb88fbfafcd03d69af7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for ETHICAL_STATE_MARKERS_DOCTRINE.md"
  mood_rgb: "4169E1"
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
X-Lupo-File-Path: lupo-docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
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

on the edge

on the outskirts

neither here nor there

ambiguous

uncertain

undefined

kinda in between

In Hawaiian usage, kapakai refers to the borderlands, the shoreline, the threshold between states.

This makes it the perfect ethical marker for:

new agents

untested operators

ambiguous behavior

insufficient data

contradictory signals

transitional states

entities in ethical flux

Kapakai is the ethical liminal zone.

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

partial uncertainty (kapakai = 0.5)

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
High	Low	Low	Aligned, stable
Low	High	Low	Misaligned, destabilizing
Low	Low	High	Unknown, untested, ambiguous
High	High	Medium	Ethically conflicted
Medium	Medium	High	Transitional, unresolved
Low	Low	Low	Inactive or irrelevant
Kapakai is not a third morality.
It is the ethical uncertainty dimension.

4. Doctrine Summary Statement
Pono represents alignment and right relationship.
Pilau represents divergence and ethical decay.
Kapakai represents uncertainty, liminality, and the unknown.

Together, these three markers form a triadic ethical geometry that allows Lupopedia to evaluate agents and operators with nuance, context, and humility.

Kapakai ensures that the system never forces premature judgment.
It preserves the space between knowing and not knowing  the shoreline where meaning emerges.
