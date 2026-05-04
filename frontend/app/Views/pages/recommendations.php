<?php

declare(strict_types=1);

$exercises ??= [];
$videos ??= [];
$achievements ??= [];
$exerciseStats ??= ['completed' => 0, 'stars' => 0];
$assessmentContext ??= ['childName' => 'Ребенок', 'diagnosis' => '', 'score' => '', 'problemSounds' => [], 'childId' => null];

require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <div class="text-center space-y-3">
        <h1 class="text-4xl font-bold text-gray-800">Персональные рекомендации</h1>
        <p class="text-lg text-gray-600">Упражнения и материалы, подобранные специально для вашего ребенка</p>
    </div>

    <!-- Progress Overview -->
    <div class="grid md:grid-cols-3 gap-6">
        <div class="gradient-card-blue border-0 shadow-lg rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full gradient-icon-blue flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700">Прогресс</h3>
                    <p class="text-sm text-gray-500"><span id="completedCount"><?= (int) ($exerciseStats['completed'] ?? 0) ?></span>/<?= count($exercises) ?> упражнений</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="overallProgress" class="gradient-progress h-2 rounded-full transition-all duration-500" style="width: <?= count($exercises) > 0 ? round(((int) ($exerciseStats['completed'] ?? 0) / count($exercises)) * 100) : 0 ?>%"></div>
            </div>
        </div>

        <div class="border-0 shadow-lg rounded-2xl p-6" style="background: linear-gradient(135deg, #ffffff, #FFF9F0);">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full gradient-icon-orange flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700">Звезды</h3>
                    <p class="text-2xl font-bold text-gray-800" id="totalStars">0</p>
                </div>
            </div>
            <p class="text-sm text-gray-600">Собрано за выполнение упражнений</p>
        </div>

        <div class="gradient-card-purple border-0 shadow-lg rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full gradient-icon-purple flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700">Достижения</h3>
                    <p class="text-2xl font-bold text-gray-800">3</p>
                </div>
            </div>
            <p class="text-sm text-gray-600">Значков получено за успехи</p>
        </div>
    </div>

    <!-- Tabs -->
    <div>
        <div class="flex justify-center mb-6">
            <div class="bg-gray-100 p-1 rounded-2xl inline-flex gap-1">
                <button type="button" data-tab="exercises" id="tab-exercises" class="tab-btn inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium rounded-xl transition-colors bg-white shadow text-gray-800">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    Упражнения
                </button>
                <button type="button" data-tab="videos" id="tab-videos" class="tab-btn inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium rounded-xl transition-colors text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>
                    Видео
                </button>
                <button type="button" data-tab="achievements" id="tab-achievements" class="tab-btn inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium rounded-xl transition-colors text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                    Награды
                </button>
            </div>
        </div>

        <!-- Exercises Tab -->
        <div id="content-exercises" class="tab-content space-y-6">
            <?php if (empty($exercises)): ?>
            <div class="text-center py-12 space-y-4">
                <div class="w-20 h-20 rounded-full gradient-icon-blue flex items-center justify-center mx-auto">
                    <svg class="w-10 h-10 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Упражнений пока нет</h3>
                <p class="text-gray-600">Логопед ещё не добавил упражнения в систему. Пройдите диагностику для персональных рекомендаций.</p>
            </div>
            <?php else: ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($exercises as $exercise): ?>
                <div class="border-0 shadow-lg bg-white rounded-2xl overflow-hidden exercise-card" id="exercise-<?= $exercise['id'] ?>">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-2">
                            <div class="w-12 h-12 rounded-2xl gradient-icon-blue flex items-center justify-center">
                                <span class="text-xl font-bold text-white"><?= $exercise['sound'] ?></span>
                            </div>
                            <span class="exercise-badge hidden inline-flex items-center gap-1 px-3 py-1 text-xs font-medium bg-mint text-white rounded-full">
                                <?= ui_icon('check', 'w-4 h-4') ?> <?= e(tr('recommendations_page.completed')) ?>
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mt-3"><?= $exercise['title'] ?></h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-600"><?= $exercise['description'] ?></p>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="flex items-center gap-1 text-gray-500">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6 3 20 12 6 21 6 3"/></svg>
                                <?= (int) ($exercise['duration_minutes'] ?? 0) ?> мин
                            </span>
                            <span class="inline-block px-2 py-0.5 text-xs border border-gray-200 rounded-full"><?= $exercise['difficulty'] ?></span>
                        </div>
                        <div class="flex items-center gap-2 star-container">
                            <?php for ($i = 0; $i < $exercise['stars']; $i++): ?>
                            <svg class="w-4 h-4 text-gray-300 star-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <?php endfor; ?>
                        </div>
                        <button data-exercise-id="<?= $exercise['id'] ?>" data-exercise-stars="<?= $exercise['stars'] ?>" class="exercise-btn w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium bg-gray-900 text-white rounded-full hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6 3 20 12 6 21 6 3"/></svg>
                            Начать
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Daily Schedule -->
            <div class="border-0 shadow-lg gradient-card-beige rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-semibold flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full gradient-icon-orange flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </span>
                        Рекомендуемый график
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <?php
                    $schedule = [
                        [
                            'icon' => '<svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v3"/><path d="m4.93 10.93 1.41 1.41"/><path d="M2 13h2"/><path d="M20 13h2"/><path d="m19.07 10.93-1.41 1.41"/><path d="M22 22H2"/><path d="m8 6 4-4 4 4"/><path d="M16 18a4 4 0 0 0-8 0"/></svg>',
                            'bg' => 'bg-mint',
                            'time' => 'Утро (8:00)',
                            'desc' => 'Артикуляционная гимнастика - 10 минут'
                        ],
                        [
                            'icon' => '<svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>',
                            'bg' => 'bg-blue-soft',
                            'time' => 'День (14:00)',
                            'desc' => 'Упражнение на проблемный звук - 15 минут'
                        ],
                        [
                            'icon' => '<svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>',
                            'bg' => 'bg-orange-soft',
                            'time' => 'Вечер (19:00)',
                            'desc' => 'Повторение и закрепление - 10 минут'
                        ],
                    ];
                    foreach ($schedule as $item): ?>
                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl">
                        <div class="w-10 h-10 rounded-full <?= $item['bg'] ?> flex items-center justify-center">
                            <?= $item['icon'] ?>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800"><?= $item['time'] ?></p>
                            <p class="text-sm text-gray-600"><?= $item['desc'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Videos Tab -->
        <div id="content-videos" class="tab-content hidden space-y-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($videos as $video): ?>
                <div class="border-0 shadow-lg bg-white rounded-2xl overflow-hidden">
                    <div class="relative aspect-video bg-gradient-to-br from-blue-soft/30 to-mint/30 rounded-t-xl overflow-hidden group cursor-pointer">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center z-20">
                            <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-blue-soft ml-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6 3 20 12 6 21 6 3"/></svg>
                            </div>
                        </div>
                        <span class="absolute top-3 right-3 z-20 px-2 py-1 text-xs font-medium bg-black/60 text-white rounded-full"><?= $video['duration'] ?></span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800"><?= $video['title'] ?></h3>
                        <div class="flex items-center gap-2 mt-2">
                            <a href="<?= e($video['url'] ?? "#") ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-blue-soft hover:bg-blue-50 rounded-full transition-colors no-underline">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
                                <?= e(tr('common.watch')) ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Achievements Tab -->
        <div id="content-achievements" class="tab-content hidden space-y-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($achievements as $ach): ?>
                <div class="border-0 shadow-lg rounded-2xl text-center <?= $ach['unlocked'] ? "bg-gradient-to-br {$ach['bgGradient']}" : 'bg-gray-100 opacity-50' ?>">
                    <div class="p-6 space-y-3">
                        <div class="w-20 h-20 rounded-full <?= $ach['unlocked'] ? $ach['gradient'] : 'bg-gray-300' ?> flex items-center justify-center mx-auto">
                            <?= ui_icon((string) $ach['icon'], 'w-8 h-8 text-white') ?>
                        </div>
                        <h3 class="font-semibold <?= $ach['unlocked'] ? 'text-gray-800' : 'text-gray-600' ?>"><?= $ach['title'] ?></h3>
                        <p class="text-sm <?= $ach['unlocked'] ? 'text-gray-600' : 'text-gray-500' ?>"><?= $ach['desc'] ?></p>
                        <?php if (!$ach['unlocked']): ?>
                        <span class="inline-block px-3 py-1 text-xs border border-gray-300 text-gray-500 rounded-full">Заблокировано</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Contact Therapist CTA -->
    <div class="border-0 shadow-lg gradient-cta rounded-2xl p-8 text-center space-y-4">
        <h2 class="text-2xl font-bold text-white">Нужна помощь логопеда?</h2>
        <p class="text-white/90 max-w-2xl mx-auto">
            Свяжитесь с профессиональным логопедом для индивидуальной консультации и персонального плана коррекции
        </p>
        <a href="tel:<?= e((string) env('CLINIC_PHONE', '+77000000000')) ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-soft font-medium rounded-full hover:bg-white/90 transition-colors no-underline">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.86.32 1.71.59 2.54a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.54-1.16a2 2 0 0 1 2.11-.45c.83.27 1.68.47 2.54.59A2 2 0 0 1 22 16.92z"/></svg>
            Связаться с логопедом
        </a>
    </div>


    <div class="border-0 shadow-lg rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #F0F9FF, #ffffff);">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-semibold flex items-center gap-3">
                <div class="w-10 h-10 rounded-full gradient-icon-blue flex items-center justify-center">
                    <?= ui_icon('robot', 'w-5 h-5 text-white') ?>
                </div>
                <?= e(tr('chat.title')) ?>
            </h3>
            <p class="text-sm text-gray-500 mt-2"><?= e(tr('chat.subtitle')) ?></p>
        </div>
        <div class="p-6 space-y-4">
            <div id="parentAssistantMessages" class="bg-white rounded-2xl p-4 min-h-[180px] max-h-[320px] overflow-y-auto space-y-3">
                <div class="p-3 rounded-2xl bg-gray-50 text-sm text-gray-700"><?= e(tr('chat.welcome')) ?></div>
            </div>
            <div class="flex gap-3 flex-wrap">
                <input id="parentAssistantInput" type="text" class="flex-1 min-w-[220px] px-4 py-3 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/40" placeholder="<?= e(tr('chat.placeholder')) ?>">
                <button id="parentAssistantSendBtn" type="button" class="inline-flex items-center gap-2 px-5 py-3 bg-gray-900 text-white rounded-full text-sm font-medium hover:bg-gray-800 transition-colors"><?= e(tr('common.send')) ?></button>
            </div>
        </div>
    </div>
</div>

<?php
$assessmentContextJson = json_encode($assessmentContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$totalExercises = count($exercises);
$initialCompleted = (int) ($exerciseStats['completed'] ?? 0);
$initialStars = (int) ($exerciseStats['stars'] ?? 0);
$chatFailedReply = json_encode((string) tr('chat.failed_reply'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$chatTemporaryUnavailable = json_encode((string) tr('chat.temporary_unavailable'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$exerciseCompleteApi = json_encode(api_url('exercises/complete'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$parentAssistantApi = json_encode(api_url('parent-assistant/send'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$extraScripts = <<<JS
<script>
const totalExercises = {$totalExercises};
let totalStars = {$initialStars};
let completedExercises = new Set();
const assessmentContext = {$assessmentContextJson};

function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('bg-white', 'shadow', 'text-gray-800');
        el.classList.add('text-gray-500');
    });
    document.getElementById('content-' + tabName).classList.remove('hidden');
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('bg-white', 'shadow', 'text-gray-800');
    activeTab.classList.remove('text-gray-500');
}

async function completeExercise(id, stars) {
    if (completedExercises.has(id)) return;

    const fd = new FormData();
    fd.append('exercise_id', id);
    fd.append('stars', stars);
    if (assessmentContext.childId) fd.append('child_id', assessmentContext.childId);

    const response = await fetch({$exerciseCompleteApi}, { method: 'POST', body: fd });
    const data = await response.json();
    if (!response.ok || !data.ok) {
        throw new Error(data.message || 'Не удалось сохранить выполнение упражнения');
    }

    completedExercises.add(id);
    const stats = data.stats || {};
    totalStars = Number(stats.stars || totalStars + stars);

    const card = document.getElementById('exercise-' + id);
    card.style.background = 'linear-gradient(135deg, #F0FDF4, #ffffff)';

    const badge = card.querySelector('.exercise-badge');
    badge.classList.remove('hidden');

    const btn = card.querySelector('.exercise-btn');
    btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg> Выполнено';
    btn.classList.remove('bg-gray-900', 'hover:bg-gray-800');
    btn.classList.add('border', 'border-gray-200', 'bg-white', 'text-gray-600');
    btn.disabled = true;

    card.querySelectorAll('.star-icon').forEach(star => {
        star.style.fill = '#FDB777';
        star.style.color = '#FDB777';
    });

    const completed = Number(stats.completed || document.getElementById('completedCount').textContent || 0);
    document.getElementById('completedCount').textContent = completed;
    document.getElementById('totalStars').textContent = totalStars;
    document.getElementById('overallProgress').style.width = Math.round((completed / totalExercises) * 100) + '%';
}

function appendMessage(text, type = 'assistant') {
    const wrap = document.getElementById('parentAssistantMessages');
    const node = document.createElement('div');
    node.className = type === 'user'
        ? 'p-3 rounded-2xl bg-gray-900 text-white text-sm ml-auto max-w-[90%]'
        : 'p-3 rounded-2xl bg-gray-50 text-sm text-gray-700 max-w-[90%]';
    node.textContent = text;
    wrap.appendChild(node);
    wrap.scrollTop = wrap.scrollHeight;
}

async function sendParentMessage() {
    const input = document.getElementById('parentAssistantInput');
    const message = input.value.trim();
    if (!message) return;
    appendMessage(message, 'user');
    input.value = '';

    const fd = new FormData();
    fd.append('message', message);
    if (assessmentContext.publicId) fd.append('assessment_id', assessmentContext.publicId);

    try {
        const response = await fetch({$parentAssistantApi}, { method: 'POST', body: fd });
        const data = await response.json();
        if (!response.ok || !data.ok) {
            appendMessage(data.message || {$chatFailedReply});
            return;
        }
        appendMessage(data.reply || {$chatFailedReply});
    } catch (error) {
        appendMessage({$chatTemporaryUnavailable});
    }
}

document.getElementById('parentAssistantInput').addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendParentMessage();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const starsEl = document.getElementById('totalStars');
    if (starsEl) starsEl.textContent = totalStars;

    document.querySelectorAll('[data-tab]').forEach((btn) => {
        btn.addEventListener('click', () => switchTab(btn.dataset.tab));
    });

    document.querySelectorAll('[data-exercise-id]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            try {
                await completeExercise(Number(btn.dataset.exerciseId), Number(btn.dataset.exerciseStars));
            } catch (error) {
                appendMessage(error && error.message ? error.message : {$chatFailedReply});
            }
        });
    });

    document.getElementById('parentAssistantSendBtn')?.addEventListener('click', sendParentMessage);
    switchTab('exercises');
});
</script>
JS;

require __DIR__ . '/../layouts/footer.php';
?>
