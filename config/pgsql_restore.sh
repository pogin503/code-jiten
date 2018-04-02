#!/usr/bin/env bash

set -eu
# set -x

# shellcheck source=env.sh
. env.sh

# disconnect session
echo "SELECT pg_terminate_backend(pid) \
   FROM pg_stat_activity \
  WHERE datname = '$DBNAME' \
    AND pid <> pg_backend_pid();" | psql --username="$USERNAME" -h "$HOST" -p "$PORT" template1

psql --username="$USERNAME" -h "$HOST" -p "$PORT" "$DBNAME" < "${RESTORE_DIR}/${SCHEMA}.sql"
psql --username="$USERNAME" -h "$HOST" -p "$PORT" "$DBNAME" < "${RESTORE_DIR}/${DATA}.sql"
