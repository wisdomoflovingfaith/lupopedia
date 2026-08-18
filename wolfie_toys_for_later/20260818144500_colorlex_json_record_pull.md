# ColorLex code -> JSON record pull (references, edges, meta)

Parked 20260818144500. Not a PRD. Not KEY grammar. Not a registry write. Not install SQL.

After rest, Wolfie wanted a later feature: you hand the system a **ColorLex display identity** (an authorized CLRLEX token, not a guessed one) and you get back a JSON block for **that record**.

```text
authorized ColorLex code
        |
        v
relational lookup of the stored record
        |
        v
JSON: references, edges, meta, identity fields the registry already has
```

This is lookup of a stored association. It is not hashing a string into a color. It is not inventing HEX6. It is not putting color inside the LUP KEY.

Work existing PRD 90 / Color Registry first. Do not mint a new PRD group for this toy until Captain says the current corpus is ready.

## What the JSON is for

Humans and programs often see only the skin token. The stored record may already know more:

- domain
- GroupColor (if present)
- ColorNickname (if authorized)
- HEX6 (machine field; humans do not have to recite it)
- references (what this record points at)
- edges (parent.child or other stored links, if any)
- meta (status, language, source, timestamps the registry already keeps)

The pull does not create those fields. It returns what the authorized row already holds. If the nickname is not authorized, lookup stays unresolved. Empty is correct. Do not fill gaps with a dream.

## Sketch of shape only

Illustrative packet. Not a live row. Codes left null on purpose.

```json
{
  "status": "TOY_SKETCH_NOT_A_REGISTRY_ROW",
  "query": {
    "clrlex": null,
    "note": "Caller supplies an authorized ColorLex display token. This file does not mint one."
  },
  "identity": {
    "domain": null,
    "group_color": null,
    "color_nickname": null,
    "hex6": null,
    "clrlex": null
  },
  "references": [],
  "edges": [],
  "meta": {},
  "unresolved": true,
  "note": "Unresolved until Captain authorizes a real lookup against PRT.LUP.colors.csv or its successor. Do not guess HEX6."
}
```

Preferred human path stays: name / authorized nickname. JSON is the machine packet after lookup, like the optional lower layer in the teaching songs. Ordinary people do not have to read it.

## Phrase lookup -- parked to the side, probably left field

A nap also showed phrase lookup ("curiosity killed the cat" -> JSON of missing proverb edges).

That is a different toy. It is tempting because it looks like the same "restore context" urge. It is also how you **create** new out-of-context damage:

- a caller feeds a **segment** of a saying;
- the JSON looks complete;
- the rest of the speech, the speaker, and the room are gone;
- the packet becomes a new first half.

So phrase lookup is **not** v1 of this file. ColorLex-code pull is about an already-governed color record. Phrase pull is language, quotes, and provenance of speech. Mixing them lets a snippet impersonate a whole record.

If Captain later wants a phrase toy at all, it needs its own parked file and a hard rule: do not resolve a fragment as if it were the full utterance. This file does not start that.

## Never

- Guess HEX6 or CLRLEX to make the JSON look full
- Hash a nickname, title, or proverb into a color
- Put the JSON fields into the eight-token LUP KEY
- Treat this sketch as PRT.LUP.colors.csv
- Auto-register GOLDENWOLF, RULES, or any teaching GroupColor
- Use phrase-segment lookup as a shortcut for color lookup
- Promote this into a PRD number without Captain

## Where it might plug later (not now)

If this ever graduates, it would sit **after** Color Identity doctrine (PRD 90) and the on-disk color table, as a read API / display packet. It would not replace POWEREDBY. It would not replace the KEY. It would not replace CC-BY credit.

Until then: parking lot only.
