<?php

declare(strict_types=1);

// Data comes from ResultController — no demo fallbacks
$report ??= null;
$history ??= [];

require __DIR__ . '/../layouts/header.php';
?>

<?php if (!$report || empty($report['diagnosis'])): ?>
<div class="max-w-3xl mx-auto text-center py-20 space-y-6">
    <div class="w-24 h-24 rounded-full gradient-icon-blue flex items-center justify-center mx-auto">
        <svg class="w-12 h-12 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Результатов пока нет</h1>
    <p class="text-lg text-gray-600">Пройдите AI-диагностику, чтобы получить первый отчёт о речи ребёнка.</p>
    <a href="/diagnosis" class="inline-flex items-center gap-2 px-6 py-3 text-white font-medium rounded-full gradient-cta hover:opacity-90 transition-opacity no-underline">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
        Начать диагностику
    </a>
</div>
<?php else: ?>

<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Результаты диагностики</h1>
            <p class="text-lg text-gray-600 mt-2">
                <?= $report['childName'] ?>, <?= $report['age'] ?> лет • Дата: <?= $report['diagnosisDate'] ?>
            </p>
        </div>
        <button id="printResultsBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-blue-soft text-white rounded-full hover:opacity-90 transition-opacity">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
            Скачать PDF
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid md:grid-cols-3 gap-6">
        <div class="gradient-card-blue border-0 shadow-lg rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-blue-soft flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                </div>
                <h3 class="font-semibold text-gray-700">Общий балл</h3>
            </div>
            <div class="flex items-end gap-2">
                <span class="text-4xl font-bold text-gray-800"><?= $report['overallScore'] ?></span>
                <span class="text-lg text-gray-500 mb-1">/100</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div class="gradient-progress h-2 rounded-full" style="width: <?= $report['overallScore'] ?>%"></div>
            </div>
        </div>

        <div class="gradient-card-green border-0 shadow-lg rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                </div>
                <h3 class="font-semibold text-gray-700">Диагноз</h3>
            </div>
            <p class="text-lg font-semibold text-gray-800"><?= $report['diagnosis'] ?></p>
            <span class="inline-block mt-3 px-3 py-1 text-xs font-medium bg-mint text-white rounded-full">
                Уверенность: <?= $report['confidence'] ?>%
            </span>
        </div>

        <div class="gradient-card-orange border-0 shadow-lg rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-orange-soft flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                </div>
                <h3 class="font-semibold text-gray-700">Требует внимания</h3>
            </div>
            <p class="text-3xl font-bold text-gray-800"><?= count($report['problematicSounds']) ?></p>
            <p class="text-sm text-gray-600 mt-1">проблемных звуков</p>
        </div>
    </div>

    <!-- AI Explanation -->
    <div class="border-0 shadow-lg rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #F0F9FF, #ffffff);">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-semibold flex items-center gap-3">
                <div class="w-10 h-10 rounded-full gradient-icon-blue flex items-center justify-center">
                    <?= ui_icon('robot', 'w-5 h-5 text-white') ?>
                </div>
                <?= e(tr('results_page.ai_explanation')) ?>
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-gray-700 leading-relaxed">
                <?= e($report['summary'] ?? '') ?>
            </p>
            <p class="text-gray-700 leading-relaxed">
                В данном случае наблюдаются трудности с произношением звуков <strong><?= e(implode(', ', array_map(fn($item) => $item['sound'], $report['problematicSounds']))) ?></strong>.
            </p>
            <div class="bg-white rounded-xl p-4 border-l-4 border-mint">
                <p class="text-sm text-gray-700">
                    <span class="inline-flex items-center gap-2"><?= ui_icon('lightbulb', 'w-4 h-4 text-blue-soft') ?><strong><?= e(tr('results_page.good_news')) ?>:</strong></span> <?= e(tr('results_page.good_news_text')) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Problematic Sounds -->
    <div class="border-0 shadow-lg bg-white rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-semibold">Проблемные звуки</h3>
        </div>
        <div class="p-6 space-y-4">
            <?php foreach ($report['problematicSounds'] as $sound): ?>
            <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full gradient-icon-blue flex items-center justify-center">
                            <span class="text-xl font-bold text-white"><?= $sound['sound'] ?></span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Звук "<?= $sound['sound'] ?>"</p>
                            <p class="text-sm text-gray-500">Сложность: <?= $sound['severity'] ?></p>
                        </div>
                    </div>
                    <button class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
                        Слушать примеры
                    </button>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Правильность</span>
                        <span class="font-semibold text-blue-soft"><?= $sound['correct'] ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="gradient-progress h-2 rounded-full" style="width: <?= $sound['correct'] ?>%"></div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Strengths -->
    <div class="border-0 shadow-lg gradient-card-green rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-semibold flex items-center gap-3">
                <svg class="w-6 h-6 text-mint" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                Сильные стороны
            </h3>
        </div>
        <div class="p-6">
            <ul class="space-y-3">
                <?php foreach ($report['strengths'] as $strength): ?>
                <li class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-mint flex items-center justify-center flex-shrink-0 mt-0.5">
                        <?= ui_icon('check', 'w-3.5 h-3.5 text-white') ?>
                    </div>
                    <span class="text-gray-700"><?= $strength ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="border-0 shadow-lg gradient-card-beige rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-semibold flex items-center gap-3">
                <svg class="w-6 h-6 text-orange-soft" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                Рекомендации
            </h3>
        </div>
        <div class="p-6">
            <ul class="space-y-3">
                <?php foreach ($report['recommendations'] as $index => $rec): ?>
                <li class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-orange-soft flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="text-white text-xs"><?= $index + 1 ?></span>
                    </div>
                    <span class="text-gray-700"><?= $rec ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-4 justify-center">
        <a href="recommendations.php" class="inline-flex items-center gap-2 px-6 py-3 text-white font-medium rounded-full gradient-cta hover:opacity-90 transition-opacity no-underline">
            Персональные упражнения
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
        <a href="tel:<?= e((string) env('CLINIC_PHONE', '+77000000000')) ?>" class="inline-flex items-center gap-2 px-6 py-3 text-blue-soft font-medium rounded-full border-2 border-blue-soft hover:bg-blue-50 transition-colors no-underline">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.86.32 1.71.59 2.54a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.54-1.16a2 2 0 0 1 2.11-.45c.83.27 1.68.47 2.54.59A2 2 0 0 1 22 16.92z"/></svg>
            Связаться с логопедом
        </a>
    </div>

    <!-- History -->
    <div class="border-0 shadow-lg bg-white rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-semibold">История тестов</h3>
        </div>
        <div class="p-6 space-y-3">
            <?php foreach ($history as $item): ?>
            <a href="/results.php?assessment=<?= e((string) $item['public_id']) ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors no-underline">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full <?= $item['active'] ? 'bg-blue-soft' : 'bg-gray-300' ?> flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800"><?= $item['date'] ?></p>
                        <p class="text-sm text-gray-500">Общий балл: <?= $item['score'] ?>/100</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
