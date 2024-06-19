up:
	docker compose up
down:
	docker compose down
php:
	docker exec -it hyperf-sqlcommenter-app bash
check:
	tools/php-cs-fixer fix
	tools/psalm --output-format=console --show-info=true
install:
	phive install
	composer install