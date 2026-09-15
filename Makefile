FRONTEND_DIR=apps/frontend
BACKEND_DIR=apps/backend
COMPOSE=docker compose -f docker/compose/docker-compose.yml

.PHONY: frontend backend generate build docker-up docker-down docker-build docker-logs docker-ps docker-migrate docker-seed docker-clear-cache migrate seed clear-cache storage-link passport-keys test-api test-api-docker

frontend:
	cd $(FRONTEND_DIR) && npm run dev

backend:
	cd $(BACKEND_DIR) && php artisan serve --port=8000

generate:
	cd $(FRONTEND_DIR) && npm run generate

build:
	cd $(FRONTEND_DIR) && npm run build

docker-up:
	$(COMPOSE) up -d

docker-down:
	$(COMPOSE) down

docker-build:
	$(COMPOSE) up -d --build --force-recreate

docker-logs:
	$(COMPOSE) logs -f --tail=120

docker-ps:
	$(COMPOSE) ps

docker-migrate:
	$(COMPOSE) exec backend php artisan migrate --force

docker-seed:
	$(COMPOSE) exec backend php artisan db:seed --force

docker-clear-cache:
	$(COMPOSE) exec backend php artisan optimize:clear

migrate:
	cd $(BACKEND_DIR) && php artisan migrate --force

seed:
	cd $(BACKEND_DIR) && php artisan db:seed --force

clear-cache:
	cd $(BACKEND_DIR) && php artisan optimize:clear

storage-link:
	cd $(BACKEND_DIR) && php artisan storage:link

passport-keys:
	cd $(BACKEND_DIR) && php artisan passport:keys --force

test-api:
	curl -sS -X POST http://localhost:8000/api/productos/listar -H 'Content-Type: application/json' -d '{"limite":1}' | python3 -m json.tool

test-api-docker:
	curl -sS -X POST http://localhost/api/productos/listar -H 'Content-Type: application/json' -d '{"limite":1}' | python3 -m json.tool
