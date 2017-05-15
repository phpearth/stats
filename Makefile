.PHONY: help generate
.DEFAULT_GOAL := help

help: ## Output usage documentation
	@echo "Usage: make COMMAND [from=2017-05-08 to=2017-05-15]\n\nCommands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

generate: ## Generate stats
	docker run --rm -it --name stats -v "$$(pwd)":/usr/src/stats -w /usr/src/stats php:7.1-cli bin/stats generate --from=$(from) --to=$(to)
