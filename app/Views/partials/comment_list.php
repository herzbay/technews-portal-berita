<div id="comment-container" class="relative">
    <!-- ✅ Loading Spinner Overlay -->
    <div id="comment-loading" class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-700 bg-opacity-80 z-10 hidden">
        <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $c): ?>
            <div class="comment-item relative p-4 bg-white dark:bg-gray-800 rounded-lg shadow mb-3 border border-gray-200 dark:border-gray-700 transition hover:shadow-lg" id="comment-<?= esc($c['id']) ?>">
                <div class="flex items-center justify-between mb-2">
                    <p class="font-semibold text-blue-600"><?= esc($c['username'] ?? 'Anonim') ?></p>
                    <div class="flex items-center gap-3 text-gray-400 text-xs">
                        <small><?= date('d M Y H:i', strtotime($c['created_at'])) ?></small>
                        <?php if (session()->get('logged_in') && (session()->get('role') === 'admin' || session()->get('user_id') == $c['user_id'])): ?>
                            <button 
                                hx-delete="/comments/delete/<?= $c['id'] ?>"
                                hx-swap="none"
                                hx-confirm="Yakin hapus komentar ini?"
                                class="text-red-500 hover:text-red-700 transition"
                                aria-label="Hapus komentar">
                                🗑
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="text-gray-800 dark:text-gray-200 leading-relaxed"><?= esc($c['content']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-gray-500 italic text-center py-4">Belum ada komentar. Jadilah yang pertama!</p>
    <?php endif; ?>
</div>

<script>
// ✅ Spinner saat komentar dimuat dengan HTMX
document.addEventListener('htmx:beforeRequest', function(evt) {
    if (evt.target.id === 'comment-list') {
        document.getElementById('comment-loading').classList.remove('hidden');
    }
});

document.addEventListener('htmx:afterSwap', function(evt) {
    if (evt.target.id === 'comment-list') {
        document.getElementById('comment-loading').classList.add('hidden');
        animateComments();
    }
});

// ✅ Animasi muncul untuk semua komentar
function animateComments() {
    const comments = document.querySelectorAll('#comment-container .comment-item');
    comments.forEach((comment, index) => {
        comment.style.opacity = '0';
        comment.style.transform = 'translateY(10px)';
        setTimeout(() => {
            comment.style.transition = 'all 0.3s ease';
            comment.style.opacity = '1';
            comment.style.transform = 'translateY(0)';
        }, index * 70);
    });
}
</script>