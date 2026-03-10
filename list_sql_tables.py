import re
import os

def list_tables(sql_file):
    tables = []
    with open(sql_file, 'r', encoding='utf-8') as f:
        content = f.read()
        # Find CREATE TABLE lupo_...
        matches = re.findall(r'CREATE TABLE\s+(`?lupo_[a-zA-Z0-9_]+`?)\s*\(', content, re.IGNORECASE)
        for m in matches:
            tables.append(m.replace('`', ''))
    return tables

sql_path = r'c:\ServBay\www\servbay\lupopedia\lupo-database\lupopedia\mysql\install\install_new_lupopedia.sql'
all_tables = list_tables(sql_path)
for t in sorted(all_tables):
    print(t)
