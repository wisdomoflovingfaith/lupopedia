---
lupopedia.headers:
  when_updated: "20260328130000"
  lupopedia.schema: "rule"
  file_path_from_root: ".windsurf/rules/lupopedia-rules.md"
  web_path: "http://www.lupopedia.com/lupopedia/.windsurf/rules/lupopedia-rules.md"
  last_modified_utc: "20260328130000"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "rule"
  artifact_kind: "configuration"
  purpose: Add Windows WSL prefix rule to IDE configuration
  tags:
  - "4.0.89"
  - "wsl"
  - "windows"
  - "command_prefix"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/WINDOWS_WSL_COMMAND_PATTERNS.md"
      type: references
      weight: 1.0
      reason: Core WSL command pattern rule
lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - "Add to IDE agent rules"
    - "Update documentation"
    - "Ensure all IDE agents follow WSL prefix rule"
---

# Windows + WSL Command Prefix Rule

## Rule Statement

**When running any Unix command (grep, find, sed, awk, cat, head, tail, etc.) on Windows, ALWAYS prefix with `wsl`.**

This rule applies to ALL IDE agents and ensures commands work correctly in Windows + WSL environments.

## Command Reference

| Command | Correct (Windows) | Incorrect (fails) |
|---------|-------------------|-------------------|
| grep | `wsl grep -r "pattern" .` | `grep -r "pattern" .` |
| find | `wsl find . -name "*.php"` | `find . -name "*.php"` |
| sed | `wsl sed -i 's/old/new/g' file` | `sed -i 's/old/new/g' file` |
| awk | `wsl awk '{print $1}' file` | `awk '{print $1}' file` |
| cat | `wsl cat file.txt` | `cat file.txt` |
| head | `wsl head -n 5 file.txt` | `head -n 5 file.txt` |
| tail | `wsl tail -n 5 file.txt` | `tail -n 5 file.txt` |
| echo | `wsl echo "text"` | `echo "text"` |
| ls | `wsl ls -la` | `ls -la` |
| wc | `wsl wc -l file.txt` | `wc -l file.txt` |

## Implementation Notes

- This rule is **non-negotiable** for Windows environments
- Commands without `wsl` prefix will fail on Windows + WSL
- IDE agents must check if command is Unix-style before execution
- When in doubt, use `wsl` prefix on Windows
