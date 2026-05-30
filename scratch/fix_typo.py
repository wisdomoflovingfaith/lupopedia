import os
import shutil

def replace_in_file(path):
    try:
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        if 'developement' in content:
            print(f"Fixing typo in {path}")
            with open(path, 'w', encoding='utf-8') as f:
                f.write(content.replace('developement', 'development'))
                return True
    except Exception:
        pass
    return False

# 1. Rename directory content
src = 'lupo-channels/0/developement'
dst = 'lupo-channels/0/development'
if os.path.exists(src):
    try:
        for item in os.listdir(src):
            src_item = os.path.join(src, item)
            dst_item = os.path.join(dst, item)
            shutil.move(src_item, dst_item)
        os.rmdir(src)
        print("Moved directory contents from developement to development.")
    except Exception as e:
        print("Failed moving directory:", e)

# 2. Find files and replace
dirs_to_search = ['lupo-config', 'lupo-bin', 'lupo-scripts', 'lupo-docs', 'lupo-channels']
for d in dirs_to_search:
    for root, _, files in os.walk(d):
        for file in files:
            if file.endswith(('.py', '.json', '.php', '.md')):
                replace_in_file(os.path.join(root, file))

print("Typo fix completed.")
