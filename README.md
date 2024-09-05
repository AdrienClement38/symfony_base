# Symfony 7.1 avec Docker Compose, PHPUnit et CI/CD
Ce projet est une application Symfony 7.1 configurée pour s'exécuter dans des conteneurs Docker. Il inclut des tests unitaires avec PHPUnit et des pipelines CI pour GitHub Actions, GitLab CI et Jenkins.

## Prérequis
- Docker
- Docker Compose

## Installation
1. Clonez le dépôt :
    ```bash
    git clone https://github.com/nicolasvauche/testing_sf.git
    ```
2. Construisez et lancez les conteneurs Docker :
    ```bash
    docker-compose up -d
    ```
3. Accédez à l'application :  
    Ouvrez votre navigateur et accédez à `http://localhost:8081/hello/<votre-prenom>`.
