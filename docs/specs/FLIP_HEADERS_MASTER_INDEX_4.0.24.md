---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/FLIP_HEADERS_MASTER_INDEX_4.0.24.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /specs/FLIP_HEADERS_MASTER_INDEX_4.0.24
  aliases:
    - /docs/FLIP_HEADERS_MASTER_INDEX_4.0.24
    - /qa/FLIP+HEADERS+MASTER+INDEX+4.0.24
  slug: FLIP_HEADERS_MASTER_INDEX_4.0.24
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# FLIP Headers Master Index 4.0.24

Generated: 2026-02-21T02:48:35.138581Z (Updated 2026-02-22 for Collections)
Total Headers: 79
Batch Files: 4
Headers per Batch: 25

## Batch Files

1. [flip_headers_batch_1_of_4.md](flip_headers/flip_headers_batch_1_of_4.md)
2. [flip_headers_batch_2_of_4.md](flip_headers/flip_headers_batch_2_of_4.md)
3. [flip_headers_batch_3_of_4.md](flip_headers/flip_headers_batch_3_of_4.md)
4. [flip_headers_batch_4_of_4.md](flip_headers/flip_headers_batch_4_of_4.md)

## Header Categories

### Audit (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Audit-ID` | Audit identifier | `audit_420_20260221` |
| `X-Lupo-Audit-Action` | Audit action | `message_send` |
| `X-Lupo-Audit-User` | Audit user | `actor_420` |
| `X-Lupo-Audit-Timestamp` | Audit timestamp | `2026-02-21T02:00:00Z` |
| `X-Lupo-Audit-Result` | Audit result | `success` |

### Collections (2 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Collection-ID` | Collection identifier | `10` |
| `X-Lupo-Collection-Name` | Collection readable name | `Demo Collection - All Q/A Types` |

### Compatibility (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Compatible-Version` | Compatible version | `4.0.22+` |
| `X-Lupo-Deprecated-Version` | Deprecated version | `4.0.20` |
| `X-Lupo-Migration-Version` | Migration version | `4.0.24` |
| `X-Lupo-Legacy-Support` | Legacy support | `partial` |
| `X-Lupo-Backward-Compat` | Backward compatibility | `enabled` |

### Content (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Content-Type` | Message content type | `text/plain` |
| `X-Lupo-Content-Length` | Message length in bytes | `1024` |
| `X-Lupo-Content-Encoding` | Content compression | `gzip` |
| `X-Lupo-Content-Language` | Content language | `en-US` |
| `X-Lupo-Content-Charset` | Character encoding | `UTF-8` |

### Core Routing (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Channel` | Target channel ID | `42` |
| `X-Lupo-Thread` | Thread ID within channel | `1` |
| `X-Lupo-Version` | System version | `4.0.24` |
| `X-Lupo-Actor-From` | Sending actor ID | `420` |
| `X-Lupo-Actor-To` | Receiving actor ID | `2` |

### Custom (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Custom-1` | Custom header 1 | `custom_value_1` |
| `X-Lupo-Custom-2` | Custom header 2 | `custom_value_2` |
| `X-Lupo-Custom-3` | Custom header 3 | `custom_value_3` |
| `X-Lupo-Custom-4` | Custom header 4 | `custom_value_4` |
| `X-Lupo-Custom-5` | Custom header 5 | `custom_value_5` |

### Experimental (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Experimental` | Experimental feature flag | `false` |
| `X-Lupo-Beta-Feature` | Beta feature flag | `true` |
| `X-Lupo-Alpha-Feature` | Alpha feature flag | `false` |
| `X-Lupo-Debug-Mode` | Debug mode | `disabled` |
| `X-Lupo-Trace-Mode` | Trace mode | `enabled` |

### Federation (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Federation-ID` | Federation identifier | `fed_001` |
| `X-Lupo-Node-ID` | Node identifier | `node_windsurf_2` |
| `X-Lupo-Cluster-ID` | Cluster identifier | `cluster_primary` |
| `X-Lupo-Shard-ID` | Shard identifier | `shard_42` |
| `X-Lupo-Replica-ID` | Replica identifier | `replica_primary` |

