document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('admin-users-tbody');
    const paginationBox = document.getElementById('admin-users-pagination');
    const searchInput = document.getElementById('admin-users-search');
    const searchBtn = document.getElementById('admin-users-search-btn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const topGamesCanvas = document.getElementById('top-games-chart');
    const memoryPieCanvas = document.getElementById('memory-types-pie');
    const difficultyPieCanvas = document.getElementById('difficulty-pie');
    const scatterCanvas = document.getElementById('scatter-chart');
    const userLevelCanvas = document.getElementById('user-level-scatter');
    const userLevelDataEl = document.getElementById('scatter-data');

    const editBackdrop = document.getElementById('admin-user-modal-backdrop');
    const editForm = document.getElementById('admin-user-modal-form');
    const modalUserId = document.getElementById('modal-user-id');
    const modalUserIdDisplay = document.getElementById('modal-user-id-display');
    const modalUserName = document.getElementById('modal-user-name');
    const modalUserEmail = document.getElementById('modal-user-email');
    const modalUserUsername = document.getElementById('modal-user-username');
    const modalUserPassword = document.getElementById('modal-user-password');
    const modalUserIsAdmin = document.getElementById('modal-user-isadmin');
    const modalUserAvatar = document.getElementById('modal-user-avatar');
    const modalAvatarInput = document.getElementById('modal-avatar-input');
    const togglePasswordBtn = document.getElementById('toggle-password-visibility');
    const editCancelBtn = document.getElementById('admin-user-modal-cancel');
    const editCloseBtn = document.getElementById('admin-user-modal-close');

    const deleteBackdrop = document.getElementById('admin-delete-modal-backdrop');
    const deleteDesc = document.getElementById('delete-user-desc');
    const deleteConfirmBtn = document.getElementById('admin-delete-confirm');
    const deleteCancelBtn = document.getElementById('admin-delete-cancel');
    const deleteCloseBtn = document.getElementById('admin-delete-modal-close');

    const scatterMemorySel = document.getElementById('scatter-memory');
    const scatterDiffSel = document.getElementById('scatter-difficulty');
    const scatterApplyBtn = document.getElementById('scatter-apply');

    if (scatterCanvas) {
        scatterCanvas.style.maxHeight = '320px';
        scatterCanvas.height = 320;
    }

    let currentPage = 1;
    let currentQuery = '';
    let deleteTargetId = null;
    let lastFocusedElement = null;

    let topGamesChart = null;
    let memoryPieChart = null;
    let difficultyPieChart = null;
    let scatterChart = null;

    const palette = [
        '#EF476F', '#FFD166', '#06D6A0', '#118AB2', '#073B4C',
        '#F38BA0', '#FFB703', '#8ECAE6', '#219EBC', '#9B5DE5',
        '#00BBF9', '#F15BB5', '#FF6D00', '#4CC9F0', '#B5179E'
    ];
    const getColors = n => Array.from({ length: n }, (_, i) => palette[i % palette.length]);
    const debounce = (fn, wait = 250) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), wait); } };

    function saveFocus() { lastFocusedElement = document.activeElement; }
    function restoreFocus() { try { lastFocusedElement && lastFocusedElement.focus(); } catch (e) { } }
    function showBackdrop(backdrop) {
        saveFocus();
        backdrop.style.display = 'flex';
        backdrop.setAttribute('aria-hidden', 'false');
        const dlg = backdrop.querySelector('.modal');
        if (dlg) { dlg.setAttribute('tabindex', '-1'); dlg.focus(); }
    }
    function hideBackdrop(backdrop) {
        backdrop.style.display = 'none';
        backdrop.setAttribute('aria-hidden', 'true');
        restoreFocus();
    }
    function avatarUrlFromUser(user) {
        if (user?.avatar_url) return user.avatar_url;
        if (user?.profile_image) return `/storage/${user.profile_image}`;
        return '/img/default-user.png';
    }
    function toast(msg, type = 'ok') {
        const div = document.createElement('div');
        div.textContent = msg;
        Object.assign(div.style, {
            position: 'fixed', top: '16px', right: '16px', padding: '12px 14px',
            borderRadius: '8px', color: '#fff', zIndex: 9999,
            background: type === 'error' ? '#e53e3e' : '#2f855a'
        });
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 2800);
    }
    function escapeHtml(unsafe = '') {
        return String(unsafe).replace(/[&<"'>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m]));
    }
    function escapeAttr(s = '') { return String(s).replace(/"/g, '&quot;'); }

    async function fetchUsers(page = 1, q = '') {
        if (!tableBody) return;
        currentPage = page; currentQuery = q;
        const res = await fetch(`/admin/api/users?page=${page}&q=${encodeURIComponent(q)}`, {
            headers: { Accept: 'application/json' }, credentials: 'same-origin'
        });
        if (!res.ok) { console.error(await res.text()); return; }
        const data = await res.json();
        renderTable(data.data || []);
        renderPagination(data);
    }

    function renderTable(users) {
        tableBody.innerHTML = '';
        if (!users.length) {
            tableBody.innerHTML = '<tr><td colspan="5">No hay usuarios.</td></tr>';
            return;
        }
        users.forEach(user => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escapeHtml(user.name)}</td>
                <td>${escapeHtml(user.email)}</td>
                <td>${user.is_admin ? 'Sí' : 'No'}</td>
                <td>${new Date(user.created_at).toLocaleString()}</td>
                <td>
                    <button class="admin-action-btn edit btn small"
                        data-id="${user.id}"
                        data-name="${escapeAttr(user.name)}"
                        data-email="${escapeAttr(user.email)}"
                        data-username="${escapeAttr(user.username || '')}">Editar</button>
                    <button class="admin-action-btn delete btn small btn-ghost"
                        data-id="${user.id}"
                        data-name="${escapeAttr(user.name)}"
                        data-email="${escapeAttr(user.email)}">Borrar</button>
                </td>`;
            tableBody.appendChild(tr);
        });
        attachRowEvents(tableBody);
    }

    function renderPagination(meta) {
        if (!paginationBox) return;
        const c = meta.current_page || 1;
        const l = meta.last_page || 1;
        if (l <= 1) { paginationBox.innerHTML = ''; return; }
        let html = '';
        if (c > 1) html += `<button data-page="${c - 1}" class="page-btn btn">Anterior</button>`;
        html += ` <span class="muted">${c} / ${l}</span> `;
        if (c < l) html += `<button data-page="${c + 1}" class="page-btn btn">Siguiente</button>`;
        paginationBox.innerHTML = html;
        paginationBox.querySelectorAll('.page-btn').forEach(b =>
            b.addEventListener('click', () => fetchUsers(Number(b.dataset.page), currentQuery))
        );
    }

    function attachRowEvents(root = document) {
        root.querySelectorAll('.admin-action-btn.edit').forEach(btn => {
            if (btn._boundEdit) return;
            btn._boundEdit = true;
            btn.addEventListener('click', () => openEditModalFromRow(btn));
        });
        root.querySelectorAll('.admin-action-btn.delete').forEach(btn => {
            if (btn._boundDelete) return;
            btn._boundDelete = true;
            btn.addEventListener('click', () => openDeleteModalFromRow(btn));
        });
    }

    async function openEditModalFromRow(btn) {
        const id = btn.dataset.id;
        if (!id) return;
        const res = await fetch(`/admin/api/users/${id}`, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) { console.error(await res.text()); toast('Error al cargar usuario', 'error'); return; }
        const user = await res.json();
        fillEditForm(user);
        showBackdrop(editBackdrop);
    }

    function fillEditForm(user) {
        modalUserId.value = user.id;
        modalUserIdDisplay.textContent = user.id;
        modalUserName.value = user.name || '';
        modalUserEmail.value = user.email || '';
        modalUserUsername.value = user.username || '';
        modalUserPassword.value = '';
        modalUserIsAdmin.checked = !!user.is_admin;
        modalUserAvatar.src = avatarUrlFromUser(user);
    }

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', () => {
            modalUserPassword.type = (modalUserPassword.type === 'password') ? 'text' : 'password';
        });
    }

    function hideEditModal() { hideBackdrop(editBackdrop); }

    if (editForm) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = modalUserId.value;
            const fd = new FormData();
            fd.append('name', modalUserName.value.trim());
            fd.append('email', modalUserEmail.value.trim());
            fd.append('username', modalUserUsername.value.trim());
            fd.append('is_admin', modalUserIsAdmin.checked ? 1 : 0);
            if (modalUserPassword.value.trim()) fd.append('password', modalUserPassword.value.trim());
            if (modalAvatarInput?.files?.[0]) fd.append('profile_image', modalAvatarInput.files[0]);

            const res = await fetch(`/admin/api/users/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                credentials: 'same-origin',
                body: fd
            });
            const text = await res.text();
            let data = null; try { data = text ? JSON.parse(text) : {}; } catch (_) { data = { message: text }; }
            if (!res.ok) { toast(data?.message || 'Error al actualizar', 'error'); return; }

            hideEditModal();
            updateRowAfterEdit(data.user || { id, name: fd.get('name'), email: fd.get('email'), is_admin: !!fd.get('is_admin') });
            toast(data.message || 'Usuario actualizado');
        });
    }

    function updateRowAfterEdit(user) {
        const btn = tableBody.querySelector(`.admin-action-btn.edit[data-id="${user.id}"]`);
        if (!btn) return;
        const row = btn.closest('tr');
        if (!row) return;
        const tds = row.querySelectorAll('td');
        if (tds.length >= 4) {
            tds[0].textContent = user.name || '';
            tds[1].textContent = user.email || '';
            tds[2].textContent = user.is_admin ? 'Sí' : 'No';
        }
        btn.dataset.name = user.name || '';
        btn.dataset.email = user.email || '';
    }

    function openDeleteModalFromRow(btn) {
        deleteTargetId = btn.dataset.id;
        deleteDesc.textContent = `¿Estás seguro de eliminar al usuario "${btn.dataset.name || deleteTargetId}"? Esta acción es irreversible.`;
        showBackdrop(deleteBackdrop);
    }
    function hideDeleteModal() { hideBackdrop(deleteBackdrop); }

    if (deleteConfirmBtn) {
        deleteConfirmBtn.addEventListener('click', async () => {
            if (!deleteTargetId) return;
            const res = await fetch(`/admin/api/users/${deleteTargetId}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                credentials: 'same-origin'
            });
            const text = await res.text();
            let data = null; try { data = text ? JSON.parse(text) : {}; } catch (_) { data = { message: text }; }
            if (!res.ok) { toast(data?.message || 'Error al eliminar', 'error'); return; }
            removeRowAfterDelete(deleteTargetId);
            deleteTargetId = null;
            hideDeleteModal();
            toast(data.message || 'Usuario eliminado');
        });
    }

    function removeRowAfterDelete(id) {
        const btn = tableBody.querySelector(`.admin-action-btn.delete[data-id="${id}"]`);
        const row = btn?.closest('tr');
        if (row) row.remove();
    }

    if (editCancelBtn) editCancelBtn.addEventListener('click', hideEditModal);
    if (editCloseBtn) editCloseBtn.addEventListener('click', hideEditModal);
    if (deleteCancelBtn) deleteCancelBtn.addEventListener('click', hideDeleteModal);
    if (deleteCloseBtn) deleteCloseBtn.addEventListener('click', hideDeleteModal);

    [editBackdrop, deleteBackdrop].forEach(backdrop => {
        if (!backdrop) return;
        backdrop.addEventListener('click', (e) => { if (e.target === backdrop) hideBackdrop(backdrop); });
    });

    if (searchInput) {
        const debouncedSearch = debounce(() => fetchUsers(1, searchInput.value.trim()), 250);
        searchInput.addEventListener('input', debouncedSearch);
    }
    if (searchBtn) {
        searchBtn.addEventListener('click', (e) => { e.preventDefault(); fetchUsers(1, searchInput.value.trim()); });
    }

    async function fetchTopGames() {
        if (!topGamesCanvas) return;
        const res = await fetch('/admin/api/stats/top-games', { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) { console.error(await res.text()); return; }
        const rows = await res.json();
        const labels = rows.map(r => r.game);
        const data = rows.map(r => r.plays);
        const colors = data.map((_, i) => palette[i % palette.length]);

        if (topGamesChart) topGamesChart.destroy();
        topGamesChart = new Chart(topGamesCanvas.getContext('2d'), {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Partidas', data, backgroundColor: colors, borderColor: '#fff', borderWidth: 1, borderRadius: 6 }] },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }

    async function fetchMemoryPie() {
        if (!memoryPieCanvas) return;
        const res = await fetch('/admin/api/stats/memory-types', { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) { console.error(await res.text()); return; }
        let rows = await res.json();

        const memoryMap = {
            iconica: ['color', 'memorama', 'secuencia', 'secuencia-color', 'secuencia-color-game'],
            ecoica: ['simon', 'repetir', 'sonido', 'sonido-pareja'],
            muscular: ['scary', 'velocimetro', 'velocímetro', 'lluvia', 'lluvia-letras']
        };

        const totalFromDB = (rows || []).reduce((a, r) => a + Number(r.plays || 0), 0);
        if (!rows || rows.length === 0 || totalFromDB === 0) {
            const allRes = await fetch('/admin/api/stats/scatter-plays', { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
            if (allRes.ok) {
                const gameRows = await allRes.json();
                const sums = { muscular: 0, ecoica: 0, iconica: 0 };
                gameRows.forEach(r => {
                    const g = String(r.game || '').toLowerCase();
                    const plays = Number(r.plays || 0);
                    const belongs = (list) => list.some(k => g.includes(k));
                    if (belongs(memoryMap.iconica)) sums.iconica += plays;
                    else if (belongs(memoryMap.ecoica)) sums.ecoica += plays;
                    else if (belongs(memoryMap.muscular)) sums.muscular += plays;
                });
                rows = [
                    { memory_type: 'muscular', plays: sums.muscular },
                    { memory_type: 'ecoica', plays: sums.ecoica },
                    { memory_type: 'iconica', plays: sums.iconica },
                ];
            }
        }

        const dataRaw = rows.map(r => Number(r.plays) || 0);
        const total = dataRaw.reduce((a, b) => a + b, 0);
        const data = total === 0 ? [1, 1, 1] : dataRaw;

        const labelMap = {
            'muscular': 'muscular',
            'ecoica': 'ecoica',
            'econica': 'ecoica',
            'iconica': 'iconica',
            'icónica': 'iconica'
        };
        const labels = rows.map(r => labelMap[String(r.memory_type || '').toLowerCase()] || (r.memory_type || 'N/D'));

        if (memoryPieChart) memoryPieChart.destroy();
        memoryPieChart = new Chart(memoryPieCanvas.getContext('2d'), {
            type: 'pie',
            data: { labels, datasets: [{ data, backgroundColor: getColors(data.length) }] },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    async function fetchDifficultyPie() {
        if (!difficultyPieCanvas) return;
        const res = await fetch('/admin/api/stats/difficulty-counts', { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) { console.error(await res.text()); return; }
        const rows = await res.json();
        const labels = rows.map(r => r.difficulty || 'N/D');
        const data = rows.map(r => r.plays);
        if (difficultyPieChart) difficultyPieChart.destroy();
        difficultyPieChart = new Chart(difficultyPieCanvas.getContext('2d'), {
            type: 'pie',
            data: { labels, datasets: [{ data, backgroundColor: getColors(data.length) }] },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    async function loadMetaForScatter() {
        const res = await fetch('/admin/api/stats/meta', { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) { console.error(await res.text()); return; }
        const meta = await res.json();
        if (scatterMemorySel) {
            scatterMemorySel.innerHTML = '<option value="">Todas</option>';
            const baseMemories = ['muscular', 'ecoica', 'iconica'];
            const apiMemories = (meta.memory_types || []).filter(Boolean);
            const merged = Array.from(new Set([...baseMemories, ...apiMemories]));
            merged.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m; opt.textContent = m;
                scatterMemorySel.appendChild(opt);
            });
        }
        if (scatterDiffSel) {
            scatterDiffSel.innerHTML = '<option value="">Todas</option>';
            (meta.difficulties || []).forEach(d => {
                const opt = document.createElement('option');
                opt.value = d; opt.textContent = d;
                scatterDiffSel.appendChild(opt);
            });
        }
    }

    async function fetchScatter() {
        if (!scatterCanvas) return;

        const difficulty = scatterDiffSel?.value || '';
        const selectedMemory = scatterMemorySel?.value || '';
        const memoryMap = {
            iconica: ['color', 'memorama', 'secuencia', 'secuencia-color', 'secuencia-color-game'],
            ecoica: ['simon', 'repetir', 'repetir-palabra', 'sonido', 'sonido-pareja'],
            muscular: ['scary', 'velocimetro', 'velocímetro', 'lluvia', 'lluvia-letras']
        };

        const params = new URLSearchParams();
        if (difficulty) params.append('difficulty', difficulty);

        const res = await fetch(`/admin/api/stats/scatter-plays?${params.toString()}`, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) { console.error(await res.text()); return; }
        const gameRows = await res.json();

        const memorySumsByGame = { muscular: {}, ecoica: {}, iconica: {} };
        gameRows.forEach(r => {
            const g = String(r.game || '').toLowerCase();
            const plays = Number(r.plays || 0);
            const belongs = (list) => list.some(k => g.includes(k));
            if (belongs(memoryMap.iconica)) memorySumsByGame.iconica[g] = plays;
            else if (belongs(memoryMap.ecoica)) memorySumsByGame.ecoica[g] = plays;
            else if (belongs(memoryMap.muscular)) memorySumsByGame.muscular[g] = plays;
        });

        const memories = selectedMemory ? [selectedMemory] : ['muscular', 'ecoica', 'iconica'];
        const allLabelsSet = new Set();
        memories.forEach(mem => Object.keys(memorySumsByGame[mem] || {}).forEach(g => allLabelsSet.add(g)));
        const labels = Array.from(allLabelsSet);

        const colors = getColors(memories.length);
        const chartDatasets = memories.map((mem, idx) => {
            const mapPlays = memorySumsByGame[mem] || {};
            const data = labels.map(lbl => ({ x: lbl, y: mapPlays[lbl] || 0 }));
            return {
                label: mem,
                data,
                parsing: false,
                showLine: true,
                borderColor: colors[idx],
                backgroundColor: colors[idx],
                pointBackgroundColor: colors[idx],
                pointBorderColor: '#fff',
                pointRadius: 5,
                tension: 0.25
            };
        });

        if (scatterChart) scatterChart.destroy();
        scatterChart = new Chart(scatterCanvas.getContext('2d'), {
            type: 'line',
            data: { labels, datasets: chartDatasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'bottom' } },
                parsing: false,
                scales: {
                    x: { type: 'category', title: { display: true, text: 'Juego' } },
                    y: { beginAtZero: true, title: { display: true, text: 'Partidas' }, ticks: { precision: 0 } }
                }
            }
        });
    }
    if (scatterApplyBtn) scatterApplyBtn.addEventListener('click', fetchScatter);

    attachRowEvents(document);
    fetchUsers();
    fetchTopGames();
    fetchMemoryPie();
    fetchDifficultyPie();
    loadMetaForScatter().then(fetchScatter);

    if (userLevelCanvas && userLevelDataEl) {
        try {
            const userLevelData = JSON.parse(userLevelDataEl.textContent);
            const userLabels = userLevelData.map(item => item.label);
            const chartData = userLevelData.map(user => ({
                x: parseFloat(user.x),
                y: userLabels.indexOf(user.label),
            }));

            new Chart(userLevelCanvas, {
                type: 'scatter',
                data: {
                    labels: userLabels,
                    datasets: [{
                        label: 'Promedio de Nivel por Usuario',
                        data: chartData,
                        backgroundColor: 'rgba(75, 192, 192, 0.4)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Nivel promedio'
                            }
                        },
                        y: {
                            type: 'category',
                            labels: userLabels,
                            title: {
                                display: true,
                                text: 'Usuarios'
                            }
                        }
                    }
                }
            });
        } catch (e) {
            console.error('Error parsing user level data:', e.message);
        }
    }
});
