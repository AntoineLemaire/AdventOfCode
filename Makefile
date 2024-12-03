.PHONY: init-day

init-day:
	php vendor/bin/aoc input $(shell date +%d | sed 's/^0//')
