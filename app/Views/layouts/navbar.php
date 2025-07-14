<nav class="bg-white shadow px-4 py-3 mb-6">
  <div class="container mx-auto flex items-center justify-between">
    
    <!-- Logo dan Brand -->
    <div class="flex items-center gap-2">
      <img src="<?= base_url('assets/logo.png') ?>" alt="Logo" class="h-8 w-8">
      <a href="/" class="text-xl font-bold text-blue-700 hover:text-blue-800">TechNews</a>
    </div>

    <!-- Navigasi User -->
    <div class="flex items-center gap-4 text-sm">
      <?php if (session('is_logged_in')): ?>
        <span class="text-gray-700">Halo, <?= esc(session('user_name')) ?></span>
        <?php if (session('role') === 'admin'): ?>
          <a href="/admin" class="text-blue-600 hover:underline">Admin Panel</a>
        <?php endif; ?>
        <a href="/logout" class="text-red-500 hover:underline">Logout</a>
      <?php else: ?>
        <a href="/login" class="text-blue-600 hover:underline">Login</a>
        <a href="/register" class="text-blue-600 hover:underline">Register</a>
      <?php endif; ?>
    </div>

  </div>
</nav>
