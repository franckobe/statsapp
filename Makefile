#######################################
## 📦 PHP-Nginx Docker template for Symfony projects
## 👤 Author: Franck GARROS
#######################################


PROJECT_NAME = php-nginx
DOCKER_COMPOSE = docker compose --project-name $(PROJECT_NAME)
PHP = php
SYMFONY = symfony
SYMFONY_CONSOLE = $(PHP) bin/console
DOCKER_EXEC = docker exec -it $(PHP) bin/console

#######################################
## 📖 Help
#######################################
.PHONY: help
help:
	@echo "Usage: make [target]"
	@echo ""
	@echo "Available targets:"
	@echo ""
	@echo "🛠️  Docker Commands"
	@grep -E '^(prune|build|stop|start|reload|logs):.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "🔧 Symfony Commands"
	@grep -E '^(init-project|init-database|database-create|database-drop|database-migrate):.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

#######################################
## 🛠️ Docker
#######################################
.PHONY: prune build stop start reload logs

prune: ## 🧹 Remove unused Docker resources
	@echo "Pruning Docker..."
	docker system prune -f
	docker volume prune -f
	docker network prune -f
	@volumes=$(docker volume ls -q); \
	if [ -n "$$volumes" ]; then \
	  $(DOCKER_COMPOSE) volume rm $volumes; \
	else \
	  echo "No Docker volumes to remove."; \
	fi

build: ## 🏗️ Build and start containers in detached mode
	@echo "Building container..."
	$(DOCKER_COMPOSE) up --build -d

stop: ## ⏹️ Stop containers without removing them
	@echo "Stopping container..."
	$(DOCKER_COMPOSE) stop

start: ## ▶️ Start containers without rebuilding
	@echo "Starting container..."
	$(DOCKER_COMPOSE) start

reload: ## 🔄 Restart containers with rebuild
	@echo "Reloading container..."
	make stop
	make prune
	make build

logs: ## 📜 Show logs of all containers
	@echo "Showing logs..."
	$(DOCKER_COMPOSE) logs -f

#######################################
## 🔧 Symfony
#######################################
.PHONY: init-project init-database database-create database-drop database-migrate

init-project: ## 📦 Create Symfony project inside the container
	@echo "Creating Symfony project..."
	chmod +x ./docker/php/initProject.sh
	./docker/php/initProject.sh

init-db: ## 🗄️ Initialize Symfony database and install ORM dependencies
	@echo "Creating database..."
	$(DOCKER_COMPOSE) exec php composer require symfony/orm-pack
	$(DOCKER_COMPOSE) exec php composer require --dev symfony/maker-bundle
	make reload

database-create: ## Create the database
	@echo "Creating database..."
	$(DOCKER_EXEC) doctrine:database:create --if-not-exists

database-drop: ## Drop the database
	@echo "Dropping database..."
	$(DOCKER_EXEC) doctrine:database:drop --force --if-exists

database-migrate: ## Migrate the database
	@echo "Migrating database..."
	$(DOCKER_EXEC) doctrine:migrations:migrate --no-interaction
