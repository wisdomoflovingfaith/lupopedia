# TOON files moved

TOON (database structure representation) files that were previously in this directory have been **moved** to:

**`lupo-database/lupopedia/toon/`**

- One file per table: `<table_name>.toon` — **TOON format (YAML)**
- Same logical content as JSON: `lupo-database/lupopedia/json/<table_name>.json` — **JSON format**

Generate or regenerate with:

```bash
python scripts/generate_toon_files.py
```

See [TOON_REFERENCE.md](../TOON_REFERENCE.md) for full documentation.
