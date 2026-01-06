.PHONY: up install db fixtures init

up:
	@if [ -z "$$(docker network ls --filter name=^questions$$ -q)" ]; then \
		echo "Tworzenie sieci 'questions'..."; \
		docker network create questions; \
	else \
		echo "Siec 'questions' juz istnieje."; \
	fi
	docker compose up -d --build

install:
	docker compose exec php-fpm composer install

db:
	docker compose exec php-fpm bin/console doctrine:migrations:migrate -n

fixtures:
	docker compose exec php-fpm bin/console hautelook:fixtures:load -n

init: up install db fixtures
