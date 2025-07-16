<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-blue-600">Login</h2>
    <form method="post" action="/login" class="space-y-4">
        <?= csrf_field() ?>
        <input type="email" name="email" placeholder="Email" class="w-full p-3 rounded border dark:bg-gray-700 dark:text-white" required>
        <input type="password" name="password" placeholder="Password" class="w-full p-3 rounded border dark:bg-gray-700 dark:text-white" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">Login</button>
    </form>
    <p class="mt-4 text-sm">Belum punya akun? <a href="/register" class="text-blue-500">Register</a></p>
</div>
<?= $this->endSection() ?>
