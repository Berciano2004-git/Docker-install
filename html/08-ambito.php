<?php
/**
 * =====================================================================
 *  PASO 08 · ¿Quién ve cada variable?                           CE 2.h
 * =====================================================================
 *
 *  OBJETIVO: ÁMBITO (dónde es visible una variable) y VIDA (cuánto dura).
 *
 *  Para ver el ámbito necesitamos una FUNCIÓN. Las funciones se estudian
 *  a fondo en la UD4; aquí solo usamos la forma mínima:
 *
 *      function nombre(): tipoQueDevuelve {
 *          ...
 *          return valor;
 *      }
 *
 *  (En Java sería un método static. En PHP una función puede ir suelta,
 *   fuera de cualquier clase.)
 *
 *  LOS 4 ÁMBITOS DE PHP
 *  1. GLOBAL: variables creadas fuera de funciones. Viven hasta que
 *     termina el script... ¡que es al terminar la petición!
 *  2. LOCAL: variables creadas dentro de una función. Mueren al salir.
 *     ¡OJO, DIFERENCIA CON JAVA!: una función de PHP NO ve las variables
 *     globales salvo que las pida con "global" o con $GLOBALS.
 *  3. STATIC: variable local que conserva su valor entre llamadas
 *     a la función... DENTRO DE LA MISMA PETICIÓN.
 *  4. SUPERGLOBALES: $_SERVER, $_GET, $_POST, $_COOKIE, $_SESSION,
 *     $_FILES, $_ENV, $_REQUEST, $GLOBALS. PHP las crea solas y se ven
 *     en todas partes.
 */

declare(strict_types=1);

// ---------------------------------------------------------------------
// 1. Variable GLOBAL (creada fuera de cualquier función)
// ---------------------------------------------------------------------
$combustible = 850;

// ---------------------------------------------------------------------
// 2. Una función NO ve las globales por defecto
// ---------------------------------------------------------------------
function leerSinPermiso(): string
{
    // Aquí $combustible es una variable LOCAL distinta que NO existe.
    // Con ?? evitamos el aviso "Undefined variable" y mostramos un texto.
    return (string) ($combustible ?? 'no existe aquí dentro');
}

// ---------------------------------------------------------------------
// 3. Pedir acceso con la palabra "global" o con $GLOBALS
// ---------------------------------------------------------------------
function leerConGlobal(): string
{
    global $combustible;          // "trae" la global a esta función
    return (string) $combustible;
}

function leerConGLOBALS(): string
{
    return (string) $GLOBALS['combustible'];   // $GLOBALS es una superglobal
}

// Las CONSTANTES sí se ven en todas partes (sin global).
const CAPACIDAD_TANQUE = 1000;
function porcentajeTanque(): float
{
    global $combustible;
    return $combustible / CAPACIDAD_TANQUE * 100;
}

// ---------------------------------------------------------------------
// 4. Variable LOCAL: nace y muere en cada llamada
// ---------------------------------------------------------------------
function contadorLocal(): int
{
    $llamadas = 0;        // se crea de nuevo en cada llamada
    $llamadas++;
    return $llamadas;     // siempre 1
}

// ---------------------------------------------------------------------
// 5. Variable STATIC: recuerda su valor entre llamadas...
//    ...pero SOLO durante esta petición (recarga la página y vuelve a 1)
// ---------------------------------------------------------------------
function contadorStatic(): int
{
    static $llamadas = 0; // se inicializa UNA vez por petición
    $llamadas++;
    return $llamadas;
}

// Llamamos 3 veces a cada una y guardamos los resultados
$local1 = contadorLocal();
$local2 = contadorLocal();
$local3 = contadorLocal();
$static1 = contadorStatic();
$static2 = contadorStatic();
$static3 = contadorStatic();

// ---------------------------------------------------------------------
// 6. SUPERGLOBAL $_SERVER: datos de la petición y del servidor
//    Todo lo que viene del CLIENTE (User-Agent, URI) puede estar
//    manipulado → htmlspecialchars al pintarlo.
// ---------------------------------------------------------------------
$software  = $_SERVER['SERVER_SOFTWARE'] ?? 'desconocido';
$uri       = $_SERVER['REQUEST_URI'] ?? '(CLI)';
$agente    = $_SERVER['HTTP_USER_AGENT'] ?? '(sin navegador)';
$script    = $_SERVER['SCRIPT_FILENAME'] ?? __FILE__;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARES-7 · Ámbito de las variables</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 08</span>
    <h1>¿Quién ve cada variable? <small>Ámbito y tiempo de vida</small></h1>

    <h2>1 · Global frente a local</h2>
    <table>
        <tr><th>Desde…</th><th>Valor de $combustible</th></tr>
        <tr><td>El script (ámbito global)</td><td><?= $combustible ?></td></tr>
        <tr><td>Una función sin permiso</td><td class="alerta"><?= leerSinPermiso() ?></td></tr>
        <tr><td>Una función con <code>global</code></td><td class="ok"><?= leerConGlobal() ?></td></tr>
        <tr><td>Una función con <code>$GLOBALS</code></td><td class="ok"><?= leerConGLOBALS() ?></td></tr>
        <tr><td>Constante dentro de función</td><td><?= porcentajeTanque() ?> % del tanque</td></tr>
    </table>
    <p class="nota">En Java un método ve los atributos de su clase. En PHP una función
       está "aislada": solo ve sus parámetros, sus variables locales, las constantes
       y las superglobales. Usar <code>global</code> es mala práctica (lo sustituiremos
       por parámetros en la UD4); aquí lo usamos para entender el ámbito.</p>

    <h2>2 · Local frente a static (3 llamadas)</h2>
    <table>
        <tr><th>Función</th><th>1.ª</th><th>2.ª</th><th>3.ª</th></tr>
        <tr><td><code>contadorLocal()</code></td><td><?= $local1 ?></td><td><?= $local2 ?></td><td><?= $local3 ?></td></tr>
        <tr><td><code>contadorStatic()</code></td><td><?= $static1 ?></td><td><?= $static2 ?></td><td><?= $static3 ?></td></tr>
    </table>
    <div class="panel">
        <p><strong>Recarga la página.</strong> ¿El contador static sigue por 4, 5, 6?</p>
        <p class="nota">No: vuelve a 1, 2, 3. "static" dura lo que dura la petición.
           La amnesia de ORBIT-OS (paso 02) afecta a TODAS las variables, incluidas
           las static. Para recordar entre peticiones → sesiones y cookies (UD5).</p>
    </div>

    <h2>3 · Superglobal $_SERVER</h2>
    <table>
        <tr><td><code>SERVER_SOFTWARE</code></td><td><?= htmlspecialchars($software) ?></td></tr>
        <tr><td><code>REQUEST_URI</code></td><td><?= htmlspecialchars($uri) ?></td></tr>
        <tr><td><code>HTTP_USER_AGENT</code></td><td><?= htmlspecialchars($agente) ?></td></tr>
        <tr><td><code>SCRIPT_FILENAME</code></td><td><?= htmlspecialchars($script) ?></td></tr>
    </table>
    <p class="nota">Prueba a añadir <code>?hola=&lt;b&gt;x&lt;/b&gt;</code> al final de la URL y observa
       REQUEST_URI: gracias a htmlspecialchars no se interpreta como HTML.</p>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
