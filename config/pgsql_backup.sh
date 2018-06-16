#!/usr/bin/env bash

set -eu
# set -x

cd "$(dirname "$0")"

# shellcheck source=env.sh
. env.sh
DATE_DIR=$(date '+%Y-%m-%d-%H%M')

mkdir -p "$BACKUP_DIR/$DATE_DIR"


TABLES="t_example t_example_group t_example_relation t_language t_language_template t_language_extension t_syntax_mode v_example_desc"

for TABLE in $TABLES; do
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "-F${FORMAT}" "$DBNAME" -t "$TABLE" --if-exists --clean \
		> "$BACKUP_DIR/${DATE_DIR}/${SCHEMA}_${TABLE}.sql"
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "-F${FORMAT}" "$DBNAME" -t "$TABLE" \
		> "$BACKUP_DIR/${DATE_DIR}/${DATA}_${TABLE}.sql"
done

TABLES_WITH_OPT=$(for x in $TABLES; do printf " -t %s" "$x"; done)

# shellcheck disable=SC2086
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "$DBNAME" --if-exists --clean "-F${FORMAT}" ${TABLES_WITH_OPT} \
		    > "$BACKUP_DIR/${DATE_DIR}/${SCHEMA}.sql"

# shellcheck disable=SC2086
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "$DBNAME" "-F${FORMAT}" ${TABLES_WITH_OPT} \
		    > "$BACKUP_DIR/${DATE_DIR}/${DATA}.sql"
