# **Instrumento Integral de Evaluación de Calidad, Experiencia de Uso y Reglas de Negocio para Aplicativos Web**

## **1\. Ficha Técnica de la Evaluación**

| Campo | Detalle |
| :---- | :---- |
| **Nombre del Aplicativo / Proyecto:** | TechnoStore (Plataforma de comercio electrónico y catálogo de productos tecnológicos) |
| **Versión / Tag de Release:** | 1.0 (con SRS en su edición 3.0) |
| **URL del Despliegue (Staging/Prod):** | Entorno local (`http://localhost/TechnoStore/`) |
| **Repositorio de Código / Rama:** | `https://github.com/deinersmitlimaslavao67/TechnoStore.git` |
| **Documento de Requisitos / PRD Base:** | `SRS-Technostore_v3.0.pdf`, `Manual técnico de TechnoStore.pdf`, `README.md` |
| **Nombre de la persona evaluadora:** | EQUIPO 7 |
| **Rol de la persona evaluadora:** | `[x]` Control de calidad (QA) ${}$ `[ ]` Diseño UX/UI ${}$ `[ ]` Liderazgo técnico ${}$ `[ ]` Dueño/a del producto |
| **Programa de formación:** | ADSO – Análisis y Desarrollo de Software (SENA) |
| **Fecha de Evaluación:** | 14 de Noviembre de 2026 |

&nbsp;

## **2\. Metodología y Criterios de Puntuación**

La evaluación usa una escala de 5 niveles para medir qué tan maduro está el proyecto en su aspecto técnico, visual y funcional. Cada criterio se califica de 1.0 a 5.0.

| Nivel | Rango Cuantitativo | Criterio de Cumplimiento |
| :---- | :---- | :---- |
| **Deficiente (D)** | $1.0-2.9$ | No cumple lo mínimo esperado. Hay fallas serias que impiden usar la tienda con normalidad o que contradicen lo que dice la documentación. |
| **Aceptable (A)** | $3.0-3.9$ | Cumple a medias. Funciona, pero hay molestias claras para quienes lo usan, problemas de accesibilidad o diferencias con la documentación. |
| **Bueno (B)** | $4.0-4.5$ | Cumple lo pedido y las buenas prácticas habituales. Necesita ajustes pequeños de presentación, de casos poco comunes o de consistencia en los textos. |
| **Excelente (E)** | $4.6-5.0$ | Cumple con rigor las normas de accesibilidad (WCAG 2.1 AA), se adapta a cualquier pantalla, coincide exactamente con la documentación y no falla en las reglas del negocio. |

## **3\. Matriz de Evaluación**

### **Dimensión 1: Diseño Adaptable a Distintas Pantallas (Responsive)**

| ID | Criterio de Verificación | Puntuación ($1.0-5.0$) | Evidencia / Observación |
| :---- | :---- | :---- | :---- |
| **1.1** | **Adaptación a distintos tamaños de pantalla:** la tienda reorganiza sus bloques en celular, tableta y computador sin obligar a deslizar de lado a lado. | 4.0 | `styles.css` tiene ajustes para pantallas de hasta 520, 640 y 860 px, y las tablas del panel de administración van dentro de un contenedor que se desliza solo (no mueve toda la página). Son pocos puntos de ajuste para un archivo de más de 2.300 líneas, por lo que algunas zonas intermedias podrían verse apretadas. |
| **1.2** | **Imágenes y videos que se ajustan:** las imágenes y recursos se adaptan al espacio disponible. | 4.0 | Si un producto no tiene foto o esta falla, la tienda dibuja una ilustración de respaldo por categoría, así nunca se ve un hueco roto. Las imágenes del catálogo cargan de forma diferida. Se recomienda convertir `logo.png` y `banner.png` a un formato más liviano (WebP). |
| **1.3** | **Tamaño cómodo de botones para el dedo:** botones y enlaces con un área táctil de al menos $48\times 48px$. | 3.0 | Los botones normales miden cerca de 36 px de alto; los botones pequeños (`.btn.sm`) rondan los 28 px y los de sumar/restar cantidad en el carrito tienen solo 30 px de ancho. Para quienes usan el celular con una mano o tienen poca precisión al tocar, resultan pequeños. |

