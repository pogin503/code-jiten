#!/usr/bin/env bash

set -eu
# set -x

# shellcheck source=env.sh
. env.sh

mkdir -p $RESTORE_DIR
echo "DROP DATABASE IF EXISTS $DBNAME;" | psql --username="$USERNAME" -h "$HOST" -p "$PORT" template1
# echo "create database $DBNAME with encoding = 'UTF8' LC_COLLATE = 'ja_JP.UTF-8' LC_CTYPE = 'ja_JP.UTF-8';" | psql --username="$USERNAME" -h "$HOST" template1
echo "CREATE DATABASE $DBNAME with encoding = 'UTF8';" | psql --username="$USERNAME" -h "$HOST" -p "$PORT" template1

psql --username="$USERNAME" -h "$HOST" -p "$PORT" "$DBNAME" < "${RESTORE_DIR}/${SCHEMA}.sql"
psql --username="$USERNAME" -h "$HOST" -p "$PORT" "$DBNAME" < "${RESTORE_DIR}/${DATA}.sql"
