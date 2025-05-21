# CRUD_Inventario
Proyecto universitario CRUD (Crear, Leer, Actualizar, Eliminar) para la gestión de inventario de equipos de cómputo. Desarrollado como actividad académica para Desarrollo Web en la Universidad de Colima 


Estrucutra actual: 
C:.
│   app.php
│   config.php
│   README.md
│
└───app
    ├───classes
    │       Autoloader.php
    │       DB.php
    │
    ├───controllers
    │   │   Controller.php
    │   │
    │   └───auth
    │           SessionController.php
    │
    ├───models
    │       equipo.php
    │       Model.php
    │       personal.php
    │
    ├───public
    │   │   .htaccess
    │   │   index.php
    │   │
    │   └───assets
    │       ├───css
    │       │       bootstrap.css
    │       │
    │       └───js
    │               app.js
    │
    └───resources
        ├───functions
        │       main_functions.php
        │
        ├───layouts
        │       main_foot.php
        │       main_head.php
        │
        └───views
            ├───auth
            │       inisession.view.php
            │
            ├───equipos
            │       form.view.php
            │       index.view.php
            │
            └───personal
                    form.view.php
                    index.view.php