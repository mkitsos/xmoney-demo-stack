# xMoney Demo Stack (WordPress + WooCommerce, PrestaShop, OpenCart) — Render Ready

This repo lets you run **WordPress (install WooCommerce via WP Admin)**, **PrestaShop**, and **OpenCart** locally with Docker,
and deploy the same setup to **Render** via `render.yaml`.

## What you get
- 1x **MariaDB** (shared) with **three databases**: `wordpress`, `prestashop`, `opencart`
- **WordPress 6.9** with automatic WooCommerce installation and sample products
- Persistent volumes locally, and persistent disks on Render
- PrestaShop and OpenCart are currently disabled (can be enabled by uncommenting in config files)

---

## Local setup (macOS)

### Prereqs
- Docker Desktop installed and running

### Start the stack
```bash
docker compose up --build
```

### URLs
- WordPress: http://localhost:8080

### Notes
- Databases are auto-created on first run via `db-init/01-create-dbs.sql`
- WordPress 6.9 is pre-installed
- After completing WordPress setup wizard, install WooCommerce and sample products:
  ```bash
  # Find your WordPress container name
  docker ps

  # Run initialization script (replace 'xmoney-demo-stack-wp-1' with your container name)
  docker exec -it xmoney-demo-stack-wp-1 /var/www/html/init-wordpress.sh
  ```
  
  This will:
  - Install and activate WooCommerce
  - Add 6 sample products (headphones, t-shirt, water bottle, fitness watch, coffee beans, laptop bag)

---

## Deploy to Render

### Steps
1. Push this repo to GitHub.
2. In Render: **New → Blueprint** and select your repo.
3. Render will create:
   - `demo-db` (private MariaDB) + disk
   - `wp-web`, `ps-web`, `oc-web` (web services) + disks

### After deploy
- Open WordPress URL and complete the setup wizard
- Then SSH into your WordPress service and run:
  ```bash
  /var/www/html/init-wordpress.sh
  ```
  This will install WooCommerce and add sample products automatically.

---

## Files overview
- `docker-compose.yml` — local dev
- `render.yaml` — Render Blueprint
- `db.Dockerfile` — MariaDB image for Render
- `db-init/01-create-dbs.sql` — auto-create databases (local)
- `wp/`, `prestashop/`, `opencart/` — Docker images per app

---

## Optional upgrades (if you want)
- Auto-install WooCommerce via WP-CLI
- Add phpMyAdmin (private service)
- Separate DB users per app (instead of root)
