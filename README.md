# ATMABISWAS Full-Stack System - Hostinger Deployment & Architecture Guide

A modern full-stack migration of the **ATMABISWAS NGO** platform built with **React (Vite), Express.js, Node.js, and MySQL (JWT Authentication & REST API)**.

---

## 🏗️ Project Architecture

```
atmabiswas_hostinger/
├── client/                     # React (Vite) Single Page Application
│   ├── src/
│   │   ├── api/               # Axios API Clients (authApi, dashboardApi, userApi)
│   │   ├── components/        # Reusable React UI Components (Navbar, StatsCard, Table)
│   │   ├── context/           # AuthContext (JWT State & LocalStorage Management)
│   │   ├── pages/             # React Pages (Login, ForgotPassword, ResetPassword, Dashboard, UserManagement, Profile, ChangePassword)
│   │   ├── App.jsx            # React Router Routes & Protected Guards
│   │   └── main.jsx
│   ├── dist/                  # Built Production SPA Output for Web Server Hosting
│   ├── package.json
│   └── vite.config.js
│
└── server/                     # Node.js + Express.js REST API Backend
    ├── src/
    │   ├── config/            # MySQL Connection Pool (mysql2/promise)
    │   ├── controllers/       # Controller Handlers (auth, dashboard, user)
    │   ├── middleware/        # JWT Authentication & Role Authorization Middleware
    │   ├── routes/            # Express Routes (/api/v1/auth, /api/v1/dashboard, /api/v1/users)
    │   ├── services/          # Prepared SQL Query Services
    │   ├── utils/             # Password Hashing (bcrypt) & Token Utility (JWT)
    │   └── app.js             # Express Server Initialization
    ├── package.json
    └── .env.example
```

---

## ⚙️ Environment Configuration

### Backend (`server/.env`)
```ini
PORT=5001
NODE_ENV=production

# MySQL Database Configuration
DB_HOST=localhost
DB_USER=u106340611_arafat
DB_PASS=MacBook@007Arafat
DB_NAME=u106340611_arafatbiswas
DB_PORT=3306

# JWT Configuration
JWT_SECRET=atmabiswas_super_secret_jwt_key_2026_bd
JWT_EXPIRES_IN=1d

# Frontend Client URL
CLIENT_URL=https://atmabiswas.org
```

---

## 🚀 Hostinger Deployment Steps

### 1. Database Setup
- Go to Hostinger **hPanel -> Databases -> MySQL Databases**.
- Import the base schema and migration scripts located in `Database/`.

### 2. Node.js Backend API Deployment
1. Go to Hostinger **hPanel -> Advanced -> Node.js**.
2. Create a new Node.js application:
   - **Application Root**: `server`
   - **Application Startup File**: `src/app.js`
   - **Node.js Version**: 18.x or 20.x
3. Upload the `server/` folder files (excluding `node_modules`).
4. Click **NPM Install** in hPanel or run `npm install` via SSH.
5. Set environment variables in hPanel or create `server/.env`.
6. Start the Node.js application.

### 3. React Frontend SPA Deployment
1. In your local development machine, run:
   ```bash
   cd client
   npm install
   npm run build
   ```
2. Upload the contents of `client/dist/` into Hostinger `public_html/`.
3. Add the following `.htaccess` inside `public_html/` for Client-side SPA routing & API reverse proxy:

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /

  # Proxy API requests to Node.js backend
  RewriteRule ^api/(.*)$ http://127.0.0.1:5001/api/$1 [P,L]

  # Direct all static React SPA routes to index.html
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^ index.html [L]
</IfModule>
```

---

## 🧪 Local Testing & Running

```bash
# 1. Start Node.js Express API (Port 5001)
cd server
npm run dev

# 2. Start React Development Server (Port 5173)
cd client
npm run dev
```

Visit `http://localhost:5173/login` in your browser.
