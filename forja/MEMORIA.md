# MEMORIA · Forja de Héroes

**Módulo:** 0613 Desarrollo Web en Entorno Servidor  
**Curso:** 2.º DAW A  
**Unidad:** UD2 · Inserción de código en páginas web  
**Actividad:** Reto evaluable · Forja de Héroes  
**Alumno/a:** ____________________________________  
**Fecha:** ____ / ____ / 2026  

## 1. Objetivo de la actividad

El objetivo de la actividad es crear una ficha de personaje generada en el servidor con PHP. Los datos iniciales del héroe se encuentran en `inc/heroe.php`, mientras que `ficha.php` convierte esos datos, realiza los cálculos y genera la página HTML.

El proyecto se ejecuta dentro de Docker mediante un servicio PHP independiente. La aplicación Forja de Héroes está publicada en el puerto local `8002`.

La ficha utilizada es la de **Bjorn Ragnarson**, apodado **Piel de Hierro**, de clase **Guerrero**.

## 2. Estructura y despliegue

El proyecto se organiza así:

```text
docker/
├── docker-compose.yml
├── inicio/
│   └── index.html
├── html/
│   ├── index.php
│   └── casos guiados de ARES
└── forja/
    ├── index.php
    ├── ficha.php
    ├── diagnostico.php
    ├── css/
    │   └── estilo.css
    ├── inc/
    │   └── heroe.php
    └── capturas/
```

El servicio de la forja en `docker-compose.yml` utiliza la imagen `php:8.4-apache`, publica el puerto `8002` y monta la carpeta local `./forja` dentro de `/var/www/html`.

```yaml
php3:
  image: php:8.4-apache
  ports:
    - "8002:80"
  container_name: php_web_3
  volumes:
    - ./forja:/var/www/html
```

Los comandos principales utilizados fueron:

```powershell
cd "C:\Users\Usuario1\OneDrive - Davante Education\Escritorio\docker"
docker compose up -d
docker ps
docker compose down
```

El acceso a la aplicación es:

```text
http://localhost:8002/
```

## 3. Datos del personaje

Los datos de Bjorn se escribieron en `forja/inc/heroe.php`.

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
| Experiencia | 1.225.000 XP |
| Vida actual | 500 HP |
| Oro | 600.000 monedas |
| Veterano | Sí |

Las estadísticas numéricas se mantienen como texto en el fichero de datos, porque simulan valores recibidos desde un formulario. Posteriormente se convierten en `ficha.php` mediante casting explícito.

## 4. Inclusión de código PHP

Los ficheros PHP comienzan con:

```php
declare(strict_types=1);
```

`ficha.php` carga los datos mediante una ruta segura basada en `__DIR__`:

```php
require_once __DIR__ . '/inc/heroe.php';
```

`inc/heroe.php` contiene únicamente código PHP, constantes y variables del personaje. No necesita etiqueta de cierre porque no mezcla código PHP con HTML.

La salida se incrusta en el documento mediante etiquetas PHP cortas, por ejemplo:

```php
<?= $nombreSeguro ?>
```

La insignia del personaje utiliza la forma larga solicitada:

```php
<?php echo $insignia; ?>
```

## 5. Conversión de tipos y cálculos

En `ficha.php` se convierten los datos recibidos como texto:

```php
$fuerza = (int) $fuerzaTxt;
$destreza = (int) $destrezaTxt;
$inteligencia = (int) $inteligenciaTxt;
$constitucion = (int) $constitucionTxt;
$experiencia = (int) $experienciaTxt;
$vidaActual = (int) $vidaActualTxt;
$oro = (float) $oroTxt;
```

Se utilizan los siguientes operadores y construcciones:

- `intdiv()` para la división entera.
- `%` para obtener el resto de una división.
- `**` para elevar a una potencia.
- `<=>` para comparar el poder del héroe con el rival.
- `? :` para decisiones mediante el operador ternario.
- `??` para proporcionar un apodo alternativo.
- `?:` para proporcionar un lema alternativo.
- `.=` para construir el registro de combate por partes.
- `str_repeat()` para crear las barras de progreso.
- `htmlspecialchars()` para mostrar el nombre y los textos de forma segura.
- `number_format()` para mostrar los números con formato español.
- Heredoc para construir la crónica de combate.

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

El estado se calcula con ternarios anidados y las decisiones se calculan mediante expresiones booleanas:

```php
$estado = $vidaActual > 0 ? ($pctVida < 35 ? 'En peligro' : 'En pie') : 'K.O.';
$puedeAscender = $nivel >= 5 && $pctVida >= 50;
$necesitaPocion = $pctVida < 40 || !$esVeterano;
```

## 6. Comparación con el rival

El rival configurado es el **Ogro de las Cavernas**, con un poder de `200`.

La comparación utiliza el operador espacial:

```php
$comparacion = $poder <=> PODER_RIVAL;
```

El veredicto se obtiene comparando el resultado con `===` y usando un ternario anidado:

```php
$veredicto = $comparacion === 1
    ? 'Ventaja: ¡a la carga!'
    : ($comparacion === 0 ? 'Empate: combate igualado' : 'Desventaja: mejor retirarse');
```

## 7. Salida HTML y depuración

La ficha muestra:

