#!/usr/bin/env bash

# shellcheck disable=SC2034
# DBPASSWD=postgres
USERNAME=postgres
DBNAME=postgres
SCHEMA=mydb.schema.sql
DATA=mydb.data.sql
HOST=localhost
PORT=5432
EXTRA=
OUTPUT_DIR=~/var/lib/postgresql/10/data/dump
