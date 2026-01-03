
(function () {
    function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
    function qsa(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }
    function apiFetch(url, opts) {
        opts = opts || {};
        opts.headers = opts.headers || {};
        if (opts.method && opts.method.toUpperCase() === 'POST') {
            opts.headers['Content-Type'] = 'application/json';
            opts.headers['X-CSRF-TOKEN'] = window.ChatConfig.csrfToken;
        }
        return fetch(url, opts).then(r => r.json());
    }
    function linkify(text) {
        return text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" style="color:var(--accent-color);text-decoration:underline;">$1</a>');
    }

    function ensureFeedbackModal() {
        let feedbackModal = document.getElementById('feedback-modal');
        if (!feedbackModal) {
            feedbackModal = document.createElement('div');
            feedbackModal.id = 'feedback-modal';
            feedbackModal.className = 'feedback-modal';
            feedbackModal.style.display = 'none';
            feedbackModal.innerHTML = `
                <div class="feedback-content">
                    <span id="feedback-message"></span>
                    <div class="feedback-btns" id="feedback-btns"></div>
                </div>
            `;
            document.body.appendChild(feedbackModal);
        }
        return feedbackModal;
    }
    function showFeedback(message, options = {closeOnly:true, onOk:null, onCancel:null, okText:'Cerrar', cancelText:'Cancelar'}) {
        let feedbackModal = ensureFeedbackModal();
        let feedbackMsg = feedbackModal.querySelector('#feedback-message');
        let feedbackBtns = feedbackModal.querySelector('#feedback-btns');
        feedbackMsg.innerHTML = message;
        feedbackBtns.innerHTML = '';
        let closeBtn = document.createElement('span');
        closeBtn.className = 'feedback-close';
        closeBtn.innerHTML = '&times;';
        closeBtn.onclick = () => { feedbackModal.style.display = 'none'; };
        let content = feedbackModal.querySelector('.feedback-content');
        if (content) {
            if (content.querySelector('.feedback-close')) content.querySelector('.feedback-close').remove();
            content.prepend(closeBtn);
        }
        if (options.closeOnly) {
            let btn = document.createElement('button');
            btn.className = 'feedback-btn';
            btn.innerText = options.okText || 'Cerrar';
            btn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onOk) options.onOk(); };
            feedbackBtns.appendChild(btn);
        } else {
            let okBtn = document.createElement('button');
            okBtn.className = 'feedback-btn';
            okBtn.innerText = options.okText || 'Aceptar';
            okBtn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onOk) options.onOk(); };
            feedbackBtns.appendChild(okBtn);
            let cancelBtn = document.createElement('button');
            cancelBtn.className = 'feedback-btn cancel';
            cancelBtn.innerText = options.cancelText || 'Cancelar';
            cancelBtn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onCancel) options.onCancel(); };
            feedbackBtns.appendChild(cancelBtn);
        }
        feedbackModal.style.display = 'flex';
    }

    function createEmojiPicker(pickerId, btnId, inputSelector) {
        const btn = document.getElementById(btnId);
        const input = document.querySelector(inputSelector);
        const popup = document.getElementById(pickerId);
        if (!btn || !input || !popup) return;
        popup.innerHTML = '';
        const pickerElem = document.createElement('emoji-picker');
        popup.appendChild(pickerElem);

        function closePicker() { popup.style.display = 'none'; }
        btn.addEventListener('click', function(e) {
            popup.style.display = 'block';
            popup.style.left = (btn.offsetLeft) + "px";
            popup.style.top = (btn.offsetTop - popup.offsetHeight - 10) + "px";
        });
        document.addEventListener('click', function(e) {
            if (!popup.contains(e.target) && e.target !== btn) {
                closePicker();
            }
        });
        pickerElem.addEventListener('emoji-click', function(event) {
            input.value += event.detail.unicode;
            closePicker();
        });
        closePicker();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const authId = parseInt(window.ChatConfig.authId, 10);

        function showChatTab(tab) {
            qsa('.chat-tab-btn').forEach(btn => btn.classList.remove('active'));
            qsa('.chat-panel').forEach(panel => panel.classList.remove('active'));
            const btn = document.getElementById('tab-btn-' + tab);
            const panel = document.getElementById('panel-' + tab);
            if (btn) btn.classList.add('active');
            if (panel) panel.classList.add('active');
        }
        qsa('.chat-tab-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const tab = btn.getAttribute('data-tab');
                showChatTab(tab);
                if (tab === 'personal') {
                    loadPersonalChatsList(selectedUserId);
                }
            });
        });
        showChatTab('personal');

        // ------- Chat Mundial -------
        const chatMessages = document.getElementById('messages-mundial');
        const chatInput = qs('#mundial-chat-input');
        const sendBtn = document.getElementById('mundial-chat-send');

        createEmojiPicker('emoji-picker-mundial-popup', 'emoji-btn-mundial', '#mundial-chat-input');

        function loadMessages() {
            apiFetch('/chat/mundial/messages')
                .then(data => {
                    if (!Array.isArray(data)) return;
                    chatMessages.innerHTML = '';
                    data.forEach(msg => {
                        let isMine = msg.user.id === authId;
                        chatMessages.innerHTML += `
                            <div class="chat-message-row ${isMine ? 'mine-row' : ''}">
                                ${!isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                                <div class="chat-message-bubble ${isMine ? 'mine' : ''}">
                                    <div class="chat-message-meta">
                                        <span class="chat-message-name">${msg.user.name}</span>
                                        <span class="chat-message-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                    </div>
                                    <div>${linkify(msg.message)}</div>
                                </div>
                                ${isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                            </div>
                        `;
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(() => {});
        }

        function sendMessage() {
            let text = chatInput.value.trim();
            if (!text) return;
            sendBtn.disabled = true;
            apiFetch('/chat/mundial/send', { method: 'POST', body: JSON.stringify({ message: text }) })
                .then(() => {
                    chatInput.value = '';
                    loadMessages();
                })
                .finally(() => sendBtn.disabled = false);
        }
        if (sendBtn) sendBtn.addEventListener('click', sendMessage);
        if (chatInput) chatInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') sendMessage(); });

        setInterval(loadMessages, 5000);
        loadMessages();

        // ------- Chat Grupos -------
        const groupSelect = document.getElementById('group-select');
        const groupMessages = document.getElementById('messages-grupos');
        const groupInput = document.getElementById('group-chat-input');
        const groupSendBtn = document.getElementById('group-chat-send');
        createEmojiPicker('emoji-picker-grupos-popup', 'emoji-btn-grupos', '#group-chat-input');

        let currentGroupId = null;
        let currentGroup = null;
        let currentGroupMembers = [];
        let currentAdminId = null;
        const inviteUsernameModal = document.getElementById('invite-username-modal');
        const inviteAutocomplete = document.getElementById('invite-autocomplete');
        let selectedInviteUsername = null;

        if (inviteUsernameModal) {
            inviteUsernameModal.addEventListener('input', function() {
                let q = this.value.trim();
                if (q.length < 2) {
                    inviteAutocomplete.style.display = 'none';
                    selectedInviteUsername = null;
                    return;
                }
                apiFetch(`/chat/grupo/${currentGroupId}/search-user?q=${encodeURIComponent(q)}`)
                    .then(users => {
                        if (!users.length) { inviteAutocomplete.style.display = 'none'; selectedInviteUsername = null; return; }
                        inviteAutocomplete.innerHTML = '';
                        users.forEach(u => {
                            inviteAutocomplete.innerHTML += `<div class="invite-autocomplete-item" data-username="${u.name}">${u.name}</div>`;
                        });
                        inviteAutocomplete.style.display = 'block';
                    })
                    .catch(() => {});
            });
        }

        if (inviteAutocomplete) {
            inviteAutocomplete.addEventListener('click', function(e) {
                if (e.target.classList.contains('invite-autocomplete-item')) {
                    selectedInviteUsername = e.target.getAttribute('data-username');
                    inviteUsernameModal.value = selectedInviteUsername;
                    inviteAutocomplete.style.display = 'none';
                }
            });
        }

        const inviteForm = document.getElementById('invite-user-form-modal');
        if (inviteForm) {
            inviteForm.addEventListener('submit', function(e){
                e.preventDefault();
                let username = selectedInviteUsername || inviteUsernameModal.value.trim();
                if(!username || !currentGroupId) return;
                apiFetch(`/chat/grupo/${currentGroupId}/invite`, { method: 'POST', body: JSON.stringify({ username }) })
                    .then(data => {
                        inviteUsernameModal.value = '';
                        selectedInviteUsername = null;
                        inviteAutocomplete.style.display = 'none';
                        loadGroupDetails(currentGroupId);
                        showFeedback(data.message || 'Usuario invitado');
                    })
                    .catch(() => {});
            });
        }

        const openCreateGroupBtn = document.getElementById('open-create-group-modal');
        if (openCreateGroupBtn) {
            openCreateGroupBtn.onclick = () => { document.getElementById('create-group-modal').style.display = 'flex'; };
        }
        const openManageGroupBtn = document.getElementById('open-manage-group-modal');
        if (openManageGroupBtn) {
            openManageGroupBtn.onclick = () => {
                document.getElementById('manage-group-modal').style.display = 'flex';
                renderGroupMembersModal(currentGroupMembers, currentAdminId);
                updateManageActions(currentGroup);
            };
        }

        function closeModal(id) { const el = document.getElementById(id); if (el) el.style.display = 'none'; }

        const createGroupForm = document.getElementById('create-group-form-modal');
        if (createGroupForm) {
            createGroupForm.addEventListener('submit', function(e){
                e.preventDefault();
                let name = document.getElementById('new-group-name-modal').value.trim();
                if(!name) return;
                apiFetch('/chat/grupos/create', { method: 'POST', body: JSON.stringify({ name }) })
                    .then(() => {
                        document.getElementById('new-group-name-modal').value = '';
                        closeModal('create-group-modal');
                        loadGroups();
                        showFeedback('Grupo creado exitosamente');
                    })
                    .catch(() => {});
            });
        }

        function loadGroups() {
            apiFetch('/chat/grupos')
                .then(groups => {
                    groupSelect.innerHTML = '';
                    groups.forEach(g => {
                        groupSelect.innerHTML += `<option value="${g.id}">${g.name}</option>`;
                    });
                    if (groups.length) {
                        currentGroupId = groups[0].id;
                        loadGroupDetails(currentGroupId);
                    } else {
                        currentGroupId = null;
                        groupMessages.innerHTML = '<div style="padding:.5rem;color:#888">Sin grupos.</div>';
                    }
                })
                .catch(() => {});
        }

        if (groupSelect) {
            groupSelect.addEventListener('change', function() {
                currentGroupId = this.value;
                loadGroupDetails(currentGroupId);
            });
        }

        function loadGroupDetails(groupId) {
            apiFetch(`/chat/grupo/${groupId}/info`)
                .then(data => {
                    currentGroup = data;
                    currentGroupMembers = data.members || [];
                    currentAdminId = data.admin_id;
                    renderGroupMembers(currentGroupMembers, currentAdminId);
                    updateManageActions(data);
                    loadGroupMessages(groupId);
                })
                .catch(() => {});
        }

        function renderGroupMembers(members, adminId) {
            let container = document.getElementById('group-members-list');
            if (!container) {
                return;
            }
            container.innerHTML = '';
            members.forEach(m => {
                let classes = 'group-member';
                if(m.id === authId) classes += ' me';
                if(m.id === adminId) classes += ' admin';
                container.innerHTML += `<span class="${classes}">${m.name}</span>`;
            });
        }
        function renderGroupMembersModal(members, adminId) {
            let container = document.getElementById('group-members-list-modal');
            if (!container) return;
            container.innerHTML = '';
            members.forEach(m => {
                let isAdmin = m.id === adminId;
                let isMe = m.id === authId;
                container.innerHTML += `
                    <div class="group-member-row group-member${isAdmin ? ' admin' : ''}${isMe ? ' me' : ''}">
                        <div class="group-member-avatar">${m.name.charAt(0).toUpperCase()}</div>
                        <div class="group-member-name">${m.name}${isAdmin ? ' <span style="font-size:0.85em;color:#009688;font-weight:600;">(admin)</span>' : ''}${isMe ? ' <span style="font-size:0.85em;color:#2196f3;">(tú)</span>' : ''}</div>
                    </div>
                `;
            });
        }

        function updateManageActions(group) {
            if (!group) return;
            const inviteFormEl = document.getElementById('invite-user-form-modal');
            const deleteBtn = document.getElementById('delete-group-btn-modal');
            const leaveBtn = document.getElementById('leave-group-btn-modal');
            if (inviteFormEl) inviteFormEl.style.display = (group.is_admin ? 'flex' : 'none');
            if (deleteBtn) deleteBtn.style.display = (group.is_admin ? 'inline-block' : 'none');
            if (leaveBtn) leaveBtn.style.display = (group.is_member ? 'inline-block' : 'none');
        }

        function loadGroupMessages(groupId) {
            apiFetch(`/chat/grupo/${groupId}/messages`)
                .then(data => {
                    groupMessages.innerHTML = '';
                    data.forEach(msg => {
                        let isMine = msg.user.id === authId;
                        groupMessages.innerHTML += `
                            <div class="chat-message-row ${isMine ? 'mine-row' : ''}">
                                ${!isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                                <div class="chat-message-bubble ${isMine ? 'mine' : ''}">
                                    <div class="chat-message-meta">
                                        <span class="chat-message-name">${msg.user.name}</span>
                                        <span class="chat-message-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                    </div>
                                    <div>${linkify(msg.message)}</div>
                                </div>
                                ${isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                            </div>
                        `;
                    });
                    groupMessages.scrollTop = groupMessages.scrollHeight;
                })
                .catch(() => {});
        }

        function sendGroupMessage() {
            let text = groupInput.value.trim();
            if (!text || !currentGroupId) return;
            groupSendBtn.disabled = true;
            apiFetch(`/chat/grupo/${currentGroupId}/send`, { method: 'POST', body: JSON.stringify({ message: text }) })
                .then(() => {
                    groupInput.value = '';
                    loadGroupMessages(currentGroupId);
                })
                .finally(() => { groupSendBtn.disabled = false; });
        }
        if (groupSendBtn) groupSendBtn.addEventListener('click', sendGroupMessage);
        if (groupInput) groupInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') sendGroupMessage(); });

        setInterval(function(){ if(currentGroupId) loadGroupMessages(currentGroupId); }, 5000);

        const leaveBtnModal = document.getElementById('leave-group-btn-modal');
        if (leaveBtnModal) {
            leaveBtnModal.addEventListener('click', function(){
                showFeedback('¿Seguro que deseas salir del grupo?', {
                    closeOnly: false,
                    onOk: function() {
                        apiFetch(`/chat/grupo/${currentGroupId}/leave`, { method: 'POST' })
                            .then(() => {
                                closeModal('manage-group-modal');
                                loadGroups();
                                showFeedback('Has salido del grupo');
                            })
                            .catch(() => {});
                    },
                    okText: 'Salir',
                    cancelText: 'Cancelar'
                });
            });
        }

        const deleteBtnModal = document.getElementById('delete-group-btn-modal');
        if (deleteBtnModal) {
            deleteBtnModal.addEventListener('click', function(){
                showFeedback('¿Seguro que deseas eliminar el grupo?', {
                    closeOnly: false,
                    onOk: function() {
                        apiFetch(`/chat/grupo/${currentGroupId}/delete`, { method: 'POST' })
                            .then(() => {
                                closeModal('manage-group-modal');
                                loadGroups();
                                showFeedback('Grupo eliminado');
                            })
                            .catch(() => {});
                    },
                    okText: 'Eliminar',
                    cancelText: 'Cancelar'
                });
            });
        }

        loadGroups();

        // ------- Chat Personal -------
        const personalChatsListRow = document.getElementById('personal-chats-list-row');
        const userSearchInput = document.getElementById('user-search-input');
        const userSearchBtn = document.getElementById('user-search-btn');
        const userListDropdown = document.getElementById('user-list-dropdown');
        const personalChatHeader = document.getElementById('personal-chat-header');
        const personalMessages = document.getElementById('messages-personal');
        const personalInput = document.getElementById('personal-chat-input');
        const personalSendBtn = document.getElementById('personal-chat-send');

        let selectedUserId = null;
        let selectedUserName = "";

        createEmojiPicker('emoji-picker-personal-popup', 'emoji-btn-personal', '#personal-chat-input');


        function loadPersonalChatsList(selectedId = null) {
            apiFetch('/chat/personal/last-chats')
                .then(chats => {
                    personalChatsListRow.innerHTML = '';
                    if(!chats.length){
                        personalChatsListRow.innerHTML = '<div style="padding:.3rem 1rem;color:#888;">Sin conversaciones personales.</div>';
                        return;
                    }
                    chats.forEach(u => {
                        personalChatsListRow.innerHTML += `
                            <div style="display:inline-flex;align-items:center;">
                                <button class="personal-chat-chip${selectedId == u.id ? ' active' : ''}"
                                    data-id="${u.id}" data-name="${u.name}">
                                    <i class="fas fa-user"></i> ${u.name}
                                </button>
                                <button class="delete-chat-btn" title="Eliminar chat" data-id="${u.id}" style="margin-left:4px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                    });
                })
                .catch(() => {});
        }

        if (personalChatsListRow) {
            personalChatsListRow.addEventListener('click', function(e){
                const deleteBtn = e.target.closest('.delete-chat-btn');
                if(deleteBtn && personalChatsListRow.contains(deleteBtn)){
                    const userId = deleteBtn.getAttribute('data-id');
                    showFeedback('¿Seguro que deseas eliminar la conversación?', {
                        closeOnly: false,
                        okText: 'Eliminar',
                        cancelText: 'Cancelar',
                        onOk: function() {
                            apiFetch(`/chat/personal/${userId}/delete`, { method: 'POST' })
                                .then(() => {
                                    if(selectedUserId == userId){
                                        selectedUserId = null;
                                        selectedUserName = "";
                                        personalChatHeader.innerHTML = '';
                                        personalMessages.innerHTML = '';
                                        userSearchInput.value = '';
                                    }
                                    loadPersonalChatsList();
                                    showFeedback('Conversación eliminada');
                                })
                                .catch(() => {});
                        }
                    });
                    e.stopPropagation();
                    return;
                }

                const chip = e.target.closest('.personal-chat-chip');
                if(chip && personalChatsListRow.contains(chip)){
                    selectedUserId = chip.getAttribute('data-id');
                    selectedUserName = chip.getAttribute('data-name');
                    userSearchInput.value = selectedUserName;
                    personalChatHeader.innerHTML = `<i class="fas fa-user-circle"></i> Conversando con <span style="color:var(--primary-color);">${selectedUserName}</span>`;
                    loadPersonalMessages();
                    loadPersonalChatsList(selectedUserId);
                    userListDropdown.style.display = 'none';
                }
            });
        }

        if (userSearchInput) {
            userSearchInput.addEventListener('input', function() {
                let q = this.value.trim();
                if (q.length < 2) {
                    userListDropdown.style.display = 'none';
                    selectedUserId = null;
                    return;
                }
                apiFetch(`/chat/personal/search?q=${encodeURIComponent(q)}`)
                    .then(users => {
                        if (!users.length) {
                            userListDropdown.style.display = 'none';
                            selectedUserId = null;
                            return;
                        }
                        userListDropdown.innerHTML = '';
                        users.forEach(u => {
                            userListDropdown.innerHTML += `<div class="user-list-item" data-id="${u.id}" data-name="${u.name}"><i class="fas fa-user"></i> ${u.name}</div>`;
                        });
                        userListDropdown.style.display = 'block';
                        userListDropdown.style.left = userSearchInput.offsetLeft + "px";
                        userListDropdown.style.top = (userSearchInput.offsetTop + userSearchInput.offsetHeight + 8) + "px";
                    })
                    .catch(() => {});
            });
        }

        if (userListDropdown) {
            userListDropdown.addEventListener('click', function(e) {
                if (e.target.classList.contains('user-list-item')) {
                    selectedUserId = e.target.getAttribute('data-id');
                    selectedUserName = e.target.getAttribute('data-name');
                    userListDropdown.style.display = 'none';
                    userSearchInput.value = selectedUserName;
                    personalChatHeader.innerHTML = `<i class="fas fa-user-circle"></i> Conversando con <span style="color:var(--primary-color);">${selectedUserName}</span>`;
                    loadPersonalMessages();
                    loadPersonalChatsList(selectedUserId);
                }
            });
        }

        if (userSearchBtn) {
            userSearchBtn.addEventListener('click', function() {
                if(selectedUserId) {
                    loadPersonalMessages();
                } else {
                    showFeedback('Selecciona un usuario válido antes de conversar.');
                }
            });
        }

        function loadPersonalMessages() {
            if (!selectedUserId) return;
            apiFetch(`/chat/personal/${selectedUserId}/messages`)
                .then(data => {
                    personalMessages.innerHTML = '';
                    data.forEach(msg => {
                        let isMine = msg.user.id === authId;
                        personalMessages.innerHTML += `
                            <div class="chat-message-row ${isMine ? 'mine-row' : ''}">
                                ${!isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                                <div class="chat-message-bubble ${isMine ? 'mine' : ''}">
                                    <div class="chat-message-meta">
                                        <span class="chat-message-name">${msg.user.name}</span>
                                        <span class="chat-message-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                    </div>
                                    <div>${msg.message}</div>
                                </div>
                                ${isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                            </div>
                        `;
                    });
                    personalMessages.scrollTop = personalMessages.scrollHeight;
                })
                .catch(() => {});
        }

        function sendPersonalMessage() {
            let text = personalInput.value.trim();
            if (!text) return;
            if (!selectedUserId) {
                showFeedback('Selecciona un usuario válido antes de enviar el mensaje.');
                return;
            }
            personalSendBtn.disabled = true;
            apiFetch(`/chat/personal/${selectedUserId}/send`, { method: 'POST', body: JSON.stringify({ message: text }) })
                .then(() => {
                    personalInput.value = '';
                    loadPersonalMessages();
                    loadPersonalChatsList(selectedUserId);
                })
                .finally(() => personalSendBtn.disabled = false);
        }

        if (personalSendBtn) personalSendBtn.addEventListener('click', sendPersonalMessage);
        if (personalInput) personalInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') sendPersonalMessage(); });

        const personalTabBtn = document.getElementById('tab-btn-personal');
        if (personalTabBtn) {
            personalTabBtn.addEventListener('click', function(){
                loadPersonalChatsList(selectedUserId);
                userListDropdown.style.display = 'none';
                if (!selectedUserId) {
                    personalChatHeader.innerHTML = '';
                    personalMessages.innerHTML = '';
                    if (userSearchInput) userSearchInput.value = '';
                } else {
                    personalChatHeader.innerHTML = `<i class="fas fa-user-circle"></i> Conversando con <span style="color:var(--primary-color);">${selectedUserName}</span>`;
                    loadPersonalMessages();
                    if (userSearchInput) userSearchInput.value = selectedUserName;
                }
            });
        }

        loadPersonalChatsList();
    });
})();
