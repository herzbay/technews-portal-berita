<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-blue-600 mb-4">Login</h2>
    <form method="post" action="/login">
        <?= csrf_field() ?>
        <input type="email" name="email" placeholder="Email"
               class="w-full mb-3 border border-gray-300 p-2 rounded" required>
        <input type="password" name="password" placeholder="Password"
               class="w-full mb-4 border border-gray-300 p-2 rounded" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>
    <p class="text-sm mt-4 text-center">Belum punya akun?
        <a href="/register" class="text-blue-500 hover:underline">Daftar</a>
    </p>
</div>

<?= $this->endSection() ?>
