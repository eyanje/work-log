REPOSITORY = harbor.eyanje.net/work-log
TAG = $(shell git rev-parse HEAD)

DRIVER = docker
BUILD = $(DRIVER) build

BUILD_ARGS += --network host
BUILD_ARGS += --build-arg REPOSITORY=$(REPOSITORY)
BUILD_ARGS += --build-arg BASE_TAG=$(TAG)
BUILD_ARGS += --cache-from=$(REPOSITORY)/build-cache
BUILD_ARGS += --cache-to=$(REPOSITORY)/build-cache

IMAGES += build-agent
IMAGES += base
IMAGES += development
IMAGES += migration
IMAGES += php-fpm
IMAGES += web

# Build

BUILD_TARGETS = $(addsuffix .image,$(IMAGES))

.PHONY: all
all: $(BUILD_TARGETS)

$(BUILD_TARGETS): FORCE

%.image: docker/%/Dockerfile
	$(BUILD) $(BUILD_ARGS) \
		-t $(REPOSITORY)/$*\:$(TAG) \
		-t $(REPOSITORY)/$*\:latest \
		-f $< .

development.image: base.image
migration.image: base.image
php-fpm.image: base.image
web.image: base.image

# Test

.PHONY: test
test: # all
	$(DRIVER) run --rm $(REPOSITORY)/development\:$(TAG) composer run test

# Push

PUSH_TARGETS = $(addsuffix .push,$(IMAGES))

.PHONY: push
push: $(PUSH_TARGETS)

%.push: # %.image test
	$(DRIVER) image push $(REPOSITORY)/$*\:latest

# Force rebuild without PHONY

FORCE:

