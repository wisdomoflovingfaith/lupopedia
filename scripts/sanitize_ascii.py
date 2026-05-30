"""
Global ASCII sanitization for Lupopedia repository.
Optimized for one-pass execution and robust character normalization.
"""

import sys
import re
import unicodedata
import argparse
from pathlib import Path

# Mapping of specific Unicode sequences to ASCII equivalents
REPLACEMENTS = {
    # Quotes & Typography
    "\u2018": "'", "\u2019": "'", "\u201c": '"', "\u201d": '"',
    "\u201B": "'", "\u201F": '"', "\u2039": "'", "\u203A": "'",
    "\u00AB": '"', "\u00BB": '"', "\u2032": "'", "\u2033": '"',
    "\u2034": "'''", "\u2035": "'", "\u2036": '"', "\u2037": "'''",
    "\u2026": "...",
    # Dashes & Whitespace
    "\u2013": "-", "\u2014": "-", "\u2212": "-", "\u2010": "-",
    "\u2011": "-", "\u2012": "-",
    "\u00a0": " ",  # Non-breaking space
    "\u00ad": "",   # Soft hyphen
    "\u200b": "",   # Zero-width space
    "\u2000": " ", "\u2001": " ", "\u2002": " ", "\u2003": " ",
    "\u2004": " ", "\u2005": " ", "\u2006": " ", "\u2007": " ",
    "\u2008": " ", "\u2009": " ", "\u200A": " ", "\u202F": " ",
    "\u205F": " ",
    # Fraction slash and unicode slash variants
    "\u2044": "/", "\u2215": "/", "\u29F5": "/",
    # Arrows
    "\u2190": "<-", "\u2192": "->", "\u2191": "^", "\u2193": "v",
    "\u2194": "<->", "\u21d2": "=>", "\u21d4": "<=>",
    # Emoji / UI Icons
    "\u2705": "[OK]", "\u274c": "[NO]", "\u2713": "[x]", "\u2717": "[ ]",
    "\u2605": "*", "\u2606": "*", "\u2b50": "*",
    "\U0001f680": "[SEND]", "\U0001f4dd": "[DRAFT]",
    "\U0001f4c4": "[DOC]", "\U0001f4c1": "[FOLDER]",
    "\u23f0": "[TIME]", "\u23f3": "[HOURGLASS]",
    # Box drawing
    "\u2500": "-", "\u2502": "|", "\u250c": "+", "\u2510": "+",
    "\u2514": "+", "\u2518": "+", "\u251c": "+", "\u2524": "+",
    "\u252c": "+", "\u2534": "+", "\u253c": "+",
}

# Compile regex for the keys in REPLACEMENTS
# This looks for any of the specific "bad" strings in one go
PATTERN = re.compile("|".join(re.escape(k) for k in REPLACEMENTS.keys()))

def sanitize_text(text):
    """
    1. Replaces specific mapped characters via regex.
    2. Normalizes remaining characters (e.g., é -> e).
    3. Drops remaining non-ASCII (e.g., complex emojis).
    """
    # Pass 1: Map known sequences
    text = PATTERN.sub(lambda m: REPLACEMENTS[m.group(0)], text)
    
    # Pass 2: Decompose characters (NFKD) and strip non-spacing marks
    # This turns 'é' into 'e' + '´', then we encode to ASCII and ignore the '´'
    normalized = unicodedata.normalize('NFKD', text)
    ascii_bytes = normalized.encode('ascii', 'ignore')
    
    return ascii_bytes.decode('ascii')

def process_file(path, dry_run=True):
    try:
        # Avoid processing massive files or binaries
        if path.stat().st_size > 10 * 1024 * 1024:  # 10MB limit
            return f"[SKIP] {path} (too large)"

        original_content = path.read_text(encoding="utf-8")
        new_content = sanitize_text(original_content)

        if original_content != new_content:
            if not dry_run:
                # Use a temp file or direct write (atomic enough for text)
                path.write_text(new_content, encoding="ascii")
            return f"{'[FIXED]' if not dry_run else '[WOULD FIX]'} {path}"
            
    except UnicodeDecodeError:
        return f"[SKIP] {path} (likely binary/non-UTF8)"
    except Exception as e:
        return f"[ERROR] {path}: {e}"
    
    return None

def main():
    parser = argparse.ArgumentParser(description="Lupopedia ASCII Sanitizer")
    parser.add_argument("--fix", action="store_true", help="Actually write changes")
    parser.add_argument("paths", nargs="*", help="Files or directories to scan")
    args = parser.parse_args()

    target_paths = args.paths or ["docs/", "scripts/", "includes/", "agents/"]
    extensions = {".md", ".php", ".py", ".js", ".html", ".htm", ".css", ".sql", ".txt", ".json", ".yaml", ".yml"}

    found_any = False
    for path_str in target_paths:
        p = Path(path_str)
        files_to_check = [p] if p.is_file() else p.rglob("*")

        for f in files_to_check:
            if f.is_file() and (f.suffix.lower() in extensions):
                result = process_file(f, not args.fix)
                if result:
                    print(result)
                    found_any = True

    if found_any and not args.fix:
        print("\nReview the changes above. Run with --fix to apply.")
    elif not found_any:
        print("Repo is clean of non-ASCII characters.")

if __name__ == "__main__":
    main()