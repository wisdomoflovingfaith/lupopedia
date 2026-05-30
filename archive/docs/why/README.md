# Why Files - Violation Documentation

This directory contains automatically generated "why" files that document every constitutional violation detected during validation.

## Purpose

These files serve as:
- **Permanent records** of what went wrong
- **Learning documentation** for the system
- **Pattern detection** data for rule improvements
- **Traceability** for debugging and auditing

## Naming Convention

`why_YYYYMMDD_HHMMSS_<prd_cluster>_<short_violation_slug>.md`

Examples:
- `why_20260421_115012_00_B_16_A_timestamp_violation.md`
- `why_20260421_115045_27_C_underscore_swallow.md`
- `why_20260421_115108_cluster_missing_rule.md`

## File Structure

Each why file contains:
1. **Violation metadata** (timestamp, cluster, file)
2. **What went wrong** (clear description)
3. **Root cause analysis** (why it happened)
4. **Recommended fix** (actionable steps)
5. **Validator output** (exact error/diff)
6. **Constitutional reference** (which rule was broken)

## Integration

Why files are:
- **Automatically generated** by validators on any violation
- **Searchable** documentation for pattern analysis
- **Referenced** in future clusters for learning
- **Used** to strengthen constitutional rules over time

## Self-Healing Loop

1. Violation occurs → why file generated
2. Pattern detected → rule strengthened
3. Rule updated → cluster updated
4. Future runs include updated rule → violations prevented

## Constitutional Authority

These files are created under the authority of **00_A_FORBIDDEN_AND_WHY.md** section 10 (Reactive Why Protocol).

Generation of why files is **mandatory** for all violations. Silent failure to document violations is itself a constitutional violation.
