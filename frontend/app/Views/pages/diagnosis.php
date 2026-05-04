<?php

declare(strict_types=1);

$words ??= [
    ['ru' => 'мама', 'kk' => 'ана', 'en' => 'mother'],
    ['ru' => 'солнце', 'kk' => 'күн', 'en' => 'sun'],
    ['ru' => 'рыба', 'kk' => 'балық', 'en' => 'fish'],
    ['ru' => 'цветок', 'kk' => 'гүл', 'en' => 'flower'],
    ['ru' => 'шарик', 'kk' => 'шар', 'en' => 'ball'],
    ['ru' => 'лампа', 'kk' => 'шам', 'en' => 'lamp'],
    ['ru' => 'жук', 'kk' => 'қоңыз', 'en' => 'beetle'],
    ['ru' => 'чашка', 'kk' => 'кесе', 'en' => 'cup'],
];

$currentLang ??= function_exists('app_locale') ? app_locale() : 'ru';

require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-4xl mx-auto space-y-8" id="diagnosisApp">
    <div class="text-center space-y-3">
        <h1 class="text-4xl font-bold text-gray-800"><?= e(tr('diagnosis_page.title', 'AI Диагностика речи')) ?></h1>
        <p class="text-lg text-gray-600">
            <?= e(tr('diagnosis_page.subtitle', 'Повторяйте слова четко и спокойно. AI анализирует произношение в реальном времени')) ?>
        </p>
    </div>

    <div class="border-0 shadow-lg gradient-card-beige rounded-2xl p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= e(tr('diagnosis_page.child_name', 'Имя ребёнка')) ?></label>
                <input id="childName" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/40" placeholder="<?= e(tr('diagnosis_page.child_name_placeholder', 'Например: Анар')) ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= e(tr('diagnosis_page.child_age', 'Возраст')) ?></label>
                <input id="childAge" type="number" min="2" max="16" class="w-full px-4 py-3 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/40" placeholder="<?= e(tr('diagnosis_page.child_age_placeholder', 'Например: 6')) ?>">
            </div>
        </div>
        <p class="text-sm text-gray-500"><?= e(tr('diagnosis_page.child_help', 'Эти данные нужны только для сохранения диагностики и отображения результата в панели логопеда.')) ?></p>
    </div>

    <div class="border-0 shadow-lg rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #F0F9FF, #ffffff);">
        <div class="p-8 md:p-10 space-y-8">
            <div class="flex items-center justify-center gap-3 text-sm font-medium text-gray-600">
                <span id="statusDot" class="w-3 h-3 rounded-full bg-green-500"></span>
                <span id="statusText"><?= e(tr('diagnosis_page.ready', 'Готов к записи')) ?></span>
            </div>

            <div class="text-center space-y-4">
                <p class="text-sm uppercase tracking-widest text-gray-500"><?= e(tr('diagnosis_page.repeat_word', 'Повторите слово')) ?></p>
                <div class="flex items-center justify-center gap-3">
                    <h2 id="currentWord" class="text-6xl md:text-7xl font-bold text-gray-800 transition-all duration-300">мама</h2>
                    <button type="button" id="playSoundBtn" class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-blue-50 text-blue-soft transition-colors" aria-label="Play word">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                        </svg>
                    </button>
                </div>
                <p id="currentWordAlt" class="text-2xl text-gray-500">(ана)</p>
            </div>

            <div class="max-w-xs mx-auto flex items-end justify-center gap-1 h-16">
                <?php for ($i = 0; $i < 24; $i++): ?>
                    <span class="waveform-bar w-2 rounded-full bg-gray-200 transition-all duration-150" style="height:20%"></span>
                <?php endfor; ?>
            </div>

            <div class="flex justify-center">
                <button id="recordBtn" type="button"  class="w-32 h-32 rounded-full flex items-center justify-center shadow-2xl gradient-record-btn transition-all duration-300 transform hover:scale-105 active:scale-95">
                    <svg id="micIcon" class="w-14 h-14 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                        <line x1="12" x2="12" y1="19" y2="22"></line>
                    </svg>
                    <svg id="stopIcon" class="w-14 h-14 text-white hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="6" y="6" width="12" height="12" rx="2"></rect>
                    </svg>
                </button>
            </div>

            <p id="hintText" class="text-center text-sm text-gray-500"><?= e(tr('diagnosis_page.hint_ready', 'Нажмите на микрофон чтобы начать запись')) ?></p>

            <div id="micError" class="hidden max-w-xl mx-auto">
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 text-center">
                    <span id="micErrorText"></span>
                </div>
            </div>

            <div id="wordResult" class="hidden text-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-mint/20 text-mint font-medium text-sm">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                    <span id="wordResultText"></span>
                </span>
            </div>

            <div class="flex justify-center">
                <button type="button" id="skipWordBtn" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-gray-200 rounded-full hover:bg-gray-50 transition-colors text-gray-700">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                    </svg>
                    <?= e(tr('diagnosis_page.skip', 'Пропустить слово')) ?>
                </button>
            </div>
        </div>
    </div>

    <div class="border-0 shadow-lg gradient-card-beige rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-soft" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18h6"></path>
                <path d="M10 22h4"></path>
                <path d="M12 2a7 7 0 0 0-4 12.75c.63.44 1 1.15 1 1.92V17h6v-.33c0-.77.37-1.48 1-1.92A7 7 0 0 0 12 2Z"></path>
            </svg>
            <span><?= e(tr('diagnosis_page.tips_title', 'Советы для точной диагностики')) ?></span>
        </h3>
        <ul class="space-y-2 text-sm text-gray-600">
            <?php foreach ([
                tr('diagnosis_page.tip1', 'Находитесь в тихом помещении'),
                tr('diagnosis_page.tip2', 'Держите микрофон на расстоянии 15-20 см'),
                tr('diagnosis_page.tip3', 'Говорите естественным темпом'),
                tr('diagnosis_page.tip4', 'Если ребенок устал, сделайте перерыв'),
            ] as $tip): ?>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 rounded-full bg-mint flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"></path>
                        </svg>
                    </span>
                    <span><?= e($tip) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php
