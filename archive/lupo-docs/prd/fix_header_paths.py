import os
import re

# Get all PRD files
prd_files = []
for filename in os.listdir('.'):
    if filename.endswith('.md') and filename not in ['prd_index.md', 'readme.md', 'tmp_test.txt', '51_memory_graph_as_source_of_truthmd.bak']:
        prd_files.append(filename)

# Sort files for consistent processing
prd_files.sort()

print(f"Found {len(prd_files)} PRD files to fix")

for filename in prd_files:
    filepath = os.path.join('.', filename)
    
    # Read the file
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Update header_format_version to 4.1.4
    content = re.sub(
        r'(header_format_version:\s*)"4\.1\.[0-9]"',
        r'\1"4.1.4"',
        content
    )
    
    # Update file_path_from_root to use the new filename
    content = re.sub(
        r'(file_path_from_root:\s*"lupo-docs/prd/)[^"]*(")',
        lambda m: m.group(1) + filename + m.group(2),
        content
    )
    
    # Update web_path to use the new filename
    content = re.sub(
        r'(web_path:\s*"https://www\.lupopedia\.com/lupopedia/lupo-docs/prd/)[^"]*(")',
        lambda m: m.group(1) + filename + m.group(2),
        content
    )
    
    # Update when_updated to today's date
    content = re.sub(
        r'(when_updated:\s*)"202604[0-9]{4}"',
        r'\1"202604210500"',
        content
    )
    
    # Write the file back
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Fixed headers in {filename}")

print("\nAll header paths updated successfully!")
