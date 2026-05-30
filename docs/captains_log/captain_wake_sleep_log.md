# Captain Wake / Sleep Log

**Related:**  
docs/captains_log/health_note_april_2026.md

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

**April 20–25 Daily Pattern**  
- Working: **16 hours/day** × 6 days = **96 hours**  
- Sleeping: **8 hours/day** × 6 days = **48 hours**

| Event                  | UTC (tick.py)  | Local Time (Sioux Falls)     | Duration since last event in minutes | Notes                                                                 |
|------------------------|----------------|------------------------------|--------------------------------------|-----------------------------------------------------------------------|
| woke up                | 20260426045833 | 23:58 (Apr 25)               | 0                                    | Manual log: Captain woke up event                                     |
| work: blog posts       | 20260426110000 | 06:00                        | 361                                  | Captain worked on captain log blog posts until 6 AM local time        |
| intentional sleep      | 20260426110000 | 06:00                        | 0                                    | Captain went to sleep intentionally (not a crash)                     |
| woke up                | 20260426130000 | 08:00                        | 120                                  | Captain woke up after intentional sleep                               |
| work: Hawaiian docs    | 20260426153000 | 10:30                        | 150                                  | Captain worked on Hawaiian documentation                              |
| work: images           | 20260426153000 | 10:30                        | 0                                    | Captain worked on image generation and editing                        |
| **intentional sleep**  | 20260426181800 | **13:18**                    | 168                                  | **Captain going to sleep now (intentional rest)**                     |
| woke up                | 20260426203000 | 15:30                        | 132                                  | Captain woke up at 3:30 PM local time                                 |
| **TOTAL**              | na             | na                           | **547**                              | **Sum of all durations minus all "woke up" row durations**            |

**How the TOTAL is calculated (using our time utility):**

```python
from scripts.timestamp_ymdhis import TimestampYmdhis as ts

# 1. Get duration for each row (using diffInMinutes which ignores seconds)
durations = [
    0,    # woke up
    361,  # work: blog posts
    0,    # intentional sleep
    120,  # woke up
    150,  # work: Hawaiian docs
    0,    # work: images
    168,  # intentional sleep
    132   # woke up (3:30 PM)
]

# 2. Identify wake-up rows and subtract their durations
wake_up_durations = [0, 120, 132]   # all "woke up" rows

total_active_minutes = sum(durations) - sum(wake_up_durations)
# = 931 - 252 = 679