import os
import mysql.connector
from datetime import datetime

# --- CONFIG ---
DOCS_DIR = "../docs"
CHANNEL_ID = 0  # root/system kernel channel
FED_NODE = 1
ACTOR_ID = 1
TIMESTAMP = int(datetime.now().strftime("%Y%m%d%H%M%S"))

# --- DB CONNECTION ---
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="ServBay.dev",
    database="lupopedia"
)
cursor = db.cursor()

# --- HELPERS ---
def insert_content(slug, title, body):
    sql = """
    INSERT INTO lupo_contents
    (federation_node_id, created_by_actor_id, slug, title, body,
     status, visibility, created_ymdhis, updated_ymdhis)
    VALUES (%s, %s, %s, %s, %s, %s, %s)
    """
    vals = (FED_NODE, ACTOR_ID, slug, title, body, 'published', 'public', TIMESTAMP, TIMESTAMP)
    cursor.execute(sql, vals)
    db.commit()
    return cursor.lastrowid

def insert_edge(content_id):
    sql = """
    INSERT INTO lupo_edges
    (left_object_type, left_object_id, right_object_type, right_object_id,
     edge_type, channel_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    """
    vals = ('channel', CHANNEL_ID, 'content', content_id, 'HAS_CONTENT', 
             CHANNEL_ID, 0, 0, ACTOR_ID, 0, 0, TIMESTAMP, TIMESTAMP)
    cursor.execute(sql, vals)
    db.commit()

# --- MAIN ---
def main():
    for filename in os.listdir(DOCS_DIR):
        if filename.endswith('.md'):
            filepath = os.path.join(DOCS_DIR, filename)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
                slug = filename[:-3]  # remove .md
                title = filename.replace('-', ' ').replace('_', ' ').title()
                content_id = insert_content(slug, title, content)
                insert_edge(content_id)
                print(f"Imported {filename} -> content_id {content_id}")

if __name__ == "__main__":
    main()
    cursor.close()
    db.close()

print("Done.")