### **Dimensión 2: Color, Contraste y Accesibilidad Visual**

| ID | Criterio de Verificación | Puntuación ($1.0-5.0$) | Evidencia / Observación |
| :---- | :---- | :---- | :---- |
| **2.1** | **Contraste entre texto y fondo (WCAG 2.1 AA):** los textos se leen bien (mínimo 4.5:1). | 4.0 | El texto principal sobre el fondo tiene un contraste excelente (13.8:1) y el texto blanco sobre el azul de marca también (8.7:1). Pero el texto gris secundario sobre el fondo claro llega a 4.18:1 y el texto blanco sobre el botón verde "ok" a 4.14:1, ambos un poco por debajo del mínimo de 4.5:1. |
| **2.2** | **Jerarquía y combinación de colores:** paleta armónica y sin saturar la vista. | 4.5 | Paleta azul sobrio con acentos claros, y botones principales, secundarios (contorno) y de alerta bien distinguibles. Una observación: la persona administradora puede cambiar los colores de la tienda y el sistema solo revisa el formato del color, no que se lea bien. |
| **2.3** | **No depender solo del color:** los estados se comunican también con íconos o texto. | 3.5 | Los mensajes de aviso dicen claramente qué pasó con palabras, lo cual ayuda. Sin embargo, la diferencia entre "éxito" y "error" en esos avisos se marca solo con el color de una franja lateral (verde o rojo), sin ícono. Para personas con daltonismo no es tan evidente. |

### **Dimensión 3: Coherencia Temática y Lenguaje de Marca**

| ID | Criterio de Verificación | Puntuación ($1.0-5.0$) | Evidencia / Observación |
| :---- | :---- | :---- | :---- |
| **3.1** | **Coherencia con el tema y el público:** los elementos reflejan que es una tienda de tecnología. | 4.5 | Identidad clara: logo, banner, categorías (celulares, computadores, audio, gaming, hogar) y un estilo limpio y moderno. Todo se siente de una misma marca. |
| **3.2** | **Uniformidad de palabras y mensajes:** mismo vocabulario en textos y etiquetas. | 4.0 | Los mensajes están en español cercano y claro ("Tu carrito está vacío", "Ese pedido no es tuyo"). Hay pequeñas variaciones, por ejemplo "Dinero digital" y "saldo digital" para lo mismo, o "Tarjeta crédito" escrito sin la palabra "de". |

### **Dimensión 4: Interfaz de Usuario (UI) y Sistema de Diseño**

| ID | Criterio de Verificación | Puntuación ($1.0-5.0$) | Evidencia / Observación |
| :---- | :---- | :---- | :---- |
| **4.1** | **Orden visual y escala de letras:** títulos y textos con tamaños claros y ritmo ordenado. | 4.0 | Usa dos tipografías (DM Sans para el texto y Sora para títulos) con buen espaciado y lectura cómoda. Depende de Google Fonts: sin internet se usa una letra de respaldo que también se ve bien. |
| **4.2** | **Estados de los componentes:** se nota cuándo un botón está en hover, activo, con foco o desactivado. | 4.0 | Aquí hay un punto a favor frente a otros proyectos: ya existe una regla de foco visible (`:focus-visible`) con un contorno amarillo grueso, que ayuda a quienes navegan con teclado. Mejora pendiente: ese amarillo sobre fondo blanco tiene poco contraste (1.86:1), por lo que en zonas claras casi no se ve. |
| **4.3** | **Componentes estandarizados:** bordes, sombras y botones consistentes. | 4.0 | Usa variables de color centralizadas (`--brand`, `--ok`, `--bad`) y tarjetas con bordes y sombras parejos. Algunas reglas (como `.btn` y `:focus-visible`) aparecen definidas más de una vez en `styles.css`; conviene revisar que no se pisen entre sí. |

### **Dimensión 5: Experiencia de Uso (UX) e Interacción**

