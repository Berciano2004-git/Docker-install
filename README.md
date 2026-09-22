# Docker-install
Aqui voy a documentar como la ia creó los contenedores necesarios en docker para clase

# Configuración de Contenedores Docker: MySQL, Apache y phpMyAdmin

Este proyecto configura tres contenedores Docker independientes que trabajan juntos: una base de datos MySQL, un servidor web Apache y phpMyAdmin para gestionar la base de datos.

## Requisitos Previos

- Docker Desktop instalado
- Docker Compose instalado (incluido en Docker Desktop)
- Puerto 3306, 8000 y 8080 disponibles en tu máquina

## Estructura del Proyecto

```
.
├── docker-compose.yml
├── html/
│   └── (aquí irán tus archivos web)
└── README.md
```

## Servicios

### MySQL (puerto 3306)
- **Imagen**: mysql:8.0
- **Contenedor**: mysql_db
- **Puerto**: 3306:3306
- **Usuario root**: root
- **Contraseña**: rootpassword
- **Base de datos inicial**: myapp
- **Volumen**: mysql_data (persistencia de datos)

### Apache (puerto 8000)
- **Imagen**: httpd:2.4
- **Contenedor**: apache_web
- **Puerto**: 8000:80
- **Directorio web**: ./html
- **Volumen**: ./html:/usr/local/apache2/htdocs

### phpMyAdmin (puerto 8080)
- **Imagen**: phpmyadmin:latest
- **Contenedor**: phpmyadmin_admin
- **Puerto**: 8080:80
- **Conexión**: Se conecta a MySQL automáticamente

## Inicio Rápido

### 1. Clonar o descargar el proyecto

```bash
cd tu-proyecto
```

### 2. Iniciar los contenedores

```bash
docker compose up -d
```

El flag `-d` ejecuta los contenedores en segundo plano.

### 3. Verificar que todo está funcionando

```bash
docker compose ps
```

Deberías ver los tres contenedores con estado `Up`.

## Acceso a los Servicios

### phpMyAdmin
- **URL**: http://localhost:8080
- **Usuario**: root
- **Contraseña**: rootpassword

### Apache
- **URL**: http://localhost:8000
- Coloca tus archivos HTML/PHP en la carpeta `./html`

### MySQL
- **Host**: mysql (desde otros contenedores) o localhost:3306 (desde tu máquina)
- **Puerto**: 3306
- **Usuario**: root
- **Contraseña**: rootpassword
- **Base de datos**: myapp

## Comandos Útiles

### Ver logs de un contenedor
```bash
docker compose logs mysql_db
docker compose logs apache_web
docker compose logs phpmyadmin_admin
```

### Acceder a un contenedor
```bash
# MySQL
docker exec -it mysql_db mysql -uroot -prootpassword

# Apache
docker exec -it apache_web bash

# phpMyAdmin
docker exec -it phpmyadmin_admin bash
```

### Detener los contenedores
```bash
docker compose down
```

### Detener y eliminar volúmenes (elimina datos de MySQL)
```bash
docker compose down -v
```

### Reiniciar los contenedores
```bash
docker compose restart
```

### Ver estado de salud
```bash
docker compose ps
```

## Configuración del docker-compose.yml

```yaml
services:
  mysql:
    image: mysql:8.0
    container_name: mysql_db
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: myapp
      MYSQL_USER: appuser
      MYSQL_PASSWORD: apppassword
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - app_network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

  apache:
    image: httpd:2.4
    container_name: apache_web
    ports:
      - "8000:80"
    volumes:
      - ./html:/usr/local/apache2/htdocs
    networks:
      - app_network
    depends_on:
      - mysql

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: phpmyadmin_admin
    environment:
      PMA_HOST: mysql
      PMA_USER: root
      PMA_PASSWORD: rootpassword
      PMA_PORT: 3306
    ports:
      - "8080:80"
    networks:
      - app_network
    depends_on:
      - mysql

volumes:
  mysql_data:

networks:
  app_network:
    driver: bridge
```

## Notas Importantes

- **Persistencia de datos**: Los datos de MySQL se guardan en el volumen `mysql_data`. Aunque elimines los contenedores, los datos persisten.
- **Red personalizada**: Los contenedores están conectados a través de la red `app_network` (tipo bridge), lo que les permite comunicarse entre sí.
- **Health checks**: MySQL incluye un health check que verifica si está disponible.
- **Dependencias**: Apache y phpMyAdmin dependen de MySQL, por lo que se inician después.

## Solución de Problemas

### Los contenedores no inician
```bash
docker compose logs
```

### Puerto ya está en uso
Cambiar los puertos en `docker-compose.yml`:
```yaml
ports:
  - "8001:80"  # Apache en 8001 en lugar de 8000
```

### No puedo conectar MySQL desde Apache
Usa el nombre del servicio `mysql` como host (no localhost) dentro de los contenedores.

### Eliminar todo y empezar de nuevo
```bash
docker compose down -v
docker system prune
```

## Variables de Entorno Personalizables

Puedes modificar las contraseñas y configuraciones en el `docker-compose.yml`:

- `MYSQL_ROOT_PASSWORD`: Contraseña del usuario root de MySQL
- `MYSQL_DATABASE`: Nombre de la base de datos inicial
- `MYSQL_USER`: Usuario adicional de MySQL
- `MYSQL_PASSWORD`: Contraseña del usuario adicional
- `PMA_USER`: Usuario para phpMyAdmin
- `PMA_PASSWORD`: Contraseña para phpMyAdmin

## Ejemplos de Uso

### Crear una tabla en MySQL desde phpMyAdmin
1. Accede a http://localhost:8080
2. Inicia sesión con root / rootpassword
3. Selecciona la base de datos "myapp"
4. Crea tus tablas

### Servir archivos PHP con Apache
1. Coloca un archivo `index.php` en la carpeta `./html`
2. Accede a http://localhost:8000/index.php

## Licencia

Este proyecto es de código abierto y libre de usar.

## Soporte

Para más información sobre Docker:
- [Documentación de Docker](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)

