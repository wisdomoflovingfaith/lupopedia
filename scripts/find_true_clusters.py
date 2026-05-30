import glob
import os

for f in glob.glob('docs/prd/*.md'):
    try:
        with open(f, 'r', encoding='ascii', errors='ignore') as file:
            for i, line in enumerate(file):
                if 'prd_cluster:' in line:
                    parts = line.split(':', 1)
                    if len(parts) > 1:
                        val = parts[1].strip()
                        if val == 'True' or val == '"True"' or val == "'True'":
                            print(f"MATCH: {f}:{i+1}: {line.strip()}")
    except Exception as e:
        pass
