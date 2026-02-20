# 🛒 Projet E-commerce Symfony - Gestion de Boissons

> **2ème projet Symfony réalisé par Maxence VENNER**

Ce projet est une application web e-commerce développée en Symfony. Il permet la gestion d'un catalogue de boissons (produits) et d'une base de clients, avec un système d'authentification et de rôles (Admin, Manager, Utilisateur).

---

## 🚀 Fonctionnalités Principales

### 📦 Gestion des Produits (Boissons)
* **Catalogue :** Affichage de la liste des boissons avec tri par prix (croissant/décroissant).
* **Création / Édition :** Formulaire multi-étapes (Flow) pour l'ajout et la modification des boissons de manière fluide.
* **Export CSV :** Fonctionnalité d'exportation des boissons en un clic (Nom, Description, Prix) gérée via un Service Symfony.
* **Import CSV :** Commande console permettant d'importer un catalogue de boissons en masse depuis un fichier.

### 👥 Gestion des Clients
* **CRUD Complet :** Ajout, modification, affichage et listing des clients.
* **Sécurité & Accès :** L'onglet et la gestion des clients sont restreints aux Administrateurs et Gestionnaires grâce à un système de Voters.
* **Validations strictes :** Vérification du format de l'email, de l'unicité des adresses, et blocage des caractères spéciaux dans les noms/prénoms.
* **Commande Interactive :** Ajout de clients directement depuis le terminal via une commande guidée.

---

## 📟 Commandes Console Personnalisées

Ce projet inclut des commandes Symfony développées sur mesure pour faciliter la gestion :

* **Création d'un client interactivement :**
  ```bash
  php bin/console app:add-client

* **Importation d'un fichier csv dans la base de donnée :**
  ```bash
  php bin/console app:import-products --filename=mes_boissons.csv

⚠️ Note pour l'importation :
-Le fichier doit être placé dans le dossier public/ du projet.

-Il doit respecter le format d'en-tête : Nom | Description | Prix.

-L'extension doit être .csv (séparateur point-virgule ;).

-Le nom exact du fichier doit être indiqué après l'option --filename=. Un fichier modèle mes_boissons.csv est déjà présent.

---

## 🛠️ Prérequis

* **PHP** : version 8.4 ou supérieure
* **Composer**
* **Base de données** : MySQL
* **Symfony CLI** 

---

## ⚙️ Installation du projet

1. **Cloner le dépôt :**
   ```bash
   git clone https://github.com/ISmeliodasNT/projet_symfony_2
   cd projet_symfony_2

2. **Installer les dépendances PHP :**
    ```bash
    composer install 

3. **Configurer les variables d'environnement :**

Copiez le fichier .env en .env.local

Modifiez la variable DATABASE_URL avec vos identifiants de base de données

---

## 🗄️ Base de données
La structure et les données de base sont fournies dans le fichier projet-symfony_2.sql à la racine du projet.
Pour l'importer, vous pouvez utiliser votre interface de gestion de base de données habituelle (comme phpMyAdmin, DBeaver, etc.). 

---

## 🔐 Comptes de tests

Administrateur : Accès total
admin@gmail.com
admin1234

Gestionnaire : Gestion des clients
manager@gmail.com
manager1234

Utilisateur : Consultation des produits
user@gmail.com
user1234