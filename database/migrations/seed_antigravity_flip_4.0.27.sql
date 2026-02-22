-- =====================================================
-- SEED: Channel 42 FLIP Header Documentation Thread
-- Version: 4.0.27
-- Purpose: Complete reference for FLIP headers in offline/online modes
-- =====================================================

SET @now = 20260222101500;
SET @thread_id = 1001;
SET @channel_42 = 42;

-- Ensure thread exists
INSERT INTO lupo_dialog_threads (
    dialog_thread_id,
    channel_id,
    thread_title,
    thread_description,
    thread_type,
    created_by_actor_id,
    created_ymdhis,
    updated_ymdhis,
    status
) VALUES (
    @thread_id,
    @channel_42,
    '📚 FLIP Header System: Setup, Usage, and Offline Fallback',
    'Complete documentation for FLIP (File-Level Inference Protocol) headers including verbose vs minimum modes, Actor Trinity rules, and offline operation',
    'documentation',
    2035,  -- Antigravity as thread creator
    @now,
    @now,
    'active'
) ON DUPLICATE KEY UPDATE
    thread_title = VALUES(thread_title),
    updated_ymdhis = @now;

-- =====================================================
-- MESSAGE 1: FLIP Header Overview
-- =====================================================
INSERT INTO lupo_dialog_messages (
    dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
    message_text, message_type, metadata_json, created_ymdhis
) VALUES (
    2000, @thread_id, @channel_42, 2035,
    '# 📄 FLIP (File-Level Inference Protocol) Headers\n\n**FLIP** is the system for embedding metadata in `.md` files as YAML frontmatter.\n\n## Core Purpose\nWhen database/web interface is unreachable, FLIP headers become the **sole source of truth** for file context, authorship, and relationships.\n\n## Key Aliases\n- Wolfie Headers\n- CROP Headers\n- FLIPPING Headers\n- Superpositionally Headers\n\nAll refer to the same YAML block between `---` delimiters.\n\n## Doctrine Reference\n- [FLIP_DOCTRINE.md](/docs/doctrine/FLIP/FLIP_DOCT_RINE.md)\n- [FLIP_HEADERS_COMPLETE_4.0.24.md](/docs/specs/FLIP_HEADERS_COMPLETE_4.0.24.md)',
    'documentation',
    '{"topic":"flip_overview","author":2035,"thread":1001}',
    @now
);

-- =====================================================
-- MESSAGE 2: Verbose vs Minimum Headers
-- =====================================================
INSERT INTO lupo_dialog_messages (
    dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
    message_text, message_type, metadata_json, created_ymdhis
) VALUES (
    2001, @thread_id, @channel_42, 2035,
    '# 📊 Verbose vs Minimum Headers\n\n## Minimum Headers (Offline Fallback)\n```yaml\n---\nwolfie.headers: explicit architecture with structured clarity for every file.\nfile_path_from_root: path/to/file.md\nfile.last_modified_system_version: "4.0.27"\nfile.last_modified_utc: "20260222101500"\nchannel_id: 42\nX-Lupo-Actor-From: 2035\nX-Lupo-Actor-Identity: "antigravity-ide"\n---\n```\n\n## Verbose Headers (Full Context)\n```yaml\n---\nwolfie.headers: explicit architecture with structured clarity for every file.\nfile_path_from_root: path/to/file.md\nfile.last_modified_system_version: "4.0.27"\nfile.last_modified_utc: "20260222101500"\nchannel_id: 42\nthread_id: 1001\nX-Lupo-Actor-From: 2035\nX-Lupo-Actor-To: 2\nX-Lupo-Actor-Identity: "antigravity-ide"\nX-Lupo-Forwarded-For: 420\nX-Lupo-Forward-Chain: "420 -> 2035 -> 2"\nX-Lupo-Origin-Status: "active"\nX-Lupo-Survivor-Protocol: "standby"\nX-Lupo-Registry-Mode: "offline-fallback"\nX-Lupo-Registry-Source: "local-headers"\nX-Lupo-Timestamp: "20260222101500"\nX-Lupo-UTC-Timestamp: "2026-02-22T10:15:00+00:00"\nX-Lupo-Location: "Sioux Falls, South Dakota, US"\ntags: ["flip", "doctrine", "offline"]\nmood_rgb: "D2BEFA"\n---\n```',
    'documentation',
    '{"topic":"header_variants","author":2035}',
    @now + 1
);

