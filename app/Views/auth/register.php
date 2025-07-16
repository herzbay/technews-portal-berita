<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-blue-600">Register</h2>
    <form method="post" action="/register" class="space-y-4">
        <?= csrf_field() ?>
        <input type="text" name="name" placeholder="Nama Lengkap" class="w-full p-3 rounded border dark:bg-gray-700 dark:text-white" required>
        <input type="email" name="email" placeholder="Email" class="w-full p-3 rounded border dark:bg-gray-700 dark:text-white" required>
        <input type="password" name="password" placeholder="Password" class="w-full p-3 rounded border dark:bg-gray-700 dark:text-white" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">Daftar</button>
    </form>
    <p class="mt-4 text-sm">Sudah punya akun? <a href="/login" class="text-blue-500">Login</a></p>
</div>
<?= $this->endSection() ?>
