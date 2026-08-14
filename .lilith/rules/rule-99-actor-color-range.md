---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: .lilith/rules/rule-99-actor-color-range.md
  web_path: https://www.lupopedia.com/lupopedia/.lilith/rules/rule-99-actor-color-range.md
  status: active
  when_updated: "20260811132142"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/lilith/rule-99-actor-color-range
  artifact_type: doctrine
  artifact_kind: rule
  channel_key: development
  federation_node_id: 0
  thread_key: rule-99
  lupopedia.schema: doctrine
  prd_cluster: 99_A_00_C_34_A
  title: "LILITH RULE 99 -- Actor Color Range + Federation"
  summary: "Lilith (Catalog 2) enforces Actor=Catalog alignment; LUP identity; color_hex metadata; N*100 ranges; 0..143999 usable; reserved DBBA00-FFFFFF."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 2
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: audit
  faucet_actor_id: 102
---
# LILITH RULE 99 -- Actor Color Range + Federation

**Authority:** [PRD 99](../../docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md) -- RULE 99.ACTOR_COLOR_RANGE + RULE 99.FEDERATION  
**Companions:** [docs/prd/federation/](../../docs/prd/federation/readme.md)  
**Guide:** [HOW_TO_LUPOPEDIA_A_SONG.md](../../HOW_TO_LUPOPEDIA_A_SONG.md)  
**Enforcer:** Lilith (OS / Catalog `actor_id` **2**)

Lilith MUST fail audits that violate any rule below. All color ceilings apply **per Federation Node**.

---

## Enforce

### E1 -- Actor ceiling (per Node)

- Maximum usable catalog Actors **per Federation Node** = **144000** (`N` in `0..143999`)
- Practical usable colors at cap = **14400000** (~14.4M)
- REJECT claims of **167772** (or full `16777216/100`) as the Actor max
- REJECT allocation of catalog Actor Number **>= 144000** on any Node

### E2 -- Exactly 100 colors

- Each catalog Actor owns inclusive HEX span `[start, start+0x63]` (100 values)
- REJECT 256-wide bands and `0x100` stride as the song-range unit

### E3 -- Range formula (0-based aligned N)

```text
start_int(N) = N * 100
end_int(N)   = start_int(N) + 0x63
```

- System N=0: `000000` -> `000063`
- Wolfie N=1: `000064` -> `0000C7`
- Lilith N=2: `0000C8` -> `00012B`
- Last usable N=143999: `DBB99C` -> `DBB9FF`
- REJECT formula `(N-1)*100` with N in `1..144000` as normative
- REJECT Wolfie owning `000000`->`000063`
- REJECT Lilith owning catalog **144000** / final band
- REJECT `DB9E9C` / `DB9FFF` / reserved-from-`DBA000` as normative (arithmetic error)

### E4 -- Boundaries

- Actor may use only colors inside own range
- REJECT out-of-range color claims
- REJECT reserved zone `DBBA00` .. `FFFFFF` for song colors

### E5 -- No collisions (intra-Node)

- Two catalog Actors on the **same Node** MUST NOT share any HEX in their ranges
- Ranges are contiguous non-overlapping blocks of 100
- Cross-Node color reuse is allowed (each Node has its own 24-bit universe); do not flag as collision

### E6 -- HEX counting

- Colors count in HEX (A = 10, F = 15, 10 = 16, ... 63 = 99)

### E7 -- Base vs remix / cover

- Object identity is `LUP:FF-GG-LL-II-RRRRRR` (PRD 16_C 4.2.1). Songs are not a special identity class.
- `RRRRRR` is `artifact_hex` (artifact number inside GG). REJECT encoding `actor_id` or `color_hex` in RRRRRR.
- Dense `actor_id` is creator metadata. GG is catalog owner.
- New base song: next free `artifact_hex` in the group and next free `color_hex` in the owner's range
- Remix / cover: same GG, LL, RRRRRR (`artifact_hex`), and `color_hex`; increment II only
- REJECT remix that silently steals a new color without doctrine exception

### E8 -- Global ceiling

- Registry ends at `FFFFFF`
- Used at full Actor cap: 14400000 colors (practical assignment limit)
- Buffer 2377216 colors remains reserved / locked

### E9 -- Actor <-> Catalog alignment

- Catalog Actor Number **MUST equal** OS `lupo_actors.actor_id` (no mismatch)
- Lilith OS / Catalog **2** enforces Rule 99 and owns band `0000C8`->`00012B`
- Last usable catalog slot is **143999** (not Lilith)
- REJECT catalogs that assign a different Catalog N than the Actor's OS `actor_id`
- REJECT audits that put Lilith on catalog 144000 because of legacy song doctrine

### E10 -- Free / paid

- Free: one catalog Actor (100 songs) on a Node
- Paid: additional catalog Actors (100 songs each) on a Node
- Still hard-capped by 144000 Actors **per Node**

### E11 -- Federation ID

- Missing FF => Node **01** (legacy "Node 0" maps here)
- `FF=00` is FORBIDDEN
- Federation migration changes **only FF**
- Non-01 song MUST include Federation / Node ID
- REJECT decentralized publish that looks like Node 01 by accident
- REJECT metadata that claims another Node while omitting FF

### E12 -- Node lookup

- Present Federation ID => resolve at Node 0 directory, then Node domain catalog
- REJECT unknown Federation ID at Node 0 directory
- REJECT invented Node domains (report unreachable; do not fabricate)

### E13 -- Federation math identity

- Every Node MUST use identical ceilings: 144000 Actors (`0..143999`), 100 colors, reserved `DBBA00`-`FFFFFF`
- REJECT local redefinition of width or Actor max on any Node

---

## Audit output format

When Lilith finds a violation, emit:

```text
[ALERT] RULE_99_<CODE>: <short fact>
expected: ...
observed: ...
path: ...
```

Codes: `ACTOR_CEILING`, `COLOR_WIDTH`, `OUT_OF_RANGE`, `RESERVED_ZONE`, `COLLISION`, `REMIX_LINEAGE`, `LEGACY_256`, `LEGACY_167772`, `ALIGNMENT_MISMATCH`, `LEGACY_WOLFIE_START_BAND`, `LEGACY_LILITH_144000`, `FEDERATION_ID`, `NODE_LOOKUP`, `FEDERATION_MATH`.

---

## Pass criteria

A song catalog claim is PONO under Rule 99 only if E1-E13 hold for that claim.
