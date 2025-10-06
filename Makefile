USER_ID=$(shell id -u)
DC = @USER_ID=$(USER_ID) docker compose
CONSOLE = $(DC) exec php bin/console
COMPOSER = $(DC) exec php composer

up:
	$(DC) up -d --remove-orphans
down:
	$(DC) down --remove-orphans

cache:
	$(CONSOLE) cache:clear
list:
	$(CONSOLE) list

list-migrations:
	$(CONSOLE) doctrine:migrations:list
migrate:
	$(CONSOLE) doctrine:migrations:migrate
migration:
	$(CONSOLE) make:migration
fixtures:
	$(CONSOLE) doctrine:fixtures:load
