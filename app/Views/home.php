<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="text-3xl font-bold mb-6 text-blue-600">Berita Teknologi Terbaru</h1>

<div class="grid md:grid-cols-3 gap-6">
    <?php if (empty($news)): ?>
        <p class="text-gray-500">Belum ada berita tersedia.</p>
    <?php else: ?>
        <?php foreach ($news as $item): ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden">
                <img src="<?= esc($item['image_url'] ?: '/images/default.jpg') ?>" alt="<?= esc($item['title']) ?>" class="w-full h-48 object-cover">
                
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 text-blue-600 hover:underline">
                        <a href="/news/<?= esc($item['slug']) ?>">
                            <?= esc($item['title']) ?>
                        </a>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">
                        <?= date('d M Y', strtotime($item['created_at'])) ?>
                    </p>
                    <p class="text-gray-700 dark:text-gray-400 mb-4">
                        <?= esc(substr(strip_tags($item['content']), 0, 150)) ?>...
                    </p>
                    <a href="/news/<?= esc($item['slug']) ?>" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Read More
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
