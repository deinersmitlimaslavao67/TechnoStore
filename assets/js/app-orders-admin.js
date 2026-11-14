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
              if (!r.success) toast(r.error, true);
              else if (r.correo_enviado) toast(`Pedido #${id}: ${estado}`);
              else toast(`Pedido #${id}: ${estado}. No se pudo enviar el correo; revisa SMTP y los permisos del destinatario.`, true);
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
                const isClient = u.rol === 'CLIENTE';
                return `<tr><td>${nom}</td><td>${esc(u.correo)}</td><td>${esc(u.telefono || '—')}</td>
    <td><select class="stock" style="width:auto" ${me || !isClient ? 'disabled' : ''} onchange="setRole(${+u.id_usuario},this.value)">
      ${['CLIENTE', 'ADMINISTRADOR'].map(x => `<option ${u.rol === x ? 'selected' : ''}>${x}</option>`).join('')}</select></td>
    <td><div class="acts">${isClient && !me ? `<button class="btn ghost sm" onclick="editUser(${+u.id_usuario})">Editar datos no sensibles</button><button class="btn bad sm" onclick="askDelete('usuario',${+u.id_usuario},'${esc(u.nombre).replace(/'/g, '&#39;')}')">Eliminar</button>` : '<span class="sub">Cuenta protegida</span>'}</div></td></tr>`;
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
              open('mUsr');
            }
            $('#fUsr').onsubmit = async e => {
              e.preventDefault();
              const id = $('#uId').value;
              const r = await api('admin_actualizar_usuario', {
                id_usuario: id,
                nombre: $('#uNom').value.trim(),
                apellido: $('#uApe').value.trim(),
                telefono: $('#uTel').value.trim(),
                pais: $('#uPais').value.trim(),
                ciudad: $('#uCiudad').value.trim(),
                barrio: $('#uBarrio').value.trim(),
                direccion: $('#uDir').value.trim(),
              }, true);
              if (!r.success) return toast(r.error || 'No se pudo guardar', true);
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
