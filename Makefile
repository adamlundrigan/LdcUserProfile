all: cs-test phpunit

cs-test:
	./vendor/bin/php-cs-fixer fix -v --dry-run --config-file=.php_cs src;
	./vendor/bin/php-cs-fixer fix -v --dry-run --config-file=.php_cs tests;
	./vendor/bin/php-cs-fixer fix -v --dry-run --config-file=.php_cs config;
	./vendor/bin/php-cs-fixer fix -v --dry-run --config-file=.php_cs demo/ExtensionModule;
	./vendor/bin/php-cs-fixer fix -v --dry-run --config-file=.php_cs demo/files;

cs-fix:
	./vendor/bin/php-cs-fixer fix -v --config-file=.php_cs src;
	./vendor/bin/php-cs-fixer fix -v --config-file=.php_cs tests;
	./vendor/bin/php-cs-fixer fix -v --config-file=.php_cs config;
	./vendor/bin/php-cs-fixer fix -v --config-file=.php_cs demo/ExtensionModule;
	./vendor/bin/php-cs-fixer fix -v --config-file=.php_cs demo/files;

phpunit:
	./vendor/bin/phpunit
