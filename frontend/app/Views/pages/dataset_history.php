<?php

declare(strict_types=1);

$fastApiBase = rtrim((string) env('FASTAPI_API_URL', 'http://localhost:8000/api'), '/');
$fastApiJson = json_encode($fastApiBase, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-6xl mx-auto space-y-8" id="datasetHistoryApp">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-soft text-sm font-semibold border border-blue-100">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l3 2"/></svg>
                <?= e(tr('dataset_history', 'История датасета')) ?>
            </div>
            <h1 class="text-4xl font-bold text-gray-800"><?= e(tr('dataset_history', 'История датасета')) ?></h1>
            <p class="text-gray-600 max-w-2xl">Все записи сгруппированы по ребёнку и слову: внутри одного блока хранится максимум 3 голосовые попытки.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="/dataset.php" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-full shadow-sm hover:bg-gray-50 transition-colors no-underline">
                <svg class="w-4 h-4 text-blue-soft" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
                Записать датасет
            </a>
            <button type="button" id="refreshHistoryBtn" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-gray-900 rounded-full shadow-sm hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 3v6h-6"/></svg>
                Обновить
            </button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="bg-white/80 border border-white/60 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-semibold text-gray-500">Записей</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" id="recordingsCount">0</p>
        </div>
        <div class="bg-white/80 border border-white/60 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-semibold text-gray-500">Детей</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" id="childrenCount">0</p>
        </div>
        <div class="bg-white/80 border border-white/60 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-semibold text-gray-500">Аудио</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" id="storageSize">0 KB</p>
        </div>
    </div>

    <div class="bg-white/85 backdrop-blur-md border border-white/60 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="relative flex-1 max-w-xl">
                <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input id="historySearch" type="search" class="w-full pl-11 pr-4 py-3 bg-gray-50/70 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-soft/15 focus:bg-white" placeholder="Поиск: имя, ID, слово, звук, тип нарушения">
            </div>
            <div class="text-sm text-gray-500" id="historyStatus">Загрузка...</div>
        </div>

        <div id="historyError" class="hidden mx-5 mt-5 px-4 py-3 rounded-2xl bg-red-50 text-red-600 border border-red-100 text-sm"></div>
        <div id="historyEmpty" class="hidden p-10 text-center text-gray-500">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 mb-4">
                <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
            </div>
            <p class="font-semibold text-gray-700">Записей пока нет</p>
        </div>

        <div id="historyGroups" class="divide-y divide-gray-100"></div>
    </div>
</div>

<script>
const FASTAPI = <?= $fastApiJson ?>;
const HISTORY_API = FASTAPI + '/audio/history';

const state = {
    items: [],
    loading: false,
    openGroups: new Set(),
};

const $history = (id) => document.getElementById(id);

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[char]));
}

function formatBytes(bytes) {
    const size = Number(bytes || 0);
    if (size >= 1024 * 1024) return (size / 1024 / 1024).toFixed(1) + ' MB';
    return Math.max(0, Math.round(size / 1024)) + ' KB';
}

function recordingWord(count) {
    const mod10 = count % 10;
    const mod100 = count % 100;
    if (mod10 === 1 && mod100 !== 11) return 'запись';
    if ([2, 3, 4].includes(mod10) && ![12, 13, 14].includes(mod100)) return 'записи';
    return 'записей';
}

