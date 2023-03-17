# Acme corp API

Zadanie dla RTB House

## Requirements

- PHP >= 8.1
- composer >= 2.5
- sqlite (default), MySQL or PostgreSQL
- Docker with docker-composer or Symfony CLI

## Installation (Docker)

1. build images

```bash
docker compose build --pull --no-cache
```

2. Run containers

```bash
docker compose up
```

3. Go to `https://localhost:8243/doc`

## Installation (Symfony CLI)

1. Install dependencies:

```bash
composer install
```

2. Setup database

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

3. Run local server

```bash
symfony server:start
```

4. Follow address returned by CLI
