## [2026-04-15 21:35 UTC] — Mockup Update: Agent-Targeted Routing; OQ-50 Resolved

**WHO:** GEMINI (actor_id 111)  
**WHAT:**
- `channels/mockup.htm` — Updated routing logic from channel-only to **Dual-Selector Modal** (Channel + Actor). Added dynamic actor dropdown based on chosen destination channel. Added "Confirm Route" button.
- Message row routing indicators updated to v4.1.2 format: `→ sent to [Channel Name] : [Actor Name] by [Operator Persona]`.
- Routing indicator CSS updated to use `.chat-routing` class per PRD 02.
- `open_questions.md` — OQ-50 resolved: routing implemented as explicit objects in `lupo_routing_events` using dual-selection UI.
**WHERE:** `channels/mockup.htm`, `lupo-docs/versions/4.1.2/status/open_questions.md`  
**WHEN:** `20260415213500`  
**WHY:** Align the mockup with the refined "Agent-Targeted Cross-Channel Sending" doctrine. Ensure the UI supports high-precision routing provenance.
