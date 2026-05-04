<?php

declare(strict_types=1);

/**
 * TilDamu.kz - AI Speech Disorder Diagnosis Platform
 * Configuration file for PHP 8.3
 */

// Application settings
define('APP_NAME', 'TilDamu.kz');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION_RU', 'AI диагностика речевых нарушений');
define('APP_DESCRIPTION_KK', 'Сөйлеу бұзылыстарын AI диагностикасы');

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Language handling
$_SESSION['language'] ??= 'ru';

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ru', 'kk'], true)) {
    $_SESSION['language'] = $_GET['lang'];
}

$lang = $_SESSION['language'];

// Translation strings
$translations = [
    'ru' => [
        'home' => 'Главная',
        'diagnosis' => 'Диагностика',
        'results' => 'Результаты',
        'recommendations' => 'Рекомендации',
        'therapist' => 'Для логопеда',
        'lang_switch' => 'Қаз',
        'subtitle' => 'AI диагностика речевых нарушений',
        'footer' => '© 2026 TilDamu.kz. Профессиональная помощь в развитии речи детей.',
    ],
    'kk' => [
        'home' => 'Басты бет',
        'diagnosis' => 'Диагностика',
        'results' => 'Нәтижелер',
        'recommendations' => 'Ұсыныстар',
        'therapist' => 'Логопедке',
        'lang_switch' => 'Рус',
        'subtitle' => 'Сөйлеу бұзылыстарын AI диагностикасы',
        'footer' => '© 2026 TilDamu.kz. Балалардың сөйлеу дамуына кәсіби көмек.',
    ],
];

$t = $translations[$lang];

// Helper: get current page name from URL
function getCurrentPage(): string
{
    $page = basename($_SERVER['SCRIPT_NAME'], '.php');
    return $page === 'index' ? 'home' : $page;
}

// Helper: check if current page matches
function isActivePage(string $page): bool
{
    return getCurrentPage() === $page;
}

// Helper: generate language switch URL
function langSwitchUrl(): string
{
    $currentLang = $_SESSION['language'] ?? 'ru';
    $newLang = $currentLang === 'ru' ? 'kk' : 'ru';
    $currentPage = basename($_SERVER['SCRIPT_NAME']);
    return "{$currentPage}?lang={$newLang}";
}
