DealStorm

Ce projet est un clone du site Dealabs, une plateforme communautaire pour les bons plans, les réductions et les offres spéciales.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- Docker
- PHP 7.4 ou supérieur
- Composer

## Installation

1. Clonez le dépôt Git :

```shell
git clone https://github.com/BrunelFlorian/dealabs_brunel_lemetayer.git
cd dealabs_brunel_lemetayer
```

2. Installez les dépendances :

```shell
composer install
```

## Configuration

1. Modifiez le fichier `.env` pour configurer les variables d'environnement nécessaires, telles que les informations de connexion à la base de données.
Voici les lignes à décommenter :

```shell
APP_ENV=dev
APP_SECRET=1786d789f04c83fb3e1f45a60cd80366
MAILER_DSN="smtp://mailcatcher:1025"
# ...
DATABASE_URL="mysql://lpa_sf6:lpa_sf6@lpa_sf6_mysql/lpa_sf6?serverVersion=8&charset=utf8mb4"
```

## Base de données

Dans le conteneur PHP

1. Créez une nouvelle base de données pour le projet.

```shell
php bin/console doctrine:database:create
```

3. Mettez à jour les informations de connexion à la base de données dans le fichier `.env` avec les informations appropriées.

```shell
php bin/console doctrine:migration:migrate
```

4. Exécutez les migrations pour créer le schéma de base de données :

```shell
php bin/console make:migration
```

## Exécution du projet

Pour exécuter le projet via Docker

1. Démarrez les conteneurs Docker :

```shell
docker-compose up -d
```

2. Accédez à l'application dans votre navigateur à l'adresse [http://localhost:8081/](http://localhost:8081/).
