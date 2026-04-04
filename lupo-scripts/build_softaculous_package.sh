#!/usr/bin/env bash
# build_softaculous_package.sh — Lupopedia Softaculous / shared-hosting distribution builder
#
# WordPress-style / LILITH directive (PRD 33): ZERO dotfiles in the archive (including NO .htaccess).
# Excludes lupo-archive/ (local legacy + wordpress-reference study tree; .gitignore) and root wordpress-reference/ if present.
# Install wizard writes .htaccess + lupo-database/.htaccess on success (InstallWizardHtaccessWriter).
# No .gitkeep — installer creates lupo-cache, lupo-logs, lupo-uploads, lupo-tmp.
#
# Requires: rsync, find, zip, tar (Git Bash on Windows, or Linux/macOS).
# Run from anywhere; script locates repo root via its own path.
#
# Usage:
#   ./lupo-scripts/build_softaculous_package.sh [VERSION]
#   VERSION defaults to 4.1.0
#   Optional: DIST_DIR=/path/to/out ./lupo-scripts/build_softaculous_package.sh

set -euo pipefail

VERSION="${1:-4.1.0}"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
DIST_DIR="${DIST_DIR:-${REPO_ROOT}/dist}"
INNER_NAME="lupopedia"
PACKAGE_BASENAME="lupopedia-${VERSION}"
STAGE_PARENT="$(mktemp -d "/tmp/${PACKAGE_BASENAME}.XXXXXX")"
STAGE="${STAGE_PARENT}/${INNER_NAME}"

echo "==> Lupopedia Softaculous packager"
echo "    VERSION=${VERSION}"
echo "    REPO_ROOT=${REPO_ROOT}"
echo "    STAGE=${STAGE}"
echo "    DIST_DIR=${DIST_DIR}"

mkdir -p "${STAGE}" "${DIST_DIR}"

# Copy runtime tree; exclude dev / docs / IDE / VCS. Do NOT exclude lupo-database/lupopedia/content/
# (LUPO_APP_DIR — required at runtime).
# Never ship a developer's lupopedia-config.php (credentials). Sample ships as lupopedia-config-sample.php.
# Root license.txt, README.md, README.txt, lupopedia-config-sample.php are not excluded.
rsync -a \
  --delete \
  --exclude='lupopedia-config.php' \
  --exclude='lupopedia-config_backup.php' \
  --exclude='.git/' \
  --exclude='.gitignore' \
  --exclude='.gitmodules' \
  --exclude='.github/' \
  --exclude='.cursor/' \
  --exclude='.windsurf/' \
  --exclude='.kiro/' \
  --exclude='.lexa/' \
  --exclude='.lilith/' \
  --exclude='.cascade/' \
  --exclude='.vscode/' \
  --exclude='.idea/' \
  --exclude='.agents/' \
  --exclude='.codex/' \
  --exclude='__pycache__/' \
  --exclude='*.pyc' \
  --exclude='.DS_Store' \
  --exclude='Thumbs.db' \
  --exclude='*.swp' \
  --exclude='*.tmp' \
  --exclude='phantom_paths.txt' \
  --exclude='lupo-docs/' \
  --exclude='lupo-scripts/' \
  --exclude='craftysyntax-reference/' \
  --exclude='wordpress-reference/' \
  --exclude='lupo-archive/' \
  --exclude='lupo-legacy/' \
  --exclude='lupo-research/' \
  --exclude='lupo-tests/' \
  --exclude='lupo-database/lupopedia/toon/' \
  --exclude='lupo-database/lupopedia/json/' \
  --exclude='.htaccess' \
  --exclude='.htpasswd' \
  --exclude='node_modules/' \
  --exclude='lupo-tools/' \
  --exclude='dist/' \
  "${REPO_ROOT}/" "${STAGE}/"

echo "==> Sanitize: remove ALL dotdirs and dotfiles (.htaccess and .htpasswd are NOT shipped — installer generates)"

# Remove IDE / VCS dot-directories if rsync missed any
while IFS= read -r -d '' d; do
  rm -rf "${d}"
done < <(find "${STAGE}" -mindepth 1 -depth -type d -name '.?*' -print0 2>/dev/null || true)

# Remove every dot-prefixed file (WordPress pattern — FTP clients skip hidden files)
while IFS= read -r -d '' f; do
  rm -f "${f}"
done < <(find "${STAGE}" -type f -name '.?*' -print0 2>/dev/null || true)

find "${STAGE}" -type f -name '.gitkeep' -delete

echo "==> Archives -> ${DIST_DIR}"
(
  cd "${STAGE_PARENT}"
  zip -r -q "${DIST_DIR}/${PACKAGE_BASENAME}.zip" "${INNER_NAME}"
  tar -czf "${DIST_DIR}/${PACKAGE_BASENAME}.tar.gz" "${INNER_NAME}"
)

rm -rf "${STAGE_PARENT}"

echo "==> Done:"
ls -la "${DIST_DIR}/${PACKAGE_BASENAME}.zip" "${DIST_DIR}/${PACKAGE_BASENAME}.tar.gz"
echo ""
echo "Validate (from an extract dir):"
echo "  find . -path './.git*' -o -name '.cursor' -type d # expect no IDE/VCS hits"
echo "  find . -name '.htaccess' -type f                 # expect NOTHING until install wizard runs"
echo "  find . -name '.gitkeep'                         # expect empty"
