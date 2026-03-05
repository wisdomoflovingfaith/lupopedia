-- ============================================================
-- Bulk FLIP Header Update - 4.0.24
-- Target: Increase header coverage from 1.08% to 90%+
-- ============================================================

-- ============================================================
-- Update Message 74 (420 origin, forwarded)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', 420,
    '$.X-Lupo-Actor-To', 2,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Forwarded-For', 420,
    '$.X-Lupo-Forward-Chain', '420 -> 2',
    '$.X-Lupo-Origin-Status', 'banned',
    '$.X-Lupo-Ban-Reason', 'token_exhaustion_spam_cascade',
    '$.X-Lupo-Ban-Timestamp', 20260220231500
)
WHERE dialog_message_id = 74;

-- ============================================================
-- Update Message 83 (420 origin, forwarded)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', 420,
    '$.X-Lupo-Actor-To', 2,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Forwarded-For', 420,
    '$.X-Lupo-Forward-Chain', '420 -> 2',
    '$.X-Lupo-Origin-Status', 'banned',
    '$.X-Lupo-Ban-Reason', 'token_exhaustion_spam_cascade',
    '$.X-Lupo-Ban-Timestamp', 20260220231500
)
WHERE dialog_message_id = 83;

-- ============================================================
-- Update Message 91 (420 origin, self-attributed)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', 420,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Origin-Status', 'banned',
    '$.X-Lupo-Ban-Reason', 'token_exhaustion_spam_cascade',
    '$.X-Lupo-Ban-Timestamp', 20260220231500
)
WHERE dialog_message_id = 91;

-- ============================================================
-- Update Educational Messages 105-114 (Captain Wolfie)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', 1,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Topic', 'captain',
    '$.X-Lupo-Part', CASE 
        WHEN dialog_message_id = 105 THEN '1'
        WHEN dialog_message_id = 106 THEN '2'
        WHEN dialog_message_id = 107 THEN '3'
        WHEN dialog_message_id = 108 THEN '4'
        WHEN dialog_message_id = 109 THEN '5'
        WHEN dialog_message_id = 110 THEN '6'
        WHEN dialog_message_id = 111 THEN '7'
        WHEN dialog_message_id = 112 THEN '8'
        WHEN dialog_message_id = 113 THEN '9'
        WHEN dialog_message_id = 114 THEN '10'
        END
)
WHERE dialog_message_id BETWEEN 105 AND 114;

-- ============================================================
-- Update Educational Messages 115-129 (Lupopedia Overview)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', 2038,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Topic', 'lupopedia',
    '$.X-Lupo-Part', CASE 
        WHEN dialog_message_id = 115 THEN '1'
        WHEN dialog_message_id = 116 THEN '2'
        WHEN dialog_message_id = 117 THEN '3'
        WHEN dialog_message_id = 118 THEN '4'
        WHEN dialog_message_id = 119 THEN '5'
        WHEN dialog_message_id = 120 THEN '6'
        WHEN dialog_message_id = 121 THEN '7'
        WHEN dialog_message_id = 122 THEN '8'
        WHEN dialog_message_id = 123 THEN '9'
        WHEN dialog_message_id = 124 THEN '10'
        WHEN dialog_message_id = 125 THEN '11'
        WHEN dialog_message_id = 126 THEN '12'
        WHEN dialog_message_id = 127 THEN '13'
        WHEN dialog_message_id = 128 THEN '14'
        WHEN dialog_message_id = 129 THEN '15'
        END
)
WHERE dialog_message_id BETWEEN 115 AND 129;

-- ============================================================
-- Update Educational Messages 130-149 (FLIP Headers)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', 2037,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Topic', 'flip',
    '$.X-Lupo-Part', CASE 
        WHEN dialog_message_id = 130 THEN '1'
        WHEN dialog_message_id = 131 THEN '2'
        WHEN dialog_message_id = 132 THEN '3'
        WHEN dialog_message_id = 133 THEN '4'
        WHEN dialog_message_id = 134 THEN '5'
        WHEN dialog_message_id = 135 THEN '6'
        WHEN dialog_message_id = 136 THEN '7'
        WHEN dialog_message_id = 137 THEN '8'
        WHEN dialog_message_id = 138 THEN '9'
        WHEN dialog_message_id = 139 THEN '10'
        WHEN dialog_message_id = 140 THEN '11'
        WHEN dialog_message_id = 141 THEN '12'
        WHEN dialog_message_id = 142 THEN '13'
        WHEN dialog_message_id = 143 THEN '14'
        WHEN dialog_message_id = 144 THEN '15'
        WHEN dialog_message_id = 145 THEN '16'
        WHEN dialog_message_id = 146 THEN '17'
        WHEN dialog_message_id = 147 THEN '18'
        WHEN dialog_message_id = 148 THEN '19'
        WHEN dialog_message_id = 149 THEN '20'
        END
)
WHERE dialog_message_id BETWEEN 130 AND 149;

