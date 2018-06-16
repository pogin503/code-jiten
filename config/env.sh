#!/usr/bin/env bash

# shellcheck disable=SC2034
# DBPASSWD=postgres
DBNAME=postgres
HOST=localhost
USERNAME=postgres
PORT=5432

# DBNAME=postgres
# HOST=192.168.10.10
# USERNAME=homestead
# PORT=5432

SCHEMA=1_mydb.schema
DATA=2_mydb.data
FUNCTION=3_mydb.function
FORMAT=c
EXTRA=

BACKUP_DIR=~/var/lib/postgresql/10/dump
RESTORE_DIR=~/var/lib/postgresql/10/dump
