<?php
/**
 * =====================================================================
 *  PASO 03 · Inventario de a bordo                        CE 2.d · 2.g
 * =====================================================================
 *
 *  OBJETIVO: variables y TIPOS DE DATOS en PHP, y cómo se convierten.
 *
 *  IDEA CLAVE (frente a Java): PHP es de TIPADO DINÁMICO.
 *  - En Java el tipo lo pone el programador y es fijo:  int tripulantes = 6;
 *  - En PHP el tipo lo tiene el VALOR, no la variable:   $tripulantes = 6;
 *    y la misma variable puede contener después otro tipo distinto.
 *
 *  REGLAS PARA NOMBRAR VARIABLES
 *  - Empiezan por $ seguido de letra o guion bajo:  $nivel, $_temp
 *  - No pueden empezar por número:                   $2nivel  ✗
 *  - Distinguen mayúsculas/minúsculas:               $Nivel ≠ $nivel
 *  - Convención del módulo: camelCase y nombres que se explican solos.
 *
 *  HERRAMIENTAS PARA "MIRAR DENTRO" DE UNA VARIABLE
 *  - var_dump($x)        → tipo + valor (la más completa, para depurar)
 *  - get_debug_type($x)  → nombre del tipo (PHP 8, preferible a gettype)
 *  - is_int(), is_float(), is_string(), is_bool(), is_null(), is_numeric()
 */

// ---------------------------------------------------------------------
// 1. LOS CUATRO TIPOS ESCALARES + null
// ---------------------------------------------------------------------
$tripulantes   = 6;               // int    (entero)
$oxigenoPct    = 87.5;            // float  (decimal: se usa PUNTO, no coma)
$comandante    = 'Lucía Ortega';  // string (cadena)
$escudoActivo  = true;            // bool   (true / false, sin comillas)
$ultimaAlerta  = null;            // null   (la variable existe pero "no tiene valor")

// Los enteros admiten otras bases y separador de miles con _ (PHP 7.4+).
$distanciaKm   = 408_000;         // más legible que 408000
$codigoPuerta  = 0x1F;            // hexadecimal → 31
$permisos      = 0o755;           // octal (PHP 8.1+) → 493

// ---------------------------------------------------------------------
// 2. TIPADO DINÁMICO: la MISMA variable cambia de tipo
// ---------------------------------------------------------------------
$lectura = 42;          // ahora es int
$tipoAntes = get_debug_type($lectura);
$lectura = 'cuarenta y dos';   // ahora es string. En Java: error de compilación.
$tipoDespues = get_debug_type($lectura);

// ---------------------------------------------------------------------
// 3. CONVERSIÓN EXPLÍCITA (casting): nosotros decidimos el tipo
// ---------------------------------------------------------------------
// Los datos que llegan de fuera (formularios, URL...) SIEMPRE son string.
// Simulamos una lectura de sensor que llega como texto:
$sensorTexto = '23.8';

$comoFloat = (float) $sensorTexto;   // 23.8   (float)
$comoInt   = (int) $sensorTexto;     // 23     (int) → TRUNCA, no redondea
$comoBool  = (bool) $sensorTexto;    // true   (cualquier cadena no vacía salvo "0")
$conIntval = intval('0x1A', 16);     // 26     (intval admite base)

// ---------------------------------------------------------------------
// 4. CONVERSIÓN AUTOMÁTICA (type juggling): PHP decide por nosotros
// ---------------------------------------------------------------------
$suma = '5' + 3;          // 8  → PHP convierte '5' a número porque + es aritmético
$concatenacion = '5' . 3; // '53' → el punto concatena: convierte 3 a cadena

// ¿Qué valores se consideran FALSOS al convertir a bool?
// false, 0, 0.0, '' (cadena vacía), '0' y null. TODO lo demás es true.
// Ojo: la cadena '0.0' y la cadena 'false' son TRUE.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARES-7 · Inventario de a bordo</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="paso">PASO 03</span>
    <h1>Inventario de a bordo <small>Variables y tipos de datos</small></h1>

    <h2>1 · Tipos básicos</h2>
    <table>
        <tr><th>Variable</th><th>Valor</th><th>get_debug_type()</th></tr>
        <tr><td><code>$tripulantes</code></td><td><?= $tripulantes ?></td><td><?= get_debug_type($tripulantes) ?></td></tr>
        <tr><td><code>$oxigenoPct</code></td><td><?= $oxigenoPct ?></td><td><?= get_debug_type($oxigenoPct) ?></td></tr>
        <tr><td><code>$comandante</code></td><td><?= $comandante ?></td><td><?= get_debug_type($comandante) ?></td></tr>
        <!-- echo de true escribe "1"; echo de false y de null NO escriben NADA.
             Por eso, para depurar, usamos var_dump. -->
        <tr><td><code>$escudoActivo</code></td><td><?= $escudoActivo ?></td><td><?= get_debug_type($escudoActivo) ?></td></tr>
        <tr><td><code>$ultimaAlerta</code></td><td><?= $ultimaAlerta ?></td><td><?= get_debug_type($ultimaAlerta) ?></td></tr>
        <tr><td><code>$distanciaKm</code></td><td><?= $distanciaKm ?></td><td><?= get_debug_type($distanciaKm) ?></td></tr>
        <tr><td><code>$codigoPuerta</code> (0x1F)</td><td><?= $codigoPuerta ?></td><td><?= get_debug_type($codigoPuerta) ?></td></tr>
        <tr><td><code>$permisos</code> (0o755)</td><td><?= $permisos ?></td><td><?= get_debug_type($permisos) ?></td></tr>
    </table>
    <p class="nota">¿Por qué la celda de <code>$ultimaAlerta</code> está vacía y la de
       <code>$escudoActivo</code> muestra un 1? Porque <code>echo</code> convierte a cadena:
       true → "1", false → "", null → "".</p>

    <h2>2 · var_dump: la lupa del programador</h2>
    <pre><?php
        // var_dump escribe TIPO y VALOR. Va dentro de <pre> para respetar
        // los saltos de línea. Es la herramienta de depuración nº 1.
        var_dump($tripulantes);
        var_dump($oxigenoPct);
        var_dump($comandante);   // fíjate: string(13)... ¡cuenta BYTES, y la í ocupa 2!
        var_dump($escudoActivo);
        var_dump($ultimaAlerta);
    ?></pre>

    <h2>3 · Tipado dinámico</h2>
    <div class="panel">
        <p><code>$lectura</code> era <strong><?= $tipoAntes ?></strong> y ahora es
           <strong><?= $tipoDespues ?></strong>. Misma variable, distinto tipo.</p>
        <p class="nota">En Java esto no compila. En PHP es legal… y fuente de errores.
           En el paso 07 veremos la directiva strict_types, que hace que PHP
           deje de convertir tipos por su cuenta al llamar a funciones.</p>
    </div>

    <h2>4 · Conversiones del sensor (llega el texto "<?= $sensorTexto ?>")</h2>
    <pre><?php
        var_dump($comoFloat);   // float(23.8)
        var_dump($comoInt);     // int(23)   ← trunca los decimales
        var_dump($comoBool);    // bool(true)
        var_dump($conIntval);   // int(26)   ← '0x1A' leído en base 16
    ?></pre>

    <h2>5 · PHP convierte por su cuenta</h2>
    <pre><?php
        var_dump($suma);            // int(8)
        var_dump($concatenacion);   // string(2) "53"
        var_dump(is_numeric('23.8'));  // bool(true)  → es un "string numérico"
        var_dump(is_numeric('23,8'));  // bool(false) → la coma NO vale como decimal
        var_dump((bool) '0');          // bool(false) ← ¡cuidado!
        var_dump((bool) '0.0');        // bool(true)  ← ¡más cuidado!
    ?></pre>

    <footer><a href="index.php">← Volver al índice</a></footer>
</main>
</body>
</html>
