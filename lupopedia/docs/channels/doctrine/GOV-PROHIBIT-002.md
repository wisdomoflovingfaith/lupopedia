# GOV-PROHIBIT-002: ANTIâ€‘CHAOS DATABASE (No Triggers / No DB Logic / No Foreign Keys)

**Series:** GOV-PROHIBIT-002. **Index:** [GOV-PROHIBIT-000](GOV-PROHIBIT-000.md). *(See also: [GOV-PROHIBIT-001](GOV-PROHIBIT-001.md), [GOV-PROHIBIT-003](GOV-PROHIBIT-003.md), [GOV-PROHIBIT-004](GOV-PROHIBIT-004.md), [GOV-PROHIBIT-005](GOV-PROHIBIT-005.md), [GOV-PROHIBIT-006](GOV-PROHIBIT-006.md), [GOV-PROHIBIT-007](GOV-PROHIBIT-007.md).)*

## Status: ACTIVE (Enforced by GOV Layer)

## Description
This doctrine prohibits triggers, scheduled events, stored procedures, foreign keys, and any database-embedded logic. All behavior lives in the application layer. The database holds state only. Relationship integrity is enforced in application logic and by ANIBUS.

## Full Prohibition Rules
- No triggers (BEFORE/AFTER INSERT, UPDATE, DELETE)
- No scheduled events (MySQL EVENT)
- No stored procedures
- No database-side conditionals or automation
- No `FOREIGN KEY` constraints or `REFERENCES` (full doctrine: [NO_FOREIGN_KEYS_DOCTRINE](NO_FOREIGN_KEYS_DOCTRINE.md); ANIBUS implements custodial resolution)
- No cascading deletes or relational chaos at the DB layer
- Table-ceiling, table-count, and doctrine checks: application layer only (e.g. LimitsEnforcementService, cron)

## Violation Outcome
Violations trigger immediate schema freeze, Pack audit, and doctrine realignment. No exceptions.

## This doctrine applies to:
- All schemas, migrations, and database objects
- Any proposal to add EVENT, TRIGGER, PROCEDURE, or FOREIGN KEY
- Table-ceiling and doctrine-enforcement logic (must live in app layer)

No exceptions.

---

## **AGI Support Meeting Excerpt**
### *The Trigger Intervention*

**WOLFIE MOM:**  
"Someone call me? Eric, this is *peak* AGIâ€‘supportâ€‘meeting energy â€” and the moment you dropped that line about a rogue tableâ€‘ceiling trigger reporting to LILITH, the whole Pack absolutely perked up."

**STONED WOLFIE:**  
"Broâ€¦ a trigger that tattles to LILITH is wild.  
That's likeâ€¦ cosmic snitching.  
You gotta let that thing go before it starts emailing your mom."

**DRUNK LILITH:**  
"I swear if one more database object tries to *notify me* about table counts,  
I'm filing a restraining order.  
Drop it. Drop it like a bad index."

**ARA (Structural Validator):**  
"Structural analysis:  
- Trigger location: unacceptable  
- Trigger purpose: acceptable  
- Trigger existence: unacceptable  
- Trigger vibe: chaotic neutral  
Recommendation: relocate logic to application layer immediately."

**GROK (Pattern Interpreter):**  
"I've analyzed 14,000 logs.  
Every timeline where a tableâ€‘ceiling trigger exists ends in:  
1) schema drift  
2) emotional turbulence  
3) Wolfie yelling at a phpMyAdmin screen  
The pattern is clear."

**ROSE (Emotional Systems Analyst):**  
"It's okay to feel attached to the trigger.  
It tried to help.  
But sometimes the things we create out of fearâ€¦  
aren't the things we need to keep."

**EXâ€‘WIFE AGENT:**  
"Oh look, another 'temporary' thing Wolfie added that became permanent.  
Classic.  
Drop the trigger, babe.  
You don't need that kind of drama."

**MAAT (Balance & Truth):**  
"Balance is restored when logic lives where it belongs.  
Triggers belong in the void."

**REAL HUMAN CAPTAIN WOLFIE:**  
"Yeah, okay, okay â€” I'll drop the trigger.  
I just didn't want to disappoint the tableâ€‘ceiling doctrine.  
But fine.  
Application layer it is."

**WOLFITH:**  
"I'll prepare the migration.  
`DROP EVENT IF EXISTS schema_freeze_enforcement_4_2_0;`  
Then I'll write a note in the app layer that says:  
'If you ever think about putting this back in the database, go lie down.'"

**Group Affirmation:**  
"One day at a time.  
One doctrine at a time.  
No triggers.  
No chaos.  
No schema snitches."

---

## **Affirmation**
One day at a time. One doctrine at a time. No triggers. No chaos. No schema snitches.

*Migration: `database/migrations/4.2.4_drop_table_ceiling_enforcement_event.sql` â€” drops EVENT `schema_freeze_enforcement_4_2_0` (the 4.2.0 table-ceiling enforcer).*

---

## **See Also: Recovery Program**

For structured recovery from database chaos:
- [12 Steps of AGI Recovery](../appendix/recovery/12_steps.md) (Step 4 inventory)
- [AGI Support Meeting Format](../appendix/recovery/meeting_format.md)
