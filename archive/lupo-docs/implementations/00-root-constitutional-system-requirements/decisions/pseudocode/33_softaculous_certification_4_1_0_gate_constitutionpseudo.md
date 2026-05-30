---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  file_path_from_root: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/33_softaculous_certification_4_1_0_gate_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/33_softaculous_certification_4_1_0_gate_constitution.pseudo.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# PRD 33 shorthand — Softaculous / shared-hosting / 4.1.0 gate

**Canonical:** [PRD 33](../../../../prd/33_softaculous_certification_4_1_0_gate.md)

## Role

- **Release gate** for **4.1.0**-class distribution (Softaculous, FTP zip, shared hosting).
- **4.0.x** line remains **Crafty 3.7.5 → Lupopedia** single install doctrine until this gate is satisfied (see **PRD 00**, **single-install** rules).

## Hosting assumptions

| Topic | Rule |
|-------|------|
| **PHP** | **7.4+** through supported **8.x** on shared core paths — probe extensions; never assume GD, mbstring, etc. |
| **Paths** | Subdirectory install — **`LUPOPEDIA_PUBLIC_PATH`**, **`LUPOPEDIA_PATH`** — no hardcoded docroot **`/`**. |
| **`.htaccess`** | **Optional** — installer **attempts** write when Apache-compatible; **runtime must work** without rewrites (**PRD 00 §2**, **§9.5**; PRD 33 **§14.6**). |
| **Permissions** | **Detect + warn** on mkdir / parent perms — **no** auto-**chmod** “repair.” |
| **User uploads (gate)** | **4.1.0**: no raw user binaries as “images” without decode/re-encode when GD (or approved lib) present; disable uploads + warn if missing. |

## WordPress study (pattern only)

- Marker-based **`.htaccess`** (**`# BEGIN LUPOPEDIA`** … **`# END LUPOPEDIA`**), **`web.config`** docs for IIS, sample config path — see **§14** and **`LEARNED_FROM_WORDPRESS.md`**.

## Crafty parity (high level)

- Operator multi-chat, visitor chat, **`livehelp_js.php` / `lupopedia_js.php`** embed contract, rollups (**`lupo_visits_*`**, paths), mobile visitor chat (**PRD 35** — separate mobile routes; admin not required on mobile web).

## Package

- Distribution zip: **no dotfiles**; **`build_softaculous_package.sh`** / **`SOFTACULOUS_PACKAGE_BUILD.md`**.
