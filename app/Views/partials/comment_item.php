<div class="comment-item p-4 bg-white dark:bg-gray-800 rounded-lg shadow mb-3 border border-gray-200 dark:border-gray-700 transform transition duration-300 opacity-0 translate-y-3">
    <div class="flex items-center justify-between mb-2">
        <p class="font-semibold text-blue-600"><?= esc($user) ?></p>
        <small class="text-gray-400 text-xs"><?= esc($time) ?></small>
    </div>
    <p class="text-gray-800 dark:text-gray-200 leading-relaxed"><?= esc($content) ?></p>
</div>

<script>
(() => {
    const newComment = document.querySelector('.comment-item:first-child');
    if (newComment) {
        requestAnimationFrame(() => {
            setTimeout(() => {
                newComment.classList.remove('opacity-0', 'translate-y-3');
                newComment.classList.add('opacity-100', 'translate-y-0');
            }, 50);
        });
    }
})();
</script>
