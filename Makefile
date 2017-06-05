.PHONY: help generate gource
.DEFAULT_GOAL := help

help: ## Output usage documentation
	@echo "Usage: make COMMAND [from=2017-05-08 to=2017-05-15]\n\nCommands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

generate: ## Generate stats
	docker run --rm -it --name stats -v "$$(pwd)":/usr/src/stats -w /usr/src/stats php:7.1-cli bin/stats generate --from=$(from) --to=$(to) --animation

gource: ## Create PPM file from gource log
	gource \
	--background 000000 \
	--title "Global PHP Group Weekly Stats" \
	--date-format "%d %B %Y" \
	--auto-skip-seconds 0.2 \
	--seconds-per-day 10 \
	--hide filenames,dirnames,mouse \
	--bloom-multiplier 1.0 \
	--bloom-intensity 0.5 \
	-e 0.02 \
	--key \
	-1920x1080 \
	--logo ./var/logo.png \
	-o ./var/video.ppm \
	./var/reports/gource_sorted.log

ffmpeg: ## Create MP4 file from PPM
	ffmpeg \
	-y \
	-r 60 \
	-f image2pipe \
	-vcodec ppm \
	-i ./var/video.ppm \
	-vcodec libx264 \
	-preset ultrafast \
	-pix_fmt yuv420p \
	-crf 1 \
	-threads 0 \
	-bf 0 \
	./var/video.mp4
