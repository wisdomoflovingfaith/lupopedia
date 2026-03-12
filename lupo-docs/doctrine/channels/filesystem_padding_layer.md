# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\channels\filesystem_padding_layer.md"
  file_hash: "fb62de18e5144fb3fa545708a8b3c4d850a8f63a683cf904e3eb273b04656141"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\doctrine\channels\filesystem_padding_layer.md"
  file_hash: "84f8ad49f0f390b0c98958437334e0e36ee363ac063d340d22a632611754fa70"
  file_path_from_root: "docs\doctrine\channels\filesystem_padding_layer.md"
  file_hash: "98c667c5db62c1dbdae197a7d72ab3889b9eba8e4d4d4b4358a529da2d7a4d3c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for filesystem_padding_layer.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "channels", "filesystem_padding_layermd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/channels/filesystem_padding_layer.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/channels/filesystem_padding_layer.md
---

# Filesystem Padding Layer (Channels)

# Channel Directory Padding Rule
Purpose:
Maintain human-friendly sorting in /channels/ without altering the semantic meaning of channel identifiers.

# Doctrine
Channel numbers are semantic identifiers.
They MUST be stored and interpreted as unpadded integers.
Example:

Code
channel_number = 42
channel_key = "42"
Filesystem directories MAY use leading zeros for sorting.
This is a presentation-layer convenience, not a semantic identifier.
Example:

Code
/channels/0042/
Tools MUST normalize padded directory names.
When reading channel directories, all leading zeros MUST be stripped.
Example:

Code
"0042" -> 42
"0007" -> 7
"0051" -> 51
Tools MUST NOT write padded identifiers into manifests or metadata.
All channel metadata MUST use the unpadded form.

Directory padding MUST NOT influence routing, registry logic, or doctrine.
The padded directory name is a filesystem artifact only.

If a conflict arises between padded and unpadded forms, the unpadded form is canonical.

Rationale:
This rule preserves doctrinal purity (semantic channel numbers) while allowing the filesystem to remain visually sorted and stable for developers and tools.