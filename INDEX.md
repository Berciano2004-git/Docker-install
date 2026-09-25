# Guía de Exportación e Importación

## Exportar Proyecto

### Método 1: Clonar desde GitHub (Recomendado)

\\\ash
git clone https://github.com/Berciano2004-git/Docker-install.git
cd Docker-install
docker compose up -d
Start-Sleep -Seconds 40
docker exec -i mysql_db mysql -uroot -prootpassword < backups/backups_complete.sql
\\\

### Método 2: Descargar ZIP

1. Ve a https://github.com/Berciano2004-git/Docker-install
2. Click Code ? Download ZIP
3. Descomprimir y ejecutar los mismos comandos

## Importar en Otra Máquina

\\\ash
# 1. Clonar
git clone https://github.com/Berciano2004-git/Docker-install.git

# 2. Iniciar servicios
docker compose up -d

# 3. Esperar a que MySQL esté listo
Start-Sleep -Seconds 40

# 4. Restaurar base de datos
docker exec -i mysql_db mysql -uroot -prootpassword < backups/backups_complete.sql

# 5. Verificar
docker compose ps
\\\

## Acceder a los Servicios

- MySQL: localhost:3306 (usuario: root, contraseña: rootpassword)
- Apache: http://localhost:8000
- phpMyAdmin: http://localhost:8080

## Cambiar Credenciales (Seguridad)

Edita .env.example, copia a .env y cambia los valores, luego reconstruye:

\\\ash
docker compose down -v
docker compose up -d
\\\
"@ | Set-Content "C:\Users\Usuario1\OneDrive - Davante Education\Escritorio\docker\EXPORT_GUIDE.md"

# Crear INDEX.md
@"
# Documentación del Proyecto

## Estructura

- **README.md** - Documentación principal
- **EXPORT_GUIDE.md** - Guía de exportación
- **docker-compose.yml** - Configuración
- **.env.example** - Variables de entorno
- **.gitignore** - Archivos a ignorar
- **backups/backups_complete.sql** - Backup completo de la BD

## Inicio Rápido

\\\ash
git clone https://github.com/Berciano2004-git/Docker-install.git
cd Docker-install
docker compose up -d
\\\

## Servicios

- **MySQL 8.0** - Puerto 3306
- **Apache 2.4** - Puerto 8000
- **phpMyAdmin** - Puerto 8080

Para más información, consulta EXPORT_GUIDE.md
