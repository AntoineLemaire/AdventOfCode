.PHONY: init-day run

init-day:
	php vendor/bin/aoc input $(shell date +%d | sed 's/^0//')

run:
	php vendor/bin/aoc run $(shell date +%d | sed 's/^0//')

test:
	php vendor/bin/aoc run $(shell date +%d | sed 's/^0//') --test
