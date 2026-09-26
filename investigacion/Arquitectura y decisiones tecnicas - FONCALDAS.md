---
title: Arquitectura y decisiones técnicas - FONCALDAS
tags:
  - investigacion
  - tecnico
---

# Arquitectura y decisiones técnicas - FONCALDAS

Notas técnicas del desarrollo de [[FONCALDAS - Portal de Autoservicio]].

## Stack

Laravel 13 + Blade + Alpine.js + Tailwind v4, sin framework de frontend tipo SPA. Base de datos SQLite en desarrollo. Auth nativa de Laravel (guard `web`), sin paquetes de terceros para roles.

## Decisiones deliberadas

- **Roles sin Spatie/permission**: en vez de añadir una dependencia, se extendió la tabla `users` con `is_super_admin` + `is_active`, y una tabla pivote `tramite_tipo_user` para el alcance por categoría. Suficiente para el caso de uso (un supremo + funcionarios por categoría), sin la complejidad de un sistema de permisos genérico.
- **Certificaciones como un tipo de trámite más**: en vez de un modelo aparte, `tramite_tipos.es_certificacion` marca la diferencia y reutiliza el mismo flujo de solicitud/aprobación/documento de respuesta.
- **Notificaciones síncronas por correo**: sin colas, usando el mailer configurado (`log` en dev). Si el volumen crece, es el punto a mover a `ShouldQueue`.

## Capas y responsabilidades (SOLID)

Flujo: `Route → Controller → FormRequest / Policy → Service → Model`.

- **Controllers** (`app/Http/Controllers`): solo HTTP (recibir, delegar, responder). Un controlador por recurso: `TransparenciaController`, `EventoController`, `Admin\DocumentoController`, `Admin\EventoController`, `Admin\DashboardController`, etc.
- **FormRequests** (`app/Http/Requests`): validación de los formularios grandes. Los de un solo campo siguen inline.
- **Policy** (`SolicitudPolicy::gestionar`): única fuente de la regla "un funcionario solo ve los tipos de trámite asignados". Se usa con `Gate::authorize`.
- **Services** (`app/Services`): reglas de negocio. `FlujoSolicitud` y `FlujoPqrs` (transiciones de estado), `RadicadorSolicitudes`, `Notificador*` (correo), `GestorEventos`/`GestorDocumentos`, `CategoriasTransparencia`.
- **Contrato** (`app/Contracts/AlmacenArchivos`): los servicios dependen de la interfaz, no de `Storage`; el binding está en `AppServiceProvider`.
- **Models**: solo datos, relaciones, scopes y etiquetas. Sin correo ni `auth()`. El código de radicación (`FC-`/`PQ-`) sale del trait `TieneCodigo`.

Regla para código nuevo: si un método de controlador hace más que validar → delegar → responder, esa lógica va a un servicio.

## Bugs encontrados y corregidos

> [!bug] Pluralización de Eloquent
> El modelo `Solicitud` sin `$table` explícito generaba la tabla `solicituds` (el pluralizador en inglés no sabe que "solicitud" → "solicitudes"). Se detectó probando el flujo completo end-to-end, no solo con `php artisan route:list`. Lección: en este proyecto, cualquier modelo con nombre en español necesita `protected $table` explícito — no confiar en la convención de Eloquent.

> [!bug] Symlink de storage faltante
> `public/storage` no estaba enlazado a `storage/app/public`, así que ni los archivos de eventos (ya existentes) ni los nuevos documentos de solicitudes se servían. Se corrigió con `php artisan storage:link`.

> [!bug] Contraste de texto en modo oscuro del panel admin
> El `<body>` del layout aplica `dark:text-slate-200` quando el modo oscuro está activo, pero las páginas de admin tenían fondo claro fijo (`bg-[#F5F7FA]`) sin adaptarse — el texto se heredaba pálido sobre fondo claro, casi invisible. Se corrigió dando a cada página de admin su propio color de texto y fondo con variantes `dark:`, en vez de depender de la herencia del `<body>`.

> [!tip] Contraste entre secciones oscuras
> El fondo oscuro del panel admin (`slate-900`, `#0F172A`) coincidía exactamente con el color del footer del sitio, así que se veían pegados sin transición. Se cambió el fondo del panel a `slate-950` (`#020617`), más oscuro que el footer, para que se note dónde termina uno y empieza el otro.

## Relacionado

[[FONCALDAS - Portal de Autoservicio]]
