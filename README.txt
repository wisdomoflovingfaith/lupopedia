Lupopedia
=========

Web-based knowledge base and live-help (semantic OS), continuing Crafty Syntax.

INSTALL (shared hosting / FTP)
------------------------------
1. Upload the package to your web directory (often public_html or a subdirectory).
2. Create a MySQL database and user; note host, name, user, and password.
3. Copy lupopedia-config-sample.php to lupopedia-config.php (or use your host's auto-installer).
4. Edit lupopedia-config.php: set database credentials and long random strings for the AUTH_* and *_SALT keys.
5. Open install.php in your browser and complete the wizard (schema, seed, optional Crafty 3.7.5 import).

Auto-installers (e.g. Softaculous) may write lupopedia-config.php from the sample using their own placeholders; then open the site URL (index.php) — you should not be stuck on the installer if the config file is already valid.

LICENSE
-------
See license.txt (GNU General Public License v2 or later).

Full documentation for developers: README.md (included at the package root when you build from the git tree).