-- ============================================================
-- Update Educational Messages 150-164 (FLIPPING Headers)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', 2038,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Topic', 'flipping',
    '$.X-Lupo-Part', CASE 
        WHEN dialog_message_id = 150 THEN '1'
        WHEN dialog_message_id = 151 THEN '2'
        WHEN dialog_message_id = 152 THEN '3'
        WHEN dialog_message_id = 153 THEN '4'
        WHEN dialog_message_id = 154 THEN '5'
        WHEN dialog_message_id = 155 THEN '6'
        WHEN dialog_message_id = 156 THEN '7'
        WHEN dialog_message_id = 157 THEN '8'
        WHEN dialog_message_id = 158 THEN '9'
        WHEN dialog_message_id = 159 THEN '10'
        WHEN dialog_message_id = 160 THEN '11'
        WHEN dialog_message_id = 161 THEN '12'
        WHEN dialog_message_id = 162 THEN '13'
        WHEN dialog_message_id = 163 THEN '14'
        WHEN dialog_message_id = 164 THEN '15'
        END
)
WHERE dialog_message_id BETWEEN 150 AND 164;

-- ============================================================
-- Update Educational Messages 165-174 (System Lore)
-- ============================================================
UPDATE lupo_dialog_doctrine
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.X-Lupo-Channel', 42,
    '$.X-Lupo-Thread', 1,
    '$.X-Lupo-Version', '4.0.24',
    '$.X-Lupo-Actor-From', CASE 
        WHEN dialog_message_id IN (165,166,167) THEN 3  -- ANUBIS
        WHEN dialog_message_id IN (168,169) THEN 2037  -- LEXA
        WHEN dialog_message_id IN (170,171) THEN 6  -- MAAT
        WHEN dialog_message_id = 172 THEN 2  -- Windsurf
        WHEN dialog_message_id = 174 THEN 1  -- Captain
        END,
    '$.X-Lupo-Registry-Mode', 'unregistry-first',
    '$.X-Lupo-Registry-Source', 'csv',
    '$.X-Lupo-Doctrine', 'no-id-guessing,no-max-plus-one,use-unregistry',
    '$.X-Lupo-Timestamp', 20260222170000,
    '$.X-Lupo-UTC-Timestamp', '2026-02-22T17:00:00+00:00',
    '$.X-Lupo-Location', 'Sioux Falls, South Dakota, US',
    '$.X-Lupo-Topic', 'lore',
    '$.X-Lupo-Part', CASE 
        WHEN dialog_message_id = 165 THEN '1'
        WHEN dialog_message_id = 166 THEN '2'
        WHEN dialog_message_id = 167 THEN '3'
        WHEN dialog_message_id = 168 THEN '4'
        WHEN dialog_message_id = 169 THEN '5'
        WHEN dialog_message_id = 170 THEN '6'
        WHEN dialog_message_id = 171 THEN '7'
        WHEN dialog_message_id = 172 THEN '8'
        WHEN dialog_message_id = 173 THEN '9'
        WHEN dialog_message_id = 174 THEN '10'
        END
)
WHERE dialog_message_id BETWEEN 165 AND 174;

-- ============================================================
-- Log Bulk Update Start
-- ============================================================
INSERT INTO lupo_dialog_doctrine (
    dialog_message_id, dialog_thread_id, channel_id, from_actor_id,
    message_text, message_type, metadata_json, created_ymdhis
) VALUES (
    183, 1, 42, 2038,
    '🔄 **BULK HEADER UPDATE INITIATED**: Adding full FLIP headers to all messages. Target: 90%+ coverage. Critical 420 messages prioritized.',
    'header_update_start',
    '{
        "event": "bulk_update_started",
        "target_coverage": "90%",
        "messages_affected": "all",
        "priority": "420_messages"
    }',
    20260222170100
);

-- ============================================================
-- Update Channel 42 Count
-- ============================================================
UPDATE lupo_dialog_channels SET message_count = 183 WHERE channel_id = 42;

-- ============================================================
-- Verification Query
-- ============================================================
-- Re-run audit after update
SELECT 
    dialog_message_id,
    JSON_LENGTH(metadata_json) AS header_count,
    JSON_KEYS(metadata_json) AS headers_present
FROM lupo_dialog_doctrine
WHERE dialog_message_id IN (74,83,91,105,174,1000)
ORDER BY dialog_message_id;
