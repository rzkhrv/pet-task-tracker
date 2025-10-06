#!/bin/sh
set -e

mysqladmin ping -h localhost -u root -"${MYSQL_ROOT_PASSWORD:-password}"
