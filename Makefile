
default: help


help: ## show this help menu
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## install composer dependencies
	composer install

update: ## update composer dependencies
	composer update
	composer dump-autoload

test: ## run tests
	./vendor/bin/phpunit tests

run: ## show dashboard
	@./php-sys-mon dashboard

