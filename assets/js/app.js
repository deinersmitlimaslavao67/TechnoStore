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
