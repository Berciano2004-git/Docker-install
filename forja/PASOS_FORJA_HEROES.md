# Forja de Héroes: pasos del proyecto

**Alumno/a:** ____________________________________  
**Curso:** 2.º DAW A  
**Módulo:** 0613 Desarrollo Web en Entorno Servidor  
**Unidad:** UD2 · Inserción de código en páginas web  
**Fecha:** ____ / ____ / ______  

---

## 1. Objetivo

El objetivo es ejecutar una aplicación PHP que genere dinámicamente la ficha de un personaje de rol. Los datos se guardan en `inc/heroe.php` y cada petición recalcula la ficha desde cero.

La aplicación se ejecuta en un contenedor PHP independiente y se accede desde el navegador mediante el puerto `8002`.

---

## 2. Estructura final del proyecto

La carpeta principal utilizada fue:

```text
C:\Users\Usuario1\OneDrive - Davante Education\Escritorio\docker
```

Estructura relevante:

```text
docker/
├── docker-compose.yml
├── inicio/
│   └── index.html                  # Página retro BERCIANO TECH
├── html/                           # Archivos del proyecto ARES
├── forja/
│   ├── index.php                   # Entrada principal
│   ├── ficha.php                   # Ficha dinámica del héroe
│   ├── diagnostico.php             # Diagnóstico del servidor
│   ├── css/
│   │   └── estilo.css
│   └── inc/
│       └── heroe.php               # Constantes y datos del personaje
└── backups/
    └── forja-before-kit/           # Copia previa de seguridad
```

> **Captura 1 — Estructura de carpetas**
>
> Pega aquí una captura del Explorador de archivos mostrando `docker`, `forja`, `inc` y `css`.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 3. Contenedores Docker utilizados

Se mantuvieron varios servicios independientes:

| Servicio | Contenedor | Puerto local | Carpeta o función |
|---|---|---:|---|
| Página retro | `php_web_1` | `8000` | `./inicio` |
| ARES | `php_web_2` | `8001` | `./html` |
| Forja de Héroes | `php_web_3` | `8002` | `./forja` |
| MySQL | `mysql_db` | `3306` | Base de datos |
| phpMyAdmin | `phpmyadmin_admin` | `8080` | Administración de MySQL |

La configuración utilizada para la forja fue:

```yaml
  php3:
    image: php:8.4-apache
    ports:
      - "8002:80"
    container_name: php_web_3
    restart: unless-stopped
    volumes:
      - ./forja:/var/www/html
    networks:
      - docker_app_network
```

Todos los servicios comparten la red `docker_app_network`.

### Comandos utilizados

Desde PowerShell:

```powershell
cd "C:\Users\Usuario1\OneDrive - Davante Education\Escritorio\docker"
```

Levantar los servicios:

```powershell
docker compose up -d
```

Detenerlos:

```powershell
docker compose down
```

Consultar los contenedores:

```powershell
docker ps
```

Levantar únicamente la forja:

```powershell
docker compose up -d php3
```

> **Captura 2 — Contenedores activos**
>
> Ejecuta `docker ps` y captura los contenedores `php_web_1`, `php_web_2`, `php_web_3`, `mysql_db` y `phpmyadmin_admin`.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 4. Accesos desde el navegador

| Aplicación | URL |
|---|---|
| BERCIANO TECH | `http://localhost:8000/` |
| ARES | `http://localhost:8001/` |
| Forja de Héroes | `http://localhost:8002/` |
| Ficha directa | `http://localhost:8002/ficha.php` |
| Diagnóstico | `http://localhost:8002/diagnostico.php` |
| phpMyAdmin | `http://localhost:8080/` |

En `inicio/index.html` se añadieron botones independientes:

- **ARES GUIADO** abre `http://localhost:8001/`.
- **FORJA DE HÉROES** abre `http://localhost:8002/`.
- **PHPMYADMIN** abre `http://localhost:8080/`.

> **Captura 3 — Página retro BERCIANO TECH**
>
> Captura la página `http://localhost:8000/` mostrando los botones.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 5. Instalación del kit de Forja de Héroes

