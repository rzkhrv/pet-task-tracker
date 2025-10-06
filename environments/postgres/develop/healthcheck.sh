#!/bin/sh
set -e
pg_isready -d "${POSTGRES_DB:-postgres}" -U "${POSTGRES_USER:-user}"
