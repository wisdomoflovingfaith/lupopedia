# LUPOPEDIA HEADERS validator fixtures

Used by `php lupo-bin/lupo.php headers validate <path>` and `php lupo-scripts/validate_lupopedia_headers.php <path>`.

## Expected results (4.0.77 / 4.0.78 validator)

| File | Expected |
|------|----------|
| valid-full.md | PASS |
| grouped-edges-valid.md | PASS |
| flat-edges-valid.md | PASS |
| missing-required-field.md | FAIL (missing last_modified_utc) |
| wrong-block-order.md | FAIL (identity line on line 1) |
| missing-snapshot-comment.md | FAIL (lupopedia.edges without comment containing snapshot/static) |
| valid-namespace.md | PASS (has namespace; not under tables/ so namespace not required) |
| namespace-on-wrong-artifact.md | PASS (non-table artifact; namespace optional, value valid) |

**Namespace fixtures (4.0.78) — table-doc path required for missing/invalid:**  
Run validator on files under `lupo-docs/database/lupopedia/tables/_validator_fixtures/`:

| Path (under tables/) | Expected |
|----------------------|----------|
| _validator_fixtures/missing-required-namespace.md | FAIL (table doc requires namespace) |
| _validator_fixtures/invalid-namespace-value.md | FAIL (namespace value not in approved taxonomy) |

## Run manually

```bash
php lupo-bin/lupo.php headers validate lupo-tests/fixtures/headers/valid-full.md
php lupo-scripts/validate_lupopedia_headers.php lupo-tests/fixtures/headers/valid-full.md
```

Exit 0 = valid; exit 1 = invalid (errors on stderr).

## Export and import (4.0.77)

- **Export:** `php lupo-bin/lupo.php headers export <path> [--output=path] [--json]` or `php lupo-scripts/export_lupopedia_headers.php <path>`. Emits the YAML header block (no `---` delimiters in script output; CLI adds none). Use `--output=file.yaml` to write to a file.
- **Import:** `php lupo-bin/lupo.php headers import <target.md> [source.yaml]` or `php lupo-scripts/import_lupopedia_headers.php <target.md> [source.yaml]`. Replaces the header block in the target file with the supplied YAML; body (identity line + content) is preserved. Source can be a file path or stdin (e.g. `cat headers.yaml | php ... import target.md --`).

## Round-trip validation

To verify export/import round-trip:

1. Export a valid fixture to a temp file:  
   `php lupo-scripts/export_lupopedia_headers.php lupo-tests/fixtures/headers/valid-full.md --output=tmp_headers.yaml` (or use CLI and redirect stdout).
2. Copy the fixture to a temp target:  
   `cp lupo-tests/fixtures/headers/valid-full.md lupo-tests/fixtures/headers/valid-full-roundtrip.md` (or equivalent).
3. Import the exported YAML into the copy:  
   `php lupo-scripts/import_lupopedia_headers.php lupo-tests/fixtures/headers/valid-full-roundtrip.md tmp_headers.yaml`.
4. Validate the result:  
   `php lupo-scripts/validate_lupopedia_headers.php lupo-tests/fixtures/headers/valid-full-roundtrip.md`.
5. Optionally diff the original and round-tripped file; normalization (e.g. trailing newline, YAML key order) may differ.

Export outputs raw YAML (no leading/trailing `---`). Import accepts YAML with or without `---` and writes `---` + content + `---` + body. Round-trip is content-equivalent for header structure and body; exact byte identity is not guaranteed (whitespace/key order may normalize).