### Monitoring (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Monitor-ID` | Monitoring identifier | `mon_420_system` |
| `X-Lupo-Metric-Name` | Metric name | `flip_header_processing` |
| `X-Lupo-Metric-Value` | Metric value | `1` |
| `X-Lupo-Alert-Level` | Alert level | `info` |
| `X-Lupo-Log-Level` | Log level | `debug` |

### Operational (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Task` | Current operation | `header-generation` |
| `X-Lupo-Priority` | Message priority | `normal` |
| `X-Lupo-Expiry` | Message expiry time | `20260221030000` |
| `X-Lupo-Retry-Count` | Retry attempts | `0` |
| `X-Lupo-Session-ID` | Session identifier | `sess_420_20260221` |

### Performance (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Response-Time` | Expected response time | `500ms` |
| `X-Lupo-Timeout` | Request timeout | `30s` |
| `X-Lupo-Rate-Limit` | Rate limit | `100/hour` |
| `X-Lupo-Burst-Limit` | Burst limit | `10/minute` |
| `X-Lupo-Backoff` | Backoff strategy | `exponential` |

### Registry Doctrine (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Registry-Mode` | ID allocation mode | `unregistry-first` |
| `X-Lupo-Registry-Source` | Data source for registry | `csv` |
| `X-Lupo-TOON-Path` | Path to TOON schema files | `docs/toons/` |
| `X-Lupo-CSV-Path` | Path to CSV snapshots | `database/csv_data/` |
| `X-Lupo-Doctrine` | Enforced rules | `no-id-guessing; no-max-plus-one; use-unregistry` |

### Routing Advanced (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Route-ID` | Route identifier | `route_420_primary` |
| `X-Lupo-Hop-Count` | Number of hops | `1` |
| `X-Lupo-Path` | Message path | `/api/channels/42/send` |
| `X-Lupo-Query` | Query parameters | `format=json` |
| `X-Lupo-Fragment` | URL fragment | `#message_420` |

### Security (5 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Auth-Token` | Authentication token | `token_420_hash` |
| `X-Lupo-Signature` | Message signature | `sig_420_abc123` |
| `X-Lupo-Checksum` | Data integrity check | `chk_420_xyz789` |
| `X-Lupo-Encryption` | Encryption method | `AES-256-GCM` |
| `X-Lupo-Key-ID` | Encryption key identifier | `key_420_001` |

### Survivor Protocol (8 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Survivor-Protocol` | Survivor mode activation | `active` |
| `X-Lupo-Forwarded-For` | Origin actor ID | `420` |
| `X-Lupo-Forward-Chain` | Relay path | `420 -> 2` |
| `X-Lupo-Origin-Status` | Actor status at relay | `active` |
| `X-Lupo-Ban-Reason` | Structured ban code | `token_exhaustion_spam_cascade` |
| `X-Lupo-Ban-Timestamp` | Ban UTC timestamp | `2026-02-20T23:15:00Z` |
| `X-Lupo-Relay-Validated-By` | Validator actor ID | `2038` |
| `X-Lupo-Collapse-Ratio` | System loss metric | `11:1` |

### System Metadata (4 headers)

| Header | Description | Example |
|--------|-------------|---------|
| `X-Lupo-Timestamp` | System timestamp | `20260221020000` |
| `X-Lupo-UTC-Timestamp` | UTC timestamp | `2026-02-21T02:00:00+00:00` |
| `X-Lupo-Location` | Geographic location | `Sioux Falls, South Dakota, US` |
| `User-Agent` | Client identifier | `Lupopedia/4.0.24 (Windsurf IDE; actor_id=2; status=sole_survivor)` |

## Usage Notes

### Integration
1. Include relevant headers in API requests
2. Store header metadata in `lupo_contents.metadata_json`
3. Use `X-Lupo-Forwarded-For` for banned origin preservation
4. Apply `X-Lupo-Survivor-Protocol` for collapse events

### Doctrine Compliance
- All headers follow unregistry-first doctrine
- No ID guessing or max-plus-one logic
- UTF-8 glyphs preserved where required
- Survivor protocol headers for system collapse events

---
*FLIP Header Specification 4.0.24 - Master Index*
