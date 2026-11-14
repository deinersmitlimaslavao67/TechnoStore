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
                toast(r.correo_enviado ? `Pedido #${id} cancelado; stock devuelto` : `Pedido #${id} cancelado; no se pudo enviar el correo. Revisa SMTP y los permisos del destinatario.`, !r.correo_enviado);
                loadAdminOrders();
                loadProds();
                return loadList('listar_catalogo');
              }
              if (tipo === 'carrito') {
                toast('Carrito vaciado');
                return loadCart();
              }
              if (tipo === 'pedido') {
                toast(r.correo_enviado ? 'Pedido cancelado' : 'Pedido cancelado; no se pudo enviar el correo. Revisa SMTP y los permisos del destinatario.', !r.correo_enviado);
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
