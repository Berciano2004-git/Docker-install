<?php
/**
 * =====================================================================
 *  PASO 02 · La amnesia de ORBIT-OS                       CE 2.a · 2.b
 * =====================================================================
 *
 *  OBJETIVO: entender el MODELO DE EJECUCIÓN de PHP, que es el mayor
 *  cambio mental respecto a Java.
 *
 *  Ciclo de vida de CADA petición:
 *    1. El navegador pide /02-amnesia.php
 *    2. Nginx ve que es un .php y se lo pasa a PHP-FPM
 *    3. PHP-FPM ejecuta el script DE ARRIBA ABAJO, desde cero
 *    4. Todo lo que el script "escribe" (echo, HTML) se envía como respuesta
 *    5. El script TERMINA y sus variables DESAPARECEN
 *
 *  GUION PARA EL PROFESOR
 *  - Antes de abrir la página, preguntar: "Si recargo 5 veces, ¿cuánto
 *    valdrá el contador de visitas?". Muchos dirán 5 (piensan en Java,
 *    donde el programa sigue vivo en memoria).
 *  - Recargar varias veces: SIEMPRE vale 1.
 *  - Conclusión: PHP NO GUARDA ESTADO entre peticiones. Para "recordar"
 *    necesitaremos cookies y sesiones (UD5) o una base de datos (UD9).
 */

// hrtime(true) devuelve un instante en nanosegundos. Lo guardamos al
// principio para medir, al final, cuánto tarda en ejecutarse el script.
$inicio = hrtime(true);

// Cada petición empieza aquí, con la memoria vacía.
$visitas = 0;       // ← se crea de nuevo en CADA petición
$visitas++;         // ← operador de incremento: ahora vale 1... siempre

// random_int() genera un entero aleatorio. Cada petición obtiene uno
// distinto porque cada petición es una ejecución completamente nueva.
$idEjecucion = random_int(1000, 9999);

// $_SERVER es una variable SUPERGLOBAL: PHP la rellena automáticamente
// con datos de la petición. Accedemos a un dato con ['CLAVE'].
// (Los arrays en profundidad llegan en la UD3; aquí solo leemos un dato.)
// El operador ?? da un valor por defecto si el dato no existe.
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
$ipCliente = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
$instantePeticion = date('H:i:s', $_SERVER['REQUEST_TIME'] ?? time());
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARES-7 · La amnesia de ORBIT-OS</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 02</span>
    <h1>La amnesia de ORBIT-OS
        <small>Cada petición es un programa nuevo que nace y muere</small></h1>

    <div class="rejilla">
        <div class="dato">
            <div class="etiqueta">Contador de visitas</div>
            <div class="valor alerta"><?= $visitas ?></div>
        </div>
        <div class="dato">
            <div class="etiqueta">ID de esta ejecución</div>
            <div class="valor">#<?= $idEjecucion ?></div>
        </div>
        <div class="dato">
            <div class="etiqueta">Petición recibida</div>
            <div class="valor"><?= $instantePeticion ?></div>
        </div>
    </div>

    <div class="panel">
        <p>Método HTTP: <code><?= $metodo ?></code> ·
           IP que ve el servidor: <code><?= $ipCliente ?></code></p>
        <p class="nota">Recarga varias veces. El contador vale siempre 1 y el
           ID cambia: <strong>ORBIT-OS no recuerda nada</strong> de la petición anterior.</p>
    </div>

    <h2>Java frente a PHP</h2>
    <table>
        <tr><th></th><th>Java (0485)</th><th>PHP (servidor web)</th></tr>
        <tr><td>Arranque</td><td>Compilas y ejecutas <code>main()</code></td><td>Cada petición HTTP ejecuta el script</td></tr>
        <tr><td>Vida del programa</td><td>Hasta que termina <code>main()</code></td><td>Lo que dura UNA petición (milisegundos)</td></tr>
        <tr><td>Variables</td><td>Viven mientras el programa vive</td><td>Mueren al enviar la respuesta</td></tr>
        <tr><td>Salida</td><td><code>System.out.println()</code> a la consola</td><td><code>echo</code> al documento HTML</td></tr>
    </table>

    <?php
    // Calculamos el tiempo transcurrido desde $inicio.
    // (hrtime devuelve nanosegundos; dividimos por 1000 para microsegundos)
    $duracion = (hrtime(true) - $inicio) / 1000;
    ?>
    <p class="nota">Este script ha tardado <?= round($duracion, 1) ?> µs en generar
       el HTML. Después, ha muerto.</p>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
