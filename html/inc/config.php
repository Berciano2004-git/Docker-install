<?php
/**
 * =====================================================================
 *  inc/config.php · Configuración y datos de sensores de ARES-7
 * =====================================================================
 *
 *  Fichero SOLO PHP (sin HTML). Por eso:
 *   - Empieza por la etiqueta de apertura y NO lleva etiqueta de cierre.
 *     Recomendación oficial (PSR-12): así se evita que un espacio o salto
 *     de línea tras el cierre se cuele en la respuesta sin querer.
 *   - Se carga desde otros scripts con require_once.
 *   - Nginx bloquea el acceso directo a /inc/ (ver nginx/default.conf).
 */

declare(strict_types=1);

// --- Constantes de la misión (no cambian) ----------------------------
const NOMBRE_ESTACION          = 'ARES-7';
const CONSUMO_O2_PERSONA_DIA_L = 550;     // litros/día por tripulante
const OBJETIVO_MISION_DIAS     = 7;       // días hasta el próximo reabastecimiento
const CAPACIDAD_TANQUE_KG      = 1000;    // combustible máximo
const BLOQUES_BARRA            = 10;      // longitud de las barras de progreso

// --- Lecturas de sensores (en la UD9 vendrán de una base de datos) ----
$comandante        = 'Lucía Ortega';
$tripulantes       = 6;
$reservaOxigenoL   = 12_600;
$combustibleKg     = 425.0;
$temperaturaIntC   = 21.456;
$ultimaAlerta      = null;
// Mensaje escrito por un tripulante: puede contener HTML "peligroso".
$mensajeDelDia     = 'Café recién hecho en el módulo 3 <3 ¡No lo dejéis enfriar!';
