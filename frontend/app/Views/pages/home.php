<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/header.php';
?>

<div class="space-y-12">
    <!-- Hero Section -->
    <section class="relative overflow-hidden rounded-3xl gradient-hero p-8 md:p-12">
        <div class="relative z-10 grid md:grid-cols-2 gap-8 items-center">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full">
                    <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/></svg>
                    <span class="text-white text-sm">Powered by AI Technology</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                    Помогаем детям говорить уверенно
                </h1>
                <p class="text-xl text-white/90">
                    Современная AI-диагностика речевых нарушений у детей 3-10 лет с персонализированными рекомендациями логопедов
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="diagnosis.php" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-soft font-medium rounded-full hover:bg-white/90 transition-colors no-underline">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                        Начать диагностику
                    </a>
                    <a href="results.php" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-sm text-white border border-white/30 font-medium rounded-full hover:bg-white/20 transition-colors no-underline">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                        История результатов
                    </a>
                </div>
            </div>
            <div class="relative">
                <!-- Child Illustration SVG -->
                <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-md mx-auto h-auto">
                    <circle cx="200" cy="200" r="180" fill="url(#gradient1)"/>
                    <circle cx="200" cy="160" r="60" fill="#FDB777"/>
                    <path d="M 140 160 Q 140 100 200 100 Q 260 100 260 160" fill="#5D4037"/>
                    <circle cx="180" cy="155" r="8" fill="#2D3748"/>
                    <circle cx="220" cy="155" r="8" fill="#2D3748"/>
                    <circle cx="182" cy="153" r="3" fill="white"/>
                    <circle cx="222" cy="153" r="3" fill="white"/>
                    <path d="M 175 175 Q 200 190 225 175" stroke="#2D3748" stroke-width="4" stroke-linecap="round" fill="none"/>
                    <rect x="160" y="220" width="80" height="100" rx="15" fill="#7FB3D5"/>
                    <rect x="130" y="240" width="30" height="60" rx="15" fill="#FDB777"/>
                    <rect x="240" y="240" width="30" height="60" rx="15" fill="#FDB777"/>
                    <g class="animate-pulse">
                        <path d="M 250 140 Q 270 140 280 130" stroke="#A8E6CF" stroke-width="3" stroke-linecap="round" fill="none"/>
                        <path d="M 260 140 Q 285 140 300 125" stroke="#A8E6CF" stroke-width="3" stroke-linecap="round" fill="none"/>
                    </g>
                    <g class="animate-bounce-soft">
                        <path d="M 100 100 L 105 110 L 115 110 L 107 116 L 110 126 L 100 120 L 90 126 L 93 116 L 85 110 L 95 110 Z" fill="#FDB777"/>
                    </g>
                    <g class="animate-float">
                        <path d="M 320 180 L 325 190 L 335 190 L 327 196 L 330 206 L 320 200 L 310 206 L 313 196 L 305 190 L 315 190 Z" fill="#D8BFD8"/>
                    </g>
                    <defs>
                        <radialGradient id="gradient1">
                            <stop offset="0%" stop-color="#C5F0DC"/>
                            <stop offset="100%" stop-color="#A8D8EA"/>
                        </radialGradient>
                    </defs>
                </svg>
                <!-- Floating stat cards -->
                <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl p-4 shadow-lg hidden md:block">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-mint flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800"><?= (int) ($platformStats['totalChildren'] ?? 0) ?></p>
                            <p class="text-sm text-gray-500">Детей в системе</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white rounded-2xl p-4 shadow-lg hidden md:block">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-soft flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800"><?= (int) ($platformStats['totalAssessments'] ?? 0) ?></p>
                            <p class="text-sm text-gray-500">Диагностик проведено</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="space-y-6">
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-bold text-gray-800">Что мы предлагаем</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Комплексная платформа для диагностики и коррекции речевых нарушений
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $features = [
                [
                    'href' => 'diagnosis.php',
                    'gradient' => 'gradient-card-blue',
                    'iconGradient' => 'gradient-icon-blue',
                    'icon' => '<path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/>',
                    'title' => 'AI Диагностика',
                    'desc' => 'Точная диагностика речевых нарушений с помощью искусственного интеллекта',
                    'linkColor' => 'text-blue-soft',
                    'linkText' => 'Начать →',
                ],
                [
                    'href' => 'results.php',
                    'gradient' => 'gradient-card-green',
                    'iconGradient' => 'gradient-icon-green',
                    'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>',
                    'title' => 'Результаты',
                    'desc' => 'Подробные отчеты о прогрессе с визуализацией и рекомендациями',
                    'linkColor' => 'text-mint',
                    'linkText' => 'Посмотреть →',
                ],
                [
                    'href' => 'recommendations.php',
                    'gradient' => 'gradient-card-orange',
                    'iconGradient' => 'gradient-icon-orange',
                    'icon' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
                    'title' => 'Упражнения',
                    'desc' => 'Персонализированные упражнения и игры для развития речи',
                    'linkColor' => 'text-orange-soft',
                    'linkText' => 'Попробовать →',
                ],
                [
                    'href' => 'therapist.php',
                    'gradient' => 'gradient-card-purple',
                    'iconGradient' => 'gradient-icon-purple',
                    'icon' => '<path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6 6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/>',
                    'title' => 'Панель логопеда',
                    'desc' => 'Инструменты для специалистов по речевой терапии',
                    'linkColor' => 'text-purple-soft',
                    'linkText' => 'Открыть →',
                ],
            ];

            foreach ($features as $f): ?>
            <a href="<?= $f['href'] ?>" class="block no-underline group">
                <div class="<?= $f['gradient'] ?> border-0 shadow-lg hover:shadow-xl transition-shadow cursor-pointer h-full rounded-2xl p-6 space-y-4">
                    <div class="w-14 h-14 rounded-2xl <?= $f['iconGradient'] ?> flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $f['icon'] ?></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800"><?= $f['title'] ?></h3>
                    <p class="text-gray-600"><?= $f['desc'] ?></p>
                    <div class="flex items-center gap-2 <?= $f['linkColor'] ?>">
                        <span class="text-sm"><?= $f['linkText'] ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- How It Works -->
    <section class="bg-white rounded-3xl p-8 md:p-12 shadow-lg">
        <div class="text-center space-y-3 mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Как это работает</h2>
            <p class="text-lg text-gray-600">Простой процесс от диагностики до результатов</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $steps = [
                ['num' => '1', 'gradient' => 'gradient-icon-blue', 'title' => 'Запись голоса', 'desc' => 'Ребенок повторяет простые слова и фразы, AI анализирует произношение в режиме реального времени'],
                ['num' => '2', 'gradient' => 'gradient-icon-green', 'title' => 'Получение результатов', 'desc' => 'AI определяет проблемные звуки и выдает диагноз с уровнем уверенности и подробными объяснениями'],
                ['num' => '3', 'gradient' => 'gradient-icon-orange', 'title' => 'План коррекции', 'desc' => 'Получите персональные упражнения, видео-примеры и связь с профессиональным логопедом'],
            ];
            foreach ($steps as $step): ?>
            <div class="text-center space-y-4">
                <div class="w-16 h-16 rounded-full <?= $step['gradient'] ?> flex items-center justify-center mx-auto">
                    <span class="text-2xl font-bold text-white"><?= $step['num'] ?></span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800"><?= $step['title'] ?></h3>
                <p class="text-gray-600"><?= $step['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="grid md:grid-cols-2 gap-8 items-center">
        <div class="space-y-6">
            <div class="inline-flex items-center gap-2 bg-mint/20 px-4 py-2 rounded-full">
                <svg class="w-4 h-4 text-mint" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                <span class="text-gray-800 text-sm">Безопасность и конфиденциальность</span>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">
                Доверенная платформа для родителей и специалистов
            </h2>
            <p class="text-lg text-gray-600">
                TilDamu.kz разработана в сотрудничестве с ведущими логопедами Казахстана. Мы используем передовые технологии AI для точной диагностики, сохраняя конфиденциальность и безопасность данных.
            </p>
            <ul class="space-y-3">
                <?php
                $trustItems = [
                    'Сертифицированные методики диагностики',
                    'Защита персональных данных по международным стандартам',
                    'Консультации с квалифицированными логопедами',
                ];
                foreach ($trustItems as $item): ?>
                <li class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-mint flex items-center justify-center flex-shrink-0 mt-0.5">
                        <?= ui_icon('check', 'w-3.5 h-3.5 text-white') ?>
                    </div>
                    <span class="text-gray-700"><?= $item ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-2xl">
            <img src="https://images.unsplash.com/photo-1605627079912-97c3810a11a4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb2xvcmZ1bCUyMGNoaWxkcmVuJTIwbGVhcm5pbmclMjBoYXBweXxlbnwxfHx8fDE3NzA4Mjk1NDB8MA&ixlib=rb-4.1.0&q=80&w=1080"
                 alt="Children learning" class="w-full h-auto" loading="lazy">
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-cta rounded-3xl p-8 md:p-12 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
            Готовы начать путь к уверенной речи?
        </h2>
        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Присоединяйтесь к тысячам семей, которые уже используют TilDamu.kz для развития речи своих детей
        </p>
        <a href="diagnosis.php" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-blue-soft font-medium rounded-full hover:bg-white/90 transition-colors no-underline">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
            Начать бесплатно
        </a>
    </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
