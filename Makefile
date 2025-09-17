start:
	php -S localhost:8080 -t public

driver-check:
	docker exec -it php_app php /var/www/bin/check.php

db-init:
	#docker exec -it php_app vendor/bin/phinx migrate
	./vendor/bin/phinx migrate && ./vendor/bin/phinx seed:run -e development

db-sw-dev:
	./vendor/bin/phinx migrate -e development

db-sw-prod:
	./vendor/bin/phinx migrate -e production