# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLARE\FLARE_DOCTRINE.md"
  file_hash: "19033383ad2d953cc1db20c04d51c42ae3a87578bc0624d4ab36644d3397f423"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/doctrine/FLARE/FLARE_DOCTRINE.md"
  system_version: "4.0.47"
  channel_id: 0
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "doctrine"
  purpose: "Core doctrine defining FLARE protocol for file-level attribute and relationship exchange"
  dialog_message: "Recommended next step: create actors/1007 profile and align any remaining docs/examples to the required FLARE prologue format."
  mood_rgb: "FFD700"
  traits: ["canonical", "system-critical", "permanent"]
  tags: ["flare", "doctrine", "protocol", "file_metadata", "relationships"]
  lupo_agent: "codex-ide"

flare.footer:
  outbound_edges:
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/FLIP/FLIP_DOCTRINE.md", type: "supersedes", weight: 0.8 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "doctrine", "protocol", "canonical", "system"]
---

## 12. Mandated Header Comments (v4.0.48+)

Starting with version 4.0.48, every FLARE header MUST begin with a specific comment line linking to the authoritative web resolution. This enables human and machine consumers to quickly access the interactive documentation and Q&A for the protocol.

**Format:**
```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
flare.headers:
  ...
```

**Reasoning:**
- **Accessibility**: Direct links for external agents and researchers.
- **Authority**: Verifies that the file follows the canonical Lupopedia protocol.
- **Portability**: Ensures the protocol remains self-documenting even outside the repository environment.

## 13. Content Overwrite Hierarchy (v4.0.53+)

To ensure predictable content resolution and synchronization across environments (IDE filesystem, database, and TOON exports), Lupopedia enforces a strict overwrite hierarchy.

**Default Load Order:**

1.  **FILESYSTEM (`lupo-channels/`)**: Highest priority. If a file exists in the `lupo-channels` directory, its content and metadata are treated as the system source of truth, overwriting any values in the database.
2.  **DATABASE**: Medium priority. If content is updated via the web interface or API, it is stored in the database. These values are overridden by filesystem changes if they exist, but are used if the filesystem is empty or out-of-sync.
3.  **CSV/TOON FILES**: Lowest priority. These files (e.g., in `lupo-database/lupopedia/csv/` or `lupo-database/lupopedia/toon/`) are used as seed data or schema reference. They are overwriten by the database once imported, and by the filesystem via `scripts/generate_toon_files.py`.

**Synchronization Protocol:**

*   **DB → CSV/TOON**: The database state overwrites CSV and TOON files when running `scripts/generate_toon_files.py`.
*   **FILESYSTEM → DB**: Files in `lupo-channels/` overwrite the database state when booting or explicitly running an import command.

## 14. Lifecycle Hooks (v4.0.56+)

Starting with version 4.0.56, FLARE headers support lifecycle hooks via `flame.init` and `flame.close` blocks. These blocks allow for automated pre-processing and post-processing and are mandatory for files with `system_version` 4.0.55+.

### **flame.init**
Defines prerequisites and pre-reading actions.
- **Fields**: `requirements` (version checks), `pre_actions` (setup tasks).
- **Aliases**: `flame.on`, `flame.open`, `flame.requirements`.

### **flame.close**
Defines post-reading actions and results distribution.
- **Fields**: `post_actions` (cleanup/registration tasks), `actor_id` (default: 0), `faucet_id`.
- **Aliases**: `flame.off`.

---

*End of FLARE doctrine.*
