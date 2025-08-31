#!/bin/bash

# Funkcja czekająca na stan "healthy" kontenera MySQL
wait_for_mysql() {
  local container_name=$1
  local retries=12
  local count=0

  echo "Sprawdzam dostępność MySQL w kontenerze $container_name..."

  while [ $count -lt $retries ]; do
    health=$(docker inspect --format "{{.State.Health.Status}}" "$container_name" 2>/dev/null || echo "unavailable")
    if [ "$health" == "healthy" ]; then
      echo "MySQL jest gotowy!"
      return 0
    fi
    echo "Aktualny status: $health. Czekam 5 sekund..."
    sleep 5
    ((count++))
  done

  echo "MySQL nie uruchomił się w oczekiwanym czasie."
  return 1
}

docker compose down -v

sudo chmod -R 777 var/cache/*
sudo chmod -R 777 var/log/*

docker compose up -d --build

# Czekaj na gotowość bazy
if wait_for_mysql "questions-mysql-1"; then
  # Dodatkowe czekanie na stabilizację po osiągnięciu healthy
  echo "Dodatkowe czekanie 5 sekund, aby baza była w pełni gotowa..."
  sleep 5

  docker compose exec php-fpm bash -c "rm -rf var/cache/*"
  docker compose exec php-fpm bash -c "rm -rf var/log/*"

  docker compose exec php-fpm bash -c "chown -R www-data:www-data public"
  docker compose exec php-fpm bash -c "chmod -R 775 public"

  docker compose exec php-fpm bash -c "composer install"
  docker compose exec php-fpm bash -c "./bin/console doctrine:migrations:migrate --no-interaction"
    docker compose exec php-fpm bash -c "./bin/console app:make-admin admin@example.com test123"

else
  echo "Baza danych nie jest dostępna. Przerywam migracje."
  exit 1
fi
