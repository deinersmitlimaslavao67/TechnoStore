    /* ---------- Dinero digital (saldo simulado, NO es dinero real) ---------- */
    const PAY_DIGITAL = 'Dinero digital'; // debe coincidir con METODO_DIGITAL del backend
    const RECARGA_MAX = 10000000; // mismo tope por recarga que el backend
    let saldo = null; // null = aún no se ha consultado
    const fechaCorta = f => f ? String(f).slice(0, 16).replace('T', ' ') : '';

    function paintSaldo() {
      $('#navWallet').hidden = !user;
      const txt = saldo === null ? '…' : money(saldo);
      $('#walletAmt').textContent = txt;
      $('#wBig').textContent = saldo === null ? '—' : txt;
      if (typeof paintPayInfo === 'function') paintPayInfo();
    }
    async function refreshSaldo() {
      if (!user) {
        saldo = null;
        paintSaldo();
        return null;
      }
      const r = await api('saldo_digital');
      if (r.success) {
        const antes = saldo;
        saldo = +r.saldo;
        if (antes !== null && antes !== saldo) {
          const b = $('#wBig');
          b.classList.remove('bump');
          void b.offsetWidth;
          b.classList.add('bump');
        }
      }
      paintSaldo();
      return saldo;
    }
    async function recargar(monto) {
      monto = Math.round(+monto);
      if (!(monto > 0)) {
        toast('Escribe un monto válido', true);
        return false;
      }
      if (monto > RECARGA_MAX) {
        toast('La recarga máxima por operación es ' + money(RECARGA_MAX), true);
        return false;
      }
      const r = await api('recargar_saldo', {
        monto
      }, true);
      if (!r.success) {
        toast(r.error || 'No se pudo recargar', true);
        return false;
      }
      saldo = +r.saldo;
      paintSaldo();
      toast(`Recarga de ${money(monto)} realizada`);
      if ($('#v-profile').classList.contains('on')) loadMovs();
      return true;
    }
    const MOV = {
      RECARGA: ['Recarga', 'ok', 'in'],
      REEMBOLSO: ['Reembolso', 'ok', 'in'],
      PAGO: ['Pago de pedido', 'y', 'out']
    };
    async function loadMovs() {
      const r = await api('movimientos_saldo');
      const rows = r.success ? r.data : [];
      $('#wMovs').innerHTML = rows.length ? rows.map(m => {
        const [nom, pill, dir] = MOV[m.tipo] || [m.tipo, '', 'in'];
        return `<tr><td>${esc(fechaCorta(m.fecha))}</td>
      <td><span class="pill ${pill}">${esc(nom)}</span>${m.id_pedido ? ` <span class="sub">pedido #${+m.id_pedido}</span>` : ''}</td>
      <td class="mv ${dir}">${dir === 'in' ? '+' : '−'}${money(m.monto)}</td><td>${money(m.saldo_posterior)}</td>
      <td>${m.comprobante ? `<button class="btn ghost sm" onclick="verComprobante(${+m.id_pedido})">${esc(m.comprobante)}</button>` : '—'}</td></tr>`;
      }).join('') : '<tr><td colspan="5" style="text-align:center;color:var(--mut);padding:2rem">Aún no tienes movimientos.</td></tr>';
    }
    async function verComprobante(idPedido) {
      const r = await api('obtener_comprobante', {
        id_pedido: idPedido
      });
      if (!r.success) return toast(r.error || 'No se pudo cargar el comprobante', true);
      const c = r.data;
      $('#compBody').innerHTML = `<div class="rcpt-h"><b>${esc($('#storeTitle').textContent)}</b><span class="sub">Comprobante de pago virtual · dinero digital</span><div class="rcpt-n">${esc(c.numero)}</div></div>
    <div class="rcpt-i"><div><span>Cliente:</span> ${esc(c.cliente)}</div><div><span>Correo:</span> ${esc(c.correo)}</div><div><span>Pedido:</span> #${+c.id_pedido} · <span class="pill ${ESTADO[c.estado] || ''}">${esc(c.estado)}</span></div><div><span>Fecha:</span> ${esc(fechaCorta(c.fecha_emision))}</div><div><span>Método de pago:</span> ${esc(c.metodo_pago)}</div></div>
    <div class="tw"><table style="min-width:0"><thead><tr><th>Producto</th><th>Cant.</th><th>Subtotal</th></tr></thead><tbody>${c.items.map(i => `<tr><td>${esc(i.producto)}<div class="sub">${money(i.precio_unitario)} c/u</div></td><td>${+i.cantidad}</td><td>${money(i.subtotal)}</td></tr>`).join('')}</tbody></table></div>
    <div class="rcpt-t"><span>Total pagado</span><span>${money(c.monto)}</span></div>
    <p class="rcpt-w">Documento simulado con fines académicos. No tiene validez fiscal.</p>`;
      open('mComp');
    }
    $('#compPrint').onclick = () => window.print();
    $('#navWallet').onclick = () => show('profile');
    $('#wQuick').onclick = e => {
      const b = e.target.closest('[data-m]');
      if (b) recargar(b.dataset.m);
    };
    $('#fRecarga').onsubmit = async e => {
      e.preventDefault();
      const btn = $('#wBtn');
      btn.disabled = true;
      if (await recargar($('#wMonto').value)) $('#fRecarga').reset();
      btn.disabled = false;
    };

    /* ---------- Navegación ---------- */
    function show(v) {
      if (v === 'admin' && !(user && user.rol === 'ADMINISTRADOR')) return toast('Solo los administradores pueden entrar al panel', true);
      if ((v === 'cart' || v === 'orders' || v === 'profile') && !user) return openAuth();
      $('#home').hidden = v !== 'catalog';
      scrollTo(0, 0);
      $$('.view').forEach(s => s.classList.toggle('on', s.id === 'v-' + v));
      $$('nav [data-v]').forEach(b => b.classList.toggle('on', b.dataset.v === v));
      const jobs = [sleep(250)]; // el círculo se ve al menos un instante al cambiar de sección
      if (v === 'orders') jobs.push(loadOrders());
      if (v === 'profile') jobs.push(loadProfile(), refreshSaldo(), loadMovs());
      if (v === 'cart') jobs.push(loadCart());
      if (v === 'admin') jobs.push(loadAdmin());
      Loader.track(Promise.all(jobs), 'Cargando…', 0);
    }
    $$('nav [data-v]').forEach(b => b.onclick = () => show(b.dataset.v));
    $$('.tabs [data-t]').forEach(b => b.onclick = () => {
      $$('#v-admin .tabs button').forEach(x => x.classList.toggle('on', x === b));
      $$('.panel').forEach(p => p.classList.toggle('on', p.id === 't-' + b.dataset.t));
    });

    /* ---------- Sesión ---------- */
    function paintUser() {
      $('#navLogin').hidden = !!user;
      $('#navReg').hidden = !!user;
      $('#navLogout').hidden = !user;
      $('#navOrders').hidden = !user;
      $('#navProfile').hidden = !user;
      $('#navAdmin').hidden = !(user && user.rol === 'ADMINISTRADOR');
      $('#navWallet').hidden = !user;
    }

    function authTab(t) {
      $$('#authTabs button').forEach(b => b.classList.toggle('on', b.dataset.a === t));
      $('#fAuth').hidden = t !== 'login';
      $('#fReg').hidden = t !== 'reg';
    }

    function openAuth(t = 'login') {
      authTab(t);
      open('mAuth');
    }
    $$('#authTabs button').forEach(b => b.onclick = () => authTab(b.dataset.a));
    $('#navLogin').onclick = () => openAuth('login');
    $('#navReg').onclick = () => openAuth('reg');

    $('#navLogout').onclick = async () => {
      await api('logout', {}, true);
      user = null;
      cart = [];
      cartAddress = {
        pais: '',
        ciudad: '',
        barrio: '',
        direccion: ''
      };
      saldo = null;
      paintSaldo();
      paintBadge();
      localStorage.removeItem('currentUser');
      paintUser();
      show('catalog');
      toast('Sesión cerrada');
    };

    function startSession(u) {
      user = u;
      localStorage.setItem('currentUser', JSON.stringify(u));
      close('mAuth');
      paintUser();
      refreshCart();
      refreshSaldo();
      toast(`¡Bienvenido, ${u.nombre}!`);
    }

    async function doLogin(correo, password) {
      const r = await Loader.track(api('login', { correo, password }, true), 'Iniciando sesión…');
      if (!r.success) {
        toast(r.error || 'Correo o contraseña incorrectos', true);
        return false;
      }
      startSession(r.user);
      return true;
    }

    $('#fAuth').onsubmit = e => {
      e.preventDefault();
      busy($('#fAuth button:not([data-close])'), doLogin($('#lEmail').value.trim(), $('#lPass').value));
    };

    $('#fReg').onsubmit = async e => {
      e.preventDefault();
      if ($('#rPass').value !== $('#rPass2').value) return toast('Las contraseñas no coinciden', true);
      if (!$('#rTerms').checked) {
        toast('Debes leer y aceptar los términos y condiciones', true);
        return openTerms(true);
      }

      const correo = $('#rEmail').value.trim();
      const password = $('#rPass').value;
      const btn = $('#fReg button:not([data-close])');
      const r = await busy(btn, Loader.track(api('registrar', {
        nombre: $('#rNom').value.trim(),
        apellido: $('#rApe').value.trim(),
        correo,
        password,
        telefono: $('#rTel').value.trim(),
        pais: $('#rPais').value.trim(),
        ciudad: $('#rCiudad').value.trim(),
        barrio: $('#rBarrio').value.trim(),
        direccion: $('#rDir').value.trim(),
        terminos: 1
      }, true), 'Creando tu cuenta…'));
      if (!r.success) return toast(r.error || 'No se pudo crear la cuenta', true);

      $('#fReg').reset();
      toast('Cuenta creada');
      if (r.user) startSession(r.user);
      else await doLogin(correo, password);
    };

            $('#rTerms').onclick = e => {
              if (e.target.checked) {
                e.preventDefault();
                openTerms();
              }
            }; // para marcarla hay que leer

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

            /* ---------- Pedidos ---------- */
            const ESTADO = {
              PENDIENTE: 'y',
              PAGADO: 'ok',
              ENVIADO: 'ok',
              ENTREGADO: 'ok',
              CANCELADO: 'low'
            };
            async function loadOrders() {
              if (!user) return;
              const r = await api('pedidos_usuario', {
                id_usuario: user.id_usuario
              });
              const rows = (r.success ? r.data : []).filter(o => o.estado !== 'CARRITO');
              $('#ordersBody').innerHTML = rows.length ? rows.map(o => `
    <tr><td>#${esc(o.id_pedido)}</td><td><span class="pill ${ESTADO[o.estado] || ''}">${esc(o.estado)}</span></td><td>${esc(o.fecha_creacion)}</td>
    <td>${esc(o.metodo_pago || 'Pendiente')}</td><td>${money(o.total)}</td>
    <td><div class="acts"><button class="btn ghost sm" onclick="orderDetail(${+o.id_pedido})">Ver detalle</button>${o.metodo_pago === PAY_DIGITAL ? `<button class="btn ghost sm" onclick="verComprobante(${+o.id_pedido})">Comprobante</button>` : ''}${o.estado === 'PENDIENTE' ? `<button class="btn bad sm" onclick="askDelete('pedido',${+o.id_pedido},'')">Cancelar</button>` : ''}</div></td></tr>`).join('') :
                '<tr><td colspan="6" style="text-align:center;color:var(--mut);padding:2rem">Aún no tienes pedidos.</td></tr>';
            }
            async function orderDetail(id) {
              const r = await api('detalle_pedido', {
                id_pedido: id
              });
              if (!r.success) return toast(r.error, true);
              $('#ordTitle').textContent = 'Pedido #' + id;
              $('#ordInfo').innerHTML = '';
              $('#ordDet').innerHTML = r.data.map(d => `<tr><td>${esc(d.producto)}<div class="m" style="color:var(--mut);font-size:.8rem">${esc(d.marca)} · ${money(d.precio_unitario)} c/u</div></td><td>${esc(d.cantidad)}</td><td>${money(d.subtotal)}</td></tr>`).join('');
              open('mOrd');
            }

            /* ---------- ADMIN: Productos (CRUD) ---------- */
            async function loadAdmin() {
              await loadCats();
              await loadProds();
              paintCats();
              loadUsers();
              loadAdminOrders();
              loadTiendaForm();
            }

            const verElim = {
              prod: false,
              cat: false,
              usr: false
            }; // ¿el panel muestra activos o eliminados?
            async function loadProds() {
              const r = verElim.prod ? await api('listar_eliminados', {
                tipo: 'producto'
              }) : await api('admin_productos');
              prods = r.success ? r.data : [];
              paintProds();
            }

            function paintProds() {
              const q = $('#fProd').value.toLowerCase(),
                c = $('#fCat').value,
                del = verElim.prod;
              const list = prods.filter(p => {
                const txt = `${p.nombre} ${p.marca} ${p.categoria}`.toLowerCase();
                const cid = p.id_categoria ?? (cats.find(x => x.nombre === p.categoria) || {}).id_categoria;
                return txt.includes(q) && (!c || String(cid) === c);
              });
              $('#prodBody').innerHTML = list.length ? list.map(p => del ? `
    <tr>
      <td><img class="thumb" src="${esc(imageSrc(p))}" alt="" ${dat(p)}></td>
      <td><strong>${esc(p.nombre)}</strong><div style="color:var(--mut);font-size:.82rem">${esc(p.marca)}</div></td>
      <td>${esc(p.categoria)}${+p.cat_activa ? '' : ' <span class="pill low">Categoría eliminada</span>'}</td>
      <td>${money(p.precio)}</td><td>${+p.stock}</td><td><span class="pill">Eliminado</span></td>
      <td><button class="btn ok sm" onclick="restaurar('producto',${+p.id_producto})">Restaurar</button></td>
    </tr>` : `
    <tr>
      <td><img class="thumb" src="${esc(imageSrc(p))}" alt="" ${dat(p)}></td>
      <td><strong>${esc(p.nombre)}</strong><div style="color:var(--mut);font-size:.82rem">${esc(p.marca)}</div></td>
      <td>${esc(p.categoria)}</td>
      <td>${money(p.precio)}</td>
      <td><input class="stock" type="number" min="0" value="${+p.stock}" aria-label="Stock de ${esc(p.nombre)}" onchange="saveStock(this,'${esc(p.nombre).replace(/'/g, '&#39;')}')"></td>
      <td>${+p.destacado ? '<span class="pill y">Destacado</span>' : ''} ${+p.stock <= 5 ? '<span class="pill low">Stock bajo</span>' : ''}</td>
      <td><div class="acts"><button class="btn ghost sm" onclick="editProd(${+p.id_producto})">Editar</button><button class="btn bad sm" onclick="askDelete('producto',${+p.id_producto},'${esc(p.nombre).replace(/'/g, '&#39;')}')">Eliminar</button></div></td>
    </tr>`).join('') : `<tr><td colspan="7" style="text-align:center;color:var(--mut);padding:2rem">${del ? 'No hay productos eliminados.' : 'No hay productos. Crea el primero con “Nuevo producto”.'}</td></tr>`;
            }
            $('#fProd').oninput = paintProds;
            $('#fCat').onchange = paintProds;

            async function saveStock(inp, nombre) {
              const r = await api('actualizar_stock', {
                nombre_producto: nombre,
                nuevo_stock: inp.value
              }, true);
              if (r.success) {
                toast('Stock actualizado');
                loadProds();
              } else toast(r.error, true);
            }

            function fillProd(p = {}) {
              $('#pId').value = p.id_producto || '';
              $('#pNom').value = p.nombre || '';
              $('#pMar').value = p.marca || '';
              $('#pCat').value = p.id_categoria ?? (cats.find(x => x.nombre === p.categoria) || cats[0] || {}).id_categoria ?? '';
              $('#pDes').value = p.descripcion || '';
              $('#pEsp').value = p.especificaciones || '';
              $('#pPre').value = p.precio ?? '';
              $('#pSto').value = p.stock ?? '';
              $('#pImg').value = p.imagen_url || '';
              $('#pDest').checked = !!+p.destacado;
              $('#pImg').dispatchEvent(new Event('input'));
            }
            $('#newProd').onclick = () => {
              $('#mProdTitle').textContent = 'Nuevo producto';
              fillProd();
              open('mProd');
            };
            async function editProd(id) {
              let p = prods.find(x => +x.id_producto === id) || {};
              const r = await api('obtener_producto', {
                id_producto: id
              }); // trae descripción y especificaciones completas
              if (r.success && r.data) p = {
                ...p,
                ...r.data
              };
              $('#mProdTitle').textContent = 'Editar producto';
              fillProd(p);
              open('mProd');
            }
            $('#fProdForm').onsubmit = async e => {
              e.preventDefault();
              const id = $('#pId').value;
              const data = {
                id_categoria: $('#pCat').value,
                nombre: $('#pNom').value.trim(),
                marca: $('#pMar').value.trim(),
                descripcion: $('#pDes').value,
                especificaciones: $('#pEsp').value,
                precio: $('#pPre').value,
                stock: $('#pSto').value,
                imagen_url: $('#pImg').value,
                destacado: $('#pDest').checked ? 1 : 0
              };
              if (id) data.id_producto = id;
              const r = await api(id ? 'actualizar_producto' : 'crear_producto', data, true);
              if (!r.success) return toast(r.error || 'No se pudo guardar', true);
              close('mProd');
              toast(id ? 'Producto actualizado' : 'Producto creado');
              loadProds();
            };

            /* ---------- ADMIN: Categorías (CRUD) ---------- */
            let catsDel = [];
            async function loadCatsView() {
              if (verElim.cat) {
                const r = await api('listar_eliminados', {
                  tipo: 'categoria'
                });
                catsDel = r.success ? r.data : [];
              }
              paintCats();
            }

            function paintCats() {
              const del = verElim.cat,
                list = del ? catsDel : cats;
              $('#catBody').innerHTML = list.length ? list.map(c => `
    <tr><td>${esc(c.id_categoria)}</td><td><strong>${esc(c.nombre)}</strong></td><td>${esc(c.descripcion)}</td>
    <td>${del ? `<button class="btn ok sm" onclick="restaurar('categoria',${+c.id_categoria})">Restaurar</button>` : `<div class="acts"><button class="btn ghost sm" onclick="editCat(${+c.id_categoria})">Editar</button>
    <button class="btn bad sm" onclick="askDelete('categoria',${+c.id_categoria},'${esc(c.nombre).replace(/'/g, '&#39;')}')">Eliminar</button></div>`}</td></tr>`).join('') :
                `<tr><td colspan="4" style="text-align:center;color:var(--mut);padding:2rem">${del ? 'No hay categorías eliminadas.' : 'Sin categorías. Crea una para poder registrar productos.'}</td></tr>`;
            }
            $('#newCat').onclick = () => {
              $('#mCatTitle').textContent = 'Nueva categoría';
              $('#cId').value = $('#cNom').value = $('#cDes').value = '';
              open('mCat');
            };

            function editCat(id) {
              const c = cats.find(x => +x.id_categoria === id);
              if (!c) return;
              $('#mCatTitle').textContent = 'Editar categoría';
              $('#cId').value = c.id_categoria;
              $('#cNom').value = c.nombre;
              $('#cDes').value = c.descripcion || '';
              open('mCat');
            }
            $('#fCatForm').onsubmit = async e => {
              e.preventDefault();
              const id = $('#cId').value;
              const data = {
                nombre: $('#cNom').value.trim(),
                descripcion: $('#cDes').value
              };
              if (id) data.id_categoria = id;
              const r = await api(id ? 'actualizar_categoria' : 'crear_categoria', data, true);
              if (!r.success) return toast(r.error || 'No se pudo guardar', true);
              close('mCat');
              toast(id ? 'Categoría actualizada' : 'Categoría creada');
              await loadCats();
              paintCats();
              paintProds();
            };

            /* ---------- ADMIN: Pedidos ---------- */
            const FLUJO = {
              PENDIENTE: ['PAGADO', 'CANCELADO'],
              PAGADO: ['ENVIADO', 'CANCELADO'],
              ENVIADO: ['ENTREGADO', 'CANCELADO']
            };
            let adminOrders = [],
              pedFiltro = '';
            async function loadAdminOrders() {
              const r = await api('admin_pedidos');
              adminOrders = r.success ? r.data : [];
              paintAdminOrders();
            }

            function paintAdminOrders() {
              const estados = ['PENDIENTE', 'PAGADO', 'ENVIADO', 'ENTREGADO', 'CANCELADO'];
              const n = e => adminOrders.filter(o => !e || o.estado === e).length;
              $('#pedChips').innerHTML = ['', ...estados].map(e => `<button class="chip ${e === pedFiltro ? 'on' : ''}" data-e="${e}">${e ? e[0] + e.slice(1).toLowerCase() : 'Todos'} (${n(e)})</button>`).join('');
              const q = $('#fPed').value.toLowerCase().trim();
              const list = adminOrders.filter(o => (!pedFiltro || o.estado === pedFiltro) && (!q || `${o.id_pedido} ${o.nombre} ${o.apellido} ${o.correo}`.toLowerCase().includes(q)));
              $('#pedBody').innerHTML = list.length ? list.map(o => {
                const sig = FLUJO[o.estado] || [];
                return `<tr>
      <td>#${esc(o.id_pedido)}</td>
      <td><strong>${esc(o.nombre + ' ' + o.apellido)}</strong><div class="sub">${esc(o.correo)}</div></td>
      <td>${esc(o.fecha_creacion)}</td><td>${esc(o.metodo_pago || '—')}</td><td>${money(o.total)}</td>
      <td><span class="pill ${ESTADO[o.estado] || ''}">${esc(o.estado)}</span></td>
      <td><div class="acts"><button class="btn ghost sm" onclick="adminOrderDetail(${+o.id_pedido})">Ver detalle</button>
        ${sig.length ? `<select class="stock" style="width:auto" aria-label="Cambiar estado del pedido ${+o.id_pedido}" onchange="changeOrderState(${+o.id_pedido},this)"><option value="">Cambiar a…</option>${sig.map(x => `<option value="${x}">${x}</option>`).join('')}</select>` : ''}
      </div></td></tr>`;
              }).join('') : '<tr><td colspan="7" style="text-align:center;color:var(--mut);padding:2rem">No hay pedidos con esos criterios.</td></tr>';
            }
            $('#pedChips').onclick = e => {
              const b = e.target.closest('.chip');
              if (b) {
                pedFiltro = b.dataset.e;
                paintAdminOrders();
              }
            };
            $('#fPed').oninput = paintAdminOrders;

            function changeOrderState(id, sel) {
              const e = sel.value;
              if (!e) return;
              if (e === 'CANCELADO') {
                sel.value = '';
                return askDelete('pedido_admin', id, '');
              } // cancelar pide confirmación
              setOrderState(id, e);
            }
            async function setOrderState(id, estado) {
              const r = await api('cambiar_estado_pedido', {
                id_pedido: id,
                estado
              }, true);
              r.success ? toast(`Pedido #${id}: ${estado}`) : toast(r.error, true);
              loadAdminOrders();
            }
            async function adminOrderDetail(id) {
              const o = adminOrders.find(x => +x.id_pedido === id) || {};
              const r = await api('detalle_pedido', {
                id_pedido: id
              });
              if (!r.success) return toast(r.error, true);
              $('#ordTitle').textContent = 'Pedido #' + id;
              const shippingAddress = [o.direccion_envio, o.barrio_envio, o.ciudad_envio, o.pais_envio].filter(Boolean).join(', ');
              $('#ordInfo').innerHTML = `<div><b>${esc(o.nombre)} ${esc(o.apellido)}</b> · ${esc(o.correo)}${o.telefono ? ' · ' + esc(o.telefono) : ''}</div>
    <div>Envío a: ${esc(shippingAddress || '—')}</div><div>Pago: ${esc(o.metodo_pago || '—')} · Total: <b>${money(o.total)}</b></div>`;
              $('#ordDet').innerHTML = r.data.map(d => `<tr><td>${esc(d.producto)}<div class="sub">${esc(d.marca)} · ${money(d.precio_unitario)} c/u</div></td><td>${esc(d.cantidad)}</td><td>${money(d.subtotal)}</td></tr>`).join('');
              open('mOrd');
            }

            /* ---------- ADMIN: Tienda (personalización) ---------- */
            function prevTienda() {
              const c1 = $('#tC1').value,
                c2 = $('#tC2').value;
              $('#tPrev').style.background = c1;
              $('#tPrevSw').style.background = c2;
              $('#tPrevName').textContent = $('#tNom').value || 'Tu tienda';
              $('#tWarn').hidden = lum(c1) < .6;
            }
            ['tNom', 'tC1', 'tC2'].forEach(id => $('#' + id).addEventListener('input', prevTienda));
            async function loadTiendaForm() {
              const r = await api('tienda_admin'),
                d = r.success && r.data;
              if (d) {
                $('#tNom').value = d.nombre_tienda || '';
                $('#tDes').value = d.descripcion_tienda || '';
                $('#tLogo').value = d.logo_url || '';
                $('#tBan').value = d.banner_url || '';
                if (isHex(d.color_primario)) $('#tC1').value = d.color_primario.toLowerCase();
                if (isHex(d.color_secundario)) $('#tC2').value = d.color_secundario.toLowerCase();
                $('#tMail').value = d.correo_contacto || '';
                $('#tTel').value = d.telefono_contacto || '';
                $('#tLogo').dispatchEvent(new Event('input'));
                $('#tBan').dispatchEvent(new Event('input'));
              }
              prevTienda();
            }
            $('#fTienda').onsubmit = async e => {
              e.preventDefault();
              const r = await api('guardar_tienda', {
                nombre_tienda: $('#tNom').value.trim(),
                descripcion_tienda: $('#tDes').value,
                logo_url: $('#tLogo').value.trim(),
                banner_url: $('#tBan').value.trim(),
                color_primario: $('#tC1').value,
                color_secundario: $('#tC2').value,
                correo_contacto: $('#tMail').value.trim(),
                telefono_contacto: $('#tTel').value.trim()
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo guardar', true);
              toast('Tienda actualizada');
              loadStore();
            };

            /* ---------- ADMIN: Usuarios ---------- */
            let adminUsers = [];
            async function loadUsers() {
              const r = verElim.usr ? await api('listar_eliminados', {
                tipo: 'usuario'
              }) : await api('listar_usuarios');
              adminUsers = r.success ? r.data : [];
              const del = verElim.usr;
              $('#usrBody').innerHTML = adminUsers.length ? adminUsers.map(u => {
                const me = user && +u.id_usuario === +user.id_usuario;
                const nom = `<strong>${esc(u.nombre + ' ' + (u.apellido || ''))}</strong>${me ? ' <span class="pill">Tú</span>' : ''}`;
                if (del) return `<tr><td>${nom}</td><td>${esc(u.correo)}</td><td>${esc(u.telefono || '—')}</td><td><span class="pill">${esc(u.rol)}</span></td>
      <td><button class="btn ok sm" onclick="restaurar('usuario',${+u.id_usuario})">Restaurar</button></td></tr>`;
                return `<tr><td>${nom}</td><td>${esc(u.correo)}</td><td>${esc(u.telefono || '—')}</td>
    <td><select class="stock" style="width:auto" ${me ? 'disabled' : ''} onchange="setRole(${+u.id_usuario},this.value)">
      ${['CLIENTE', 'ADMINISTRADOR'].map(x => `<option ${u.rol === x ? 'selected' : ''}>${x}</option>`).join('')}</select></td>
    <td><div class="acts"><button class="btn ghost sm" onclick="editUser(${+u.id_usuario})">Editar</button>${me ? '' : `<button class="btn bad sm" onclick="askDelete('usuario',${+u.id_usuario},'${esc(u.nombre).replace(/'/g, '&#39;')}')">Eliminar</button>`}</div></td></tr>`;
              }).join('') : `<tr><td colspan="5" style="text-align:center;color:var(--mut);padding:2rem">${del ? 'No hay usuarios eliminados.' : 'No hay usuarios registrados.'}</td></tr>`;
            }

            function editUser(id) {
              const u = adminUsers.find(x => +x.id_usuario === id);
              if (!u) return;
              $('#uId').value = u.id_usuario;
              $('#uNom').value = u.nombre || '';
              $('#uApe').value = u.apellido || '';
              $('#uMail').value = u.correo || '';
              $('#uTel').value = u.telefono || '';
              $('#uPais').value = u.pais || '';
              $('#uCiudad').value = u.ciudad || '';
              $('#uBarrio').value = u.barrio || '';
              $('#uDir').value = u.direccion || '';
              $('#uPass').value = '';
              open('mUsr');
            }
            $('#fUsr').onsubmit = async e => {
              e.preventDefault();
              const id = $('#uId').value;
              const r = await api('admin_actualizar_usuario', {
                id_usuario: id,
                nombre: $('#uNom').value.trim(),
                apellido: $('#uApe').value.trim(),
                correo: $('#uMail').value.trim(),
                telefono: $('#uTel').value.trim(),
                pais: $('#uPais').value.trim(),
                ciudad: $('#uCiudad').value.trim(),
                barrio: $('#uBarrio').value.trim(),
                direccion: $('#uDir').value.trim(),
                password: $('#uPass').value
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo guardar', true);
              if (user && +id === +user.id_usuario) {
                user.nombre = $('#uNom').value.trim();
                user.apellido = $('#uApe').value.trim();
                localStorage.setItem('currentUser', JSON.stringify(user));
              }
              close('mUsr');
              toast('Usuario actualizado');
              loadUsers();
            };
            async function setRole(id, rol) {
              const r = await api('cambiar_rol', {
                id_usuario: id,
                rol
              }, true);
              r.success ? toast('Rol actualizado') : toast(r.error, true);
              loadUsers();
            }

            /* ---------- ADMIN: alternar activos / eliminados + restaurar ---------- */
            $('#togProd').onclick = async function() {
              verElim.prod = !verElim.prod;
              this.textContent = verElim.prod ? 'Ver activos' : 'Ver eliminados';
              $('#newProd').hidden = verElim.prod;
              await loadProds();
            };
            $('#togCat').onclick = async function() {
              verElim.cat = !verElim.cat;
              this.textContent = verElim.cat ? 'Ver activas' : 'Ver eliminados';
              $('#newCat').hidden = verElim.cat;
              await loadCatsView();
            };
            $('#togUsr').onclick = async function() {
              verElim.usr = !verElim.usr;
              this.textContent = verElim.usr ? 'Ver activos' : 'Ver eliminados';
              await loadUsers();
            };
            async function restaurar(tipo, id) {
              const r = await api('restaurar', {
                tipo,
                id
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo restaurar', true);
              toast(tipo[0].toUpperCase() + tipo.slice(1) + (tipo === 'categoria' ? ' restaurada' : ' restaurado'));
              if (tipo === 'producto') {
                loadProds();
                loadList('listar_catalogo');
              } else if (tipo === 'categoria') {
                await loadCats();
                await loadCatsView();
              } else loadUsers();
            }

            /* ---------- Mi perfil ---------- */
            async function loadProfile() {
              const r = await api('perfil_obtener');
              if (!(r.success && r.data)) return toast(r.error || 'No se pudo cargar tu perfil', true);
              const d = r.data;
              $('#mpMail').value = d.correo || '';
              $('#mpNom').value = d.nombre || '';
              $('#mpApe').value = d.apellido || '';
              $('#mpTel').value = d.telefono || '';
              $('#mpPais').value = d.pais || '';
              $('#mpCiudad').value = d.ciudad || '';
              $('#mpBarrio').value = d.barrio || '';
              $('#mpDir').value = d.direccion || '';
              $('#mpAct').value = $('#mpNew').value = $('#mpNew2').value = '';
            }
            $('#fPerfil').onsubmit = async e => {
              e.preventDefault();
              const r = await api('perfil_actualizar', {
                nombre: $('#mpNom').value.trim(),
                apellido: $('#mpApe').value.trim(),
                telefono: $('#mpTel').value.trim(),
                pais: $('#mpPais').value.trim(),
                ciudad: $('#mpCiudad').value.trim(),
                barrio: $('#mpBarrio').value.trim(),
                direccion: $('#mpDir').value.trim()
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo guardar', true);
              user = r.user;
              localStorage.setItem('currentUser', JSON.stringify(user));
              cartAddress = {
                pais: $('#mpPais').value.trim(),
                ciudad: $('#mpCiudad').value.trim(),
                barrio: $('#mpBarrio').value.trim(),
                direccion: $('#mpDir').value.trim()
              };
              toast('Perfil actualizado');
            };
            $('#fPass').onsubmit = async e => {
              e.preventDefault();
              if ($('#mpNew').value !== $('#mpNew2').value) return toast('Las contraseñas nuevas no coinciden', true);
              const r = await api('cambiar_password', {
                password_actual: $('#mpAct').value,
                password_nueva: $('#mpNew').value
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo cambiar la contraseña', true);
              $('#mpAct').value = $('#mpNew').value = $('#mpNew2').value = '';
              toast('Contraseña actualizada');
            };

            /* ---------- Detalle de producto (público) ---------- */
            async function showProduct(id) {
              const r = await api('detalle_producto', {
                id_producto: id
              });
              if (!(r.success && r.data)) return toast(r.error || 'Este producto ya no está disponible', true);
              const p = r.data;
              const raw = String(p.especificaciones || '');
              const specs = raw.split(raw.includes('\n') ? /\n/ : /,\s*/).map(x => x.trim()).filter(Boolean);
              $('#pdBody').innerHTML = `<div class="pd">
    <img src="${esc(imageSrc(p))}" alt="${esc(p.nombre)}" ${dat(p)}>
    <div>
      <div class="brand">${esc(p.marca)}</div><h3>${esc(p.nombre)}</h3>
      <div class="price">${money(p.precio)}</div>
      <div class="mut">${esc(p.categoria)} · ${+p.stock > 0 ? p.stock + ' disponibles' : 'Agotado'}</div>
      ${p.descripcion ? `<p class="pdesc">${esc(p.descripcion)}</p>` : ''}
      ${specs.length ? `<h4>Especificaciones</h4><ul>${specs.map(x => `<li>${esc(x)}</li>`).join('')}</ul>` : ''}
      <button class="btn ok full" ${+p.stock > 0 ? '' : 'disabled'} onclick="close('mProdDet');addCart(${+p.id_producto})">Agregar al carrito</button>
    </div></div>`;
              open('mProdDet');
            }

            /* ---------- Subida de imágenes ---------- */
            async function subirImagen(file, inputId) {
              if (file.size > 2 * 1024 * 1024) return toast('La imagen supera el máximo de 2 MB', true);
              const r = await api('subir_imagen', {
                archivo: file
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo subir la imagen', true);
              const t = $('#' + inputId);
              t.value = r.ruta;
              t.dispatchEvent(new Event('input', {
                bubbles: true
              }));
              toast('Imagen subida');
            }
            $$('input[type=file][data-target]').forEach(f => {
              const t = $('#' + f.dataset.target),
                im = f.parentElement.querySelector('img');
              const prev = () => {
                if (t.value) {
                  im.style.visibility = 'visible';
                  im.src = t.value;
                } else {
                  im.removeAttribute('src');
                  im.style.visibility = 'hidden';
                }
              };
              im.onerror = () => {
                im.style.visibility = 'hidden';
              };
              t.addEventListener('input', prev);
              prev();
              f.onchange = async () => {
                if (f.files[0]) await subirImagen(f.files[0], f.dataset.target);
                f.value = '';
              };
            });

            /* ---------- Eliminar (compartido) ---------- */
            let pendingDel = null;

            function askDelete(tipo, id, nombre) {
              pendingDel = {
                tipo,
                id
              };
              $('#delMsg').textContent = tipo === 'carrito' ? '¿Vaciar todo el carrito?' :
                tipo === 'pedido' || tipo === 'pedido_admin' ? `¿Cancelar el pedido #${id}? Los productos volverán al inventario.` :
                `¿Eliminar ${tipo} “${nombre}”? Podrás restaurarlo después desde “Ver eliminados”.`;
              $('#delOk').textContent = tipo === 'carrito' ? 'Vaciar' : tipo === 'pedido' || tipo === 'pedido_admin' ? 'Cancelar pedido' : 'Eliminar';
              open('mDel');
            }
            $('#delOk').onclick = async () => {
              const {
                tipo,
                id
              } = pendingDel;
              const custom = {
                carrito: ['vaciar_carrito', {}],
                pedido: ['cancelar_pedido', {
                  id_pedido: id
                }],
                pedido_admin: ['cambiar_estado_pedido', {
                  id_pedido: id,
                  estado: 'CANCELADO'
                }]
              } [tipo];
              const r = custom ? await api(custom[0], custom[1], true) : await api('eliminar_' + tipo, {
                ['id_' + tipo]: id
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo completar la acción', true);
              close('mDel');
              if (tipo === 'pedido_admin') {
                toast(`Pedido #${id} cancelado; stock devuelto`);
                loadAdminOrders();
                loadProds();
                return loadList('listar_catalogo');
              }
              if (tipo === 'carrito') {
                toast('Carrito vaciado');
                return loadCart();
              }
              if (tipo === 'pedido') {
                toast('Pedido cancelado');
                loadOrders();
                return loadList('listar_catalogo');
              }
              toast(tipo[0].toUpperCase() + tipo.slice(1) + ' eliminado' + (tipo === 'categoria' ? 'a' : '') + '. Puedes restaurarlo en “Ver eliminados”.');
              if (tipo === 'producto') loadProds();
              else if (tipo === 'usuario') loadUsers();
              else {
                await loadCats();
                paintCats();
              }
            };

            /* ---------- ADMIN: Reportes en Excel ---------- */
            $$('[data-rep]').forEach(b => b.onclick = async () => {
              const txt = b.textContent;
              b.disabled = true;
              b.textContent = 'Generando…';
              try {
                const res = await fetch('backend/endpoints/reporte.php?tipo=' + encodeURIComponent(b.dataset.rep));
                if ((res.headers.get('Content-Type') || '').includes('application/json')) { // el servidor respondió un error
                  const r = await res.json();
                  return toast(r.error || 'No se pudo generar el reporte', true);
                }
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const nombre = ((res.headers.get('Content-Disposition') || '').match(/filename="([^"]+)"/) || [])[1] || 'reporte.xlsx';
                const url = URL.createObjectURL(await res.blob());
                const a = document.createElement('a');
                a.href = url;
                a.download = nombre;
                document.body.append(a);
                a.click();
                a.remove();
                setTimeout(() => URL.revokeObjectURL(url), 1000);
                toast('Reporte descargado');
              } catch (e) {
                toast('No se pudo descargar el reporte', true);
              } finally {
                b.disabled = false;
                b.textContent = txt;
              }
            });

            /* ---------- TechnoStore: carrito lateral, búsqueda del header y modales ---------- */
            const openDrawer = () => {
              $('#drawer').classList.add('on');
              $('#drawer').setAttribute('aria-hidden', 'false');
              $('#scrim').hidden = false;
            };
            const closeDrawer = () => {
              $('#drawer').classList.remove('on');
              $('#drawer').setAttribute('aria-hidden', 'true');
              $('#scrim').hidden = true;
            };
            $('#navCart').onclick = openDrawer;
            $('#dClose').onclick = $('#scrim').onclick = closeDrawer;
            document.addEventListener('keydown', e => {
              if (e.key === 'Escape') closeDrawer();
            });

            function paintDrawer() {
              const body = $('#dBody'),
                foot = $('#dFoot');
              if (!user) {
                body.innerHTML = '<div class="empty">Inicia sesión para guardar productos en tu carrito.</div>';
                foot.innerHTML = '<button class="btn full" id="dLogin">Iniciar sesión</button>';
                $('#dLogin').onclick = () => {
                  closeDrawer();
                  openAuth();
                };
                return;
              }
              if (!cart.length) {
                body.innerHTML = '<div class="empty">Tu carrito está vacío.</div>';
                foot.innerHTML = '';
                return;
              }
              const total = cart.reduce((s, i) => s + +i.subtotal, 0),
                units = cart.reduce((s, i) => s + +i.cantidad, 0);
              body.innerHTML = cart.map(i => `<div class="di">
    <img src="${esc(imageSrc(i))}" alt="" ${dat(i)}>
    <div><strong>${esc(i.nombre)}</strong><span>${money(i.precio)} c/u</span>
      <div class="qty" style="width:max-content"><button aria-label="Quitar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad - 1})">−</button><span>${+i.cantidad}</span><button aria-label="Agregar una unidad" onclick="setQty(${+i.id_producto},${+i.cantidad + 1})" ${+i.cantidad >= +i.stock ? 'disabled' : ''}>+</button></div></div>
    <b>${money(i.subtotal)}</b></div>`).join('');
              foot.innerHTML = `<div class="line tot"><span>Total (${units})</span><span>${money(total)}</span></div><button class="btn ok full" id="dGo">Ir a pagar</button>`;
              $('#dGo').onclick = () => {
                closeDrawer();
                show('cart');
              };
            }

            function notice(title, html, btns = [
              ['Entendido', 'ok', () => close('mInfo')]
            ]) {
              $('#infTitle').textContent = title;
              $('#infBody').innerHTML = html;
              const box = $('#infBtns');
              box.innerHTML = '';
              btns.forEach(([t, c, f]) => {
                const b = document.createElement('button');
                b.className = 'btn ' + c;
                b.textContent = t;
                b.onclick = f;
                box.append(b);
              });
              open('mInfo');
            }

            function confirmAdd(id) {
              const it = cart.find(i => +i.id_producto === id);
              if (!it) return;
              const total = cart.reduce((s, i) => s + +i.subtotal, 0);
              notice('Añadido al carrito', `<div class="addok"><img src="${esc(imageSrc(it))}" alt="" ${dat(it)}><div><strong>${esc(it.nombre)}</strong><span>${+it.cantidad} en tu carrito · Total ${money(total)}</span></div></div>`,
                [
                  ['Seguir comprando', 'ghost', () => close('mInfo')],
                  ['Ver carrito', 'ok', () => {
                    close('mInfo');
                    openDrawer();
                  }]
                ]);
            }

            $('#hSearchForm').onsubmit = e => {
              e.preventDefault();
              const q = $('#hSearch').value.trim();
              show('catalog');
              $('#searchInput').value = q;
              loadList(q ? 'buscar_productos' : 'listar_catalogo', q ? {
                q
              } : {});
              $('#v-catalog').scrollIntoView({
                behavior: 'smooth'
              });
            };

            /* ---------- Animaciones ---------- */
            const reduceMotion = () => matchMedia('(prefers-reduced-motion: reduce)').matches;
            let lastAddBtn = null,
              flyDone = false,
              lastUnits = 0;
            document.addEventListener('click', e => {
              lastAddBtn = e.target.closest('button');
            }, true);

            function flyToCart() {
              flyDone = false;
              const img = lastAddBtn && lastAddBtn.closest('.card') && lastAddBtn.closest('.card').querySelector('.ph img');
              if (!img || reduceMotion()) return;
              const a = img.getBoundingClientRect(),
                t = $('#navCart').getBoundingClientRect();
              const c = img.cloneNode();
              Object.assign(c.style, {
                position: 'fixed',
                left: a.left + 'px',
                top: a.top + 'px',
                width: a.width + 'px',
                height: a.height + 'px',
                objectFit: 'cover',
                borderRadius: '14px',
                zIndex: 300,
                pointerEvents: 'none',
                margin: 0
              });
              document.body.append(c);
              flyDone = true;
              const dx = t.left + t.width / 2 - (a.left + a.width / 2),
                dy = t.top + t.height / 2 - (a.top + a.height / 2);
              c.animate([{
                transform: 'none',
                opacity: 1
              }, {
                transform: `translate(${dx * .6}px,${dy * .5 - 40}px) scale(.45)`,
                opacity: .95,
                offset: .55
              }, {
                transform: `translate(${dx}px,${dy}px) scale(.05)`,
                opacity: .2
              }], {
                duration: 600,
                easing: 'cubic-bezier(.45,0,.75,.4)'
              }).onfinish = () => c.remove();
            }
            const _paintDrawer = paintDrawer;
            paintDrawer = function() { // pulso del contador al sumar unidades
              _paintDrawer();
              const n = cart.reduce((s, i) => s + +i.cantidad, 0);
              if (n > lastUnits)['#cartBadge', '#navCart'].forEach(s => {
                const el = $(s);
                el.classList.remove('bump');
                void el.offsetWidth;
                el.classList.add('bump');
              });
              lastUnits = n;
            };

            /* ---------- Términos y condiciones ---------- */
            document.addEventListener('click', e => {
              if (e.target.closest('[data-terms]')) {
                e.preventDefault();
                openTerms();
              }
            });

            function openTerms() {
              const contact = $('#footContact').textContent.trim() || 'el correo de contacto de la tienda';
              $$('.tcContact').forEach(el => el.textContent = contact);
              $('#mTerms .mc').scrollTop = 0;
              $('#tcBar').style.width = '0';
              open('mTerms');
            }
            $('#mTerms .mc').addEventListener('scroll', e => {
              const m = e.currentTarget;
              $('#tcBar').style.width = (m.scrollTop / Math.max(1, m.scrollHeight - m.clientHeight) * 100) + '%';
            });
            $('#tcAll').onclick = function() {
              const all = $$('#mTerms details'),
                openAll = all.some(d => !d.open);
              all.forEach(d => d.open = openAll);
              this.textContent = openAll ? 'Contraer todo' : 'Expandir todo';
            };
            $('#tcAccept').onclick = () => {
              termsOk = true;
              $$('#rTerms,#cTerms').forEach(c => c.checked = true);
              close('mTerms');
              toast('Términos y condiciones aceptados');
            };

            /* ---------- Animaciones al hacer scroll ---------- */
            document.addEventListener('scroll', () => $('header').classList.toggle('stuck', scrollY > 8), {
              passive: true
            });
            if (!reduceMotion() && 'IntersectionObserver' in window) {
              const io = new IntersectionObserver(es => es.forEach(en => {
                if (!en.isIntersecting) return;
                en.target.classList.add('in');
                io.unobserve(en.target);
                en.target.addEventListener('transitionend', () => en.target.style.transitionDelay = '', {
                  once: true
                });
              }), {
                threshold: .15
              });
              $$('.perks-in>div, .sh, .chips, .foot-in').forEach((el, i) => {
                el.classList.add('reveal');
                el.style.transitionDelay = (i % 4) * 80 + 'ms';
                io.observe(el);
              });
            }

            (async () => {
                Loader.start('Cargando tienda…', 0);
                try {
                  await syncSession();
                  loadStore();
                  await loadCats();
                  await loadList('listar_catalogo');
                } finally {
                  Loader.stop();
                }
                api('obtener_destacados').then(r => heroArt(r.success ? r.data : []));
              })();
