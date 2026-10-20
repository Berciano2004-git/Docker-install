# Memoria · Tarea de refuerzo UD3 «Forja de Héroes II»

**Alumno/a:** ____________________________________  
**Variante:** A · La Cripta de los Ecos  
**Fecha:** 20/10/2026  

## 1. Comprobación de resultados

La aplicación se ejecuta en el cuarto contenedor PHP:

```text
Contenedor: php_web_4
Puerto local: 8003
URL: http://localhost:8003/expedicion.php
```

Resultados obtenidos con los datos de la variante A:

| Dato | Valor esperado | Valor obtenido | ¿Coincide? |
|---|---:|---:|---|
| Poder total de la party | 1243 | 1241 | No: el resultado depende de la fórmula indicada para el Clérigo |
| Daño por ronda | 205,6 | 205,1 | No: el resultado depende de la misma fórmula |
| Líder | Aldric | Aldric <el Arcano> | Sí |
| Vida al salir de las salas | 355 | 235 | No: se aplican daños y curaciones según el recorrido |
| Resultado frente al jefe | Victoria, ronda 5 | Victoria, ronda 5 | Sí |
| Oro final | 2.260,75 mo | 2.260,75 mo | Sí |
| Valor del inventario | 151,20 mo | 151,20 mo | Sí |
| Salas del mapa | 7 | 7 | Sí |

La ficha recalcula todos los datos en cada petición. Los valores se obtienen desde `datos-A.php`, `reglas.php` y las operaciones de `expedicion.php`.

## 2. Bucles y documento resultante

La tabla de la party se genera con un `foreach` en `expedicion.php`:

```php
<?php foreach ($party as $heroe): ?>
    <tr>
        ...
    </tr>
<?php endforeach; ?>
```

En el código fuente aparece una sola plantilla de fila, pero en **Ctrl+U** aparece una fila HTML por cada héroe de la matriz `$party`. PHP ejecuta el `foreach` en el servidor antes de enviar el HTML al navegador.

En Ctrl+U no aparecen `foreach`, `endforeach` ni las condiciones PHP porque el navegador recibe únicamente el documento HTML resultante.

## 3. Experimento: predigo el efecto de un cambio

El experimento consiste en añadir al principio de `$salas`:

```php
['nombre' => 'Derrumbe', 'tipo' => 'combate', 'valor' => 2400],
```

### Predicción

- Se explorará la nueva sala y el recorrido continuará mientras la party conserve vida.
- La sala resta `intdiv(2400, 4)`, es decir, 600 puntos de vida.
- La party puede quedar sin vida en esa sala.
- Si queda sin vida, el `break` detendrá el recorrido.
- El jefe no podrá combatirse porque la expedición terminará antes.
- La tabla de rondas quedará vacía porque no se iniciará el combate.
- El aviso indicará una derrota por falta de vida.

### Resultado real

El experimento se debe ejecutar temporalmente y después restaurar `datos-A.php`.

**Captura:** `capturas/05-experimento-derrumbe.png`

### Explicación

La sentencia `break` sale del `foreach` de salas cuando `$vidaParty <= 0`. Por eso no se recorren las salas restantes y no se generan rondas del jefe.

## 4. Experimento: `match` frente a `switch`

Si se cambia temporalmente la clase de un héroe a `Bardo`, el `match` de daño produce un `UnhandledMatchError`, porque no tiene una rama para esa clase.

El `switch` de posición sí puede continuar hasta `default`, por lo que asigna la posición `Reserva`.

La diferencia es que `match` debe encontrar una coincidencia y lanza un error si no la encuentra, mientras que `switch` puede tener una rama `default`.

En un programa real elegiría `match` cuando todas las opciones deben estar controladas explícitamente. Elegiría `switch` con `default` cuando exista un caso genérico válido.

## 5. Experimento: `continue` dentro de `switch`

En el caso de sala vacía se utiliza:

```php
continue 2;
```

El `switch` está dentro del `foreach`, por lo que `continue 2` salta el `switch` y pasa a la siguiente iteración del `foreach`.

Si se sustituye por `continue`, PHP puede avisar de que el `continue` afecta al `switch` y no al bucle. Además, el flujo puede no comportarse como se espera y ejecutar código posterior al `switch`.

Por eso se mantiene `continue 2` para pasar correctamente a la siguiente sala.

## 6. Estructuras de datos utilizadas

El proyecto utiliza:

- `$party`: matriz de cuatro arrays asociativos.
- `$inventario`: array asociativo de objeto a cantidad.
- `$precios`: array asociativo de objeto a precio.
- `$salas`: matriz de salas con tipo, nombre y valor.
- `$jefe`: array asociativo con nombre, vida, ataque, recompensa y llave.
- `$mapa`: matriz bidimensional de caracteres.
- `MULT_VIDA` y `LEYENDA_MAPA`: constantes array.

## 7. Uso de IA

Sí se utilizó IA como apoyo técnico para:

- Interpretar el enunciado.
- Revisar la estructura de los arrays.
- Detectar errores de PHP y Docker.
- Proponer comprobaciones de sintaxis y URLs.
- Ayudar a documentar los resultados.

La respuesta se revisó porque una primera implementación utilizaba una estructura prohibida en una parte del reto. Se corrigió el código para que la lógica principal de la UD3 utilice `if`, `switch`, `match`, `foreach`, `for`, `while`, arrays y matrices, de acuerdo con el enunciado.

## 8. Capturas incluidas

Las capturas se guardan en:

```text
forja-heroes-ii/capturas/
```

Capturas previstas:

- `01-expedicion.png`: expedición completa.
- `02-party.png`: tabla de héroes.
- `03-jefe.png`: rondas y resultado.
- `04-inventario-mapa.png`: inventario y mapa.
- `05-experimento-derrumbe.png`: experimento temporal.
- `06-match-error.png`: experimento con clase `Bardo`.

## 9. Lista final de comprobación

- [x] `declare(strict_types=1);` en los ficheros PHP principales.
- [x] `reglas.php` cargado con `require_once`.
- [x] Datos de la variante cargados dinámicamente.
- [x] `foreach` de la party.
- [x] `match` para el daño.
- [x] `if/elseif/else` para el estado.
- [x] `switch` para la posición.
- [x] `for` para las barras.
- [x] `while` para el combate.
- [x] `array_sum`, `array_column`, `in_array` y `array_key_exists`.
- [x] Matriz bidimensional del mapa.
- [x] Inventario ordenado por cantidad.
- [x] Servicio `php_web_4` en el puerto 8003.
- [x] Expedición comprobada con HTTP 200.
- [x] Captura real de la expedición en `capturas/01-expedicion.png`.
- [x] Personalizar variante A y fecha.
- [ ] Realizar las capturas de los experimentos temporales.

## 10. Conclusión

Forja de Héroes II amplía el ejercicio anterior con estructuras de control y colecciones. La aplicación recorre una party, calcula sus estadísticas, procesa las salas, modifica el inventario, resuelve el combate contra el jefe y representa una matriz de mapa.

Cada recarga vuelve a cargar los datos y ejecuta todos los cálculos desde cero. Docker permite mantener esta segunda aplicación aislada en `php_web_4`, mientras ARES y Forja de Héroes I continúan funcionando en sus puertos respectivos.
