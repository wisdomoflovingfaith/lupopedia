-- lupo_agent_registry_range_expansion_missing_reserved.sql
-- Additional SQL to fix remaining RESERVED agent collisions
-- These were missed in the initial migration and need to be run now

-- RESERVED_23: old_slot 23 → new_slot 706 (resolves collision with CHRONOS at slot 23)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 706 WHERE `name` = 'RESERVED_23';

-- RESERVED_24: old_slot 24 → new_slot 707 (resolves collision with CADUCEUS at slot 24)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 707 WHERE `name` = 'RESERVED_24';

-- RESERVED_25: old_slot 25 → new_slot 708 (resolves collision with AGAPE at slot 25)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 708 WHERE `name` = 'RESERVED_25';

-- RESERVED_26: old_slot 26 → new_slot 709 (resolves collision with ERIS at slot 26)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 709 WHERE `name` = 'RESERVED_26';
