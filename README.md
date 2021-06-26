# Dashboard-codeigniter
Panel de administración basico usando codeigniter 3 y el tema adminlte3.

# Introducción

- Este es un panel de administración basado en codeigniter que nos permite administrar usuarios y gestionar permisos de los modulo de manera independiente por cada usuario.

# Instalación
```
  # clonar el repositorio
  $ git clone https://github.com/alf1995/dashboard-codeigniter.git
  
  # acceder al directorio de nuestro proyecto
  $ cd dashboard-codeigniter
  
  # instalar dependencias
  $ composer install
```
# Configuración

- En nuestro archivo **.env** realizaremos nuestra configuracion inicial

```
    #DATABASE

    DB_DATABASE=ciruka
    DB_USERNAME=root
    DB_PASSWORD=

    #CONFIGURATION

    CF_LOG=0
    CF_KEY=DEVELOPER-PERU@2021
    CF_HOOKS=TRUE

    #ADMIN CONFIGURATION

    WEB_DIRECTORY=web
    SYSTEM_DIRECTORY=dashboard
    SYSTEM_TITLE='Sistema Administración'
    SYSTEM_ERROR=6
    SYSTEM_BLOCK=2
    SYSTEM_FOOTER=Developer-peru.com
    SYSTEM_WEB=https://www.developer-peru.com
    SYSTEM_VERSION=1.0.1

    #MAIL CONFIGURATION

    MAIL_NAME=email@xyz.com
    MAIL_PASSWORD=password_here
    MAIL_HOST=SMTP_SETTINGS
    MAIL_FROM_EMAIL=email@xyz.com
    MAIL_FROM_NAME=WebOmnizz
    MAIL_PORT=465
    MAIL_ENCRYPTION=ssl

```
- Luego generamos de configurar nuestro archivo env exportamos la base de datos que se encuentra en el proyecto:

# Características

- Para acceder al panel de administración ingresaremos la url **/dashboard** :
```
Usuario:

  Admin:
   - Usuario: admin@gmail.com
   - Contraseña: admin123
    
```

- Módulo de usuario:

![Imagen de modulo usuario](https://i.imgur.com/xCsCLUB.png)

- Perfil de usuario:

![Imagen perfil de usuario](https://i.imgur.com/Jjf2yBL.png?1)

- Módulo de permisos:

![Imagen de modulo permisos](https://i.imgur.com/BNW9X2V.png)

- Opcion para que el usuario pueda recuperar su contraseña mediante correo:

![Imagen de modulo permisos](https://i.imgur.com/Q2WeTLr.png)

- Correo de recuperacion de cuenta:

![Imagen de modulo permisos](https://i.imgur.com/QkMPaR4.png)

