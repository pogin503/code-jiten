#!/usr/bin/env bash

set -eu
# set -x

cd "$(dirname "$0")"

# shellcheck source=env.sh
. env.sh

# disconnect session
echo "SELECT pg_terminate_backend(pid) \
   FROM pg_stat_activity \
  WHERE datname = '$DBNAME' \
    AND pid <> pg_backend_pid();" | psql --username="$USERNAME" -h "$HOST" -p "$PORT" template1

echo "DROP DATABASE IF EXISTS $DBNAME;" | psql --username="$USERNAME" -h "$HOST" -p "$PORT" template1
# echo "create database $DBNAME with encoding = 'UTF8' LC_COLLATE = 'ja_JP.UTF-8' LC_CTYPE = 'ja_JP.UTF-8';" | psql --username="$USERNAME" -h "$HOST" template1
echo "CREATE DATABASE $DBNAME with encoding = 'UTF8';" | psql --username="$USERNAME" -h "$HOST" -p "$PORT" template1

psql --username="$USERNAME" -h "$HOST" -p "$PORT" "$DBNAME" --single-transaction < "${RESTORE_DIR}/${SCHEMA}.sql"

psql --username="$USERNAME" -h "$HOST" -p "$PORT" "$DBNAME" --single-transaction < "${RESTORE_DIR}/${FUNCTION}.sql"

psql --username="$USERNAME" -h "$HOST" -p "$PORT" "$DBNAME" --single-transaction < "${RESTORE_DIR}/${DATA}.sql"
