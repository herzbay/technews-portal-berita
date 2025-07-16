<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="text-2xl font-bold mb-4">Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-blue-500 text-white rounded-lg p-6 shadow">
        <p>Total Berita</p>
        <h2 class="text-3xl font-bold"><?= $totalNews ?></h2>
    </div>
    <div class="bg-green-500 text-white rounded-lg p-6 shadow">
        <p>Total User</p>
        <h2 class="text-3xl font-bold"><?= $totalUsers ?></h2>
    </div>
    <div class="bg-yellow-500 text-white rounded-lg p-6 shadow">
        <p>Total Komentar</p>
        <h2 class="text-3xl font-bold"><?= $totalComments ?></h2>
    </div>
</div>

<?= $this->endSection() ?>
