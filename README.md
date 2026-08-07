# Sistema Gestor de Becas UPTex 🎓

Plataforma web centralizada para la Universidad Politécnica de Texcoco (UPTex) diseñada para gestionar y agilizar el trámite de becas de descuento institucional. Permite la evaluación digital de solicitudes, carga de expedientes y administración de usuarios basado en roles.

## 🚀 Tecnologías

* **Backend:** Laravel 11 (PHP 8.2)
* **Frontend:** Vue 3 (Composition API) + Vite
* **Estilos:** Tailwind CSS
* **Base de Datos:** MySQL
* **Infraestructura:** Docker & Docker Compose
* **Autenticación:** Laravel Sanctum + 2FA (Google Authenticator)
* **Librerías Adicionales:** 
  * `maatwebsite/excel` (Exportación de reportes a .xls)
  * `simplesoftwareio/simple-qrcode` (Generación de códigos QR para 2FA)

## ✨ Características Principales

1. **Roles y Permisos:** 
   * **Jefe de Carrera (Edurnet):** Gestión de profesores (CRUD completo), evaluación de solicitudes, asignación de porcentajes (25%, 50%, 75%, 100%) y exportación de reportes en Excel.
   * **Profesor:** Módulo de revisión de alumnos por grupo.
   * **Alumno:** Creación de solicitudes, justificación de motivos y carga de expedientes (Foto de perfil y Kardex en PDF - Máx. 5MB).
2. **Seguridad Robusta:** Autenticación de doble factor (2FA) integrada.
3. **Independencia de Librerías de Prueba:** Seeders programados en PHP puro para generar 30 alumnos de prueba sin depender de librerías externas (Faker).

---

## ⚙️ Requisitos Previos

* Docker y Docker Compose instalados.
* Node.js y NPM (opcional, para compilar fuera de Docker si se prefiere).

## 🛠️ Instalación y Configuración

Sigue estos pasos para levantar el entorno desde cero usando los contenedores de Docker.

**1. Clonar y preparar entorno**
```bash
# Copiar el archivo de variables de entorno
cp .env.example .env