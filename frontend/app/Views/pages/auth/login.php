<?php

declare(strict_types=1);

$error ??= null;
$old_email ??= '';
$hideNav = true;

require __DIR__ . '/../../layouts/header.php';
?>

<div class="min-h-[75vh] flex items-center justify-center py-12">
    <div class="w-full max-w-md space-y-8">
        <div class="text-center space-y-3">
            <div class="w-20 h-20 rounded-3xl gradient-icon-blue flex items-center justify-center mx-auto shadow-lg">
                <svg class="w-10 h-10 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800"><?= e(tr('auth.login_title')) ?></h1>
            <p class="text-gray-500"><?= e(tr('auth.login_subtitle')) ?></p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
            <p class="text-sm text-red-600 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                <?= e($error) ?>
            </p>
        </div>
        <?php endif; ?>

        <form method="POST" action="/login" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl p-8 space-y-6">
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700"><?= e(tr('auth.avatar_title')) ?> <span class="text-gray-400">(<?= e(tr('common.optional')) ?>)</span></label>
                <div class="flex items-center gap-4 rounded-2xl border border-dashed border-gray-300 p-4 bg-gray-50">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <img id="loginAvatarPreview" src="" alt="preview" class="hidden w-full h-full object-cover">
                        <div id="loginAvatarPlaceholder" class="text-gray-400">
                            <?= ui_icon('camera-plus', 'w-8 h-8') ?>
                        </div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-medium text-gray-700 cursor-pointer hover:bg-gray-100">
                            <?= ui_icon('camera-plus', 'w-4 h-4') ?>
                            <span><?= e(tr('auth.upload_avatar')) ?></span>
                            <input id="loginAvatarInput" type="file" name="avatar" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">
                        </label>
                        <p class="text-xs text-gray-500"><?= e(tr('auth.avatar_change_hint')) ?></p>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700"><?= e(tr('common.email')) ?></label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <input type="email" name="email" value="<?= e($old_email) ?>" required placeholder="example@mail.com" class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50 focus:border-blue-soft transition-colors">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700"><?= e(tr('common.password')) ?></label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" name="password" required minlength="6" placeholder="<?= e(tr('auth.password_min')) ?>" class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50 focus:border-blue-soft transition-colors">
                </div>
            </div>

            <button type="submit" class="w-full py-3 text-white font-medium rounded-2xl gradient-cta hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                <?= e(tr('common.login')) ?>
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            <?= e(tr('auth.no_account')) ?>
            <a href="/register" class="text-blue-soft font-medium hover:underline"><?= e(tr('common.register')) ?></a>
        </p>
    </div>
</div>

<?php
$extraScripts = <<<JS
<script>
const loginAvatarInput = document.getElementById('loginAvatarInput');
const loginAvatarPreview = document.getElementById('loginAvatarPreview');
const loginAvatarPlaceholder = document.getElementById('loginAvatarPlaceholder');
if (loginAvatarInput) {
    loginAvatarInput.addEventListener('change', (event) => {
        const [file] = event.target.files || [];
        if (!file) {
            loginAvatarPreview.classList.add('hidden');
            loginAvatarPlaceholder.classList.remove('hidden');
            loginAvatarPreview.src = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            loginAvatarPreview.src = e.target?.result || '';
            loginAvatarPreview.classList.remove('hidden');
            loginAvatarPlaceholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
}
</script>
JS;
require __DIR__ . '/../../layouts/footer.php';
?>
