
default: help


help: ## Show this help
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Runs all kind of stuff
	composer install

update: ## Runs all kind of stuff
	composer update
	composer dump-autoload

run: ## Server the Desktop-App via caddy
	@./php-sys-mon phpSysMon:dashboard

