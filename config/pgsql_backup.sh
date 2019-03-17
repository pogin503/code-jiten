#!/usr/bin/env bash

set -eu
# set -x

backup_table() {
    local table="$1"
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "-F${FORMAT}" "$DBNAME" -t "${table}" --if-exists --clean \
		    > "$BACKUP_DIR/${DATE_DIR}/${SCHEMA}_${table}.sql"
	pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "-F${FORMAT}" "$DBNAME" -t "$TABLE" \
		    > "$BACKUP_DIR/${DATE_DIR}/${DATA}_${table}.sql"
}

backup_all_tables() {
    local tables="$@"
    local tables_with_opt=$(for x in $tables; do printf " -t %s" "$x"; done)

    # shellcheck disable=SC2086
    pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --schema-only "$DBNAME" --if-exists --clean "-F${FORMAT}" ${tables_with_opt} \
		    > "$BACKUP_DIR/${DATE_DIR}/${SCHEMA}.sql"

    # shellcheck disable=SC2086
    pg_dump --username="$USERNAME" -h "$HOST" -p "$PORT" --data-only --disable-triggers "$DBNAME" "-F${FORMAT}" ${tables_with_opt} \
		    > "$BACKUP_DIR/${DATE_DIR}/${DATA}.sql"
}

backup_function() {
    psql --username="$USERNAME" -h "$HOST" -p "$PORT" -At "$DBNAME" \
         > "$BACKUP_DIR/${DATE_DIR}/${FUNCTION}.sql" << "__END__"
SELECT pg_get_functiondef(f.oid)
FROM pg_catalog.pg_proc f
INNER JOIN pg_catalog.pg_namespace n ON (f.pronamespace = n.oid)
WHERE n.nspname = 'public';
__END__

}

main() {
    cd "$(dirname "$0")"

    # shellcheck source=env.sh
    . env.sh
    DATE_DIR=$(date '+%Y-%m-%d-%H%M')

    mkdir -p "$BACKUP_DIR/$DATE_DIR"

    TABLES="\
t_example \
t_example_group \
t_example_relation \
t_language \
t_language_template \
t_language_extension \
t_syntax_mode \
v_example_desc"

    for TABLE in $TABLES; do
        backup_table $TABLE
    done
    backup_all_tables $TABLES
    backup_function
}

main
