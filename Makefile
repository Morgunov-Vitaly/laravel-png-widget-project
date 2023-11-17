init: down up

down:
	./vendor/bin/sail down

up:
	./vendor/bin/sail up -d

remove-logs:
	sudo rm -rf ./storage/logs/*.log

add-alias:
	bash bash_aliases.sh

pint:
	./vendor/bin/sail php ./vendor/bin/pint --dirty $(ARGS)

pint-test:
	./vendor/bin/sail php ./vendor/bin/pint --dirty --test $(ARGS)

