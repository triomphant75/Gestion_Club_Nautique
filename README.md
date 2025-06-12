# 🛥️ Club Nautique – Système d’Information (SI)

Projet réalisé dans le cadre du Master 1 CSI – Méthode et Optimisation  
Groupe 13 | Année académique 2024–2025

## 🎯 Objectif du Projet

Développer une application web de gestion pour un **club nautique**, afin d’améliorer son efficacité opérationnelle par l’automatisation des processus suivants :
- Gestion des **cours** (planification, inscriptions, moniteurs)
- Gestion des **locations de matériel nautique**
- Suivi des **forfaits** clients
- Traitement des **factures**
- Archivage des données (**historique des actions**)
- Gestion des **utilisateurs et rôles**

---

## 👥 Membres de l’équipe

- Triomphant Aldi NZIKOU  
- Moustapha KONE  
- Yahse ZOGBEMA  
- Warda SOULAIMANA

Supervision :
- Mme Armelle BRUN (Conseillère IT)  
- M. Yannick NARBEY (Responsable du club nautique)

---

## 🛠️ Stack Technique

- **Backend** : Symfony (PHP)
- **Base de données** : PostgreSQL
- **Outils de gestion de projet** : Trello (SCRUM)
- **Modélisation** : Lucidchart, UML (Use Case, Classes, Séquences, Activités)
- **Documentation** : PDF + Maquettes + Diagrammes

---

## 🔑 Fonctionnalités principales

### Gestion des Cours
- Création, modification, annulation et archivage
- Attribution des moniteurs
- Suivi des places disponibles
- Déclaration d’absence

### Gestion des Clients
- Ajout, recherche, modification, suppression
- Inscription à un cours
- Attribution de forfaits/remises

### Gestion du Matériel
- Ajout, suivi de l’état (panne, hors service)
- Location à un client
- Déclaration de panne
- Archivage des états

### Gestion des Utilisateurs
- Rôles : `Propriétaire`, `Compagne du Propriétaire`, `Moniteur`, `IT`
- Droits précis attribués à chaque rôle via SQL (GRANT, TRIGGER, FUNCTION)

### Facturation et Historique
- Création et consultation de factures
- Archivage automatique des cours, clients, factures, locations
- Suivi des actions via journaux d’audit

---

## 📚 Modélisation et Documentation

- 📄 Diagramme de cas d’utilisation  
- 🧱 Diagramme de classes  
- 🔁 Diagramme d'états (cours, matériel, utilisateurs)  
- 🔄 Diagrammes de séquences et d’activités  
- 🔐 Scripts SQL pour la gestion des rôles et procédures stockées  
- 📂 Dictionnaire de données (modèle relationnel complet)

---

## 🧪 Tests et Déploiement

- Tests unitaires des procédures SQL
- Validation fonctionnelle des rôles et autorisations
- Maquettes validées avec les utilisateurs métiers
- Fonctionnalités testées en local (Symfony server + Postgres)

---

## 🚀 Pour lancer le projet

```bash
# 1. Cloner le dépôt
git clone https://github.com/triomphant75/Gestion_Club_Nautique.git
cd club-nautique-si

# 2. Installer les dépendances PHP
composer install

# 3. Lancer le serveur Symfony
symfony server:start

# 4. Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
