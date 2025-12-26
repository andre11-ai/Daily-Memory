// public/js/admin_users_management4.js
// Modal estilo "Editar perfil" + admin checkbox + delete modal

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('admin-users-tbody');
    const paginationBox = document.getElementById('admin-users-pagination');
    const searchInput = document.getElementById('admin-users-search');
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    // Modales / campos
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
    const togglePasswordBtn = document.getElementById('toggle-password-visibility');
    const editCancelBtn = document.getElementById('admin-user-modal-cancel');
    const editCloseBtn = document.getElementById('admin-user-modal-close');

    const deleteBackdrop = document.getElementById('admin-delete-modal-backdrop');
    const deleteDesc = document.getElementById('delete-user-desc');
    const deleteConfirmBtn = document.getElementById('admin-delete-confirm');
    const deleteCancelBtn = document.getElementById('admin-delete-cancel');

    let currentPage = 1;
    let currentQuery = '';
    let deleteTargetId = null;
    let lastFocusedElement = null;

    if (!tableBody) return;

    // --- Utility: focus management ---
    function saveFocus() { lastFocusedElement = document.activeElement; }
    function restoreFocus() { try { lastFocusedElement && lastFocusedElement.focus(); } catch(e) {} }

    function showBackdrop(backdrop) {
        if (!backdrop) return;
        saveFocus();
        backdrop.style.display = 'flex';
        backdrop.setAttribute('aria-hidden', 'false');
        const dlg = backdrop.querySelector('.modal');
        if (dlg) { dlg.setAttribute('tabindex','-1'); dlg.focus(); }
    }
    function hideBackdrop(backdrop) {
        if (!backdrop) return;
        backdrop.style.display = 'none';
        backdrop.setAttribute('aria-hidden', 'true');
        restoreFocus();
    }

    // --- Fetch users (paginated) ---
    async function fetchUsers(page = 1, q = '') {
        currentPage = page;
        currentQuery = q;
        const url = `/admin/api/users?page=${page}&q=${encodeURIComponent(q)}`;

        try {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            if (!res.ok) {
                const txt = await res.text().catch(()=>null);
                console.error('fetchUsers error', res.status, txt);
                throw new Error('Error al obtener usuarios');
            }
            const data = await res.json();
            renderTable(data.data || []);
            renderPagination(data);
        } catch (err) {
            console.error(err);
            alert(err.message || 'Error al obtener usuarios');
        }
    }

    function renderTable(users) {
        tableBody.innerHTML = '';
        if (!users || users.length === 0) {
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
                    <button class="admin-action-btn edit btn" data-id="${user.id}" data-name="${escapeAttr(user.name)}" data-email="${escapeAttr(user.email)}" data-username="${escapeAttr(user.username || '')}">Editar</button>
                    <button class="admin-action-btn delete btn" data-id="${user.id}" data-name="${escapeAttr(user.name)}" data-email="${escapeAttr(user.email)}">Borrar</button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
        attachRowEvents(tableBody);
    }

    function renderPagination(meta) {
        if (!paginationBox) return;
        const current = meta.current_page || 1;
        const last = meta.last_page || 1;
        if (last <= 1) { paginationBox.innerHTML = ''; return; }
        let html = '';
        if (current > 1) html += `<button data-page="${current-1}" class="page-btn btn">Anterior</button>`;
        html += ` <span class="muted"> ${current} / ${last} </span> `;
        if (current < last) html += `<button data-page="${current+1}" class="page-btn btn">Siguiente</button>`;
        paginationBox.innerHTML = html;
        paginationBox.querySelectorAll('.page-btn').forEach(b => b.addEventListener('click', () => fetchUsers(Number(b.dataset.page), currentQuery)));
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

    // --- Edit modal ---
    async function openEditModalFromRow(btn) {
        const id = btn.dataset.id;
        if (!id) return;
        try {
            const res = await fetch(`/admin/api/users/${id}`, { headers:{ 'Accept':'application/json' }, credentials:'same-origin' });
            if (!res.ok) {
                const txt = await res.text().catch(()=>null);
                console.error('openEditModalFromRow error', res.status, txt);
                throw new Error('Error al cargar usuario');
            }
            const user = await res.json();
            fillEditForm(user);
            showBackdrop(editBackdrop);
        } catch (err) {
            console.error(err);
            alert(err.message || 'Error al cargar usuario');
        }
    }

    function fillEditForm(user) {
        modalUserId.value = user.id;
        modalUserIdDisplay.textContent = user.id;
        modalUserName.value = user.name || '';
        modalUserEmail.value = user.email || '';
        modalUserUsername.value = user.username || '';
        modalUserPassword.value = '';
        modalUserIsAdmin.checked = !!user.is_admin;
        if (user.avatar_url) modalUserAvatar.src = user.avatar_url;
    }

    // toggle password visibility
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function () {
            if (modalUserPassword.type === 'password') modalUserPassword.type = 'text';
            else modalUserPassword.type = 'password';
        });
    }

    function hideEditModal() { hideBackdrop(editBackdrop); }

    // Save edits
    if (editForm) {
        editForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const id = modalUserId.value;
            const payload = {
                name: modalUserName.value.trim(),
                email: modalUserEmail.value.trim(),
                username: modalUserUsername.value.trim(),
                is_admin: modalUserIsAdmin.checked,
                password: modalUserPassword.value || null
            };
            try {
                const res = await fetch(`/admin/api/users/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken || '' },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });
                const text = await res.text();
                let data = null;
                try { data = text ? JSON.parse(text) : null; } catch(_) { data = { message: text }; }
                if (!res.ok) throw data || { message:'Error al actualizar' };
                hideEditModal();
                updateRowAfterEdit(data.user || { id, name:payload.name, email:payload.email, is_admin:payload.is_admin });
                alert(data.message || 'Usuario actualizado.');
            } catch (err) {
                console.error('Error actualizando usuario:', err);
                alert((err && err.message) ? err.message : 'Error al actualizar usuario');
            }
        });
    }

    function updateRowAfterEdit(user) {
        if (!user) return;
        const btn = tableBody.querySelector(`.admin-action-btn.edit[data-id="${user.id}"]`);
        if (btn) {
            const row = btn.closest('tr');
            if (row) {
                const tds = row.querySelectorAll('td');
                if (tds.length >= 4) {
                    tds[0].textContent = user.name || '';
                    tds[1].textContent = user.email || '';
                    tds[2].textContent = user.is_admin ? 'Sí' : 'No';
                }
                btn.dataset.name = user.name || '';
                btn.dataset.email = user.email || '';
            }
        }
    }

    // --- Delete modal ---
    function openDeleteModalFromRow(btn) {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        if (!id) return;
        deleteTargetId = id;
        deleteDesc.textContent = `¿Estás seguro de eliminar al usuario "${name || id}"? Esta acción es irreversible.`;
        showBackdrop(deleteBackdrop);
    }
    function hideDeleteModal() { hideBackdrop(deleteBackdrop); }

    if (deleteConfirmBtn) {
        deleteConfirmBtn.addEventListener('click', async function () {
            if (!deleteTargetId) return;
            try {
                const res = await fetch(`/admin/api/users/${deleteTargetId}`, {
                    method: 'DELETE',
                    headers: { 'Accept':'application/json','X-CSRF-TOKEN': csrfToken || '' },
                    credentials: 'same-origin'
                });
                const text = await res.text();
                let data = null;
                try { data = text ? JSON.parse(text) : null; } catch(_) { data = { message:text }; }
                if (!res.ok) throw data || { message:'Error al eliminar' };
                hideDeleteModal();
                removeRowAfterDelete(deleteTargetId);
                alert(data.message || 'Usuario eliminado.');
            } catch (err) {
                console.error('Error eliminando usuario:', err);
                alert((err && err.message) ? err.message : 'Error al eliminar usuario');
            }
        });
    }

    function removeRowAfterDelete(id) {
        const btn = tableBody.querySelector(`.admin-action-btn.delete[data-id="${id}"]`);
        if (btn) {
            const row = btn.closest('tr');
            if (row) row.remove();
        }
    }

    // attach cancel/close
    if (editCancelBtn) editCancelBtn.addEventListener('click', hideEditModal);
    if (editCloseBtn) editCloseBtn.addEventListener('click', hideEditModal);
    if (deleteCancelBtn) deleteCancelBtn.addEventListener('click', hideDeleteModal);

    // simple escape click outside to close
    function setupOutsideClick(backdrop) {
        if (!backdrop) return;
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) hideBackdrop(backdrop);
        });
    }
    setupOutsideClick(editBackdrop);
    setupOutsideClick(deleteBackdrop);

    // helpers
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return String(unsafe).replace(/[&<"'>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
    }
    function escapeAttr(s) { return s ? String(s).replace(/"/g,'&quot;') : ''; }

    // search form
    const usersForm = document.getElementById('admin-users-form');
    if (usersForm) usersForm.addEventListener('submit', function(e){ e.preventDefault(); fetchUsers(1, (searchInput ? searchInput.value.trim() : '')); });

    // initialize
    attachRowEvents(document);
    fetchUsers();
});
// public/js/admin_users_management5.js
// Enhanced admin users management script:
// - live search (debounced)
// - edit modal loads avatar and admin checkbox
// - delete modal styled like other modals
// - updates table row after edit/delete

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('admin-users-tbody');
    const paginationBox = document.getElementById('admin-users-pagination');
    const searchInput = document.getElementById('admin-users-search');
    const searchBtn = document.getElementById('admin-users-search-btn');
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    // modals and form elements
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
    const togglePasswordBtn = document.getElementById('toggle-password-visibility');
    const editCancelBtn = document.getElementById('admin-user-modal-cancel');
    const editCloseBtn = document.getElementById('admin-user-modal-close');

    const deleteBackdrop = document.getElementById('admin-delete-modal-backdrop');
    const deleteDesc = document.getElementById('delete-user-desc');
    const deleteConfirmBtn = document.getElementById('admin-delete-confirm');
    const deleteCancelBtn = document.getElementById('admin-delete-cancel');
    const deleteCloseBtn = document.getElementById('admin-delete-modal-close');

    let currentPage = 1;
    let currentQuery = '';
    let deleteTargetId = null;
    let lastFocusedElement = null;

    if (!tableBody) return;

    // Debounce helper
    function debounce(fn, wait) {
        let t;
        return function (...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // Focus management
    function saveFocus() { lastFocusedElement = document.activeElement; }
    function restoreFocus() { try { lastFocusedElement && lastFocusedElement.focus(); } catch(e) {} }

    function showBackdrop(backdrop) {
        if (!backdrop) return;
        saveFocus();
        backdrop.style.display = 'flex';
        backdrop.setAttribute('aria-hidden', 'false');
        const dlg = backdrop.querySelector('.modal');
        if (dlg) { dlg.setAttribute('tabindex','-1'); dlg.focus(); }
    }
    function hideBackdrop(backdrop) {
        if (!backdrop) return;
        backdrop.style.display = 'none';
        backdrop.setAttribute('aria-hidden', 'true');
        restoreFocus();
    }

    // Build avatar url helper (fallbacks)
    function avatarUrlFromUser(user) {
        if (!user) return '';
        if (user.avatar_url) return user.avatar_url;
        if (user.profile_image) return `/user-avatar/${encodeURIComponent(user.profile_image)}`;
        return '/img/default-user.png';
    }

    // Fetch users
    async function fetchUsers(page = 1, q = '') {
        currentPage = page;
        currentQuery = q;
        const url = `/admin/api/users?page=${page}&q=${encodeURIComponent(q)}`;

        try {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            if (!res.ok) {
                const txt = await res.text().catch(()=>null);
                console.error('fetchUsers error', res.status, txt);
                throw new Error('Error al obtener usuarios');
            }
            const data = await res.json();
            renderTable(data.data || []);
            renderPagination(data);
        } catch (err) {
            console.error(err);
            // silent fail in UI
        }
    }

    // Live search: debounce input
    const debouncedFetch = debounce(() => fetchUsers(1, searchInput.value.trim()), 300);
    if (searchInput) {
        searchInput.addEventListener('input', debouncedFetch);
    }
    if (searchBtn) {
        searchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fetchUsers(1, searchInput.value.trim());
        });
    }

    function renderTable(users) {
        tableBody.innerHTML = '';
        if (!users || users.length === 0) {
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
                    <button class="admin-action-btn edit btn small" data-id="${user.id}" data-name="${escapeAttr(user.name)}" data-email="${escapeAttr(user.email)}" data-username="${escapeAttr(user.username || '')}">Editar</button>
                    <button class="admin-action-btn delete btn small btn-ghost" data-id="${user.id}" data-name="${escapeAttr(user.name)}" data-email="${escapeAttr(user.email)}">Borrar</button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
        attachRowEvents(tableBody);
    }

    function renderPagination(meta) {
        if (!paginationBox) return;
        const current = meta.current_page || 1;
        const last = meta.last_page || 1;
        if (last <= 1) { paginationBox.innerHTML = ''; return; }
        let html = '';
        if (current > 1) html += `<button data-page="${current-1}" class="page-btn btn">Anterior</button>`;
        html += ` <span class="muted"> ${current} / ${last} </span> `;
        if (current < last) html += `<button data-page="${current+1}" class="page-btn btn">Siguiente</button>`;
        paginationBox.innerHTML = html;
        paginationBox.querySelectorAll('.page-btn').forEach(b => b.addEventListener('click', () => fetchUsers(Number(b.dataset.page), currentQuery)));
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

    // Edit modal logic
    async function openEditModalFromRow(btn) {
        const id = btn.dataset.id;
        if (!id) return;
        try {
            const res = await fetch(`/admin/api/users/${id}`, { headers:{ 'Accept':'application/json' }, credentials:'same-origin' });
            if (!res.ok) {
                const txt = await res.text().catch(()=>null);
                console.error('openEditModalFromRow error', res.status, txt);
                throw new Error('Error al cargar usuario');
            }
            const user = await res.json();
            fillEditForm(user);
            showBackdrop(editBackdrop);
        } catch (err) {
            console.error(err);
            alert(err.message || 'Error al cargar usuario');
        }
    }

    function fillEditForm(user) {
        modalUserId.value = user.id;
        modalUserIdDisplay.textContent = user.id;
        modalUserName.value = user.name || '';
        modalUserEmail.value = user.email || '';
        modalUserUsername.value = user.username || '';
        modalUserPassword.value = '';
        modalUserIsAdmin.checked = !!user.is_admin;
        modalUserAvatar.src = avatarUrlFromUser(user) || '/img/default-user.png';
    }

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function () {
            if (modalUserPassword.type === 'password') modalUserPassword.type = 'text';
            else modalUserPassword.type = 'password';
        });
    }

    function hideEditModal() { hideBackdrop(editBackdrop); }

    // Save edits
    if (editForm) {
        editForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const id = modalUserId.value;
            const payload = {
                name: modalUserName.value.trim(),
                email: modalUserEmail.value.trim(),
                username: modalUserUsername.value.trim(),
                is_admin: modalUserIsAdmin.checked,
                password: modalUserPassword.value || null
            };
            try {
                const res = await fetch(`/admin/api/users/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN': csrfToken || '' },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });
                const text = await res.text();
                let data = null;
                try { data = text ? JSON.parse(text) : null; } catch(_) { data = { message: text }; }
                if (!res.ok) throw data || { message:'Error al actualizar' };
                hideEditModal();
                updateRowAfterEdit(data.user || { id, name:payload.name, email:payload.email, is_admin:payload.is_admin });
            } catch (err) {
                console.error('Error actualizando usuario:', err);
                alert((err && err.message) ? err.message : 'Error al actualizar usuario');
            }
        });
    }

    function updateRowAfterEdit(user) {
        if (!user) return;
        const btn = tableBody.querySelector(`.admin-action-btn.edit[data-id="${user.id}"]`);
        if (btn) {
            const row = btn.closest('tr');
            if (row) {
                const tds = row.querySelectorAll('td');
                if (tds.length >= 4) {
                    tds[0].textContent = user.name || '';
                    tds[1].textContent = user.email || '';
                    tds[2].textContent = user.is_admin ? 'Sí' : 'No';
                }
                btn.dataset.name = user.name || '';
                btn.dataset.email = user.email || '';
            }
        }
    }

    // Delete modal logic
    function openDeleteModalFromRow(btn) {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        if (!id) return;
        deleteTargetId = id;
        deleteDesc.textContent = `¿Estás seguro de eliminar al usuario "${name || id}"? Esta acción es irreversible.`;
        showBackdrop(deleteBackdrop);
    }
    function hideDeleteModal() { hideBackdrop(deleteBackdrop); }

    if (deleteConfirmBtn) {
        deleteConfirmBtn.addEventListener('click', async function () {
            if (!deleteTargetId) return;
            try {
                const res = await fetch(`/admin/api/users/${deleteTargetId}`, {
                    method: 'DELETE',
                    headers: { 'Accept':'application/json','X-CSRF-TOKEN': csrfToken || '' },
                    credentials: 'same-origin'
                });
                const text = await res.text();
                let data = null;
                try { data = text ? JSON.parse(text) : null; } catch(_) { data = { message:text }; }
                if (!res.ok) throw data || { message:'Error al eliminar' };
                hideDeleteModal();
                removeRowAfterDelete(deleteTargetId);
                deleteTargetId = null;
            } catch (err) {
                console.error('Error eliminando usuario:', err);
                alert((err && err.message) ? err.message : 'Error al eliminar usuario');
            }
        });
    }

    function removeRowAfterDelete(id) {
        const btn = tableBody.querySelector(`.admin-action-btn.delete[data-id="${id}"]`);
        if (btn) {
            const row = btn.closest('tr');
            if (row) row.remove();
        }
    }

    // attach cancel/close
    if (editCancelBtn) editCancelBtn.addEventListener('click', hideEditModal);
    if (editCloseBtn) editCloseBtn.addEventListener('click', hideEditModal);
    if (deleteCancelBtn) deleteCancelBtn.addEventListener('click', hideDeleteModal);
    if (deleteCloseBtn) deleteCloseBtn.addEventListener('click', hideDeleteModal);

    // click outside to close
    function setupOutsideClick(backdrop) {
        if (!backdrop) return;
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) hideBackdrop(backdrop);
        });
    }
    setupOutsideClick(editBackdrop);
    setupOutsideClick(deleteBackdrop);

    // helpers
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return String(unsafe).replace(/[&<"'>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
    }
    function escapeAttr(s) { return s ? String(s).replace(/"/g,'&quot;') : ''; }

    // search form submit prevention and linkage
    const usersForm = document.getElementById('admin-users-form');
    if (usersForm) {
        usersForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchUsers(1, searchInput.value.trim());
        });
    }

    // initialize
    attachRowEvents(document);
    fetchUsers();
});
