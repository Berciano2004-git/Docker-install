<?php
/**
 * =====================================================================
 *  PASO 07 · Configurar el ordenador de a bordo                 CE 2.f
 * =====================================================================
 *
 *  OBJETIVO: DIRECTIVAS = ajustes que MODIFICAN EL COMPORTAMIENTO
 *  PREDETERMINADO de PHP. Se pueden fijar en varios niveles:
 *
 *   NIVEL                     ÁMBITO                     EJEMPLO
 *   php.ini (+ conf.d/*.ini)  todo el servidor           display_errors = On
 *   .user.ini                 una carpeta (PHP-FPM)      memory_limit = 256M
 *   ini_set() en el script    solo esta petición         ini_set('precision', '4')
 *   declare() en el fichero   solo este fichero          declare(strict_types=1);
 *
 *  En nuestro Docker, las directivas del servidor están en
 *  php/ares7.ini, que se monta en /usr/local/etc/php/conf.d/
 *  (ábrelo en clase y comenta cada línea).
 *
 *  GUION PARA EL PROFESOR
 *  1. Enseñar php/ares7.ini y esta página.
 *  2. Cambiar en ares7.ini:  date.timezone = America/New_York
 *     → docker compose restart php → recargar: la hora cambia.
 *     (Devolverlo después a Europe/Madrid.)
 *  3. Abrir 07b-error-strict.php para ver strict_types en acción.
 */

// ---------------------------------------------------------------------
// declare(strict_types=1) DEBE ser la PRIMERA instrucción del fichero.
// Efecto: en las llamadas a funciones, PHP deja de convertir tipos
// "por su cuenta". Si una función espera int y le pasas '5', ERROR.
// Es lo más parecido al comportamiento de Java. A partir de aquí,
// TODOS nuestros ficheros lo llevarán.
// ---------------------------------------------------------------------
// (Los comentarios de cabecera no cuentan como instrucciones: por eso
//  el declare puede ir después de ellos.)
declare(strict_types=1);

// ---------------------------------------------------------------------
// 1. LEER directivas: ini_get() devuelve SIEMPRE un string
//    (On → "1", Off → "" o "0")
// ---------------------------------------------------------------------
$displayErrors  = ini_get('display_errors');
$zonaHoraria    = ini_get('date.timezone');
$maxEjecucion   = ini_get('max_execution_time');
$limiteMemoria  = ini_get('memory_limit');
$etiquetaCorta  = ini_get('short_open_tag');

// ¿Qué php.ini ha cargado el servidor? ¿Y qué ficheros extra de conf.d?
$iniPrincipal = php_ini_loaded_file() ?: '(ninguno: valores por defecto)';
$iniExtra     = php_ini_scanned_files() ?: '(ninguno)';

// ---------------------------------------------------------------------
// 2. CAMBIAR directivas en tiempo de ejecución: ini_set()
//    Devuelve el valor ANTERIOR si lo consigue, o false si no se permite.
// ---------------------------------------------------------------------
$horaMadrid = date('H:i');
$anteriorZona = ini_set('date.timezone', 'Asia/Tokyo');   // permitido (PHP_INI_ALL)
$horaTokio = date('H:i');
ini_set('date.timezone', 'Europe/Madrid');                 // la dejamos como estaba

// Algunas directivas NO se pueden cambiar desde el script: solo en php.ini
// o .user.ini. short_open_tag es una de ellas → ini_set devuelve false.
$intentoEtiqueta = ini_set('short_open_tag', '1');

// La directiva "precision" controla cuántas cifras muestra echo con los float.
$pi14 = (string) M_PI;          // con la precisión por defecto (14)
ini_set('precision', '4');
$pi4 = (string) M_PI;           // ahora con 4 cifras significativas
ini_restore('precision');       // ini_restore vuelve al valor del php.ini

// ---------------------------------------------------------------------
// 3. error_reporting(): qué NIVELES de error se notifican
//    E_ALL (todos) es lo recomendado en desarrollo.
// ---------------------------------------------------------------------
$nivelErrores = error_reporting();
$esEAll = ($nivelErrores === E_ALL);

// ---------------------------------------------------------------------
// 4. strict_types en una llamada CORRECTA (tipos exactos)
// ---------------------------------------------------------------------
$repeticiones = 5;                           // int → correcto
$estrellas = str_repeat('★', $repeticiones);  // funciona
// Con strict_types, str_repeat('★', '5') daría TypeError → ver paso 07b.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARES-7 · Directivas</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 07</span>
    <h1>Configurar el ordenador de a bordo <small>Directivas de PHP</small></h1>

    <h2>1 · Directivas activas (ini_get)</h2>
    <table>
        <tr><th>Directiva</th><th>Valor</th><th>Para qué sirve</th></tr>
        <!-- var_export muestra las comillas: así se ve que ini_get devuelve string -->
        <tr><td><code>display_errors</code></td><td><?= var_export($displayErrors, true) ?></td><td>Mostrar errores en la página (solo en desarrollo)</td></tr>
        <tr><td><code>date.timezone</code></td><td><?= var_export($zonaHoraria, true) ?></td><td>Zona horaria de date()</td></tr>
        <tr><td><code>max_execution_time</code></td><td><?= var_export($maxEjecucion, true) ?></td><td>Segundos máximos por script</td></tr>
        <tr><td><code>memory_limit</code></td><td><?= var_export($limiteMemoria, true) ?></td><td>Memoria máxima por script</td></tr>
        <tr><td><code>short_open_tag</code></td><td><?= var_export($etiquetaCorta, true) ?></td><td>Permitir la etiqueta corta &lt;? (desaconsejado)</td></tr>
    </table>
    <p class="nota">php.ini cargado: <code><?= $iniPrincipal ?></code><br>
       Ficheros adicionales: <code><?= $iniExtra ?></code></p>

    <h2>2 · Cambiar directivas en tiempo de ejecución (ini_set)</h2>
    <table>
        <tr><td>Hora con <code>Europe/Madrid</code></td><td class="mono"><?= $horaMadrid ?></td></tr>
        <tr><td>Hora tras <code>ini_set('date.timezone', 'Asia/Tokyo')</code></td><td class="mono"><?= $horaTokio ?></td></tr>
        <tr><td>Valor anterior devuelto por ini_set</td><td class="mono"><?= var_export($anteriorZona, true) ?></td></tr>
        <tr><td><code>ini_set('short_open_tag', '1')</code></td><td class="mono alerta"><?= var_export($intentoEtiqueta, true) ?> ← no permitido desde el script</td></tr>
        <tr><td>M_PI con precision = 14</td><td class="mono"><?= $pi14 ?></td></tr>
        <tr><td>M_PI con precision = 4</td><td class="mono"><?= $pi4 ?></td></tr>
    </table>

    <h2>3 · Nivel de errores</h2>
    <p>error_reporting() = <code><?= $nivelErrores ?></code> ·
       ¿Es E_ALL? <strong class="<?= $esEAll ? 'ok' : 'alerta' ?>"><?= $esEAll ? 'Sí' : 'No' ?></strong></p>

    <h2>4 · strict_types</h2>
    <div class="panel">
        <p>Llamada correcta: <code>str_repeat('★', 5)</code> → <span class="barra"><?= $estrellas ?></span></p>
        <p>Ahora abre <a href="07b-error-strict.php">07b-error-strict.php</a> para ver
           qué ocurre cuando el tipo no coincide.</p>
    </div>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
