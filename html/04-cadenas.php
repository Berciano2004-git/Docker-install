<?php
/**
 * =====================================================================
 *  PASO 04 · Mensajes de la tripulación             CE 2.d · 2.e · 2.g
 * =====================================================================
 *
 *  OBJETIVO: trabajar con CADENAS, la materia prima de la web
 *  (todo lo que enviamos al navegador es, al final, texto).
 *
 *  CONCEPTOS
 *  1. Comillas simples '...'  → texto LITERAL (no sustituye variables)
 *  2. Comillas dobles "..."   → INTERPOLA variables y secuencias \n, \t...
 *  3. Concatenación con el PUNTO .   (en Java era +)
 *  4. Heredoc <<<  para bloques largos de texto con variables
 *  5. Funciones de cadena (¡con acentos, las mb_*!)
 *  6. SEGURIDAD: htmlspecialchars() antes de pintar texto que no es nuestro
 */

$tripulante = 'Iker';
$modulo = 'Hidroponía';
$temperatura = 21.456;

// ---------------------------------------------------------------------
// 1 y 2. COMILLAS SIMPLES frente a DOBLES
// ---------------------------------------------------------------------
$literal     = 'Hola $tripulante';          // → Hola $tripulante   (tal cual)
$interpolado = "Hola $tripulante";          // → Hola Iker
$conLlaves   = "Módulo: {$modulo}s";        // → Módulo: Hidroponías
// Las llaves { } delimitan la variable cuando va pegada a más texto.
// Sin llaves, "$modulos" buscaría una variable llamada $modulos.

// Secuencias de escape (solo funcionan con comillas DOBLES):
//   \n salto de línea   \t tabulador   \$ dólar literal   \" comilla   \\ barra
$conEscapes = "Precio: \$15 \"aprox.\"";   // → Precio: $15 "aprox."

// ---------------------------------------------------------------------
// 3. CONCATENACIÓN con . y .=
// ---------------------------------------------------------------------
$saludo = 'Buenos días, ' . $tripulante . '. Revisa ' . $modulo . '.';
$registro = 'INICIO';
$registro .= ' > revisión';      // .= añade al final (como += en Java con Strings)
$registro .= ' > informe';       // → INICIO > revisión > informe

// ---------------------------------------------------------------------
// 4. HEREDOC: bloque de texto largo que SÍ interpola (como comillas dobles)
//    NOWDOC ('ETIQUETA' con comillas simples): no interpola (como simples)
// ---------------------------------------------------------------------
$fecha = date('d/m/Y H:i');
$entradaDiario = <<<TXT
    DIARIO DE A BORDO · {$fecha}
    Tripulante de guardia: {$tripulante}
    Módulo revisado: {$modulo}
    Estado: sin incidencias.
    TXT;
// Desde PHP 7.3 la etiqueta de cierre puede ir sangrada: PHP elimina
// esa misma sangría de todas las líneas.

// ---------------------------------------------------------------------
// 5. FUNCIONES DE CADENA
//    Las versiones mb_* entienden UTF-8 (acentos, ñ). Las "clásicas"
//    trabajan con BYTES y fallan con caracteres no ingleses.
// ---------------------------------------------------------------------
$nombreModulo = '  módulo de hidroponía  ';
$recortado    = trim($nombreModulo);                 // quita espacios a ambos lados
$bytes        = strlen($recortado);                  // 22 (bytes: ó e í ocupan 2)
$caracteres   = mb_strlen($recortado);               // 20 (caracteres reales)
$mayusMal     = strtoupper($recortado);              // MóDULO DE HIDROPONíA  ✗
$mayusBien    = mb_strtoupper($recortado);           // MÓDULO DE HIDROPONÍA  ✓
$reemplazo    = str_replace('hidroponía', 'reciclaje', $recortado);
$contiene     = str_contains($recortado, 'hidro');   // PHP 8: true / false
$empiezaPor   = str_starts_with($recortado, 'mód');  // PHP 8

