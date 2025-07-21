<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- ✅ Wrapper Berita -->
<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 animate-fadeIn">
    <?php if (!empty($news['image_url'])): ?>
        <img src="<?= esc($news['image_url']) ?>" alt="<?= esc($news['title']) ?>"
             class="w-full h-64 object-cover rounded-lg mb-4 shadow-lg hover:scale-105 transition-transform duration-300">
    <?php endif; ?>

    <h1 class="text-3xl font-bold mb-3 text-blue-600"><?= esc($news['title']) ?></h1>
    <p class="text-gray-500 text-sm mb-5 flex items-center gap-2">
        <span><?= date('d M Y', strtotime($news['created_at'])) ?></span> •
        <span class="font-semibold"><?= esc($news['category']) ?></span>
    </p>

    <div class="prose dark:prose-invert max-w-none leading-relaxed text-gray-800 dark:text-gray-200">
        <?= $news['content'] ?>
    </div>
</div>

<!-- ✅ Tombol Aksi -->
<div class="max-w-4xl mx-auto flex flex-wrap items-center gap-4 mb-8">
    <!-- ✅ Like -->
    <button id="likeBtn"
        onclick="toggleLike(<?= $news['id'] ?>)"
        class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition active:scale-95 focus:ring-2 focus:ring-blue-400">
        <span id="likeIcon" class="text-xl"><?= ($isLiked ?? false) ? '❤️' : '🤍' ?></span>
        <span class="hidden sm:inline">Suka</span>
        (<span id="likeCount"><?= esc($likeCount ?? 0) ?></span>)
    </button>

    <!-- ✅ Share -->
    <button onclick="shareNews()"
        class="flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition active:scale-95 focus:ring-2 focus:ring-green-400">
        🔗 <span class="hidden sm:inline">Bagikan</span>
    </button>
</div>

<!-- ✅ Komentar -->
<div class="max-w-4xl mx-auto bg-gray-100 dark:bg-gray-700 rounded-xl p-6 shadow-lg">
    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100">💬 Komentar</h2>

    <!-- ✅ Form -->
    <form id="commentForm" class="mb-6 space-y-3" onsubmit="return handleCommentSubmit(event)">
        <textarea name="content" placeholder="Tulis komentar Anda..." required
            class="w-full p-3 rounded-lg border dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
        <div class="flex items-center gap-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow font-semibold">
                Kirim
            </button>
            <div id="loading-indicator" class="hidden">
                <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </div>
        </div>
    </form>

    <!-- ✅ Daftar Komentar -->
    <div id="comment-list" class="space-y-3">
        <p class="text-gray-500 italic">Memuat komentar...</p>
    </div>
</div>

<!-- ✅ Scripts -->
<script>
// ✅ Load komentar awal via Fetch
document.addEventListener('DOMContentLoaded', loadComments);

function loadComments() {
    fetch('/comments/list/<?= $news['id'] ?>')
        .then(res => res.text())
        .then(html => document.querySelector('#comment-list').innerHTML = html)
        .catch(() => document.querySelector('#comment-list').innerHTML = '<p class="text-red-500">Gagal memuat komentar.</p>');
}

// ✅ Toggle Like
function toggleLike(newsId) {
    fetch(`/like/${newsId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            showToast(data.error, 'error');
            if (data.redirect) setTimeout(() => window.location.href = data.redirect, 1500);
            return;
        }
        document.getElementById('likeIcon').textContent = (data.status === 'liked') ? '❤️' : '🤍';
        document.getElementById('likeCount').textContent = data.count;
        if (data.newPoints) animatePoints(data.newPoints);
        if (data.message) showToast(data.message, 'success');

        const likeIcon = document.getElementById('likeIcon');
        likeIcon.classList.add('scale-125', 'transition', 'duration-300');
        setTimeout(() => likeIcon.classList.remove('scale-125'), 300);
    })
    .catch(() => showToast('Terjadi kesalahan koneksi', 'error'));
}

// ✅ Share Berita
function shareNews() {
    if (navigator.share) {
        navigator.share({
            title: '<?= esc($news['title']) ?>',
            text: 'Baca berita menarik di NEWSTECHLY',
            url: window.location.href
        }).then(() => {
            fetch('/share/<?= $news['id'] ?>', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .catch(() => showToast('Gagal mencatat share', 'error'));
        }).catch(() => showToast('Gagal membagikan berita', 'error'));
    } else {
        navigator.clipboard.writeText(window.location.href)
            .then(() => showToast('Link disalin ke clipboard!', 'success'))
            .catch(() => showToast('Gagal menyalin link', 'error'));
    }
}

// ✅ Submit Komentar via Fetch
function handleCommentSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    const loading = document.getElementById('loading-indicator');

    loading.classList.remove('hidden');

    fetch('/comments/add/<?= $news['id'] ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: data
    })
    .then(res => res.json())
    .then(response => {
        loading.classList.add('hidden');

        if (response.error) {
            showToast(response.error, 'error');
            if (response.redirect) setTimeout(() => window.location.href = response.redirect, 1500);
            return;
        }

        if (response.html) {
            const commentList = document.getElementById('comment-list');

            // Hapus placeholder "Belum ada komentar"
            const placeholder = commentList.querySelector('p.text-gray-500');
            if (placeholder) placeholder.remove();

            // Buat elemen dari response.html
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = response.html;
            const newComment = tempDiv.firstElementChild;

            // Insert komentar di atas
            commentList.insertBefore(newComment, commentList.firstChild);

            // Animasi fade-in
            requestAnimationFrame(() => {
                newComment.classList.remove('opacity-0', 'translate-y-3');
                newComment.classList.add('opacity-100', 'translate-y-0');
            });
        }

        if (response.newPoints) animatePoints(response.newPoints);
        if (response.message) showToast(response.message, 'success');

        form.reset();

        // Update CSRF
        if (response.csrfToken) {
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', response.csrfToken);
        }
    })
    .catch(() => {
        loading.classList.add('hidden');
        showToast('Terjadi kesalahan koneksi', 'error');
    });

    return false;
}

</script>

<?= $this->endSection() ?>
