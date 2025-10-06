ARG NGINX_VERSION=alpine
FROM nginx:${NGINX_VERSION}

COPY environments/nginx/develop/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh
