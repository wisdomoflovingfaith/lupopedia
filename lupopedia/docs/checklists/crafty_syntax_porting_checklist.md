# Crafty Syntax Porting Checklist

Practical engineering checklist for porting all Crafty Syntax features into Lupopedia.

============================================================
SECTION 1 — Public-Facing Features (Website Side)
============================================================
[ ] livehelp_js.php online/offline icon
[ ] Online/Offline icon rendering
[ ] Chat request popup / embedded window
[ ] Visitor session initialization
[ ] Visitor tracking (basic)
[ ] Chat start form (name/email optional)

============================================================
SECTION 2 — Operator Console Features
============================================================
[ ] Operator login
[ ] Operator availability (online/offline/away)
[ ] Incoming chat request panel
[ ] Operator chat console (send/receive)
[ ] Operator-to-operator messaging
[ ] Operator list panel
[ ] Canned responses panel
[ ] System activity panel

============================================================
SECTION 3 — Chat Session Mechanics
============================================================
[ ] Chat routing to available operator
[ ] Chat transcript storage
[ ] Chat history viewer
[ ] Chat transfer between operators
[ ] End chat workflow

============================================================
SECTION 4 — Visitor Tracking Features
============================================================
[ ] Live visitor list
[ ] Visitor page tracking
[ ] Visitor path tracking
[ ] Proactive invite popup

============================================================
SECTION 5 — Administrative Features
============================================================
[ ] Department management
[ ] Operator management
[ ] Settings panel (colors, icon style, offline behavior)
[ ] SMTP / email offline message handling

============================================================
SECTION 6 — Integration Tasks (Lupopedia-Specific)
============================================================
[ ] Map Crafty Syntax tables → Lupopedia tables
[ ] Update PHP endpoints to new table names
[ ] Update message send/receive endpoints
[ ] Update operator status endpoints
[ ] Update visitor tracking endpoints
[ ] Update chat routing logic
[ ] Update transcript storage logic
[ ] Update canned response storage
[ ] Update operator list queries