El kit se localizó en Descargas con el nombre:

```text
UD2_Reto_Kit_alumno.zip
```

El contenido importante del ZIP era:

```text
forja-heroes/
├── MEMORIA.md
├── capturas/
├── php/
│   └── forja.ini
└── src/
    ├── diagnostico.php
    ├── ficha.php
    ├── css/
    │   └── estilo.css
    └── inc/
        └── heroe.php
```

Se copiaron los archivos de `src` a la carpeta local `docker/forja`, porque esa carpeta está montada dentro de `php_web_3` como `/var/www/html`.

También se creó una copia de seguridad en:

```text
docker\backups\forja-before-kit
```

El archivo `forja/index.php` se dejó como punto de entrada y carga `ficha.php`:

```php
<?php
require __DIR__ . '/ficha.php';
```

---

## 6. Datos de Bjorn Ragnarson

Los datos introducidos en `forja/inc/heroe.php` fueron:

| Campo | Valor |
|---|---|
| Nombre | Bjorn Ragnarson |
| Apodo | Piel de Hierro |
| Clase | Guerrero |
| Lema | El hierro se quiebra, yo no, te daré la bienvenida al Valhalla |
| Fuerza | 18 |
| Destreza | 17 |
| Inteligencia | 15 |
| Constitución | 20 |
| Experiencia actual | 1.225.000 XP |
| Vida actual | 500 HP |
| Oro | 600.000 monedas |
| Veterano | Sí |

Fragmento de configuración:

```php
const CLASE_HEROE = 'Guerrero';
const XP_POR_NIVEL = 400;
const VIDA_BASE = 50;
const MULT_VIDA = 3;
const BLOQUES_BARRA = 20;
const NOMBRE_RIVAL = 'Ogro de las Cavernas';
define('PODER_RIVAL', 200);

$nombreHeroe = 'Bjorn Ragnarson';
$apodo = 'Piel de Hierro';
$lema = 'El hierro se quiebra, yo no, te daré la bienvenida al Valhalla';
$fuerzaTxt = '18';
$destrezaTxt = '17';
$inteligenciaTxt = '15';
$constitucionTxt = '20';
$experienciaTxt = '1225000';
$vidaActualTxt = '500';
$oroTxt = '600000';
$esVeterano = true;
```

Las estadísticas se almacenan como texto y se convierten en `ficha.php` mediante casting explícito.

> **Captura 4 — Código de datos del héroe**
>
> Captura `forja/inc/heroe.php` mostrando las constantes y los datos de Bjorn.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 7. Cálculos realizados en `ficha.php`

La ficha carga los datos con:

```php
require_once __DIR__ . '/inc/heroe.php';
```

Después convierte los textos:

```php
$fuerza = (int) $fuerzaTxt;
$destreza = (int) $destrezaTxt;
$inteligencia = (int) $inteligenciaTxt;
$constitucion = (int) $constitucionTxt;
$experiencia = (int) $experienciaTxt;
$vidaActual = (int) $vidaActualTxt;
$oro = (float) $oroTxt;
```

### Progresión

```php
$nivel = intdiv($experiencia, XP_POR_NIVEL) + 1;
$xpEnNivel = $experiencia % XP_POR_NIVEL;
$xpParaSubir = XP_POR_NIVEL - $xpEnNivel;
```

### Vida y combate

```php
$vidaMax = VIDA_BASE + $constitucion * $nivel * MULT_VIDA;
$pctVida = $vidaActual / $vidaMax * 100;
$danio = $fuerza ** 2 / 10 + $nivel * 2;
$estadisticaEspecial = ($fuerza % 5) * 3;
$poder = (int) round($danio * $nivel);
```

### Operadores utilizados

