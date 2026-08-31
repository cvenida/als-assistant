# als-assistant


# ALS Assistant Setup Guide

This guide provides step-by-step instructions for setting up and running the **ALS Assistant** project in your local development environment.

---

## 🛠️ Prerequisites & Installation

Before starting, make sure you have the following tools installed on your machine:

1. **Docker / Docker Desktop**: [Download & Install Docker](https://www.docker.com/products/docker-desktop/)
2. **DBeaver** (Database Management Tool): [Download & Install DBeaver](https://dbeaver.io/download/)
3. Node version >= 24

---

## 🚀 Getting Started

### 1. Environment Configuration & Database Connection
* Request the necessary `.env` configuration file from the system administrator.
* Place the provided `.env` file inside the `admin-api` directory.
* Open **DBeaver** and set up a new connection to the `als-assistant` database using the credentials specified in your `.env` file.

---

### 2. Backend Setup (Docker & Laravel API)

Open your terminal (or Docker Terminal) and navigate to the `admin-api` folder:

```bash
# Navigate to the API directory
cd admin-api

# Start the Docker containers in detached mode
docker compose up -d

# Install PHP dependencies inside the container
docker compose exec app composer install

# Run database migrations
docker compose exec app php artisan migrate
