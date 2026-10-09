<?php
/**
 * =====================================================================
 *  PASO 05 · Soporte vital                                      CE 2.g
 * =====================================================================
 *
 *  OBJETIVO: OPERADORES de PHP. Casi todos son iguales que en Java;
 *  nos centramos en las DIFERENCIAS, que es donde están los errores:
 *    - División / que devuelve float, intdiv() para la entera
 *    - Potencia **
 *    - Concatenación . (no +)
 *    - == frente a ===  (¡crucial en PHP!)
 *    - Nave espacial <=>
 *    - Ternario ?:, "Elvis" ?:  y fusión de null ??
 *
 *  Todavía NO usamos if ni bucles (llegan en la UD3). Todo se resuelve
 *  con expresiones y operadores.
 */

// ---------------------------------------------------------------------
// DATOS DE SOPORTE VITAL
// ---------------------------------------------------------------------
$tripulantes        = 6;
$reservaOxigenoL    = 12_600;  // litros de oxígeno en reserva
$consumoPersonaDiaL = 550;     // litros por tripulante y día
$radioPanelM        = 3;       // radio del panel solar circular (m)

// ---------------------------------------------------------------------
// 1. ARITMÉTICOS  + - * / % **  e intdiv()
// ---------------------------------------------------------------------
$consumoDiario   = $tripulantes * $consumoPersonaDiaL;         // 3300
$diasExactos     = $reservaOxigenoL / $consumoDiario;          // 3.8181... → / SIEMPRE puede dar float
$diasCompletos   = intdiv($reservaOxigenoL, $consumoDiario);   // 3  → división entera (en Java: 12600 / 3300)
$litrosSobrantes = $reservaOxigenoL % $consumoDiario;          // 2700 → resto (módulo)
$areaPanel       = M_PI * $radioPanelM ** 2;                   // ** es la potencia (en Java: Math.pow)
// Precedencia: ** antes que *, y * antes que +. Ante la duda: paréntesis.

// ---------------------------------------------------------------------
// 2. INCREMENTO / DECREMENTO (igual que en Java)
// ---------------------------------------------------------------------
$contador = 5;
$post = $contador++;   // $post = 5 y DESPUÉS $contador pasa a 6
$pre  = ++$contador;   // $contador pasa a 7 y DESPUÉS $pre = 7

// ---------------------------------------------------------------------
// 3. ASIGNACIÓN COMPUESTA  += -= *= /= %= **= .= ??=
// ---------------------------------------------------------------------
$combustible = 1000;
$combustible -= 150;    // 850  (maniobra de corrección)
$combustible *= 2;      // 1700 (repostaje: se duplica)
$combustible /= 4;      // 425  (425 es int... ¿o float? Míralo con var_dump abajo)

// ---------------------------------------------------------------------
// 4. COMPARACIÓN   == (igual valor)   === (igual valor Y tipo)
// ---------------------------------------------------------------------
// == convierte los tipos antes de comparar ("comparación débil").
// === no convierte nada ("comparación estricta"). REGLA DEL MÓDULO: usar ===.
$lecturaSensor = '6';   // llega como string
$debil   = ($lecturaSensor == $tripulantes);    // true  → '6' se convierte a 6
$estricta = ($lecturaSensor === $tripulantes);  // false → string frente a int

// Nave espacial <=> : devuelve -1, 0 o 1 (menor, igual, mayor).
// Muy útil para ordenar (lo usaremos en la UD3 con arrays).
$comparaA = 3 <=> 5;    // -1
$comparaB = 5 <=> 5;    //  0
$comparaC = 7 <=> 5;    //  1

// ---------------------------------------------------------------------
// 5. LÓGICOS  &&  ||  !   (and / or existen pero tienen OTRA precedencia)
// ---------------------------------------------------------------------
$oxigenoOk = $diasCompletos >= 3;
$energiaOk = $areaPanel > 25;
$todoOk    = $oxigenoOk && $energiaOk;
$algunFallo = !$oxigenoOk || !$energiaOk;

// TRAMPA DE PRECEDENCIA: "and" tiene MENOS prioridad que "=".
$trampa = true and false;   // se evalúa como ($trampa = true) and false → $trampa vale true
$correcto = (true && false); // false. Usa siempre && y ||.

// ---------------------------------------------------------------------
// 6. TERNARIO, ELVIS y FUSIÓN DE NULL
// ---------------------------------------------------------------------
// Ternario:  condición ? valor_si_true : valor_si_false
$estadoOxigeno = $diasCompletos >= 3 ? 'NOMINAL' : 'CRÍTICO';

// Elvis ?: → devuelve el primer operando si es "verdadero"; si no, el segundo.
$apodo = '';
$nombreVisible = $apodo ?: 'Tripulante sin apodo';

