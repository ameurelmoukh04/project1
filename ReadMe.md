# Projet To-Do List Full Stack

## 📌 Vous Pouvez Voir Une Demonstration live Du Test Ici En Youtube.
Lien : https://www.youtube.com/watch?v=jiC_Ik1rGo0

## 📌 Description

Cette application est une **To-Do List Full Stack** développée pour le test technique.  
Elle permet à un utilisateur de :  

- **S’inscrire** et **se connecter**  
- **Créer, modifier, supprimer et afficher ses tâches**  
- Recevoir des **notifications en temps réel** via **Pusher**  

L’objectif pédagogique est d’apprendre à :  

- Structurer un projet professionnel  
- Utiliser **Laravel** pour le backend et **Vue.js** pour le frontend  
- Mettre en place une **authentification sécurisée via JWT**  
- Implémenter des **design patterns** comme Repository et Service  

---
## Important !!!
J’ai réalisé un front-end basique pour montrer le fonctionnement de l’application, car c’est ma première expérience avec Vue.js et le temps était limité.

## 🛠️ Technologies utilisées

- **Backend** : Laravel 12, PHP 8.x, MySQL 
- **Frontend** : Vue.js 3, Axios, Tailwind CSS  
- **Websockets** : Pusher + Laravel Echo  
- **Authentification** : JWT (PHP Open Source Saver)  
- **Patterns** : Repository & Service  

---

## 🏗️ Architecture et Design Patterns

### 🔹 Repository
Le **Repository** est responsable de **l’accès aux données**.  
Exemple : `TaskRepository` contient toutes les requêtes SQL ou Eloquent pour les tâches et retourne les modèles ou collections à la couche Service.

### 🔹 Service
Le **Service** contient **la logique métier**.  
Exemple : `TaskService` utilise `TaskRepository` pour effectuer des actions plus complexes, comme :  
- Ajouter une tâche pour un utilisateur spécifique  
- Envoyer une notification après création d’une tâche  

### 🔹 Controller
Le **Controller** reçoit la requête HTTP, appelle les Services et retourne la réponse JSON au frontend.

**Flux classique :**  
`Controller → Service → Repository → DB → Service → Controller → Frontend`

---

## 🗄️ Base de données

### Tables principales :
- `users` : id, full_name, email, phone_number, address, profile_picture, password  
- `tasks` : id, user_id, title, created_at, updated_at  
- `notifications` : id, user_id, message, read_at, created_at, updated_at  

### Relations :  
- `User` **1:N** `Task`  
- `User` **1:N** `Notification`  

---

## ⚙️ Installation locale

### 🔧 Prérequis
- PHP 8.x  
- Composer  
- Node.js et npm  
- MySQL ou PostgreSQL  

### 🚀 Étapes

1. **Cloner le projet :**
   ```bash
   git clone https://github.com/ameurelmoukh04/project1

2. **Run le Front-End :**
- cd projet1-frontend
- npm install
- npm run dev

3. **Run le Back-End :**
- cd project1-backend
- cp .env.example .env
- composer install
- php artisan key:generate
- php artisan migrate
- php artisan serve

4. **ajouter ces variables d'envirenment de Pusher :**

- BROADCAST_DRIVER=pusher
- PUSHER_APP_ID=2048606
- PUSHER_APP_KEY=1b891a822f15667a8d89
- PUSHER_APP_SECRET=5ccadee2c5cd35d27156
- PUSHER_APP_CLUSTER=eu

