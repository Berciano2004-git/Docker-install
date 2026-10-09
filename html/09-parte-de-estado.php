<?php
/**
 * =====================================================================
 *  PASO 09 · Parte de estado final                CE 2.c · 2.e · 2.f · 2.g
 * =====================================================================
 *
 *  OBJETIVO: juntar todo lo aprendido en una página "real":
 *   - Separar configuración/datos (inc/config.php) de la página.
 *   - Calcular ARRIBA y pintar ABAJO.
 *   - Tipos, conversiones, operadores, constantes, cadenas con formato.
 *   - Directiva strict_types y escape seguro con htmlspecialchars.
 *
 *  Es también el MODELO de estructura que se pide en el entregable.
 */

declare(strict_types=1);

// require_once carga el fichero UNA sola vez. Si no existe, error fatal
// (require) → el script se detiene. __DIR__ = carpeta de ESTE fichero.
require_once __DIR__ . '/inc/config.php';

// =====================================================================
// 1. CÁLCULOS (lógica): aquí no se escribe nada de HTML
// =====================================================================

// Oxígeno
$consumoDiarioL   = $tripulantes * CONSUMO_O2_PERSONA_DIA_L;
$autonomiaDias    = $reservaOxigenoL / $consumoDiarioL;             // float
$diasCompletos    = intdiv($reservaOxigenoL, $consumoDiarioL);      // int
$pctObjetivo      = min($autonomiaDias / OBJETIVO_MISION_DIAS * 100, 100);
$bloquesO2        = (int) round($pctObjetivo / 100 * BLOQUES_BARRA); // (int): strict_types
$barraO2          = str_repeat('█', $bloquesO2) . str_repeat('░', BLOQUES_BARRA - $bloquesO2);
$o2Suficiente     = $autonomiaDias >= OBJETIVO_MISION_DIAS;
$estadoO2         = $o2Suficiente ? 'NOMINAL' : 'REABASTECER';
$claseO2          = $o2Suficiente ? 'ok' : 'aviso';

// Combustible
$pctCombustible   = $combustibleKg / CAPACIDAD_TANQUE_KG * 100;
$bloquesFuel      = (int) round($pctCombustible / 100 * BLOQUES_BARRA);
$barraFuel        = str_repeat('█', $bloquesFuel) . str_repeat('░', BLOQUES_BARRA - $bloquesFuel);

// Textos con formato español (coma decimal, punto de miles)
$txtConsumo       = number_format($consumoDiarioL, 0, ',', '.');
$txtAutonomia     = number_format($autonomiaDias, 1, ',', '.');
$txtTemperatura   = number_format($temperaturaIntC, 1, ',', '.');
$txtCombustible   = number_format($combustibleKg, 0, ',', '.');
$txtAlerta        = $ultimaAlerta ?? 'Sin alertas activas';

// Datos del sistema (directivas y entorno)
$versionPhp       = PHP_VERSION;
$zonaHoraria      = ini_get('date.timezone') ?: 'UTC (sin configurar)';
$modoErrores      = ini_get('display_errors') === '1' ? 'DESARROLLO (errores visibles)' : 'PRODUCCIÓN (errores ocultos)';
$generadoA        = date('d/m/Y H:i:s');

// Escape seguro del texto escrito por un tripulante
$mensajeSeguro    = htmlspecialchars($mensajeDelDia);

// =====================================================================
// 2. PRESENTACIÓN: HTML con valores incrustados
// =====================================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= NOMBRE_ESTACION ?> · Parte de estado</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 09</span>
    <h1>🛰️ Parte de estado de <?= NOMBRE_ESTACION ?>
        <small>Comandante: <?= htmlspecialchars($comandante) ?> ·
               <?= $tripulantes ?> tripulantes · generado el <?= $generadoA ?></small></h1>

    <div class="rejilla">
        <div class="dato">
            <div class="etiqueta">Oxígeno</div>
            <div class="valor <?= $claseO2 ?>"><?= $estadoO2 ?></div>
            <div class="nota"><?= $txtAutonomia ?> días de autonomía (<?= $diasCompletos ?> completos)</div>
        </div>
        <div class="dato">
            <div class="etiqueta">Consumo diario O₂</div>
            <div class="valor"><?= $txtConsumo ?> L</div>
        </div>
        <div class="dato">
            <div class="etiqueta">Temperatura interior</div>
            <div class="valor"><?= $txtTemperatura ?> °C</div>
        </div>
        <div class="dato">
            <div class="etiqueta">Combustible</div>
            <div class="valor"><?= $txtCombustible ?> kg</div>
        </div>
    </div>

    <div class="panel">
        <p class="barra">O₂   <?= $barraO2 ?> <?= round($pctObjetivo) ?>% del objetivo (<?= OBJETIVO_MISION_DIAS ?> días)</p>
        <p class="barra">FUEL <?= $barraFuel ?> <?= round($pctCombustible) ?>% del tanque</p>
        <p>Alertas: <strong><?= $txtAlerta ?></strong></p>
    </div>

    <h2>Mensaje del día</h2>
    <div class="panel"><p><?= $mensajeSeguro ?></p></div>

    <h2>Diagnóstico de ORBIT-OS</h2>
    <table>
        <tr><td>Versión de PHP</td><td class="mono"><?= $versionPhp ?></td></tr>
        <tr><td>Zona horaria (date.timezone)</td><td class="mono"><?= $zonaHoraria ?></td></tr>
        <tr><td>Modo de errores (display_errors)</td><td class="mono"><?= $modoErrores ?></td></tr>
        <tr><td>strict_types</td><td class="mono">activado en este fichero</td></tr>
    </table>

    <footer>Recuerda: este parte se ha generado para TI, ahora. Si recargas,
        ORBIT-OS lo calculará todo otra vez desde cero. ·
        <a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
