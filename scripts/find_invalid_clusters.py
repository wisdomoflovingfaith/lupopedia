import glob
import re
import os

for f in glob.glob('docs/prd/*.md'):
    try:
        with open(f, 'r', encoding='ascii', errors='ignore') as file:
            for i, line in enumerate(file):
                if 'prd_cluster:' in line:
                    val = line.split(':', 1)[1].strip()
                    # Look for True, true, missing values, or non-conforming values
                    if val.lower() == 'true' or not val or val == '""' or val == "''":
                        print(f"FOUND: {f}:{i+1}: {line.strip()}")
    except Exception as e:
        print(f"Error reading {f}: {e}")
