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

