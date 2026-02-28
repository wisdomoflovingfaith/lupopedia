# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\dialogs_old_replaced_by_channels\GOV_dialog-side.md"
  file_hash: "df354a4509ec1bc0689962aaa1d0b3de7e2623e97b1bbc7ac70b15d01642518d"
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
  file_path_from_root: "dialogs_old_replaced_by_channels\GOV_dialog-side.md"
  file_hash: "fb498c580673f077bc58c98a28164b845575ebb004b8d34e5ce1520fba337188"
  file_path_from_root: "dialogs_old_replaced_by_channels\GOV_dialog-side.md"
  file_hash: "3ba3a507ed70307dc036535ca356664b29a25e135e25decf8f26f85ccf011318"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for GOV_dialog-side.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["dialogs_old_replaced_by_channels", "gov_dialog-sidemd"]
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
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "GOV_dialog-side.md"
file.last_modified_system_version: 3.1.10
file.last_modified_utc: 20260120120000
file.utc_day: 20260120
UTC_TIMEKEEPER__CHANNEL_ID: "dev"

sync_role: "GOV_dialog-side"
sync_pair:
  primary: "dialogs/GOV_dialog.md"
  secondary: "dialogs/changelog_dialog.md"

GOV-AD-PROHIBIT-001: true

header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID

temporal_edges:
  actor_identity: "GOVERNANCE"
  system_context: "Governance channel sync-side / File-Sovereignty"

dialog:
  speaker: CURSOR
  target: @GOVERNANCE @CAPTAIN_WOLFIE
  mood_RGB: "00CCFF"
  message: "GOV_dialog-side initialized. Sync companion for GOV_dialog.md. current_sync_state: governance_channel active."

tags:
  categories: ["documentation", "sync", "dialog-side", "governance"]
  channels: ["dev", "governance"]
  collections: ["sync-pair", "dialog-system", "governance"]

file:
  title: "GOV_dialog Sync — Dialog-Side"
  description: "Sync-side companion for dialogs/GOV_dialog.md. Governance channel state and cross-references."
  version: "1.0"
  status: active
  author: GLOBAL_CURRENT_AUTHORS

current_sync_state:
  governance_channel: active
---

# GOV_dialog-side

**Role:** Sync-side companion for **dialogs/GOV_dialog.md**.  
**Full thread:** `dialogs/GOV_dialog.md`

---

### GOVERNANCE — Channel Activation (2026-01-20)

- Governance dialog channel created
- Monitoring doctrine, table count, protocol expansion
- Linked to LILITH Critical Review

---

**Last updated:** 2026-01-20  
**Sync pair:** dialogs/GOV_dialog.md ↔ dialogs/GOV_dialog-side.md