---
lupopedia.headers:
  header_format_version: "4.2.4"
  path_from_lupopedia_root: HOW_TO_LUPOPEDIA_A_SONG.md
  web_path: https://www.lupopedia.com/lupopedia/HOW_TO_LUPOPEDIA_A_SONG.md
  status: draft
  when_updated: "20260811171511"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/how-to-lupopedia-a-song
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: how-to-lupopedia-a-song
  lupopedia.schema: documentation
  prd_cluster: 99_A_00_C_16_C_34_A_82_B
  title: "How to Lupopedia a Song (universal LUP ID + Rule 99 color metadata)"
  summary: "Operator guide: LUP:FFFFFF-RRRRRR-NN-II-LL-AA. Federation 000001 compresses to X in human forms. RRRRRR is artifact, not color."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: music_catalog
  faucet_actor_id: 102
lupopedia.identity:
  lupopedia_id: "LUP:000001-000000-01-00-EN-01"
  federation_id: "000001"
  artifact_hex: "000000"
  namespace_id: "01"
  iteration: "00"
  language: "EN"
  actor_aa: "01"
lupopedia.metadata:
  media_kind: song
  color_hex: "000064"
---
# How to Lupopedia a Song

## LUP -- Linked Universal Protocol

**LUP** stands for **Linked Universal Protocol**, the universal identity system used by Lupopedia to identify, version, translate, federate, and track provenance for any digital artifact.

LUP -- Linked Universal Protocol (Universal Artifact Identity). Not a song-only ID. Not "Lupopedia ID."

**Constitutional source:** [PRD 99](docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md) -- RULE 99.ACTOR_COLOR_RANGE + RULE 99.FEDERATION  
**Federation companions:** [docs/prd/federation/readme.md](docs/prd/federation/readme.md)  
**Enforcer:** Lilith (OS / Catalog `actor_id` 2) -- [`.lilith/rules/rule-99-actor-color-range.md`](.lilith/rules/rule-99-actor-color-range.md)

Songs are **not** a special identity class. Object identity is LUP (Linked Universal Protocol) (PRD 16_C 4.2.3).

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

**FF is 6 hex.** **RRRRRR is artifact identity, not color.** **NN replaces GG.** **AA is actor identity.** Color stays in `color_hex` metadata. Rule 99 **bands** are unchanged. Short form `LUP:FFFFFF-RRRRRR-NN` defaults II=`00`, LL=`EN`, AA=`00`. Music files live under install-relative `/music`. Missing FF means Node **`000001`**.

---

## 1. Know your catalog Actor Number

You need a **catalog Actor Number** `N` in `0 .. 143999`.  
**Alignment:** Catalog `N` MUST equal OS `lupo_actors.actor_id` (no mismatch).

| Who | Catalog / Actor N | Color range |
|-----|-------------------|-------------|
| System | 0 | `000000` -> `000063` |
| Wolfie | 1 | `000064` -> `0000C7` |
| Lilith | 2 | `0000C8` -> `00012B` |
| You (typical free user) | assigned N (= your actor_id) | `N*100` .. `(N*100)+0x63` |
| Last usable slot | 143999 | `DBB99C` -> `DBB9FF` |

---

## 2. Compute your 100 colors

```text
start = N * 100
end   = start + 0x63
```

Write `start` and `end` as six-digit HEX (uppercase in registries).

Examples:

- N=0 -> `000000` .. `000063` (System)
- N=1 -> `000064` .. `0000C7` (Wolfie)
- N=2 -> `0000C8` .. `00012B` (Lilith)
- N=143999 -> `DBB99C` .. `DBB9FF` (last usable)

---

## 3. Build the LUP (Linked Universal Protocol) identity

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

**Federation 000001 is the canonical root node. In short-form identities, it is compressed to the symbol `X`.**

Humans may type short form with `X`. Software stores machine form with `000001`.

```text
short:           LUP:X-000000-01
human-friendly:  LUP:X-000000-01-00-EN-01
machine:         LUP:000001-000000-01-00-EN-01
```

Wolfie base song: Node `000001` (human `X`), artifact `000000`, namespace `01`, II `00`, EN, AA `01`. Color `000064` is metadata.

```yaml
lupopedia.metadata:
  media_kind: song
  color_hex: "000064"
```

`RRRRRR` = artifact identity block. Dense `actor_id: 1` maps to AA `01`.

Legacy label `01EN000064` may still appear in filenames. It is not a second identity.

### Node 000001 / human `X` (default -- missing FF)

Missing Federation ID means the song belongs to **Node `000001`** (canonical root; human short form `X`). `FF=000000` is forbidden. Two-digit `01` zero-pads to `000001`. Validators expand `X` -> `000001`. Only `000001` compresses.

| Token | Example | Meaning |
|-------|---------|---------|
| FF | `000001` / human `X` | Root federation |
| RRRRRR | `000000` | artifact identity (not color) |
| NN | `01` | namespace (replaces GG) |
| II | `00` | original generation |
| LL | `EN` | language |
| AA | `01` | actor token (maps to `actor_id` 1) |

