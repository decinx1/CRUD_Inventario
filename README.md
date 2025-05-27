# CRUD_Inventario
Proyecto universitario CRUD (Crear, Leer, Actualizar, Eliminar) para la gestión de inventario de equipos de cómputo. Desarrollado como actividad académica para Desarrollo Web en la Universidad de Colima 


Estrucutra actual: 
C:.
│   .gitattributes
│   .gitignore
│   package-lock.json
│   package.json
│   README.md
│   
├───app
│   │   app.php
│   │   config.php
│   │
│   ├───classes
│   │       Autoloader.php
│   │       DB.php
│   │       Redirect.php
│   │       Router.php
│   │       Views.php
│   │
│   ├───controllers
│   │   │   Controller.php
│   │   │   EquiposController.php
│   │   │   HomeController.php
│   │   │
│   │   └───auth
│   │           SessionController.php
│   │
│   ├───models
│   │       equipo.php
│   │       Model.php
│   │       personal.php
│   │
│   ├───public
│   │   │   .htaccess
│   │   │   index.php
│   │   │
│   │   └───assets
│   │       ├───css
│   │       │       bootstrap.min.css
│   │       │
│   │       └───js
│   │               app.js
│   │               bootstrap.bundle.js
│   │
│   └───resources
│       ├───functions
│       │       main_functions.php
│       │
│       ├───layouts
│       │       main_foot.php
│       │       main_head.php
│       │
│       └───views
│           │   home.view.php
│           │
│           ├───auth
│           │       inisession.view.php
│           │
│           ├───equipos
│           │       form.view.php
│           │       index.view.php
│           │
│           └───personal
│                   form.view.php
│                   index.view.php
