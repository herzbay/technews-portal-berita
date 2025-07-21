<?php $commentIdEsc = esc($commentId ?? 'temp'); ?>
<div id="comment-<?= $commentIdEsc ?>" 
     class="comment-item relative p-4 bg-white dark:bg-gray-800 rounded-lg shadow mb-3 border border-gray-200 dark:border-gray-700 opacity-0 translate-y-3 transition-all duration-300">
    
    <!-- Header Komentar -->
    <div class="flex items-center justify-between mb-2">
        <p class="font-semibold text-blue-600"><?= esc($user) ?></p>
        <div class="flex items-center gap-3 text-gray-400 text-xs">
            <small><?= esc($time) ?></small>
            <?php if (session()->get('logged_in') && (session()->get('role') === 'admin' || session()->get('user_id') == ($userId ?? 0))): ?>
                <button 
                    type="button"
                    hx-delete="/comments/delete/<?= $commentId ?? 0 ?>"
                    hx-swap="none"
                    hx-confirm="Yakin hapus komentar ini?"
                    class="text-red-500 hover:text-red-700 transition focus:outline-none"
                    aria-label="Hapus komentar">
                    🗑
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Isi Komentar -->
    <p class="text-gray-800 dark:text-gray-200 leading-relaxed"><?= esc($content) ?></p>
</div>

<script>
// ✅ Jalankan animasi fade-in setelah elemen dimasukkan ke DOM
(() => {
    const newComment = document.getElementById('comment-<?= $commentIdEsc ?>');
    if (newComment) {
        requestAnimationFrame(() => {
            newComment.classList.remove('opacity-0', 'translate-y-3');
            newComment.classList.add('opacity-100', 'translate-y-0');
        });
    }
})();
</script>
