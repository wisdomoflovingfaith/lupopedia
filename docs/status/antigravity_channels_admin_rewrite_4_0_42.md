# Channels Admin UI Rewrite & Ticket System Integration (Version 4.0.42)
## Status: COMPLETED

### 1. Summary of UI Changes
The Channels Admin UI has been completely rewritten to provide real-time operational metrics instead of static metadata.
- **New Layout**: Premium table design using 'Inter' and 'Outfit' typography.
- **Operational Metrics**:
    - **Active Actors (24h)**: Dynamic count of distinct actors sending messages in the last 24 hours.
    - **Thread Count**: Real-time count of dialog threads per channel.
    - **Ticket Count**: Total integrated tickets (including migrated doctrine refinements).
    - **Open Tickets**: Real-time visibility into pending issues/refinements.
- **Activity Tracking**: Added 'Last Activity' timestamp based on the latest message or ticket update.
- **Action Group**: Unified access to View details, Threads, and Tickets.

### 2. SQL Changes
- **New Tables**:
    - `lupo_tickets`: PK `ticket_id`, `channel_id`, `actor_id`, `status`, `priority`, `subject`, `metadata_json`.
    - `lupo_ticket_messages`: PK `ticket_message_id`, `ticket_id`, `actor_id`, `message_text`.
- **Doctrine Transition**:
    - `lupo_doctrine_refinements` marked as DEPRECATED in `install_new_lupopedia.sql`.
- **Indexes**:
    - Added high-performance indexes on `channel_id`, `actor_id`, `status` for tickets.

### 3. Ticket System Integration
- The new ticket system replaces the fragmented refinement/review workflows.
- Tickets are now the primary vehicle for:
    - Doctrine Refinements
    - System Corrections
    - Multi-Agent Coordination Issues
    - User Support (Legacy Crafty Syntax bridge)

### 4. Doctrine Refinement Migration
A migration script `database/migrations/migrate_refinements_to_tickets_4.0.42.sql` was created to perform the following:
- **Refinement -> Ticket**: Converts each row in `lupo_doctrine_refinements` into a ticket on Channel 42 (Development).
- **Status Mapping**:
    - `pending` -> `open`
    - `approved`/`rejected` -> `closed`
- **Metadata Preservation**: Original CIP event IDs, file paths, and hashes are preserved in `lupo_tickets.metadata_json`.
- **Content Preservation**: `change_description` is moved to the first message of the ticket in `lupo_ticket_messages`.

### 5. New Queries
- **Active Actors (24h)**: `SELECT COUNT(DISTINCT from_actor_id) FROM lupo_dialog_doctrine WHERE channel_id = ? AND created_ymdhis >= ?`
- **Open Tickets**: `SELECT COUNT(*) FROM lupo_tickets WHERE channel_id = ? AND status = 'open' AND is_deleted = 0`
- **Last Activity**: `MAX(created_ymdhis)` from both dialog and ticket tables.

### 6. VSX Updates (Required)
- VSX extension should be updated to point the "Tickets" button to the new unified ticketing UI.
- `lupopedia.tickets.lookup` endpoint should be used instead of any doctrine refinement specific APIs.

---
**Antigravity** (Actor 1003)
*Version Target: 4.0.42*
