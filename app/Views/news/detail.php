<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- ✅ Wrapper Berita -->
<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 animate-fadeIn">
    <?php if (!empty($news['image_url'])): ?>
        <img src="<?= esc($news['image_url']) ?>" alt="<?= esc($news['title']) ?>"
             class="w-full h-64 object-cover rounded-lg mb-4 shadow-lg hover:scale-105 transition-transform duration-300">
    <?php endif; ?>

    <!-- ✅ Judul & Meta -->
    <h1 class="text-3xl font-bold mb-3 text-blue-600"><?= esc($news['title']) ?></h1>
    <p class="text-gray-500 text-sm mb-5 flex items-center gap-2">
        <span><?= date('d M Y', strtotime($news['created_at'])) ?></span> •
        <span class="font-semibold"><?= esc($news['category']) ?></span>
    </p>

    <!-- ✅ Isi Konten -->
    <div class="prose dark:prose-invert max-w-none leading-relaxed text-gray-800 dark:text-gray-200">
        <?= $news['content'] ?>
    </div>
</div>

<!-- ✅ Tombol Aksi -->
<div class="max-w-4xl mx-auto flex flex-wrap items-center gap-4 mb-8">
    <!-- ✅ Tombol Like -->
    <button id="likeBtn"
        hx-post="/like/<?= $news['id'] ?>" hx-swap="none"
        class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition transform active:scale-95 focus:ring-2 focus:ring-blue-400">
        <span id="likeIcon" class="text-xl"><?= ($isLiked ?? false) ? '❤️' : '🤍' ?></span>
        <span class="hidden sm:inline">Suka</span>
        (<span id="likeCount"><?= esc($likeCount ?? 0) ?></span>)
    </button>

    <!-- ✅ Tombol Share -->
    <button onclick="shareNews()"
        class="flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition transform active:scale-95 focus:ring-2 focus:ring-green-400">
        🔗 <span class="hidden sm:inline">Bagikan</span>
    </button>
</div>

<!-- ✅ Komentar -->
<div class="max-w-4xl mx-auto bg-gray-100 dark:bg-gray-700 rounded-xl p-6 shadow-lg">
    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100">💬 Komentar</h2>

    <!-- ✅ Form Komentar -->
    <form hx-post="/comments/add/<?= $news['id'] ?>"
          hx-target="#comment-list"
          hx-swap="none"
          hx-indicator="#loading-indicator"
          hx-on="htmx:afterRequest: this.reset()"
          class="mb-6 space-y-3">

        <textarea name="content" placeholder="Tulis komentar Anda..." required
            class="w-full p-3 rounded-lg border dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
        <div class="flex items-center gap-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow font-semibold">
                Kirim
            </button>
            <!-- ✅ Loading Spinner -->
            <div id="loading-indicator" class="htmx-indicator hidden">
                <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </div>
        </div>
    </form>

    <!-- ✅ Daftar Komentar -->
    <div id="comment-list"
         hx-get="/comments/list/<?= $news['id'] ?>"
         hx-trigger="load"
         class="space-y-3">
        <p class="text-gray-500 italic">Memuat komentar...</p>
    </div>
</div>

<!-- ✅ Script Share & Like Animation -->
<script>
function shareNews() {
    if (navigator.share) {
        navigator.share({
            title: '<?= esc($news['title']) ?>',
            text: 'Baca berita menarik di NEWSTECHLY',
            url: window.location.href
        }).then(() => {
            fetch('/share/<?= $news['id'] ?>', { 
                method: 'POST', 
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            }).catch(() => showToast('Gagal mencatat share', 'error'));
        }).catch(() => showToast('Gagal membagikan berita', 'error'));
    } else {
        navigator.clipboard.writeText(window.location.href)
            .then(() => showToast('Link disalin ke clipboard!', 'success'))
            .catch(() => showToast('Gagal menyalin link', 'error'));
    }
}

// ✅ Animasi Like Setelah Request
document.addEventListener('htmx:afterRequest', function(evt) {
    if (!evt.detail.xhr.response) return;
    try {
        const data = JSON.parse(evt.detail.xhr.response);

        // Update ikon & jumlah like jika status tersedia
        if (data.status && typeof data.count !== 'undefined') {
            document.getElementById('likeIcon').textContent = (data.status === 'liked') ? '❤️' : '🤍';
            document.getElementById('likeCount').textContent = data.count;

            // Animasi ikon
            const likeIcon = document.getElementById('likeIcon');
            likeIcon.classList.add('scale-125', 'transition', 'duration-300');
            setTimeout(() => likeIcon.classList.remove('scale-125'), 300);
        }
    } catch (e) {
        console.error('Gagal parse response:', e);
    }
});
</script>

<?= $this->endSection() ?>
