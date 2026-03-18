---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/actors/lupo_actor_channels.md"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  artifact_type: "table_documentation"
  artifact_kind: "database_schema"
  purpose: "Complete documentation for lupo_actor_channels table - channel membership management"
  tags: ["table_documentation", "actors", "channels", "membership", "4.0.80"]
  created_ymdhis: 20260317170000
---

# lupo_actor_channels - Actor Channel Membership

**Table Type**: Membership Registry  
**Domain**: Channel System  
**Criticality**: HIGH - Controls channel access and participation  
**Primary Key**: `actor_channel_id`  
**Unique Key**: `(actor_id, channel_id)`

## Overview

The `lupo_actor_channels` table manages actor memberships in channels, providing the foundation for channel-based communication and collaboration in Lupopedia. It tracks which actors can access which channels, their membership status, and channel-specific preferences.

### Key Characteristics
- **Membership Control**: Central authority for channel access
- **Status Tracking**: Tracks membership status and participation
- **Preferences Storage**: Channel-specific user preferences
- **Activity Monitoring**: Tracks read status and activity patterns

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `actor_channel_id` | bigint | **PRIMARY KEY** - Unique membership ID | Application-assigned |
| `actor_id` | bigint | Actor ID | References `lupo_actors.actor_id` |
| `actor_name` | varchar(64) | Actor name cache | Denormalized for performance |
| `channel_id` | bigint | Channel ID | References `lupo_channels.channel_id` |
| `created_by_actor_id` | bigint | Who added actor to channel | Default 0 (system) |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `status` | char(1) | Membership status | 'A' (Active) |
| `start_date` | bigint | Membership start date | NULL (immediate) |
| `is_deleted` | tinyint | Membership is deleted | 0 |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL |

### Activity Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `last_read_ymdhis` | bigint | Last message read timestamp | NULL |
| `muted_until_ymdhis` | bigint | Mute expiration | NULL |

### Configuration Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `channel_color` | varchar(6) | Channel color hex | 'F7FAFF' |
| `preferences_json` | json | Channel preferences | NULL |
| `dialog_output_file` | varchar(500) | Output file path | NULL |

### Timestamp Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | 0 |
| `updated_ymdhis` | bigint | Last update timestamp | Current time |

## Indexes

### Primary Index
- `PRIMARY KEY (actor_channel_id)` - Unique membership identifier

### Unique Index
- `lupo_actor_channels_unq_actor_channel (actor_id, channel_id)` - Prevents duplicate memberships

### Performance Indexes
- `lupo_actor_channels_idx_actor (actor_id)` - Find actor's channels
- `lupo_actor_channels_idx_actor_name (actor_name)` - Find channels by actor name
- `lupo_actor_channels_idx_channel (channel_id)` - Find channel members
- `lupo_actor_channels_idx_status (status)` - Filter by membership status
- `lupo_actor_channels_idx_created (created_ymdhis)` - Sort by join time
- `lupo_actor_channels_idx_updated (updated_ymdhis)` - Sort by activity
- `lupo_actor_channels_idx_deleted (is_deleted)` - Filter deleted memberships

## Key Relationships

### Many-to-One Relationships
- **Actor**: `lupo_actor_channels.actor_id` → `lupo_actors.actor_id`
- **Channel**: `lupo_actor_channels.channel_id` → `lupo_channels.channel_id`
- **Creator**: `lupo_actor_channels.created_by_actor_id` → `lupo_actors.actor_id`

### Related Tables
- **Channel Roles**: `lupo_actor_channel_roles` - Role-based permissions
- **Channel Content**: `lupo_channel_content` - Channel-specific content
- **Dialog Messages**: `lupo_dialog_messages` - Channel communications

## Usage Patterns

### Channel Membership
```php
// Add actor to channel
$membership = [
    'actor_channel_id' => generateId(),
    'actor_id' => 102,
    'channel_id' => 42,
    'status' => 'A',
    'created_by_actor_id' => 1,
    'created_ymdhis' => 20260317170000,
    'updated_ymdhis' => 20260317170000
];
```

