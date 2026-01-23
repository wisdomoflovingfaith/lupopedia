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
    # Check if content already exists
    check_sql = "SELECT content_id FROM lupo_contents WHERE slug = %s AND federation_node_id = %s"
    cursor.execute(check_sql, (slug, FED_NODE))
    existing = cursor.fetchone()
    
    if existing:
        print(f"Skipping existing content: {slug} (content_id: {existing[0]})")
        return existing[0]
    
    sql = """
    INSERT INTO lupo_contents
    (federation_node_id, user_id, slug, title, body,
     status, visibility, created_ymdhis, updated_ymdhis)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
    """
    vals = (FED_NODE, ACTOR_ID, slug, title, body, 'published', 'public', TIMESTAMP, TIMESTAMP)
    cursor.execute(sql, vals)
    db.commit()
    return cursor.lastrowid

def insert_edge(content_id):
    # Check if edge already exists
    check_sql = """SELECT edge_id FROM lupo_edges 
                  WHERE left_object_type = %s AND left_object_id = %s 
                  AND right_object_type = %s AND right_object_id = %s 
                  AND edge_type = %s"""
    cursor.execute(check_sql, ('channel', CHANNEL_ID, 'content', content_id, 'HAS_CONTENT'))
    existing = cursor.fetchone()
    
    if existing:
        print(f"Skipping existing edge for content_id: {content_id}")
        return
    
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
    for root, dirs, files in os.walk(DOCS_DIR):
        for filename in files:
            if filename.endswith('.md'):
                filepath = os.path.join(root, filename)
                # Calculate relative path from DOCS_DIR for slug
                rel_path = os.path.relpath(filepath, DOCS_DIR)
                slug = rel_path[:-3].replace('\\', '/').replace(' ', '-').lower()  # remove .md and normalize
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()
                    title = filename.replace('-', ' ').replace('_', ' ').replace('.md', '').title()
                    content_id = insert_content(slug, title, content)
                    insert_edge(content_id)
                    print(f"Imported {rel_path} -> content_id {content_id}")

if __name__ == "__main__":
    main()
    cursor.close()
    db.close()

print("Done.")
