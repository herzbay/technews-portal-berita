<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Navbar -->
<nav class="flex items-center justify-between bg-white shadow px-4 py-3 mb-4">
  <div class="flex items-center gap-2">
    <img src="<?= base_url('assets/logo.png') ?>" alt="Logo" class="h-8 w-8">
    <h1 class="text-xl font-bold text-blue-700">NEWSTECHLY</h1>
  </div>
  <div>
    <?php if (session('is_logged_in')): ?>
      <a href="/profile" class="text-sm text-gray-700 hover:text-blue-600"><?= session('user_name') ?></a>
    <?php else: ?>
      <a href="/login" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Login</a>
    <?php endif; ?>
  </div>
</nav>

<!-- Advertisement Section -->
<div class="overflow-x-auto whitespace-nowrap scrollbar-hide px-4 mb-6">
  <?php if (!empty($ads)): ?>
    <?php foreach ($ads as $ad): ?>
      <a href="<?= esc($ad['target_url']) ?>" target="_blank" class="inline-block mr-4">
        <img src="<?= esc($ad['image_url']) ?>" alt="<?= esc($ad['title']) ?>" class="h-32 rounded shadow hover:scale-105 transition-transform">
      </a>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-gray-500">Tidak ada iklan tersedia.</p>
  <?php endif; ?>
</div>

<!-- News Section -->
<section class="grid md:grid-cols-3 gap-6 px-4">
  <?php if (!empty($news)): ?>
    <?php foreach ($news as $item): ?>
      <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
        <img src="<?= esc($item['image_url']) ?>" alt="<?= esc($item['title']) ?>" class="rounded-t-lg h-40 w-full object-cover">
        <div class="p-4">
          <h2 class="text-lg font-semibold text-blue-800 hover:text-blue-600 transition-colors">
            <a href="/news/<?= esc($item['slug']) ?>"><?= esc($item['title']) ?></a>
          </h2>
          <p class="text-sm text-gray-600 mt-2">
            <?= esc(character_limiter(strip_tags($item['content']), 100)) ?>
          </p>
          <p class="text-xs text-gray-400 mt-3">
            Diposting: <?= date('d M Y', strtotime($item['created_at'])) ?>
          </p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="col-span-3 text-gray-500 text-center">Tidak ada berita ditemukan.</p>
  <?php endif; ?>
</section>

<?= $this->endSection() ?>
