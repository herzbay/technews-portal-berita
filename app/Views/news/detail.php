<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow p-6">
    <img src="<?= esc($news['image_url']) ?>" alt="<?= esc($news['title']) ?>" class="w-full h-64 object-cover rounded mb-4">
    <h1 class="text-3xl font-bold mb-2 text-blue-600"><?= esc($news['title']) ?></h1>
    <p class="text-gray-500 mb-4"><?= date('d M Y', strtotime($news['created_at'])) ?> | <?= esc($news['category']) ?></p>
    <div class="prose dark:prose-invert">
        <?= $news['content'] ?>
    </div>
</div>

<!-- Komentar -->
<div class="max-w-4xl mx-auto mt-8 bg-gray-100 dark:bg-gray-700 rounded-xl p-6">
    <h2 class="text-2xl font-semibold mb-4">Komentar</h2>

    <!-- Form Tambah Komentar -->
    <form hx-post="/comments/add" hx-target="#comment-list" hx-swap="afterbegin" class="mb-4">
        <input type="hidden" name="news_id" value="<?= $news['id'] ?>">
        <textarea name="content" placeholder="Tulis komentar..." class="w-full p-3 rounded border dark:bg-gray-800 dark:text-gray-100 mb-2"></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Kirim
        </button>
    </form>

    <!-- Daftar Komentar -->
    <div id="comment-list" hx-get="/comments/list/<?= $news['id'] ?>" hx-trigger="load">
        <p class="text-gray-500">Memuat komentar...</p>
    </div>
</div>

<?= $this->endSection() ?>
