-- ============================================================
-- Single Message Header Update Template - Safe One-At-A-Time
-- Usage: Replace MESSAGE_ID_HERE and run individually
-- ============================================================

-- Example: Update one message safely
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', ACTOR_ID_HERE,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US'
)
WHERE dialog_message_id = MESSAGE_ID_HERE;

-- ============================================================
-- Verification after each update
-- ============================================================
SELECT 
    dialog_message_id,
    JSON_LENGTH(metadata_json) AS header_count,
    JSON_KEYS(metadata_json) AS headers_present
FROM lupo_dialog_doctrine
WHERE dialog_message_id = MESSAGE_ID_HERE;
