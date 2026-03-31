---
lupopedia.headers:
  file_path_from_root: lupo-docs/doctrine/EMOJI_AND_SMILIES.md
  file.last_modified_system_version: 4.0.x
  file.last_modified_utc: '20260330'
  purpose: Documentation for the new emoji/smilies system in Lupopedia
---

# Emoji and Smilies System (Lupopedia 4.0.x)

## Overview

Lupopedia now uses a modern emoji and smilies system based on image codes, replacing the legacy `lupo_smilies` table. Channels are now dialog-based, and emoji rendering is handled via inline codes and a user-friendly selector popup.

## Emoji Code Format

To insert an emoji or smilie, use the following code format in chat text:

```
::img|foldername|filename::
```

- `foldername`: The subfolder under `lupo-emoji/` where the emoji image is stored.
- `filename`: The image file name (e.g., `smile.png`).

**Example:**

```
Hello! ::img|classic|smile.png::
```

This will render the `smile.png` image from `lupo-emoji/classic/smile.png` inline in the chat.

## Emoji Selector Popup

- Users can click an emoji/smilies button in the chat UI to open a popup selector.
- The selector displays all available emoji images from the `lupo-emoji/` directory, organized by folder.
- Selecting an emoji inserts the corresponding `::img|foldername|filename::` code into the chat input.

## Rendering

- When displaying chat messages, the system scans for `::img|foldername|filename::` codes and replaces them with the corresponding `<img>` tags.
- Images are loaded from `lupo-emoji/foldername/filename`.

## Migration Notes

- The legacy `lupo_smilies` table and database-driven smilies are obsolete and have been removed from the schema.
- All emoji/smilies are now managed as static images in the filesystem.
- This approach supports unlimited emoji sets and easy customization by adding/removing images in `lupo-emoji/`.

## Example Directory Structure

```
lupo-emoji/
  classic/
    smile.png
    wink.png
  animals/
    cat.png
    dog.png
```

## Benefits

- No database dependency for emoji/smilies.
- Easy to add, remove, or update emoji sets.
- Supports any image format (PNG, GIF, etc.).
- Consistent rendering across all dialogs and channels.

---
For implementation details, see the chat UI and rendering logic in the dialog module.