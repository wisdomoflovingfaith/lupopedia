# WHY VIOLATION REPORT
**Generated:** 20260421115012
**Failure ID:** why_20260421_115012_00_B_16_A_99_A_timestamp_violation

## Update Context
- **File being updated:** `app/Models/User.php` 
- **prd_cluster provided:** 00_B_16_A_99_A
- **Task:** Add new `last_login` column to the users table migration

## What Went Wrong
The AI generated the following migration code:

```php
$table->timestamp('last_login')->nullable();
```

**Violation:**  
Used Laravel's `timestamp()` helper (which creates a DATETIME column) instead of a 14-digit BIGINT.

## Root Cause Analysis
- The global rule "All timestamps MUST be stored as 14-digit BIGINT in YYYYMMDDHHMMSS format" exists in `00_A_forbidden_and_why.md`.
- However, `00_A_forbidden_and_why.md` was **not included** in the supplied `prd_cluster`.
- The AI therefore fell back to its strongest training prior (standard Laravel timestamp conventions).
- The cluster started with `00_B` instead of `00_A`, so the anti-assumption shield was never read first.

## Defensive Notes
This is the exact same class of failure as "whitespace crayon eating".  
Even when the rule exists, if it is not placed at the front of the reading order, the model will still apply common patterns.

## Recommended Fix
1. Always include `00_A_forbidden_and_why` as the **first** entry in any `prd_cluster` that could involve timestamps, headers, formatting, or structural rules.
2. Update the cluster for this file to:  
   `00_A_forbidden_and_why_00_B_16_A_99_A` 
3. Consider adding a stronger, more explicit reminder in `00_A_forbidden_and_why.md` under the Timestamp Policy section.

## Validator Output
```diff
- $table->timestamp('last_login')->nullable();
+ $table->bigInteger('last_login')->nullable();  // 14-digit BIGINT YYYYMMDDHHMMSS
```

## Status
- Update rejected.
- This why file has been created as permanent doctrine memory.
- Next run should include the corrected cluster so the AI reads the rule **before** it can default to Laravel conventions.
