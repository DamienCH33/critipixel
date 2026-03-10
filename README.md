<img src="assets/images/logo.png" alt="CritiPixel" width="200" />

# CritiPixel

## Description

CritiPixel est une application Symfony permettant de consulter et d'évaluer des jeux vidéo.

Ce projet met en place une architecture backend propre ainsi que des tests automatisés afin d'assurer la qualité du code.

![CI](https://github.com/DamienCH33/critipixel/actions/workflows/ci.yml/badge.svg)

## Stack technique

- PHP 8.2
- Symfony
- Doctrine ORM
- PostgreSQL
- PHPUnit
- PHPStan
- Docker

## Architecture

Le projet suit une architecture Symfony classique :

src/
 ├ Controller
 ├ Doctrine
 │   ├ Repository
 │   └ DataFixtures
 ├ EntityListener
 ├ Form
 ├ List
 ├ Rating
 |-Twig
 ├ Security
 │   └ Voter
 └ Tests

Composants principaux :

- **RatingHandler** : calcule la moyenne des notes et la distribution des votes
- **VideoGameRepository** : pagination et filtrage des jeux vidéo
- **VideoGameVoter** : empêche un utilisateur de poster plusieurs reviews
- **PaginationValueResolver** : injecte automatiquement la pagination dans les controllers

## Fonctionnalités

- consultation des jeux vidéo
- publication de reviews
- système de notation
- filtrage par tags
- pagination
- authentification utilisateur

## Installation

### Composer
Dans un premier temps, installer les dépendances :
```bash
composer install
```

### Docker (optionnel)
Si vous souhaitez utiliser Docker Compose, il vous suffit de lancer la commande suivante :
```bash
docker compose up -d
```

## Configuration

### Base de données
Actuellement, le fichier `.env` est configuré pour la base de données PostgreSQL mise en place dans `docker-compose.yml`.
Cependant, vous pouvez créer un fichier `.env.local` si nécessaire pour configurer l'accès à la base de données.
Exemple :
```dotenv
DATABASE_URL=mysql://root:Password123!@host:3306/criti-pixel
```

### PHP (optionnel)
Vous pouvez surcharger la configuration PHP en créant un fichier `php.local.ini`.

De même pour la version de PHP que vous pouvez spécifier dans un fichier `.php-version`.

## Usage

### Base de données

#### Supprimer la base de données
```bash
symfony console doctrine:database:drop --force --if-exists
```

#### Créer la base de données
```bash
symfony console doctrine:database:create
```

#### Exécuter les migrations
```bash
symfony console doctrine:migrations:migrate -n
```

#### Charger les fixtures
```bash
symfony console doctrine:fixtures:load -n --purge-with-truncate
```

*Note : Vous pouvez exécuter ces commandes avec l'option `--env=test` pour les exécuter dans l'environnement de test.*

### Tests
```bash
symfony php bin/phpunit

### Analyse statique
vendor/bin/phpstan analyse
```

*Note : Penser à charger les fixtures avant chaque éxécution des tests.*

### Serveur web
```bash
symfony serve
```

## Tests

Le projet contient :

- tests unitaires
- tests fonctionnels
- analyse statique avec PHPStan
- pipeline CI GitHub Actions