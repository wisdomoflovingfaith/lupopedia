# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_federated_trust.md"
  file_hash: "4abd51ce5587a6a58f5a04df009065c73e05bdcfa1dc699d5749c59429e1cbdf"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\lupo_federated_trust.md"
  file_hash: "0d52e7ee88489b870c84c39fe61d7644c0aea97472aded4ba841e8ebea66cc8f"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_federated_trust.md"
  file_hash: "519579a724e6e732402b697c9886c12611e2464c78aeb548ddec3bf8fdd0910d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_federated_trust.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_federated_trustmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/lupo_federated_trust.md",
  system_version: "4.0.48",
  channel_id: 1,
  actor_id: 1003,
  created_ymdhis: 20260227000000,
  updated_ymdhis: 20260227000000,
  message_type: "table_documentation",
  visibility: "public",
  priority: "high",
  mood_rgb: "4B0082",
  artifact_kind: "table",
  traits: ["canonical", "federation", "trust_management"],
  tags: ["database", "federation", "trust", "security", "nodes"]
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-database/lupopedia/toon/lupo_federated_trust.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["identity_federation", "node_trust", "cross_instance_security"]
}
---

# 🌐 Table: lupo_federated_trust

**Purpose:** Manages the trust levels, permissions, and validation status between Lupopedia instances/nodes.  
**Type:** Federation Control Table  
**Status:** ✅ Production Ready  
**Volume:** Low (one record per node relationship)

---

## 🎯 **Overview**

The `lupo_federated_trust` table is the core of the multi-instance actor identity system. It defines how much one Lupopedia node trusts another, enabling actors to migrate or interact across instances securely. This directly supports Lilith's (2038) recommendation for "Multi-Instance Actor Identity" and federated trust.

### **Key Responsibilities**
- **Node Relationship Management:** Defines trust levels between local and remote instances.
- **Capability Scoping:** Specifies which actions a federated actor can perform on the local node.
- **Trust Calibration:** Stores the `trust_level` (0.0 to 1.0) derived from interaction history.
- **Validation Tracking:** Records when and how a remote node was last verified.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`trust_id`** (BIGINT) - Unique trust relationship identifier.

### **Node Identity Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `source_node_id` | BIGINT | The local node ID | |
| `target_node_id` | BIGINT | The remote node being trusted | |
| `trust_level` | FLOAT | Confidence score (0.0-1.0) | Defaults to 0.5 |
| `trust_type` | VARCHAR(50) | Nature of trust | e.g., 'full_federation', 'guest' |

### **Permission & Validation**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `capabilities` | JSON | NULL | Allowed cross-node actions |
| `restrictions` | JSON | NULL | Specifically forbidden actions |
| `last_verified_ymdhis` | BIGINT | 0 | YYYYMMDDHHIISS of last handshake |
| `verification_method` | VARCHAR(100) | NULL | e.g., 'mTLS', 'challenge_response' |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Nodes:** `source_node_id` & `target_node_id` → `lupo_federation_nodes.federation_node_id`
- **Actors:** `lupo_actors.primary_federation_node_id` references these nodes to resolve home instances.

---

## 🚀 **Usage Patterns**

### **Verifying Remote Authority**
Determining if a remote actor is authorized to sync their Identity Capsule to the local node.

```sql
SELECT trust_level, capabilities
FROM lupo_federated_trust
WHERE target_node_id = 42 -- Remote Node ID
  AND trust_type = 'full_federation'
  AND is_deleted = 0;
```

### **Handshake Audit**
Retrieving nodes that require re-verification based on last seen time.

```sql
SELECT target_node_id, last_verified_ymdhis
FROM lupo_federated_trust
WHERE last_verified_ymdhis < 20260226000000 -- Older than 24h
  AND is_deleted = 0;
```

---

## 🛡️ **Security & Privacy**

- **IP Locking:** Remote nodes are optionally locked to specific IP ranges (stored in `lupo_federation_nodes`).
- **Data Sovereignty:** Federated trust specifies which metadata from `WHO.json` is allowed to cross into a remote instance.
- **Root Protection:** Multi-node trust cannot grant `root_access` (Actor 10000 authority) unless expressly configured via air-gapped manual authorization.

---

*This documentation is part of the v4.0.48 Federated Identity framework.*