- Nombre seguro del héroe.
- Clase, apodo y lema.
- Nivel, vida, daño, crítico, poder y oro.
- Fuerza, destreza, inteligencia y constitución.
- Barra de vida y barra de experiencia.
- Estado y decisiones tácticas.
- Rival y veredicto.
- Crónica de tres líneas.
- Registro de combate.
- Tipos de variables y comparación entre `==` y `===`.
- Fecha, hora y versión de PHP.

El modo de depuración comprueba la diferencia entre el valor original recibido como texto y el valor convertido a entero:

```php
get_debug_type($experienciaTxt);
get_debug_type($experiencia);
$experienciaTxt == $experiencia;
$experienciaTxt === $experiencia;
```

La comparación con `==` puede ser verdadera porque PHP convierte los tipos. La comparación con `===` es falsa porque el primer valor es `string` y el segundo es `int`.

## 8. Comprobaciones realizadas

Se comprobó que todos los servicios respondían correctamente:

| Servicio | URL | Resultado |
|---|---|---|
| BERCIANO TECH | `http://localhost:8000/` | HTTP 200 |
| ARES | `http://localhost:8001/` | HTTP 200 |
| Forja de Héroes | `http://localhost:8002/` | HTTP 200 |
| Diagnóstico | `http://localhost:8002/diagnostico.php` | HTTP 200 |
| phpMyAdmin | `http://localhost:8080/` | HTTP 200 |

También se comprobó la sintaxis PHP:

```powershell
docker exec php_web_3 php -l /var/www/html/ficha.php
docker exec php_web_3 php -l /var/www/html/inc/heroe.php
```

Resultado obtenido:

```text
No syntax errors detected in /var/www/html/ficha.php
No syntax errors detected in /var/www/html/inc/heroe.php
```

## 9. Problemas encontrados y soluciones

### Apache mostraba el código PHP

El primer contenedor utilizaba Apache sin un intérprete PHP correctamente configurado. Por eso el navegador mostraba etiquetas como `<?php ... ?>` en lugar de ejecutar el código.

La solución fue utilizar contenedores basados en `php:8.4-apache`, que incluyen Apache y PHP integrados.

### Conflicto de puertos

El puerto `8000` ya estaba ocupado por el contenedor de BERCIANO TECH. Se mantuvieron puertos diferentes para cada aplicación:

- `8000` para BERCIANO TECH.
- `8001` para ARES.
- `8002` para Forja de Héroes.

### Error en `str_repeat()`

Al probar una vida actual superior a la vida máxima calculada, la expresión que calculaba los bloques vacíos produjo un número negativo. PHP lanzó un `ValueError` porque `str_repeat()` no acepta cantidades negativas.

Se restauró una configuración funcional con `500 HP` y `1.225.000 XP`. Este punto debe revisarse si se quiere permitir una vida actual superior a la vida máxima.

### Aviso dentro del heredoc

El heredoc no interpretaba `NOMBRE_RIVAL` como una constante escrita directamente. Se creó la variable `$nombreRival = NOMBRE_RIVAL` y se utilizó esa variable dentro del heredoc.

## 10. Declaración del uso de IA

Se utilizó un asistente de inteligencia artificial como apoyo técnico durante el proceso. La ayuda se empleó para:

- Interpretar errores de Docker, Apache y PHP.
- Revisar la estructura de los archivos.
- Proponer comandos de comprobación.
- Explicar operadores y cálculos de PHP.
- Ayudar a documentar el proceso.

La selección del personaje, sus datos, la configuración final y las decisiones del proyecto fueron revisadas por el alumno/a. El código debe ser comprendido y comprobado antes de la entrega.

## 11. Capturas incluidas

Las capturas reales se encuentran en `forja/capturas/`:

| Archivo | Contenido |
|---|---|
| `01-berciano-tech.png` | Página principal BERCIANO TECH |
| `02-ares.png` | Página de ARES |
| `03-forja-bjorn.png` | Ficha de Bjorn Ragnarson |
| `04-diagnostico.png` | Panel de diagnóstico |

## 12. Estado de entrega

- [x] Contenedor `php_web_3` configurado en el puerto 8002.
- [x] Ficha de Bjorn funcionando.
- [x] Datos y cálculos principales implementados.
- [x] Sintaxis de `ficha.php` comprobada.
- [x] Sintaxis de `inc/heroe.php` comprobada.
- [x] Capturas de las páginas generadas.
- [x] Memoria redactada.
- [ ] Completar todos los `TODO` de `diagnostico.php`.
- [ ] Añadir y configurar `forja/php/forja.ini` con las seis directivas requeridas.
- [ ] Revisar la variante exacta exigida por el profesor.
- [ ] Revisar los valores calculados contra la tabla de comprobación.
- [ ] Personalizar los datos de alumno/a y fecha.

## 13. Conclusión

El proyecto demuestra cómo Docker puede separar varias aplicaciones PHP mediante contenedores y puertos distintos. BERCIANO TECH funciona como punto de entrada, ARES se sirve desde otro contenedor y Forja de Héroes dispone de un servicio PHP independiente.

La ficha no guarda el personaje en una base de datos: cada petición carga los datos de `inc/heroe.php`, realiza los cálculos y genera de nuevo el HTML. Esto permite comprobar que la salida cambia cuando se modifican los datos del héroe.

La configuración queda preparada para continuar el trabajo del reto, completar el diagnóstico y documentar las directivas PHP antes de la entrega final.
