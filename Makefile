start:
	php -S localhost:8080 -t public

driver-check:
	docker exec -it php_app php /var/www/bin/check.php

db-init:
	docker exec -it php_app vendor/bin/phinx migrate