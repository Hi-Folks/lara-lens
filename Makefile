
help:           ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

phpstan: ## Execute phpstan
	vendor/bin/phpstan analyse src -c ./vendor/nunomaduro/larastan/extension.neon  --level=4 --no-progress


test: ## Execute phpunit
	vendor/bin/phpunit --testdox

coverage: ## Execute the coverage test
	vendor/bin/phpunit --coverage-text

phpcs: ## execute phpcs
	vendor/bin/phpcs --standard=PSR12 src

allcheck: phpcs phpstan test ## all check