function formatDate(value) {
    if (!value) return '-';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleString('ru-RU', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function audioUrl(item) {
    return FASTAPI + item.audio_url;
}

function ownerMeta(item) {
    const parts = [];
    if (item.child_external_id) parts.push('ID: ' + item.child_external_id);
    if (item.child_age) parts.push(item.child_age + ' лет');
    if (item.child_gender) parts.push('Пол: ' + genderLabel(item.child_gender));
    if (item.disorder_type) parts.push(item.disorder_type);
    return parts.length ? parts.join(' · ') : 'Данные владельца не заполнены';
}

function genderLabel(value) {
    const normalized = String(value || '').toLowerCase();
    if (normalized === 'male') return 'мужской';
    if (normalized === 'female') return 'женский';
    return value;
}

function groupKey(item) {
    return 'child:' + item.child_id + ':word:' + item.word_id;
}

function groupRecordings(items) {
    const map = new Map();
    items.forEach((item) => {
        const key = groupKey(item);
        if (!map.has(key)) {
            map.set(key, { key, owner: item, word: item.word, targetSound: item.target_sound, items: [], bytes: 0, latestAt: 0 });
        }
        const group = map.get(key);
        group.items.push(item);
        group.bytes += Number(item.file_size || 0);
        group.latestAt = Math.max(group.latestAt, new Date(item.created_at).getTime() || 0);
    });
    return [...map.values()].map((group) => {
        group.items.sort((a, b) => {
            const byAttempt = String(a.attempt_number).localeCompare(String(b.attempt_number), 'ru', { numeric: true });
            if (byAttempt !== 0) return byAttempt;
            return new Date(a.created_at).getTime() - new Date(b.created_at).getTime();
        });
        return group;
    }).sort((a, b) => b.latestAt - a.latestAt);
}

function analysisBadge(item) {
    const analysis = item.analysis_result;
    if (!analysis) return '<span class="text-xs text-gray-400">без анализа</span>';
    const risk = analysis.risk_level || '-';
    const color = risk === 'жоғары' ? 'bg-red-100 text-red-700' : (risk === 'орташа' ? 'bg-orange-soft/30 text-orange-700' : 'bg-mint/30 text-mint-dark');
    return '<span class="px-2.5 py-1 rounded-full text-xs font-bold ' + color + '">' + escapeHtml(risk) + ' · ' + escapeHtml(analysis.accuracy || 0) + '%</span>';
}

function recordingLabel(item) {
    const analysis = item.analysis_result;
    const recognized = analysis ? '<div class="text-xs text-gray-500 mt-1">Распознано: ' + escapeHtml(analysis.recognized_word || '-') + '</div>' : '';
    return `
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">${escapeHtml(item.target_sound || '-')}</span>
            <span class="font-bold text-gray-900">${escapeHtml(item.word)}</span>
            <span class="text-sm text-gray-500">${escapeHtml(item.attempt_number)}</span>
            ${analysisBadge(item)}
        </div>
        ${recognized}
    `;
}

function renderTotals(payload) {
    $history('recordingsCount').textContent = payload.totals?.recordings ?? 0;
    $history('childrenCount').textContent = payload.totals?.children ?? 0;
    $history('storageSize').textContent = formatBytes(payload.totals?.bytes ?? 0);
}

function renderRecording(item) {
    return `
        <article class="grid gap-4 p-4 bg-gray-50/60 border border-gray-100 rounded-2xl lg:grid-cols-[1.15fr_1.35fr_0.75fr_auto] lg:items-center">
            <div>
                ${recordingLabel(item)}
                <div class="text-xs text-gray-500 mt-2">Дата: ${escapeHtml(formatDate(item.created_at))}</div>
            </div>
            <audio controls preload="none" class="w-full" src="${escapeHtml(audioUrl(item))}"></audio>
            <div class="text-sm text-gray-600">
                <div class="font-semibold text-gray-800">${escapeHtml(formatBytes(item.file_size))}</div>
                <a href="${escapeHtml(audioUrl(item))}" target="_blank" class="text-xs font-semibold text-blue-soft hover:underline">Открыть файл</a>
            </div>
            <button type="button" data-delete-audio="${escapeHtml(item.id)}" class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-red-600 bg-red-50 border border-red-100 rounded-full hover:bg-red-100 transition-colors">
                <svg class="w-4 h-4 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                Удалить
            </button>
        </article>
    `;
}

function renderGroups(items) {
    const groups = groupRecordings(items);
    const html = groups.map((group) => {
        const owner = group.owner;
        const isOpen = state.openGroups.has(group.key);
        const lastDate = group.latestAt;
        return `
            <section class="bg-white">
                <button type="button" data-group-toggle="${escapeHtml(group.key)}" class="w-full px-5 py-5 text-left hover:bg-blue-50/35 transition-colors">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex w-9 h-9 items-center justify-center rounded-2xl bg-blue-50 text-blue-soft">
                                    <svg class="w-5 h-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-900 truncate">${escapeHtml(owner.child_name || 'Ребенок')}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">${escapeHtml(ownerMeta(owner))}</p>
                                    <p class="text-sm text-gray-700 mt-2">
                                        <span class="font-semibold">Слово:</span> ${escapeHtml(group.word)}
                                        ${group.targetSound ? '<span class="ml-2 inline-flex px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">звук ' + escapeHtml(group.targetSound) + '</span>' : ''}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 font-semibold">${group.items.length}/3 ${recordingWord(group.items.length)}</span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 font-semibold">${escapeHtml(formatBytes(group.bytes))}</span>
                            <span class="text-gray-500">${escapeHtml(formatDate(lastDate))}</span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform ${isOpen ? 'rotate-180' : ''}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>
                </button>
                <div class="${isOpen ? '' : 'hidden'} px-5 pb-5 space-y-3">
                    <div class="text-xs text-gray-400">Backend child_id: ${escapeHtml(owner.child_id)} · word_id: ${escapeHtml(owner.word_id)}</div>
                    ${group.items.map(renderRecording).join('')}
                </div>
            </section>
        `;
    }).join('');
    $history('historyGroups').innerHTML = html;
    $history('historyStatus').textContent = items.length ? 'Показано: ' + items.length + ' · групп: ' + groups.length : 'Нет записей';
}

function render(payload) {
    const items = payload.items || [];
    state.items = items;
    renderTotals(payload);
    renderGroups(items);
    $history('historyEmpty').classList.toggle('hidden', items.length !== 0);
}

async function loadHistory(options = {}) {
    if (state.loading && !options.force) return;
    state.loading = true;
    $history('historyStatus').textContent = 'Загрузка...';
    $history('historyError').classList.add('hidden');

    const search = $history('historySearch').value.trim();
    const url = new URL(HISTORY_API);
    url.searchParams.set('limit', '500');
    if (search) url.searchParams.set('search', search);

    try {
        const response = await fetch(url);
        const payload = await response.json();
        if (!response.ok || !payload.ok) {
            throw new Error(payload.detail || 'FastAPI backend недоступен');
        }
        render(payload);
    } catch (error) {
        $history('historyError').textContent = 'Не удалось загрузить историю: ' + (error && error.message ? error.message : 'ошибка сети');
        $history('historyError').classList.remove('hidden');
        $history('historyStatus').textContent = 'Ошибка загрузки';
    } finally {
        state.loading = false;
    }
}

async function deleteAudio(audioId) {
    const item = state.items.find((entry) => Number(entry.id) === Number(audioId));
    const label = item ? `${item.child_name || 'Ребенок'} · ${item.word} · ${item.attempt_number}` : 'эту запись';
    if (!window.confirm('Удалить ' + label + '? Файл аудио и анализ будут удалены.')) {
        return;
    }

    try {
        const response = await fetch(FASTAPI + '/audio/' + encodeURIComponent(audioId), { method: 'DELETE' });
        const payload = await response.json();
        if (!response.ok || !payload.ok) {
            throw new Error(payload.detail || 'Не удалось удалить запись');
        }
        await loadHistory({ force: true });
    } catch (error) {
        $history('historyError').textContent = 'Не удалось удалить запись: ' + (error && error.message ? error.message : 'ошибка сети');
        $history('historyError').classList.remove('hidden');
    }
}

let searchTimer = null;
document.addEventListener('DOMContentLoaded', () => {
    loadHistory();
    $history('refreshHistoryBtn')?.addEventListener('click', () => loadHistory({ force: true }));
    $history('historySearch')?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadHistory({ force: true }), 300);
    });
    $history('historyGroups')?.addEventListener('click', (event) => {
        const deleteButton = event.target.closest('[data-delete-audio]');
        if (deleteButton) {
            event.stopPropagation();
            deleteAudio(deleteButton.dataset.deleteAudio);
            return;
        }

        const groupButton = event.target.closest('[data-group-toggle]');
        if (!groupButton) return;
        const key = groupButton.dataset.groupToggle;
        if (state.openGroups.has(key)) {
            state.openGroups.delete(key);
        } else {
            state.openGroups.add(key);
        }
        renderGroups(state.items);
    });
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
