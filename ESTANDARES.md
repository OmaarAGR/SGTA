# Estándares del equipo — SGTA

> Proyecto SGTA: control de citas e inventario del Taller JMT. Stack: Laravel (PHP).
> Documento acordado por el equipo. Cualquier cambio requiere un PR aprobado por ambos integrantes.

**Roles durante la definición de estándares**

| Integrante | Roles |
|---|---|
| Omar Andrés Gutiérrez Rojas | Redactor · Responsable del repositorio |
| Joseph Hans Díaz González | Guardián de lo verificable · Abogado del diablo |

---

## 1. Guía de estilo y nombres

- **Guía oficial adoptada:** PSR-12 y las convenciones de la documentación oficial de Laravel.
- **Idioma:**
  - Código (clases, métodos, variables, tablas, columnas, rutas): **inglés**, para respetar las convenciones de Laravel (pluralización de tablas, relaciones, rutas de recurso).
  - Comentarios, documentación y textos de la interfaz: **español**.
  - Mensajes de commit: **inglés**.
- **Formateador configurado:** Laravel Pint con el preset `laravel`, configurado en `pint.json` en la raíz del repositorio.
  - Verificación: `./vendor/bin/pint --test` termina sin reportar archivos.

**Reglas propias de nombres**

1. **Modelos en singular y `PascalCase`; tablas en plural y `snake_case`; llaves foráneas `<modelo>_id`.**
   Ej.: modelo `Appointment` → tabla `appointments`; columna `vehicle_id`.
2. **Métodos empiezan con verbo en `camelCase`; booleanos empiezan con `is`, `has` o `can`.**
   Ej.: `calculateEndTime()`, `isBelowMinimum()`, `$hasOverlap`.
3. **Los términos del dominio se nombran siempre según este glosario** (nadie inventa sinónimos):

| Dominio (español) | Código (inglés) |
|---|---|
| Cliente | `Customer` |
| Vehículo | `Vehicle` |
| Bahía | `Bay` |
| Técnico | `Technician` |
| Cita | `Appointment` |
| Tipo de servicio | `ServiceType` |
| Registro de servicio | `ServiceRecord` |
| Repuesto | `Part` |
| Movimiento de inventario | `StockMovement` |
| Stock mínimo | `min_stock` |

Si aparece un término nuevo, se agrega al glosario en el mismo PR que lo usa.

---

## 2. Convención de commits y ramas

**Formato del mensaje** (Conventional Commits)

```
<tipo>(<alcance>): <description in English, imperative mood, lowercase, no trailing period> (#<id-tarea>)
```

Ejemplos:

```
feat(appointments): validate appointment overlap per bay (#12)
fix(inventory): deduct parts only when the service is closed (#27)
docs: add team standards
```

- El `(#<id-tarea>)` del tablero es **obligatorio** en `feat`, `fix`, `refactor` y `test` (trazabilidad tarea–commit). En `docs`, `style` y `chore` es opcional.
- **Alcances permitidos:** `customers`, `vehicles`, `appointments`, `inventory`, `services`, `auth`, `deploy`.

**Tipos permitidos**

| Tipo | Uso |
|---|---|
| `feat` | Nueva funcionalidad |
| `fix` | Corrección de un error |
| `docs` | Solo documentación |
| `style` | Formato, sin cambio de lógica |
| `refactor` | Cambio de código que no agrega funcionalidad ni corrige errores |
| `test` | Agregar o corregir pruebas |
| `chore` | Configuración, dependencias, migraciones de entorno, mantenimiento |

**Esquema de ramas** (GitHub Flow)

- `main`: siempre estable y desplegable. Protegida: **no se hace push directo**, solo merge vía PR.
- `feature/<id-tarea>-<descripcion-corta>`: nuevas funcionalidades. Ej.: `feature/12-cruce-citas`.
- `fix/<id-tarea>-<descripcion-corta>`: correcciones. Ej.: `fix/27-descuento-inventario`.
- Las ramas se eliminan después del merge.

---

## 3. Definition of Ready

Una tarea puede pasar a "En progreso" cuando:

1. Está escrita en el tablero como historia de usuario: *"Como <administrador | técnico> quiero … para …"*.
2. Tiene criterios de aceptación escritos que se pueden probar (ej.: *"si la bahía 2 está ocupada de 8:00 a 9:00, no se puede agendar otra cita a las 8:30 en esa bahía"*).
3. Está estimada en horas.
4. Está dentro del alcance del acta de constitución (no cae en ninguna exclusión).
5. Sus dependencias están resueltas o tienen datos de prueba definidos (ej.: listado de repuestos o seeders con repuestos de mayor rotación).
6. Tiene un responsable asignado en el tablero.

---

## 4. Definition of Done

Una tarea está terminada cuando:

| # | Condición | Cómo lo comprueba alguien que no estuvo |
|---|---|---|
| 1 | El código está en `main` mediante un PR aprobado por el otro integrante. | El PR aparece como *merged* con 1 aprobación de alguien distinto al autor. |
| 2 | El código cumple el formato del equipo. | `./vendor/bin/pint --test` no reporta archivos. |
| 3 | Todas las pruebas pasan y la funcionalidad nueva tiene al menos una prueba Feature. | `php artisan test` termina en verde; el PR incluye un archivo nuevo o modificado en `tests/Feature`. |
| 4 | La base de datos se construye desde cero sin errores. | `php artisan migrate:fresh --seed` termina sin errores. |
| 5 | Se cumplen los criterios de aceptación de la tarea. | En el PR se lista cada criterio con evidencia (prueba que lo cubre o captura de pantalla). |
| 6 | Los commits siguen la convención y referencian la tarea. | `git log` del PR: cada commit cumple el formato de la sección 2. |
| 7 | La tarea está cerrada en el tablero y enlazada al PR. | La tarjeta está en "Hecho" y contiene el enlace al PR. |

---

## 5. Política de revisión

- **Quién revisa:** el integrante que no escribió el código. Nadie aprueba su propio PR.
- **Plazo:** máximo **48 horas** desde que se abre el PR.
  - Si el revisor no puede revisar a tiempo (parciales, ausencia), lo avisa en el PR. Pasadas 72 horas sin revisión, el autor puede hacer merge **solo si** cumple las condiciones 2, 3 y 4 de la Definition of Done, y deja un comentario indicándolo. El revisor lo revisa después y cualquier hallazgo se corrige en un PR nuevo.
- **Qué bloquea el merge:**
  - Pint o las pruebas fallan.
  - No se cumple alguna condición de la Definition of Done.
  - Errores de lógica en reglas del negocio (cruce de citas, descuento de inventario, alertas de stock).
  - Credenciales, archivo `.env` o datos personales reales de clientes en el repositorio (Ley 1581 de 2012).
  - Rutas sin protección de autenticación o de rol cuando la funcionalidad lo requiere.
- **Qué NO bloquea:**
  - Preferencias personales de estilo no cubiertas por PSR-12, Pint o este documento.
  - Sugerencias de mejora o refactorización opcionales (se registran como tarea nueva en el tablero si se quieren hacer).
- **Cómo se comenta:**
  - Sobre el código, nunca sobre la persona.
  - Cada comentario empieza con un prefijo: `bloqueante:`, `sugerencia:` o `pregunta:`.
  - Un comentario `bloqueante:` explica el problema y, si es posible, propone una solución.
  - Solo quien abrió el comentario lo marca como resuelto.

---

## 6. Aceptación

- Omar Andrés Gutiérrez Rojas — conozco y acepto estos estándares.
- Joseph Hans Díaz González — conozco y acepto estos estándares.
