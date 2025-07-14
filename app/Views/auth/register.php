<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
  <h2 class="text-2xl font-bold text-blue-600 mb-4">Register</h2>
  <form method="post" action="/register" class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Register</h2>
    <input type="text" name="name" class="w-full mb-3 border p-2 rounded" placeholder="Nama" required>
    <input type="email" name="email" class="w-full mb-3 border p-2 rounded" placeholder="Email" required>
    <input type="password" name="password" class="w-full mb-3 border p-2 rounded" placeholder="Password" required>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Register</button>
  </form>

  <p class="text-sm mt-3 text-center">Sudah punya akun? <a href="/login" class="text-blue-500 underline">Login</a></p>
</div>

<?= $this->endSection() ?>