### Channel Access Check
```php
// Check if actor can access channel
$hasAccess = ChannelService::actorHasChannelAccess($actorId, $channelId);

// Get actor's channels
$channels = ChannelService::getActorChannels($actorId);

// Get channel members
$members = ChannelService::getChannelMembers($channelId);
```

### Status Management
```php
// Update membership status
ChannelService::updateMembershipStatus($actorChannelId, 'I'); // Inactive

// Mute channel
ChannelService::muteChannel($actorId, $channelId, $duration);

// Update last read
ChannelService::updateLastRead($actorId, $channelId, $timestamp);
```

## Membership Status Codes

| Status | Description | Usage |
|--------|-------------|-------|
| 'A' | Active | Full participation in channel |
| 'I' | Inactive | Member but not participating |
| 'S' | Suspended | Temporarily suspended |
| 'B' | Banned | Permanently banned from channel |
| 'P' | Pending | Membership awaiting approval |

## Channel Preferences

### Preference Structure
```json
{
    "notifications": {
        "enabled": true,
        "mentions_only": false,
        "email_enabled": false
    },
    "display": {
        "theme": "light",
        "font_size": "medium",
        "show_timestamps": true
    },
    "behavior": {
        "auto_mark_read": true,
        "show_join_leave": false,
        "compact_mode": false
    }
}
```

### Preference Management
```php
// Update preferences
$preferences = [
    'notifications' => [
        'enabled' => true,
        'mentions_only' => true
    ]
];
ChannelService::updateChannelPreferences($actorId, $channelId, $preferences);
```

## Activity Tracking

### Read Status
```php
// Update read status
ChannelService::markMessagesRead($actorId, $channelId, $lastMessageId);

// Get unread count
$unreadCount = ChannelService::getUnreadCount($actorId, $channelId);
```

### Muting
```php
// Mute channel for 24 hours
$muteUntil = time() + 86400;
ChannelService::muteChannel($actorId, $channelId, $muteUntil);

// Check if muted
$isMuted = ChannelService::isChannelMuted($actorId, $channelId);
```

## Security Considerations

### Access Control
- Validate membership before allowing channel access
- Check membership status for permission evaluation
- Implement proper ownership verification for channel operations
- Use role-based permissions for channel management

### Privacy Protection
- Respect user mute preferences
- Protect channel membership lists where appropriate
- Secure preference data in JSON field
- Audit channel access attempts

### Data Integrity
- Prevent duplicate memberships with unique constraint
- Maintain actor_name cache consistency
- Validate channel_id exists before membership creation
- Implement soft deletion with is_deleted flag

## Performance Considerations

### High-Volume Operations
- Cache frequently accessed channel memberships
- Batch membership checks for multiple channels
- Use appropriate indexes for common query patterns
- Implement membership caching for active users

### Optimization Strategies
```php
// Batch membership check
$channelIds = [42, 43, 44];
$memberships = ChannelService::getActorMemberships($actorId, $channelIds);

// Cache channel members
$members = CacheService::getChannelMembers($channelId);
if (!$members) {
    $members = ChannelService::getChannelMembers($channelId);
    CacheService::setChannelMembers($channelId, $members, 300);
}
```

## Common Queries

### Actor's Active Channels
```sql
SELECT c.channel_id, c.channel_name, c.channel_type, ac.status, ac.last_read_ymdhis
FROM lupo_actor_channels ac
JOIN lupo_channels c ON ac.channel_id = c.channel_id
WHERE ac.actor_id = 102 
  AND ac.status = 'A'
  AND ac.is_deleted = 0
  AND c.is_deleted = 0
ORDER BY ac.updated_ymdhis DESC;
```

### Channel Members with Status
```sql
SELECT a.actor_id, a.actor_name, a.actor_type, ac.status, ac.created_ymdhis
FROM lupo_actor_channels ac
JOIN lupo_actors a ON ac.actor_id = a.actor_id
WHERE ac.channel_id = 42 
  AND ac.is_deleted = 0
  AND a.is_deleted = 0
ORDER BY ac.status, a.actor_name;
```

