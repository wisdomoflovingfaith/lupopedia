---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: 20260224153600
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
# WINDOWS COMPATIBILITY FOR UNIX COMMANDS
Windows agents MUST use WSL for Unix-style tooling (wc, ls, grep, sed):
1. Install WSL via 'wsl --install' in elevated shell.
2. Use 'wsl <command>' (e.g., 'wsl wc -l').
3. Do NOT use native Windows cmd/powershell equivalents for Unix scripts.
Rationale: Uniform toolchain across OS environments. Mandatory for script compatibility.
<!-- FLIP_FOOTER_BEGIN
{
  "import_checksum": "a08e498dc42c73ab33c160d35e9e50490293f47ee9220024e84628c33e7dca4c",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}
FLIP_FOOTER_END -->
