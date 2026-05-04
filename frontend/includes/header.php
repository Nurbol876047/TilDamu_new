<!DOCTYPE html>
<html lang="<?= $lang === 'kk' ? 'kk' : 'ru' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?> — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mint: '#A8E6CF',
                        'mint-light': '#C5F0DC',
                        'mint-dark': '#7FD8BE',
                        'blue-light': '#A8D8EA',
                        'blue-soft': '#7FB3D5',
                        beige: '#F5E6D3',
                        'beige-light': '#FBF5ED',
                        'purple-soft': '#D8BFD8',
                        'orange-soft': '#FDB777',
                    },
                    borderRadius: {
                        '2xl': '1rem',
                        '3xl': '1.5rem',
                    },
                    animation: {
                        'pulse-soft': 'pulse-soft 2s ease-in-out infinite',
                        'wave': 'wave 1s ease-in-out infinite',
                        'bounce-soft': 'bounce-soft 1s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        'pulse-soft': {
                            '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                            '50%': { opacity: '0.8', transform: 'scale(1.05)' },
                        },
                        'wave': {
                            '0%, 100%': { transform: 'scaleY(0.5)' },
                            '50%': { transform: 'scaleY(1)' },
                        },
                        'bounce-soft': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-hero { background: linear-gradient(135deg, #A8E6CF, #7FB3D5, #D8BFD8); }
        .gradient-cta { background: linear-gradient(135deg, #7FB3D5, #A8E6CF); }
        .gradient-icon-blue { background: linear-gradient(135deg, #7FB3D5, #A8E6CF); }
        .gradient-icon-green { background: linear-gradient(135deg, #A8E6CF, #7FD8BE); }
        .gradient-icon-orange { background: linear-gradient(135deg, #FDB777, #F5E6D3); }
        .gradient-icon-purple { background: linear-gradient(135deg, #D8BFD8, #C4A5C4); }
        .gradient-card-blue { background: linear-gradient(135deg, #ffffff, #F0F9FF); }
        .gradient-card-green { background: linear-gradient(135deg, #ffffff, #F0FDF4); }
        .gradient-card-orange { background: linear-gradient(135deg, #ffffff, #FEF3F2); }
        .gradient-card-purple { background: linear-gradient(135deg, #ffffff, #FAF5FF); }
        .gradient-card-beige { background: linear-gradient(135deg, #FBF5ED, #ffffff); }
        .gradient-bg { background: linear-gradient(135deg, #FAFBFC, #F7FAFC, #EDF7F6); }
        .gradient-progress { background: linear-gradient(90deg, #7FB3D5, #A8E6CF); }
        .gradient-record-btn { background: linear-gradient(135deg, #7FB3D5, #A8E6CF); }
        .gradient-record-btn-active { background: linear-gradient(135deg, #FC8181, #F56565); }
    </style>
    <?php if (isset($extraHead)) echo $extraHead; ?>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="index.php" class="flex items-center gap-3 no-underline">
                    <div class="w-12 h-12 rounded-2xl gradient-icon-blue flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-800"><?= APP_NAME ?></h1>
                        <p class="text-sm text-gray-500"><?= $t['subtitle'] ?></p>
                    </div>
                </a>
                <a href="<?= langSwitchUrl() ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-gray-200 rounded-full hover:bg-gray-50 transition-colors text-gray-700 no-underline">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    <?= $t['lang_switch'] ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-white/60 backdrop-blur-sm border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-2 overflow-x-auto py-3">
                <?php
                $navItems = [
                    ['page' => 'home', 'href' => 'index.php', 'label' => $t['home'], 'icon' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                    ['page' => 'diagnosis', 'href' => 'diagnosis.php', 'label' => $t['diagnosis'], 'icon' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>'],
                    ['page' => 'results', 'href' => 'results.php', 'label' => $t['results'], 'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>'],
                    ['page' => 'recommendations', 'href' => 'recommendations.php', 'label' => $t['recommendations'], 'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>'],
                    ['page' => 'therapist', 'href' => 'therapist.php', 'label' => $t['therapist'], 'icon' => '<path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6 6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/>'],
                ];
                foreach ($navItems as $item):
                    $active = isActivePage($item['page']);
                    $btnClass = $active
                        ? 'bg-gray-900 text-white'
                        : 'text-gray-600 hover:bg-gray-100';
                ?>
                <a href="<?= $item['href'] ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-colors no-underline <?= $btnClass ?>">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $item['icon'] ?></svg>
                    <?= $item['label'] ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
