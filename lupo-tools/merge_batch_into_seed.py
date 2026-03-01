#!/usr/bin/env python3
"""Merge tools/md_ingest_batch0.sql into database/migrations/seed_lupopedia.sql."""

import os

script_dir = os.path.dirname(os.path.abspath(__file__))
repo_root = os.path.dirname(script_dir)
seed_path = os.path.join(repo_root, "database", "migrations", "seed_lupopedia.sql")
batch_path = os.path.join(script_dir, "md_ingest_batch0.sql")

with open(seed_path, "r", encoding="utf-8") as f:
    seed = f.read()

with open(batch_path, "r", encoding="utf-8") as f:
    batch = f.read().rstrip()

marker = "-- md_flip_ingest batch 0: first 30 doctrine"
idx = seed.find(marker)
if idx == -1:
    print("Marker not found")
    exit(1)

end_marker = "INSERT INTO lupo_federation_nodes"
end_idx = seed.find(end_marker, idx)
if end_idx == -1:
    print("End marker not found")
    exit(1)

pre = seed[:idx]
post = seed[end_idx:]

emotional = "INSERT INTO lupo_emotional_frameworks (`framework_name`, `description`, `is_default`, `created_ymdhis`, `updated_ymdhis`) VALUES ('contextual_holism', 'Emotions inseparable from situation, history, relationship, and culture.', 0, 20250101000000, 20250101000000);"

middle = (
    "-- md_flip_ingest batch 0: first 30 doctrine .md files (content_id 5000-5029, channels 0 and 51)\n"
    + batch
    + "\n\n"
    + emotional
)

new_seed = pre + middle + "\n\n" + post
with open(seed_path, "w", encoding="utf-8") as f:
    f.write(new_seed)
print("Merged batch into seed")
