<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/reglas.php';
require_once __DIR__ . '/inc/datos-' . VARIANTE . '.php';

// Calcula y guarda los datos derivados de cada héroe para reutilizarlos en la salida.
foreach ($party as $i => $heroe) {
    $nivel = intdiv($heroe['xp'], XP_POR_NIVEL) + 1;
    $vidaMax = VIDA_BASE + $heroe['constitucion'] * $nivel * MULT_VIDA[$heroe['clase']];
    $pctVida = $vidaMax > 0 ? $heroe['vida'] / $vidaMax * 100 : 0;

    // match selecciona la fórmula de daño propia de cada clase.
    $danio = match ($heroe['clase']) {
        'Guerrero' => $heroe['fuerza'] ** 2 / 10 + $nivel * 2,
        'Guerrera' => $heroe['fuerza'] ** 2 / 10 + $nivel * 2,
        'Hechicero' => $heroe['inteligencia'] * 3 + $nivel ** 2 / 4,
        'Pícara' => $heroe['destreza'] * 2 + $nivel ** 1.5,
        'Clérigo' => $heroe['inteligencia'] * 3 + $nivel * 0.5,
    };
    $poder = (int) round($danio * $nivel);

    // if/elseif/else determina el estado según la vida disponible.
    if ($heroe['vida'] <= 0) {
        $estado = 'K.O.';
    } elseif ($pctVida < 35) {
        $estado = 'En peligro';
    } elseif ($pctVida < 70) {
        $estado = 'Herido';
    } else {
        $estado = 'En pie';
    }

    // switch agrupa las clases que ocupan la primera línea.
    switch ($heroe['clase']) {
        case 'Guerrero':
        case 'Guerrera':
        case 'Clérigo':
            $posicion = 'Primera línea';
            break;
        case 'Pícara':
            $posicion = 'Flanco';
            break;
        case 'Hechicero':
            $posicion = 'Retaguardia';
            break;
        default:
            $posicion = 'Reserva';
    }

    // La barra se construye con for porque la práctica pide no usar str_repeat.
    $bloquesLlenos = (int) round(max(0, min(100, $pctVida)) / 100 * BLOQUES_BARRA);
    $barra = '';
    for ($bloque = 0; $bloque < BLOQUES_BARRA; $bloque++) {
        $barra .= $bloque < $bloquesLlenos ? '█' : '░';
    }

    $party[$i]['nivel'] = $nivel;
    $party[$i]['vidaMax'] = $vidaMax;
    $party[$i]['pctVida'] = $pctVida;
    $party[$i]['danio'] = $danio;
    $party[$i]['poder'] = $poder;
    $party[$i]['estado'] = $estado;
    $party[$i]['posicion'] = $posicion;
    $party[$i]['barra'] = $barra;
}

// array_column y array_sum calculan los totales de la party.
$vidaParty = array_sum(array_column($party, 'vida'));
$vidaMaxParty = array_sum(array_column($party, 'vidaMax'));
$poderParty = array_sum(array_column($party, 'poder'));
$nivelMedio = array_sum(array_column($party, 'nivel')) / count($party);

$danioParty = 0.0;
$heroesActivos = 0;
$lider = null;
foreach ($party as $heroe) {
    if ($heroe['vida'] <= 0) {
        continue;
    }
    $danioParty += $heroe['danio'];
    $heroesActivos++;
    if ($lider === null || $heroe['poder'] > $lider['poder']) {
        $lider = $heroe;
    }
}

$hayPicara = in_array('Pícara', array_column($party, 'clase'), true);
$hayClerigo = in_array('Clérigo', array_column($party, 'clase'), true);

// Recorre las salas, actualiza oro, experiencia, vida e inventario y escribe el diario.
$oro = $oroInicial;
$xpGanada = 0;
$diario = [];
$salasExploradas = 0;
foreach ($salas as $n => $sala) {
    $salasExploradas++;
    switch ($sala['tipo']) {
        case 'vacía':
            $diario[] = 'Sala ' . ($n + 1) . ': ' . $sala['nombre'] . ' está vacía. La party sigue avanzando.';
            continue 2;
        case 'trampa':
            if ($hayPicara) {
                $diario[] = 'Sala ' . ($n + 1) . ': ' . $sala['nombre'] . ' · la Pícara evita la trampa.';
            } else {
                $vidaParty -= $sala['valor'];
                $diario[] = 'Sala ' . ($n + 1) . ': ' . $sala['nombre'] . ' · trampa: -' . $sala['valor'] . ' de vida.';
            }
            break;
        case 'combate':
            $perdida = intdiv($sala['valor'], 4);
            $vidaParty -= $perdida;
            $xpGanada += $sala['valor'];
            $diario[] = 'Sala ' . ($n + 1) . ': ' . $sala['nombre'] . ' · combate: -' . $perdida . ' de vida y +' . $sala['valor'] . ' XP.';
            break;
        case 'tesoro':
            $oro += $sala['valor'];
            if (array_key_exists('objeto', $sala)) {
                $objeto = $sala['objeto'];
                $inventario[$objeto] = ($inventario[$objeto] ?? 0) + 1;
                $diario[] = 'Sala ' . ($n + 1) . ': ' . $sala['nombre'] . ' · +' . $sala['valor'] . ' mo y objeto: ' . $objeto . '.';
            } else {
                $diario[] = 'Sala ' . ($n + 1) . ': ' . $sala['nombre'] . ' · +' . $sala['valor'] . ' mo.';
            }
            break;
        case 'descanso':
            $curacion = $sala['valor'] * ($hayClerigo ? 2 : 1);
            $vidaParty = min($vidaMaxParty, $vidaParty + $curacion);
            $diario[] = 'Sala ' . ($n + 1) . ': ' . $sala['nombre'] . ' · descanso: +' . $curacion . ' de vida.';
            break;
    }
    if ($vidaParty <= 0) {
        $vidaParty = 0;
        $diario[] = 'La party ha quedado sin vida y abandona la expedición.';
        break;
    }
}
$salasPendientes = count($salas) - $salasExploradas;

// Resuelve la entrada al jefe y, si procede, el combate por rondas.
$tieneLlave = array_key_exists($jefe['llave'], $inventario);
$golpe = (int) round($danioParty);
$rondas = [];
$ronda = 0;
$vidaJefe = $jefe['vida'];
$resultado = '';
$motivo = '';
if ($vidaParty <= 0) {
    $resultado = 'Derrota';
    $motivo = 'La party no llegó con vida a la guarida.';
} elseif (!$tieneLlave) {
    $resultado = 'Puerta sellada';
    $motivo = 'Falta la llave necesaria para entrar.';
} else {
    while ($vidaJefe > 0 && $vidaParty > 0 && $ronda < MAX_RONDAS) {
        $ronda++;
        $vidaJefe = max(0, $vidaJefe - $golpe);
        if ($vidaJefe > 0) {
            $vidaParty = max(0, $vidaParty - $jefe['ataque']);
        }
        $rondas[] = ['ronda' => $ronda, 'vidaJefe' => $vidaJefe, 'vidaParty' => $vidaParty];
    }
    $resultado = match (true) {
        $vidaJefe <= 0 => 'Victoria',
        $vidaParty <= 0 => 'Derrota',
        $ronda >= MAX_RONDAS => 'Retirada',
        default => 'Retirada',
    };
    $motivo = $resultado === 'Victoria' ? 'La party derrota al jefe.' : 'El combate termina antes de derrotar al jefe.';
    if ($resultado === 'Victoria') {
        $oro += $jefe['recompensa'];
    }
}

// Calcula el valor del inventario y ordena una copia por cantidad descendente.
$valorInventario = 0.0;
foreach ($inventario as $objeto => $cantidad) {
    $valorInventario += $cantidad * ($precios[$objeto] ?? 0.0);
}
$totalObjetos = array_sum($inventario);
$inventarioOrdenado = $inventario;
arsort($inventarioOrdenado);
$objetoMasAbundante = array_key_first($inventarioOrdenado);

// Cuenta las celdas del mapa y comprueba que el número de salas coincide.
$conteoCeldas = [];
for ($fila = 0; $fila < count($mapa); $fila++) {
    for ($columna = 0; $columna < count($mapa[$fila]); $columna++) {
        $celda = $mapa[$fila][$columna];
        $conteoCeldas[$celda] = ($conteoCeldas[$celda] ?? 0) + 1;
    }
}
$mapaCoherente = ($conteoCeldas['S'] ?? 0) === count($salas);
$bloquesParty = (int) round(max(0, min(100, $vidaParty / $vidaMaxParty * 100)) / 100 * BLOQUES_BARRA);
$barraParty = '';
for ($bloque = 0; $bloque < BLOQUES_BARRA; $bloque++) {
    $barraParty .= $bloque < $bloquesParty ? '█' : '░';
}
$hora = date('d/m/Y H:i:s');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expedición · <?= htmlspecialchars($nombreMazmorra, ENT_QUOTES, 'UTF-8') ?> · Forja de Héroes II</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="insignia">VARIANTE <?= VARIANTE ?></span>
    <h1><?= htmlspecialchars($nombreMazmorra, ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="subtitulo">Informe de expedición · líder: <?= $lider === null ? 'ninguno' : htmlspecialchars($lider['nombre'], ENT_QUOTES, 'UTF-8') ?></p>

    <h2>La party</h2>
    <table>
        <tr><th>Héroe</th><th>Clase</th><th>Nivel</th><th>Vida</th><th>Daño</th><th>Poder</th><th>Posición</th><th>Estado</th></tr>
        <?php foreach ($party as $heroe): ?>
            <tr>
                <td><?= htmlspecialchars($heroe['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($heroe['clase'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $heroe['nivel'] ?></td>
                <td><span class="barra mini"><?= $heroe['barra'] ?></span> <?= $heroe['vida'] ?>/<?= $heroe['vidaMax'] ?></td>
                <td><?= number_format($heroe['danio'], 1, ',', '.') ?></td>
                <td><?= $heroe['poder'] ?></td>
                <td><?= $heroe['posicion'] ?></td>
                <?php if ($heroe['estado'] === 'K.O.' || $heroe['estado'] === 'En peligro'): ?>
                    <td class="peligro"><?= $heroe['estado'] ?></td>
                <?php else: ?>
                    <td class="vida"><?= $heroe['estado'] ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="rejilla">
        <div class="stat"><div class="etq">Poder total</div><div class="val"><?= $poderParty ?></div></div>
        <div class="stat"><div class="etq">Nivel medio</div><div class="val"><?= number_format($nivelMedio, 1, ',', '.') ?></div></div>
        <div class="stat"><div class="etq">Daño por ronda</div><div class="val"><?= number_format($danioParty, 1, ',', '.') ?></div></div>
        <div class="stat"><div class="etq">Héroes activos</div><div class="val"><?= $heroesActivos ?> / <?= count($party) ?></div></div>
    </div>

    <h2>Diario de la expedición</h2>
    <ol class="diario">
        <?php foreach ($diario as $linea): ?>
            <li><?= htmlspecialchars($linea, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ol>
    <p>Salas exploradas: <?= $salasExploradas ?> de <?= count($salas) ?><?php if ($salasPendientes > 0): ?> · <span class="peligro">quedan <?= $salasPendientes ?> pendientes</span><?php endif; ?> · XP ganada: <?= $xpGanada ?></p>

    <h2>El jefe: <?= htmlspecialchars($jefe['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>
    <p>Vida <?= $jefe['vida'] ?> · ataque <?= $jefe['ataque'] ?> · golpe de la party: <?= $golpe ?> por ronda</p>
    <?php if (count($rondas) > 0): ?>
        <table>
            <tr><th>Ronda</th><th>Vida del jefe</th><th>Vida de la party</th></tr>
            <?php foreach ($rondas as $fila): ?>
                <tr><td><?= $fila['ronda'] ?></td><td><?= $fila['vidaJefe'] ?></td><td><?= $fila['vidaParty'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <p class="resultado <?= $vidaParty <= 0 ? 'peligro' : 'vida' ?>"><strong><?= $resultado ?></strong> · <?= htmlspecialchars($motivo, ENT_QUOTES, 'UTF-8') ?></p>
    <p class="barra vida">PARTY <?= $barraParty ?> <?= $vidaParty ?>/<?= $vidaMaxParty ?></p>

    <h2>Inventario</h2>
    <table>
        <tr><th>Objeto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        <?php foreach ($inventarioOrdenado as $objeto => $cantidad): ?>
            <tr><td><?= htmlspecialchars($objeto, ENT_QUOTES, 'UTF-8') ?></td><td><?= $cantidad ?></td><td><?= number_format($precios[$objeto] ?? 0, 2, ',', '.') ?> mo</td><td><?= number_format($cantidad * ($precios[$objeto] ?? 0), 2, ',', '.') ?> mo</td></tr>
        <?php endforeach; ?>
    </table>
    <div class="rejilla">
        <div class="stat"><div class="etq">Objetos</div><div class="val"><?= $totalObjetos ?></div></div>
        <div class="stat"><div class="etq">Valor del inventario</div><div class="val"><?= number_format($valorInventario, 2, ',', '.') ?> mo</div></div>
        <div class="stat"><div class="etq">Oro final</div><div class="val"><?= number_format($oro, 2, ',', '.') ?> mo</div></div>
        <div class="stat"><div class="etq">Más abundante</div><div class="val peq"><?= htmlspecialchars((string) $objetoMasAbundante, ENT_QUOTES, 'UTF-8') ?></div></div>
    </div>

    <h2>Mapa de la mazmorra</h2>
    <table class="mapa">
        <?php foreach ($mapa as $fila): ?>
            <tr>
                <?php foreach ($fila as $celda): ?>
                    <td class="<?= LEYENDA_MAPA[$celda][0] ?>" title="<?= LEYENDA_MAPA[$celda][1] ?>"><?= $celda ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>Leyenda: <?php foreach ($conteoCeldas as $celda => $cantidad): ?><?= $celda ?> = <?= $cantidad ?> · <?php endforeach; ?></p>
    <?php if ($mapaCoherente): ?>
        <p class="vida">El mapa es coherente: sus salas coinciden con la mazmorra.</p>
    <?php else: ?>
        <p class="peligro">El mapa no coincide con el número de salas.</p>
    <?php endif; ?>

    <footer>Informe generado el <?= $hora ?> con PHP <?= PHP_VERSION ?> · cada recarga lo recalcula todo desde cero.</footer>
</main>
</body>
</html>
