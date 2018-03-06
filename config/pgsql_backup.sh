#!/usr/bin/env bash

set -eu
# set -x

# shellcheck source=env.sh
. env.sh
DATE_DIR=$(date '+%Y-%m-%d')
mkdir -p "$OUTPUT_DIR/$DATE_DIR"
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "$DBNAME" --if-exists --clean > "$OUTPUT_DIR/${DATE_DIR}/${SCHEMA}_$(date '+%Y%m%d_%H%M')"
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "$DBNAME" > "$OUTPUT_DIR/${DATE_DIR}/${DATA}_$(date '+%Y%m%d_%H%M')"