| ID | Criterio de Verificación | Puntuación ($1.0-5.0$) | Evidencia / Observación |
| :---- | :---- | :---- | :---- |
| **5.1** | **Recorrido lógico para las tareas clave:** el camino para comprar es claro. | 4.5 | El recorrido catálogo → carrito → confirmar pedido (dirección y método de pago) → comprobante es directo. El dinero digital de práctica y el historial de pedidos están bien ubicados. |
| **5.2** | **Respuesta mientras la tienda "piensa":** indicadores durante las esperas. | 4.5 | Hay un círculo de carga general que evita parpadeos, y los botones se bloquean mientras se procesa la acción. Además, el servidor protege el pago ante doble clic, por lo que no se cobra dos veces. Muy buen trabajo en este punto. |
| **5.3** | **Mensajes de error comprensibles:** explican qué pasó y qué hacer. | 4.0 | La mayoría de los mensajes son claros y amables ("Solo hay 3 unidades disponibles", "Primero restaura la categoría de este producto"). Algunos casos raros muestran textos técnicos, como el aviso de falla de conexión que menciona nombres internos (`DB_HOST`, `DB_PORT`) o "Error de base de datos". |
| **5.4** | **Estabilidad visual y rapidez:** sin saltos bruscos y con buena velocidad. | 3.5 | El proyecto incluye un video de 15 MB dentro de la propia carpeta de la tienda. Además, la lista de países y ciudades se consulta a servicios externos cada vez, sin guardarla, lo que puede hacerlo lento o dejar la lista vacía si esos servicios fallan. |

### **Dimensión 6: Coherencia con la Documentación**

| ID | Criterio de Verificación | Puntuación ($1.0-5.0$) | Evidencia / Observación |
| :---- | :---- | :---- | :---- |
| **6.1** | **Seguimiento de requisitos (SRS):** lo construido coincide con lo prometido. | 4.5 | El SRS v3.0 lista 59 requisitos funcionales (RF-01 a RF-59), y cada uno indica la acción del servidor que lo cumple. Las acciones citadas (carrito, pagos, comprobantes, reportes, reportes de errores, anexos) existen en el código. El README todavía menciona `SRS_TechnoStore.pdf`, pero la carpeta trae `SRS-Technostore_v3.0.pdf`. |
| **6.2** | **Fidelidad a diagramas y modelos:** concuerda con el MER y otros diseños. | 4.0 | Las 9 entidades del diagrama (`USUARIO`, `CATEGORIA`, `PRODUCTO`, `PEDIDO`, `DETALLE_PEDIDO`, `PERSONALIZACION_TIENDA`, `TRANSACCION_DIGITAL`, `COMPROBANTE`, `FEEDBACK_REPORTES`) coinciden con las tablas de la base de datos. No se encontraron bocetos de pantallas (wireframes) para comparar con la interfaz. |
| **6.3** | **Correspondencia con la base de datos documentada:** tipos, tamaños y relaciones. | 4.0 | La base de datos tiene llaves foráneas, valores únicos y reglas de seguridad (por ejemplo, precio, stock y saldo no pueden ser negativos). No hay un diccionario de datos aparte, y parte de la estructura también se crea desde el código (`migrar()`), lo que duplica la fuente de verdad. |
| **6.4** | **Coherencia con los manuales:** los flujos coinciden con los manuales de uso. | 3.5 | El manual de usuario cubre visitantes, clientes, dinero digital y administración con buen detalle. Pero su índice trae un error visible ("Lista de capturas por insertar: ¡Error\! Marcador no definido") y los capítulos 9, 10 y 11 (reportes y anexos) no aparecen en el índice. |

### **Dimensión 7: Cumplimiento de Reglas de Negocio**

