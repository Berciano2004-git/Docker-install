<?php
/**
 * =====================================================================
 *  diagnostico.php · Panel de directivas del servidor          [CE 2.f]
 * =====================================================================
 *  Autor/a: TODO
 *
 *  TODO (ver requisito R9 del enunciado):
 *   - strict_types
 *   - Leer con ini_get las 6 directivas de tu forja.ini y comprobar
 *     error_reporting() con E_ALL
 *   - Mostrar qué ficheros .ini adicionales ha cargado PHP
 *   - Cambiar date.timezone con ini_set, mostrar la hora antes y después,
 *     el valor devuelto por ini_set y restaurarla con ini_restore
 *   - Intentar cambiar short_open_tag con ini_set y mostrar el resultado
 */
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
    <p class="subtitulo">TODO versión de PHP · sistema operativo</p>

    <h2>Directivas configuradas en php/forja.ini</h2>
    <table>
        <tr><th>Directiva</th><th>Valor (ini_get)</th><th>Por qué</th></tr>
        <tr><td>TODO</td><td>TODO</td><td>TODO</td></tr>
    </table>

    <h2>Cambio en tiempo de ejecución (ini_set)</h2>
    <table>
        <tr><td>TODO</td><td>TODO</td></tr>
    </table>

    <footer><a href="ficha.php">← Volver a la ficha</a></footer>
</main>
</body>
</html>
