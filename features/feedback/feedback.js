(() => {
  const endpoint = 'features/feedback/endpoint.php';
  const navLogin = document.getElementById('navLogin');
  const navLogout = document.getElementById('navLogout');
  const navAdmin = document.getElementById('navAdmin');
  const adminView = document.getElementById('v-admin');
  if (!navLogin || !navLogout || !navAdmin || !adminView) return;

  const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  } [char]));
  const isLoggedIn = () => !navLogout.hidden;
  const isAdmin = () => !navAdmin.hidden;
  const button = document.createElement('button');
  button.type = 'button';
  button.className = 'btn feedback-launch';
  button.textContent = 'Reportar error / sugerencia';
  button.hidden = true;
  button.setAttribute('aria-haspopup', 'dialog');

  const modal = document.createElement('div');
  modal.className = 'modal feedback-modal';
  modal.id = 'feedbackModal';
  modal.setAttribute('role', 'dialog');
  modal.setAttribute('aria-modal', 'true');
  modal.setAttribute('aria-labelledby', 'feedbackTitle');
  modal.innerHTML = `
    <div class="mc">
      <h3 id="feedbackTitle">Reportar un error o enviar una sugerencia</h3>
      <p class="feedback-notice" id="feedbackNotice" role="status" aria-live="polite"></p>
      <form id="feedbackForm">
        <div class="fg">
          <label for="feedbackType">Tipo de reporte</label>
          <select id="feedbackType" required>
            <option value="SUGERENCIA">Sugerencia</option>
            <option value="ERROR">Error</option>
          </select>
        </div>
        <div class="fg">
          <label for="feedbackDetail">Describe el reporte</label>
          <textarea id="feedbackDetail" maxlength="2000" required></textarea>
        </div>
        <div class="mf">
          <button type="button" class="btn ghost" id="feedbackClose">Cerrar</button>
          <button type="submit" class="btn ok" id="feedbackSubmit">Enviar</button>
        </div>
      </form>
    </div>`;
  document.body.append(button, modal);

  const notice = document.getElementById('feedbackNotice');
  const form = document.getElementById('feedbackForm');
  const submitButton = document.getElementById('feedbackSubmit');
  const setNotice = (message, isError = false) => {
    notice.textContent = message;
    notice.classList.toggle('error', isError);
  };
  const closeModal = () => modal.classList.remove('on');
  const openModal = () => {
    setNotice('');
    form.reset();
    modal.classList.add('on');
    document.getElementById('feedbackDetail').focus();
  };

  button.addEventListener('click', openModal);
  document.getElementById('feedbackClose').addEventListener('click', closeModal);
  modal.addEventListener('mousedown', event => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && modal.classList.contains('on')) closeModal();
  });

  async function request(action, data = null) {
    const options = data === null ? {
      credentials: 'same-origin'
    } : {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    };
    const response = await fetch(`${endpoint}?action=${encodeURIComponent(action)}`, options);
    const result = await response.json();
    if (!response.ok || !result.success) throw new Error(result.error || 'No se pudo completar la solicitud.');
    return result;
  }

  form.addEventListener('submit', async event => {
    event.preventDefault();
    submitButton.disabled = true;
    setNotice('Enviando…');
    try {
      await request('submit', {
        tipo: document.getElementById('feedbackType').value,
        origen: 'USUARIO',
        detalle: document.getElementById('feedbackDetail').value,
        pagina: location.pathname
      });
      setNotice('Gracias. Tu reporte fue enviado al equipo.');
      form.reset();
      setTimeout(closeModal, 1600);
    } catch (error) {
      setNotice(error.message, true);
    } finally {
      submitButton.disabled = false;
    }
  });

  let autoLogs = 0;
  const seenErrors = new Set();
  function logJavaScriptError(message, stack, filename = '') {
    if (!isLoggedIn() || autoLogs >= 5 || !message) return;
    if (filename) {
      try {
        if (new URL(filename, location.href).origin !== location.origin) return;
      } catch {
        return;
      }
    }
    const detail = String(message).slice(0, 2000);
    const trace = String(stack || '').slice(0, 6000);
    const signature = `${detail}\n${trace}`;
    if (seenErrors.has(signature)) return;
    seenErrors.add(signature);
    autoLogs++;
    request('submit', {
      tipo: 'ERROR',
      origen: 'JAVASCRIPT',
      detalle: detail,
      stack_trace: trace,
      pagina: location.pathname
    }).catch(error => console.warn('[TechnoStore] No se pudo registrar un error JavaScript:', error.message));
  }

  window.addEventListener('error', event => {
    if (event instanceof ErrorEvent) {
      logJavaScriptError(event.message, event.error && event.error.stack, event.filename);
    }
  });
  window.addEventListener('unhandledrejection', event => {
    const reason = event.reason;
    logJavaScriptError(
      reason instanceof Error ? reason.message : String(reason),
      reason instanceof Error ? reason.stack : ''
    );
  });

  function updateVisibility() {
    button.hidden = !isLoggedIn();
    if (!isAdmin()) {
      adminTab.hidden = true;
      if (adminPanel.classList.contains('on')) selectAdminTab('prod');
    } else {
      adminTab.hidden = false;
    }
  }

  const adminTabs = adminView.querySelector('.tabs');
  const adminTab = document.createElement('button');
  adminTab.type = 'button';
  adminTab.dataset.t = 'feedback';
  adminTab.textContent = 'Reportes de usuarios';
  adminTab.hidden = true;
  adminTabs.append(adminTab);

  const adminPanel = document.createElement('div');
  adminPanel.className = 'panel';
  adminPanel.id = 't-feedback';
  adminPanel.innerHTML = `
    <div class="tools">
      <p class="sub">Últimos 200 reportes de errores y sugerencias.</p>
      <button type="button" class="btn ghost" id="feedbackReload">Actualizar</button>
    </div>
    <p class="feedback-notice" id="feedbackAdminNotice" role="status" aria-live="polite"></p>
    <div class="feedback-list" id="feedbackList" aria-live="polite"></div>`;
  adminView.append(adminPanel);

  function selectAdminTab(name) {
    adminView.querySelectorAll('.tabs [data-t]').forEach(tab => tab.classList.toggle('on', tab === adminView.querySelector(`[data-t="${name}"]`)));
    adminView.querySelectorAll('.panel').forEach(panel => panel.classList.toggle('on', panel.id === `t-${name}`));
  }

  function renderReports(reports) {
    const list = document.getElementById('feedbackList');
    if (!reports.length) {
      list.innerHTML = '<div class="empty">Todavía no hay reportes.</div>';
      return;
    }
    list.innerHTML = reports.map(report => `
      <article class="feedback-card">
        <div class="feedback-card-head">
          <strong>${escapeHtml(report.tipo === 'ERROR' ? 'Error' : 'Sugerencia')} · ${escapeHtml(report.origen)}</strong>
          <select class="feedback-status" data-report-id="${Number(report.id_reporte)}" aria-label="Estado del reporte ${Number(report.id_reporte)}">
            <option value="NUEVO" ${report.estado === 'NUEVO' ? 'selected' : ''}>Nuevo</option>
            <option value="EN_REVISION" ${report.estado === 'EN_REVISION' ? 'selected' : ''}>En revisión</option>
            <option value="RESUELTO" ${report.estado === 'RESUELTO' ? 'selected' : ''}>Resuelto</option>
          </select>
        </div>
        <p>${escapeHtml(report.detalle)}</p>
        ${report.stack_trace ? `<details><summary>Traza JavaScript</summary><pre>${escapeHtml(report.stack_trace)}</pre></details>` : ''}
        <div class="feedback-card-meta">
          <span>${escapeHtml(report.nombre)} ${escapeHtml(report.apellido)} · ${escapeHtml(report.correo)}</span>
          <span>${escapeHtml(report.fecha_creacion)}</span>
        </div>
        <div class="feedback-page">${escapeHtml(report.pagina)}</div>
      </article>`).join('');
  }

  async function loadReports() {
    const list = document.getElementById('feedbackList');
    list.innerHTML = '<div class="empty">Cargando reportes…</div>';
    try {
      const result = await request('list');
      renderReports(result.data);
    } catch (error) {
      list.innerHTML = `<div class="empty">${escapeHtml(error.message)}</div>`;
    }
  }

  adminTab.addEventListener('click', () => {
    selectAdminTab('feedback');
    loadReports();
  });
  document.getElementById('feedbackReload').addEventListener('click', loadReports);
  adminPanel.addEventListener('change', async event => {
    const select = event.target.closest('[data-report-id]');
    if (!select) return;
    select.disabled = true;
    try {
      await request('status', {
        id_reporte: Number(select.dataset.reportId),
        estado: select.value
      });
      const adminNotice = document.getElementById('feedbackAdminNotice');
      adminNotice.textContent = 'Estado actualizado.';
      adminNotice.classList.remove('error');
    } catch (error) {
      const adminNotice = document.getElementById('feedbackAdminNotice');
      adminNotice.textContent = error.message;
      adminNotice.classList.add('error');
      loadReports();
    } finally {
      select.disabled = false;
    }
  });

  const visibilityObserver = new MutationObserver(updateVisibility);
  visibilityObserver.observe(navLogout, {
    attributes: true,
    attributeFilter: ['hidden']
  });
  visibilityObserver.observe(navAdmin, {
    attributes: true,
    attributeFilter: ['hidden']
  });
  updateVisibility();
})();