| ID | Criterio de Verificación | Puntuación ($1.0-5.0$) | Evidencia / Observación |
| :---- | :---- | :---- | :---- |
| **7.1** | **Validaciones de datos y seguridad de la información:** reglas respetadas en pantalla y en el servidor. | 4.0 | Muy bien: todas las consultas usan parámetros protegidos (no se pegan textos del usuario directo en la base de datos), se revisan longitudes, correo y direcciones de imagen, y las imágenes subidas se verifican y se guardan con nombre aleatorio. A mejorar: la contraseña solo exige 6 caracteres y el teléfono no se valida por formato. |
| **7.2** | **Ciclo de vida de los pedidos:** los cambios de estado están controlados. | 4.5 | El flujo PENDIENTE → PAGADO → ENVIADO → ENTREGADO está definido y no permite saltos indebidos. Cancelar un pedido devuelve el stock y, si se pagó con dinero digital, también el saldo, una sola vez. |
| **7.3** | **Seguridad y permisos por rol:** cada persona accede solo a lo que le corresponde. | 3.5 | Bien resuelto: se distingue cliente de administrador, cada cliente solo ve sus pedidos y comprobantes, y se cambia la sesión al iniciar. Pendientes importantes: las cuentas de ejemplo entran con la contraseña `123456`; no hay límite de intentos al iniciar sesión; y las acciones que cambian datos también aceptan peticiones por enlace, sin una protección extra contra acciones hechas a escondidas (CSRF). |
| **7.4** | **Exactitud de cálculos automáticos:** las cuentas del negocio son correctas. | 4.5 | Los totales se calculan en el servidor con el precio congelado al momento de comprar, con dos decimales. El saldo no puede quedar negativo y el comprobante se numera automáticamente (COMP-000001). |
| **7.5** | **Situaciones poco comunes del negocio:** manejo de casos límite. | 4.0 | Muy bien cubiertos: el doble clic al pagar, el stock que se acaba mientras se compra (si el saldo no alcanza, se deshace todo), que un administrador no pueda cambiarse el rol ni borrarse a sí mismo, y que un producto no se restaure si su categoría sigue eliminada. Queda un caso: `sp_actualizar_stock` busca el producto por nombre, y el nombre de un producto no es único. |

## **4\. Consolidado Cuantitativo**

&nbsp;

$PuntajeConsolidado=\frac{\sum\limits_{i=1}^{22}Puntaj{e}_{i}}{22}$

&nbsp;

| Dimensión Evaluada | Peso / Total Ítems | Subtotal Obtenido | Promedio Dimensión |
| :---- | :---- | :---- | :---- |
| 1\. Diseño Adaptable (Responsive) | 3 ítems | 11.0 / 15.0 | 3.67 |
| 2\. Color, Contraste y Accesibilidad | 3 ítems | 12.0 / 15.0 | 4.00 |
| 3\. Coherencia Temática | 2 ítems | 8.5 / 10.0 | 4.25 |
| 4\. Interfaz de Usuario (UI) | 3 ítems | 12.0 / 15.0 | 4.00 |
| 5\. Experiencia de Uso (UX) | 4 ítems | 16.5 / 20.0 | 4.13 |
| 6\. Coherencia con la Documentación | 4 ítems | 16.0 / 20.0 | 4.00 |
| 7\. Reglas de Negocio | 5 ítems | 20.5 / 25.0 | 4.10 |
| **Total Global** | **22 ítems** | **96.5 / 110.0** | **4.39 / 5.0** |

### **Veredicto de Calificación**

* `[ ]` **Aprobado con Distinción** ($4.6-5.0$): Listo para producción sin observaciones críticas.  
* `[x]` **Aprobado Ordinario** ($4.0-4.5$): Puede avanzar, condicionado a corregir ajustes menores de diseño, accesibilidad y seguridad.  
* `[ ]` **Aprobado con Observaciones / En Observación** ($3.0-3.9$): Requiere rehacer partes importantes antes de salir a producción.  
* `[ ]` **No Aprobado / Rechazado** ($1.0-2.9$): Presenta fallas estructurales en las reglas del negocio, la documentación o la interfaz.

## **5\. Matriz de Hallazgos y Plan de Acción de Mejora**

