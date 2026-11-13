<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechnoStore · Catálogo</title>
  <link rel="icon" type="image/png" href="logo/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css"><link rel="stylesheet" href="features/feedback/feedback.css"><link rel="stylesheet" href="features/project-resources/resources.css">
</head>

<body>
  <header>
    <div class="bar">
      <div class="logo"><img id="storeLogo" alt="" hidden><span id="storeTitle">TechnoStore</span></div>
      <form class="hsearch" id="hSearchForm" role="search"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
          <circle cx="11" cy="11" r="7" />
          <path d="m20 20-3.5-3.5" />
        </svg><input id="hSearch" type="search" placeholder="Buscar laptops, audífonos, marcas…" aria-label="Buscar productos"></form>
      <nav>
        <button data-v="catalog" class="on">Catálogo</button>
        <button data-v="orders" id="navOrders" hidden>Mis pedidos</button>
        <button data-v="profile" id="navProfile" hidden>Mi perfil</button>
        <button data-v="admin" id="navAdmin" hidden>Administración</button>
        <button type="button" id="navWallet" class="wallet" hidden aria-label="Ver mi saldo de dinero digital" title="Mi dinero digital"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 7H5a2 2 0 0 1 0-4h13v4" />
            <path d="M3 5v14a2 2 0 0 0 2 2h15V7" />
            <circle cx="16.5" cy="14" r="1.2" />
          </svg><span id="walletAmt">…</span></button>
        <button type="button" id="navCart" class="cartbtn" aria-label="Abrir carrito"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9" cy="20" r="1.3" />
            <circle cx="18" cy="20" r="1.3" />
            <path d="M2 3h3l2.7 12.4a1 1 0 0 0 1 .8h8.7a1 1 0 0 0 1-.8L20 7H6" />
          </svg>Carrito<span id="cartBadge" class="badge" hidden>0</span></button>
        <button id="navLogin">Iniciar sesión</button>
        <button id="navReg">Registrarse</button>
        <button id="navLogout" hidden>Cerrar sesión</button>
      </nav>
    </div>
  </header>

  <div id="home">
    <section class="hero">
      <div class="hero-in">
        <div>
          <span class="eyebrow">Tecnología · Envíos a todo el país</span>
          <h1 id="bannerTitle">Cargando tienda…</h1>
          <p id="bannerDesc">Encuentra los mejores productos en tecnología</p>
          <div class="hero-cta"><button class="btn gold" id="goCat">Explorar catálogo</button><button class="btn ghost-w" id="goFeat">Ver destacados</button></div>
        </div>
        <div class="hero-art" id="heroArt"></div>
      </div>
    </section>
    <section class="perks">
      <div class="perks-in">
        <div><i>🚚</i>
          <p><b>Envíos nacionales</b><span>Llegamos a todo el país</span></p>
        </div>
        <div><i>💳</i>
          <p><b>Varios medios de pago</b><span>Tarjeta, PSE, Nequi o dinero digital</span></p>
        </div>
        <div><i>📦</i>
          <p><b>Inventario en vivo</b><span>Solo lo que hay disponible</span></p>
        </div>
        <div><i>🛒</i>
          <p><b>Compra fácil</b><span>Carrito y pedido en 2 pasos</span></p>
        </div>
      </div>
    </section>
  </div>

  <main class="wrap">
    <!-- CATÁLOGO -->
    <section id="v-catalog" class="view on">
      <div class="sh">
        <h2>Catálogo de productos</h2><span id="count"></span>
      </div>
      <div class="tools">
        <input id="searchInput" type="search" placeholder="Buscar por nombre o marca (ej. Sony)">
        <button class="btn" id="bSearch">Buscar</button>
        <select id="sortSel" aria-label="Ordenar">
          <option value="">Ordenar: relevancia</option>
          <option value="asc">Precio: menor a mayor</option>
          <option value="desc">Precio: mayor a menor</option>
          <option value="name">Nombre A-Z</option>
        </select>
        <button class="btn ghost" id="bAll">Ver todo</button>
        <button class="btn ghost" id="bFeat">Destacados</button>
      </div>
      <div class="chips" id="chips"></div>
      <div class="grid" id="productGrid"></div>
    </section>

    <!-- PEDIDOS -->
    <section id="v-orders" class="view">
      <h2>Mis pedidos</h2>
      <div class="tw">
        <table>
          <thead>
            <tr>
              <th>Pedido</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Pago</th>
              <th>Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="ordersBody"></tbody>
        </table>
      </div>
    </section>

    <!-- PERFIL -->
    <section id="v-profile" class="view">
      <h2>Mi perfil</h2>
      <section class="wallet-sec" id="walletSec" aria-labelledby="wTitle">
        <div class="wbal">
          <span class="eyebrow">Dinero digital · simulado</span>
          <h3 id="wTitle">Saldo disponible</h3>
          <div class="wbig" id="wBig" aria-live="polite">—</div>
          <p class="sub">No es dinero real: solo sirve para pagar pedidos dentro de esta tienda.</p>
        </div>
        <form class="wrec" id="fRecarga">
          <label for="wMonto">Recargar saldo</label>
          <div class="wq" id="wQuick">
            <button type="button" class="chip" data-m="50000">+ $50.000</button>
            <button type="button" class="chip" data-m="200000">+ $200.000</button>
            <button type="button" class="chip" data-m="1000000">+ $1.000.000</button>
          </div>
          <div class="wrow"><input id="wMonto" type="number" min="1" max="10000000" step="1" inputmode="numeric" placeholder="Otro monto (COP)" required><button class="btn ok" id="wBtn">Recargar</button></div>
          <p class="sub" style="margin-top:.5rem">Máximo $10.000.000 por recarga.</p>
        </form>
      </section>
      <h3 class="wsec-t">Movimientos de mi saldo</h3>
      <div class="tw" style="margin-bottom:1.4rem">
        <table>
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Movimiento</th>
              <th>Monto</th>
              <th>Saldo final</th>
              <th>Comprobante</th>
            </tr>
          </thead>
          <tbody id="wMovs"></tbody>
        </table>
      </div>
      <div class="pgrid">
        <form class="tw tform" id="fPerfil">
          <h3>Mis datos</h3>
          <div class="fg"><label for="mpMail">Correo electrónico</label><input id="mpMail" disabled></div>
          <div class="row2">
            <div class="fg"><label for="mpNom">Nombre</label><input id="mpNom" maxlength="80" autocomplete="given-name" required></div>
            <div class="fg"><label for="mpApe">Apellido</label><input id="mpApe" maxlength="80" autocomplete="family-name" required></div>
          </div>
          <div class="fg"><label for="mpTel">Teléfono</label><input id="mpTel" maxlength="20" autocomplete="tel"></div>
          <div class="row2">
            <div class="fg"><label for="mpPais">País</label><input id="mpPais" maxlength="100" autocomplete="country-name"></div>
            <div class="fg"><label for="mpCiudad">Ciudad</label><input id="mpCiudad" maxlength="100" autocomplete="address-level2"></div>
          </div>
          <div class="row2">
            <div class="fg"><label for="mpBarrio">Barrio</label><input id="mpBarrio" maxlength="100" autocomplete="address-level3"></div>
            <div class="fg"><label for="mpDir">Dirección</label><input id="mpDir" maxlength="255" autocomplete="street-address"></div>
          </div>
          <div class="mf"><button class="btn ok">Guardar datos</button></div>
        </form>
        <form class="tw tform" id="fPass">
          <h3>Cambiar contraseña</h3>
          <div class="fg"><label for="mpAct">Contraseña actual</label><input type="password" id="mpAct" autocomplete="current-password" required></div>
          <div class="fg"><label for="mpNew">Nueva contraseña (mín. 6)</label><input type="password" id="mpNew" minlength="6" autocomplete="new-password" required></div>
          <div class="fg"><label for="mpNew2">Repetir nueva contraseña</label><input type="password" id="mpNew2" minlength="6" autocomplete="new-password" required></div>
          <div class="mf"><button class="btn">Cambiar contraseña</button></div>
        </form>
      </div>
    </section>

    <!-- CARRITO -->
    <section id="v-cart" class="view">
      <h2>Mi carrito</h2>
      <div id="cartBox"></div>
    </section>

    <!-- ADMIN -->
    <section id="v-admin" class="view">
      <h2>Administración</h2>
      <div class="tabs">
        <button class="on" data-t="prod">Productos</button>
        <button data-t="cat">Categorías</button>
        <button data-t="ped">Pedidos</button>
        <button data-t="usr">Usuarios</button>
        <button data-t="tie">Tienda</button>
        <button data-t="rep">Reportes</button>
      </div>

      <div class="panel on" id="t-prod">
        <div class="tools">
          <input id="fProd" type="search" placeholder="Filtrar productos por nombre, marca o categoría">
          <select id="fCat">
            <option value="">Todas las categorías</option>
          </select>
          <button class="btn ok" id="newProd">Nuevo producto</button>
          <button type="button" class="btn ghost" id="togProd">Ver eliminados</button>
        </div>
        <div class="tw">
          <table>
            <thead>
              <tr>
                <th></th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="prodBody"></tbody>
          </table>
        </div>
      </div>

      <div class="panel" id="t-cat">
        <div class="tools"><button class="btn ok" id="newCat">Nueva categoría</button><button type="button" class="btn ghost" id="togCat" style="flex:0 0 auto">Ver eliminados</button></div>
        <div class="tw">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="catBody"></tbody>
          </table>
        </div>
      </div>
      <div class="panel" id="t-ped">
        <div class="chips" id="pedChips"></div>
        <div class="tools"><input id="fPed" type="search" placeholder="Buscar por n.º de pedido, cliente o correo"></div>
        <div class="tw">
          <table>
            <thead>
              <tr>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Pago</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="pedBody"></tbody>
          </table>
        </div>
      </div>
      <div class="panel" id="t-usr">
        <div class="tools"><button type="button" class="btn ghost" id="togUsr" style="flex:0 0 auto">Ver eliminados</button></div>
        <div class="tw">
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="usrBody"></tbody>
          </table>
        </div>
      </div>

      <div class="panel" id="t-tie">
        <form class="tw tform" id="fTienda">
          <h3>Personalización de la tienda</h3>
          <div class="fg"><label for="tNom">Nombre de la tienda</label><input id="tNom" maxlength="80" required></div>
          <div class="fg"><label for="tDes">Descripción (se muestra en el banner de inicio)</label><textarea id="tDes"></textarea></div>
          <div class="row2">
            <div class="fg"><label for="tLogo">Logo (ruta, URL o subir archivo)</label><input id="tLogo" maxlength="255" placeholder="img/logo.png">
              <div class="up"><img alt=""><input type="file" data-target="tLogo" accept="image/png,image/jpeg,image/webp,image/gif" aria-label="Subir logo"></div>
            </div>
            <div class="fg"><label for="tBan">Banner (ruta, URL o subir archivo)</label><input id="tBan" maxlength="255" placeholder="img/banner.jpg">
              <div class="up"><img alt=""><input type="file" data-target="tBan" accept="image/png,image/jpeg,image/webp,image/gif" aria-label="Subir banner"></div>
            </div>
          </div>
          <div class="row2">
            <div class="fg"><label for="tC1">Color primario</label><input type="color" id="tC1" value="#1f4e79"></div>
            <div class="fg"><label for="tC2">Color secundario</label><input type="color" id="tC2" value="#fff2cc"></div>
          </div>
          <div class="tprev" id="tPrev"><span id="tPrevName"></span><i id="tPrevSw"></i></div>
          <p class="hint" id="tWarn" hidden>El color primario es muy claro: el texto blanco del menú será difícil de leer.</p>
          <div class="row2" style="margin-top:.9rem">
            <div class="fg"><label for="tMail">Correo de contacto</label><input type="email" id="tMail" maxlength="120"></div>
            <div class="fg"><label for="tTel">Teléfono de contacto</label><input id="tTel" maxlength="20"></div>
          </div>
          <div class="mf"><button class="btn ok">Guardar cambios</button></div>
        </form>
      </div>

      <div class="panel" id="t-rep">
        <p class="sub" style="margin-bottom:1rem">Descarga la información de la tienda en archivos de Excel (.xlsx).</p>
        <div class="pgrid">
          <div class="tw tform">
            <h3>Pedidos entregados</h3>
            <p class="sub" style="margin-bottom:.9rem">Pedidos en estado ENTREGADO, con cliente, fechas, método de pago y total. La segunda hoja trae los productos de cada pedido.</p>
            <div class="mf" style="margin-top:0"><button class="btn ok" data-rep="pedidos_entregados">Descargar Excel</button></div>
          </div>
          <div class="tw tform">
            <h3>Pedidos pendientes</h3>
            <p class="sub" style="margin-bottom:.9rem">Pedidos en estado PENDIENTE (el cliente ya confirmó la compra y falta confirmar el pago). Incluye la hoja de productos.</p>
            <div class="mf" style="margin-top:0"><button class="btn ok" data-rep="pedidos_pendientes">Descargar Excel</button></div>
          </div>
          <div class="tw tform">
            <h3>Pedidos enviados</h3>
            <p class="sub" style="margin-bottom:.9rem">Pedidos en estado ENVIADO que van en camino al cliente. Incluye la hoja de productos.</p>
            <div class="mf" style="margin-top:0"><button class="btn ok" data-rep="pedidos_enviados">Descargar Excel</button></div>
          </div>
          <div class="tw tform">
            <h3>Usuarios con pedidos</h3>
            <p class="sub" style="margin-bottom:.9rem">Usuarios que han hecho al menos un pedido (el carrito no cuenta): cuántos pedidos tienen, cuántos cancelaron y cuánto han comprado.</p>
            <div class="mf" style="margin-top:0"><button class="btn ok" data-rep="usuarios_con_pedidos">Descargar Excel</button></div>
          </div>
          <div class="tw tform">
            <h3>Usuarios sin pedidos</h3>
            <p class="sub" style="margin-bottom:.9rem">Clientes activos que nunca han hecho un pedido, con una columna que indica si tienen un carrito abierto.</p>
            <div class="mf" style="margin-top:0"><button class="btn ok" data-rep="usuarios_sin_pedidos">Descargar Excel</button></div>
          </div>
          <div class="tw tform">
            <h3>Productos más y menos comprados</h3>
            <p class="sub" style="margin-bottom:.9rem">Dos hojas: los 10 más comprados y los 10 menos comprados (incluye los que nunca se han vendido). Cuentan los pedidos no cancelados.</p>
            <div class="mf" style="margin-top:0"><button class="btn ok" data-rep="productos_mas_menos_comprados">Descargar Excel</button></div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="foot">
    <div class="foot-in"><b id="footName">TecnoShop</b><span>© 2026 · Tecnología con envío a todo el país · <button type="button" class="linkbtn" data-terms>Términos y condiciones</button></span><span id="footContact"></span></div>
  </footer>



  <!-- MODAL LOGIN / REGISTRO -->
  <div class="modal" id="mAuth">
    <div class="mc">
      <div class="tabs" id="authTabs"><button type="button" class="on" data-a="login">Iniciar sesión</button><button type="button" data-a="reg">Crear cuenta</button></div>
      <form id="fAuth">
        <div class="fg"><label for="lEmail">Correo electrónico</label><input type="email" id="lEmail" autocomplete="email" required></div>
        <div class="fg"><label for="lPass">Contraseña</label><input type="password" id="lPass" autocomplete="current-password" required></div>
        <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn">Ingresar</button></div>
      </form>
      <form id="fReg" hidden>
        <div class="row2">
          <div class="fg"><label for="rNom">Nombre</label><input id="rNom" autocomplete="given-name" required></div>
          <div class="fg"><label for="rApe">Apellido</label><input id="rApe" autocomplete="family-name" required></div>
        </div>
        <div class="fg"><label for="rEmail">Correo electrónico</label><input type="email" id="rEmail" autocomplete="email" required></div>
        <div class="row2">
          <div class="fg"><label for="rPass">Contraseña (mín. 6)</label><input type="password" id="rPass" minlength="6" autocomplete="new-password" required></div>
          <div class="fg"><label for="rPass2">Repetir contraseña</label><input type="password" id="rPass2" minlength="6" autocomplete="new-password" required></div>
        </div>
        <div class="row2">
          <div class="fg"><label for="rTel">Teléfono (opcional)</label><input id="rTel" maxlength="20" autocomplete="tel"></div>
          <div class="fg"><label for="rDir">Dirección (opcional)</label><input id="rDir" maxlength="255" autocomplete="street-address"></div>
        </div>
        <div class="row2">
          <div class="fg"><label for="rPais">País</label><input id="rPais" maxlength="100" autocomplete="country-name"></div>
          <div class="fg"><label for="rCiudad">Ciudad</label><input id="rCiudad" maxlength="100" autocomplete="address-level2"></div>
        </div>
        <div class="fg"><label for="rBarrio">Barrio</label><input id="rBarrio" maxlength="100" autocomplete="address-level3"></div>

        <div class="fg chk terms-row"><label><input type="checkbox" id="rTerms" required><span>Acepto los <button type="button" class="linkbtn" data-terms>términos y condiciones</button></span></label></div>
        <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok">Crear cuenta</button></div>
        
      </form>
    </div>
  </div>

  <!-- MODAL TÉRMINOS Y CONDICIONES -->
  <div class="modal" id="mTermsLegacy" role="dialog" aria-modal="true" aria-labelledby="termsTitle">
    <div class="mc terms">
      <h3 id="termsTitle">Términos y Condiciones</h3>
      <div class="terms-box" id="termsBox" tabindex="0">
        <p><b>Última actualización:</b> noviembre de 2026</p>
        <h4>1. Aceptación</h4>
        <p>Al crear una cuenta o usar TechnoStore (la «Tienda») aceptas estos Términos y Condiciones. Si no estás de acuerdo, no uses la Tienda ni crees una cuenta.</p>
        <h4>2. Cuenta de usuario</h4>
        <ul>
          <li>Debes ser mayor de edad (o contar con autorización de tu representante legal) y dar información veraz y actualizada.</li>
          <li>Eres responsable de la confidencialidad de tu contraseña y de toda la actividad realizada con tu cuenta.</li>
          <li>Puedes solicitar la eliminación de tu cuenta; la Tienda puede suspenderla si detecta uso fraudulento o contrario a estos términos.</li>
        </ul>
        <h4>3. Productos, precios y disponibilidad</h4>
        <p>Los productos, precios e inventario se muestran en tiempo real y pueden cambiar sin previo aviso. Las imágenes son de referencia. Si un producto se agota antes de confirmar tu pedido, te lo informaremos.</p>
        <h4>4. Pedidos y pagos</h4>
        <ul>
          <li>Un pedido se considera confirmado cuando recibes el comprobante y el correo de confirmación.</li>
          <li>Medios de pago disponibles: tarjeta, PSE, Nequi y dinero digital.</li>
          <li><b>Dinero digital:</b> es un saldo <b>simulado</b>, sin valor monetario real, que solo sirve para pagar pedidos dentro de la Tienda. No es retirable, transferible ni reembolsable en dinero.</li>
        </ul>
        <h4>5. Envíos</h4>
        <p>Realizamos envíos a todo el país. Los tiempos de entrega son estimados y dependen de la ciudad de destino y de la transportadora. Debes indicar una dirección de entrega correcta y completa.</p>
        <h4>6. Cancelaciones, retracto y garantía</h4>
        <ul>
          <li>Puedes cancelar un pedido desde «Mis pedidos» mientras no haya sido despachado; el stock se devuelve al inventario.</li>
          <li>Conforme a la Ley 1480 de 2011 (Estatuto del Consumidor), en compras a distancia tienes derecho de retracto dentro de los cinco (5) días hábiles siguientes a la entrega, con el producto sin uso y en su empaque original.</li>
          <li>Los productos cuentan con la garantía legal y la del fabricante, según corresponda.</li>
        </ul>
        <h4>7. Protección de datos personales</h4>
        <p>Tratamos tus datos (nombre, correo, teléfono, dirección y datos de compra) para gestionar tu cuenta, procesar pedidos, enviarte notificaciones y mejorar el servicio, de acuerdo con la Ley 1581 de 2012 y sus normas reglamentarias. Tienes derecho a conocer, actualizar, rectificar y suprimir tus datos, y a revocar tu autorización, escribiendo al contacto de la Tienda. No vendemos tus datos a terceros.</p>
        <h4>8. Uso adecuado</h4>
        <p>Está prohibido intentar vulnerar la seguridad de la Tienda, usar datos de otras personas, publicar contenido ilícito o realizar compras fraudulentas.</p>
        <h4>9. Propiedad intelectual</h4>
        <p>Los logotipos, textos, diseños e imágenes de la Tienda son de su titular o de sus licenciantes y no pueden reproducirse sin autorización.</p>
        <h4>10. Limitación de responsabilidad</h4>
        <p>La Tienda no responde por interrupciones del servicio ajenas a su control ni por daños derivados del uso indebido de los productos. Nada de lo aquí dispuesto limita los derechos irrenunciables del consumidor.</p>
        <h4>11. Cambios y ley aplicable</h4>
        <p>Podemos modificar estos términos; la versión vigente será la publicada en la Tienda. Se rigen por las leyes de la República de Colombia.</p>
        <h4>12. Contacto</h4>
        <p>Para dudas, reclamos o ejercer tus derechos sobre tus datos, usa la información de contacto que aparece al pie de la página.</p>
      </div>
      <p class="terms-hint" id="termsHint">Desplázate hasta el final para poder aceptar.</p>
      <div class="mf"><button type="button" class="btn ghost" id="termsClose">Cerrar</button><button type="button" class="btn ok" id="termsAccept" disabled>Aceptar y continuar</button></div>
    </div>
  </div>

  <!-- MODAL PRODUCTO (crear / editar) -->
  <div class="modal" id="mProd">
    <form class="mc" id="fProdForm">
      <h3 id="mProdTitle">Nuevo producto</h3>
      <input type="hidden" id="pId">
      <div class="row2">
        <div class="fg"><label for="pNom">Nombre</label><input id="pNom" required></div>
        <div class="fg"><label for="pMar">Marca</label><input id="pMar"></div>
      </div>
      <div class="fg"><label for="pCat">Categoría</label><select id="pCat" required></select></div>
      <div class="fg"><label for="pDes">Descripción</label><textarea id="pDes"></textarea></div>
      <div class="fg"><label for="pEsp">Especificaciones</label><textarea id="pEsp"></textarea></div>
      <div class="row2">
        <div class="fg"><label for="pPre">Precio (COP)</label><input type="number" id="pPre" min="0" step="0.01" required></div>
        <div class="fg"><label for="pSto">Stock</label><input type="number" id="pSto" min="0" required></div>
      </div>
      <div class="fg"><label for="pImg">Imagen (ruta, URL o subir archivo)</label><input id="pImg" maxlength="255">
        <div class="up"><img alt=""><input type="file" data-target="pImg" accept="image/png,image/jpeg,image/webp,image/gif" aria-label="Subir imagen del producto"></div>
      </div>
      <div class="fg chk"><label><input type="checkbox" id="pDest"> Mostrar como destacado</label></div>
      <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok" id="pSave">Guardar producto</button></div>
    </form>
  </div>

  <!-- MODAL CATEGORÍA (crear / editar) -->
  <div class="modal" id="mCat">
    <form class="mc" id="fCatForm">
      <h3 id="mCatTitle">Nueva categoría</h3>
      <input type="hidden" id="cId">
      <div class="fg"><label for="cNom">Nombre</label><input id="cNom" required></div>
      <div class="fg"><label for="cDes">Descripción</label><input id="cDes"></div>
      <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok">Guardar categoría</button></div>
    </form>
  </div>

  <!-- MODAL DETALLE PRODUCTO (público) -->
  <div class="modal" id="mProdDet">
    <div class="mc wide">
      <div id="pdBody"></div>
      <div class="mf"><button class="btn ghost" data-close>Cerrar</button></div>
    </div>
  </div>

  <!-- MODAL EDITAR USUARIO (administrador) -->
  <div class="modal" id="mUsr">
    <form class="mc" id="fUsr">
      <h3>Editar usuario</h3>
      <input type="hidden" id="uId">
      <div class="row2">
        <div class="fg"><label for="uNom">Nombre</label><input id="uNom" maxlength="80" required></div>
        <div class="fg"><label for="uApe">Apellido</label><input id="uApe" maxlength="80" required></div>
      </div>
      <div class="fg"><label for="uMail">Correo electrónico</label><input type="email" id="uMail" maxlength="120" required></div>
      <div class="row2">
        <div class="fg"><label for="uTel">Teléfono</label><input id="uTel" maxlength="20"></div>
        <div class="fg"><label for="uPass">Nueva contraseña (opcional)</label><input type="password" id="uPass" minlength="6" autocomplete="new-password" placeholder="Dejar vacío para no cambiarla"></div>
      </div>
      <div class="row2">
        <div class="fg"><label for="uPais">País</label><input id="uPais" maxlength="100"></div>
        <div class="fg"><label for="uCiudad">Ciudad</label><input id="uCiudad" maxlength="100"></div>
      </div>
      <div class="row2">
        <div class="fg"><label for="uBarrio">Barrio</label><input id="uBarrio" maxlength="100"></div>
        <div class="fg"><label for="uDir">Dirección</label><input id="uDir" maxlength="255"></div>
      </div>
      <div class="mf"><button type="button" class="btn ghost" data-close>Cancelar</button><button class="btn ok">Guardar usuario</button></div>
    </form>
  </div>

  <!-- MODAL CONFIRMAR ELIMINAR -->
  <div class="modal" id="mDel">
    <div class="mc">
      <h3>Confirmar acción</h3>
      <p id="delMsg"></p>
      <div class="mf"><button class="btn ghost" data-close>Cancelar</button><button class="btn bad" id="delOk">Eliminar</button></div>
    </div>
  </div>

  <!-- MODAL DETALLE PEDIDO -->
  <div class="modal" id="mOrd">
    <div class="mc">
      <h3 id="ordTitle">Detalle del pedido699999</h3>
      <div class="oinfo" id="ordInfo"></div>
      <div class="tw">
        <table style="min-width:0">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cant.</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody id="ordDet"></tbody>
        </table>
      </div>
      <div class="mf"><button class="btn ghost" data-close>Cerrar</button></div>
    </div>
  </div>

  <!-- MODAL COMPROBANTE (dinero digital) -->
  <div class="modal" id="mComp">
    <div class="mc">
      <div class="rcpt" id="compBody"></div>
      <div class="mf"><button class="btn ghost" id="compPrint">Imprimir</button><button class="btn ghost" data-close>Cerrar</button></div>
    </div>
  </div>

  <div class="scrim" id="scrim" hidden></div>
  <aside class="drawer" id="drawer" aria-label="Carrito de compras" aria-hidden="true">
    <div class="dh">
      <h3>Tu carrito</h3><button type="button" class="x" id="dClose" aria-label="Cerrar carrito">✕</button>
    </div>
    <div class="db" id="dBody"></div>
    <div class="df" id="dFoot"></div>
  </aside>
  <div class="modal" id="mInfo">
    <div class="mc">
      <h3 id="infTitle"></h3>
      <div id="infBody"></div>
      <div class="mf" id="infBtns"></div>
    </div>
  </div>
  <!-- MODAL TÉRMINOS Y CONDICIONES -->
  <div class="modal" id="mTerms">
    <div class="mc tc" role="dialog" aria-labelledby="tcTitle">
      <div class="tc-bar" id="tcBar"></div>
      <div class="tc-top">
        <div>
          <h3 id="tcTitle">Términos y condiciones</h3>
          <p class="sub">Última actualización: noviembre de 2026</p>
        </div><button type="button" class="btn ghost sm" id="tcAll">Expandir todo</button>
      </div>
      <details>
        <summary>1. Aceptación</summary>
        <div class="tc-b">
          <p>Al crear una cuenta, navegar o comprar en esta tienda aceptas estos términos. Si no estás de acuerdo, no uses el servicio. Podemos actualizarlos; la versión vigente es la publicada en esta página.</p>
        </div>
      </details>
      <details>
        <summary>2. Tu cuenta</summary>
        <div class="tc-b">
          <ul>
            <li>Debes ser mayor de edad y tener capacidad legal para contratar.</li>
            <li>Los datos que registres deben ser reales y estar actualizados.</li>
            <li>Eres responsable de tu contraseña y de lo que se haga con tu cuenta. Avísanos si detectas un uso no autorizado.</li>
            <li>Podemos suspender cuentas con datos falsos o uso fraudulento.</li>
          </ul>
        </div>
      </details>
      <details>
        <summary>3. Productos, precios y disponibilidad</summary>
        <div class="tc-b">
          <p>Los precios están en pesos colombianos (COP). El inventario se muestra en tiempo real y se descuenta cuando confirmas el pedido. Las imágenes son ilustrativas. Si un producto tiene un error evidente de precio o de stock, podemos cancelar el pedido y devolverte lo pagado.</p>
        </div>
      </details>
      <details>
        <summary>4. Pedidos y su estado</summary>
        <div class="tc-b">
          <p>Al confirmar el pedido se crea en estado <b>Pendiente</b>. Luego pasa por <b>Pagado</b>, <b>Enviado</b> y <b>Entregado</b>, o termina en <b>Cancelado</b>. Te notificamos los cambios al correo registrado y puedes verlos en “Mis pedidos”. Antes de confirmar revisa productos, cantidades, dirección y método de pago.</p>
        </div>
      </details>
      <details>
        <summary>5. Métodos de pago</summary>
        <div class="tc-b">
          <p>Puedes pagar con tarjeta de crédito, PSE, Nequi, contraentrega o dinero digital.</p>
          <p><b>Dinero digital:</b> es un saldo interno de la tienda, simulado, sin valor monetario real. Solo sirve para comprar aquí: no se puede retirar, transferir ni cambiar por dinero. Cada pago genera un comprobante, y si un pedido pagado con este saldo se cancela, el valor se devuelve a tu saldo.</p>
        </div>
      </details>
      <details>
        <summary>6. Envíos y entrega</summary>
        <div class="tc-b">
          <p>Enviamos a todo el país. Debes indicar país, ciudad, barrio y dirección completos y correctos; si el envío falla por datos errados o porque nadie recibe, los costos o demoras adicionales pueden ser tu responsabilidad. Revisa el paquete al recibirlo y repórtanos cualquier daño de inmediato.</p>
        </div>
      </details>
      <details>
        <summary>7. Cancelaciones, retracto y devoluciones</summary>
        <div class="tc-b">
          <ul>
            <li><b>Cancelación:</b> puedes cancelar desde “Mis pedidos” mientras el pedido esté en estado Pendiente. El stock vuelve al inventario.</li>
            <li><b>Derecho de retracto:</b> en compras a distancia puedes retractarte dentro de los 5 días hábiles siguientes a la entrega del producto (artículo 47 de la Ley 1480 de 2011 y normas que lo modifiquen). Escríbenos a <span class="tcContact"></span>.</li>
            <li>El producto debe devolverse en buen estado y con sus accesorios. Los costos de la devolución los asume el consumidor y, si el retracto procede, te devolvemos todo lo pagado sin descuentos ni retenciones.</li>
            <li>Aplican las excepciones que establece la ley.</li>
          </ul>
        </div>
      </details>
      <details>
        <summary>8. Garantía</summary>
        <div class="tc-b">
          <p>Los productos cuentan con la garantía legal del Estatuto del Consumidor (Ley 1480 de 2011) y, cuando exista, con la garantía del fabricante. Si tu producto falla, contáctanos con el número de pedido para gestionar la reclamación.</p>
        </div>
      </details>
      <details>
        <summary>9. Tus datos personales</summary>
        <div class="tc-b">
          <p>Tratamos tus datos conforme a la Ley 1581 de 2012 solo para gestionar tu cuenta, tus compras, los envíos y las notificaciones de tus pedidos. No los vendemos. Tienes derecho a conocer, actualizar, rectificar y solicitar la supresión de tus datos escribiendo a <span class="tcContact"></span>. Puedes editarlos en “Mi perfil”.</p>
        </div>
      </details>
      <details>
        <summary>10. Uso adecuado y propiedad intelectual</summary>
        <div class="tc-b">
          <p>No puedes usar la tienda para actividades ilícitas, intentar vulnerar su seguridad ni copiar su contenido. Logos, textos e imágenes pertenecen a la tienda o a sus titulares.</p>
        </div>
      </details>
      <details>
        <summary>11. Responsabilidad, cambios y ley aplicable</summary>
        <div class="tc-b">
          <p>No respondemos por fallas de internet, de pasarelas de pago o de transportadoras ajenas a nuestro control, salvo lo que la ley disponga. Estos términos se rigen por las leyes de Colombia. Ante cualquier reclamo puedes acudir también a la Superintendencia de Industria y Comercio.</p>
        </div>
      </details>
      <div class="mf"><button type="button" class="btn ghost" data-close>Cerrar</button><button type="button" class="btn ok" id="tcAccept">Aceptar y continuar</button></div>
    </div>
  </div>

  <!-- OVERLAY DE SESIÓN (carga + confirmación) -->
  <div class="authfx" id="authFx" role="status" aria-live="polite" hidden>
    <div class="afx-card">
      <div class="afx-icon">
        <div class="afx-spin"></div>
        <svg class="afx-ok" viewBox="0 0 72 72" aria-hidden="true">
          <circle cx="36" cy="36" r="30" />
          <path d="M23 37l9 9 17-19" />
        </svg>
      </div>
      <div class="afx-txt" id="afxTxt"></div>
      <div class="afx-sub" id="afxSub"></div>
    </div>
  </div>

  <div id="toasts" aria-live="polite"></div>
  <div id="loader" role="status" aria-live="polite" aria-hidden="true">
    <div class="lbox">
      <div class="spinner"></div><span id="loaderMsg">TechnoStore.</span>
    </div>
  </div>

  <script>
    /* ---------- Utilidades ---------- */
    const $ = s => document.querySelector(s);
    const $$ = s => [...document.querySelectorAll(s)];
    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    } [c]));
    const money = n => '$' + Number(n || 0).toLocaleString('es-CO');
    const PALETA = [
      ['#1F4E79', '#3d8bd1'],
      ['#6a3fb5', '#b07cf0'],
      ['#0f8b6d', '#4cd1a3'],
      ['#c0562b', '#f2a65a'],
      ['#b0306a', '#f06fa4'],
      ['#2b3a8c', '#5a7bf0'],
      ['#2e7d8c', '#58c3d6'],
      ['#7a5c12', '#e0b84a']
    ];
    /* Imagen de respaldo: ilustración por categoría (se usa si no existe la foto en img/) */
    function art(d) {
      const n = d.n || '',
        c = (d.c || '').toLowerCase();
      let k = 0;
      for (const ch of n) k = (k * 31 + ch.charCodeAt(0)) >>> 0;
      const [a, b] = PALETA[k % PALETA.length], W = 'fill="#fff" fill-opacity=".93"';
      let ic;
      if (c.includes('celular')) ic = `<rect x="155" y="38" width="90" height="190" rx="16" ${W}/><rect x="164" y="54" width="72" height="150" rx="6" fill="${a}" fill-opacity=".55"/><circle cx="200" cy="216" r="5" fill="${a}"/>`;
      else if (c.includes('comput')) ic = `<rect x="110" y="58" width="180" height="120" rx="9" ${W}/><rect x="120" y="68" width="160" height="100" rx="4" fill="${a}" fill-opacity=".55"/><path d="M80 190h240l-14 24H94z" ${W}/>`;
      else if (c.includes('audio')) ic = `<path d="M120 160a80 80 0 0 1 160 0" fill="none" stroke="#fff" stroke-opacity=".93" stroke-width="14" stroke-linecap="round"/><rect x="104" y="150" width="34" height="68" rx="14" ${W}/><rect x="262" y="150" width="34" height="68" rx="14" ${W}/>`;
      else if (c.includes('gaming')) ic = `<rect x="105" y="85" width="190" height="105" rx="46" ${W}/><path d="M146 120v36M128 138h36" stroke="${a}" stroke-width="9" stroke-linecap="round"/><circle cx="246" cy="128" r="9" fill="${a}"/><circle cx="268" cy="150" r="9" fill="${b}"/>`;
      else if (c.includes('hogar')) ic = `<path d="M110 150l90-72 90 72v78H110z" ${W}/><rect x="176" y="170" width="48" height="58" rx="6" fill="${a}" fill-opacity=".6"/>`;
      else ic = `<path d="M120 100l80-38 80 38v90l-80 38-80-38z" ${W}/><path d="M120 100l80 38 80-38M200 138v90" stroke="${a}" stroke-width="6" fill="none"/>`;
      const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="${a}"/><stop offset="1" stop-color="${b}"/></linearGradient></defs><rect width="400" height="300" fill="url(#g)"/><circle cx="335" cy="45" r="90" fill="#fff" fill-opacity=".07"/><circle cx="55" cy="285" r="110" fill="#fff" fill-opacity=".06"/>${ic}<text x="200" y="274" text-anchor="middle" font-family="Arial,sans-serif" font-size="22" font-weight="700" fill="#fff" fill-opacity=".92">${esc(d.m || '')}</text></svg>`;
      return 'data:image/svg+xml,' + encodeURIComponent(svg);
    }
    const dat = p => `data-n="${esc(p.nombre)}" data-c="${esc(p.categoria)}" data-m="${esc(p.marca)}"`;
    const imageSrc = p => p.imagen_url || art({ n: p.nombre, c: p.categoria, m: p.marca });
    document.addEventListener('error', e => {
      const i = e.target;
      if (i.tagName === 'IMG' && i.dataset.n !== undefined && !i.dataset.fb) {
        i.dataset.fb = 1;
        i.src = art(i.dataset);
      }
    }, true);

    let user = null,
      cats = [],
      prods = [];
    try {
      user = JSON.parse(localStorage.getItem('currentUser'));
    } catch (e) {}

    function toast(msg, err) {
      const t = document.createElement('div');
      t.className = 'toast' + (err ? ' err' : '');
      t.textContent = msg;
      $('#toasts').append(t);
      setTimeout(() => t.remove(), 3500);
    }

    /* ---------- Cargando: círculo giratorio global ---------- */
    const Loader = (() => {
      let n = 0,
        timer = null,
        shownAt = 0;
      const box = () => $('#loader');
      const paint = on => {
        box().classList.toggle('on', on);
        box().setAttribute('aria-hidden', on ? 'false' : 'true');
      };
      return {
        start(msg, delay = 150) { // delay: evita parpadeos si la carga es rápida
          if (msg) $('#loaderMsg').textContent = msg;
          if (++n === 1) timer = setTimeout(() => {
            shownAt = Date.now();
            paint(true);
          }, delay);
        },
        stop() {
          if (n > 0) n--;
          if (n > 0) return;
          clearTimeout(timer);
          if (box().classList.contains('on')) // una vez visible, se queda al menos 300 ms
            setTimeout(() => {
              if (n === 0) {
                paint(false);
                $('#loaderMsg').textContent = 'TechnoStore.';
              }
            }, Math.max(0, 300 - (Date.now() - shownAt)));
        },
        async track(p, msg, delay = 0) {
          this.start(msg, delay);
          try {
            return await p;
          } finally {
            this.stop();
          }
        }
      };
    })();
    const sleep = ms => new Promise(r => setTimeout(r, ms));
    /* Botón con círculo giratorio mientras dura una promesa */
    async function busy(btn, p) {
      if (btn) {
        btn.classList.add('loading');
        btn.disabled = true;
      }
      try {
        return await p;
      } finally {
        if (btn) {
          btn.classList.remove('loading');
          btn.disabled = false;
        }
      }
    }

    /* Cliente API: GET para lecturas, POST (FormData) para escrituras */
    const API_SILENT = new Set(['obtener_ciudades', 'sesion']); // sin spinner (autocompletado / verificación)
    /* ---------- Overlay de sesión ---------- */
    function fxLoading(txt, sub = 'Un momento por favor…') {
      const f = $('#authFx');
      f.classList.remove('done');
      $('#afxTxt').textContent = txt;
      $('#afxSub').textContent = sub;
      f.hidden = false;
    }
    async function fxDone(txt, sub = '') {
      const f = $('#authFx');
      if (f.hidden) f.hidden = false;
      f.classList.remove('done');
      void f.offsetWidth; // reinicia la animación del check
      f.classList.add('done');
      $('#afxTxt').textContent = txt;
      $('#afxSub').textContent = sub;
      await sleep(1500);
      f.hidden = true;
      f.classList.remove('done');
    }
    const fxHide = () => {
      const f = $('#authFx');
      f.hidden = true;
      f.classList.remove('done');
    };

    /* Cliente API: GET para lecturas, POST (FormData) para escrituras */

    async function api(action, params = {}, post = false) {
      const spin = !API_SILENT.has(action);
      if (spin) Loader.start(null, 250); // solo aparece si la respuesta tarda más de 250 ms
      try {
        let res;
        if (post) {
          const fd = new FormData();
          fd.append('action', action);
          Object.entries(params).forEach(([k, v]) => v !== undefined && v !== null && fd.append(k, v));
          res = await fetch('backend/endpoints/api.php', {
            method: 'POST',
            body: fd
          });
        } else {
          res = await fetch('backend/endpoints/api.php?' + new URLSearchParams({
            action,
            ...params
          }));
        }
        return await res.json();
      } catch (e) {
        return {
          success: false,
          error: 'No se pudo conectar con el servidor.'
        };
      } finally {
        if (spin) Loader.stop();
      }
    }
    let countryOptions = null;
    const cityOptionsCache = new Map();

    async function loadCountries() {
      if (countryOptions) return countryOptions;
      const codes = 'AF AL DZ AD AO AG AR AM AU AT AZ BS BD BB BY BE BZ BJ BT BO BA BW BR BN BG BF BI CV KH CM CA CF TD CL CN CO KM CG CR CI HR CU CY CZ DK DJ DM DO EC EG SV GQ ER EE SZ ET FJ FI FR GA GM GE DE GH GR GD GT GN GW GY HT HN HU IS IN ID IR IQ IE IL IT JM JP JO KZ KE KI KP KR KW KG LA LV LB LS LR LY LI LT LU MG MW MY MV ML MT MH MR MU MX FM MD MC MN ME MA MZ MM NA NR NP NL NZ NI NE NG MK NO OM PK PW PA PG PY PE PH PL PT QA RO RU RW KN LC VC WS SM ST SA SN RS SC SL SG SK SI SB SO ZA SS ES LK SD SR SE CH SY TJ TZ TH TL TG TO TT TN TR TM TV UG UA AE GB US UY UZ VU VA VE VN YE ZM ZW';
      const displayNames = new Intl.DisplayNames(['es'], {
        type: 'region'
      });
      countryOptions = codes.split(' ').map(code => {
        try {
          return displayNames.of(code);
        } catch (e) {
          return code;
        }
      }).filter(Boolean).sort((a, b) => a.localeCompare(b, 'es'));
      return countryOptions;
    }

    async function loadCities(country, query = '') {
      if (!country || !query.trim()) return [];
      const key = country + '\0' + query.trim().toLowerCase();
      if (cityOptionsCache.has(key)) return cityOptionsCache.get(key);
      const r = await api('obtener_ciudades', {
        pais: country,
        q: query.trim()
      });
      const cities = r.success ? r.data : [];
      cityOptionsCache.set(key, cities);
      return cities;
    }

    function bindAddressAutocomplete(input, getOptions, countryInput = null, clearOnCountryChange = false) {
      if (!input || input.dataset.autocompleteBound) return;
      input.dataset.autocompleteBound = '1';
      input.parentElement.classList.add('autocomplete');
      const list = document.createElement('div');
      list.className = 'autocomplete-list';
      list.hidden = true;
      input.parentElement.appendChild(list);
      let active = -1;
      let options = [];
      let lastCountry = countryInput ? countryInput.value : '';

      const closeList = () => {
        list.hidden = true;
        active = -1;
      };
      const show = values => {
        options = values.slice(0, 12);
        active = -1;
        list.innerHTML = options.map(value => `<button type="button">${esc(value)}</button>`).join('');
        list.hidden = options.length === 0;
        [...list.children].forEach((button, index) => {
          button.onmousedown = e => e.preventDefault();
          button.onclick = () => selectOption(index);
        });
      };
      const selectOption = index => {
        if (!options[index]) return;
        input.value = options[index];
        closeList();
        input.dispatchEvent(new Event('input', {
          bubbles: true
        }));
        input.dispatchEvent(new Event('change', {
          bubbles: true
        }));
      };
      const refreshList = async () => {
        const query = input.value.trim().toLowerCase();
        const values = await getOptions();
        show(values.filter(value => !query || value.toLowerCase().includes(query)));
      };
      input.addEventListener('input', refreshList);
      input.addEventListener('focus', refreshList);
      input.onkeydown = e => {
        const buttons = [...list.children];
        if (!buttons.length) return;
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
          e.preventDefault();
          active += e.key === 'ArrowDown' ? 1 : -1;
          if (active < 0) active = buttons.length - 1;
          if (active >= buttons.length) active = 0;
          buttons.forEach((button, index) => button.classList.toggle('active', index === active));
          buttons[active].scrollIntoView({
            block: 'nearest'
          });
        } else if (e.key === 'Enter' && active >= 0) {
          e.preventDefault();
          selectOption(active);
        } else if (e.key === 'Escape') closeList();
      };
      input.onblur = () => setTimeout(closeList, 120);

      if (countryInput && clearOnCountryChange) {
        countryInput.addEventListener('input', () => {
          if (lastCountry !== countryInput.value) {
            input.value = '';
            input.dispatchEvent(new Event('input', {
              bubbles: true
            }));
            lastCountry = countryInput.value;
          }
        });
      }
    }

    function setupAddressAutocomplete(prefix) {
      const country = $('#' + prefix + 'Pais');
      const city = $('#' + prefix + 'Ciudad');
      if (!country || !city) return;
      bindAddressAutocomplete(country, loadCountries);
      bindAddressAutocomplete(city, async () => loadCities(country.value.trim(), city.value.trim()), country, true);
    }
    setupAddressAutocomplete('r');
    setupAddressAutocomplete('mp');
    setupAddressAutocomplete('u');
    const open = id => $('#' + id).classList.add('on');
    const close = id => $('#' + id).classList.remove('on');
    $$('[data-close]').forEach(b => b.onclick = () => b.closest('.modal').classList.remove('on'));
    $$('.modal').forEach(m => m.addEventListener('mousedown', e => {
      if (e.target === m) m.classList.remove('on');
    }));
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') $$('.modal.on').forEach(m => m.classList.remove('on'));
    });

            /* ---------- Inicio ---------- */
            async function syncSession() { // la sesión real vive en el servidor
                const r = await api('sesion');
                user = r.success ? r.user : null;
                user ? localStorage.setItem('currentUser', JSON.stringify(user)) : localStorage.removeItem('currentUser');
                paintUser();
                if (user) {
                  refreshCart();
                  refreshSaldo();
                } else {
                  saldo = null;
                  paintSaldo();
                }
              }
  </script>
  <script src="assets/js/app-session-wallet.js"></script>
  <script src="assets/js/app-catalog-cart.js"></script>
  <script src="assets/js/app-orders-admin.js"></script>
  <script src="assets/js/app-profile-shared.js"></script>
  <script src="assets/js/app.js"></script>
  <script src="features/feedback/feedback.js"></script><script src="features/project-resources/resources.js"></script>
</body>
</html>