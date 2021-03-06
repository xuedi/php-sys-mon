
default: help


help: ## show this help menu
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## install composer dependencies
	composer install

update: ## update composer dependencies
	composer update
	composer dump-autoload

infos: ## show various environment infos
	php --version
	composer show --tree

run: ## show dashboard
	@./php-sys-mon dashboard




test: test_unit test_psalm ## run all tests

test_unit: ## run unit tests
	@./vendor/bin/phpunit --configuration tests/phpunit.xml

test_psalm: ## run psalm
	@./vendor/bin/psalm --config='tests/psalm.xml' --show-info=true

coverage_badge: test_unit ## generate badge and add it to repo
	php tests/badge_generator.php
	git add tests/badge/coverage.svg

