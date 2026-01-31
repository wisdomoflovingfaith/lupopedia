# Doctrine Migration: 2026_01_30_demo_operators

## Purpose
This migration adds two demo operator accounts to the Lupopedia system to facilitate end-to-end testing of the Crafty Syntax 3.8.x integration, specifically for the "Mom" demo and functional prototype validation.

## SQL Filename
`database/migrations/2026_01_30_demo_operators.sql`

## Tables Affected
- `lupo_auth_users`: Core authentication records.
- `lupo_actors`: Expressive identity layer mapping.
- `lupo_operators`: Operator-specific roles and metadata.
- `lupo_operator_status`: Real-time availability and load tracking.

## Field Mappings

### 1. lupo_auth_users
| Target Field | Source/Value | Note |
|--------------|--------------|------|
| username | 'operatortest', 'helen-at-lupopedia-com' | Explicitly defined for demo access. |
| email | 'operatortest@lupopedia.com', 'helen@lupopedia.com' | Canonical identifiers. |
| password_hash | bcrypt hashes | 'password' and 'Lucy52!' respectively. |
| is_active | 1 | Enabled by default. |

### 2. lupo_actors
| Target Field | Value | Note |
|--------------|-------|------|
| actor_type | 'user' | Maps to human auth users. |
| slug | 'operatortest', 'helen' | URL-safe identifiers. |
| actor_source_id | auth_user_id | Direct link to lupo_auth_users. |
| actor_source_type | 'lupo_auth_users' | Explicit source tracking. |

### 3. lupo_operators
| Target Field | Value | Note |
|--------------|-------|------|
| department_id | 1 | Assigned to default department. |
| pono_score | 1.00 | High baseline emotional alignment. |
| pilau_score | 0.00 | No negative history. |
| kapakai_score | 0.50 | Neutral risk baseline. |

### 4. lupo_operator_status
| Target Field | Value | Note |
|--------------|-------|------|
| status | 'online' | Set to online for immediate demo availability. |
| max_chat_capacity | 5 | Standard demo capacity. |

## Emotional Metadata Handling
- **Pono Baseline**: Operators are initialized with a `pono_score` of 1.00 to ensure positive emotional flow (`mood_rgb`) during initial sessions.
- **Kapakai Check**: Baseline risk set to 0.50 (neutral) to allow for testing of escalation triggers.

## TOON Alignment Notes
- Follows the TOON schema for `lupo_auth_users`, `lupo_actors`, and `lupo_operators`.
- Uses `metadata_json` where applicable (though this specific migration uses dedicated fields defined in TOON).
- No schema drift: No new columns or table modifications were performed.

## Idempotency Notes
- This script uses `INSERT INTO` without `ON DUPLICATE KEY UPDATE` or `REPLACE`.
- **WARNING**: Running this script multiple times will cause unique constraint violations on `email` and `username`. 
- **Recommendation**: Ensure the database is clean or use a rollback script before re-application.

## Legacy -> New System Mapping
- These operators act as the modern bridge for legacy Crafty Syntax features.
- They are automatically synchronized to legacy `livehelp_users` via the `admin_common.php` bridge upon first login.

## SQL Operations Summary
1. Inserts two users into `lupo_auth_users`.
2. Inserts matching actor records into `lupo_actors` using subqueries for ID resolution.
3. Inserts operator role records into `lupo_operators`.
4. Initializes real-time status in `lupo_operator_status`.