### Unread Messages Count
```sql
SELECT 
    ac.channel_id,
    c.channel_name,
    COUNT(dm.dialog_message_id) as unread_count
FROM lupo_actor_channels ac
JOIN lupo_channels c ON ac.channel_id = c.channel_id
JOIN lupo_dialog_messages dm ON dm.channel_id = c.channel_id
WHERE ac.actor_id = 102 
  AND ac.status = 'A'
  AND ac.is_deleted = 0
  AND (ac.last_read_ymdhis IS NULL OR dm.created_ymdhis > ac.last_read_ymdhis)
GROUP BY ac.channel_id, c.channel_name;
```

### Channel Activity Summary
```sql
SELECT 
    c.channel_id,
    c.channel_name,
    COUNT(DISTINCT ac.actor_id) as member_count,
    SUM(CASE WHEN ac.status = 'A' THEN 1 ELSE 0 END) as active_members,
    MAX(ac.updated_ymdhis) as last_activity
FROM lupo_channels c
LEFT JOIN lupo_actor_channels ac ON c.channel_id = ac.channel_id AND ac.is_deleted = 0
WHERE c.is_deleted = 0
GROUP BY c.channel_id, c.channel_name
ORDER BY active_members DESC;
```

## Integration Points

### Authentication System
- Channel membership checked during login
- Default channel assignments for new users
- Integration with user registration flow

### Messaging System
- Read status tracking for message delivery
- Mute preferences for notification control
- Channel-based message routing

### Notification System
- Channel membership determines notification recipients
- Preference-based notification filtering
- Mute status affects notification delivery

## Troubleshooting

### Common Issues
1. **Duplicate Membership**: Check unique constraint violation
2. **Missing Access**: Verify status is 'A' and not deleted
3. **Stale Read Status**: Update last_read_ymdhis regularly
4. **Preference Issues**: Validate JSON structure

### Debug Queries
```sql
-- Check actor membership
SELECT * FROM lupo_actor_channels 
WHERE actor_id = 102 
  AND channel_id = 42 
  AND is_deleted = 0;

-- Find duplicate memberships
SELECT actor_id, channel_id, COUNT(*) as count
FROM lupo_actor_channels 
WHERE is_deleted = 0
GROUP BY actor_id, channel_id
HAVING COUNT(*) > 1;

-- Check inactive members
SELECT actor_id, status, updated_ymdhis
FROM lupo_actor_channels 
WHERE channel_id = 42 
  AND status != 'A'
  AND is_deleted = 0;
```

## Migration Notes

### Version History
- **v4.0.40**: Initial channel membership system
- **v4.0.60**: Added preferences_json and mute functionality
- **v4.0.75**: Enhanced status tracking and activity monitoring
- **v4.0.80**: Current schema with comprehensive membership management

### Breaking Changes
- Added status field for granular membership control
- Enhanced preferences with JSON storage
- Improved activity tracking with timestamps

## Best Practices

### Membership Management
- Always check membership before channel operations
- Use appropriate status codes for membership states
- Implement proper cleanup when removing members
- Cache membership data for performance

### Performance Optimization
- Batch membership operations when possible
- Use appropriate indexes for query patterns
- Implement membership caching for active users
- Monitor query performance for large channels

### User Experience
- Respect user preferences and mute settings
- Provide clear membership status indicators
- Implement proper read status tracking
- Offer granular notification controls

---

**Table Statistics**:
- **Records**: Variable based on channel usage
- **Size**: Medium - grows with user activity
- **Growth Rate**: Medium - new memberships as channels grow
- **Criticality**: HIGH - Controls channel access

**Dependencies**:
- **Required By**: Channel access throughout system
- **References**: `lupo_actors`, `lupo_channels`
- **Integrations**: Authentication, Messaging, Notifications

**Maintenance**:
- **Backup Priority**: HIGH
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review inactive memberships monthly
- **Monitoring**: Track membership growth and activity
