---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 0
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# WINDOWS COMPATIBILITY FOR UNIX COMMANDS
Windows agents MUST use WSL for Unix-style tooling (wc, ls, grep, sed):
1. Install WSL via 'wsl --install' in elevated shell.
2. Use 'wsl <command>' (e.g., 'wsl wc -l').
3. Do NOT use native Windows cmd/powershell equivalents for Unix scripts.
Rationale: Uniform toolchain across OS environments. Mandatory for script compatibility.


<!-- FLIP_FOOTER_BEGIN
{
    "references": "\"docs\/status\/broadcast_collection_0.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_0_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->