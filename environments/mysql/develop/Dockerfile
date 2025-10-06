ARG MYSQL_VERSION=8.0
FROM mysql:${MYSQL_VERSION}

COPY environments/mysql/develop/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh
