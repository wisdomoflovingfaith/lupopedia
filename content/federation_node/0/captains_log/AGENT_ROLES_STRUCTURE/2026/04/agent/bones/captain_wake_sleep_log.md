# Captain Wake / Sleep Log

**Purpose:**  
Track Captain Wolfie wake, sleep, and pass-out events using canonical UTC time.

**Time Source:**  
All UTC timestamps MUST be generated using:  
`scripts/tick.py`

**Format:**  
`YYYYMMDDHHMMSS` (BIGINT UTC, full precision, no placeholders)

This file is a simple human log.  
It is **not** medical advice.

If fainting, blackouts, chest pain, confusion, injury, or repeated pass-out events occur, Captain should seek medical help.

---

## Sleep / Wake Timeline

**April 20–30 Daily Pattern**  
- Working: **16 hours/day** × 10 days = **160 hours**  
- Sleeping: **8 hours/day** × 10 days = **80 hours**

| pk_id           | event        | start               | end                 | duration | description           |
|-----------------|--------------|---------------------|---------------------|---------------------------------|
| 20260501010101  | sleep        | 2026-05-01 01:01:01 | 2026-05-01 11:28:01 |      627 | sleeping             |
| 20260501110102  | work         | 2026-05-01 11:28:01 | 2026-05-01 12:53:01 |        8 | patreon toc          |
 
