<div class="comment-item p-4 bg-white dark:bg-gray-800 rounded-lg shadow mb-3 border border-gray-200 dark:border-gray-700 opacity-0 translate-y-2 transition-all duration-300">
    <div class="flex items-center justify-between mb-2">
        <p class="font-semibold text-blue-600"><?= esc($user) ?></p>
        <small class="text-gray-400 text-xs"><?= esc($time) ?></small>
    </div>
    <p class="text-gray-800 dark:text-gray-200 leading-relaxed"><?= esc($content) ?></p>
</div>
