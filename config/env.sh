#!/usr/bin/env bash

# shellcheck disable=SC2034
# DBPASSWD=postgres
USERNAME=postgres
DBNAME=postgres
SCHEMA=1_mydb.schema
DATA=2_mydb.data
HOST=localhost
PORT=5432
EXTRA=
BACKUP_DIR=~/var/lib/postgresql/10/dump
RESTORE_DIR=~/var/lib/postgresql/10/dump
