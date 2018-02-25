#!/usr/bin/env bash

# shellcheck disable=SC2034
# DBPASSWD=postgres
USERNAME=postgres
DBNAME=postgres
SCHEMA=1_mydb.schema.sql
DATA=2_mydb.data.sql
HOST=localhost
PORT=5432
EXTRA=
OUTPUT_DIR=~/var/lib/postgresql/10/dump