$wJson = json_encode($words, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$lJson = json_encode($currentLang, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$apiStart = json_encode(api_url('diagnosis/start'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$apiAnalyze = json_encode(api_url('diagnosis/analyze'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$apiComplete = json_encode(api_url('diagnosis/complete'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$extraScripts = <<<JS
<script>
const W = {$wJson};
const LANG = {$lJson};
const API = {
    start: {$apiStart},
    analyze: {$apiAnalyze},
    complete: {$apiComplete}
};

let wi = 0;
let rec = false;
let busy = false;
let sesOk = false;
let mr = null;
let ms = null;
let ch = [];
let wt = null;
let ac = null;
let an = null;

const \$ = (id) => document.getElementById(id);
const bars = document.querySelectorAll('.waveform-bar');

function show() {
    const w = W[wi];
    if (!w) return;

    $('currentWord').textContent = w.ru || '';
    $('currentWord').style.opacity = '0';
    $('currentWord').style.transform = 'scale(0.8)';

    requestAnimationFrame(() => {
        $('currentWord').style.opacity = '1';
        $('currentWord').style.transform = 'scale(1)';
    });

    const alt = LANG === 'kk' ? (w.kk || w.ru || '') : (LANG === 'en' ? (w.en || w.ru || '') : (w.kk || w.ru || ''));
    $('currentWordAlt').textContent = '(' + alt + ')';
    $('wordResult').classList.add('hidden');
}

function stat(mode, title, hint) {
    $('statusText').textContent = title;
    $('hintText').textContent = hint;

    if (mode === 'ready') {
        $('statusDot').className = 'w-3 h-3 rounded-full bg-green-500';
        $('recordBtn').className = 'w-32 h-32 rounded-full flex items-center justify-center shadow-2xl gradient-record-btn transition-all duration-300 transform hover:scale-105 active:scale-95';
        $('micIcon').classList.remove('hidden');
        $('stopIcon').classList.add('hidden');
    }

    if (mode === 'rec') {
        $('statusDot').className = 'w-3 h-3 rounded-full bg-red-500 animate-pulse';
        $('recordBtn').className = 'w-32 h-32 rounded-full flex items-center justify-center shadow-2xl gradient-record-btn-active animate-pulse-soft transition-all duration-300';
        $('micIcon').classList.add('hidden');
        $('stopIcon').classList.remove('hidden');
    }

    if (mode === 'ai') {
        $('statusDot').className = 'w-3 h-3 rounded-full bg-yellow-500 animate-pulse';
        $('recordBtn').className = 'w-32 h-32 rounded-full flex items-center justify-center shadow-2xl gradient-record-btn opacity-50 transition-all duration-300';
        $('micIcon').classList.remove('hidden');
        $('stopIcon').classList.add('hidden');
    }
}

function err(msg) {
    $('micErrorText').textContent = msg;
    $('micError').classList.remove('hidden');
    setTimeout(() => $('micError').classList.add('hidden'), 8000);
}

function stopWave() {
    clearInterval(wt);
    bars.forEach((b) => {
        b.style.height = '20%';
        b.style.background = '#E5E7EB';
    });
}

function realWave(stream) {
    try {
        ac = new (window.AudioContext || window.webkitAudioContext)();
        an = ac.createAnalyser();
        an.fftSize = 64;
        ac.createMediaStreamSource(stream).connect(an);
        const d = new Uint8Array(an.frequencyBinCount);

        (function draw() {
            if (!rec) return;
            an.getByteFrequencyData(d);
            bars.forEach((b, i) => {
                const v = d[i % d.length] || 0;
                b.style.height = Math.max(8, (v / 255) * 100) + '%';
                b.style.background = 'linear-gradient(to top,#7FB3D5,#A8E6CF)';
            });
            requestAnimationFrame(draw);
        })();
    } catch (e) {
        wt = setInterval(() => {
            bars.forEach((b) => {
                b.style.height = Math.max(15, Math.random() * 100) + '%';
                b.style.background = 'linear-gradient(to top,#7FB3D5,#A8E6CF)';
            });
        }, 80);
    }
}

async function ses() {
    if (sesOk) return;
    const f = new FormData();
    f.append('child_name', $('childName').value.trim());
    f.append('child_age', $('childAge').value.trim());

    try {
        await fetch(API.start, { method: 'POST', body: f });
    } catch (e) {
    }

    sesOk = true;
}

async function handleRecord() {
    if (busy) return;

    if (rec && mr && mr.state === 'recording') {
        mr.stop();
        return;
    }

    if (!window.isSecureContext) {
        err('Страница должна открываться по HTTPS. Иначе браузер блокирует микрофон.');
        return;
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        err('Браузер не поддерживает запись. Используйте Chrome, Edge, Firefox или Safari.');
        return;
    }

    if (!window.MediaRecorder) {
        err('MediaRecorder не поддерживается в этом браузере.');
        return;
    }

    await ses();

    try {
        ms = await navigator.mediaDevices.getUserMedia({
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true
            }
        });
    } catch (e) {
        if (e && e.name === 'NotAllowedError') {
            err('Доступ к микрофону запрещён. Разрешите микрофон в настройках браузера.');
        } else if (e && e.name === 'NotFoundError') {
            err('Микрофон не найден. Подключите устройство и обновите страницу.');
        } else {
            err('Ошибка микрофона: ' + (e && e.message ? e.message : 'неизвестная ошибка'));
        }
        return;
    }

    ch = [];

    let mt = '';
    if (MediaRecorder.isTypeSupported('audio/webm;codecs=opus')) {
        mt = 'audio/webm;codecs=opus';
    } else if (MediaRecorder.isTypeSupported('audio/webm')) {
        mt = 'audio/webm';
    } else if (MediaRecorder.isTypeSupported('audio/mp4')) {
        mt = 'audio/mp4';
    }

    try {
        mr = mt ? new MediaRecorder(ms, { mimeType: mt }) : new MediaRecorder(ms);
    } catch (e) {
        err('Не удалось запустить запись: ' + (e && e.message ? e.message : 'ошибка MediaRecorder'));
        if (ms) ms.getTracks().forEach((t) => t.stop());
        return;
    }

    mr.ondataavailable = (e) => {
        if (e.data && e.data.size > 0) ch.push(e.data);
    };

    mr.onerror = (e) => {
        err('Ошибка записи аудио.');
        rec = false;
        busy = false;
        stopWave();
        if (ms) ms.getTracks().forEach((t) => t.stop());
        stat('ready', 'Готов к записи', 'Нажмите на микрофон чтобы начать запись');
    };

    mr.onstop = upload;

    try {
        mr.start();
    } catch (e) {
        err('Не удалось начать запись.');
        if (ms) ms.getTracks().forEach((t) => t.stop());
        return;
    }

    rec = true;
    stat('rec', 'Идет запись...', 'Говорите четко. Нажмите ещё раз чтобы остановить.');
    realWave(ms);

    setTimeout(() => {
        if (mr && mr.state === 'recording') mr.stop();
    }, 4000);
}

async function upload() {
    rec = false;
    busy = true;
    stopWave();

    if (ac) {
        try { ac.close(); } catch (e) {}
    }
    ac = null;

    stat('ai', 'AI анализирует...', 'Анализируем произношение...');

    const mime = (mr && mr.mimeType) ? mr.mimeType : 'audio/webm';
    const ext = mime.includes('mp4') ? 'mp4' : (mime.includes('ogg') ? 'ogg' : 'webm');
    const blob = new Blob(ch, { type: mime });

    if (!blob || blob.size === 0) {
        err('Запись получилась пустой. Попробуйте ещё раз.');
        busy = false;
        stat('ready', 'Готов к записи', 'Нажмите на микрофон чтобы начать запись');
        if (ms) ms.getTracks().forEach((t) => t.stop());
        return;
    }

    const fd = new FormData();
    fd.append('audio', blob, 'speech.' + ext);
    fd.append('word', W[wi].ru);

    try {
        const r = await fetch(API.analyze, { method: 'POST', body: fd });
        const d = await r.json();

        if (!d.ok) {
            throw new Error(d.message || 'Ошибка анализа');
        }

        await done(d.analysis || {});
    } catch (e) {
        err('Ошибка: ' + (e && e.message ? e.message : 'неизвестная ошибка'));
        busy = false;
        stat('ready', 'Готов к записи', 'Попробуйте ещё раз');
    } finally {
        if (ms) ms.getTracks().forEach((t) => t.stop());
    }
}

async function done(a) {
    busy = false;
    const sc = a.score || a.overall_score || 0;

    $('wordResultText').textContent = W[wi].ru + ': ' + sc + '/100';
    $('wordResult').classList.remove('hidden');

    if (wi < W.length - 1) {
        stat('ready', 'Готов к записи', 'Переходим к следующему слову.');
        setTimeout(() => {
            wi++;
            show();
        }, 1200);
        return;
    }

    stat('ai', 'Формируем отчёт...', 'Собираем AI-диагностику...');

    try {
        const r = await fetch(API.complete, { method: 'POST' });
        const d = await r.json();
        window.location.href = d.redirect || '/results.php';
    } catch (e) {
        err('Ошибка завершения: ' + (e && e.message ? e.message : 'неизвестная ошибка'));
        busy = false;
        stat('ready', 'Готов к записи', 'Попробуйте завершить ещё раз');
    }
}

function skipWord() {
    if (rec || busy) return;

    if (wi < W.length - 1) {
        wi++;
        show();
        return;
    }

    fetch(API.complete, { method: 'POST' })
        .then((r) => r.json())
        .then((d) => {
            window.location.href = d.redirect || '/results.php';
        })
        .catch((e) => {
            err('Ошибка завершения: ' + (e && e.message ? e.message : 'неизвестная ошибка'));
        });
}

function playSound() {
    if (!('speechSynthesis' in window)) return;
    const u = new SpeechSynthesisUtterance(W[wi].ru);
    u.lang = 'ru-RU';
    u.rate = 0.8;
    speechSynthesis.cancel();
    speechSynthesis.speak(u);
}

document.addEventListener('DOMContentLoaded', () => {
    show();
    document.getElementById('recordBtn')?.addEventListener('click', handleRecord);
    document.getElementById('playSoundBtn')?.addEventListener('click', playSound);
    document.getElementById('skipWordBtn')?.addEventListener('click', skipWord);
});
</script>
JS;

require __DIR__ . '/../layouts/footer.php';
?>