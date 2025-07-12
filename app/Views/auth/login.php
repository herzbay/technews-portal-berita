<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
  <h2 class="text-2xl font-bold text-blue-600 mb-4">Login</h2>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>
  <form method="post" action="/login">
    <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded" />
    <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded" />
    <button class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Login</button>
  </form>
  <p class="text-sm mt-3 text-center">Belum punya akun? <a href="/register" class="text-blue-500 underline">Daftar</a></p>
</div>

<?= $this->endSection() ?>
