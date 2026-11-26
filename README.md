# Touche pas au klaxon

Application intranet de covoiturage entre agences. Elle permet de consulter les
prochains trajets disposant de places libres, de proposer un trajet après
connexion et de gérer les agences depuis un espace administrateur.

Projet de formation : « Mise en place d’une application MVC en PHP ».

## Fonctionnalités

| Profil | Fonctionnalités |
| --- | --- |
| Visiteur | Consulter les trajets futurs avec des places disponibles, classés du départ le plus proche au plus lointain. |
| Employé | Consulter les détails et les coordonnées de l’auteur, publier un trajet, modifier ou supprimer ses propres trajets. |
| Administrateur | Consulter les utilisateurs en lecture seule, créer, renommer et supprimer des agences, lister les trajets et les supprimer. |

Le formulaire de publication conserve les valeurs saisies si la validation
échoue et affiche l’erreur sous le champ concerné. Par exemple, une saisie
de 5 places disponibles pour 4 places au total est refusée.

## Architecture

L’application utilise PHP, un routeur, une architecture MVC, PDO pour l’accès
à MySQL ou MariaDB, et Bootstrap 5 avec Sass pour l’interface.

| Emplacement | Rôle |
| --- | --- |
| `public/index.php` | Point d’entrée HTTP de l’application. |
| `app/Core/` | Routage, connexion à la base, authentification, CSRF et messages flash. |
| `app/Controllers/` | Traitement des requêtes et validation des formulaires. |
| `app/Models/` | Accès aux utilisateurs, aux agences et aux trajets. |
| `views/` | Pages et formulaires de l’application. |
| `app/Data/` | Scripts SQL de création et d’alimentation de la base. |
| `app/scss/` | Sources Sass du thème. |
| `public/assets/css/app.css` | Feuille de style compilée. |

## Installation locale

### 1. Préparer l’environnement

- Utiliser PHP 8.1 ou supérieur avec les extensions `pdo_mysql` et `mbstring`.
- Disposer d’un serveur MySQL ou MariaDB. Sous Windows, XAMPP peut fournir PHP,
  MariaDB et phpMyAdmin.
- Cloner le dépôt ou extraire son ZIP, puis ouvrir dans VS Code le dossier qui
  contient directement `app/`, `public/` et `views/`.

### 2. Créer et alimenter la base

1. Dans XAMPP, démarrer MySQL et Apache pour accéder à phpMyAdmin.
2. Ouvrir `http://localhost/phpmyadmin/`.
3. Depuis l’accueil de phpMyAdmin, utiliser **Importer** et sélectionner
   `app/Data/covoit_mvc.sql`.
4. Sélectionner ensuite la base `covoit_mvc`, puis importer `app/Data/seed.sql`.

**Attention :** le premier script supprime et recrée la base `covoit_mvc`.
Ne pas l’importer sur une base qui contient des données à conserver.

Le second script ajoute les agences, les utilisateurs et les trajets d’essai.
Il initialise aussi les mots de passe des deux comptes de démonstration :
aucun hachage n’est à générer à la main. Les identifiants sont fournis dans
le PDF de restitution, à la rubrique « Identifiants de connexion ».

Les dates des trajets d’essai sont calculées à partir du jour de l’import.
Juste après une installation neuve, trois trajets doivent apparaître sur
l’accueil. Un trajet passé et un trajet futur complet servent à vérifier
qu’ils sont bien exclus de la liste publique.

### 3. Vérifier la configuration si nécessaire

Dans `app/config.php`, vérifier `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASS`
si la configuration locale diffère de celle de XAMPP. La base attendue
par défaut se nomme `covoit_mvc`.

### 4. Lancer l’application

Dans VS Code, ouvrir un terminal **PowerShell** à la racine du projet.
Si XAMPP est installé dans `C:\xampp`, exécuter :

```powershell
& "C:\xampp\php\php.exe" -S 127.0.0.1:8000 -t public
```

Si PHP est déjà disponible dans le terminal, utiliser à la place :

```powershell
php -S 127.0.0.1:8000 -t public
```

Laisser le terminal ouvert et afficher `http://127.0.0.1:8000/`
dans le navigateur. MySQL doit rester démarré.

### 5. Recompiler les styles uniquement si nécessaire

Le CSS compilé est fourni : Node.js n’est pas nécessaire pour lancer le site.
Pour modifier le thème, installer les dépendances puis lancer la compilation
en mode surveillance :

```powershell
npm install
npm run sass
```

## Utilisation

1. Ouvrir l’accueil `/` pour consulter les prochains trajets.
2. Cliquer sur **Connexion** et utiliser le compte employé indiqué dans le PDF.
3. Cliquer sur **Infos** pour afficher le contact et le nombre total de places.
4. Cliquer sur **Publier un trajet** pour ajouter un trajet. Après une écriture
   réussie, revenir à la liste et vérifier le message de confirmation.
5. Pour l’administration, se connecter avec le compte admin et cliquer sur
   **Accès admin**, ou ouvrir directement `/admin` après connexion.

## Vérifications

### Vérifications manuelles

Les vérifications réalisées en local portent sur l’affichage public, la
connexion avec les deux comptes de démonstration, la déconnexion, la
consultation des détails d’un trajet, la création et la modification d’un
trajet, le refus d’un nombre de places incohérent, la gestion des agences
et la suppression d’un trajet depuis l’administration.

### Analyse statique avec PHPStan

L’analyse a été exécutée au niveau `0` sur les dossiers `app`, `public`
et `views`. Résultat : 30 fichiers analysés, aucune erreur signalée.

### Tests automatisés avec PHPUnit

Le test `tests/AgencyModelTest.php` a été exécuté avec PHPUnit 11.5.58
et PHP 8.2.12 sur la base dédiée `covoit_mvc_test`.
Résultat : 1 test réussi, 9 assertions.

Le test vérifie la création, le renommage et la suppression d’une agence,
puis annule les écritures de test. Les opérations d’écriture sur les trajets
ont été vérifiées manuellement, mais ne sont pas couvertes par ce test
automatisé.

## Dépannage

- **Pas de trajet sur l’accueil :** Les dates des trajets de démonstration sont calculées 
  au moment de l’import. Une fois ces trajets 
  passés, l’accueil ne les affiche plus.
- **Connexion refusée :** utiliser exactement l’adresse de démonstration
  indiquée dans le PDF (`@email.fr`, pas `@gmail.com`) et saisir le mot de
  passe lisible, pas le hachage.
- **Erreur de connexion à la base :** vérifier que MySQL est démarré et que
  `app/config.php` pointe vers la bonne base.
- **Le terminal indique que `public` n’existe pas :** rouvrir le terminal
  depuis le dossier qui contient directement le sous-dossier `public/`.
- **La modale ne s’ouvre pas :** vérifier que le navigateur peut charger
  le script Bootstrap référencé par l’application.