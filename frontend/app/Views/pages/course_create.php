<?php
declare(strict_types=1);
$error ??= null;
$old ??= [];
$hideNav = false;
require __DIR__ . '/../layouts/header.php';
?>
<div class="max-w-3xl mx-auto space-y-8">
    <div>
        <a href="/courses.php" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 no-underline mb-4">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            <?= e(tr('courses_page.title', 'Курсы')) ?>
        </a>
        <h1 class="text-3xl font-bold text-gray-800"><?= e(tr('courses_page.create', 'Создать курс')) ?></h1>
    </div>

    <?php if ($error): ?>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
        <p class="text-sm text-red-600"><?= e($error) ?></p>
    </div>
    <?php endif; ?>

    <form method="POST" action="/course-store.php" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl p-8 space-y-6">

        <!-- Title -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700"><?= e(tr('courses_page.form_title', 'Название курса')) ?> <span class="text-red-400">*</span></label>
            <input type="text" name="title" value="<?= e($old['title'] ?? '') ?>" required
                   placeholder="<?= e(tr('courses_page.form_title', 'Название курса')) ?>"
                   class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50 focus:border-blue-soft">
        </div>

        <!-- Description -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700"><?= e(tr('courses_page.form_desc', 'Описание')) ?></label>
            <textarea name="description" rows="3" placeholder="Краткое описание курса..."
                      class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50 resize-none"><?= e($old['description'] ?? '') ?></textarea>
        </div>

        <!-- Content (rich text) -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700"><?= e(tr('courses_page.form_content', 'Содержание курса')) ?></label>
            <textarea name="content" rows="10" placeholder="Подробное содержание курса: упражнения, инструкции, рекомендации..."
                      class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50 resize-y"><?= e($old['content'] ?? '') ?></textarea>
        </div>

        <!-- Settings row -->
        <div class="grid md:grid-cols-3 gap-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Целевые звуки</label>
                <input type="text" name="target_sounds" value="<?= e($old['target_sounds'] ?? '') ?>" placeholder="Р, Л, Ш"
                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Сложность</label>
                <select name="difficulty" class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50 bg-white">
                    <option value="Легко" <?= ($old['difficulty'] ?? '') === 'Легко' ? 'selected' : '' ?>>Легко</option>
                    <option value="Средне" <?= ($old['difficulty'] ?? '') === 'Средне' ? 'selected' : '' ?>>Средне</option>
                    <option value="Сложно" <?= ($old['difficulty'] ?? '') === 'Сложно' ? 'selected' : '' ?>>Сложно</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Количество уроков</label>
                <input type="number" name="lessons_count" min="0" value="<?= e((string) ($old['lessons_count'] ?? '0')) ?>"
                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50">
            </div>
        </div>

        <!-- Age range -->
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Возраст от</label>
                <input type="number" name="age_from" min="2" max="18" value="<?= e((string) ($old['age_from'] ?? '3')) ?>"
                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Возраст до</label>
                <input type="number" name="age_to" min="2" max="18" value="<?= e((string) ($old['age_to'] ?? '10')) ?>"
                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-soft/50">
            </div>
        </div>

        <!-- Media Upload -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700"><?= e(tr('courses_page.form_media', 'Медиа-файлы')) ?></label>
            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:border-blue-soft/50 transition-colors" id="dropZone">
                <input type="file" name="media[]" multiple accept="image/*,audio/*,video/*,.pdf,.doc,.docx" id="mediaInput" class="hidden">
                <div class="space-y-3">
                    <div class="w-16 h-16 rounded-full bg-blue-soft/10 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-blue-soft" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    </div>
                    <p class="text-sm text-gray-600">
                        <button type="button" id="openMediaPickerBtn" class="text-blue-soft font-medium hover:underline">Выберите файлы</button>
                        или перетащите сюда
                    </p>
                    <p class="text-xs text-gray-400"><?= e(tr('courses_page.form_media_hint', 'Изображения, аудио или видео (макс. 50 МБ каждый)')) ?></p>
                </div>
            </div>
            <div id="filePreview" class="grid grid-cols-2 md:grid-cols-3 gap-3"></div>
        </div>

        <!-- Publish -->
        <div class="flex items-center gap-3 p-4 bg-mint/5 border border-mint/20 rounded-2xl">
            <input type="checkbox" name="is_published" value="1" id="isPublished" checked
                   class="w-5 h-5 rounded border-gray-300 text-blue-soft focus:ring-blue-soft/50">
            <label for="isPublished" class="text-sm text-gray-700">Опубликовать курс сразу</label>
        </div>

        <button type="submit" class="w-full py-3 text-white font-medium rounded-2xl gradient-cta hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            <?= e(tr('courses_page.form_submit', 'Создать курс')) ?>
        </button>
    </form>
</div>

<?php
$extraScripts = <<<'JS'
<script>
function previewFiles(input) {
    const preview = document.getElementById('filePreview');
    if (!preview) return;
    preview.innerHTML = '';
    if (!input.files) return;
    Array.from(input.files).forEach((file) => {
        const div = document.createElement('div');
        div.className = 'bg-gray-50 rounded-xl p-3 flex items-center gap-3 border border-gray-100';
        const isImg = file.type.startsWith('image/');
        const isVid = file.type.startsWith('video/');
        const isAud = file.type.startsWith('audio/');
        let icon = '📄';
        if (isImg) icon = '🖼️';
        if (isVid) icon = '🎬';
        if (isAud) icon = '🎵';
        const size = (file.size / 1024 / 1024).toFixed(1);
        div.innerHTML = `<span class="text-2xl">${icon}</span><div class="min-w-0 flex-1"><p class="text-xs font-medium text-gray-700 truncate">${file.name}</p><p class="text-xs text-gray-400">${size} MB</p></div>`;
        preview.appendChild(div);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const dropZone = document.getElementById('dropZone');
    const mediaInput = document.getElementById('mediaInput');
    const openMediaPickerBtn = document.getElementById('openMediaPickerBtn');

    openMediaPickerBtn?.addEventListener('click', () => mediaInput?.click());
    mediaInput?.addEventListener('change', () => previewFiles(mediaInput));
    dropZone?.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-blue-soft'); });
    dropZone?.addEventListener('dragleave', () => dropZone.classList.remove('border-blue-soft'));
    dropZone?.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-soft');
        if (mediaInput) {
            mediaInput.files = e.dataTransfer.files;
            previewFiles(mediaInput);
        }
    });
});
</script>
JS;
require __DIR__ . '/../layouts/footer.php';
?>
