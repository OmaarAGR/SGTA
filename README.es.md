# 🚗 Sistema de Gestión de Talleres Automotrices

Este sistema web está diseñado para ayudar a los talleres automotrices pequeños a gestionar de manera eficiente sus clientes, vehículos, citas, inventarios y registros de servicio. La aplicación está desarrollada con **Laravel** (backend), **Vue.js** (frontend) y **MariaDB** (base de datos).

---

## 📦 Funcionalidades

- 🔐 **Autenticación de usuarios** con control de acceso por roles (Administrador, Recepcionista, Técnico, Cliente)
- 👥 **Gestión de clientes y vehículos**
- 📅 **Programación de citas** con asociación al usuario correspondiente
- 🧰 **Control de inventario** con alertas automáticas de bajo stock
- 🧾 **Registros de servicios** para seguimiento de reparaciones
- 📊 **Generación de reportes** (exportación en CSV, PDF)
- 🧱 Código modular y escalable
- 🌐 Interfaz responsiva y fácil de usar

---

## 🛠️ Tecnologías Utilizadas

| Capa         | Tecnología             |
|--------------|------------------------|
| Backend      | Laravel (PHP 8+)       |
| Frontend     | Vue.js 3 + Tailwind CSS|
| Base de datos| MariaDB                |
| Autenticación| Laravel Breeze / Sanctum |
| UI           | [Flowbite](https://flowbite.com/) |

---

## 🚀 Instalación

1. **Clona el repositorio**

   ```bash
   git clone https://github.com/tuusuario/inventory-system.git
   cd inventory-system
   ```

2. **Instala las dependencias**

   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configura el entorno**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Crea y configura la base de datos**

   - Crea una base de datos MariaDB
   - Actualiza el archivo `.env` con tus credenciales

   ```dotenv
   DB_DATABASE=db
   DB_USERNAME=root
   DB_PASSWORD=secreto
   ```

5. **Ejecuta migraciones y seeders**

   ```bash
   php artisan migrate --seed
   ```

6. **Inicia el servidor de desarrollo**

   ```bash
   php artisan serve
   ```

---

## 📋 Datos de prueba

El seeder por defecto crea:

- 10 Usuarios (uno por cliente)
- 10 Clientes
- 20 Vehículos (2 por cliente)
- 80 Citas (4 por vehículo)
- 160 Registros de servicio (2 por cita)
- 160 Técnicos (1 por registro)

Cada técnico tiene su propio usuario asignado.

---

## 🧪 Pruebas

Próximamente: Pruebas unitarias y funcionales de los componentes principales.

---

## 🧩 Estructura del Proyecto

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
│       └── Pages/     # Vistas Inertia
├── routes/
│   └── web.php
├── public/
├── .env
└── README.es.md
```

---

## 🌐 Capturas de Pantalla

Próximamente: Capturas del panel de control, formularios y reportes.

---

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT.

---

## 👩‍💻 Autores

- Danna Giselle Aguilar García
- Joseph Hans Díaz González
- Omar Andrés Gutiérrez Rojas
- Santiago Gutiérrez Henao
- Sheila Nayeli Torres Berrio

Proyecto para **Planificación del Alcance en Proyectos de Software** - Marzo 2025

---

## 📬 Contacto

Para dudas o sugerencias, no dudes en abrir un issue o ponerte en contacto con los autores.
