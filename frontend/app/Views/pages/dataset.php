<?php

declare(strict_types=1);

$words = [
    ['ru' => 'мама', 'kk' => 'ана', 'en' => 'mother', 'image' => '/public/assets/img/preview_img_6215_661.jpg'],
    ['phoneme' => 'Р', 'ru' => 'ракета', 'kk' => 'ракета', 'en' => 'rocket', 'image' => '/public/assets/img/rocket.png'],
    ['phoneme' => 'Р', 'ru' => 'лев', 'kk' => 'арыстан', 'en' => 'lion', 'image' => '/public/assets/img/lion.png'],
    ['phoneme' => 'Л', 'ru' => 'козлёнок', 'kk' => 'лақ', 'en' => 'kid', 'image' => '/public/assets/img/kid.png'],
    ['phoneme' => 'Л', 'ru' => 'цветок', 'kk' => 'гүл', 'en' => 'flower', 'image' => '/public/assets/img/flower.png'],
    ['phoneme' => 'С', 'ru' => 'часы', 'kk' => 'сағат', 'en' => 'clock', 'image' => '/public/assets/img/clock.png'],
    ['phoneme' => 'С', 'ru' => 'кошка', 'kk' => 'мысық', 'en' => 'cat', 'image' => '/public/assets/img/cat.png'],
    ['phoneme' => 'Ш', 'ru' => 'санки', 'kk' => 'шана', 'en' => 'sled', 'image' => '/public/assets/img/sled.png'],
    ['phoneme' => 'Ш', 'ru' => 'ягнёнок', 'kk' => 'қошақан', 'en' => 'lamb', 'image' => '/public/assets/img/lamb.png'],
    ['phoneme' => 'Ж', 'ru' => 'флаг', 'kk' => 'жалау', 'en' => 'flag', 'image' => '/public/assets/img/flag.png'],
    ['phoneme' => 'Ж', 'ru' => 'виноград', 'kk' => 'жүзім', 'en' => 'grapes', 'image' => '/public/assets/img/grapes.png'],
    ['phoneme' => 'Қ', 'ru' => 'заяц', 'kk' => 'қоян', 'en' => 'rabbit', 'image' => '/public/assets/img/rabbit.png'],
    ['phoneme' => 'Қ', 'ru' => 'лебедь', 'kk' => 'аққу', 'en' => 'swan', 'image' => '/public/assets/img/swan.png'],
    ['phoneme' => 'Ғ', 'ru' => 'космос', 'kk' => 'ғарыш', 'en' => 'space', 'image' => '/public/assets/img/space.png'],
    ['phoneme' => 'З', 'ru' => 'закон', 'kk' => 'заң', 'en' => 'law', 'image' => '/public/assets/img/law.png'],
    ['phoneme' => 'Ч/Ш', 'ru' => 'чемпион', 'kk' => 'чемпион', 'en' => 'champion', 'image' => '/public/assets/img/champion.png'],
    ['ru' => 'солнце', 'kk' => 'күн', 'en' => 'sun', 'image' => '/public/assets/img/sun.png'],
    ['ru' => 'рыба', 'kk' => 'балық', 'en' => 'fish', 'image' => '/public/assets/img/fish.png'],
    ['ru' => 'шарик', 'kk' => 'шар', 'en' => 'ball', 'image' => '/public/assets/img/ball.png'],
    ['ru' => 'лампа', 'kk' => 'шам', 'en' => 'lamp', 'image' => '/public/assets/img/lamp.png'],
    ['ru' => 'жук', 'kk' => 'қоңыз', 'en' => 'beetle', 'image' => '/public/assets/img/beetle.png'],
    ['ru' => 'чашка', 'kk' => 'кесе', 'en' => 'cup', 'image' => '/public/assets/img/cup.png'],
];

$words = array_slice($words, 0, 15);

$currentLang ??= function_exists('app_locale') ? app_locale() : 'ru';

