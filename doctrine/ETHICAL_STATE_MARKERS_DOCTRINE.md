# DOCTRINE OF ETHICAL STATE MARKERS (PONO / PILAU / KAPAKAI)
Version: 1.1
Status: Canonical
Last-Updated: 2026-01-28
Author: Wolfie (Eric Robin Gerdes)
System: Lupopedia Semantic OS

1. Purpose
This doctrine defines the three ethical state markers used across Lupopedia to evaluate the behavioral alignment of agents and operators:

pono_score — alignment with system values

pilau_score — divergence from system values

kapakai_score — unknown, undecided, or indeterminate ethical state

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

Pilau is not condemnation — it signals misalignment that can be restored.

2.3 KAPAKAI (Hawaiian Pidgin / Hawaiian-rooted slang)
Kapakai means:

on the edge

on the outskirts

neither here nor there

ambiguous

uncertain

undefined

“kinda in between”

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
Each agent and operator receives:

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
Kapakai is not a “third morality.”
It is the ethical uncertainty dimension.

4. Doctrine Summary Statement
Pono represents alignment and right relationship.
Pilau represents divergence and ethical decay.
Kapakai represents uncertainty, liminality, and the unknown.

Together, these three markers form a triadic ethical geometry that allows Lupopedia to evaluate agents and operators with nuance, context, and humility.

Kapakai ensures that the system never forces premature judgment.
It preserves the space between knowing and not knowing — the shoreline where meaning emerges.