| Ref. Ítem | Componente / Módulo Afectado | Descripción de lo Encontrado | Severidad (Baja / Media / Alta / Crítica) | Acción Recomendada | Responsable Asignado |
| :---- | :---- | :---- | :---- | :---- | :---- |
| **7.3** | *Inicio de sesión y cuentas (`api.php`, `bdcatalogo.sql`)* | Las cuentas de ejemplo (administración incluida) entran con la contraseña `123456`; no hay límite de intentos fallidos ni protección extra contra acciones hechas a escondidas por otra página (CSRF). | **Alta** | Quitar el acceso con `123456` antes de publicar, limitar los intentos de inicio de sesión, pedir contraseñas más largas y exigir que las acciones que cambian datos solo se envíen por formulario (POST) con un código de verificación. | *Equipo de Backend* |
| **1.3** | *Botones del carrito, tarjetas y tablas (`styles.css`)* | Botones pequeños (28–36 px de alto, 30 px en sumar/restar) difíciles de tocar en celular. | **Media** | Subir el tamaño mínimo de botones y enlaces a 44–48 px en pantallas táctiles y separar más los botones entre sí. | *Equipo de Frontend* |
| **5.4** | *Video y listas de países/ciudades* | Video de 15 MB dentro del proyecto y consultas a servicios externos en cada uso, sin guardar resultados. | **Media** | Alojar el video fuera del código (o comprimirlo) y guardar temporalmente la lista de países; dejar un plan B si el servicio externo no responde. | *Equipo de Backend* |
| **2.1 / 4.2** | *Colores de texto, botón verde y foco (`styles.css`)* | El gris secundario (4.18:1), el texto blanco sobre verde (4.14:1) y el contorno amarillo de foco (1.86:1 sobre blanco) quedan bajo lo recomendado. | **Media** | Oscurecer un poco el gris y el verde, y usar un contorno de foco más oscuro o de doble color (por ejemplo azul con borde blanco). | *Diseño UI/UX* |
| **6.4** | *Manual de usuario (PDF)* | El índice muestra un error ("Marcador no definido") y no incluye los capítulos 9, 10 y 11\. | **Media** | Corregir y actualizar el índice, y completar o eliminar el apartado de capturas pendientes. | *Documentación* |
| **2.3** | *Avisos de la tienda (`toast`)* | Éxito y error se diferencian solo por el color de una franja lateral. | **Baja** | Agregar un ícono (✔ / ✖) y la palabra "Error" o "Listo" al inicio del aviso. | *Equipo de Frontend* |
| **5.3** | *Mensajes de falla de conexión* | Un error de conexión muestra nombres técnicos internos a quien usa la tienda. | **Baja** | Mostrar un mensaje simple ("Estamos teniendo problemas, intenta de nuevo en unos minutos") y dejar el detalle solo en el registro interno. | *Equipo de Backend* |
| **7.5** | *Stock por nombre (`sp_actualizar_stock`)* | Se actualiza el stock buscando por nombre, y los nombres de producto pueden repetirse. | **Baja** | Buscar por el identificador del producto en lugar del nombre. | *Equipo de Backend* |
| **6.1 / 6.3** | *README y estructura de datos* | El README cita un archivo de SRS que no está en la carpeta; existe un archivo vacío (`configof.php`); parte de la estructura de la base de datos se crea desde el código además del script `.sql`. | **Baja** | Actualizar el README, borrar el archivo vacío y dejar un solo lugar donde se defina la base de datos. | *Documentación / Backend* |

## **6\. Dictamen Final y Firmas**

**Fortalezas Destacadas de la Solución:**

TechnoStore muestra un trabajo muy completo y bien pensado. Su mayor fortaleza está en el cuidado de las reglas del negocio: el recorrido de compra es claro, los pagos están protegidos contra cobros dobles, los pedidos siguen un ciclo ordenado y los precios quedan guardados tal como se pagaron. La documentación es amplia y se corresponde casi uno a uno con lo construido (59 requisitos con su acción asociada), y la interfaz ofrece buenos detalles de uso: círculo de carga, botones que se bloquean mientras se procesan, imágenes de respaldo y un foco visible para navegar con teclado.

**Puntos de Atención Prioritarios:**

1. Reforzar la seguridad de las cuentas: eliminar las contraseñas de ejemplo antes de publicar, limitar los intentos de ingreso y proteger las acciones que modifican datos.  
2. Mejorar la comodidad en celular: botones más grandes y mejor separados.  
3. Ajustar los contrastes de color (gris secundario, verde y foco) para que todas las personas puedan leer y ubicarse con facilidad.  
4. Corregir el índice del manual de usuario y alivianar el proyecto (video de 15 MB y consultas externas).

&nbsp;

**KAROLL NIKOL CORONADO BERMEO**

**&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma de la Persona Evaluadora**                              ${}$ **Firma del Líder Técnico / Responsable**

&nbsp;

&nbsp;