<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Kontainer Berita -->
<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
    <img src="<?= esc($news['image_url']) ?>" alt="<?= esc($news['title']) ?>" class="w-full h-64 object-cover rounded mb-4">

    <h1 class="text-3xl font-bold mb-2 text-blue-600"><?= esc($news['title']) ?></h1>
    <p class="text-gray-500 mb-4"><?= date('d M Y', strtotime($news['created_at'])) ?> | <?= esc($news['category']) ?></p>
    
    <div class="prose dark:prose-invert max-w-none leading-relaxed">
        <?= $news['content'] ?>
    </div>
</div>

<!-- Tombol Like & Share -->
<div class="max-w-4xl mx-auto flex flex-wrap gap-4 mb-6">
    <button hx-post="/like/<?= $news['id'] ?>" hx-swap="none"
        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        ❤️ Like (<span id="likeCount"><?= isset($likeCount) ? esc($likeCount) : 0 ?></span>)
    </button>

    <button @click="shareNews()" class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
        🔗 Share
    </button>
</div>

<!-- Komentar -->
<div class="max-w-4xl mx-auto bg-gray-100 dark:bg-gray-700 rounded-xl p-6">
    <h2 class="text-2xl font-semibold mb-4">Komentar</h2>

    <form hx-post="/comments/add/<?= $news['id'] ?>" 
          hx-target="#comment-list" 
          hx-swap="afterbegin" 
          hx-on="htmx:afterRequest: this.reset()" 
          class="mb-4">
        <textarea name="content" placeholder="Tulis komentar..." required
            class="w-full p-3 rounded border dark:bg-gray-800 dark:text-gray-100 mb-2"></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim</button>
    </form>

    <div id="comment-list" hx-get="/comments/list/<?= $news['id'] ?>" hx-trigger="load">
        <p class="text-gray-500">Memuat komentar...</p>
    </div>
</div>

<script>
function shareNews() {
    if (navigator.share) {
        navigator.share({
            title: '<?= esc($news['title']) ?>',
            text: 'Baca berita menarik di NEWSTECHLY',
            url: window.location.href
        }).then(() => {
            fetch('/share/<?= $news['id'] ?>', {method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}});
        });
    } else {
        alert('Share tidak didukung di browser ini.');
    }
}
</script>

<?= $this->endSection() ?>
