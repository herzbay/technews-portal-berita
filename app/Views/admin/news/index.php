<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Kelola Berita</h1>
    <a href="/admin/news/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Berita</a>
</div>

<table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow">
    <thead>
        <tr class="bg-gray-100 dark:bg-gray-700">
            <th class="py-2 px-4 text-left">Judul</th>
            <th class="py-2 px-4 text-left">Kategori</th>
            <th class="py-2 px-4 text-left">Tanggal</th>
            <th class="py-2 px-4">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($news as $item): ?>
        <tr class="border-b border-gray-200 dark:border-gray-700">
            <td class="py-2 px-4"><?= esc($item['title']) ?></td>
            <td class="py-2 px-4"><?= esc($item['category']) ?></td>
            <td class="py-2 px-4"><?= date('d M Y', strtotime($item['created_at'])) ?></td>
            <td class="py-2 px-4 text-center space-x-2">
                <a href="/admin/news/edit/<?= $item['id'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
                <a href="/admin/news/delete/<?= $item['id'] ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Yakin hapus berita ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
