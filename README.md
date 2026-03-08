# Brujosa Tarot App (PHP + MySQL)

MVP para XAMPP con dos accesos:
- Cliente: `public/index.php`
- Administración Heidy: `public/admin_login.php`

## Instalación rápida (XAMPP)
1. Copiar proyecto a `htdocs/testmona`.
2. Crear base de datos ejecutando `sql/schema.sql` en phpMyAdmin.
3. Ajustar credenciales en `includes/config.php`.
4. Dar permisos de escritura a `uploads/audio`.
5. Abrir:
   - `http://localhost/testmona/public/index.php`
   - `http://localhost/testmona/public/admin_login.php`

## Flujo implementado
- Login admin y vista rápida cliente por correlativo.
- Panel con:
  - Lecturas realizadas (con botón ver).
  - Preguntas disponibles (listar, agregar, eliminar).
  - Registrar nueva lectura en 2 pasos:
    - Datos cliente + paquete.
    - Selección de hasta 10 cartas, texto de lectura, preguntas según paquete y carga de audios.
  - Contabilidad histórica + resumen por mes y semana.
- Vista cliente con bienvenida por género, cartas, lectura y preguntas/respuestas.

## Campos principales de la base de datos
- `readings`: correlativo, cliente, género, edad, paquete, precio, fecha, lectura.
- `reading_cards`: cartas seleccionadas por lectura.
- `reading_answers`: preguntas, respuesta escrita y audio opcional.
- Catálogos: `cards`, `questions`, `packages`.
