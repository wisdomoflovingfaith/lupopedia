"""
Fix all PRD memory_key paths to match staging/2026/04 TOON files.
Rule: memory_key = memory/development/staging/2026/04/{NN}_{snake_case}.toon
"""
import os
import re

BASE = "C:/ServBay/www/servbay/lupopedia"
PRD_DIR = os.path.join(BASE, "docs/prd")
STAGING_DIR = os.path.join(BASE, "memory/development/staging/2026/04")


def get_prd_meta(filepath):
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read(600)
    wu = re.search(r"when_updated:\s*['\"]?(\d+)['\"]?", content)
    ti = re.search(r'title:\s*["\'](.+?)["\']', content)
    mk = re.search(r'memory_key:\s*["\'](.+?)["\']', content)
    return (
        wu.group(1) if wu else "20260414000000",
        ti.group(1) if ti else "",
        mk.group(1) if mk else ""
    )


def toon_exists_anywhere(nn, snake):
    canonical_2026 = os.path.join(BASE, f"memory/development/canonical/2026/04/{nn}_{snake}.toon")
    canonical_1026 = os.path.join(BASE, f"memory/development/canonical/1026/04/{nn}_{snake}.toon")
    staging = os.path.join(STAGING_DIR, f"{nn}_{snake}.toon")
    # Also check alternate names in canonical dirs
    canon_2026_dir = os.path.join(BASE, "memory/development/canonical/2026/04")
    for fname in os.listdir(canon_2026_dir) if os.path.isdir(canon_2026_dir) else []:
        if fname.startswith(f"{nn}_"):
            return True
    return (os.path.exists(canonical_2026) or
            os.path.exists(canonical_1026) or
            os.path.exists(staging))


TOON_TEMPLATE = """\
id: {nn}
type: prd
title: {title}
status: active
created: {created}

edges:
  outbound: []
"""


def main():
    os.makedirs(STAGING_DIR, exist_ok=True)

    prd_files = sorted([f for f in os.listdir(PRD_DIR)
                        if re.match(r"^\d{2}_.*\.md$", f)])

    stats = {"created": 0, "updated": 0, "skipped": 0}

    for fname in prd_files:
        m = re.match(r"^(\d{2})_(.+)\.md$", fname)
        if not m:
            continue
        nn = m.group(1)
        snake = m.group(2)
        prd_path = os.path.join(PRD_DIR, fname)
        when_updated, title, old_mk = get_prd_meta(prd_path)

        toon_fname = f"{nn}_{snake}.toon"
        toon_path = os.path.join(STAGING_DIR, toon_fname)
        new_mk = f"memory/development/staging/2026/04/{toon_fname}"

        # Step 1: Create staging TOON if it doesn't exist
        if not os.path.exists(toon_path):
            # Check if a canonical TOON exists to use as source
            canon_2026_dir = os.path.join(BASE, "memory/development/canonical/2026/04")
            source_toon = None
            if os.path.isdir(canon_2026_dir):
                for existing in os.listdir(canon_2026_dir):
                    if existing.startswith(f"{nn}_"):
                        source_toon = os.path.join(canon_2026_dir, existing)
                        break

            if source_toon and os.path.exists(source_toon):
                with open(source_toon, "r", encoding="utf-8") as sf:
                    toon_content = sf.read()
                print(f"[COPY]   {toon_fname}  (from canonical)")
            else:
                # Type E: create from template
                toon_content = TOON_TEMPLATE.format(
                    nn=int(nn),
                    title=title if title else snake.replace("_", " "),
                    created=when_updated
                )
                print(f"[CREATE] {toon_fname}  (Type E - no existing TOON)")

            with open(toon_path, "w", encoding="utf-8") as tf:
                tf.write(toon_content)
            stats["created"] += 1
        else:
            print(f"[EXISTS] {toon_fname}")
            stats["skipped"] += 1

        # Step 2: Update memory_key in PRD file
        if old_mk == new_mk:
            print(f"         {fname}: memory_key already correct")
            continue

        with open(prd_path, "r", encoding="utf-8") as f:
            prd_content = f.read()

        if old_mk:
            new_content = prd_content.replace(
                f'memory_key: "{old_mk}"',
                f'memory_key: "{new_mk}"',
                1
            )
        else:
            # No existing memory_key - shouldn't happen but handle gracefully
            print(f"[WARN]   {fname}: no memory_key found, skipping update")
            continue

        if new_content != prd_content:
            with open(prd_path, "w", encoding="utf-8") as f:
                f.write(new_content)
            print(f"[UPDATE] {fname}: {old_mk}")
            print(f"      -> {new_mk}")
            stats["updated"] += 1
        else:
            print(f"[NOCHANGE] {fname}: replace failed (check for quotes/format)")

    print(f"\nDone. TOONs created: {stats['created']}, already existed: {stats['skipped']}, PRDs updated: {stats['updated']}")


if __name__ == "__main__":
    main()