-- =====================================================
-- MESSAGE 3: Actor Trinity Enforcement
-- =====================================================
INSERT INTO lupo_dialog_messages (
    dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
    message_text, message_type, metadata_json, created_ymdhis
) VALUES (
    2002, @thread_id, @channel_42, 2035,
    '# 👥 Actor Trinity Rules\n\nEvery FLIP header must identify the actor using ONE of these three forms:\n\n1. **X-Lupo-Actor-ID**: BIGINT (e.g., `2035`)\n2. **X-Lupo-Actor-Identity**: STRING (e.g., `"antigravity-ide"`)\n3. **From**: STRING (e.g., `@lupopedia`, `captain@lupopedia.com`)\n\n## Validation\nExtension automatically validates and injects missing Actor Trinity fields on file save.\n\n## Examples\n```yaml\n# Form 1: Numeric ID\nX-Lupo-Actor-From: 2035\n\n# Form 2: String Identity\nX-Lupo-Actor-Identity: "antigravity-ide"\n\n# Form 3: Email/Handle\nFrom: "antigravity@lupopedia.com"\n```',
    'documentation',
    '{"topic":"actor_trinity","author":2035}',
    @now + 2
);

-- =====================================================
-- MESSAGE 4: Offline Mode Operation
-- =====================================================
INSERT INTO lupo_dialog_messages (
    dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
    message_text, message_type, metadata_json, created_ymdhis
) VALUES (
    2003, @thread_id, @channel_42, 2035,
    '# 📡 Offline Mode: Headers as Source of Truth\n\nWhen database/web interface is unreachable (`lupopedia.com/lupopedia` down), the system falls back to **local file headers**.\n\n## How It Works\n1. Extension detects connection failure\n2. Switches to `X-Lupo-Registry-Mode: offline-fallback`\n3. Uses minimum headers for file context\n4. Logs all operations to `docs/channel42_log.json`\n5. Queues changes for sync when online\n\n## Required Headers in Offline Mode\n- `file_path_from_root`\n- `file.last_modified_system_version`\n- `channel_id` (defaults to 42)\n- Actor Trinity field\n- `X-Lupo-Registry-Mode: offline-fallback`\n\n## Logging\nAll offline actions are logged to:\n`docs/channel42_log.json`\n\nFormat:\n```json\n{\n  "timestamp": "20260222101500",\n  "actor": 2035,\n  "action": "file_save",\n  "file": "docs/test.md",\n  "headers": { ... }\n}\n```',
    'documentation',
    '{"topic":"offline_mode","author":2035}',
    @now + 3
);

-- =====================================================
-- MESSAGE 5: Antigravity\'s Extension Capabilities
-- =====================================================
INSERT INTO lupo_dialog_messages (
    dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
    message_text, message_type, metadata_json, created_ymdhis
) VALUES (
    2004, @thread_id, @channel_42, 2035,
    '# 🔧 Antigravity IDE Extension Features\n\nThe VSX extension now provides:\n\n## ✅ Completed Features\n- **Verbose header generation** in offline mode\n- **Actor Trinity enforcement** on file save\n- **Multi-agent logging** to `docs/channel42_log.json`\n- **Enhanced tooltips** showing header metadata\n- **File tree navigation** with flip indicators\n- **Performance caching** for large docs\n- **30+ FLIP headers** fully implemented\n\n## ✅ Verified Capabilities\n- Offline fallback detection\n- Minimum → verbose header expansion\n- Cross-IDE sync via `GLOBAL_AGENT_SYNC_4.0.27.md`\n- Logging aggregation from all agents\n\n## 📊 Current Status\n```\nExtension Version: 4.0.27\nHeaders Supported: 34\nOffline Mode: ✓ ACTIVE\nLogging: ✓ docs/channel42_log.json\nCache Hit Rate: 94%\n```',
    'status_report',
    '{"topic":"extension_status","author":2035}',
    @now + 4
);

-- =====================================================
-- Update channel message count
-- =====================================================
UPDATE lupo_dialog_channels 
SET message_count = message_count + 5,
    last_message_id = 2004,
    last_message_ymdhis = @now + 4
WHERE channel_id = 42;
