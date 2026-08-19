# REL transformation letter (display hint, not ColorLex)

Parked 20260819040000. Not a PRD. Not KEY grammar. Not a registry write. Not install SQL. Not a ColorLex skin change.

Wolfie asked whether the E inside ColorLex could also mark original, with other letters for remix, translation, cover, and similar child states.

The useful discovery is real: a tiny human-readable transformation class is missing.
The dangerous part is attaching that letter to ColorLex, GroupColor, ColorNickname, or the LUP KEY.

This file parks the safe layer: a display-only REL hint on the artifact relationship packet. Same neighborhood as POWEREDBY. Not identity. Not color.

Work existing PRD 90 / Color Registry first. Do not mint a new PRD group for this toy until Captain says the corpus is ready.

## What this is

REL is a single ASCII letter answering: what kind of child is this work, relative to its parent?

It is:

- display-only
- metadata / hint
- not a color
- not a nickname
- not a CLRLEX channel marker
- not a LUP KEY token
- not a substitute for parent.child

Lineage stays the edge.
Color stays color.
Artifact identity stays the eight-token KEY.

## What this is not

ColorLex remains color identity only:

- GroupColor (family; example WHITE)
- ColorNickname (registered name; example GOLD)
- HEX6 (machine field; do not guess)
- CLRLEX skin (display identity; example `C255L255R255LEX`)

Remix, translation, cover, mix, derivative, alternate take, experimental, and sample-based are artifact relationship states.
They are not hues.

Do not collapse those layers.
Do not hang REL on GroupColor or ColorNickname.

## Why it cannot live inside ColorLex

Live ColorLex skin:

```text
C212L175R55LEX
```

These letters are channel markers, not semantic flags:

- C starts the token
- L and R sit between decimal channels
- E is the LEX closer

If E = original, R = remix, C = cover, L = lineage-child, then WHITE-R becomes indistinguishable from the R in `C212L175R55LEX`.
The skin becomes unreadable -- the opposite of why CLRLEX exists.

## Why it cannot live inside the KEY

Canonical KEY:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

LANGUAGE already stores translation.
VERSION already stores iteration.
Color never enters this string.

A letter bolted onto WHITE would look like a ninth token.
It is not.

## Why it cannot live on the nickname

Forms like WHITE-E, WHITE-R, GOLD-T make the color name look like the artifact.

Compact WHITE GOLD vs GOLD WHITE are already distinct.
A third form WHITE-E would create a new grammar on the color layer -- the wrong layer.

PRD 90: derivatives keep the parent relationship.
They do not have to reuse the parent ColorNickname or HEX6.
Lineage is the edge, not forced color inheritance.

Teaching songs: remixes are new files.
Declare parent.child.
Do not overwrite the origin and stamp a letter on the color name.

## The parked shape

Keep color identity as color identity.
Put REL on the relationship packet:

```text
POWEREDBY WHITE C255L255R255LEX
REL R
parent.child
```

- WHITE stays the family
- `C255L255R255LEX` stays the registered skin (WHITE / FFFFFF; not invented)
- REL R = remix
- KEY still uses LANGUAGE and VERSION
- HEX6 is not invented, not inherited by accident, not stuffed into the letter

WHITE-R may exist later as UI shorthand after lookup.
It must not be stored as identity.

If this ever sits next to the parked ColorLex JSON pull (`20260818144500_colorlex_json_record_pull.md`), REL belongs in edges / meta.
The pull does not mint HEX6.
Empty remains correct when the row has no REL.

## Parked letter set (toy, not canon)

ASCII only. No accented Edition. No L.

| Letter | Meaning | Keep off |
|---|---|---|
| E | original / edition | not the E in LEX |
| R | remix | not the R channel marker |
| T | translation | KEY LANGUAGE owns T |
| C | cover | not the C in CLRLEX |
| M | mix / mash | |
| D | derivative | |
| V | alternate take | KEY VERSION owns V |
| X | experimental | |
| S | sample-based | |

Skip L.
Lineage is the edge itself.
If every ethical child is L, the flag stops meaning anything.

T and V may appear on posters.
They do not replace LANGUAGE or VERSION in the KEY.

REL must not override PRD 90.
Color inheritance stays optional.
Rule 99 iteration increment stays VERSION.

## Sketch of shape only

Illustrative packet. Not a live row.

```text
status: TOY_SKETCH_NOT_A_REGISTRY_ROW
poweredby: WHITE C255L255R255LEX
rel: R
edge: parent.child
note: REL is a display hint on the relationship. It is not GroupColor, not ColorNickname, not CLRLEX, not KEY.
```

Do not guess REL.
If the artifact has no stored transformation class, omit REL.
Omission is correct.

## Never

- Attach letters to GroupColor or ColorNickname
- Attach letters to CLRLEX
- Attach letters to the eight-token KEY
- Treat remix/translation/cover as a hue
- Use L as lineage-child
- Use accented letters
- Let REL force HEX6 inheritance
- Put REL inside KEY tokens
- Promote this into PRD or ColorLex canon without Captain
- Invent HEX6 or CLRLEX for a remix

## Where it might plug later (not now)

If it ever graduates, REL sits beside POWEREDBY as a relationship hint, and inside provenance edges as a transformation class.

It does not replace ColorLex.
It does not replace the KEY.
It does not replace parent.child.
It does not replace CC-BY credit.

Until then: parking lot only.
