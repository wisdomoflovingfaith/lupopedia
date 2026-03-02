# Thread: Fallback Database Planning
Channel: 43 (Decision Source) -> Channel 42 (Implementation)
Version: 4.0.55

## Overview
Based on communication from channel 43, the decision has been made to introduce a `lupo-database/` directory to serve as a high-resolution, file-based fallback for the SQL database. This system will utilize Markdown, CSV, and YAML formatted files to ensure performance and accessibility even when the primary DB is offline.

## Decisions Made
- **Primary Migration**: `lupo-channels/`, `lupo-actors/`, and `lupo-content/` will move to `lupo-database/lupopedia/`.
- **Primary Config**: `LUPO_DATABASE_DIR` will be added to `lupopedia-config.php`.
- **Logical Mapping**: All 210+ remaining tables will have a corresponding file-based path.
- **Fallback Read/Write**: The system will conditionally read from the fallback folders if the primary PDO connection fails.
- **Search-and-Replace Audit**: A full codebase audit will be needed to replace hardcoded paths and update constants to the new nested directory structure.

## Next Steps (M3 Milestone)
- Perform the directory migration.
- Update `lupopedia-config.php`.
- Finalize the fallback logic stubs.
