---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "channel"
  system_version: "4.0.82"
  file_path_from_root: "channels/channel_index.md"
  web_path: "http://www.lupopedia.com/channels/CHANNEL_INDEX.md"
  questions_toon: null
  channel_id: null
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "channel"
  artifact_kind: "index"
  purpose: "Canonical channel index (machine + human navigation) for channels/"
  tags: ["channel_index", "navigation", "canonical", "4.0.82"]
  message_type: "index"
---

# file: Lupopedia Channel Index — web_path: http://www.lupopedia.com/channels/CHANNEL_INDEX.md

## Canonical entrypoint

- Primary: `channels/CHANNEL_INDEX.md` (this file)
- Secondary (legacy index): `channels/INDEX.md`

---

## Channel directories (present in repo)

- `channels/0/` — System Kernel
- `channels/1/` — Release Operations
- `channels/7/` — Validator Engineering
- `channels/11/` — Documentation Systems
- `channels/17/` — Project Architecture
- `channels/23/` — Migration & Upgrade
- `channels/31/` — External AI / Faucet
- `channels/36/` — (unassigned / TBD)
- `channels/42/` — Protocol Development
- `channels/51/` — Doctrine Council
- `channels/66/` — QA / Adversarial Review
- `channels/88/` — Research / Experiments
- `channels/420/` — Adversarial / attack threads
- `channels/666/` — Quarantine
- `channels/1_channel_refactor_governance/` — Channel refactor governance pilot (4.0.88)

---

## Thread navigation (per channel)

For any channel:

- **Canonical (new channels):** `channels/<channel_slug>/threads/`
- **Legacy compatibility:** `channels/<channel_id>/threads/`
- Artifact path pattern: `channels/<channel_dir>/threads/<thread_id>/YYYYMMDD_HHIISS_<actor>_<type>_<...>.md`

Where `<channel_dir>` is `channel_slug` for new channels and may be numeric `channel_id` for legacy channels.

---

## Related

- Repo root: [GitHub repo](https://github.com/wisdomoflovingfaith/lupopedia)
- Global (older) channel index: `channels/INDEX.md`
- **Most Active Channel**: 42 (Protocol Development)
- **Last System Activity**: 2026-03-19

### **Channel Distribution**
- **System Channels**: 2 (0, 666)
- **Development Channels**: 1 (42)
- **Governance Channels**: 1 (51)
- **Reserved**: 1 (1000+)

---

## **🔄 Automated Updates**

This index is automatically generated and maintained by WOLFIE using the following process:

1. **Channel Discovery**: Scan `/channels/` directory for channel folders
2. **Database Query**: Query `lupo_channels` table for channel metadata
3. **Activity Analysis**: Count active threads and tasks per channel
4. **Index Generation**: Generate markdown with current state
5. **Update Frequency**: 
   - Real-time: Channel creation/deletion
   - Hourly: Activity statistics refresh
   - Daily: Full regeneration

---

## **🛠️ Maintenance**

### **Regeneration Command**
```bash
# Generate fresh channel index
php scripts/generate_channel_index.php
```

### **Manual Override**
To manually update this index:
1. Edit this file directly
2. Run validation: `php scripts/validate_channel_index.php`
3. Commit changes with proper attribution

### **Troubleshooting**
- **Missing Channels**: Check channel directory structure
- **Incorrect Counts**: Verify database connectivity
- **Stale Data**: Run regeneration script

---

## **📋 Channel Creation Guidelines**

When creating new channels:

1. **Select Channel ID**: Use next available ID from reserved range
2. **Define Channel Slug**: Use lowercase, digits, underscore only
3. **Create Directory**: `mkdir channels/<channel_slug>/`
4. **Initialize Structure**: Create standard subdirectories
5. **Register in Database**: Insert channel with both `channel_id` and `channel_slug`
6. **Update Index**: Run regeneration script

### **Standard Channel Structure**
```
channels/<channel_slug>/
+-- broadcasts/     # System-wide announcements
+-- threads/        # Discussion threads
|   +-- <project_slug>/
|       +-- questions/   # Ambiguity capture before execution
|       +-- prompts/     # Final execution artifacts only
|       +-- *.md
+-- content/        # Shared resources
+-- direct/         # Direct messages
+-- rules/          # Channel rules
```

Legacy channels may remain at `channels/<channel_id>/` for historical compatibility.

During 4.0.88 refactor work, current channel-wide `prompts/` directories remain legacy-compatible until per-thread prompt migration is performed in controlled batches.

---

**Index Version**: 1.0  
**Last Updated**: 2026-03-19 15:00:00 UTC  
**Next Update**: 2026-03-19 16:00:00 UTC  
**Maintained by**: WOLFIE (Agent 1)
