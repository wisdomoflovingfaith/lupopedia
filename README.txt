Lupopedia
=========

Web-based knowledge base and live-help (semantic OS), continuing Crafty Syntax.

INSTALL (shared hosting / FTP)
------------------------------
1. Upload the package to your web directory (often public_html or a subdirectory).
2. Create a MySQL database and user; note host, name, user, and password.
3. Open install.php in your browser and complete the wizard. It applies schema/seed
   (and optional Crafty 3.7.5 import when upgrading) and writes lupopedia-config.php
   when you finish the site configuration step — you usually do not create that file by hand first.

Auto-installers (e.g. Softaculous) may write lupopedia-config.php from the sample using their own placeholders; then open the site URL (index.php) — you should not be stuck on the installer if the config file is already valid.

LICENSE
-------
See license.txt (GNU General Public License v2 or later).

More detail at package root: INSTALL.txt (full plaintext steps), QUICKSTART.md (short markdown).

Full documentation for developers lives in lupo-docs/ in a git checkout only; it is not inside Softaculous/FTP distribution zips. README.md at the package root summarizes the product when shipped.
