# CRUD_Inventario

# Inventa Fácil - CRUD de Gestión de Inventario

## 1. Descripción del Proyecto y Objetivo

**Inventa Fácil** es una aplicación web desarrollada como un proyecto universitario para la materia de Desarrollo Web en la Universidad de Colima (Facultad de Ingeniería Electromecánica, Ingeniería de Software)[cite: 1, 5]. Su objetivo principal es proporcionar una herramienta funcional para la **gestión del inventario de equipos de cómputo**[cite: 5], permitiendo llevar un control eficiente de los activos tecnológicos.

La aplicación implementa operaciones CRUD (Crear, Leer, Actualizar, Eliminar) para administrar tanto los equipos de cómputo como el personal al que pueden ser asignados[cite: 7]. Esto facilita al administrador de una sucursal o empresa llevar un control detallado, optimizar la asignación de recursos, planificar mantenimientos o reemplazos y ayudar a disminuir pérdidas[cite: 7, 15].

Este proyecto demuestra la aplicación práctica de un framework PHP MVC personalizado, desarrollado como parte de ejercicios previos en la materia[cite: 6, 8].

## 2. Instrucciones de Instalación y Ejecución

### Prerrequisitos

* **XAMPP** (o cualquier otro servidor web local compatible con Apache, MySQL y PHP).
    * PHP 8.0 o superior[cite: 33].
    * Servidor de base de datos MySQL o MariaDB (el que viene con XAMPP es MariaDB)[cite: 34].
    * Apache Web Server.
* Un navegador web moderno (Chrome, Firefox, Edge, etc.).
* Git (opcional, para clonar el repositorio).

### Pasos para la Instalación Local

1.  **Clonar el Repositorio (Opcional):**
    Si tienes el proyecto en un repositorio Git:
    ```bash
    git clone https://github.com/decinx1/CRUD_Inventario.git CRUD_Inventario
    ```
    Si no, simplemente descarga o copia los archivos del proyecto en tu máquina.

2.  **Mover Archivos del Proyecto:**
    * Copia toda la carpeta del proyecto (ej. `CRUD_Inventario`) a tu directorio `htdocs` de XAMPP. La estructura final dentro de `htdocs` debería ser algo como:
        `C:\xampp\htdocs\CRUD_Inventario\`
        (Esta carpeta `CRUD_Inventario` contendrá tu archivo `app.php`, `config.php` y las subcarpetas `app/`, etc., según la estructura que me mostraste más recientemente).

3.  **Configurar la Base de Datos:**
    * Inicia los módulos de **Apache** y **MySQL** desde el panel de control de XAMPP.
    * Abre phpMyAdmin en tu navegador (usualmente `http://localhost/phpmyadmin`).
    * Crea una nueva base de datos llamada `inventafacil` (todo en minúsculas). Asegúrate de usar el cotejamiento `utf8mb4_general_ci`.
    * Selecciona la base de datos `inventafacil` y ve a la pestaña "SQL".
    * Ejecuta los scripts SQL para crear las tablas: `personal`, `usuarios`, y `equipos`. (Puedes obtener estos scripts de los que te proporcioné anteriormente o de tu script SQL si ya lo tienes listo [cite: 24]).

4.  **Configurar el Framework (`app/config.php`):**
    * Abre el archivo `CRUD_Inventario/app/config.php` en un editor de texto.
    * Verifica las siguientes constantes:
        * `DB_HOST`: Debería ser `'localhost'` o `'127.0.0.1'`.
        * `DB_USER`: `'root'` (usuario por defecto de XAMPP).
        * `DB_PASS`: `''` (contraseña vacía por defecto para `root` en XAMPP).
        * `DB_NAME`: Asegúrate de que sea `'inventafacil'`.
        * `URL`: Esta debe ser la URL base que apunta a tu carpeta `public/`. Si vas a configurar un Virtual Host (recomendado), ajústala. Si no, y accedes vía `http://localhost/CRUD_Inventario/app/public/`, entonces `URL` debería ser eso.
    * Asegúrate de que `ROOT` y las rutas a `CLASSES`, `CONTROLLERS`, `MODELS`, `RESOURCES`, `PUBLIC_PATH`, `VIEWS`, `LAYOUTS`, `FUNCTIONS`, `ASSETS_URL`, `CSS_URL`, `JS_URL` estén correctamente definidas según la estructura de tu proyecto (donde `config.php` y `app.php` están en `CRUD_Inventario/app/`).

