---
title: FONCALDAS - Portal de Autoservicio
tags:
  - proyecto
  - activo
status: en-desarrollo
---

# FONCALDAS - Portal de Autoservicio

Fondo de Empleados de la Universidad de Caldas. El sitio existente (Laravel + Blade + Alpine.js + Tailwind) es informativo, con un panel admin simple para Documentos y Eventos. El proyecto en curso es una práctica profesional para agregarle un **Portal de Autoservicio** para asociados y ampliar el **Panel Administrativo**, según el documento de requerimientos entregado por [[Marcelo Herrera Gonzalez]] (docente) y elaborado por [[Nicolas Grajales Hoyos]].

## Alcance implementado

- **Solicitudes y certificaciones sin login** (`/tramites`): el asociado se identifica con documento + nombre en el formulario, sin crear cuenta. El funcionario verifica la identidad manualmente desde el panel.
- **Seguimiento público** (`/tramites/consultar`): el asociado consulta estado e historial con documento + código de solicitud.
- **Panel admin ampliado** con pestañas nuevas: Solicitudes, Auditoría, y (solo para el administrador principal) Usuarios y Tipos de trámite.
- **Roles con alcance por categoría**: un admin supremo (`is_super_admin`) ve y gestiona todo; puede crear funcionarios y asignarles qué tipos de trámite (ej. "Créditos") pueden ver. Un funcionario sin ese permiso recibe 403 al intentar ver solicitudes o módulos fuera de su alcance.
- **Notificaciones por correo**: al asociado cuando cambia el estado de su solicitud, al equipo correspondiente cuando llega una nueva.
- **Auditoría**: cada acción sobre una solicitud queda registrada (usuario, acción, fecha), consultable desde el panel.
- **Modo oscuro** funcionando en todo el panel admin (antes solo existía en las páginas públicas).

Ver decisiones técnicas y bugs encontrados en [[Arquitectura y decisiones tecnicas - FONCALDAS]].

## Pendiente / fuera de alcance

Ver [[Pendientes y decisiones por confirmar]].

## Bitácora

- [[2026-09-17]] — sesión de implementación del portal de autoservicio, corrección de modo oscuro, y organización de esta bóveda.
