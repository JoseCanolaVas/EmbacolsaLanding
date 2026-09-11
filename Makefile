FRONTEND_DIR=apps/frontend
BACKEND_DIR=apps/backend

.PHONY: frontend backend generate build migrate seed clear-cache storage-link passport-keys test-api

frontend:
	cd $(FRONTEND_DIR) && npm run dev

backend:
	cd $(BACKEND_DIR) && php artisan serve --port=8000

generate:
	cd $(FRONTEND_DIR) && npm run generate

build:
	cd $(FRONTEND_DIR) && npm run build

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
