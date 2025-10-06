ifeq (,$(wildcard .env))
$(error Файл .env не найден, скопируйте из .env.example)
endif

include .env
export

NUKE_CONTAINERS := $(shell docker ps -a --format '{{.Names}}' | grep '^${COMPOSE_PROJECT_NAME}_\|^app-' || true)

COMPOSE=docker compose -p ${COMPOSE_PROJECT_NAME}
PHP_CONTAINER=php

INSTALL_LOCK := installed.lock

build:
	@$(COMPOSE) build

rebuild:
	@$(COMPOSE) down && $(COMPOSE) up -d --build

start:
	@$(COMPOSE) up -d

stop-%:
	@$(COMPOSE) stop $*

restart:
	@$(COMPOSE) restart

install:
	@if [ -f $(INSTALL_LOCK) ]; then \
		echo "Установка уже была выполнена. Удалите '$(INSTALL_LOCK)', чтобы повторить установку."; \
		exit 1; \
	fi

	@echo "Выполняем установку..."
	@set -e; \
		$(MAKE) build; \
		$(MAKE) start; \
		$(COMPOSE) exec $(PHP_CONTAINER) php artisan key:generate --force; \
		$(COMPOSE) exec $(PHP_CONTAINER) php artisan storage:link --force; \
		$(MAKE) update; \
		date '+%Y-%m-%d %H:%M:%S' > $(INSTALL_LOCK); \
		echo "Установка завершена. Файл '$(INSTALL_LOCK)' создан."

update:
	@$(COMPOSE) exec $(PHP_CONTAINER) composer install --prefer-dist --no-dev -o
	@$(COMPOSE) exec $(PHP_CONTAINER) npm install
	@$(COMPOSE) exec $(PHP_CONTAINER) npm run build
	@$(COMPOSE) exec $(PHP_CONTAINER) php artisan optimize:clear
	@$(COMPOSE) exec $(PHP_CONTAINER) php artisan migrate --force
	@$(COMPOSE) exec $(PHP_CONTAINER) php artisan db:seed --force
	@$(COMPOSE) exec $(PHP_CONTAINER) php artisan optimize

bash-%:
	@$(COMPOSE) exec $* sh

logs:
	@$(COMPOSE) logs -f --tail=100

log-%:
	@$(COMPOSE) logs -f --tail=100 $*

permissions:
	@$(COMPOSE) exec $(PHP_CONTAINER) chown -R www-data:www-data storage bootstrap/cache

fix:
	@$(COMPOSE) exec $(PHP_CONTAINER) ./vendor/bin/pint

analyse:
	@$(COMPOSE) exec $(PHP_CONTAINER) ./vendor/bin/phpstan

check:
	@make fix
	@make analyse
	@$(COMPOSE) exec $(PHP_CONTAINER) ./vendor/bin/composer-unused
	@$(COMPOSE) exec $(PHP_CONTAINER) composer audit
	@$(COMPOSE) exec $(PHP_CONTAINER) php artisan test

nuke-docker:
	@read -p "Удалить полностью докер проект? [y/N]: " confirm && \
	if [ "$$confirm" = "y" ]; then \
		docker compose down -v --remove-orphans --rmi all; \
		docker stop $(NUKE_CONTAINERS) 2>/dev/null || true; \
		docker rm -f $(NUKE_CONTAINERS) 2>/dev/null || true; \
		docker images --format '{{.Repository}}:{{.Tag}} {{.ID}}' | grep '^${COMPOSE_PROJECT_NAME}' | awk '{ print $$2 }' | xargs -r docker rmi -f; \
		docker images --format '{{.Repository}}:{{.Tag}} {{.ID}}' | grep '^app-' | awk '{ print $$2 }' | xargs -r docker rmi -f; \
		docker builder prune -f; \
		docker volume prune -f; \
		docker network prune -f; \
	else \
		echo "Отмена."; \
	fi
