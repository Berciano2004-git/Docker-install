<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/heroe.php';

$fuerza = (int) $fuerzaTxt;
$destreza = (int) $destrezaTxt;
$inteligencia = (int) $inteligenciaTxt;
$constitucion = (int) $constitucionTxt;
$experiencia = (int) $experienciaTxt;
$vidaActual = (int) $vidaActualTxt;
$oro = (float) $oroTxt;

$nivel = intdiv($experiencia, XP_POR_NIVEL) + 1;
$xpEnNivel = $experiencia % XP_POR_NIVEL;
$xpParaSubir = XP_POR_NIVEL - $xpEnNivel;
$pctNivel = $xpEnNivel / XP_POR_NIVEL * 100;

$vidaMax = VIDA_BASE + $constitucion * $nivel * MULT_VIDA;
$pctVida = $vidaActual / $vidaMax * 100;
$danio = $fuerza ** 2 / 10 + $nivel * 2;
$estadisticaEspecial = ($fuerza % 5) * 3;
$poder = (int) round($danio * $nivel);
$comparacion = $poder <=> PODER_RIVAL;
$veredicto = $comparacion === 1
    ? 'Ventaja: ¡a la carga!'
    : ($comparacion === 0 ? 'Empate: combate igualado' : 'Desventaja: mejor retirarse');

$estado = $vidaActual > 0 ? ($pctVida < 35 ? 'En peligro' : 'En pie') : 'K.O.';
$puedeAscender = $nivel >= 5 && $pctVida >= 50;
$necesitaPocion = $pctVida < 40 || !$esVeterano;
$insignia = $esVeterano ? 'VETERANO' : 'NOVATO';

$bloquesVida = (int) round($pctVida / 100 * BLOQUES_BARRA);
$bloquesXp = (int) round($pctNivel / 100 * BLOQUES_BARRA);
$barraVida = str_repeat('█', $bloquesVida) . str_repeat('░', BLOQUES_BARRA - $bloquesVida);
$barraXp = str_repeat('█', $bloquesXp) . str_repeat('░', BLOQUES_BARRA - $bloquesXp);

$nombreSeguro = htmlspecialchars($nombreHeroe, ENT_QUOTES, 'UTF-8');
$apodoVisible = $apodo ?? 'Sin apodo';
$lemaVisible = $lema ?: 'Sin lema (todavía)';
$oroVisible = number_format($oro, 2, ',', '.') . ' mo';
$danioVisible = number_format($danio, 1, ',', '.');
$pctVidaVisible = number_format($pctVida, 1, ',', '.');
$hora = date('d/m/Y H:i:s');
$nombreRival = NOMBRE_RIVAL;

$registro = '[HORA] ' . $hora;
$registro .= ' · HÉROE: ' . $nombreSeguro;
$registro .= ' · NIVEL: ' . $nivel;
$registro .= ' · RIVAL: ' . NOMBRE_RIVAL;
$registro .= ' · ' . $veredicto;

$cronica = <<<CRONICA
$nombreSeguro, guerrero de nivel $nivel, avanza hacia $nombreRival.
Le faltan $xpParaSubir puntos de experiencia para subir de nivel.
Su estado actual es: $estado.
CRONICA;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha de <?= $nombreSeguro ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main>
    <span class="insignia"><?php echo $insignia; ?></span>
    <h1><?= $nombreSeguro ?></h1>
    <p class="subtitulo"><?= CLASE_HEROE ?> · «<?= htmlspecialchars($apodoVisible, ENT_QUOTES, 'UTF-8') ?>» · <?= htmlspecialchars($lemaVisible, ENT_QUOTES, 'UTF-8') ?></p>

    <h2>Estadísticas</h2>
    <div class="rejilla">
        <div class="stat"><div class="etq">Nivel</div><div class="val"><?= $nivel ?></div></div>
        <div class="stat"><div class="etq">Vida</div><div class="val"><?= $vidaActual ?> / <?= $vidaMax ?></div></div>
        <div class="stat"><div class="etq">Daño</div><div class="val"><?= $danioVisible ?></div></div>
        <div class="stat"><div class="etq">Crítico</div><div class="val"><?= $estadisticaEspecial ?> %</div></div>
        <div class="stat"><div class="etq">Poder</div><div class="val"><?= $poder ?></div></div>
        <div class="stat"><div class="etq">Oro</div><div class="val"><?= $oroVisible ?></div></div>
    </div>

    <table>
        <tr><th>Fuerza</th><th>Destreza</th><th>Inteligencia</th><th>Constitución</th></tr>
        <tr><td><?= $fuerza ?></td><td><?= $destreza ?></td><td><?= $inteligencia ?></td><td><?= $constitucion ?></td></tr>
    </table>

    <h2>Progreso</h2>
    <p class="barra vida">VIDA <?= $barraVida ?> · <?= $pctVidaVisible ?> %</p>
    <p class="barra xp">XP&nbsp;&nbsp; <?= $barraXp ?> · <?= $xpEnNivel ?>/<?= XP_POR_NIVEL ?> (faltan <?= $xpParaSubir ?>)</p>

    <h2>Estado y decisiones</h2>
    <table>
        <tr><td>Estado</td><td><?= $estado ?></td></tr>
        <tr><td>¿Puede ascender de rango?</td><td><?= $puedeAscender ? 'Sí' : 'No' ?></td></tr>
        <tr><td>¿Necesita poción?</td><td><?= $necesitaPocion ? 'Sí' : 'No' ?></td></tr>
        <tr><td>Rival: <?= NOMBRE_RIVAL ?> (poder <?= PODER_RIVAL ?>)</td><td><?= $comparacion ?> → <?= $veredicto ?></td></tr>
    </table>

    <h2>Crónica</h2>
    <pre><?= htmlspecialchars($cronica, ENT_QUOTES, 'UTF-8') ?></pre>
    <p><em><?= htmlspecialchars($registro, ENT_QUOTES, 'UTF-8') ?></em></p>

    <h2>Modo depuración</h2>
    <pre><?php
        echo 'experienciaTxt: ' . get_debug_type($experienciaTxt) . PHP_EOL;
        echo 'experiencia: ' . get_debug_type($experiencia) . PHP_EOL;
        var_dump($oro, $pctVida, $esVeterano, $apodo);
        echo 'experienciaTxt == experiencia: ' . (($experienciaTxt == $experiencia) ? 'true' : 'false') . PHP_EOL;
        echo 'experienciaTxt === experiencia: ' . (($experienciaTxt === $experiencia) ? 'true' : 'false') . PHP_EOL;
    ?></pre>

    <footer><?= $hora ?> · PHP <?= PHP_VERSION ?> · <a href="diagnostico.php">Diagnóstico del servidor</a></footer>
</main>
</body>
</html>
