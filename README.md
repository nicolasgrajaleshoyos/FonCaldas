# FONCALDAS - Portal de Autoservicio y Panel Administrativo

## Acerca del proyecto

Este proyecto corresponde al desarrollo del **portal de autoservicio para asociados** y el **panel administrativo** de FONCALDAS (Fondo de Empleados de la Universidad de Caldas), en el marco de la práctica profesional en el área de Tecnologías de la Información.

El sistema busca centralizar y digitalizar la gestión de:

- Trámites y solicitudes de los asociados
- Emisión de certificaciones
- Gestión documental
- Autenticación de usuarios
- Trazabilidad de procesos
- Notificaciones

## Stack tecnológico

- **Backend:** Laravel (PHP)
- **Base de datos:** MySQL
- **Frontend:** JavaScript

## Información de la práctica

| Dato | Detalle |
|---|---|
| Entidad | FONCALDAS (Fondo de Empleados U. de Caldas) |
| Área | Tecnologías de la Información |
| Duración | 3 de agosto – 3 de diciembre de 2026 |
| Profesor asesor | Marcelo Herrera |
| Responsable en FONCALDAS | Germán Darío Correa Galvis (Representante Legal) |

## Instalación

```bash
git clone <url-del-repositorio>
cd foncaldas
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
```

## Levantar el servidor local

```bash
php artisan serve
```

## Estructura general

- `app/` — Lógica de negocio (controladores, modelos, servicios)
- `routes/` — Rutas web y API
- `resources/` — Vistas y assets del frontend
- `database/migrations/` — Migraciones de la base de datos

## Licencia

Uso interno para FONCALDAS.
