# 🚗 Simplified Inventory Management System for Small Automotive Workshops

This is a web-based system designed to help small automotive workshops efficiently manage their clients, vehicles, appointments, inventory, and service records. The application is built with **Laravel** (backend), **Vue.js** (frontend), and **MariaDB** (database).

---

## 📦 Features

- 🔐 **User Authentication** with role-based access (Admin, Receptionist, Technician, Client)
- 👥 **Client and Vehicle Management**
- 📅 **Appointment Scheduling** with user linkage
- 🧰 **Inventory Control** with automatic low-stock alerts
- 🧾 **Service Records** for appointment tracking
- 📊 **Report Generation** (CSV, PDF exports)
- 🧱 Modular and scalable codebase
- 🌐 Responsive and user-friendly interface

---

## 🛠️ Tech Stack

| Layer         | Tech                  |
|--------------|-----------------------|
| Backend       | Laravel (PHP 8+)       |
| Frontend      | Vue.js 3 + Tailwind CSS |
| Database      | MariaDB                |
| Authentication| Laravel Breeze / Sanctum |
| UI Components | [Flowbite](https://flowbite.com/) |

---

## 🚀 Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/yourusername/inventory-system.git
    cd inventory-system
    ```

1. **Install dependencies**

   ```bash
   composer install
   npm install && npm run build
   ```

1. **Configure environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

1. **Set up the database**

   - Create a MariaDB database
   - Update `.env` with your DB credentials

   ```dotenv
   DB_DATABASE=inventory_db
   DB_USERNAME=root
   DB_PASSWORD=secret
   ```

1. **Run migrations and seeders**

   ```bash
   php artisan migrate --seed
   ```

1. **Start the development server**

   ```bash
   php artisan serve
   ```

---

## 📋 Seeding Overview

The default database seeder creates:

- 10 Users (one for each client)
- 10 Clients
- 20 Vehicles (2 per client)
- 80 Appointments (4 per vehicle)
- 160 Service Records (2 per appointment)
- 160 Technicians (1 per service record)

Each technician also has an associated user account.

---

## 🧪 Testing

Coming soon: Unit and Feature tests for core components.

---

## 🧩 Project Structure

```text
├── app/
│   ├── Models/
│   ├── Http/Controllers/
│   ├── Policies/
│   └── Providers/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── js/
│       └── Pages/     # Inertia views
├── routes/
│   └── web.php
├── public/
├── .env
└── README.md
```

---

## 🌐 Screenshots

Coming soon: Screenshots of the dashboard, forms, and reports.

---

## 📄 License

This project is licensed under the MIT License.

---

## 👩‍💻 Authors

- Danna Giselle Aguilar García
- Joseph Hans Díaz González
- Omar Andrés Gutiérrez Rojas
- Santiago Gutiérrez Henao
- Sheila Nayeli Torres Berrio

Project for **Software Project Scope Planning** - March 2025

---

## 📬 Contact

For questions or feedback, feel free to reach out or open an issue.
