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
