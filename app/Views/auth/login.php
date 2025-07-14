<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- FontAwesome (untuk ikon Google) -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
  <h2 class="text-2xl font-bold text-blue-600 mb-4">Login</h2>

  <!-- Flash Alert -->
  <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php elseif (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php elseif (session()->getFlashdata('info')): ?>
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-2 rounded mb-4">
      <?= session()->getFlashdata('info') ?>
    </div>
  <?php endif; ?>

  <!-- Form Login Manual -->
  <form method="post" action="/login">
    <input type="email" name="email" class="w-full mb-3 border border-gray-300 p-2 rounded" placeholder="Email" required>
    <input type="password" name="password" class="w-full mb-4 border border-gray-300 p-2 rounded" placeholder="Password" required>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Login</button>
  </form>

  <!-- Atau Garis Pemisah -->
  <div class="my-4 text-center text-gray-500">atau</div>

  <!-- Tombol Login Google -->
  <a href="/auth/googleLogin" class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded transition">
    <i class="fab fa-google"></i> Login dengan Google
  </a>

  <!-- Link ke Daftar -->
  <p class="text-sm mt-4 text-center">Belum punya akun? 
    <a href="/register" class="text-blue-500 hover:underline">Daftar</a>
  </p>
</div>

<?= $this->endSection() ?>