- Casting explícito: `(int)` y `(float)`.
- División entera: `intdiv()`.
- Resto: `%`.
- Potencia: `**`.
- Comparación espacial: `<=>`.
- Ternario: `? :`.
- Coalescencia: `??`.
- Concatenación acumulativa: `.=`.
- Repetición de caracteres: `str_repeat()`.
- Conversión segura a HTML: `htmlspecialchars()`.
- Formato numérico: `number_format()`.
- Texto multilínea: heredoc.

> **Captura 5 — Ficha generada**
>
> Abre `http://localhost:8002/` y captura la ficha completa.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 8. Comprobaciones realizadas

Se comprobó la sintaxis de los archivos PHP desde el contenedor:

```powershell
docker exec php_web_3 php -l /var/www/html/inc/heroe.php
docker exec php_web_3 php -l /var/www/html/ficha.php
```

El resultado esperado es:

```text
No syntax errors detected
```

También se comprobó la respuesta HTTP:

```powershell
Invoke-WebRequest -UseBasicParsing http://localhost:8002/
```

El resultado esperado es una respuesta `HTTP 200`.

> **Captura 6 — Comprobación de sintaxis**
>
> Captura PowerShell mostrando los dos mensajes `No syntax errors detected`.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 9. Diagnóstico del servidor

El enlace de diagnóstico se encuentra al final de la ficha:

```text
http://localhost:8002/diagnostico.php
```

En esa página se deben mostrar las directivas de `php/forja.ini`, la versión de PHP, los archivos `.ini` cargados y las pruebas con `ini_set()` e `ini_restore()`.

> **Estado actual:** `diagnostico.php` todavía contiene marcas `TODO` del kit. Hay que completarlo antes de la entrega final.
>
> **Captura 7 — Diagnóstico del servidor**
>
> Captura `http://localhost:8002/diagnostico.php` cuando la página esté terminada.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 10. Directivas PHP

El archivo previsto para las directivas es:

```text
forja/php/forja.ini
```

Debe contener al menos seis directivas comentadas. Son obligatorias:

```ini
display_errors
er ror_reporting
date.timezone
short_open_tag
expose_php
```

> Corrige la escritura de `error_reporting` si aparece con un espacio accidental y añade una sexta directiva.

Ejemplo de sexta directiva:

```ini
memory_limit = 128M
```

Cada directiva debe incluir un comentario que explique qué modifica y por qué se utiliza.

> **Captura 8 — Archivo `forja.ini`**
>
> Captura el archivo mostrando las seis directivas y sus comentarios.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 11. Resultado final esperado

La navegación final debe ser:

```text
http://localhost:8000/       → BERCIANO TECH
http://localhost:8001/       → ARES
http://localhost:8002/       → Forja de Héroes
http://localhost:8080/       → phpMyAdmin
```

La página retro debe permitir acceder a ARES y a la Forja de Héroes mediante botones separados.

> **Captura 9 — Navegación completa**
>
> Incluye una captura de cada URL o una composición con las cuatro páginas abiertas.
>
> **Espacio para captura:**
>
> ```text
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> [                                                                                ]
> ```

---

## 12. Lista de comprobación antes de entregar

- [ ] `docker compose down` funciona.
- [ ] `docker compose up -d` levanta todos los servicios.
- [ ] `php_web_3` aparece activo en `docker ps`.
- [ ] `http://localhost:8002/` muestra la ficha.
- [ ] `ficha.php` no muestra errores ni avisos.
- [ ] `diagnostico.php` está completo y sin `TODO`.
- [ ] `forja.ini` contiene seis directivas comentadas.
- [ ] `inc/heroe.php` contiene `declare(strict_types=1);` como primera instrucción.
- [ ] Las estadísticas llegan como texto y se convierten mediante casting.
- [ ] No se utilizan `if`, `else`, bucles, arrays propios ni funciones propias.
- [ ] Se han realizado las capturas solicitadas.
- [ ] `MEMORIA.md` está completada.
- [ ] La carpeta `forja-heroes` se comprime con el nombre solicitado.

---

## 13. Observaciones personales

________________________________________________________________________________

________________________________________________________________________________

________________________________________________________________________________

________________________________________________________________________________

________________________________________________________________________________
