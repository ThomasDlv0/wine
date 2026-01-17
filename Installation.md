# Installation du Projet Wine avec Docker

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre machine :

- **Docker Desktop** (version 20.10 ou supérieure)
  - [Télécharger Docker Desktop pour Mac](https://docs.docker.com/desktop/install/mac-install/)
  - [Télécharger Docker Desktop pour Windows](https://docs.docker.com/desktop/install/windows-install/)
  - [Télécharger Docker Desktop pour Linux](https://docs.docker.com/desktop/install/linux-install/)
- **Docker Compose** (généralement inclus avec Docker Desktop)
- **Git** (optionnel, pour cloner le projet)

### Vérification de l'installation

Pour vérifier que Docker est correctement installé, exécutez les commandes suivantes :

```bash
docker --version
docker compose version
```

## 🏗️ Architecture du Projet

Le projet utilise une architecture Docker Compose avec 3 services :

- **nginx** : Serveur web (port 8080)
- **php-fpm** : Interpréteur PHP 8.4 avec extensions PDO et MySQL
- **mysql** : Base de données MySQL 8.0 (port 3306)

## 🚀 Installation et Démarrage

### Étape 1 : Cloner ou télécharger le projet

```bash
cd /chemin/vers/votre/dossier
git clone <url-du-repo>
cd filsRouges
```

### Étape 2 : Construire et démarrer les conteneurs

À la racine du projet (où se trouve le fichier `compose.yml`), exécutez :

```bash
docker compose up -d --build
```

**Explications :**
- `up` : démarre les conteneurs
- `-d` : mode détaché (en arrière-plan)
- `--build` : reconstruit les images si nécessaire

### Étape 3 : Vérifier que les conteneurs sont actifs

```bash
docker compose ps
```

Vous devriez voir 3 conteneurs en cours d'exécution :
- `nginx` (port 8080:80)
- `php-fpm`
- `mysql` (port 3306:3306)

### Étape 4 : Accéder à l'application

Ouvrez votre navigateur et accédez à :

```
http://localhost:8080
```

## 🗄️ Configuration de la Base de Données

### Informations de connexion MySQL

Les identifiants par défaut sont définis dans le fichier `compose.yml` :

- **Hôte** : `db` (ou `localhost` depuis votre machine sur le port 3306)
- **Base de données** : `wine_db`
- **Utilisateur** : `root`
- **Mot de passe** : `password`
- **Mot de passe root** : `root`

### Se connecter à MySQL depuis le conteneur

```bash
docker exec -it mysql mysql -u root -p
# Entrez le mot de passe : root
```

### Se connecter à MySQL depuis votre machine

Si vous avez un client MySQL installé localement :

```bash
mysql -h 127.0.0.1 -P 3306 -u root -p
# Entrez le mot de passe : root
```

### Importer un fichier SQL (si nécessaire)

```bash
docker exec -i mysql mysql -u root -proot wine_db < /chemin/vers/votre/fichier.sql
```

## 🛠️ Commandes Utiles

### Arrêter les conteneurs

```bash
docker compose stop
```

### Démarrer les conteneurs (après un stop)

```bash
docker compose start
```

### Redémarrer les conteneurs

```bash
docker compose restart
```

### Arrêter et supprimer les conteneurs

```bash
docker compose down
```

### Arrêter et supprimer les conteneurs + volumes (⚠️ efface les données)

```bash
docker compose down -v
```

### Voir les logs

```bash
# Tous les services
docker compose logs -f

# Un service spécifique
docker compose logs -f nginx
docker compose logs -f php
docker compose logs -f db
```

### Accéder au shell d'un conteneur

```bash
# PHP
docker exec -it php-fpm bash

# Nginx
docker exec -it nginx bash

# MySQL
docker exec -it mysql bash
```

### Reconstruire les images

```bash
docker compose build --no-cache
docker compose up -d
```

## 🐛 Dépannage

### Les conteneurs ne démarrent pas

1. Vérifiez que les ports 8080 et 3306 ne sont pas déjà utilisés :
   ```bash
   lsof -i :8080
   lsof -i :3306
   ```

2. Consultez les logs pour identifier l'erreur :
   ```bash
   docker compose logs
   ```

### Erreur 502 Bad Gateway

Cela indique généralement que PHP-FPM ne répond pas :

```bash
docker compose restart php
docker compose logs php
```

### Problèmes de permissions

Si vous rencontrez des erreurs de permissions sur les fichiers :

```bash
# Sur Mac/Linux
chmod -R 755 wine/
```

### Réinitialiser complètement le projet

```bash
docker compose down -v
docker compose up -d --build
```

### La page ne s'affiche pas

1. Vérifiez que le fichier `index.php` existe dans le dossier `wine/`
2. Consultez les logs Nginx :
   ```bash
   docker compose logs nginx
   ```

## 📝 Configuration PHP

Le conteneur PHP inclut les extensions suivantes :
- PDO
- PDO MySQL
- MySQL Client

Pour ajouter d'autres extensions, modifiez le fichier `docker/php/Dockerfile` et reconstruisez l'image.

## 🔒 Sécurité (Production)

⚠️ **Important** : La configuration actuelle est prévue pour le développement. Pour la production :

1. Changez les mots de passe MySQL dans `compose.yml`
2. Utilisez des variables d'environnement (fichier `.env`)
3. Ne publiez pas les ports MySQL directement
4. Configurez SSL/TLS pour Nginx
5. Ajoutez un fichier `.dockerignore`

## 📚 Ressources Supplémentaires

- [Documentation Docker](https://docs.docker.com/)
- [Documentation Docker Compose](https://docs.docker.com/compose/)
- [Documentation PHP-FPM](https://www.php.net/manual/fr/install.fpm.php)
- [Documentation Nginx](https://nginx.org/en/docs/)
- [Documentation MySQL](https://dev.mysql.com/doc/)

## 💡 Conseils de Développement

### Mode développement avec rechargement automatique

Les volumes sont configurés pour synchroniser automatiquement vos modifications :
- Modifiez les fichiers dans le dossier `wine/`
- Rafraîchissez votre navigateur
- Les changements sont immédiatement visibles

### Debugging PHP

Pour activer le mode debug, ajoutez dans votre code PHP :

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## ❓ Support

En cas de problème, vérifiez :
1. Les logs des conteneurs : `docker compose logs`
2. L'état des conteneurs : `docker compose ps`
3. L'utilisation des ressources : `docker stats`

---

**Version du document** : 1.0  
**Dernière mise à jour** : Janvier 2026
