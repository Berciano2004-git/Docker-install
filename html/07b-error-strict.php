<?php
/**
 * =====================================================================
 *  PASO 07b · Fallo provocado con strict_types                  CE 2.f
 * =====================================================================
 *
 *  ESTE FICHERO FALLA A PROPÓSITO. Sirve para ver en clase:
 *   1. Qué hace la directiva strict_types.
 *   2. Cómo se ve un error cuando display_errors = On.
 *   3. Cómo se LEE un mensaje de error: tipo, descripción, fichero y línea.
 *
 *  GUION PARA EL PROFESOR
 *  a) Abrir la página: aparece "Fatal error: Uncaught TypeError:
 *     str_repeat(): Argument #2 ($times) must be of type int, float given".
 *     → round() devuelve SIEMPRE float, aunque el resultado sea 4.0.
 *  b) Comentar la línea declare(...) y recargar: FUNCIONA, porque sin
 *     strict_types PHP convierte 4.0 a 4 en silencio.
 *     Pregunta: ¿qué es mejor, que falle o que "funcione" convirtiendo
 *     sin avisar? (En Java no compilaría.)
 *  c) Volver a activar declare y ARREGLARLO de verdad con un casting:
 *     str_repeat('█', (int) round($porcentaje / 10))
 *  d) Poner display_errors = Off en php/ares7.ini, reiniciar el contenedor
 *     y recargar: página en blanco (o error 500). Así se configura en
 *     PRODUCCIÓN: el usuario no ve detalles internos; el error va al log
 *     (docker compose logs php).
 */

declare(strict_types=1);

$porcentaje = 42.0;

// round() devuelve float (4.0). str_repeat exige int en su 2.º parámetro.
// Con strict_types=1 → TypeError. Sin strict_types → funciona (convierte).
$barra = str_repeat('█', round($porcentaje / 10));

// Esta línea nunca se ejecuta: el error anterior detiene el script.
echo "<p>Barra: {$barra}</p>";