// Fusión de null ?? → devuelve el primero si EXISTE y NO es null; si no, el segundo.
// Diferencia con ?: → ?? solo mira null; ?: mira "falsedad" ('' , 0, '0'...).
$ultimaAlerta = null;
$textoAlerta = $ultimaAlerta ?? 'Sin alertas registradas';
$nivelCero = 0;
$conElvis = $nivelCero ?: 'por defecto';   // 'por defecto' (0 es "falso")
$conFusion = $nivelCero ?? 'por defecto';  // 0 (0 no es null)

// ??= asigna solo si la variable es null o no existe.
$frecuenciaRadio = null;
$frecuenciaRadio ??= 145.8;

// ---------------------------------------------------------------------
// 7. APLICACIÓN: barra de oxígeno con operadores y funciones
// ---------------------------------------------------------------------
$porcentajeReserva = $diasExactos / 7 * 100;          // objetivo de la misión: 7 días
$bloquesLlenos = (int) round($porcentajeReserva / 10); // de 0 a 10 bloques
// str_repeat(cadena, veces) repite una cadena. "veces" debe ser int.
$barra = str_repeat('█', $bloquesLlenos) . str_repeat('░', 10 - $bloquesLlenos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARES-7 · Soporte vital</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 05</span>
    <h1>Soporte vital <small>Operadores</small></h1>

    <div class="rejilla">
        <div class="dato"><div class="etiqueta">Consumo diario</div>
            <div class="valor"><?= number_format($consumoDiario, 0, ',', '.') ?> L</div></div>
        <div class="dato"><div class="etiqueta">Autonomía</div>
            <div class="valor"><?= $diasCompletos ?> días</div></div>
        <div class="dato"><div class="etiqueta">Estado O₂</div>
            <div class="valor <?= $diasCompletos >= 3 ? 'ok' : 'alerta' ?>"><?= $estadoOxigeno ?></div></div>
        <div class="dato"><div class="etiqueta">Panel solar</div>
            <div class="valor"><?= number_format($areaPanel, 2, ',', '.') ?> m²</div></div>
    </div>
    <p class="barra">O₂ <?= $barra ?> <?= round($porcentajeReserva) ?>% del objetivo</p>

    <h2>1 · Aritméticos</h2>
    <pre><?php
        var_dump($diasExactos);      // float
        var_dump($diasCompletos);    // int
        var_dump($litrosSobrantes);  // int
        var_dump($combustible);      // int(425): / da int si la división es EXACTA
        var_dump(10 / 4);            // float(2.5)
    ?></pre>

    <h2>2 · Incremento</h2>
    <p class="mono">$post = <?= $post ?> · $pre = <?= $pre ?> · $contador = <?= $contador ?></p>

    <h2>3 · Comparación: == frente a ===</h2>
    <pre><?php
        var_dump($debil);        // bool(true)
        var_dump($estricta);     // bool(false)
        // Casos "curiosos" de la comparación débil (PHP 8):
        var_dump('1' == '01');   // true  → ambos son strings numéricos: se comparan como números
        var_dump(100 == '1e2');  // true  → '1e2' es notación científica = 100
        var_dump(0 == 'abc');    // false en PHP 8 (¡en PHP 7 era true!)
        var_dump(null == false); // true
        var_dump(null === false);// false
    ?></pre>
    <p class="mono">3 &lt;=&gt; 5 = <?= $comparaA ?> · 5 &lt;=&gt; 5 = <?= $comparaB ?> · 7 &lt;=&gt; 5 = <?= $comparaC ?></p>

    <h2>4 · Lógicos</h2>
    <pre><?php
        var_dump($todoOk);
        var_dump($algunFallo);
        var_dump($trampa);     // bool(true)  ← la trampa de "and"
        var_dump($correcto);   // bool(false)
    ?></pre>

    <h2>5 · Ternario, Elvis y ??</h2>
    <table>
        <tr><th>Expresión</th><th>Resultado</th></tr>
        <tr><td><code>$apodo ?: 'Tripulante sin apodo'</code></td><td><?= $nombreVisible ?></td></tr>
        <tr><td><code>$ultimaAlerta ?? 'Sin alertas…'</code></td><td><?= $textoAlerta ?></td></tr>
        <tr><td><code>0 ?: 'por defecto'</code></td><td><?= $conElvis ?></td></tr>
        <tr><td><code>0 ?? 'por defecto'</code></td><td><?= $conFusion ?></td></tr>
        <tr><td><code>$frecuenciaRadio ??= 145.8</code></td><td><?= $frecuenciaRadio ?> MHz</td></tr>
    </table>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
