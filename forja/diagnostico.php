<?php
declare(strict_types=1);

$reportaTodo = error_reporting() === E_ALL;
$iniAdicionales = php_ini_scanned_files() ?: 'Ninguno';
$zonaOriginal = ini_get('date.timezone') ?: 'UTC';
$horaAntes = date('d/m/Y H:i:s');
$resultadoZona = ini_set('date.timezone', 'UTC');
$horaDespues = date('d/m/Y H:i:s');
ini_restore('date.timezone');
$zonaRestaurada = ini_get('date.timezone') ?: 'UTC';
$resultadoShort = ini_set('short_open_tag', 'Off');
$shortActual = ini_get('short_open_tag');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnóstico del servidor · Forja de Héroes</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <h1>Diagnóstico del servidor</h1>
    <p class="subtitulo">PHP <?= PHP_VERSION ?> · <?= PHP_OS_FAMILY ?></p>

    <h2>Directivas configuradas en php/forja.ini</h2>
    <table>
        <tr><th>Directiva</th><th>Valor</th><th>Descripción</th></tr>
        <tr><td>display_errors</td><td><?= htmlspecialchars((string) ini_get('display_errors'), ENT_QUOTES, 'UTF-8') ?></td><td>Muestra errores durante el desarrollo.</td></tr>
        <tr><td>error_reporting</td><td><?= htmlspecialchars((string) ini_get('error_reporting'), ENT_QUOTES, 'UTF-8') ?></td><td>Define los niveles de error notificados.</td></tr>
        <tr><td>date.timezone</td><td><?= htmlspecialchars((string) ini_get('date.timezone'), ENT_QUOTES, 'UTF-8') ?></td><td>Zona horaria usada por date().</td></tr>
        <tr><td>short_open_tag</td><td><?= htmlspecialchars((string) ini_get('short_open_tag'), ENT_QUOTES, 'UTF-8') ?></td><td>Controla las etiquetas PHP cortas.</td></tr>
        <tr><td>expose_php</td><td><?= htmlspecialchars((string) ini_get('expose_php'), ENT_QUOTES, 'UTF-8') ?></td><td>Controla si PHP se anuncia en cabeceras.</td></tr>
        <tr><td>log_errors</td><td><?= htmlspecialchars((string) ini_get('log_errors'), ENT_QUOTES, 'UTF-8') ?></td><td>Registra los errores del servidor.</td></tr>
    </table>

    <h2>Comprobación de errores</h2>
    <p>¿error_reporting() === E_ALL?: <?= $reportaTodo ? 'Sí' : 'No' ?></p>

    <h2>Ficheros .ini adicionales</h2>
    <pre><?= htmlspecialchars($iniAdicionales, ENT_QUOTES, 'UTF-8') ?></pre>

    <h2>Cambio temporal de date.timezone</h2>
    <table>
        <tr><td>Zona original</td><td><?= htmlspecialchars($zonaOriginal, ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><td>Hora antes</td><td><?= $horaAntes ?></td></tr>
        <tr><td>Resultado de ini_set()</td><td><?= htmlspecialchars((string) $resultadoZona, ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><td>Hora después en UTC</td><td><?= $horaDespues ?></td></tr>
        <tr><td>Zona restaurada con ini_restore()</td><td><?= htmlspecialchars($zonaRestaurada, ENT_QUOTES, 'UTF-8') ?></td></tr>
    </table>

    <h2>Intento de cambio de short_open_tag</h2>
    <table>
        <tr><td>Resultado de ini_set()</td><td><?= htmlspecialchars((string) $resultadoShort, ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><td>Valor observado</td><td><?= htmlspecialchars((string) $shortActual, ENT_QUOTES, 'UTF-8') ?></td></tr>
    </table>
    <p>Algunas directivas solo pueden establecerse en la configuración del servidor y no cambian durante la ejecución del script.</p>

    <footer><a href="ficha.php">← Volver a la ficha</a></footer>
</main>
</body>
</html>
