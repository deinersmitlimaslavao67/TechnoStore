(() => {
  const footer = document.querySelector('.foot-in');
  if (!footer) return;

  const categories = [
    { id: 'video', title: 'Video del proyecto' },
    { id: 'srs', title: 'SRS y requisitos' },
    { id: 'mer', title: 'Diagrama MER' },
    { id: 'pac', title: 'PAC' },
    { id: 'quality', title: 'Herramientas de calidad' },
    { id: 'user_manual', title: 'Manual de usuario' },
    { id: 'technical_manual', title: 'Manual técnico' }
  ];
  const documents = new Map(categories.filter(category => category.id !== 'video').map(category => [category.id, []]));
  const openButton = document.createElement('button');
  openButton.type = 'button';
  openButton.className = 'linkbtn resources-link';
  openButton.textContent = 'Ver anexos y video';
  openButton.setAttribute('aria-haspopup', 'dialog');

  const modal = document.createElement('div');
  modal.className = 'modal resources-modal';
  modal.setAttribute('role', 'dialog');
  modal.setAttribute('aria-modal', 'true');
  modal.setAttribute('aria-labelledby', 'resourcesTitle');
  modal.innerHTML = `
    <div class="mc">
      <div class="resources-head">
        <h3 id="resourcesTitle">Anexos y video del proyecto</h3>
        <button type="button" class="btn ghost sm" data-resources-close aria-label="Cerrar">Cerrar</button>
      </div>
      <div class="resources-tabs" role="tablist" aria-label="Recursos del proyecto">
        ${categories.map((category, index) => `<button type="button" class="btn ${index ? 'ghost ' : ''}sm" role="tab" data-resource="${category.id}" aria-selected="${index === 0}">${category.title}</button>`).join('')}
      </div>
      <div class="resources-content">
        ${categories.map((category, index) => category.id === 'video' ? `
          <section class="resources-pane ${index === 0 ? 'on' : ''}" role="tabpanel" data-pane="${category.id}" ${index ? 'hidden' : ''}>
            <div class="resources-actions"><strong>${category.title}</strong><a href="videoo/video.mp4" target="_blank" rel="noopener">Abrir video en otra pestaña</a></div>
            <video class="resources-video" controls preload="none" playsinline>
              <source src="videoo/video.mp4" type="video/mp4">
              Tu navegador no puede reproducir este video.
            </video>
          </section>` : `
          <section class="resources-pane" role="tabpanel" data-pane="${category.id}" hidden>
            <p class="resources-status" data-resource-status>${category.id === 'srs' ? 'Buscando documentos SRS…' : 'Buscando documentos…'}</p>
            <div class="resources-grid" data-resource-list="${category.id}"></div>
            <div class="resources-preview" data-resource-preview="${category.id}" aria-live="polite"></div>
          </section>`).join('')}
      </div>
    </div>`;

  footer.append(openButton);
  document.body.append(modal);

  const closeButton = modal.querySelector('[data-resources-close]');
  const video = modal.querySelector('video');

  function close() {
    modal.classList.remove('on');
    video.pause();
    openButton.focus();
  }

  function showResource(key) {
    if (!categories.some(category => category.id === key)) return;
    modal.querySelectorAll('[data-resource]').forEach(tab => {
      const selected = tab.dataset.resource === key;
      tab.setAttribute('aria-selected', String(selected));
      tab.classList.toggle('ghost', !selected);
      tab.classList.toggle('on', selected);
    });
    modal.querySelectorAll('[data-pane]').forEach(pane => {
      const selected = pane.dataset.pane === key;
      pane.classList.toggle('on', selected);
      pane.hidden = !selected;
    });
    video.pause();
    if (key === 'video' && video.readyState === 0) video.load();
  }

  async function loadCatalog() {
    categories.filter(category => category.id !== 'video').forEach(category => {
      const pane = modal.querySelector(`[data-pane="${category.id}"]`);
      pane.querySelector('[data-resource-status]').textContent = 'Buscando documentos…';
      pane.querySelector(`[data-resource-list="${category.id}"]`).replaceChildren();
      pane.querySelector(`[data-resource-preview="${category.id}"]`).replaceChildren();
    });

    try {
      const response = await fetch('features/project-resources/catalog.php', {
        credentials: 'same-origin',
        cache: 'no-store'
      });
      if (!response.ok) throw new Error(`No se pudieron consultar los anexos (HTTP ${response.status}).`);
      const catalog = await response.json();
      if (!catalog || !catalog.categories) throw new Error('El catálogo de anexos no tiene un formato válido.');

      categories.filter(category => category.id !== 'video').forEach(category => {
        const files = catalog.categories[category.id];
        if (!Array.isArray(files)) throw new Error(`No se pudo consultar la categoría «${category.title}».`);
        documents.set(category.id, files);
        renderCategory(category);
      });
    } catch (error) {
      categories.filter(category => category.id !== 'video').forEach(category => {
        const pane = modal.querySelector(`[data-pane="${category.id}"]`);
        pane.querySelector('[data-resource-status]').textContent = error.message || 'No se pudieron consultar los documentos.';
        const retry = document.createElement('button');
        retry.type = 'button';
        retry.className = 'btn sm';
        retry.textContent = 'Reintentar';
        retry.addEventListener('click', loadCatalog);
        pane.querySelector(`[data-resource-list="${category.id}"]`).replaceChildren(retry);
      });
    }
  }

  function renderCategory(category) {
    const pane = modal.querySelector(`[data-pane="${category.id}"]`);
    const status = pane.querySelector('[data-resource-status]');
    const list = pane.querySelector(`[data-resource-list="${category.id}"]`);
    const files = documents.get(category.id) || [];
    list.replaceChildren();
    pane.querySelector(`[data-resource-preview="${category.id}"]`).replaceChildren();
    status.textContent = files.length
      ? `${files.length} ${files.length === 1 ? 'documento disponible' : 'documentos disponibles'}.`
      : 'Aún no se han agregado documentos a esta carpeta.';

    files.forEach(file => {
      const card = document.createElement('article');
      card.className = 'resources-card';
      const heading = document.createElement('h4');
      heading.textContent = file.name;
      const format = document.createElement('p');
      format.className = 'resources-format';
      format.textContent = file.extension.toUpperCase();
      const actions = document.createElement('div');
      actions.className = 'resources-actions';
      const link = document.createElement('a');
      link.href = file.url;
      link.target = '_blank';
      link.rel = 'noopener';
      link.textContent = 'Abrir documento';
      actions.append(link);

      if (['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'drawio'].includes(file.extension)) {
        const preview = document.createElement('button');
        preview.type = 'button';
        preview.className = 'btn ghost sm';
        preview.textContent = file.extension === 'drawio' ? 'Ver diagrama' : 'Vista previa';
        preview.addEventListener('click', () => showPreview(category.id, file));
        actions.append(preview);
      }

      card.append(heading, format, actions);
      list.append(card);
    });
  }

  async function showPreview(categoryId, file) {
    const pane = modal.querySelector(`[data-pane="${categoryId}"]`);
    const preview = pane.querySelector(`[data-resource-preview="${categoryId}"]`);
    preview.replaceChildren();
    const extension = file.extension;

    if (extension === 'pdf') {
      const frame = document.createElement('iframe');
      frame.className = 'resources-frame';
      frame.title = file.name;
      frame.loading = 'lazy';
      frame.src = file.url;
      preview.append(frame);
      return;
    }

    if (['png', 'jpg', 'jpeg', 'gif', 'webp'].includes(extension)) {
      const image = document.createElement('img');
      image.className = 'resources-image';
      image.alt = file.name;
      image.loading = 'lazy';
      image.src = file.url;
      preview.append(image);
      return;
    }

    const status = document.createElement('p');
    status.className = 'resources-status';
    status.textContent = 'Cargando la vista interactiva del diagrama…';
    const canvas = document.createElement('div');
    canvas.className = 'resources-diagram';
    canvas.setAttribute('role', 'region');
    canvas.setAttribute('aria-label', file.name);
    canvas.tabIndex = 0;
    preview.append(status, canvas);

    try {
      const response = await fetch(file.url, { credentials: 'same-origin' });
      if (!response.ok) throw new Error(`No se pudo cargar el diagrama (HTTP ${response.status}).`);
      const source = await response.text();
      const xml = new DOMParser().parseFromString(source, 'application/xml');
      if (xml.querySelector('parsererror')) throw new Error('El archivo del diagrama tiene un formato XML no válido.');
      renderDiagram(xml, canvas);
      status.textContent = 'Vista previa del diagrama. Desplázate para explorar el modelo completo.';
    } catch (error) {
      status.textContent = error.message || 'No se pudo mostrar el diagrama.';
    }
  }

  function renderDiagram(xml, canvas) {
    const namespace = 'http://www.w3.org/2000/svg';
    const create = (name, attributes, parent) => {
      const element = document.createElementNS(namespace, name);
      Object.entries(attributes).forEach(([key, value]) => element.setAttribute(key, String(value)));
      parent.append(element);
      return element;
    };
    const cells = [...xml.getElementsByTagName('mxCell')];
    const roots = cells.filter(cell => cell.getAttribute('vertex') === '1' && cell.getAttribute('parent') === '1');
    const boxes = new Map(roots.map(cell => {
      const geometry = cell.querySelector('mxGeometry');
      return [cell.id, {
        cell,
        x: Number(geometry && geometry.getAttribute('x')) || 0,
        y: Number(geometry && geometry.getAttribute('y')) || 0,
        width: Number(geometry && geometry.getAttribute('width')) || 0,
        height: Number(geometry && geometry.getAttribute('height')) || 0
      }];
    }));
    const width = Math.max(1350, ...[...boxes.values()].map(box => box.x + box.width + 70));
    const height = Math.max(850, ...[...boxes.values()].map(box => box.y + box.height + 70));
    const svg = create('svg', {
      xmlns: namespace,
      width,
      height,
      viewBox: `0 0 ${width} ${height}`,
      role: 'img',
      'aria-label': 'Diagrama entidad-relación de TechnoStore'
    }, canvas);
    create('rect', {
      width,
      height,
      fill: '#fff'
    }, svg);

    const text = (parent, value, x, y, attributes = {}) => {
      const label = create('text', {
        x,
        y,
        fill: '#1d2733',
        'font-family': 'Arial, sans-serif',
        'font-size': 12,
        ...attributes
      }, parent);
      String(value || '').split(/\r?\n/).forEach((line, index) => {
        const span = create('tspan', {
          x,
          dy: index === 0 ? 0 : 14
        }, label);
        span.textContent = line.trim();
      });
      return label;
    };
    const boundary = (box, target, diamond = false) => {
      const centerX = box.x + box.width / 2;
      const centerY = box.y + box.height / 2;
      const dx = target.x + target.width / 2 - centerX;
      const dy = target.y + target.height / 2 - centerY;
      const tx = Math.abs(dx) ? box.width / 2 / Math.abs(dx) : Infinity;
      const ty = Math.abs(dy) ? box.height / 2 / Math.abs(dy) : Infinity;
      const scale = diamond ? 1 / (Math.abs(dx) / (box.width / 2) + Math.abs(dy) / (box.height / 2)) : Math.min(tx, ty);
      return {
        x: centerX + dx * scale,
        y: centerY + dy * scale
      };
    };

    cells.filter(cell => cell.getAttribute('edge') === '1').forEach(edge => {
      const from = boxes.get(edge.getAttribute('source'));
      const to = boxes.get(edge.getAttribute('target'));
      if (!from || !to) return;
      const relationFrom = (from.cell.getAttribute('style') || '').includes('rhombus');
      const relationTo = (to.cell.getAttribute('style') || '').includes('rhombus');
      const start = boundary(from, to, relationFrom);
      const end = boundary(to, from, relationTo);
      create('line', {
        x1: start.x,
        y1: start.y,
        x2: end.x,
        y2: end.y,
        stroke: '#64748b',
        'stroke-width': 2
      }, svg);
      const cardinality = edge.getAttribute('value');
      if (cardinality) {
        const x = start.x + (end.x - start.x) * .28;
        const y = start.y + (end.y - start.y) * .28 - 5;
        create('rect', {
          x: x - 10,
          y: y - 14,
          width: 20,
          height: 18,
          rx: 4,
          fill: '#fff'
        }, svg);
        text(svg, cardinality, x, y, {
          'font-size': 12,
          'font-weight': 'bold',
          'text-anchor': 'middle'
        });
      }
    });

    roots.forEach(cell => {
      const box = boxes.get(cell.id);
      const style = cell.getAttribute('style') || '';
      if (style.includes('rhombus')) {
        create('polygon', {
          points: `${box.x + box.width / 2},${box.y} ${box.x + box.width},${box.y + box.height / 2} ${box.x + box.width / 2},${box.y + box.height} ${box.x},${box.y + box.height / 2}`,
          fill: '#fff2cc',
          stroke: '#bf9000',
          'stroke-width': 2
        }, svg);
        text(svg, cell.getAttribute('value'), box.x + box.width / 2, box.y + box.height / 2 + 4, {
          'font-size': 12,
          'font-weight': 'bold',
          'text-anchor': 'middle'
        });
        return;
      }

      create('rect', {
        x: box.x,
        y: box.y,
        width: box.width,
        height: box.height,
        rx: 3,
        fill: '#fff',
        stroke: '#1f4e79',
        'stroke-width': 2
      }, svg);
      create('rect', {
        x: box.x,
        y: box.y,
        width: box.width,
        height: 30,
        rx: 2,
        fill: '#1f4e79'
      }, svg);
      text(svg, cell.getAttribute('value'), box.x + 9, box.y + 20, {
        fill: '#fff',
        'font-size': 14,
        'font-weight': 'bold'
      });
      cells.filter(child => child.getAttribute('vertex') === '1' && child.getAttribute('parent') === cell.id).forEach(child => {
        const geometry = child.querySelector('mxGeometry');
        const rowY = box.y + (Number(geometry && geometry.getAttribute('y')) || 30);
        const value = child.getAttribute('value') || '';
        text(svg, value, box.x + 9, rowY + 15, {
          'font-size': 11
        });
        create('line', {
          x1: box.x,
          y1: rowY + 22,
          x2: box.x + box.width,
          y2: rowY + 22,
          stroke: '#e3e8ee',
          'stroke-width': 1
        }, svg);
      });
    });
    canvas.replaceChildren(svg);
  }

  openButton.addEventListener('click', () => {
    modal.classList.add('on');
    showResource('video');
    loadCatalog();
    modal.querySelector('[data-resource="video"]').focus();
  });
  closeButton.addEventListener('click', close);
  modal.addEventListener('mousedown', event => {
    if (event.target === modal) close();
  });
  modal.querySelectorAll('[data-resource]').forEach(tab => {
    tab.addEventListener('click', () => showResource(tab.dataset.resource));
    tab.addEventListener('keydown', event => {
      if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
      event.preventDefault();
      const tabs = [...modal.querySelectorAll('[data-resource]')];
      const current = tabs.indexOf(tab);
      const direction = event.key === 'ArrowRight' ? 1 : -1;
      const next = tabs[(current + direction + tabs.length) % tabs.length];
      showResource(next.dataset.resource);
      next.focus();
    });
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && modal.classList.contains('on')) close();
  });
})();
