<?php
/**
 * =====================================================================
 *  UD2 · CASO GUIADO · Estación orbital ARES-7
 *  Índice de pasos
 * =====================================================================
 *
 *  HISTORIA: somos el equipo de software de la estación orbital ARES-7.
 *  El ordenador de a bordo, ORBIT-OS, genera en el SERVIDOR el "parte de
 *  estado" que la tripulación consulta desde el navegador.
 *
 *  Pero ORBIT-OS tiene un problema: AMNESIA. Cada vez que alguien pide
 *  una página, el programa arranca de cero, genera el HTML y muere.
 *  No recuerda nada de la petición anterior. Esa es la idea central
 *  de la unidad (y de todo el módulo).
 *
 *  Cada paso se abre en el navegador y se compara SIEMPRE con
 *  "Ver código fuente de la página" (Ctrl+U).
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARES-7 · Índice del caso guiado</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <h1>🛰️ Estación orbital ARES-7
        <small>UD2 · Inserción de código en páginas web · Caso guiado</small></h1>

    <div class="panel">
        <p>Abre cada paso, observa el resultado y pulsa <strong>Ctrl+U</strong>
           para ver qué HTML ha recibido realmente tu navegador.</p>
    </div>

    <table>
        <tr><th>Paso</th><th>Qué aprendemos</th><th>CE</th></tr>
        <tr><td><a href="01-hola-estacion.php">01 · Hola, estación</a></td><td>Etiquetas &lt;?php ?&gt; y &lt;?= ?&gt;. PHP escribe HTML.</td><td>a, c, e</td></tr>
        <tr><td><a href="02-amnesia.php">02 · La amnesia de ORBIT-OS</a></td><td>Cada petición es un programa nuevo.</td><td>a, b</td></tr>
        <tr><td><a href="03-tipos.php">03 · Inventario de a bordo</a></td><td>Variables, tipos y conversiones.</td><td>d, g</td></tr>
        <tr><td><a href="04-cadenas.php">04 · Mensajes de la tripulación</a></td><td>Cadenas, interpolación, heredoc y escape seguro.</td><td>d, e, g</td></tr>
        <tr><td><a href="05-operadores.php">05 · Soporte vital</a></td><td>Operadores aritméticos, comparación, lógicos, ternario y ??.</td><td>g</td></tr>
        <tr><td><a href="06-constantes.php">06 · Leyes de la física</a></td><td>Constantes propias, predefinidas y mágicas.</td><td>d, g</td></tr>
        <tr><td><a href="07-directivas.php">07 · Configurar el ordenador de a bordo</a></td><td>Directivas: php.ini, ini_get/ini_set, strict_types.</td><td>f</td></tr>
        <tr><td><a href="07b-error-strict.php">07b · Fallo provocado</a></td><td>Qué hace strict_types cuando nos equivocamos.</td><td>f</td></tr>
        <tr><td><a href="08-ambito.php">08 · ¿Quién ve cada variable?</a></td><td>Ámbito local, global, static y superglobales.</td><td>h</td></tr>
        <tr><td><a href="09-parte-de-estado.php">09 · Parte de estado final</a></td><td>Todo junto en una página real.</td><td>c, e, f, g</td></tr>
    </table>

    <footer>0613 DWES · 2.º DAW A · PHP <?= PHP_VERSION ?></footer>
</main>
</body>
</html>
