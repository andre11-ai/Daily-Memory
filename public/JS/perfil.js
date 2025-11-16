(function () {
    const DATA = window.__PERFIL_DATA__ || {};

    const PALETTE = ['#00c8a3','#45b6fe','#ff7a7a','#f59e0b','#8b5cf6','#38bdf8','#fb7185','#10b981','#6ee7b7','#a78bfa'];

    function initToggleScoresList() {
        const btnToggle = document.getElementById('toggle-scores-list');
        const fullList = document.getElementById('scores-list-full');
        const badges = document.getElementById('scores-badges');

        if (!btnToggle || !fullList || !badges) return;

        btnToggle.addEventListener('click', function () {
            const opened = fullList.style.display === 'block';
            fullList.style.display = opened ? 'none' : 'block';
            fullList.setAttribute('aria-hidden', opened ? 'true' : 'false');
            btnToggle.textContent = opened ? 'Ver lista' : 'Ocultar lista';
            badges.style.display = opened ? 'flex' : 'none';
        });
    }

    function buildDatasets(raw, palette) {
        if (!Array.isArray(raw)) return [];
        return raw.map(function (ds, idx) {
            return {
                label: ds.label,
                data: ds.data,
                borderColor: palette[idx % palette.length],
                backgroundColor: palette[idx % palette.length],
                tension: 0.22,
                pointRadius: 4,
                borderWidth: 2,
                spanGaps: true,
                fill: false
            };
        });
    }

    function drawLineChart(ctxEl, labels, datasets, optionsExtra) {
        if (!ctxEl || !labels || !datasets) return;
        if (typeof Chart === 'undefined') {
            setTimeout(function () {
                drawLineChart(ctxEl, labels, datasets, optionsExtra);
            }, 120);
            return;
        }

        return new Chart(ctxEl.getContext('2d'), {
            type: 'line',
            data: { labels: labels, datasets: datasets },
            options: Object.assign({
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                },
                interaction: { mode: 'nearest', intersect: false },
                scales: {
                    x: { display: true, title: { display: true, text: 'Fecha' }, ticks: { autoSkip: true } },
                    y: { display: true, title: { display: true, text: 'Score acumulado' }, beginAtZero: true }
                }
            }, optionsExtra || {})
        });
    }

    function initCharts() {
        const labels = DATA.chartLabels || [];
        const scoresDatasets = DATA.chartDatasets || [];
        const memDatasets = DATA.chartMemoryDatasets || [];
        const diffDatasets = DATA.chartDifficultyDatasets || [];

        if (labels.length && Array.isArray(scoresDatasets)) {
            const scoresEl = document.getElementById('scoresChart');
            const datasets = buildDatasets(scoresDatasets, PALETTE);
            if (scoresEl) {
                drawLineChart(scoresEl, labels, datasets, {
                    scales: {
                        x: { display: true, title: { display: true, text: 'Fecha' } },
                        y: { display: true, title: { display: true, text: 'Mejor score' }, beginAtZero: true }
                    },
                    plugins: { legend: { labels: { boxWidth: 12, padding: 8 } } }
                });
                const container = scoresEl.closest('.chart-container');
                if (container) container.style.height = '360px';
            }
        }

        if (labels.length && Array.isArray(memDatasets)) {
            const memEl = document.getElementById('chartMemory');
            const datasets = buildDatasets(memDatasets, PALETTE);
            if (memEl) {
                drawLineChart(memEl, labels, datasets);
                const container = memEl.closest('.chart-container');
                if (container) container.style.height = '320px';
            }
        }

        if (labels.length && Array.isArray(diffDatasets)) {
            const diffEl = document.getElementById('chartDifficulty');
            const datasets = buildDatasets(diffDatasets, PALETTE);
            if (diffEl) {
                drawLineChart(diffEl, labels, datasets);
                const container = diffEl.closest('.chart-container');
                if (container) container.style.height = '320px';
            }
        }
    }

    let lastFocusedElement = null;
    const modalBackdrop = document.getElementById('modal-backdrop'); // first occurrence
    const btnEdit = document.getElementById('btn-edit-profile');
    const btnCancel = document.getElementById('btn-cancel');
    const modalClose = document.getElementById('modal-close');
    const firstFocusSelector = '#name';

    function openModal() {
        if (!modalBackdrop) return;
        lastFocusedElement = document.activeElement;
        modalBackdrop.style.display = 'flex';
        modalBackdrop.setAttribute('aria-hidden', 'false');
        const first = document.querySelector(firstFocusSelector);
        if (first) first.focus();
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        if (!modalBackdrop) return;
        modalBackdrop.style.display = 'none';
        modalBackdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        const fileInput = document.getElementById('profile_image');
        if (fileInput) fileInput.value = '';
        if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') lastFocusedElement.focus();
    }

    function initModalEvents() {
        if (btnEdit) {
            btnEdit.addEventListener('click', function (e) { e.preventDefault(); openModal(); });
        }
        if (btnCancel) {
            btnCancel.addEventListener('click', function (e) { e.preventDefault(); closeModal(); });
        }
        if (modalClose) {
            modalClose.addEventListener('click', function (e) { e.preventDefault(); closeModal(); });
        }
        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', function (e) {
                if (e.target === modalBackdrop) closeModal();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modalBackdrop && modalBackdrop.style.display === 'flex') {
                closeModal();
            }
        });

        if (DATA.errorsAny) {
            setTimeout(openModal, 10);
        }
    }

    window.previewImage = function (event) {
        const file = event.target && event.target.files && event.target.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        const preview = document.getElementById('preview-avatar');
        const mainImg = document.getElementById('profile-avatar-img');
        if (preview) preview.src = url;
        if (mainImg) mainImg.src = url;
    };

    function initPasswordToggles() {
        const eyeSVG = '<svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>';
        const eyeOffSVG = '<svg class="icon-eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-6 0-10-7-10-7 .89-1.53 2.19-3.18 3.76-4.62M3 3l18 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.88 9.88A3 3 0 0 0 14.12 14.12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';

        document.querySelectorAll('.password-toggle').forEach(function (button) {
            if (button.innerHTML.trim() === '') button.innerHTML = eyeSVG;

            button.addEventListener('click', function (e) {
                e.preventDefault();
                const wrapper = button.closest('.input-icon-wrapper');
                if (!wrapper) return;
                const input = wrapper.querySelector('input');
                if (!input) return;

                const isPassword = input.type === 'password';
                if (isPassword) {
                    input.type = 'text';
                    button.setAttribute('aria-label', 'Ocultar contraseña');
                    button.setAttribute('aria-pressed', 'true');
                    button.innerHTML = eyeOffSVG;
                } else {
                    input.type = 'password';
                    button.setAttribute('aria-label', 'Mostrar contraseña');
                    button.setAttribute('aria-pressed', 'false');
                    button.innerHTML = eyeSVG;
                }
                input.focus();
            });
        });
    }

    function initFormValidation() {
        const form = document.getElementById('profile-edit-form');
        const modalError = document.getElementById('modal-error');
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        function showModalError(message) {
            if (!modalError) return alert(message); // fallback
            modalError.style.display = 'block';
            modalError.innerHTML = '<div>' + message + '</div>';
            modalError.classList.add('visible');
        }

        function clearModalError() {
            if (!modalError) return;
            modalError.style.display = 'none';
            modalError.textContent = '';
            modalError.classList.remove('visible');
        }

        if (!form) return;

        form.addEventListener('submit', function (e) {
            clearModalError();

            const pwd = password && password.value ? password.value.trim() : '';
            const pwdConfirm = passwordConfirmation && passwordConfirmation.value ? passwordConfirmation.value.trim() : '';

            if (pwd === '' && pwdConfirm === '') {
                return true;
            }

            if (pwd === '' || pwdConfirm === '') {
                e.preventDefault();
                showModalError('Ambos campos de contraseña deben llenarse para cambiar la contraseña.');
                return false;
            }

            if (pwd.length < 8) {
                e.preventDefault();
                showModalError('La contraseña debe tener al menos 8 caracteres.');
                return false;
            }

            if (pwd !== pwdConfirm) {
                e.preventDefault();
                showModalError('Las contraseñas no coinciden. Por favor revisa y vuelve a intentarlo.');
                return false;
            }

            return true;
        });

        [password, passwordConfirmation].forEach(function (input) {
            if (!input) return;
            input.addEventListener('input', clearModalError);
        });
    }

    function initLiveStats() {
        const statsUrl = DATA.statsUrl;
        if (!statsUrl) return;

        const elLevelValue = document.getElementById('level-value');
        const elProgressBar = document.getElementById('level-progress-bar');
        const elLevelMeta = document.getElementById('level-meta');
        const elTotalPoints = document.getElementById('total-points');

        async function fetchAndUpdateStats() {
            try {
                const res = await fetch(statsUrl, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return;
                const data = await res.json();
                if (!data) return;

                if (elTotalPoints && typeof data.scoreGeneral !== 'undefined') {
                    elTotalPoints.textContent = data.scoreGeneral;
                }

                if (data.levelInfo) {
                    if (elLevelValue) elLevelValue.textContent = data.levelInfo.level;
                    if (elLevelMeta) elLevelMeta.textContent = data.levelInfo.xp_into_level + ' / ' + data.levelInfo.xp_for_next + ' pts';

                    if (elProgressBar) {
                        const pct = Math.min(100, Math.max(0, data.levelInfo.progress || 0));
                        elProgressBar.style.width = pct + '%';
                        elProgressBar.setAttribute('aria-valuenow', data.levelInfo.xp_into_level);
                        elProgressBar.setAttribute('aria-valuemax', data.levelInfo.xp_for_next);
                    }
                }
            } catch (err) {
                console.error('Error fetching profile stats:', err);
            }
        }

        if (elTotalPoints && typeof DATA.scoreGeneral !== 'undefined') {
            elTotalPoints.textContent = DATA.scoreGeneral;
        }
        if (DATA.levelInfo) {
            if (document.getElementById('level-value')) document.getElementById('level-value').textContent = DATA.levelInfo.level;
            if (document.getElementById('level-meta')) document.getElementById('level-meta').textContent = (DATA.levelInfo.xp_into_level || 0) + ' / ' + (DATA.levelInfo.xp_for_next || 0) + ' pts';
            if (document.getElementById('level-progress-bar')) {
                const bar = document.getElementById('level-progress-bar');
                const pct = Math.min(100, Math.max(0, (DATA.levelInfo.progress || 0)));
                bar.style.width = pct + '%';
                bar.setAttribute('aria-valuenow', DATA.levelInfo.xp_into_level || 0);
                bar.setAttribute('aria-valuemax', DATA.levelInfo.xp_for_next || 0);
            }
        }

        fetchAndUpdateStats();
        const POLL_INTERVAL_MS = 10000;
        setInterval(fetchAndUpdateStats, POLL_INTERVAL_MS);
    }

    function init() {
        initToggleScoresList();
        initCharts();
        initModalEvents();
        initPasswordToggles();
        initFormValidation();
        initLiveStats();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
