# -*- coding: utf-8 -*-
import os
REPO = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
SKIP = {"archive", ".git", "node_modules", "vendor"}
n = 0
for dp, dns, fns in os.walk(REPO):
    dns[:] = [d for d in dns if d not in SKIP and not d.startswith(".")]
    for fn in fns:
        if not (fn.endswith(".md") or fn.endswith(".mdc")):
            continue
        path = os.path.join(dp, fn)
        try:
            with open(path, "r", encoding="utf-8", newline="") as f:
                t = f.read()
        except Exception:
            continue
        old = "  header_format_version: 4.1.8"
        new = '  header_format_version: "4.1.8"'
        if old not in t:
            continue
        nt = t.replace(old, new)
        if nt != t:
            with open(path, "w", encoding="utf-8", newline="") as f:
                f.write(nt)
            n += 1
print("quoted_files", n)
