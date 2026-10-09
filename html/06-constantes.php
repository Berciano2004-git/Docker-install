<?php
/**
 * =====================================================================
 *  PASO 06 · Leyes de la física                           CE 2.d · 2.g
 * =====================================================================
 *
 *  OBJETIVO: CONSTANTES. Valores que no cambian durante la ejecución.
 *
 *  DOS FORMAS DE DEFINIRLAS
 *  - const NOMBRE = valor;       → en tiempo de compilación. Preferida.
 *  - define('NOMBRE', valor);    → en tiempo de ejecución (es una función).
 *
 *  DIFERENCIAS CON LAS VARIABLES
 *  - NO llevan $.  Se escriben en MAYÚSCULAS por convención.
 *  - No se pueden reasignar (en Java: static final).
 *  - Son GLOBALES: se ven en todo el script, también dentro de funciones.
 *
 *  Además, PHP trae constantes PREDEFINIDAS (PHP_VERSION...) y
 *  constantes MÁGICAS (__LINE__, __FILE__...) cuyo valor depende de
 *  dónde se escriben.
 */

// ---------------------------------------------------------------------
// 1. CONSTANTES PROPIAS
// ---------------------------------------------------------------------
const GRAVEDAD_TIERRA = 9.81;            // m/s²
const VELOCIDAD_LUZ_KMS = 299_792;       // km/s
const NOMBRE_ESTACION = 'ARES-7';
define('ALTITUD_ORBITA_KM', 408);        // equivalente con define()

// Si intentáramos cambiar una constante:
//     GRAVEDAD_TIERRA = 10;   → Error de sintaxis: no se puede asignar
//     const GRAVEDAD_TIERRA = 10;  → Warning: constante ya definida
// (Pruébalo en clase descomentando y observa el mensaje de error.)

// Cálculos con constantes: tiempo que tarda una señal de radio en
// llegar a la Tierra desde la órbita (ida).
$retardoSenalMs = ALTITUD_ORBITA_KM / VELOCIDAD_LUZ_KMS * 1000;

// Peso de 80 kg de masa en la Tierra frente a la Luna (1,62 m/s²)
const GRAVEDAD_LUNA = 1.62;
$masaKg = 80;
$pesoTierraN = $masaKg * GRAVEDAD_TIERRA;
$pesoLunaN   = $masaKg * GRAVEDAD_LUNA;

// ---------------------------------------------------------------------
// 2. CURIOSIDAD NUMÉRICA: los float no son exactos (igual que en Java)
// ---------------------------------------------------------------------
$sumaFloat = 0.1 + 0.2;          // 0.30000000000000004
$esIgual   = ($sumaFloat === 0.3);   // false
// Para comparar decimales se usa una tolerancia (lo veremos en la UD3 con if).
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= NOMBRE_ESTACION ?> · Leyes de la física</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 06</span>
    <h1>Leyes de la física <small>Constantes</small></h1>

    <h2>1 · Constantes de la estación</h2>
    <table>
        <tr><th>Constante</th><th>Valor</th></tr>
        <tr><td><code>GRAVEDAD_TIERRA</code></td><td><?= GRAVEDAD_TIERRA ?> m/s²</td></tr>
        <tr><td><code>VELOCIDAD_LUZ_KMS</code></td><td><?= number_format(VELOCIDAD_LUZ_KMS, 0, ',', '.') ?> km/s</td></tr>
        <tr><td><code>ALTITUD_ORBITA_KM</code> (define)</td><td><?= ALTITUD_ORBITA_KM ?> km</td></tr>
    </table>

    <div class="rejilla">
        <div class="dato"><div class="etiqueta">Retardo de señal</div>
            <div class="valor"><?= number_format($retardoSenalMs, 3, ',', '.') ?> ms</div></div>
        <div class="dato"><div class="etiqueta">Peso en la Tierra</div>
            <div class="valor"><?= number_format($pesoTierraN, 1, ',', '.') ?> N</div></div>
        <div class="dato"><div class="etiqueta">Peso en la Luna</div>
            <div class="valor"><?= number_format($pesoLunaN, 1, ',', '.') ?> N</div></div>
    </div>

    <h2>2 · Constantes predefinidas de PHP</h2>
    <table>
        <tr><td><code>PHP_VERSION</code></td><td><?= PHP_VERSION ?></td></tr>
        <tr><td><code>PHP_OS_FAMILY</code></td><td><?= PHP_OS_FAMILY ?></td></tr>
        <tr><td><code>PHP_INT_MAX</code></td><td><?= PHP_INT_MAX ?> (en Java: Long.MAX_VALUE)</td></tr>
        <tr><td><code>PHP_INT_SIZE</code></td><td><?= PHP_INT_SIZE ?> bytes</td></tr>
        <tr><td><code>M_PI</code></td><td><?= M_PI ?></td></tr>
    </table>

    <h2>3 · Constantes mágicas (dependen de DÓNDE se escriben)</h2>
    <table>
        <tr><td><code>__LINE__</code></td><td>Línea <?= __LINE__ ?> de este fichero</td></tr>
        <tr><td><code>__FILE__</code></td><td><?= __FILE__ ?></td></tr>
        <tr><td><code>__DIR__</code></td><td><?= __DIR__ ?></td></tr>
    </table>
    <p class="nota">__FILE__ muestra la ruta DENTRO DEL CONTENEDOR, no la de tu ordenador:
       el código se ejecuta en el servidor.</p>

    <h2>4 · Los float no son exactos</h2>
    <pre><?php
        var_dump($sumaFloat);   // float(0.30000000000000004)
        var_dump($esIgual);     // bool(false)
    ?></pre>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
