# Étape 1 : Construction de l'application
FROM node:18 AS build-stage

# Définir le répertoire de travail dans le conteneur
WORKDIR /app

# Copier les fichiers package.json et package-lock.json
COPY package*.json ./

# Installer les dépendances
RUN npm install

# Copier le reste des fichiers du projet
COPY . .

# Construire l'application pour la production
RUN npm run dev

# Étape 2 : Configuration du serveur Nginx pour servir l'application
FROM nginx:alpine AS production-stage

# Copier les fichiers construits depuis l'étape de construction
COPY --from=build-stage /app/dist /usr/share/nginx/html

# Copier le fichier de configuration Nginx personnalisé
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Exposer le port que Nginx utilisera
EXPOSE 80

# Démarrer Nginx
CMD ["nginx", "-g", "daemon off;"]