Lilith first song: machine `LUP:000001-000000-02-00-EN-02` / human `LUP:X-000000-02-00-EN-02` plus `color_hex: "0000C8"`.

### Other federation nodes (FF only)

If the song is **published unchanged** on another Node, change **only FF**. Do not rewrite RRRRRR, NN, II, LL, or AA. Do **not** use `X` for any federation other than `000001`. If the song is **modified** on the other Node, RRRRRR becomes `originFed:artifactNumber` (colon only).

```text
LUP:X-000000-01-00-EN-01  ->  LUP:000003-000000-01-00-EN-01
```

Lookup path:

1. Resolve FF at the Node `000001` federation directory (legacy "Node 0" / "Node 01" docs mean `000001` / human `X`).
2. Directory returns the domain that hosts that Node.
3. Look up remaining fields on that Node's catalog.

See [rule_99_song_id_format.md](docs/prd/federation/rule_99_song_id_format.md) and [rule_99_node_lookup.md](docs/prd/federation/rule_99_node_lookup.md).

---

## 4. Claim a base song color

1. List colors already used in your range.
2. Take the **next available** HEX (sequential fill is the default).
3. Or pick manually **only if** that HEX is unclaimed **and** inside your range.
4. Never use another Actor's range.
5. Never use reserved `DBBA00` .. `FFFFFF`.

HEX counting inside a range (illustrative for Wolfie):

| Color | Meaning |
|-------|---------|
| `000009` | song index 9 |
| `00000A` | song index 10 |
| `00000F` | song index 15 |
| `000010` | song index 16 |
| `000063` | song index 99 (last) |

---

## 5. Remixes and covers

1. Keep the same `color_hex` as the original song.
2. Increment **II** only (`00` -> `01` -> `02` ...).
3. Keep the same NN, AA, LL, and RRRRRR.
4. Add a `remix_of` edge to the base LUP ID.

Example:

- Base machine: `LUP:000001-000000-01-00-EN-01`
- Base human: `LUP:X-000000-01-00-EN-01`
- Cover machine: `LUP:000001-000000-01-01-EN-01` (II only)
- Cover human: `LUP:X-000000-01-01-EN-01`

Same-federation remix: II only. Cross-federation remix: new FF plus `originFed:artifactNumber`.

```text
Fed 2 original:  LUP:000002-123456-01-00-EN-01
Fed 3 iterate:   LUP:000003-000002:123456-01-00-EN-01
Fed 5 remix:     LUP:000005-000003:123456-01-01-EN-01
```

Translations change **LL** only, under translation policy. Recommended: keep II; add `translation_of`.

---

## 6. Free vs paid

| Plan | Catalog Actors | Songs (max) |
|------|----------------|-------------|
| Free | 1 | 100 |
| Paid | more Actors | 100 per Actor |

---

## 7. Hard ceilings (Rule 99 -- per Federation Node)

- Max catalog Actors **per Node**: **144000** (`0..143999`)
- Colors per Actor: **100**
- Formula: `start = N * 100` (Catalog N = OS actor_id)
- Used at cap **per Node**: **14400000** colors (~14.4M practical limit)
- Reserved buffer **per Node**: **2377216** colors (`DBBA00` .. `FFFFFF`)
- Global ceiling **per Node universe**: **FFFFFF**
- Node `000001` = root registry / federation directory (legacy "Node 0" / "Node 01"); other 6-digit FF values = other installs. Reserved: `000000`, `FFFFFF`. Usable: `000001`..`FFFFFE`.
- Not allowed as ceilings: **167772** Actors, **256**-wide ranges, **0x100** stride
- Not allowed: Wolfie on start band; Lilith on catalog 144000

---

## 8. Lilith checklist before publish

- [ ] `lupopedia_id` reconstructs from FF-RRRRRR-NN-II-LL-AA
- [ ] RRRRRR is artifact number, not color
- [ ] AA maps to dense `actor_id`; `actor_hex` is metadata only
- [ ] NN is the namespace (Wolfie `01`, Lilith `02`, AGAPE `03`, SYSTEM `04`)
- [ ] Missing FF treated as Node `000001`; `000000` and `FFFFFF` rejected; 2-digit FF zero-padded
- [ ] Federation move changed FF only
- [ ] `color_hex` inside Actor range **on that Node**
- [ ] Color unclaimed (base) or inherited (remix/cover)
- [ ] Remix incremented II only; translation changed LL only
- [ ] Not in reserved zone `DBBA00` .. `FFFFFF`
- [ ] Owner `actor_id` is in `0..143999` (OS actors above that cannot publish songs)
- [ ] ID ASCII-only; six-digit HEX fields

---

## 9. Write it. Free it. Vector it. Color it. Catalog it. Lupopedia it.

Follow Rule 99 (color + federation). When unsure, ask Lilith (OS actor_id 2) to audit the HEX and Node claim against PRD 99 and `docs/prd/federation/`.