5.  **Configurar el Servidor Web (Apache):**

    * **Opción A: Virtual Host (Recomendado)**
        1.  Edita `C:\xampp\apache\conf\extra\httpd-vhosts.conf`. Añade:
            ```apache
            <VirtualHost *:80>
                DocumentRoot "C:/xampp/htdocs/CRUD_Inventario/app/public"
                ServerName inventafacil.local # O el nombre que prefieras
                <Directory "C:/xampp/htdocs/CRUD_Inventario/app/public">
                    Options Indexes FollowSymLinks
                    AllowOverride All
                    Require all granted
                    DirectoryIndex index.php
                </Directory>
                ErrorLog "logs/inventafacil.local-error.log"
                CustomLog "logs/inventafacil.local-access.log" common
            </VirtualHost>
            ```
        2.  Edita tu archivo `hosts` (`C:\Windows\System32\drivers\etc\hosts` como administrador) y añade:
            ```
            127.0.0.1 inventafacil.local
            ```
        3.  Asegúrate de que la línea `Include conf/extra/httpd-vhosts.conf` esté descomentada en `C:\xampp\apache\conf\httpd.conf`.
        4.  Reinicia Apache desde el panel de XAMPP.
        5.  Ahora podrás acceder al proyecto vía `http://inventafacil.local/`.

    * **Opción B: Acceso Directo (Sin Virtual Host)**
        * Si no configuras un Virtual Host, accederás al proyecto a través de una URL como `http://localhost/CRUD_Inventario/app/public/`.
        * En este caso, la constante `URL` en `app/config.php` debe reflejar esta ruta completa.
        * El `RewriteBase` en el archivo `.htaccess` también podría necesitar ajuste.

6.  **Archivo `.htaccess`:**
    * Asegúrate de que el archivo `.htaccess` exista en la carpeta `CRUD_Inventario/app/public/` con las reglas de reescritura correctas para dirigir todas las peticiones a `index.php`. Ejemplo:
        ```apache
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteBase / # Si usas Virtual Host y app/public es la raíz del ServerName.
                          # Si accedes por subcarpeta, ej. /CRUD_Inventario/app/public/, ajusta aquí.
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php?uri=$1 [L,QSA]
        </IfModule>
        ```

7.  **Verificar dependencias de JavaScript:**
    * Los archivos `bootstrap.min.css`, `app.js`, `bootstrap.bundle.js` (o `bootstrap.js` si es el bundle), `jquery.js`, y `sweetalert2.js` deben estar presentes en las carpetas `app/public/assets/css/` y `app/public/assets/js/` respectivamente. Si los descargaste con npm, asegúrate de copiar los archivos necesarios de `node_modules` a estas carpetas.

8.  **Ejecutar el Proyecto:**
    * Abre tu navegador y ve a la URL que configuraste (ej. `http://inventafacil.local/` o `http://localhost/CRUD_Inventario/app/public/`).
    * Deberías ver la página de inicio de "Inventa Fácil".

## 3. Estructura de Archivos (Explicada Brevemente)

La estructura del proyecto sigue un patrón similar a MVC (Modelo-Vista-Controlador) [cite: 21] para organizar el código:


app/
app.php
config.php
classes/
Autoloader.php
DB.php
Redirect.php
Router.php
Views.php
controllers/
Controller.php
EquiposController.php
HomeController.php
auth/
SessionController.php
models/
equipo.php
Model.php
personal.php
public/
.htaccess
index.php
assets/
css/
js/
app.js
bootstrap.bundle.js
resources/
functions/
main_functions.php
layouts/
main_foot.php
main_head.php
views/
home.view.php
equipos/
form.view.php
index.view.php
personal/
form.view.php
index.view.php
auth/
