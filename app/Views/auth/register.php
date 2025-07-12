<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
  <h2 class="text-2xl font-bold text-blue-600 mb-4">Register</h2>
  <form method="post" action="/register">
    <input type="text" name="name" placeholder="Nama" required class="w-full mb-3 p-2 border rounded" />
    <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded" />
    <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded" />
    <button class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">Register</button>
  </form>
  <p class="text-sm mt-3 text-center">Sudah punya akun? <a href="/login" class="text-blue-500 underline">Login</a></p>
</div>

<?= $this->endSection() ?>
