COMPOSE=docker compose

.PHONY: up down ps logs backend frontend frontend-fast backend-fast migrate seed clear-cache test-api

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

ps:
	$(COMPOSE) ps

logs:
	$(COMPOSE) logs -f --tail=120 backend frontend

backend:
	$(COMPOSE) up -d --build --force-recreate backend

frontend:
	$(COMPOSE) up -d --build --force-recreate frontend

frontend-fast:
	$(COMPOSE) up -d --no-deps --build --force-recreate frontend

backend-fast:
	$(COMPOSE) up -d --no-deps --build --force-recreate backend

migrate:
	$(COMPOSE) exec backend php artisan migrate --force

seed:
	$(COMPOSE) exec backend php artisan db:seed --force

clear-cache:
	$(COMPOSE) exec backend php artisan optimize:clear

test-api:
	curl -sS -X POST http://localhost:8080/api/productos/listar -H 'Content-Type: application/json' -d '{"limite":1}' | python3 -m json.tool
