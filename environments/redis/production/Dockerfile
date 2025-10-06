ARG REDIS_VERSION=alpine
FROM redis:${REDIS_VERSION}

COPY environments/redis/production/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh
