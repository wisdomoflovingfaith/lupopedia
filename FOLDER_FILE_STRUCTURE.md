---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: FOLDER_FILE_STRUCTURE.md
  web_path: https://www.lupopedia.com/lupopedia/FOLDER_FILE_STRUCTURE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: working
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: ''
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: guide
  prd_cluster: ''
  title: Lupopedia Root Folder File Structure
  summary: Project root folder map and naming conventions.
---
# FOLDER_FILE_STRUCTURE

All Lupopedia files and folders live inside a project subfolder under the web root. The project is never the raw web root itself. Example install path: `/home/users/someone/public/lupopedia`, where `lupopedia` is the project folder.

## Root Folder Naming Rules

- Use lowercase names.
- Prefer short, semantic names (`api`, `app`, `docs`, `scripts`).
- Keep role-specific roots separate (runtime, docs, tooling, archive, temp).
- Keep hidden tool config folders prefixed with `.` (`.cursor`, `.vscode`, `.venv`).
- Do not hardcode absolute machine paths in code; rely on project constants/config.

## Root Folders in Lupopedia

- `.augment`, `.cascade`, `.claude`, `.cursor`, `.idea`, `.kiro`, `.lexa`, `.lilith`, `.qodo`, `.vscode`, `.windsurf`: IDE and agent surface config/state folders.
- `.venv`: local Python virtual environment for scripts and tooling.
- `actors`, `agents`: actor and agent domain data and related artifacts.
- `admin`, `admin_sections`: admin UI pages and segmented admin components.
- `api`: API endpoints and transport-facing handlers.
- `app`: core application classes/services and app-layer logic.
- `archive`: archived project artifacts retained for history/reference.
- `bin`: executable helper scripts.
- `cache`, `tmp`: transient cache and temporary working files.
- `changelog-archive`, `changelog-pending`: changelog workflow staging and history.
- `channel`, `channels`: channel/router-facing paths and channel artifacts.
- `chats`: chat-specific resources.
- `collections`: collection artifacts and collection indexing support.
- `config`: canonical runtime and environment configuration.
- `content`: content entities and content handling surfaces.
- `craftysyntax-reference`: read-only legacy Crafty Syntax reference material.
- `database`: database SQL, install/seed/import, and schema-related assets.
- `docs`: project documentation and doctrine/guide content.
- `emoji`: emoji assets/resources (non-runtime logic content).
- `hermies`: Hermes-related orchestration artifacts.
- `hooks`: hook scripts and hook integration surfaces.
- `images`, `uploads`: static image assets and user-uploaded files.
- `includes`: shared include files (bootstrap/helpers/theme wiring).
- `install`, `install_compare_backup`: installer resources and installer comparison backups.
- `kuliana`: project-specific subsystem folder.
- `logs`: log outputs and diagnostics.
- `memory`, `memories`: memory graph/state and historical memory artifacts.
- `meta`: metadata artifacts and metadata support files.
- `node_modules`: npm-managed dependency cache for local tooling.
- `registry`: registries and index-like identity mappings.
- `routes`: route definitions and route resolution helpers.
- `rules`: rule files and rule bundles.
- `runtime`: runtime-generated or runtime-scoped artifacts.
- `scratch`: scratchpad/workbench area for temporary experiments.
- `scripts`: automation scripts and utility tooling.
- `sessions`: session storage artifacts.
- `skills`: skill definitions and skill runtime assets.
- `templates`: reusable page/component templates.
- `tests`: test scripts and test fixtures.
- `tools`: developer tools and tool wrappers.
- `ui`: UI-specific shared assets and support files.
- `views`: rendered view templates.
## Artifact Folder Structure

All generated artifact files live under:

artifacts/{channel_key}/{thread_key}/{department_name}/{actor_name}/{YYYY}/{MM}/{DD}/

The `artifacts` folder is the canonical location for generated markdown, TOON, JSON, image, and other file-backed outputs that are not source code or PRD doctrine.

## Artifact Filename Format

Canonical filename format:

{artifact_id}_{optional_semantic_lens}_{slug}.{ext}

Where:

- artifact_id = deterministic BIGINT UTC timestamp ID
- optional_semantic_lens = pono, kuleana, kapu, shadow, pilau, agape, talk_story, or omitted
- slug = short lowercase subject slug
- ext = md, toon, json, jsonl, txt, png, jpg, etc.

Examples:

202605081101020000_folder_file_structure.md
202605081101020001_pono_folder_file_structure.md
202605081101020002_kapu_folder_file_structure.md

## Semantic Lens Rule

A semantic lens does not replace the artifact identity.

It describes the interpretive focus of that artifact.

- pono = desired/correct state
- kuleana = responsibility or meaning carried by actor
- kapu = forbidden boundary
- shadow = missing/absent/negative space
- pilau = corrupted/not pono
- agape = learned wisdom
- talk_story = exploratory/non-directive notes

If no semantic lens is present, the artifact is treated as a general canonical artifact.

## Database Rule

The database stores only:

- artifact_id
- file_path
- channel_key
- thread_key
- department_name
- actor_name
- semantic_lens
- slug
- ext
- hash
- size
- created_utc

The file system stores the content.

No LONGTEXT canonical artifact bodies belong in the database.

## Practical Path Reminder

Treat the project root as a subfolder-mounted application directory under web root, not as `/` for the host. Build URLs and filesystem references so they remain correct when installed as `/something/lupopedia`.
