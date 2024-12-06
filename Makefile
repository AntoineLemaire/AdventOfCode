.PHONY: init-day run

init-day:
	@if [ ! -f "$(shell date +%Y)/day-$(shell date +%d | sed 's/^0//')/solution.php" ]; then \
		php vendor/bin/aoc input $(shell date +%d | sed 's/^0//'); \
	else \
		echo "\033[31mThis day has already been initialized!\033[0m"; \
	fi

run:
	php vendor/bin/aoc run $(shell date +%d | sed 's/^0//')

test:
	php vendor/bin/aoc run $(shell date +%d | sed 's/^0//') --test
