# Instrumento Integral de Evaluación Técnica, UI/UX y Reglas de Negocio para Aplicativos Web

> **Tipo de Auditoría:** Revisión Académica entre Pares (Peer Review Crítico)  
> **Comité Evaluador:** Grupo 5 (Auditoría de Calidad de Software & Experiencia de Usuario)  
> **Proyecto y Equipo Evaluado:** Grupo 6 — *TechnoStore Huila* (`technostore.nexohuila.com`)  
> **Instrumento Base:** `Instrumento_Evaluacion_Aplicativos_Web.xlsx`  
> **Calificación Consolidada:** **4.04 / 5.00 — Nivel: Bueno (Aprobado Ordinario)**  
> **Condición:** *Despliegue condicionado al levantamiento de observaciones críticas de diseño, saturación visual, errores de maquetación en el footer y control de concurrencia en la billetera.*

---

## 1. Ficha Técnica de la Evaluación

| Parámetro                    | Detalle de la Auditoría                                                                 |
|:---------------------------- |:--------------------------------------------------------------------------------------- |
| **Nombre del Aplicativo**    | **TechnoStore Huila**                                                                   |
| **Equipo Desarrollador**     | **Grupo 6** (Deiner Limas, Juan Silva, Danna Farfán, Cristian Alegría, Kevin Caballero) |
| **Equipo Auditor**           | **Grupo 5** (Aprendices ADSO - Ficha 3407799)                                           |
| **Enfoque de la Evaluación** | **Auditoría de Interfaz (UI), Ergonomía de Usuario (UX) y Navegación Prolongada.**      |
| **URL del Despliegue**       | [https://technostore.nexohuila.com](https://technostore.nexohuila.com)                  |
| **Fecha de Dictamen**        | 11 de Noviembre de 2026                                                                 |

### Posición del Evaluador (Grupo 5)

Como usuarios y evaluadores exigentes, consideramos que un aplicativo de comercio electrónico no solo debe "funcionar" a nivel de base de datos; **debe ser visualmente cómodo, intuitivo y no agotar mentalmente al usuario**. Si el objetivo comercial es que una persona pase horas explorando productos de tecnología, la interfaz actual de TechnoStore presenta fricciones evidentes de saturación, aglomeración de controles en el encabezado, elementos huérfanos sin estilos en el pie de página y desbordes en la gestión de saldo simulado que deben ser corregidos.

---

## 2. Escala de Valoración Aplicada (Matriz Oficial)

* **1.0 – 2.9 | Deficiente (D):** No cumple estándares mínimos.
* **3.0 – 3.9 | Aceptable (A):** Cumple pero presenta fricciones notorias en UI/UX, sobrecarga o debilidades en casos borde.
* **4.0 – 4.5 | Bueno (B):** Funcional y operativo; requiere ajustes obligatorios de optimización visual y refinamiento ergonómico.
* **4.6 – 5.0 | Excelente (E):** Cumplimiento riguroso, pulido y sin fisuras de diseño.

---

## 3. Matriz de Evaluación Detallada (24 Criterios)

### Dimensión 1: Diseño Responsivo y Adaptabilidad (RWD)

*Promedio de la Dimensión:* **4.00 / 5.00 (Bueno)**

| ID      | Criterio                                        | Puntaje | Nivel     | Juicio Crítico y Observación de Diseño (Grupo 5)                                                                                                                                                                                                                           |
|:-------:|:----------------------------------------------- |:-------:|:---------:|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1.1** | **Adaptabilidad a Viewports (Breakpoints)**     | **4.0** | Bueno     | La grilla de productos responde bien al ancho, pero en resoluciones intermedias (pantallas de portátiles de 1366px o tablets en horizontal), la barra de navegación colapsa de forma desordenada porque acumula demasiados botones en una sola fila.                       |
| **1.2** | **Contenido Multimedia Adaptativo**             | **3.9** | Aceptable | Las imágenes mantienen relación de aspecto, pero visualmente chocan: hay productos con fotos en fondo blanco puro, otros con fondos transparentes y otros con recortes grises. La falta de un contenedor neutro estandarizado genera un mosaico dispar y poco profesional. |
| **1.3** | **Zonas de Interacción Táctil (Touch Targets)** | **4.1** | Bueno     | El tamaño de los botones es aceptable en móvil, pero la separación entre las opciones del menú superior es muy estrecha, provocando clics erróneos entre "Mi perfil" y "Mis pedidos".                                                                                      |

---

### Dimensión 2: Teoría del Color, Contraste y Accesibilidad Visual

*Promedio de la Dimensión:* **4.00 / 5.00 (Bueno)**

| ID      | Criterio                                         | Puntaje | Nivel     | Juicio Crítico y Observación de Diseño (Grupo 5)                                                                                                                                                                                                                                                                                              |
|:-------:|:------------------------------------------------ |:-------:|:---------:|:--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **2.1** | **Ratio de Contraste Tipográfico (WCAG 2.1 AA)** | **4.3** | Bueno     | Los contrastes cumplen la norma técnica sobre fondo blanco, pero algunos textos secundarios en gris (`#6b7785`) sobre fondos de tarjeta se sienten apagados y difíciles de leer bajo luz ambiente intensa.                                                                                                                                    |
| **2.2** | **Jerarquía y Saturación Cromática**             | **3.6** | Aceptable | **PUNTO CRÍTICO:** La interfaz peca de sobrecarga cromática. Coexisten al mismo tiempo un azul oscuro dominante, amarillo arena, botones dorados de alto brillo, badges rojos y verdes, y un banner con degradado pesado. No hay suficiente "blanco de descanso" (*whitespace*); tras 15 minutos continuos de navegación, la vista se fatiga. |
| **2.3** | **Independencia de la Información Cromática**    | **4.1** | Bueno     | Correcto uso de etiquetas e iconos para acompañar estados de pedido y stock, aunque las pastillas de estado (*pills*) abusan de colores muy vivos que compiten con las fotos del catálogo.                                                                                                                                                    |

---

### Dimensión 3: Coherencia Temática y Lenguaje de Marca

*Promedio de la Dimensión:* **4.10 / 5.00 (Bueno)**

| ID      | Criterio                                   | Puntaje | Nivel | Juicio Crítico y Observación de Diseño (Grupo 5)                                                                                                                                                                                           |
|:-------:|:------------------------------------------ |:-------:|:-----:|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **3.1** | **Alineación con el Dominio y Audiencia**  | **4.2** | Bueno | La tipografía elegida (*DM Sans* y *Sora*) es acertada para tecnología, pero la identidad se siente dividida: el banner busca proyectar sofisticación mientras que la barra de botones planos transmite una estética utilitaria básica.    |
| **3.2** | **Uniformidad Terminológica y Microcopia** | **4.0** | Bueno | Se mezclan conceptos de forma innecesaria: en la barra se habla de "dinero digital", en el perfil de "saldo disponible" y en los mensajes de "recarga". Conviene unificar bajo un término más intuitivo como *"Mi Billetera TechnoStore"*. |

---

### Dimensión 4: Interfaz de Usuario (UI) y Sistema de Diseño

*Promedio de la Dimensión:* **3.90 / 5.00 (Aceptable)**

| ID      | Criterio                                           | Puntaje | Nivel     | Juicio Crítico y Observación de Diseño (Grupo 5)                                                                                                                                                                                                                                                                             |
|:-------:|:-------------------------------------------------- |:-------:|:---------:|:---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **4.1** | **Jerarquía Visual y Escala Tipográfica**          | **3.7** | Aceptable | **PUNTO CRÍTICO:** La barra superior carece de orden jerárquico. Todos los botones tienen prácticamente el mismo peso visual (Catálogo, Mis pedidos, Mi perfil, Admin, Saldo, Carrito, Login, Registro). Un usuario primerizo no sabe dónde poner el foco; el buscador y el carrito quedan opacados entre tanto texto plano. |
| **4.2** | **Estados Interactivos de Componentes**            | **4.3** | Bueno     | Destacable el anillo `:focus-visible` amarillo. Sin embargo, los botones secundarios en reposo carecen de volumen y se confunden con texto plano sin interactividad.                                                                                                                                                         |
| **4.3** | **Estandarización de Componentes (Design Tokens)** | **3.7** | Aceptable | **PUNTO CRÍTICO:** Las tarjetas de producto utilizan un borde gris sólido continuo (`1px solid var(--line)`). En pantallas medianas con 12 productos visibles, esto genera un "efecto cuadrícula de celdas" anticuado y pesado. Hace falta suavizar bordes y apoyarse en elevaciones difusas para lograr ligereza.           |

---

### Dimensión 5: Experiencia de Usuario (UX) e Interacción

*Promedio de la Dimensión:* **3.75 / 5.00 (Aceptable)**

| ID      | Criterio                                                            | Puntaje | Nivel     | Juicio Crítico y Observación de Diseño (Grupo 5)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
|:-------:|:------------------------------------------------------------------- |:-------:|:---------:|:---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **5.1** | **Arquitectura de Información y Navegabilidad**                     | **3.8** | Aceptable | Los filtros por chips y el selector de ordenamiento están sueltos sobre los productos y se pierden tan pronto el usuario hace scroll hacia abajo. Para cambiar de categoría hay que subir toda la página otra vez, lo que entorpece la exploración continua.                                                                                                                                                                                                                                                                                         |
| **5.2** | **Retroalimentación y Manejo de Latencias**                         | **3.5** | Aceptable | **FALLA VISUAL EVIDENTE (FOOTER):** El componente de carga global (`#loader`) carece por completo de reglas CSS en los estilos. En lugar de aparecer como un *overlay* modal centrado con fondo traslúcido y spinner giratorio, la etiqueta `<span id="loaderMsg">Cargando…</span>` queda huérfana en el flujo normal del documento al final de la página, exhibiéndose en texto plano blanco sobre negro en la esquina inferior izquierda del footer. Esto genera una impresión inmediata de código de depuración abandonado o interfaz incompleta. |
| **5.3** | **Tratamiento Humanizado de Errores**                               | **4.1** | Bueno     | Los mensajes toast informan fallos, pero desaparecen muy rápido cuando ocurren validaciones en formularios largos de registro o checkout.                                                                                                                                                                                                                                                                                                                                                                                                            |
| **5.4** | **Estabilidad Visual y Rendimiento (Confort para Sesiones Largas)** | **3.6** | Aceptable | **PUNTO CRÍTICO:** El micro-zoom de las tarjetas (`scale(1.06)`) al pasar el cursor es demasiado brusco. Al mover el ratón rápidamente por el catálogo para ojear precios, las imágenes saltan constantemente en pantalla, lo que genera cansancio visual y mareo tras pocos minutos. Debe atenuarse a `scale(1.02)` o a un sutil sombreado.                                                                                                                                                                                                         |

---

### Dimensión 6: Coherencia con la Documentación Técnica y Funcional

*Promedio de la Dimensión:* **4.25 / 5.00 (Bueno)**

| ID      | Criterio                                            | Puntaje | Nivel | Juicio Crítico y Observación de Diseño (Grupo 5)                                                                                                                                                |
|:-------:|:--------------------------------------------------- |:-------:|:-----:|:----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **6.1** | **Trazabilidad de Requisitos (SRS / User Stories)** | **4.4** | Bueno | Las historias de usuario de compra, consulta de catálogo y saldo existen y responden. No obstante, la experiencia práctica de usuario dista de la fluidez esperada en los wireframes de diseño. |
| **6.2** | **Fidelidad a Diagramas y Modelos de Diseño**       | **4.2** | Bueno | La secuencia de checkout es lógica, aunque la transición entre el carrito y los campos de dirección se siente apretada dentro del modal.                                                        |
| **6.3** | **Correspondencia con el Diccionario de Datos**     | **4.3** | Bueno | Correspondencia precisa en precios, stocks y nombres de productos.                                                                                                                              |
| **6.4** | **Alineación con Manuales de Operación**            | **4.1** | Bueno | Se echa en falta una ayuda contextual o tooltip que le aclare al usuario final qué significa "dinero digital" antes de que se vea forzado a entrar a su perfil.                                 |

---

### Dimensión 7: Cumplimiento de Reglas de Negocio

*Promedio de la Dimensión:* **4.20 / 5.00 (Bueno)**

| ID      | Criterio                                           | Puntaje | Nivel     | Juicio Crítico y Observación de Diseño (Grupo 5)                                                                                                                                                                                                                                                                                                                                                                                                                                              |
|:-------:|:-------------------------------------------------- |:-------:|:---------:|:--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **7.1** | **Validaciones de Dominio e Integridad**           | **4.3** | Bueno     | Buena validación de formato en campos de cliente y checkout con selector asistido de ciudades.                                                                                                                                                                                                                                                                                                                                                                                                |
| **7.2** | **Flujos y Máquina de Estados de Entidades**       | **4.2** | Bueno     | Las órdenes pasan correctamente de pendiente a pagado al procesar las compras.                                                                                                                                                                                                                                                                                                                                                                                                                |
| **7.3** | **Seguridad y Control de Acceso por Roles (RBAC)** | **4.4** | Bueno     | El menú de administración permanece inaccesible para clientes comunes.                                                                                                                                                                                                                                                                                                                                                                                                                        |
| **7.4** | **Exactitud de Fórmulas y Cálculos Automáticos**   | **4.5** | Bueno     | Sumas de subtotales, totales y deducciones monetarias sin errores aritméticos.                                                                                                                                                                                                                                                                                                                                                                                                                |
| **7.5** | **Intercepción de Casos de Borde de Negocio**      | **3.6** | Aceptable | **HALLAZGO CRÍTICO COMPROBADO EN AUDITORÍA:** En el módulo de dinero digital, los botones de recarga rápida (`+ $1.000.000`) no cuentan con bloqueo de concurrencia ni validación de saldo acumulado máximo. Un usuario haciendo clics repetidos satura la cuenta hasta cifras absurdas (superando los **$1.200 millones de pesos** en segundos), lo que rompe visualmente la barra superior por la longitud del número y satura la tabla de movimientos con decenas de filas sin paginación. |

---

## 4. Consolidado Cuantitativo y Veredicto

| N.º   | Dimensión Evaluada                                 | Ítems  | Subtotal  | Máximo     | Promedio | Nivel Cualitativo |
|:-----:|:-------------------------------------------------- |:------:|:---------:|:----------:|:--------:|:-----------------:|
| **1** | Diseño Responsivo y Adaptabilidad (RWD)            | 3      | 12.00     | 15.00      | **4.00** | Bueno             |
| **2** | Teoría del Color, Contraste y Accesibilidad Visual | 3      | 12.00     | 15.00      | **4.00** | Bueno             |
| **3** | Coherencia Temática y Lenguaje de Marca            | 2      | 8.20      | 10.00      | **4.10** | Bueno             |
| **4** | Interfaz de Usuario (UI) y Sistema de Diseño       | 3      | 11.70     | 15.00      | **3.90** | Aceptable         |
| **5** | Experiencia de Usuario (UX) e Interacción          | 4      | 15.00     | 20.00      | **3.75** | Aceptable         |
| **6** | Coherencia con Documentación Técnica y Funcional   | 4      | 17.00     | 20.00      | **4.25** | Bueno             |
| **7** | Cumplimiento de Reglas de Negocio                  | 5      | 21.00     | 25.00      | **4.20** | Bueno             |
| —     | **TOTAL GLOBAL**                                   | **24** | **96.90** | **120.00** | **4.04** | **Bueno**         |

### Fórmula de Calificación Global

$$\mathbf{Puntaje\ Consolidado} = \frac{96.90}{24} = \mathbf{4.04\ /\ 5.00}$$

> ### **VEREDICTO: APROBADO ORDINARIO**
> 
> **Dictamen Académico:** La plataforma cumple con los requisitos operativos esenciales de un e-commerce y demuestra trabajo técnico en sus endpoints y base de datos. Sin embargo, **como usuarios no nos sentimos cómodos pasando horas navegando en ella**. Su aprobación queda condicionada al levantamiento de los 5 problemas críticos de diseño y usabilidad señalados a continuación.

---

## 5. Matriz de Hallazgos Críticos de Diseño y Plan de Acción (Nivel Usuario Exigente)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      MATRIZ DE INTERVENCIÓN ERGONÓMICA                      │
│                                                                             │
│   [Header Saturado]    ──>  Agrupar en menú 'Mi Cuenta' (Avatar desplegable)│
│   [Cuadrícula Pesada]  ──>  Eliminar bordes rígidos; usar sombra difusa     │
│   [Zoom Brusco Cards]  ──>  Moderar de scale(1.06) a scale(1.02) o sombra   │
│   [Loader Huérfano]    ──>  Definir CSS para #loader (modal fijo con overlay│
│   [Fallo en Billetera] ──>  Debounce en botones rápidos y tope máx de saldo │
└─────────────────────────────────────────────────────────────────────────────┘
```

| Ref.     | Área / Componente                       | Falla Detectada (Perspectiva de Usuario)                                                                                                                                                                        | Severidad | Acción Correctiva Recomendada                                                                                                                                                               | Impacto Esperado                                                                                                                          |
|:--------:|:--------------------------------------- |:--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |:---------:|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |:----------------------------------------------------------------------------------------------------------------------------------------- |
| **H-01** | **Header / Barra Superior**             | Acumula hasta 8 botones planos a la vez. En pantallas normales satura la vista y compite con el buscador principal.                                                                                             | **Media** | Reubicar "Mis pedidos", "Mi perfil", "Administración" y "Cerrar sesión" en un menú desplegable de usuario. Dejar limpios en el header solo el logo, la barra de búsqueda y el carrito.      | Despeja el área superior y permite al usuario concentrarse en buscar productos sin distracciones.                                         |
| **H-02** | **Tarjetas del Catálogo (`.card`)**     | Bordes oscuros tipo cuadrícula (`border: 1px solid var(--line)`) combinados con un zoom excesivo (`scale(1.06)`).                                                                                               | **Media** | Quitar el borde rígido, aumentar las esquinas a `16px`, aplicar sombra suave (`box-shadow: 0 4px 14px rgba(0,0,0,0.04)`) y rebajar el zoom hover a `scale(1.02)`.                           | Transforma el catálogo en una vitrina moderna y relajante donde ojear productos no cause mareo ni fatiga ocular.                          |
| **H-03** | **Barra de Herramientas y Filtros**     | Los chips de categorías y el selector de orden se quedan atrás al hacer scroll.                                                                                                                                 | **Media** | Convertir la barra de filtros en una barra flotante fija (*sticky toolbar*) o incorporarla como panel lateral colapsable.                                                                   | Permite filtrar por marcas y categorías en cualquier momento sin tener que regresar obligatoriamente a la cima.                           |
| **H-04** | **Footer / Componente `#loader`**       | Texto *"Cargando…"* huérfano visible de manera permanente al final del documento (esquina inferior izquierda debajo del footer) por ausencia total de reglas CSS en `#loader`, `.lbox` y `.spinner`.            | **Media** | Agregar las reglas CSS correspondientes en `styles.css`: `position: fixed; inset: 0; background: rgba(0,0,0,.5); display: none;` y activarlo solo mediante `#loader.on { display: flex; }`. | Elimina el texto residual antiestético en el pie de página y restaura la función prevista de overlay modal durante peticiones asíncronas. |
| **H-05** | **Módulo de Saldo / Billetera Digital** | Botones rápidos (`#wQuick`) sin bloqueo de clics repetidos (*debounce*). Permite recargar cientos de millones por error, desbordando el ancho del header (`$1.263.352.000`) y colapsando la tabla de historial. | **Alta**  | Deshabilitar el botón al primer clic mientras responde el servidor, definir un tope razonable de saldo total (ej. $15.000.000) y paginar la tabla de movimientos a 5 filas.                 | Evita bloqueos de cuentas de clientes por falsos positivos, protege la coherencia del diseño y estabiliza el Navbar.                      |

---

## 6. Conclusión y Recomendación Final del Grupo 5 al Grupo 6

> *"Reconocemos el esfuerzo técnico que el **Grupo 6** ha depositado en TechnoStore; la tienda tiene los motores listos: carrito, órdenes, filtros y pagos virtuales. Pero el diseño actual es como una tienda física con pasillos demasiado estrechos, luces demasiado brillantes y cables sueltos en el suelo: **abruma y tiene descuidos visuales notables** (como el texto de carga abandonado en el pie de página). Si atienden estas 5 recomendaciones de diseño —descongestionar la barra, estilizar el loader modal, suavizar las tarjetas, fijar los filtros y controlar la billetera— el aplicativo dejará de verse como un prototipo denso y se convertirá en una tienda online donde cualquier usuario realmente disfrutará quedarse navegando por horas."*

---

## 7. Registro de Firmas Simbólicas



Kevin Fernando Martinez Perez 
**Firma del Evaluador / Auditor** $\quad\quad\quad\quad\quad\quad\quad\quad\quad\quad\quad$    **Firma del Líder Técnico / Responsable**