// Formato de números: number_format(numero, decimales, sep_decimal, sep_miles)
$tempFormateada = number_format($temperatura, 1, ',', '.');   // 21,5
// sprintf devuelve una cadena con formato (%s cadena, %d entero, %.2f decimal)
$lineaInforme = sprintf('%s mantiene %s a %.2f °C', $tripulante, $modulo, $temperatura);

// ---------------------------------------------------------------------
// 6. SEGURIDAD: el mensaje "saboteado"
//    Imagina que este texto lo ha escrito un usuario (en la UD4 llegará
//    desde un formulario). Contiene etiquetas HTML.
// ---------------------------------------------------------------------
$mensajeUsuario = 'Todo OK <b style="color:#ff5a44;font-size:2rem">¡MOTORES APAGADOS!</b>';
// Si lo pintamos tal cual, el navegador INTERPRETA las etiquetas.
// Con htmlspecialchars, < y > se convierten en &lt; y &gt; y se ven como texto.
$mensajeSeguro = htmlspecialchars($mensajeUsuario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARES-7 · Mensajes de la tripulación</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 04</span>
    <h1>Mensajes de la tripulación <small>Cadenas de texto</small></h1>

    <h2>1 · Simples frente a dobles</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <tr><td><code>'Hola $tripulante'</code></td><td><?= $literal ?></td></tr>
        <tr><td><code>"Hola $tripulante"</code></td><td><?= $interpolado ?></td></tr>
        <tr><td><code>"Módulo: {$modulo}s"</code></td><td><?= $conLlaves ?></td></tr>
        <tr><td><code>"Precio: \$15 \"aprox.\""</code></td><td><?= $conEscapes ?></td></tr>
    </table>

    <h2>2 · Concatenación</h2>
    <div class="panel">
        <p><?= $saludo ?></p>
        <p class="mono"><?= $registro ?></p>
    </div>

    <h2>3 · Heredoc: una entrada del diario</h2>
    <!-- <pre> respeta los saltos de línea del heredoc.
         Alternativa: nl2br(), que convierte \n en <br>. -->
    <pre><?= $entradaDiario ?></pre>

    <h2>4 · Funciones de cadena</h2>
    <table>
        <tr><th>Operación</th><th>Resultado</th></tr>
        <tr><td><code>trim()</code></td><td>"<?= $recortado ?>"</td></tr>
        <tr><td><code>strlen()</code> (bytes)</td><td><?= $bytes ?></td></tr>
        <tr><td><code>mb_strlen()</code> (caracteres)</td><td><?= $caracteres ?></td></tr>
        <tr><td><code>strtoupper()</code></td><td class="alerta"><?= $mayusMal ?></td></tr>
        <tr><td><code>mb_strtoupper()</code></td><td class="ok"><?= $mayusBien ?></td></tr>
        <tr><td><code>str_replace()</code></td><td><?= $reemplazo ?></td></tr>
        <!-- var_export devuelve la representación del valor como texto (true/false) -->
        <tr><td><code>str_contains(…, 'hidro')</code></td><td><?= var_export($contiene, true) ?></td></tr>
        <tr><td><code>str_starts_with(…, 'mód')</code></td><td><?= var_export($empiezaPor, true) ?></td></tr>
        <tr><td><code>number_format()</code></td><td><?= $tempFormateada ?> °C</td></tr>
        <tr><td><code>sprintf()</code></td><td><?= $lineaInforme ?></td></tr>
    </table>

    <h2>5 · El mensaje saboteado</h2>
    <div class="panel">
        <p><strong>Sin proteger:</strong> <?= $mensajeUsuario ?></p>
        <p><strong>Con htmlspecialchars:</strong> <?= $mensajeSeguro ?></p>
        <p class="nota">Si en lugar de una etiqueta &lt;b&gt; el "saboteador" hubiera
           escrito un &lt;script&gt;, el navegador lo habría EJECUTADO (ataque XSS).
           Regla del módulo: <strong>todo dato que no escribimos nosotros se pinta
           con htmlspecialchars()</strong>.</p>
    </div>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
