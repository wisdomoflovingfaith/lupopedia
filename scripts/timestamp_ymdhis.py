"""
timestamp_ymdhis — Unified Doctrine-Aligned Timestamp Utility (Python port)

#   lupopedia.headers (adapted for Python):
#   header_format_version: "4.1.3"
#   file_path_from_root: "scripts/timestamp_ymdhis.py"
#   status: "active"
#   when_updated: "20260426031100"
#   trust_tier: "canonical"
#   artifact_type: implementation
#   artifact_kind: library
#   channel_key: "development"

PURPOSE:
    Calendar UTC helpers for packed decimal timestamps stored as int
    in the format YYYYMMDDHHMMSS (fourteen digits, zero-padded).

DOCTRINE:
    - Always UTC
    - Packed integer format for storage (BIGINT equivalent)
    - No Unix epoch as canonical persisted clock
    - Calendar-correct arithmetic via datetime (no 32-bit time_t issues)
    - Python 3+ only (arbitrary precision ints — runtimePackedUtcIntSafe always True)

YEAR 2038:
    The persisted value is a calendar label (e.g. 20401231235959), not Unix time.
    No Y2038 problem in storage. Arithmetic uses datetime, not gmmktime.

PUBLIC API (all static; packed int = YYYYMMDDHHMMSS UTC unless noted):

   Core
     now()                           Current UTC as packed int.
     explode(int ts)                 Packed int → dict year, month, day, hour, minute, second.
     implode(dict c)                 Component dict → packed int.
     runtimePackedUtcIntSafe()       Always True in Python 3+.

   Arithmetic
     addSeconds(int ts, int seconds)     Add/subtract seconds (calendar-correct).
     subtractSeconds(int ts, int n)      addSeconds(ts, -n).
     addMinutes(int ts, int n)           addSeconds(ts, n * 60).
     addHours(int ts, int n)             addSeconds(ts, n * 3600).
     diffInSeconds(int a, int b)         Signed seconds (a − b): time at a minus time at b.
     diffInMinutes(int a, int b)         Signed minutes (a − b): time at a minus time at b, ignoring seconds.

   Comparison
     isBefore(int a, int b)              a < b.
     isAfter(int a, int b)               a > b.
     isBetween(int ts, int start, int end)  start <= ts <= end.

   Formatting / parse
     toHuman(int ts)                      "YYYY-MM-DD HH:MM:SS UTC".
     fromHuman(string str)                Parse string as UTC DateTime → packed; invalid → 0.
     convert_bigint_to_iso8601(int ts)    Packed → YYYY-MM-DDTHH:MM:SSZ.
     convert_iso8601_to_bigint(string iso)  ISO8601 UTC string → packed; invalid → 0.

   Intervals (dicts: {'start': packed, 'end': packed})
     interval(int start, int end)        Build interval dict.
     overlaps(dict a, dict b)            Ranges overlap.
     intersection(dict a, dict b)        Overlap range or None.
     shift(dict interval, int seconds)   Move both ends by seconds.
     expand(dict interval, int seconds)  Grow symmetrically (start earlier, end later).

   Internal
     _dateTimeFromPacked(int ts)          private — UTC datetime from packed, or None.

All methods are static. Use TimestampYmdhis.method() or the alias: from scripts.timestamp_ymdhis import ts
"""

from datetime import datetime, timezone, timedelta
from typing import Dict, Optional


class TimestampYmdhis:
    """Unified Doctrine-Aligned Timestamp Utility for YYYYMMDDHHMMSS packed UTC timestamps."""

    @staticmethod
    def _dateTimeFromPacked(ts: int) -> Optional[datetime]:
        """Build a UTC datetime from a packed YmdHis value, or None if invalid."""
        try:
            c = TimestampYmdhis.explode(ts)
            return datetime(
                year=c['year'],
                month=c['month'],
                day=c['day'],
                hour=c['hour'],
                minute=c['minute'],
                second=c['second'],
                tzinfo=timezone.utc
            )
        except (ValueError, TypeError, KeyError):
            return None

    # ============================================================
    # CORE
    # ============================================================

    @staticmethod
    def runtimePackedUtcIntSafe() -> bool:
        """True when Python can hold fourteen-digit packed UTC (always True in Python 3+)."""
        return True

    @staticmethod
    def now() -> int:
        """Current UTC as packed int YYYYMMDDHHMMSS."""
        return int(datetime.now(timezone.utc).strftime('%Y%m%d%H%M%S'))

    @staticmethod
    def explode(ts: int) -> Dict[str, int]:
        """Packed int → dict with year, month, day, hour, minute, second."""
        s = str(ts)

        if not s.isdigit() or len(s) != 14:
            raise ValueError("Invalid packed timestamp format (must be 14-digit YYYYMMDDHHMMSS)")

        return {
            'year':   int(s[0:4]),
            'month':  int(s[4:6]),
            'day':    int(s[6:8]),
            'hour':   int(s[8:10]),
            'minute': int(s[10:12]),
            'second': int(s[12:14]),
        }

    @staticmethod
    def implode(c: Dict[str, int]) -> int:
        """Component dict → packed int YYYYMMDDHHMMSS."""
        return int(
            f"{c['year']:04d}{c['month']:02d}{c['day']:02d}"
            f"{c['hour']:02d}{c['minute']:02d}{c['second']:02d}"
        )

    # ============================================================
    # ARITHMETIC (MySQL-style calendar arithmetic)
    # ============================================================

    @staticmethod
    def addSeconds(ts: int, seconds: int) -> int:
        """Add/subtract seconds (calendar-correct, handles calendar arithmetic via datetime (no leap second modeling))."""
        dt = TimestampYmdhis._dateTimeFromPacked(ts)
        if dt is None:
            return ts
        delta = timedelta(seconds=seconds)
        new_dt = dt + delta
        return int(new_dt.strftime('%Y%m%d%H%M%S'))

    @staticmethod
    def subtractSeconds(ts: int, seconds: int) -> int:
        return TimestampYmdhis.addSeconds(ts, -seconds)

    @staticmethod
    def addMinutes(ts: int, minutes: int) -> int:
        return TimestampYmdhis.addSeconds(ts, minutes * 60)

    @staticmethod
    def addHours(ts: int, hours: int) -> int:
        return TimestampYmdhis.addSeconds(ts, hours * 3600)

    @staticmethod
    def diffInSeconds(a: int, b: int) -> int:
        """Signed seconds (a − b): time at a minus time at b."""
        da = TimestampYmdhis._dateTimeFromPacked(a)
        db = TimestampYmdhis._dateTimeFromPacked(b)
        if da is None or db is None:
            return 0
        return int((da - db).total_seconds())

    @staticmethod
    def diffInMinutes(a: int, b: int) -> int:
        """Signed whole minutes (a − b), ignoring seconds (both treated as :00)."""
        da = TimestampYmdhis._dateTimeFromPacked(a)
        db = TimestampYmdhis._dateTimeFromPacked(b)
        if da is None or db is None:
            return 0

        # Ignore seconds — treat both timestamps as :00
        da = da.replace(second=0)
        db = db.replace(second=0)

        delta = (da - db).total_seconds()
        return int(delta // 60)

    # ============================================================
    # COMPARISON
    # ============================================================

    @staticmethod
    def isBefore(a: int, b: int) -> bool:
        return a < b

    @staticmethod
    def isAfter(a: int, b: int) -> bool:
        return a > b

    @staticmethod
    def isBetween(ts: int, start: int, end: int) -> bool:
        return start <= ts <= end

    # ============================================================
    # FORMATTING / PARSE
    # ============================================================

    @staticmethod
    def toHuman(ts: int) -> str:
        """Return "YYYY-MM-DD HH:MM:SS UTC" string."""
        c = TimestampYmdhis.explode(ts)
        return (
            f"{c['year']:04d}-{c['month']:02d}-{c['day']:02d} "
            f"{c['hour']:02d}:{c['minute']:02d}:{c['second']:02d} UTC"
        )

    @staticmethod
    def fromHuman(s: str) -> int:
        """
        Parse string as UTC datetime → packed int.
        Accepts common formats (ISO8601, 'YYYY-MM-DD HH:MM:SS', etc.).
        Invalid input returns 0.
        """
        s = s.strip()
        if not s:
            return 0
        try:
            # Try ISO8601 first (handles Z and offsets)
            if 'T' in s or 'Z' in s:
                iso = s.replace('Z', '+00:00')
                dt = datetime.fromisoformat(iso)
            else:
                # Common human formats
                for fmt in ('%Y-%m-%d %H:%M:%S', '%Y-%m-%d %H:%M', '%Y%m%d%H%M%S'):
                    try:
                        dt = datetime.strptime(s, fmt)
                        break
                    except ValueError:
                        continue
                else:
                    # No known format matched → invalid, return 0 (hardened)
                    raise ValueError("No matching format")
            if dt.tzinfo is None:
                dt = dt.replace(tzinfo=timezone.utc)
            else:
                dt = dt.astimezone(timezone.utc)
            return int(dt.strftime('%Y%m%d%H%M%S'))
        except Exception:
            return 0

    @staticmethod
    def convert_bigint_to_iso8601(ts: int) -> str:
        """Convert packed BIGINT(14) YmdHis to ISO8601 UTC string (YYYY-MM-DDTHH:MM:SSZ)."""
        c = TimestampYmdhis.explode(ts)
        return (
            f"{c['year']:04d}-{c['month']:02d}-{c['day']:02d}T"
            f"{c['hour']:02d}:{c['minute']:02d}:{c['second']:02d}Z"
        )

    @staticmethod
    def convert_iso8601_to_bigint(iso: str) -> int:
        """Convert ISO8601 UTC string to BIGINT(14) YmdHis. Invalid → 0."""
        try:
            s = iso.strip()
            if s.endswith('Z'):
                s = s[:-1] + '+00:00'
            dt = datetime.fromisoformat(s)
            if dt.tzinfo is None:
                dt = dt.replace(tzinfo=timezone.utc)
            dt = dt.astimezone(timezone.utc)
            return int(dt.strftime('%Y%m%d%H%M%S'))
        except Exception:
            return 0

    # ============================================================
    # INTERVAL HELPERS
    # ============================================================

    @staticmethod
    def interval(start: int, end: int) -> Dict[str, int]:
        """Build interval dict {'start': ..., 'end': ...}."""
        return {'start': start, 'end': end}

    @staticmethod
    def overlaps(a: Dict[str, int], b: Dict[str, int]) -> bool:
        """Return True if the two intervals overlap."""
        if 'start' not in a or 'end' not in a or 'start' not in b or 'end' not in b:
            raise ValueError("Invalid interval structure; expected {'start': int, 'end': int}")
        return not (a['end'] < b['start'] or b['end'] < a['start'])

    @staticmethod
    def intersection(a: Dict[str, int], b: Dict[str, int]) -> Optional[Dict[str, int]]:
        """Return overlapping interval or None if no overlap."""
        if 'start' not in a or 'end' not in a or 'start' not in b or 'end' not in b:
            raise ValueError("Invalid interval structure; expected {'start': int, 'end': int}")
        if not TimestampYmdhis.overlaps(a, b):
            return None
        return {
            'start': max(a['start'], b['start']),
            'end':   min(a['end'], b['end'])
        }

    @staticmethod
    def shift(interval: Dict[str, int], seconds: int) -> Dict[str, int]:
        """Move both ends of the interval by the given seconds."""
        if 'start' not in interval or 'end' not in interval:
            raise ValueError("Invalid interval structure; expected {'start': int, 'end': int}")
        return {
            'start': TimestampYmdhis.addSeconds(interval['start'], seconds),
            'end':   TimestampYmdhis.addSeconds(interval['end'], seconds)
        }

    @staticmethod
    def expand(interval: Dict[str, int], seconds: int) -> Dict[str, int]:
        """Grow the interval symmetrically (start earlier, end later)."""
        if 'start' not in interval or 'end' not in interval:
            raise ValueError("Invalid interval structure; expected {'start': int, 'end': int}")
        return {
            'start': TimestampYmdhis.subtractSeconds(interval['start'], seconds),
            'end':   TimestampYmdhis.addSeconds(interval['end'], seconds)
        }


# Convenience alias for shorter usage
ts = TimestampYmdhis