require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-4xl mx-auto space-y-8" id="datasetApp">
    <div class="text-center space-y-3">
        <h1 class="text-4xl font-bold text-gray-800"><?= e(tr('dataset_page.title', 'Сбор датасета')) ?></h1>
        <p class="text-lg text-gray-600">
            <?= e(tr('dataset_page.subtitle', 'Помогите нам собрать данные для улучшения AI')) ?>
        </p>
        <div class="flex justify-center pt-2">
            <a href="/dataset-history.php" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white/80 border border-gray-200 rounded-full shadow-sm hover:bg-white hover:border-blue-soft/40 transition-colors no-underline">
                <svg class="w-4 h-4 text-blue-soft" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l3 2"/></svg>
                <?= e(tr('dataset_history', 'История датасета')) ?>
            </a>
        </div>
    </div>

    <div class="border-0 shadow-xl bg-white/80 backdrop-blur-md rounded-3xl p-8 space-y-6 border border-white/20">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-2xl bg-blue-light/30 flex items-center justify-center text-blue-soft">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800"><?= e(tr('dataset_page.info_title', 'Данные участника')) ?></h2>
        </div>

        <div class="grid md:grid-cols-2 gap-x-8 gap-y-5">
            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-600 ml-1">
                    <span><?= e(tr('dataset_page.child_name', 'Имя ребёнка')) ?></span>
                </label>
                <div class="relative">
                    <input id="childName" type="text" class="w-full px-5 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/30 transition-all hover:bg-white focus:bg-white" placeholder="<?= e(tr('dataset_page.child_name_placeholder', 'Например: Анар')) ?>">
                </div>
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-600 ml-1">
                    <span><?= e(tr('dataset_page.child_age', 'Возраст')) ?></span>
                </label>
                <div class="relative">
                    <input id="childAge" type="number" min="2" max="16" class="w-full px-5 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/30 transition-all hover:bg-white focus:bg-white" placeholder="<?= e(tr('dataset_page.child_age_placeholder', 'Например: 6')) ?>">
                </div>
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-600 ml-1">
                    <span><?= e(tr('dataset_page.child_id', 'ID ребёнка')) ?></span>
                </label>
                <div class="relative">
                    <input id="childId" type="text" class="w-full px-5 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/30 transition-all hover:bg-white focus:bg-white" placeholder="<?= e(tr('dataset_page.child_id_placeholder', 'Например: 101')) ?>">
                </div>
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-600 ml-1">
                    <span><?= e(tr('dataset_page.gender', 'Пол')) ?></span>
                </label>
                <div class="relative group">
                    <select id="childGender" class="w-full px-5 py-4 bg-blue-50/50 border border-blue-100 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-blue-soft/15 transition-all hover:bg-white focus:bg-white appearance-none cursor-pointer text-gray-700 shadow-sm group-hover:shadow-md">
                        <option value="" disabled selected class="bg-white">Выберите пол</option>
                        <option value="male" class="bg-white"><?= e(tr('dataset_page.male', 'Мужской')) ?></option>
                        <option value="female" class="bg-white"><?= e(tr('dataset_page.female', 'Женский')) ?></option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-blue-soft transition-transform group-hover:translate-y-[-40%]">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-600 ml-1">
                    <span><?= e(tr('dataset_page.disorder_type', 'Тип нарушения')) ?></span>
                </label>
                <div class="relative group">
                    <select id="disorderType" class="w-full px-5 py-4 bg-blue-50/50 border border-blue-100 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-blue-soft/15 transition-all hover:bg-white focus:bg-white appearance-none cursor-pointer text-gray-700 shadow-sm group-hover:shadow-md">
                        <option value="" disabled selected class="bg-white"><?= e(tr('dataset_page.disorder_type_placeholder', 'Выберите тип нарушения')) ?></option>
                        <option value="ЗРР" class="bg-white"><?= e(tr('dataset_page.disorder_zrr', 'ЗРР')) ?></option>
                        <option value="дислалия" class="bg-white"><?= e(tr('dataset_page.disorder_dyslalia', 'Дислалия')) ?></option>
                        <option value="ОНР" class="bg-white"><?= e(tr('dataset_page.disorder_onr', 'ОНР')) ?></option>
                        <option value="дизартрия" class="bg-white"><?= e(tr('dataset_page.disorder_dysarthria', 'Дизартрия')) ?></option>
                        <option value="заикание" class="bg-white"><?= e(tr('dataset_page.disorder_stuttering', 'Заикание')) ?></option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-blue-soft transition-transform group-hover:translate-y-[-40%]">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-0 shadow-lg rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #F0F9FF, #ffffff);">
        <div class="p-8 md:p-10 space-y-8">
            <div id="participantNotice" class="rounded-2xl border border-orange-100 bg-orange-50 px-5 py-4 text-sm font-semibold text-orange-700 text-center">
                Заполните данные ребёнка полностью, затем начните запись.
            </div>

            <div class="rounded-2xl bg-white/85 border border-blue-100 p-5 shadow-sm space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Прогресс записи</p>
                        <p id="wordProgressMeta" class="text-sm font-semibold text-gray-700 mt-1">Текущее слово: мама · попытка 1 из 3</p>
                    </div>
                    <span id="wordProgressCount" class="inline-flex items-center justify-center px-3 py-1.5 rounded-full bg-blue-50 text-blue-soft text-sm font-bold">0/15 слов</span>
                </div>
                <div class="h-3 rounded-full bg-blue-50 overflow-hidden">
                    <div id="wordProgressBar" class="h-full rounded-full bg-blue-soft transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>
                <div id="wordProgressList" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2"></div>
            </div>

            <div class="flex items-center justify-center gap-3 text-sm font-medium text-gray-600">
                <span id="statusDot" class="w-3 h-3 rounded-full bg-green-500"></span>
                <span id="statusText"><?= e(tr('dataset_page.ready', 'Готов к записи')) ?></span>
            </div>

            <div id="micError" class="hidden rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700 text-center">
                <span id="micErrorText"></span>
            </div>

            <div class="text-center space-y-6">
                <div class="flex justify-center">
                    <div class="w-72 h-72 rounded-3xl overflow-hidden shadow-2xl bg-white p-3 border border-blue-50 transform hover:scale-105 transition-transform duration-500">
                        <img id="wordImage" src="<?= e($words[0]['image']) ?>" alt="Word Visual" class="w-full h-full object-contain transition-opacity duration-300" onerror="this.src='/public/assets/img/mother.png'">
                    </div>
                </div>

                <div class="space-y-4">
                    <div id="phonemeBadgeContainer" class="hidden flex justify-center">
                        <span id="phonemeBadge" class="px-4 py-1.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-widest border border-blue-200 shadow-sm"></span>
                    </div>
                    <p class="text-sm uppercase tracking-widest text-gray-500"><?= e(tr('dataset_page.repeat_word', 'Повторите слово')) ?></p>
                    <p id="attemptText" class="text-sm font-bold text-blue-soft">Попытка 1 из 3</p>
                </div>
                    <div class="flex items-center justify-center gap-3">
                        <h2 id="currentWord" class="text-5xl md:text-7xl font-bold text-gray-800 transition-all duration-300">мама</h2>
                        <button type="button" id="playSoundBtn" class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-blue-50 text-blue-soft transition-colors" aria-label="Play word">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                            </svg>
                        </button>
                    </div>
                    <p id="currentWordAlt" class="text-2xl text-gray-400 font-medium tracking-tight italic opacity-80">(ана)</p>
                </div>
            </div>


            <div class="max-w-md mx-auto flex items-center justify-center gap-1.5 h-12">
                <?php for ($i = 0; $i < 32; $i++): ?>
                    <div class="waveform-bar w-1 bg-blue-soft/20 rounded-full transition-all duration-150 h-2"></div>
                <?php endfor; ?>
            </div>


            <div class="flex flex-col items-center gap-8 py-4">
                <div class="relative">
                    <div id="recordPulse" class="pointer-events-none absolute inset-0 rounded-full bg-blue-soft/20 scale-100 opacity-0 transition-all duration-500"></div>
                    <button type="button" id="recordBtn" class="w-32 h-32 rounded-full gradient-record-btn text-white shadow-2xl hover:scale-105 transition-all duration-300 flex items-center justify-center group relative z-10">
                        <svg id="micIcon" class="w-12 h-12 transition-transform duration-300 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="22"></line>
                        </svg>
                        <svg id="stopIcon" class="hidden w-12 h-12 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <rect x="6" y="6" width="12" height="12" rx="2" fill="currentColor"></rect>
                        </svg>
                    </button>
                </div>
                
                <p id="hintText" class="text-gray-500 font-medium text-center px-4 max-w-sm">
                    <?= e(tr('dataset_page.recording_hint', 'Нажмите на микрофон, чтобы начать запись')) ?>
                </p>
            </div>

            <div id="pendingRecordingPanel" class="hidden max-w-xl mx-auto rounded-2xl bg-white border border-blue-100 shadow-sm p-5 space-y-4">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Готовые записи</p>
                        <p id="pendingRecordingText" class="text-sm font-semibold text-gray-700 mt-1"></p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-mint/25 text-mint-dark text-xs font-bold">WAV</span>
                </div>
                <audio id="pendingAudio" controls preload="metadata" class="w-full"></audio>
                <div id="pendingRecordingList" class="space-y-2 max-h-44 overflow-y-auto"></div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <button type="button" id="discardRecordingBtn" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-full hover:bg-gray-100 transition-colors">
                        Удалить последнюю
                    </button>
                    <button type="button" id="sendAudioBtn" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gray-900 rounded-full shadow-sm hover:bg-gray-800 transition-colors">
                        Отправить все
                    </button>
                </div>
            </div>

            <div id="wordResult" class="hidden text-center py-2">
                <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-mint/20 text-mint font-semibold text-sm shadow-sm border border-mint/10">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                    <span id="wordResultText"></span>
                </span>
            </div>

            <div class="flex justify-center pt-6 pb-2">
                <button type="button" id="skipWordBtn" class="group inline-flex items-center gap-2.5 px-6 py-3 text-sm font-semibold border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all text-gray-700 shadow-sm">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-soft transition-colors" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                        <polyline points="21 12 16 12 16 7"></polyline>
                    </svg>
                    <?= e(tr('dataset_page.skip', 'Пропустить слово')) ?>
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-10 pt-12">
        <div id="historyPanel" class="hidden border-0 shadow-xl bg-white/80 backdrop-blur-md rounded-3xl p-6 space-y-4 border border-white/20">
            <div id="historyEmpty" class="hidden"></div>
            <div id="historyList" class="space-y-3"></div>
        </div>

        <div id="reportPanel" class="hidden border-0 shadow-xl bg-white/80 backdrop-blur-md rounded-3xl p-6 space-y-4 border border-white/20">
            <div class="flex justify-end">
                <a id="openReportLink" href="#" target="_blank" class="hidden px-4 py-2 rounded-full bg-mint/30 text-mint-dark text-sm font-bold hover:bg-mint/40 transition">
                    Открыть
                </a>
            </div>
            <div id="reportEmpty" class="hidden"></div>
            <div id="reportBody" class="hidden space-y-4">
                <p id="reportSummary" class="text-sm text-gray-600 leading-relaxed"></p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-blue-50 p-4">
                        <p class="text-xs uppercase tracking-widest text-gray-500 font-bold">Попытки</p>
                        <p id="reportAttempts" class="text-2xl font-bold text-gray-800">0</p>
                    </div>
                    <div class="rounded-2xl bg-mint/20 p-4">
                        <p class="text-xs uppercase tracking-widest text-gray-500 font-bold">Accuracy</p>
                        <p id="reportAccuracy" class="text-2xl font-bold text-gray-800">0%</p>
                    </div>
                    <div class="rounded-2xl bg-orange-soft/20 p-4">
                        <p class="text-xs uppercase tracking-widest text-gray-500 font-bold">Risk</p>
                        <p id="reportRisk" class="text-2xl font-bold text-gray-800">-</p>
                    </div>
                    <div class="rounded-2xl bg-purple-soft/20 p-4">
                        <p class="text-xs uppercase tracking-widest text-gray-500 font-bold">Слова</p>
                        <p id="reportWords" class="text-2xl font-bold text-gray-800">0</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-700 mb-2">Следующие действия</p>
                    <ul id="reportActions" class="space-y-2 text-sm text-gray-600"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$wJson = json_encode($words, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$lJson = json_encode($currentLang, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$fastApiBase = json_encode(rtrim((string) env('FASTAPI_API_URL', 'http://localhost:8000/api'), '/'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$tJson = json_encode([
    'recording_status' => tr('dataset_page.recording_status'),
    'recording_hint' => tr('dataset_page.recording_hint'),
    'analyzing_status' => tr('dataset_page.analyzing_status'),
    'analyzing_hint' => tr('dataset_page.analyzing_hint'),
    'report_status' => tr('dataset_page.report_status'),
    'report_hint' => tr('dataset_page.report_hint'),
    'ready_status' => tr('dataset_page.ready'),
    'ready_hint' => tr('dataset_page.hint_ready'),
    'mic_error_https' => tr('dataset_page.mic_error_https'),
    'mic_error_browser' => tr('dataset_page.mic_error_browser'),
    'mic_error_recorder' => tr('dataset_page.mic_error_recorder'),
    'mic_error_denied' => tr('dataset_page.mic_error_denied'),
    'mic_error_not_found' => tr('dataset_page.mic_error_not_found'),
    'mic_error_empty' => tr('dataset_page.mic_error_empty'),
    'mic_error_generic' => tr('dataset_page.mic_error_generic'),
    'complete_error' => tr('dataset_page.complete_error'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$extraScripts .= <<<JS
<script>
const W = {$wJson};
const LANG = {$lJson};
const FASTAPI = {$fastApiBase};
const API = {
    children: FASTAPI + '/children',
    resolveChild: FASTAPI + '/children/resolve',
    ensureWord: FASTAPI + '/words/ensure',
    uploadAudio: FASTAPI + '/audio/upload',
    childAudio: (childId) => FASTAPI + '/audio/child/' + childId,
    childReport: (childId) => FASTAPI + '/reports/child/' + childId,
    childReportHtml: (childId) => FASTAPI + '/reports/child/' + childId + '/html'
};

const T = {$tJson};

const MAX_ATTEMPTS_PER_WORD = 3;
const WORD_TOTAL = W.length;
let wi = 0;
let currentAttempt = 1;
let rec = false;
let busy = false;
let sesOk = false;
let mr = null;
let ms = null;
let ch = [];
let wt = null;
let ac = null;
let an = null;
let backendChildId = null;
let backendReportUrl = null;
let sessionHistory = [];
let pendingRecordings = [];
let pendingRecordingId = 0;

const \$ = (id) => document.getElementById(id);
const bars = document.querySelectorAll('.waveform-bar');

function esc(value) {
    return String(value ?? '').replace(/[&<>"']/g, (ch) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[ch]));
}

function riskBadgeClass(risk) {
    if (risk === 'жоғары') return 'bg-red-100 text-red-700';
    if (risk === 'орташа') return 'bg-orange-soft/30 text-orange-700';
    return 'bg-mint/30 text-mint-dark';
}

function parseAttemptNumber(value) {
    const normalized = String(value || '').trim().toLowerCase().replace(/^x/, '');
    const parsed = Number.parseInt(normalized, 10);
    return Number.isFinite(parsed) ? parsed : null;
}

function normalizedWord(value) {
    return String(value || '').trim().toLowerCase();
}

function currentWordText() {
    return W[wi] ? W[wi].ru : '';
}

function savedAttemptCountForWord(wordText = currentWordText()) {
    return sessionHistory.filter((item) => {
        const analysis = item.analysis || {};
        return normalizedWord(analysis.expected_word || item.word || '') === normalizedWord(wordText);
    }).length;
}

function pendingAttemptCountForWord(wordText = currentWordText()) {
    return pendingRecordings.filter((recording) => {
        return normalizedWord(recording.word.ru) === normalizedWord(wordText);
    }).length;
}

function attemptCountForWord(wordText = currentWordText()) {
    return savedAttemptCountForWord(wordText) + pendingAttemptCountForWord(wordText);
}

function completedWordCount() {
    return W.filter((word) => attemptCountForWord(word.ru) >= MAX_ATTEMPTS_PER_WORD).length;
}

function nextIncompleteWordIndex(startIndex = 0) {
    for (let i = Math.max(0, startIndex); i < W.length; i++) {
        if (attemptCountForWord(W[i].ru) < MAX_ATTEMPTS_PER_WORD) return i;
    }
    for (let i = 0; i < Math.max(0, startIndex); i++) {
        if (attemptCountForWord(W[i].ru) < MAX_ATTEMPTS_PER_WORD) return i;
    }
    return -1;
}

function updateAttemptText() {
    const el = $('attemptText');
    if (!el) return;
    el.textContent = 'Попытка ' + currentAttempt + ' из ' + MAX_ATTEMPTS_PER_WORD;
}

function renderWordProgress() {
    const completed = completedWordCount();
    const percent = WORD_TOTAL ? Math.round((completed / WORD_TOTAL) * 100) : 0;
    const count = $('wordProgressCount');
    const bar = $('wordProgressBar');
    const meta = $('wordProgressMeta');
    const list = $('wordProgressList');

    if (count) count.textContent = completed + '/' + WORD_TOTAL + ' слов';
    if (bar) bar.style.width = percent + '%';
    if (meta) {
        meta.textContent = completed >= WORD_TOTAL
            ? 'Все слова записаны по 3 раза'
            : 'Текущее слово: ' + currentWordText() + ' · попытка ' + currentAttempt + ' из ' + MAX_ATTEMPTS_PER_WORD;
    }
    if (!list) return;

    list.innerHTML = W.map((word, index) => {
        const attempts = Math.min(MAX_ATTEMPTS_PER_WORD, attemptCountForWord(word.ru));
        const isCurrent = index === wi && attempts < MAX_ATTEMPTS_PER_WORD;
        const isDone = attempts >= MAX_ATTEMPTS_PER_WORD;
        const classes = isDone
            ? 'bg-mint/25 border-mint/30 text-mint-dark'
            : (isCurrent ? 'bg-blue-50 border-blue-soft/40 text-blue-700 ring-2 ring-blue-soft/15' : 'bg-white border-gray-100 text-gray-500');
        return '<div class="rounded-2xl border px-3 py-2 text-xs font-bold transition-all ' + classes + '">'
            + '<div class="flex items-center justify-between gap-2">'
            + '<span class="truncate">' + esc(index + 1) + '. ' + esc(word.ru) + '</span>'
            + '<span>' + attempts + '/3</span>'
            + '</div>'
            + '</div>';
    }).join('');
}

function syncAttemptFromHistory() {
    const nextAttempt = attemptCountForWord() + 1;
    currentAttempt = Math.min(MAX_ATTEMPTS_PER_WORD, Math.max(1, nextAttempt));
    updateAttemptText();
    renderWordProgress();
}

function resetPendingRecordings() {
    pendingRecordings.forEach((recording) => {
        if (recording.url) URL.revokeObjectURL(recording.url);
    });
    pendingRecordings = [];
    renderPendingRecordings();
}

function renderPendingRecordings() {
    const panel = $('pendingRecordingPanel');
    const audio = $('pendingAudio');
    const text = $('pendingRecordingText');
    const list = $('pendingRecordingList');
    const sendButton = $('sendAudioBtn');
    const discardButton = $('discardRecordingBtn');
    const latest = pendingRecordings[pendingRecordings.length - 1] || null;
    const count = pendingRecordings.length;

    if (panel) panel.classList.toggle('hidden', count === 0);
    if (audio) {
        if (latest) {
            audio.src = latest.url;
        } else {
            audio.removeAttribute('src');
        }
    }
    if (text) {
        text.textContent = count
            ? count + ' WAV в очереди. Можно продолжать запись или отправить все в историю.'
            : '';
    }
    if (list) {
        list.innerHTML = pendingRecordings.map((recording, index) => {
            return ''
                + '<div class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 border border-gray-100 px-3 py-2 text-xs">'
                + '<span class="font-semibold text-gray-700">' + esc(index + 1) + '. ' + esc(recording.word.ru) + ' · попытка ' + esc(recording.attempt) + '/3</span>'
                + '<button type="button" data-remove-pending="' + esc(recording.id) + '" class="font-bold text-red-500 hover:text-red-600">Удалить</button>'
                + '</div>';
        }).join('');
    }
    if (sendButton) sendButton.disabled = count === 0;
    if (discardButton) discardButton.disabled = count === 0;
}

function addPendingRecording(recording) {
    pendingRecordings.push(recording);
    renderPendingRecordings();
}

function removePendingRecording(recordingId) {
    const index = pendingRecordings.findIndex((recording) => Number(recording.id) === Number(recordingId));
    if (index === -1) return;
    const [recording] = pendingRecordings.splice(index, 1);
    if (recording && recording.url) URL.revokeObjectURL(recording.url);
    renderPendingRecordings();
    syncAttemptFromHistory();
    renderWordProgress();
}

function removeLastPendingRecording() {
    const latest = pendingRecordings[pendingRecordings.length - 1];
    if (!latest) return;
    removePendingRecording(latest.id);
}

function advanceAfterLocalRecording(recording) {
    const attempts = attemptCountForWord(recording.word.ru);
    renderWordProgress();

    if (attempts < MAX_ATTEMPTS_PER_WORD) {
        currentAttempt = attempts + 1;
        updateAttemptText();
        stat('ready', T.ready_status, 'Запишите это же слово еще раз: попытка ' + currentAttempt + ' из ' + MAX_ATTEMPTS_PER_WORD + '.');
        return;
    }

    const nextIndex = nextIncompleteWordIndex(recording.wordIndex + 1);
    if (nextIndex !== -1) {
        wi = nextIndex;
        currentAttempt = Math.min(MAX_ATTEMPTS_PER_WORD, attemptCountForWord(W[wi].ru) + 1);
        show();
        stat('ready', T.ready_status, 'Слово "' + recording.word.ru + '" готово. Продолжайте запись следующего слова.');
        return;
    }

    currentAttempt = MAX_ATTEMPTS_PER_WORD;
    renderWordProgress();
    updateAttemptText();
    stat('ready', 'Серия записана', 'Все 15 слов записаны по 3 раза. Нажмите "Отправить все", чтобы сохранить очередь в истории.');
}

function participantValidation() {
    const name = $('childName').value.trim();
    const age = Number($('childAge').value);
    const externalId = $('childId').value.trim();
    const gender = $('childGender').value.trim();
    const disorder = $('disorderType').value.trim();

    if (!name || !Number.isFinite(age) || age < 2 || age > 16 || !externalId || !gender || !disorder) {
        return {
            ok: false,
            message: 'Заполните данные ребёнка полностью: имя, возраст, ID, пол и тип нарушения.'
        };
    }

    return { ok: true, message: '' };
}

function updateParticipantState() {
    const validation = participantValidation();
    const notice = $('participantNotice');
    if (notice) {
        notice.textContent = validation.ok ? '' : validation.message;
        notice.classList.toggle('hidden', validation.ok);
    }
    return validation;
}

function renderHistory() {
    const panel = $('historyPanel');
    const list = $('historyList');
    const empty = $('historyEmpty');
    const count = $('historyCount');
    if (!list || !empty) return;

    const hasItems = sessionHistory.length > 0;
    if (panel) panel.classList.toggle('hidden', !hasItems);
    if (count) count.textContent = String(sessionHistory.length);
    empty.classList.add('hidden');

    list.innerHTML = sessionHistory.map((item, index) => {
        const analysis = item.analysis || {};
        const audio = item.audio || {};
        const risk = analysis.risk_level || '-';
        return ''
            + '<div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm space-y-3">'
            + '<div class="flex items-start justify-between gap-3">'
            + '<div>'
            + '<p class="text-xs text-gray-400 font-bold uppercase tracking-widest">#' + esc(index + 1) + ' · audio ' + esc(audio.id || '') + '</p>'
            + '<h3 class="text-lg font-bold text-gray-800">' + esc(analysis.expected_word || item.word || '') + ' → ' + esc(analysis.recognized_word || '') + '</h3>'
            + '</div>'
            + '<span class="px-3 py-1 rounded-full text-xs font-bold ' + riskBadgeClass(risk) + '">' + esc(risk) + '</span>'
            + '</div>'
            + '<div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">'
            + '<div class="h-full rounded-full bg-blue-soft" style="width:' + Number(analysis.accuracy || 0) + '%"></div>'
            + '</div>'
            + '<div class="grid md:grid-cols-2 gap-2 text-xs text-gray-500">'
            + '<span>Accuracy: <b>' + esc(analysis.accuracy || 0) + '%</b></span>'
            + '<span>Қате: <b>' + esc(analysis.mistake_type || '-') + '</b></span>'
            + '<span class="md:col-span-2 break-all">Файл: ' + esc(audio.file_path || '') + '</span>'
            + '<span class="md:col-span-2">Ұсыныс: ' + esc(analysis.recommendation || '') + '</span>'
            + '</div>'
            + '</div>';
    }).join('');
}

function resetDatasetSession() {
    if (!sesOk && !backendChildId && sessionHistory.length === 0 && pendingRecordings.length === 0) return;

    resetPendingRecordings();
    sesOk = false;
    backendChildId = null;
    backendReportUrl = null;
    sessionHistory = [];
    wi = 0;
    currentAttempt = 1;
    renderHistory();
    renderWordProgress();
    updateParticipantState();
    show();

    const link = $('openReportLink');
    const panel = $('reportPanel');
    const empty = $('reportEmpty');
    const body = $('reportBody');
    if (panel) panel.classList.add('hidden');
    if (link) link.classList.add('hidden');
    if (empty) empty.classList.add('hidden');
    if (body) body.classList.add('hidden');
}

function bindParticipantFields() {
    ['childName', 'childAge', 'childId', 'childGender', 'disorderType'].forEach((id) => {
        const el = $(id);
        if (!el) return;
        const eventName = el.tagName === 'SELECT' ? 'change' : 'input';
        el.addEventListener(eventName, () => {
            updateParticipantState();
            resetDatasetSession();
        });
    });
}

async function loadBackendHistory() {
    if (!backendChildId) return;

    const records = await apiJson(API.childAudio(backendChildId));
    sessionHistory = (records || [])
        .filter((record) => record.analysis_result)
        .map((record) => ({
            word: record.analysis_result.expected_word,
            audio: {
                id: record.id,
                child_id: record.child_id,
                word_id: record.word_id,
                attempt_number: record.attempt_number,
                file_path: record.file_path,
                duration: record.duration,
                created_at: record.created_at
            },
            analysis: record.analysis_result
        }));
    renderHistory();
    const nextIndex = nextIncompleteWordIndex(0);
    wi = nextIndex === -1 ? Math.max(0, W.length - 1) : nextIndex;
    syncAttemptFromHistory();
}

async function refreshReport() {
    if (!backendChildId) return;
    const report = await apiJson(API.childReport(backendChildId));
    renderReport(report);
}

function renderReport(report) {
    const panel = $('reportPanel');
    const empty = $('reportEmpty');
    const body = $('reportBody');
    if (!empty || !body || !report) return;

    if (Number(report.total_audio || 0) <= 0) {
        if (panel) panel.classList.add('hidden');
        empty.classList.add('hidden');
        body.classList.add('hidden');
        const link = $('openReportLink');
        if (link) link.classList.add('hidden');
        return;
    }

    if (panel) panel.classList.remove('hidden');
    empty.classList.add('hidden');
    body.classList.remove('hidden');
    $('reportSummary').textContent = report.summary || '';
    $('reportAttempts').textContent = String(report.total_audio || 0);
    $('reportAccuracy').textContent = String(report.average_accuracy || 0) + '%';
    $('reportRisk').textContent = report.overall_risk_level || '-';
    $('reportWords').textContent = String(report.words_practiced || 0);

    const actions = report.next_actions || [];
    $('reportActions').innerHTML = actions.map((action) => {
        return '<li class="rounded-xl bg-gray-50 px-3 py-2">' + esc(action) + '</li>';
    }).join('');

    const link = $('openReportLink');
    if (link) {
        link.href = backendReportUrl || API.childReportHtml(backendChildId);
        link.classList.remove('hidden');
    }
}

function show() {
    const w = W[wi];
    if (!w) return;
    syncAttemptFromHistory();

    const main = LANG === 'kk' ? (w.kk || w.ru) : (LANG === 'en' ? (w.en || w.ru) : w.ru);
    const alt = LANG === 'ru' ? (w.kk || w.en) : (LANG === 'kk' ? w.ru : w.ru);

    $('currentWord').textContent = main;
    $('currentWord').style.opacity = '0';
    $('currentWord').style.transform = 'scale(0.8)';

    if (w.phoneme) {
        $("phonemeBadge").textContent = w.phoneme;
        $("phonemeBadgeContainer").classList.remove("hidden");
    } else {
        $("phonemeBadgeContainer").classList.add("hidden");
    }

    if (w.image) {
        $('wordImage').src = w.image;
        $('wordImage').style.opacity = '1';
    } else {
        $('wordImage').style.opacity = '0';
    }

    requestAnimationFrame(() => {
        $('currentWord').style.opacity = '1';
        $('currentWord').style.transform = 'scale(1)';
    });

    $('currentWordAlt').textContent = '(' + alt + ')';
    $('wordResult').classList.add('hidden');
    updateAttemptText();
    renderWordProgress();
}

function stat(mode, title, hint) {
    $('statusText').textContent = title;
    $('hintText').textContent = hint;

    if (mode === 'ready') {
        $('statusDot').className = 'w-3 h-3 rounded-full bg-green-500';
        $('recordBtn').className = 'w-32 h-32 rounded-full flex items-center justify-center shadow-2xl gradient-record-btn transition-all duration-300 transform hover:scale-105 active:scale-95 relative z-10';
        $('micIcon').classList.remove('hidden');
        $('stopIcon').classList.add('hidden');
    }

    if (mode === 'rec') {
        $('statusDot').className = 'w-3 h-3 rounded-full bg-red-500 animate-pulse';
        $('recordBtn').className = 'w-32 h-32 rounded-full flex items-center justify-center shadow-2xl gradient-record-btn-active animate-pulse-soft transition-all duration-300 relative z-10';
        $('micIcon').classList.add('hidden');
        $('stopIcon').classList.remove('hidden');
    }

    if (mode === 'ai') {
        $('statusDot').className = 'w-3 h-3 rounded-full bg-yellow-500 animate-pulse';
        $('recordBtn').className = 'w-32 h-32 rounded-full flex items-center justify-center shadow-2xl gradient-record-btn opacity-50 transition-all duration-300 relative z-10';
        $('micIcon').classList.remove('hidden');
        $('stopIcon').classList.add('hidden');
    }
}

function err(msg) {
    const box = $('micError');
    const text = $('micErrorText');
    if (!box || !text) {
        alert(msg);
        return;
    }
    text.textContent = msg;
    box.classList.remove('hidden');
    setTimeout(() => box.classList.add('hidden'), 8000);
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

function stopMediaStream() {
    if (!ms) return;
    ms.getTracks().forEach((track) => track.stop());
    ms = null;
}

function secureContextMessage() {
    const host = window.location.hostname;
    const isLocal = host === 'localhost' || host === '127.0.0.1' || host === '[::1]' || host === '::1';
    if (isLocal) return T.mic_error_https;
    return T.mic_error_https + ' Откройте проект через localhost на своем компьютере или настройте HTTPS для доступа с другого устройства.';
}

async function microphonePermissionState() {
    if (!navigator.permissions || !navigator.permissions.query) return '';
    try {
        const permission = await navigator.permissions.query({ name: 'microphone' });
        return permission.state || '';
    } catch (e) {
        return '';
    }
}

async function requestMicrophoneStream() {
    const permissionState = await microphonePermissionState();
    if (permissionState === 'denied') {
        throw new Error('Доступ к микрофону запрещен в браузере. Нажмите на значок замка рядом с адресом сайта и разрешите микрофон.');
    }

    try {
        return await navigator.mediaDevices.getUserMedia({
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true
            }
        });
    } catch (e) {
        if (e && e.name === 'NotAllowedError') {
            throw new Error(T.mic_error_denied + ' Если окно разрешения не появляется, нажмите на значок замка рядом с адресом сайта.');
        }
        if (e && e.name === 'NotFoundError') {
            throw new Error(T.mic_error_not_found);
        }
        if (e && e.name === 'SecurityError') {
            throw new Error(secureContextMessage());
        }
        throw new Error(T.mic_error_generic + ': ' + (e && e.message ? e.message : ''));
    }
}

function formatBackendDetail(detail) {
    if (!detail) return '';
    if (typeof detail === 'string') return detail;
    if (Array.isArray(detail)) {
        return detail.map(formatBackendDetail).filter(Boolean).join('; ');
    }
    if (typeof detail === 'object') {
        const location = Array.isArray(detail.loc) ? detail.loc.filter((part) => part !== 'body').join('.') : '';
        const message = detail.msg || detail.message || detail.detail || JSON.stringify(detail);
        return location ? location + ': ' + message : message;
    }
    return String(detail);
}

async function apiJson(url, options = {}) {
    const r = await fetch(url, options);
    let d = null;
    try {
        d = await r.json();
    } catch (e) {
        d = {};
    }

    if (!r.ok) {
        const msg = formatBackendDetail(d && d.detail) || (d && d.message) || 'Backend error: ' + r.status;
        throw new Error(msg);
    }

    return d;
}

function childAgeGroup() {
    const age = Number($('childAge').value || 5);
    if (!Number.isFinite(age)) return 5;
    return Math.max(2, Math.min(16, Math.round(age)));
}

async function ses() {
    if (sesOk) return;
    const validation = updateParticipantState();
    if (!validation.ok) {
        throw new Error(validation.message);
    }

    const child = await apiJson(API.resolveChild, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            full_name: $('childName').value.trim(),
            age: childAgeGroup(),
            parent_name: $('childId').value.trim(),
            gender: $('childGender').value,
            disorder_type: $('disorderType').value.trim()
        })
    });

    backendChildId = child.id;
    backendReportUrl = API.childReportHtml(backendChildId);

    sesOk = true;
    await loadBackendHistory();
    show();
    try {
        await refreshReport();
    } catch (e) {
        console.warn('Initial report refresh failed', e);
    }
}

async function ensureBackendWord(word) {
    return apiJson(API.ensureWord, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            text: word.ru,
            age_group: childAgeGroup(),
            target_sound: word.phoneme || null
        })
    });
}

async function handleRecord() {
    if (busy) return;

    if (rec && mr && mr.state === 'recording') {
        mr.stop();
        return;
    }

    const validation = updateParticipantState();
    if (!validation.ok) {
        err(validation.message);
        stat('ready', 'Заполните данные ребёнка', validation.message);
        return;
    }

    const incompleteIndex = nextIncompleteWordIndex(wi);
    if (incompleteIndex === -1) {
        stat('ready', 'Серия завершена', 'Все 15 слов записаны по 3 раза.');
        renderWordProgress();
        return;
    }
    if (incompleteIndex !== wi && attemptCountForWord() >= MAX_ATTEMPTS_PER_WORD) {
        wi = incompleteIndex;
        show();
    }

    if (!window.isSecureContext) {
        err(secureContextMessage());
        return;
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        err(T.mic_error_browser);
        return;
    }

    if (!window.MediaRecorder) {
        err(T.mic_error_recorder);
        return;
    }

    busy = true;
    try {
        ms = await requestMicrophoneStream();
    } catch (e) {
        const message = e && e.message ? e.message : T.mic_error_generic;
        busy = false;
        err(message);
        stat('ready', 'Микрофон недоступен', message);
        return;
    }

    try {
        await ses();
        syncAttemptFromHistory();
        if (attemptCountForWord() >= MAX_ATTEMPTS_PER_WORD) {
            stopMediaStream();
            busy = false;
            err('Для слова "' + currentWordText() + '" уже сохранены 3 записи этого ребенка. Перейдите к следующему слову.');
            stat('ready', T.ready_status, 'Максимум для одного слова: 3 голосовые записи.');
            return;
        }
    } catch (e) {
        stopMediaStream();
        busy = false;
        err('Ошибка: ' + (e && e.message ? e.message : 'не удалось подготовить запись'));
        stat('ready', T.ready_status, T.ready_hint);
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
        busy = false;
        err('Не удалось запустить запись: ' + (e && e.message ? e.message : 'ошибка MediaRecorder'));
        stopMediaStream();
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
        stopMediaStream();
        stat('ready', T.ready_status, T.ready_hint);
    };

    mr.onstop = preparePendingRecording;

    try {
        mr.start();
    } catch (e) {
        busy = false;
        err(T.mic_error_generic);
        stopMediaStream();
        return;
    }

    rec = true;
    busy = false;
    stat('rec', T.recording_status, T.recording_hint);
    realWave(ms);

    setTimeout(() => {
        if (mr && mr.state === 'recording') mr.stop();
    }, 4000);
}

async function preparePendingRecording() {
    rec = false;
    busy = true;
    stopWave();

    if (ac) {
        try { ac.close(); } catch (e) {}
    }
    ac = null;

    stat('ai', 'Подготовка WAV', 'Готовим запись к отправке.');

    const mime = (mr && mr.mimeType) ? mr.mimeType : 'audio/webm';
    const blob = new Blob(ch, { type: mime });

    if (!blob || blob.size === 0) {
        err(T.mic_error_empty);
        busy = false;
        stat('ready', T.ready_status, T.ready_hint);
        stopMediaStream();
        return;
    }

    try {
        const wavBlob = await convertBlobToWav(blob);
        const url = URL.createObjectURL(wavBlob);
        const recording = {
            id: ++pendingRecordingId,
            blob: wavBlob,
            url: url,
            word: W[wi],
            wordIndex: wi,
            attempt: currentAttempt
        };
        addPendingRecording(recording);
        busy = false;
        advanceAfterLocalRecording(recording);
    } catch (e) {
        err('Не удалось подготовить WAV: ' + (e && e.message ? e.message : 'ошибка обработки аудио'));
        busy = false;
        stat('ready', T.ready_status, T.ready_hint);
    } finally {
        stopMediaStream();
    }
}

function writeAscii(view, offset, value) {
    for (let i = 0; i < value.length; i++) {
        view.setUint8(offset + i, value.charCodeAt(i));
    }
}

function audioBufferToWav(buffer) {
    const channels = Math.max(1, buffer.numberOfChannels);
    const sampleRate = buffer.sampleRate;
    const frameCount = buffer.length;
    const bytesPerSample = 2;
    const blockAlign = channels * bytesPerSample;
    const dataSize = frameCount * blockAlign;
    const output = new ArrayBuffer(44 + dataSize);
    const view = new DataView(output);
    const channelData = [];

    for (let channel = 0; channel < channels; channel++) {
        channelData.push(buffer.getChannelData(channel));
    }

    writeAscii(view, 0, 'RIFF');
    view.setUint32(4, 36 + dataSize, true);
    writeAscii(view, 8, 'WAVE');
    writeAscii(view, 12, 'fmt ');
    view.setUint32(16, 16, true);
    view.setUint16(20, 1, true);
    view.setUint16(22, channels, true);
    view.setUint32(24, sampleRate, true);
    view.setUint32(28, sampleRate * blockAlign, true);
    view.setUint16(32, blockAlign, true);
    view.setUint16(34, 16, true);
    writeAscii(view, 36, 'data');
    view.setUint32(40, dataSize, true);

    let offset = 44;
    for (let i = 0; i < frameCount; i++) {
        for (let channel = 0; channel < channels; channel++) {
            const sample = Math.max(-1, Math.min(1, channelData[channel][i] || 0));
            view.setInt16(offset, sample < 0 ? sample * 0x8000 : sample * 0x7fff, true);
            offset += bytesPerSample;
        }
    }

    return output;
}

async function convertBlobToWav(blob) {
    const AudioContextCtor = window.AudioContext || window.webkitAudioContext;
    if (!AudioContextCtor) {
        throw new Error('браузер не поддерживает подготовку WAV');
    }

    const context = new AudioContextCtor();
    try {
        const sourceBuffer = await blob.arrayBuffer();
        const audioBuffer = await context.decodeAudioData(sourceBuffer.slice(0));
        return new Blob([audioBufferToWav(audioBuffer)], { type: 'audio/wav' });
    } finally {
        try { await context.close(); } catch (e) {}
    }
}

function storeUploadedResult(response, wordIndex) {
    const analysis = response.analysis || response;
    const audio = response.audio || {};
    const word = W[wordIndex] || W[wi] || {};
    sessionHistory.unshift({
        word: word.ru || analysis.expected_word || '',
        audio,
        analysis
    });
    return { analysis, audio };
}

async function sendPendingRecordings() {
    if (busy || rec) return;
    if (pendingRecordings.length === 0) {
        err('Сначала запишите хотя бы один голос.');
        return;
    }

    const validation = updateParticipantState();
    if (!validation.ok) {
        err(validation.message);
        return;
    }

    const queue = [...pendingRecordings];
    busy = true;
    const sendButton = $('sendAudioBtn');
    const discardButton = $('discardRecordingBtn');
    if (sendButton) sendButton.disabled = true;
    if (discardButton) discardButton.disabled = true;
    stat('ai', T.analyzing_status, 'Отправляем ' + queue.length + ' записей в историю.');

    let uploaded = 0;
    try {
        await ses();

        for (const recording of queue) {
            if (!pendingRecordings.some((item) => item.id === recording.id)) {
                continue;
            }

            const backendWord = await ensureBackendWord(recording.word);
            const fd = new FormData();
            fd.append('child_id', String(backendChildId));
            fd.append('word_id', String(backendWord.id));
            fd.append('attempt_number', 'x' + Math.min(MAX_ATTEMPTS_PER_WORD, Math.max(1, recording.attempt)));
            fd.append('file', recording.blob, 'speech.wav');

            const response = await apiJson(API.uploadAudio, { method: 'POST', body: fd });
            removePendingRecording(recording.id);
            storeUploadedResult(response, recording.wordIndex);
            uploaded += 1;
            renderHistory();
            renderWordProgress();
            stat('ai', T.analyzing_status, 'Отправлено ' + uploaded + ' из ' + queue.length + ' записей.');
        }

        renderHistory();
        renderWordProgress();
        try {
            await refreshReport();
        } catch (e) {
            console.warn('Report refresh failed', e);
        }

        const nextIndex = nextIncompleteWordIndex(wi);
        if (nextIndex !== -1) {
            wi = nextIndex;
            show();
            stat('ready', T.ready_status, 'Отправлено ' + uploaded + ' записей. Можно продолжать сбор датасета.');
        } else {
            currentAttempt = MAX_ATTEMPTS_PER_WORD;
            updateAttemptText();
            stat('ready', 'Серия завершена', 'Отправлено ' + uploaded + ' записей. Все 15 слов готовы.');
        }

        busy = false;
        renderPendingRecordings();
    } catch (e) {
        const message = e && e.message ? e.message : 'неизвестная ошибка';
        if (message.includes('3 голосовые записи')) {
            try {
                await loadBackendHistory();
                const nextIndex = nextIncompleteWordIndex(wi + 1);
                if (nextIndex !== -1) {
                    wi = nextIndex;
                    currentAttempt = 1;
                    show();
                }
            } catch (refreshError) {
                console.warn('History refresh failed after duplicate attempt', refreshError);
            }
        }
        err('Ошибка: ' + message + (uploaded ? '. Уже отправлено: ' + uploaded + '.' : ''));
        busy = false;
        if (sendButton) sendButton.disabled = false;
        if (discardButton) discardButton.disabled = false;
        renderPendingRecordings();
        stat('ready', 'Готов к записи', pendingRecordings.length ? 'Оставшиеся записи можно отправить еще раз.' : 'Можно продолжать запись.');
    }
}

async function done(a) {
    busy = false;
    const analysis = a.analysis || a;
    const audio = a.audio || {};
    const completedAttempt = parseAttemptNumber(audio.attempt_number) || currentAttempt;
    const sc = analysis.accuracy || analysis.score || analysis.overall_score || 0;

    sessionHistory.unshift({
        word: W[wi].ru,
        audio,
        analysis
    });
    renderHistory();
    renderWordProgress();
    try {
        await refreshReport();
    } catch (e) {
        console.warn('Report refresh failed', e);
    }

    $('wordResultText').textContent = W[wi].ru + ' · попытка ' + completedAttempt + '/' + MAX_ATTEMPTS_PER_WORD + ': ' + sc + '/100'
        + (analysis.risk_level ? ' · ' + analysis.risk_level : '');
    $('wordResult').classList.remove('hidden');

    if (completedAttempt < MAX_ATTEMPTS_PER_WORD) {
        currentAttempt = completedAttempt + 1;
        updateAttemptText();
        renderWordProgress();
        stat('ready', T.ready_status, 'Запишите это же слово еще раз: попытка ' + currentAttempt + ' из ' + MAX_ATTEMPTS_PER_WORD + '.');
        return;
    }

    const nextIndex = nextIncompleteWordIndex(wi + 1);
    if (nextIndex !== -1) {
        stat('ready', T.ready_status, 'Слово "' + W[wi].ru + '" записано 3 раза. Следующее слово...');
        setTimeout(() => {
            wi = nextIndex;
            currentAttempt = 1;
            show();
        }, 1200);
        return;
    }

    currentAttempt = MAX_ATTEMPTS_PER_WORD;
    renderWordProgress();
    stat('ready', 'Серия завершена', 'Все 15 слов записаны по 3 раза. История и общий отчет сохранены ниже.');
}

function skipWord() {
    if (rec && mr && mr.state === 'recording') {
        mr.stop();
        return;
    }
    
    if (busy) return;

    const nextIndex = nextIncompleteWordIndex(wi + 1);
    if (nextIndex !== -1) {
        wi = nextIndex;
        currentAttempt = 1;
        show();
        return;
    }

    if (backendChildId) {
        refreshReport()
            .then(() => stat('ready', 'Серия завершена', 'История и общий отчет сохранены ниже в этой вкладке.'))
            .catch(() => err(T.complete_error));
        return;
    }

    err('Сначала запишите хотя бы одно слово, чтобы backend создал общий отчет.');
}

function playSound() {
    if (!('speechSynthesis' in window)) return;
    const main = LANG === 'kk' ? (W[wi].kk || W[wi].ru) : (LANG === 'en' ? (W[wi].en || W[wi].ru) : W[wi].ru);
    const u = new SpeechSynthesisUtterance(main);
    u.lang = LANG === 'kk' ? 'kk-KZ' : (LANG === 'en' ? 'en-US' : 'ru-RU');
    u.rate = 0.8;
    speechSynthesis.cancel();
    speechSynthesis.speak(u);
}

document.addEventListener('DOMContentLoaded', () => {
    show();
    renderHistory();
    renderWordProgress();
    updateParticipantState();
    bindParticipantFields();
    document.getElementById('recordBtn')?.addEventListener('click', handleRecord);
    document.getElementById('sendAudioBtn')?.addEventListener('click', sendPendingRecordings);
    document.getElementById('discardRecordingBtn')?.addEventListener('click', () => {
        removeLastPendingRecording();
        stat('ready', T.ready_status, T.ready_hint);
    });
    document.getElementById('pendingRecordingList')?.addEventListener('click', (event) => {
        const button = event.target.closest('[data-remove-pending]');
        if (!button || busy || rec) return;
        removePendingRecording(button.dataset.removePending);
        stat('ready', T.ready_status, T.ready_hint);
    });
    document.getElementById('playSoundBtn')?.addEventListener('click', playSound);
    document.getElementById('skipWordBtn')?.addEventListener('click', skipWord);
});
</script>
JS;

require __DIR__ . '/../layouts/footer.php';
?>
