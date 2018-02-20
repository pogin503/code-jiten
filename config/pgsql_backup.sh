#!/usr/bin/env bash

set -eu
# set -x

# shellcheck source=env.sh
. env.sh

mkdir -p "$OUTPUT_DIR"
pg_dump --username="$USERNAME"  -h "$HOST" -p "$PORT" --schema-only "$DBNAME" --if-exists --clean > "$OUTPUT_DIR/$SCHEMA"
pg_dump --username="$USERNAME"  -h "$HOST" -p "$PORT" --data-only --disable-triggers "$DBNAME" > "$OUTPUT_DIR/$DATA"
