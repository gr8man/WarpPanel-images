.PHONY: generate build test check-updates catalog sync-obsidian install help

all: generate

help:
	@echo "WarpPanel Images Builder (PHP + Composer & Docker):"
	@echo "  make install        - Instaluje zależności Composera (symfony/yaml, twig/twig)"
	@echo "  make generate       - Generuje Dockerfile i docker-bake.hcl przez 'composer generate'"
	@echo "  make build          - Buduje obrazy za pomocą docker buildx bake przez 'composer build'"
	@echo "  make test           - Uruchamia testy integracyjne przez 'composer test'"
	@echo "  make check-updates  - Sprawdza dostępność nowszych wersji PHP, Nginx, Apache, FrankenPHP"
	@echo "  make catalog        - Aktualizuje catalog.json i CATALOG.md przez 'composer catalog'"
	@echo "  make sync-obsidian  - Synchronizuje dokumentację z Obsidianem przez 'composer sync-obsidian'"

install:
	composer install

generate:
	composer run generate

build: generate
	composer run build

test:
	composer run test

check-updates:
	composer run check-updates

catalog:
	composer run catalog

sync-obsidian:
	composer run sync-obsidian
