# -*- coding: utf-8 -*-
"""Map PRD 16 mojibake to Unicode punctuation and arrows."""
import sys

path = sys.argv[1]
with open(path, "r", encoding="utf-8") as f:
    t = f.read()
orig = t
patterns = [
    ("\u00e2\u20ac\u2122", "\u2019"),
    ("\u00e2\u20ac\u201d", "\u2014"),
    ("\u00e2\u20ac\u201c", "\u2013"),
    ("\u00e2\u20ac\u0153", "\u201c"),
    ("\u00e2\u20ac" + chr(0x9D), "\u201d"),
    ("\u00e2\u20ac\u00a6", "\u2026"),
    ("\u00e2\u2020\u2019", "\u2192"),
    ("\u00e2\u2020\u201d", "\u2194"),
    ("\u00e2\u2030\u00a0", "\u2260"),
]
for old, new in patterns:
    t = t.replace(old, new)
if t != orig:
    with open(path, "w", encoding="utf-8", newline="\n") as f:
        f.write(t)
    print("[OK]", path)
else:
    print("[SKIP]", path)
