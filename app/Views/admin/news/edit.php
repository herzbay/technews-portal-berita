<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="text-2xl font-bold mb-6">Edit Berita</h1>

<form action="/admin/news/update/<?= $news['id'] ?>" method="post" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded shadow">
    <?= csrf_field() ?>
    <div>
        <label class="block font-semibold mb-2">Judul</label>
        <input type="text" name="title" value="<?= esc($news['title']) ?>" required class="w-full p-2 border rounded dark:bg-gray-700">
    </div>

    <div>
        <label class="block font-semibold mb-2">Kategori</label>
        <input type="text" name="category" value="<?= esc($news['category']) ?>" required class="w-full p-2 border rounded dark:bg-gray-700">
    </div>

    <div>
        <label class="block font-semibold mb-2">Konten</label>
        <textarea name="content" rows="6" required class="w-full p-2 border rounded dark:bg-gray-700"><?= esc($news['content']) ?></textarea>
    </div>

    <div>
        <label class="block font-semibold mb-2">Gambar (opsional)</label>
        <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded dark:bg-gray-700">
        <p class="mt-2 text-sm text-gray-500">Gambar saat ini:</p>
        <img src="<?= esc($news['image_url']) ?>" alt="Current Image" class="h-32 mt-2 rounded">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
</form>

<?= $this->endSection() ?>
