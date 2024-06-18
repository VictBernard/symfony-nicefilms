# <span style="color;">Guide de Déploiement de NiceFilm</span>

Le guide de déploiement fournit des instructions étape par étape pour installer et configurer NiceFilms.
Ce guide s'adresse aux administrateurs système, aux développeurs et à toute personne responsable du déploiement du logiciel.

## Guide d'installation

## Installation du framework Symfony

Si votre système d'exploitation est linux, vous pouvez installer le framework symfony grâce à la commande suivante :

```
wget https://get.symfony.com/cli/installer -O - | bash
```


Si l'éxécution s'est bien déroulé, une ligne dans votre terminal devrait ressembler à celle-ci : 

```
$export PATH="$HOME/ .symfony/bin:$PATH"
```

Copier cette ligne puis tapez la commande suivante afin d'ouvrir le fichier .bashrc dans lequel vous allez devoir y mettre la ligne que vous venez de copier :

```
nano ~/.bashrc
```

Allez à la fin du fichier puis copiez la ligne 

Sauvegardez puis fermez le fichier et tapez la commande suivante :

```
source ~/.bashrc
```

Maintenant, si vous tapez la commande  ```symfony```, une explication du framework devrait apparaître. Cela signifie que l'installation s'est bien déroulé.

## Installation des composers 

Une fois que symfony est installé, vous devez installer le **composer paginator** à l'aide de la commande suivante :

```
composer require knplabs/knp-paginator-bundle
```

## Base De Données 

Connectez vous sur phpMyadmin, creez une nouvelle base puis remplissez la avec le script suivant : [shows.sql](../shows.sql)

Dans un premier temps, dans le même dossier que le fichier **.env**, créez un nouveau fichier avec le nom suivant : **.env.local.** Ce fichier va vous permettre de vous connecter à votre base de données.
Copiez tout le contenu de votre fichier **.env** et coller le dans votre nouveau fichier **.env.local.**

Remplacez la ligne DATABASE_URL par : 
``DATABASE_URL="mysql://nomUtilisateur:motDePasse@nomDuServeur/nomDeLaBase"`` 

Une fois terminé, tapez la commande suivante afin de mettre à jour la base de données :  
```
symfony console doctrine:schema:update --force
```

Toutes les installations sont désormais faites.

## Lancement de l'application

Vous pouvez désormais lancer l'application. Tapez la commande 
 suivante afin de lancer un serveur :

```
symfony serve 
```
Ouvrez un naviguateur et tapez cette URL : http://127.0.0.1:8000/

Vous voilà désormais sur l'application.

## Super Admin 

Un compte super Admin permet d'avoir tout les droits. 
Une fois qu'un compte est crée, la commande suivante permet de passer ce dernier en super admin : 

```
symfony console App:AssignSuperAdmin <mail>
```
