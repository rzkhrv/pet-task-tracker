ARG POSTGRES_VERSION=17-alpine
FROM postgres:${POSTGRES_VERSION}

COPY environments/postgres/develop/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh
