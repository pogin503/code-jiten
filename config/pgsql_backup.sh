#!/usr/bin/env bash

set -eu
# set -x

# shellcheck source=env.sh
. env.sh
DATE_DIR=$(date '+%Y-%m-%d-%H%M')

mkdir -p "$BACKUP_DIR/$DATE_DIR"

TABLES="t_example t_example_group t_example_relation t_language t_language_template v_example_desc"
for TABLE in $TABLES; do
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only -Fc "$DBNAME" -t "$TABLE" --if-exists --clean \
		> "$BACKUP_DIR/${DATE_DIR}/${SCHEMA}_${TABLE}.sql"
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers -Fc "$DBNAME" -t "$TABLE" \
		> "$BACKUP_DIR/${DATE_DIR}/${DATA}_${TABLE}.sql"
done

TABLES_WITH_OPT=$(for x in $TABLES; do printf " -t %s" "$x"; done)

# shellcheck disable=SC2086
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "$DBNAME" --if-exists --clean -Fc ${TABLES_WITH_OPT} \
		    > "$BACKUP_DIR/${DATE_DIR}/${SCHEMA}.sql"

# shellcheck disable=SC2086
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "$DBNAME" -Fc ${TABLES_WITH_OPT} \
		    > "$BACKUP_DIR/${DATE_DIR}/${DATA}.sql"
