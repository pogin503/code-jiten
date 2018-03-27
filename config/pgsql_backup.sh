#!/usr/bin/env bash

set -eu
# set -x

# shellcheck source=env.sh
. env.sh
DATE_DIR=$(date '+%Y-%m-%d')

mkdir -p "$OUTPUT_DIR/$DATE_DIR"

IFS=' '
TABLES="t_example t_example_group t_example_relation t_language t_language_template v_example_desc"
for TABLE in $TABLES; do
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "$DBNAME" -t "$TABLE" --if-exists --clean \
		> "$OUTPUT_DIR/${DATE_DIR}/${SCHEMA}_$(date '+%Y%m%d_%H%M')_${TABLE}.sql"
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "$DBNAME" -t "$TABLE" \
		> "$OUTPUT_DIR/${DATE_DIR}/${DATA}_$(date '+%Y%m%d_%H%M')_${TABLE}.sql"
done

TABLES_WITH_OPS=$(IFS=' '; for x in $TABLES; do printf " -t %s" "$x"; done)

# shellcheck disable=SC2086
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "$DBNAME" --if-exists --clean ${TABLES_WITH_OPS} \
		    > "$OUTPUT_DIR/${DATE_DIR}/${SCHEMA}_$(date '+%Y%m%d_%H%M').sql"

# shellcheck disable=SC2086
pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "$DBNAME" ${TABLES_WITH_OPS} \
		    > "$OUTPUT_DIR/${DATE_DIR}/${DATA}_$(date '+%Y%m%d_%H%M').sql"
