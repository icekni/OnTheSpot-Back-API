# On The Spot - API & BackOffice (WIP)

## Sommaire

## Contexte

OnTheSpot est une application web permettant à des vacanciers profitant de la plage de pouvoir commander leur glaces, beignet, chouchou... depuis leur serviette.
A mi chemin entre la borne de reservation McDo et uber eat, il permettra aux vacanciers de ne plus avoir a attendre de longues heures sans savoir si un vendeur ambulant passera et aura les produits désirés.
Mais il sera surtout d'une grande aide pour le vendeur pour savoir qui souhaite acheter quoi, à quelle heure et à quel endroit. Facilitant ainsi sa tournée des plages.

## Technologies utilisées

Pour faire fonctionner notre Backend, nous avons utilisé le framework PHP Symfony en version 5.2 connecté à une base de données MySQL / MariaDB via Doctrine.
Il fonctionne en mode classique (template twig) pour le Back Office, et en mode API Rest pour la liaison avec le front.
En dehors du Website Skeleton Symfony, nous avons utilisé :
- Bootstrap pour le CSS du Back Office
- JS pour l'interactivité du Back Office
- Leaflet pour l'affichage des commandes sur une carte
- PHP unit pour les tests unitaires et fonctionnels
- Faker pour la génération de fausses données durant le developpement
- Trello pour la gestion en methode Agile des user stories
- Slack & Discord pour communiquer
- Insomnia pour tester les requetes de l'API

## Installation

### Prérequis

- Un serveur Web
- 'Composer' installé
- Une base de données

### Installation

- Telecharger ou cloner ce dépot GitHub
- Installer les dépendances
- Parametrer le fichier .env.local selon votre configuration (acces base de données et passphrase JWT)
- Créer et parametrer la base de données
- (optionnel) Charger des données grace aux fixtures
- Acceder au Back Office
- Enjoy...
