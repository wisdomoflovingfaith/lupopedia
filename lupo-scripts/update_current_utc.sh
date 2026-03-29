#!/bin/sh
# Updates CURRENT_UTC.txt with the current UTC time in YYYYMMDDHHMMSS format
# Usage: sh lupo-scripts/update_current_utc.sh

date -u +"%Y%m%d%H%M%S" > "$(dirname "$0")/../CURRENT_UTC.txt"
