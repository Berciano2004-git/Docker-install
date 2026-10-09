<?php
/**
 * =====================================================================
 *  PASO 01 · Hola, estación                         CE 2.a · 2.c · 2.e
 * =====================================================================
 *
 *  OBJETIVO: ver que un fichero .php es un documento HTML con "islas"
 *  de código que el SERVIDOR ejecuta antes de enviar la respuesta.
 *
 *  GUION PARA EL PROFESOR
 *  1. Abrir http://localhost:8080/01-hola-estacion.php
 *  2. Pulsar Ctrl+U (ver código fuente) y preguntar a la clase:
 *     "¿Dónde está el código PHP?"  →  NO ESTÁ. El navegador solo
 *     recibe el HTML que PHP ha ESCRITO. El código se queda en el servidor.
 *  3. Recargar varias veces (F5): la hora cambia. La página se genera
 *     de nuevo en cada petición: eso es una PÁGINA DINÁMICA.
 *
 *  COMPARACIÓN CON JAVA (lo que ya conocen de 0485)
 *  - En Java hay que compilar (javac) y ejecutar (java) un main().
 *  - En PHP no hay main() ni compilación manual: el servidor recibe la
 *    petición, el intérprete lee el fichero de arriba abajo y lo ejecuta.
 *
 *  Este primer bloque PHP está ANTES del <!DOCTYPE>. Es buena práctica:
 *  primero calculamos, después pintamos.
 */

// Una variable en PHP empieza SIEMPRE por $. No se declara el tipo
// (en Java sería: String nombreEstacion = "ARES-7";).
$nombreEstacion = 'ARES-7';

// date() es una función de PHP que devuelve la fecha/hora del SERVIDOR
// con el formato indicado (H = hora 00-23, i = minutos, s = segundos).
$horaServidor = date('H:i:s');
$fechaServidor = date('d/m/Y');

// Aquí CERRAMOS la etiqueta PHP porque a continuación viene HTML.
// Todo lo que haya fuera de las etiquetas PHP se envía TAL CUAL al navegador.
// (OJO: dentro de un comentario de una línea // no se puede escribir la
//  etiqueta de cierre de PHP: el intérprete la obedecería y cerraría el bloque.)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- &lt;?= ... ?&gt; es la forma CORTA de &lt;?php echo ... ?&gt;.
         Ideal para "incrustar" un valor dentro del HTML. -->
    <title>Hola desde <?= $nombreEstacion ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 01</span>

    <!-- FORMA LARGA: etiqueta de apertura, instrucción echo y cierre. -->
    <h1>Hola, tripulación de <?php echo $nombreEstacion; ?></h1>

    <div class="rejilla">
        <div class="dato">
            <div class="etiqueta">Hora del servidor</div>
            <!-- FORMA CORTA: equivale exactamente a la forma larga con echo -->
            <div class="valor"><?= $horaServidor ?></div>
        </div>
        <div class="dato">
            <div class="etiqueta">Fecha</div>
            <div class="valor"><?= $fechaServidor ?></div>
        </div>
    </div>

    <h2>Dos comentarios, dos destinos</h2>
    <!-- Este comentario HTML SÍ viaja al navegador: búscalo con Ctrl+U. -->
    <?php // Este comentario PHP NO viaja: el intérprete lo descarta. ?>
    <p>Busca los dos comentarios en el código fuente. ¿Cuántos encuentras?</p>

    <h2>Trampa: PHP dentro de un comentario HTML</h2>
    <?php
    /*
     * PREGUNTA A LA CLASE: la línea siguiente es un comentario HTML con
     * PHP dentro. ¿Se ejecuta el PHP?
     * RESPUESTA: SÍ. PHP no entiende de HTML: ejecuta todo lo que esté
     * entre sus etiquetas, esté donde esté. El navegador recibirá el
     * comentario... con la hora YA escrita dentro. Compruébalo con Ctrl+U.
     */
    ?>
    <!-- Comentario HTML con PHP dentro. Hora: <?= $horaServidor ?> -->
    <p class="nota">(Pista: el comentario de arriba no se ve en la página,
       pero está en el código fuente… y con la hora ya calculada.)</p>

    <h2>PHP también puede escribir etiquetas HTML</h2>
    <?php
    // echo puede escribir cualquier texto, incluidas etiquetas HTML.
    // El navegador no sabe (ni le importa) si este <p> lo escribió
    // una persona o lo generó PHP.
    echo '<p class="ok">Este párrafo lo ha escrito PHP con echo.</p>';

    // print hace lo mismo que echo (con pequeñas diferencias que no
    // nos afectan). En el módulo usaremos echo.
    print '<p class="nota">Y este, con print.</p>';
    ?>

    <p class="nota">Recarga con F5: la hora cambia porque la página se
       <strong>genera de nuevo</strong> en cada petición.</p>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
