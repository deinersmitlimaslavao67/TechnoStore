/* ---------- Catálogo público ---------- */
            const isHex = h => /^#[0-9a-fA-F]{6}$/.test(h || '');

            function shade(hex, f) { // f < 0 oscurece, f > 0 aclara
              const n = parseInt(hex.slice(1), 16);
              return '#' + [n >> 16, (n >> 8) & 255, n & 255].map(v => Math.round(f < 0 ? v * (1 + f) : v + (255 - v) * f).toString(16).padStart(2, '0')).join('');
            }
            const lum = hex => {
              const n = parseInt(hex.slice(1), 16);
              return (0.299 * (n >> 16) + 0.587 * ((n >> 8) & 255) + 0.114 * (n & 255)) / 255;
            };
            async function loadStore() {
              const r = await api('obtener_tienda');
              if (!(r.success && r.data)) return;
              const d = r.data,
                root = document.documentElement.style,
                hero = $('.hero');
              $('#storeTitle').textContent = $('#bannerTitle').textContent = d.nombre_tienda;
              $('#bannerDesc').textContent = d.descripcion_tienda || '';
              $('#footName').textContent = d.nombre_tienda;
              $('#footContact').textContent = [d.correo_contacto, d.telefono_contacto].filter(Boolean).join(' · ');
              document.title = d.nombre_tienda + ' · Catálogo';
              const c1 = isHex(d.color_primario) ? d.color_primario : '#1F4E79',
                c2 = isHex(d.color_secundario) ? d.color_secundario : '#FFF2CC';
              root.setProperty('--brand', c1);
              root.setProperty('--brand-d', shade(c1, -.25));
              root.setProperty('--brand-dd', shade(c1, -.45));
              root.setProperty('--brand-l', shade(c1, .25));
              root.setProperty('--sand', c2);
              root.setProperty('--accent', c2);
              root.setProperty('--acc', lum(c1) > .5 ? c1 : shade(c1, .55));
              const lg = $('#storeLogo');
              lg.onerror = () => {
                lg.hidden = true;
              };
              if (d.logo_url) {
                lg.src = d.logo_url;
                lg.hidden = false;
              } else lg.hidden = true;
              const ban = d.banner_url && !/["'()\\\s]/.test(d.banner_url) ? d.banner_url : '';
              hero.style.backgroundImage = ban ? `linear-gradient(135deg, ${shade(c1, -.45)}e6, ${c1}b3), url("${ban}")` : '';
              hero.style.backgroundSize = ban ? 'cover' : '';
              hero.style.backgroundPosition = ban ? 'center' : '';
            }
            async function loadCats() {
              const r = await api('listar_categorias');
              cats = r.success ? r.data : [];
              const opts = cats.map(c => `<option value="${esc(c.id_categoria)}">${esc(c.nombre)}</option>`).join('');
              $('#pCat').innerHTML = opts;
              $('#fCat').innerHTML = '<option value="">Todas las categorías</option>' + opts;
            }
            let cur = [],
              curCat = '';

            function renderCatalog() {
              const cs = [...new Set(cur.map(p => p.categoria).filter(Boolean))];
              $('#chips').innerHTML = ['', ...cs].map(c => `<button class="chip ${c === curCat ? 'on' : ''}" data-c="${esc(c)}">${c ? esc(c) : 'Todos'}</button>`).join('');
              const s = $('#sortSel').value;
              const list = cur.filter(p => !curCat || p.categoria === curCat).sort(
                s === 'asc' ? (a, b) => a.precio - b.precio : s === 'desc' ? (a, b) => b.precio - a.precio : s === 'name' ? (a, b) => a.nombre.localeCompare(b.nombre) : () => 0);
              $('#count').textContent = list.length + (list.length === 1 ? ' producto' : ' productos');
              $('#productGrid').innerHTML = list.length ? list.map((p, ix) => `
    <article class="card" style="--i:${Math.min(ix, 12)}">
      <div class="ph" onclick="showProduct(${+p.id_producto})">
        <img src="${esc(imageSrc(p))}" alt="${esc(p.nombre)}" loading="lazy" ${dat(p)}>
        ${+p.destacado ? '<span class="tag">Destacado</span>' : ''}${+p.stock > 0 && +p.stock <= 5 ? '<span class="tag low">Últimas unidades</span>' : ''}
      </div>
      <div class="b">
        <div class="m">${esc(p.marca)}</div>
        <h3 onclick="showProduct(${+p.id_producto})">${esc(p.nombre)}</h3>
        <div class="p">${money(p.precio)}</div>
        <div class="m">${esc(p.categoria)} · ${+p.stock > 0 ? p.stock + ' disponibles' : 'Agotado'}</div>
      </div>
      <div class="f"><button class="btn ghost full" onclick="showProduct(${+p.id_producto})">Ver detalles</button><button class="btn ok full" ${+p.stock > 0 ? '' : 'disabled'} onclick="addCart(${+p.id_producto})">Agregar al carrito</button></div>
    </article>`).join('') : '<div class="empty">No encontramos productos con esos criterios.</div>';
            }
            async function loadList(action, params) {
              const r = await api(action, params);
              if (!r.success) return toast(r.error || 'Error al cargar productos', true);
              cur = r.data;
              curCat = '';
              renderCatalog();
            }
            $('#chips').onclick = e => {
              const b = e.target.closest('.chip');
              if (b) {
                curCat = b.dataset.c;
                renderCatalog();
              }
            };
            $('#sortSel').onchange = renderCatalog;
            $('#goCat').onclick = () => $('#v-catalog').scrollIntoView({
              behavior: 'smooth'
            });
            $('#goFeat').onclick = () => {
              $('#bFeat').click();
              $('#v-catalog').scrollIntoView({
                behavior: 'smooth'
              });
            };

            function heroArt(list) {
              $('#heroArt').innerHTML = list.slice(0, 3).map((p, i) => `<div class="hc hc${i}"><img src="${esc(imageSrc(p))}" alt="" ${dat(p)}><div><b>${esc(p.nombre)}</b><span>${money(p.precio)}</span></div></div>`).join('');
            }
            $('#bSearch').onclick = () => loadList('buscar_productos', {
              q: $('#searchInput').value
            });
            $('#searchInput').onkeydown = e => {
              if (e.key === 'Enter') $('#bSearch').click();
            };
            $('#bAll').onclick = () => loadList('obtener_vista_catalogo');
            $('#bFeat').onclick = () => loadList('obtener_destacados');

            /* ---------- Carrito ---------- */
            let termsOk = false; // aceptación de términos (se pide en el registro y antes de confirmar el pedido)
            let cart = [],
              cartAddress = {
                pais: '',
                ciudad: '',
                barrio: '',
                direccion: ''
              },
              cartPay = 'Tarjeta crédito';

            function paintBadge() {
              const n = cart.reduce((s, i) => s + +i.cantidad, 0),
                b = $('#cartBadge');
              b.textContent = n;
              b.hidden = !n;
              paintDrawer();
            }
            async function refreshCart() {
              if (!user) {
                cart = [];
                paintBadge();
                return;
              }
              const r = await api('ver_carrito');
              cart = r.success ? r.data : [];
              if (r.success && !Object.values(cartAddress).some(Boolean)) {
                cartAddress = {
                  pais: r.pais || '',
                  ciudad: r.ciudad || '',
                  barrio: r.barrio || '',
                  direccion: r.direccion || ''
                };
              }
              paintBadge();
            }
            async function loadCart() {
              await Promise.all([refreshCart(), refreshSaldo()]);
              paintCart();
            }

            function paintCart() {
              const box = $('#cartBox');
              if (!cart.length) {
                box.innerHTML = '<div class="empty" style="padding:3rem 1rem"><p style="font-size:1.1rem;margin-bottom:1rem">Tu carrito está vacío</p><button class="btn" onclick="show(\'catalog\')">Ir al catálogo</button></div>';
                return;
              }
              const total = cart.reduce((s, i) => s + +i.subtotal, 0),
                units = cart.reduce((s, i) => s + +i.cantidad, 0);
              box.innerHTML = `<div class="cartwrap"><div>
    ${cart.map(i => `<div class="ci">
      <img src="${esc(imageSrc(i))}" alt="" ${dat(i)}>
      <div class="ci-t"><strong>${esc(i.nombre)}</strong><span>${esc(i.marca)} · ${esc(i.categoria)}</span><span>${money(i.precio)} c/u</span></div>
      <div class="qty"><button aria-label="Quitar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad - 1})">−</button><span>${+i.cantidad}</span><button aria-label="Agregar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad + 1})" ${+i.cantidad >= +i.stock ? 'disabled' : ''}>+</button></div>
      <div class="ci-s">${money(i.subtotal)}</div>
      <button class="x" aria-label="Quitar ${esc(i.nombre)} del carrito" onclick="setQty(${+i.id_producto},0)">✕</button>
    </div>`).join('')}
    <div class="ci-foot"><button class="btn ghost sm" onclick="show('catalog')">← Seguir comprando</button><button class="btn ghost sm" onclick="askDelete('carrito',0,'')">Vaciar carrito</button></div>
  </div>
  <aside class="sum"><h3>Resumen del pedido</h3>
    <div class="line"><span>Productos (${units})</span><span>${money(total)}</span></div>
    <div class="line tot"><span>Total</span><span>${money(total)}</span></div>
    <div class="row2">
      <div class="fg"><label for="cPais">País</label><input id="cPais" maxlength="100" value="${esc(cartAddress.pais)}" required oninput="cartAddress.pais=this.value"></div>
      <div class="fg"><label for="cCiudad">Ciudad</label><input id="cCiudad" maxlength="100" value="${esc(cartAddress.ciudad)}" required oninput="cartAddress.ciudad=this.value"></div>
    </div>
    <div class="fg"><label for="cBarrio">Barrio</label><input id="cBarrio" maxlength="100" value="${esc(cartAddress.barrio)}" required oninput="cartAddress.barrio=this.value"></div>
    <div class="fg"><label for="cDir">Dirección</label><input id="cDir" maxlength="255" value="${esc(cartAddress.direccion)}" required oninput="cartAddress.direccion=this.value"></div>
    <div class="fg"><label for="cPay">Método de pago</label><select id="cPay" onchange="cartPay=this.value;paintPayInfo()">${['Tarjeta crédito', 'PSE', 'Nequi', 'Contraentrega', PAY_DIGITAL].map(m => `<option ${m === cartPay ? 'selected' : ''}>${m}</option>`).join('')}</select></div>
    <div id="payInfo" aria-live="polite"></div>
    <div class="fg chk terms-row" id="cTermsRow"><label><input type="checkbox" id="cTerms" ${termsOk ? 'checked' : ''} onchange="termsOk=this.checked"><span>He leído y acepto los <button type="button" class="linkbtn" data-terms>términos y condiciones</button></span></label></div>
    <button class="btn ok full" id="bCheckout" onclick="checkout()">Confirmar pedido</button>
  </aside></div>`;
              setupAddressAutocomplete('c');
              paintPayInfo();
            }
            async function addCart(id) {
              if (!user) {
                toast('Inicia sesión para usar el carrito', true);
                return openAuth();
              }
              const r = await api('agregar_carrito', {
                id_producto: id,
                cantidad: 1
              }, true);
              if (!r.success) return toast(r.error, true);
              await refreshCart();
              flyToCart();
              setTimeout(() => confirmAdd(id), flyDone ? 520 : 0);
            }
            async function setQty(id, n) { // n <= 0 quita el producto
              const r = await api('actualizar_carrito', {
                id_producto: id,
                cantidad: n
              }, true);
              if (!r.success) toast(r.error, true);
              else if (n <= 0) toast('Producto quitado del carrito');
              loadCart();
            }
            /* Panel informativo cuando se elige "Dinero digital": saldo, total y saldo restante */
            function paintPayInfo() {
              const box = $('#payInfo'),
                btn = $('#bCheckout');
              if (!box || !btn) return;
              const total = cart.reduce((s, i) => s + +i.subtotal, 0);
              if (cartPay !== PAY_DIGITAL) {
                box.innerHTML = '';
                btn.disabled = false;
                btn.textContent = 'Confirmar pedido';
                return;
              }
              if (saldo === null) {
                box.innerHTML = '<div class="paybox"><span class="sub">Consultando tu saldo…</span></div>';
                btn.disabled = true;
                return;
              }
              const falta = total - saldo;
              if (falta > 0) {
                box.innerHTML = `<div class="paybox low"><div class="line"><span>Tu saldo</span><b>${money(saldo)}</b></div><div class="line"><span>Total del pedido</span><b>${money(total)}</b></div><div class="line"><span>Te faltan</span><b style="color:#ff8f86">${money(falta)}</b></div><button type="button" class="btn ghost sm" onclick="recargarFalta(${falta})">Recargar lo que falta</button></div>`;
                btn.disabled = true;
                btn.textContent = 'Saldo insuficiente';
              } else {
                box.innerHTML = `<div class="paybox ok"><div class="line"><span>Tu saldo</span><b>${money(saldo)}</b></div><div class="line"><span>Total del pedido</span><b>− ${money(total)}</b></div><div class="line"><span>Saldo después de pagar</span><b>${money(saldo - total)}</b></div></div>`;
                btn.disabled = false;
                btn.textContent = `Pagar ${money(total)} con dinero digital`;
              }
            }
            async function recargarFalta(falta) { // redondea hacia arriba a miles y respeta el tope por recarga
              await recargar(Math.min(RECARGA_MAX, Math.ceil(falta / 1000) * 1000));
            }
            let pagando = false;
            async function checkout() {
              if (pagando) return; // evita doble clic (el backend también lo bloquea)
              if (!termsOk) { // los términos son obligatorios para confirmar
                const row = $('#cTermsRow');
                if (row) {
                  row.classList.remove('shake');
                  void row.offsetWidth;
                  row.classList.add('shake');
                  $('#cTerms').focus();
                }
                return toast('Acepta los términos y condiciones para confirmar el pedido', true);
              }
              const btn = $('#bCheckout'),
                txt = btn ? btn.textContent : '';
              const digital = cartPay === PAY_DIGITAL,
                tot = cart.reduce((s, i) => s + +i.subtotal, 0);
              pagando = true;
              if (btn) {
                btn.disabled = true;
                btn.textContent = digital ? 'Procesando pago…' : 'Confirmando…';
              }
              const r = await api('confirmar_pedido', {
                pais_envio: cartAddress.pais.trim(),
                ciudad_envio: cartAddress.ciudad.trim(),
                barrio_envio: cartAddress.barrio.trim(),
                direccion_envio: cartAddress.direccion.trim(),
                metodo_pago: cartPay
              }, true);
              pagando = false;
              if (!r.success) {
                if (btn) {
                  btn.disabled = false;
                  btn.textContent = txt;
                }
                if (digital && /saldo/i.test(r.error || '')) { // saldo insuficiente: aviso claro + salida rápida
                  await refreshSaldo();
                  return notice('Saldo insuficiente', `<p>${esc(r.error)}</p><p class="sub" style="margin-top:.5rem">No se descontó nada ni se reservó stock. Recarga tu saldo o elige otro método de pago.</p>`,
                    [
                      ['Elegir otro método', 'ghost', () => close('mInfo')],
                      ['Ir a recargar', 'ok', () => {
                        close('mInfo');
                        show('profile');
                      }]
                    ]);
                }
                return toast(r.error, true);
              }
              cart = [];
              paintBadge();
              loadList('listar_catalogo');
              if (digital && r.comprobante) {
                saldo = +r.saldo;
                paintSaldo();
                notice('¡Pago realizado!', `<p>Pagaste <b>${money(tot)}</b> con dinero digital. Tu pedido <b>#${r.id_pedido}</b> quedó en estado <b>PAGADO</b>.</p><p style="margin-top:.5rem">Comprobante: <b>${esc(r.comprobante)}</b><br>Saldo restante: <b>${money(r.saldo)}</b></p>`,
                  [
                    ['Ver mis pedidos', 'ghost', () => {
                      close('mInfo');
                      show('orders');
                    }],
                    ['Ver comprobante', 'ok', () => {
                      close('mInfo');
                      show('orders');
                      verComprobante(+r.id_pedido);
                    }]
                  ]);
              } else {
                notice('¡Pedido confirmado!', `<p>Tu pedido <b>#${r.id_pedido}</b> por <b>${money(tot)}</b> quedó registrado. Puedes seguir su estado en “Mis pedidos”.</p>`);
              }
              show('orders');
            }

