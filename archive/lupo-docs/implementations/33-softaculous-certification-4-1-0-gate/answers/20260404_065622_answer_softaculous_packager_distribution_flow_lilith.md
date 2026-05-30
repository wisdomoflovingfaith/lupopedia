---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404065622"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_065622_ANSWER_softaculous_packager_distribution_flow_lilith.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_065622_ANSWER_softaculous_packager_distribution_flow_lilith.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: answer
  artifact_kind: review_resolution
  thread_id: "33-softaculous-answers"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "complete"
  parent_pk_id: "33_softaculous_certification_4_1_0_gate"
  summary: ""
  module: null
  dialog_transcript: null
---
# file: ANSWER — Softaculous packager and distribution flow — LILITH — PRD 33

# ANSWER: Softaculous packager — what it does (LILITH)

**Answer UTC:** `20260404065622` — **LILITH** (**actor_id 2**). **Question:** `../questions/20260404_065622_QUESTION_softaculous_packager_distribution_flow.md`.

## Short verdict

**Yes (with two corrections):**

1. The packager builds a **distribution tree** — **not** a full git checkout. It is a **runtime-oriented rsync** of the repo **minus** explicit excludes, then a **sanitize** pass that removes **all** dot-directories and dotfiles. Archives are written from a **temporary** stage directory.
2. **Softaculous acceptance + shared-host smoke tests** are **necessary** evidence for the **hosting-distribution** part of **4.1.0**, but **tagging 4.1.0** still requires **all** **PRD 33 §10** completion criteria (Crafty parity, checklists, documented audit, etc.) — not packaging alone.

```yaml
verdict: "Packager = clean runtime distribution zip/tar, not the whole dev tree. 4.1.0 = full PRD 33 gate, not only Softaculous."
next_action: "Run ./lupo-scripts/build_softaculous_package.sh; validate per SOFTACULOUS_PACKAGE_BUILD.md; keep §10 matrix green."
final_truth: "Exclude everything that is not runtime; installer generates secrets, .htaccess, and writable dirs — WordPress-class pattern."
```

## What the packager does (canonical script)

**Script:** `lupo-scripts/build_softaculous_package.sh` (**Bash**, not Python). Run from any cwd; it resolves **repo root** from the script path.

**Staging:** `mktemp` under **`/tmp/lupopedia-${VERSION}.XXXXXX/`** (or OS temp), inner folder name **`lupopedia/`**.

**Output archives (default):** **`${REPO_ROOT}/dist/lupopedia-${VERSION}.zip`** and **`.tar.gz`** — override with **`DIST_DIR=/path ./lupo-scripts/build_softaculous_package.sh`**.

**Inner zip layout:** one top-level folder **`lupopedia/`** (Softaculous-friendly).

```yaml
what_it_does:
  - "rsync from REPO_ROOT into STAGE/lupopedia/ with explicit --exclude rules"
  - "delete any remaining dotdirs (.?*) and dotfiles (.?*) under STAGE"
  - "delete all .gitkeep under STAGE"
  - "zip + tar.gz from STAGE_PARENT into DIST_DIR"
  - "remove STAGE_PARENT (temp)"
```

## What is excluded (not an exhaustive prose list — see script)

**Development / IDE / VCS:** `.git/`, `.github/`, `.cursor/`, `.windsurf/`, `.kiro/`, `.lexa/`, `.lilith/`, `.cascade/`, `.vscode/`, `.idea/`, `.agents/`, `.codex/`, `__pycache__/`, `*.pyc`, common junk files.

**Docs and reference trees:** `lupo-docs/`, `craftysyntax-reference/`, `lupo-archive/legacy/wordpress-reference/` (local study copy; **`.gitignore`d**), `lupo-legacy/`, `lupo-research/`.

**Tooling and tests:** `lupo-scripts/` (the packager itself is **not** inside the zip), `lupo-tests/`, `lupo-tools/`, `node_modules/`, `dist/`.

**Generated schema exports:** `lupo-database/lupopedia/toon/`, `lupo-database/lupopedia/json/`.

**Rsync excludes for dot-auth files:** `.htaccess`, `.htpasswd` (then **sanitize** removes **any** other dotfiles).

**Included intentionally:** runtime PHP, themes, `lupo-database/` **SQL and content paths needed at runtime** (e.g. install/import/seed SQL; **not** the excluded toon/json export dirs).

## What the installer generates (target host)

- **`lupopedia-config.php`** (wizard — not shipped with secrets).
- **`.htaccess`** at docroot and **`lupo-database/.htaccess`** when **`InstallWizardHtaccessWriter`** determines an Apache-compatible stack (see **`isApacheHtaccessEnvironment()`**); Nginx/IIS: docs / server config.
- **Runtime directories:** **`lupo-cache/`**, **`lupo-logs/`**, **`lupo-uploads/`**, **`lupo-tmp/`** (canonical name **`lupo-tmp`**, not `lupo-temp`).

**Note:** **`lupo-config/lupopedia-config-sample.php`** is **specified** in **PRD 33** / LILITH backlog for manual-install hosts; if it is **not** yet in the tree, the packager cannot include it until the file exists.

## Flow (corrected command)

**Development machine**

```text
repo/  (full tree: .git, lupo-docs, lupo-scripts, …)
  |
  |  ./lupo-scripts/build_softaculous_package.sh [VERSION]
  ▼
dist/lupopedia-${VERSION}.zip   +   .tar.gz
  (only runtime subtree under lupopedia/; zero dotfiles in archive)
```

**Vendor / operator**

- Submit **`dist/lupopedia-*.zip`** per vendor process (**Softaculous** review: structure, hidden files, policy).
- End user extract → browse **`…/install.php`** → wizard runs SQL + config + `.htaccess` (when applicable).

## “Green light 4.1.0”

**Softaculous (or equivalent) PASS** satisfies **PRD 33** hosting / evidence expectations **only together with** the rest of **§10** (Crafty parity, §7.x checklists, LILITH constitutional audit for scoped code, **§12** traceability in version **TODO.md**, etc.). Treat packaging as **one** gate input, not the whole release definition.

---

This file complies with Lupopedia Constitutional Root Rules.
