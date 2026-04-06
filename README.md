# Symfony 6.4 Development Boilerplate (Docker + Caddy)

This is a professional Docker-based development environment for **Symfony 6.4 LTS**. It includes a **Caddy** web server (with native HTTPS support), **PHP 8.2-FPM** (optimized with PCOV for unit testing), and **MySQL 8.4**.

## 🚀 Prerequisites

- **Docker** and **Docker Compose**.
- **Symfony CLI** (installed locally for initial project creation).
- If you are using **Fedora** or systems with SELinux, the project is already configured with the necessary security labels (`:Z`).

## 🛠️ Installation and Setup

Follow these steps to set up the environment from scratch:

### 1. Clone and configure the environment

Clone the repository and create your local environment variables file:

```bash
# Docker
cp .env.example .env

# API
cp api/.env.dev api/.env
```

### 2. Run dockers

Use root (sudo/su)

```bash
sudo docker compose -f docker-compose.dev.yaml up -d --build
```

### 3. Install deps

```bash
sudo docker exec -it php-fpm composer install
```

### 4. Create DB

```bash
sudo docker exec -it php-fpm php bin/console doctrine:database:create --if-not-exists
```

### 5. Create DB

```bash
sudo docker exec -it php-fpm php bin/console doctrine:migrations:migrate
```

### 6. Show in browser

Open you browser in URL: http://localhost

:)
