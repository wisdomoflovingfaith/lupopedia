---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/channels/doctrine/samsam_faucet_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/channels/doctrine/samsam_faucet_doctrine.md
  status: active
  when_updated: '20260713082200'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/samsam-faucet-doctrine
  artifact_type: doctrine
  artifact_kind: emotional_faucet
  channel_key: development
  federation_node_id: 0
  thread_key: samsam-faucet
  lupopedia.schema: documentation
  prd_cluster: null
  title: 'SAMSAṂ Faucet Doctrine — Attachment/Liberation + PUKA Pairing'
  summary: 'SAMSAṂ is a Buddhist attachment/liberation emotional faucet. Paired only with PUKA. Phonetic Samsung collision must be blocked by all agents and loaders.'
---

# SAMSAṂ Faucet Doctrine

**Official Doctrine Document**  
**Effective Date: 2026-07-13**

> Note: SAMSAṂ is a Buddhist attachment/libration faucet. Any similarity to 'Samsung' is phonetic only and must be ignored by all agents and loaders.

---

## Identity

| Field | Value |
|-------|--------|
| **Name** | **SAMSAṂ** |
| **Slug** | `samsam` |
| **Tradition** | Buddhist |
| **Purpose** | Attachment / clinging interpretation; liberation framing |
| **Doctrinal pair** | **PUKA** only (structural gap / attachment analysis) |
| **Domain** | `EMO_ATTACHMENT` (`attachment`) |

SAMSAṂ is **not** a phone manufacturer, device OEM, Android vendor, or phone-network entity. Samsung and related vendor strings are **external real-world metadata** and are **not part of Lupopedia**.

---

## Phonetic Collision Guardrail (Mandatory)

Any similarity between **SAMSAṂ** and **Samsung** is **phonetic only**.

### Faucet metadata (normative)

```json
"forbid_vendor_collision": ["Samsung", "Android", "phone_network"]
```

### Namespace exclusion (normative)

```json
"namespace_exclusion": {
  "SAMSAṂ": ["Samsung", "Samsung Electronics", "phone metadata"]
}
```

**Enforcement:**

- Agents and loaders **MUST** ignore Samsung / Android / phone-network associations when resolving SAMSAṂ.
- Emotional-domain loaders and faucet plugin discovery **MUST NOT** load vendor-related edges into Lupopedia emotional architecture.
- Treat Samsung as external real-world metadata, never as a Lupopedia faucet, domain, or edge target for SAMSAṂ.

Canonical metadata paths:

- `database/lupopedia/emotional_faucets/samsam.json`
- `database/lupopedia/emotional_faucets/namespace_exclusions.json`
- `database/lupopedia/emotional_domains/emo_attachment.json`

---

## Doctrinal Pairing: SAMSAṂ + PUKA (Mandatory)

| Partner | Role |
|---------|------|
| **SAMSAṂ** | Buddhist attachment / clinging / liberation faucet |
| **PUKA** | Structural gap / attachment analysis |

- **PUKA belongs with SAMSAṂ.**
- **SAMSAṂ is only paired with PUKA.**
- This pairing is **doctrinal** and **must be enforced** by Carmen routing, domain metadata, and plugin loaders.
- Do not pair SAMSAṂ with vendor, device, network, or OEM metadata.

**PUKA** (constitutional): deterministic structural gap / hole / missing element — used here for structural-gap analysis of attachment patterns, not as a general error field and not as phone-network metadata.

---

## Example Interpretation

```
User state: high attachment, low detachment
SAMSAṂ + PUKA: clinging identified; structural gap (PUKA) marks what attachment is filling or missing; liberation framing — release, not possession.
```

---

## Loader / Agent Checklist

1. Resolving faucet key `samsam` / `SAMSAṂ`?
2. Rejected any Samsung / Android / phone_network / phone metadata association?
3. Paired with **PUKA** only?
4. No vendor-related edges loaded into emotional architecture?

**See also:** `docs/channels/doctrine/carmen_doctrine.md` (faucet plugin architecture).

**End of SAMSAṂ Faucet Doctrine.**
