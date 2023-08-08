# Default values
env = dev
colors = --colors=always

# Executables
EXEC_PHP = docker-compose exec -T php

ifeq ($(ci),true)
    fixerDryRun = --dry-run
    coverage = --coverage-text --coverage-cobertura cobertura-report.xml
    force = true
endif
ifeq ($(force),true)
    forceDrop = --force
endif

cs:
	$(EXEC_PHP) php tests/check-namespaces.php $(force)
	$(EXEC_PHP) php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix $(fixerDryRun)
#	$(EXEC_PHP) php vendor/phpstan/phpstan/phpstan analyse --memory-limit=512M

	$(EXEC_PHP) php bin/console lint:twig templates/
	$(EXEC_PHP) php bin/console lint:yaml --parse-tags config/
	$(EXEC_PHP) php bin/console doctrine:schema:validate --env=$(env)
	$(EXEC_PHP) php bin/console doctrine:mapping:info --env=$(env)

db:
	$(EXEC_PHP) php bin/console doctrine:database:drop --if-exists $(forceDrop) --env=$(env)
	$(EXEC_PHP) php bin/console doctrine:database:create --if-not-exists --env=$(env)
	$(EXEC_PHP) php bin/console doctrine:migration:migrate --no-interaction --env=$(env)

fixtures:
#	$(EXEC_PHP) php bin/console doctrine:fixtures:load --append --group=default --no-interaction --env=$(env)

test:
	make db env=test force=true
	make test-ci

test-ci:
	make fixtures env=test
	$(EXEC_PHP) bin/phpunit --testdox $(coverage)
