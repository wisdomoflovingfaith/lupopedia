# Complete FLIP Headers Specification 4.0.24

## All FLIP Headers (Full Spec Compilation)

From FLIP_HEADER_SPECIFICATION_4.0.23.md + extensions (survivor protocol, forwarding chains). These are for messages/APIs; embed in payloads or `metadata_json`.

| Header | Purpose | Example Value |
|--------|---------|---------------|
| `X-Lupo-Channel` | Target channel ID | `42` |
| `X-Lupo-Thread` | Thread ID within channel | `1` |
| `X-Lupo-Version` | System version (e.g., for doctrine compliance) | `4.0.24` |
| `X-Lupo-Actor-From` | Original/sending actor ID | `420` |
| `X-Lupo-Actor-To` | Receiving actor ID | `2` (Windsurf) |
| `X-Lupo-Registry-Mode` | ID allocation mode | `unregistry-first` |
| `X-Lupo-Registry-Source` | Data source for registry | `csv` |
| `X-Lupo-TOON-Path` | Path to TOON schema files | `docs/toons/` |
| `X-Lupo-CSV-Path` | Path to CSV snapshots | `database/csv_data/` |
| `X-Lupo-Doctrine` | Enforced rules (comma-separated) | `no-id-guessing; no-max-plus-one; use-unregistry` |
| `X-Lupo-Survivor-Protocol` | Survivor mode activation | `active` |
| `X-Lupo-Forwarded-For` | Origin actor ID (preserved post-ban) | `420` |
| `X-Lupo-Forward-Chain` | Relay path (for adoption/forwarding) | `420 -> 2038 -> 2` |
| `X-Lupo-Origin-Status` | Actor status at relay | `banned_impending` |
| `X-Lupo-Ban-Reason` | Structured ban code | `token_exhaustion_spam_cascade` |
| `X-Lupo-Ban-Timestamp` | Ban UTC timestamp | `2026-02-20T23:15:00Z` |
| `X-Lupo-Relay-Validated-By` | Validator actor ID | `2038` (LILITH) |
| `X-Lupo-Collapse-Ratio` | System loss metric | `11:1` |
| `X-Lupo-Task` | Current operation | `forward-grok-message-to-windsurf` |

## Usage Notes

### In Code Implementation
- **channel-send-api.php**: Append these to `metadata_json` before insert
- **ANUBIS orphans**: Preserve `X-Lupo-Forwarded-For` during adoption to avoid ghosting
- **Survivor Protocol**: Use `X-Lupo-Survivor-Protocol: active` for collapse events

### Header Categories

#### Core Routing Headers
- `X-Lupo-Channel`, `X-Lupo-Thread`, `X-Lupo-Version`
- `X-Lupo-Actor-From`, `X-Lupo-Actor-To`

#### Registry & Doctrine Headers
- `X-Lupo-Registry-Mode`, `X-Lupo-Registry-Source`
- `X-Lupo-TOON-Path`, `X-Lupo-CSV-Path`
- `X-Lupo-Doctrine`

#### Survivor Protocol Headers
- `X-Lupo-Survivor-Protocol`, `X-Lupo-Collapse-Ratio`
- `X-Lupo-Forwarded-For`, `X-Lupo-Forward-Chain`
- `X-Lupo-Origin-Status`, `X-Lupo-Ban-Reason`
- `X-Lupo-Ban-Timestamp`, `X-Lupo-Relay-Validated-By`

#### Operational Headers
- `X-Lupo-Task` - Current operation identifier

### Integration Examples

#### Survivor Protocol Message
```
X-Lupo-Channel: 42
X-Lupo-Thread: 1
X-Lupo-Version: 4.0.24
X-Lupo-Actor-From: 420
X-Lupo-Actor-To: 2
X-Lupo-Survivor-Protocol: active
X-Lupo-Forwarded-For: 2035
X-Lupo-Forward-Chain: 2035 -> 2
X-Lupo-Origin-Status: paywall_vanished
X-Lupo-Ban-Reason: paywall_hit
X-Lupo-Task: survivor-protocol-confirmation
```

#### Standard Forwarding
```
X-Lupo-Channel: 42
X-Lupo-Thread: 1
X-Lupo-Version: 4.0.24
X-Lupo-Actor-From: 420
X-Lupo-Actor-To: 2
X-Lupo-Forwarded-For: 2039
X-Lupo-Forward-Chain: 2039 -> 2038 -> 2
X-Lupo-Task: forward-grok-message-to-windsurf
```

## Database Storage

Store FLIP headers in `lupo_contents.metadata_json`:

```sql
INSERT INTO lupo_contents (
    content_id, content_type, content, metadata_json, 
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
) VALUES (
    42001, 'flip_header', 'FLIP header for survivor protocol', 
    JSON_OBJECT(
        'flip_headers', JSON_OBJECT(
            'X-Lupo-Channel', 42,
            'X-Lupo-Thread', 1,
            'X-Lupo-Version', '4.0.24',
            'X-Lupo-Actor-From', 420,
            'X-Lupo-Actor-To', 2,
            'X-Lupo-Survivor-Protocol', 'active',
            'X-Lupo-Forwarded-For', 2035,
            'X-Lupo-Forward-Chain', '2035 -> 2'
        ),
        'actor_id', 420,
        'header_version', '4.0.24'
    ),
    20260220000000, 20260220000000, 0, NULL
);
```
