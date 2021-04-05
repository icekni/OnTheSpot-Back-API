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
- Parametrer le fichier `.env.local` selon votre configuration (acces base de données, acces SMTP et passphrase JWT)
-<details>
  <summary>exemple de configuration</summary>
  
  ```
  DATABASE_URL="mysql://identifiant_bdd:mot_de_passe_bdd@127.0.0.1:3306/nom_bdd?serverVersion=mariadb-10.4.18"

  MAILER_DSN=smtp://identifiant_SMTP:mot_de_passe_SMTP@serveur_SMTP
  
  JWT_PASSPHRASE=phrase_secrete_au_libre_choix
  ```  
</details>

- Créer et parametrer la base de données
-<details>
  <summary>Comment faire</summary>
  
  (en terminal, depuis la racine du projet)
  
  Creation de la base de données
  ```
  bin/console doctrine:database:create
  ```
  
  Creation des tables, champs et relations (application des migrations)
  ```
  bin/console doctrine:migrations:migrate
  ```  
</details>

- (optionnel) Charger des données grace aux fixtures
-<details>
  <summary>Comment faire</summary>
  
  (en terminal, depuis la racine du projet)
  
  Creation de la base de données
  ```
  bin/console doctrine:fixtures:load
  ```  
</details>

- Acceder au Back Office
- Enjoy...

## Tester l'application

Un scrit bash qui lance les smoke tests, tests unitaires et fonctionnels a été créé à la racine du projet.
Ce script créé une base de données dédiée à l'environnement de test (à parametrer dans le `.env.test.local`)
Charge des fixtures (fausses données) spécifiques
et execute les tests.

Pour le lancer :
`sh runTest.sh`
