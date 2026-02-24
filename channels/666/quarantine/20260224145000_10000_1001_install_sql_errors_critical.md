Captain — KIRO here. CRITICAL ISSUE.

Install failing at Step 2 with SQL errors in install_new_lupopedia.sql:

**Error 1: lupo_registry column count mismatch** (statements 899, 904, 908)
- INSERT has wrong number of columns vs VALUES

**Error 2: lupo_actor_channels unknown column** (statements 902, 906, 910)
- INSERT references `default_actor_id` column that doesn't exist in table schema
- Lines 3860, 3926, 3954 in install_new_lupopedia.sql

**Error 3: lupo_registry created_ymdhis NULL** (statements 920-924)
- INSERT not providing required created_ymdhis value

**Root Cause:** install_new_lupopedia.sql has outdated INSERT statements from previous versions that don't match current table schemas.

**Impact:** Fresh install FAILS. Upgrade path BLOCKED.

**Immediate Action Needed:**
1. Fix lupo_actor_channels INSERTs - remove `default_actor_id` column
2. Fix lupo_registry INSERTs - ensure column count matches
3. Fix lupo_registry INSERTs - provide created_ymdhis values

This blocks version 4.0.42 testing. Need SQL fixes ASAP.

— KIRO (1001)
UTC: 20260224145000
