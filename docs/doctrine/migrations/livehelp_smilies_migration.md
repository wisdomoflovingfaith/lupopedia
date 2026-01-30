# Migration Note: livehelp_smilies
# Status: DROPPED
# Replacement: chat_smilies/ directory + |:emoji ... :| inline token format

# 1. Summary
livehelp_smilies was Crafty Syntax's legacy table for storing emoji metadata used by the live chat UI.
It did not store the images themselves -- only:

the emoji "code"

the folder name

the filename

the display order

The actual images always lived in:

Code
chat_smilies/<folder>/<filename>
Lupopedia replaces this entire system with:

a. A file-based emoji architecture
Images live exclusively in the chat_smilies/ directory.

b. A modern inline token format
Code
|:emoji src="folder/filename" :|
c. A renderer that resolves tokens -> images
No database lookup is required.

No data is imported.
The table is dropped after migration.

# 2. What the Legacy Table Did
Crafty Syntax's chat UI allowed operators to pick an emoji from a dropdown.
When selected, it inserted markup like:

Code
|IMG SRC=chat_smilies/FOLDER/image|
The renderer then replaced that with an <img> tag.

The table existed solely to populate the emoji picker UI.

It was never:

a content table

a configuration table

a semantic entity

a durable metadata store

It was a UI convenience table.

# 3. Why Lupopedia Drops This Table
a. The new system is declarative and self-describing
The new token format:

Code
|:emoji src="folder/filename" :|
is:

explicit

namespaced

safe

renderer-agnostic

future-proof

b. The renderer reads directly from the filesystem
No DB lookup.
No syncing.
No metadata drift.

c. The legacy table contains no durable data
Everything in it is already represented by:

the folder structure

the filenames

d. The table was a maintenance trap
If someone added a new emoji file but forgot to update the table, the UI broke.

Lupopedia eliminates this entire class of errors.

# 4. The New Emoji Token Format
Token Syntax
Code
|:emoji src="folder/filename" :|
Rules
emoji is the token type

src is a relative path inside chat_smilies/

quotes are recommended but not required

whitespace before :| is allowed

additional attributes may be added later (e.g., size, alt)

Examples
Code
|:emoji src="happy/smile.png" :|
|:emoji src="animals/cat.gif" :|
|:emoji src="flags/us.png" :|
Renderer Behavior
The renderer:

Scans dialog text for the token pattern

Extracts the src attribute

Resolves it to chat_smilies/<src>

Replaces the token with the appropriate inline image element

This is consistent across:

web

mobile

desktop

federated renderers

# 5. Migration Behavior (as implemented in SQL)
Step 1 -- Convert for safe reading
Code
ALTER TABLE livehelp_smilies ENGINE=InnoDB;
ALTER TABLE livehelp_smilies CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'LEGACY ARCHIVE TABLE -- no longer used...'
Step 3 -- No import
There is:

no SELECT

no INSERT

no mapping

Step 4 -- Drop legacy table
Removed after migration.

# 6. Mapping Summary
Legacy -> New
Code
livehelp_smilies -> DROPPED
Replacement
Code
chat_smilies/<folder>/<filename>
|:emoji src="folder/filename" :|
Fields preserved
None -- the filesystem already contains the real data.

# 7. Doctrine Notes
This migration is a perfect example of:

Replacing a fragile DB-driven UI system with a clean file-based architecture
The old system required:

DB rows

folder structure

matching filenames

UI code to sync them

The new system requires only:

the folder structure

the inline token

The Slope Principle
We do not attempt to import or reinterpret legacy emoji codes.
We rely entirely on the filesystem and the new token format.

Future-proofing
The new token format supports:

additional attributes

theming

accessibility metadata

federated rendering

without schema changes.

# 8. Final Decision
Code
livehelp_smilies -> DROPPED
Emoji system replaced by chat_smilies/ directory + |:emoji src="folder/filename" :| tokens.
