<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: true }" x-bind:class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - NEWSTECHLY</title>
    <link href="/css/output.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-64 bg-gray-800 text-white flex-shrink-0 flex flex-col">
    <div class="p-4 border-b border-gray-700">
        <h1 class="text-2xl font-bold">Admin Panel</h1>
        <p class="text-sm text-gray-400">NEWSTECHLY</p>
    </div>
    <nav class="flex-1 p-4 space-y-2">
        <a href="/admin" class="block px-4 py-2 rounded hover:bg-gray-700 transition">📊 Dashboard</a>
        <a href="/admin/news" class="block px-4 py-2 rounded hover:bg-gray-700 transition">📰 Kelola Berita</a>
        <a href="/admin/users" class="block px-4 py-2 rounded hover:bg-gray-700 transition">👥 Kelola User</a>
    </nav>
    <div class="p-4 border-t border-gray-700">
        <a href="/logout" class="block text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold">Logout</a>
    </div>
</aside>

<!-- Main Content -->
<div class="flex-1 flex flex-col">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow px-6 py-4 flex justify-between items-center">
        <h2 class="text-xl font-bold">Admin Dashboard</h2>
        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode" class="p-2 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
            <span x-show="!darkMode">🌙</span>
            <span x-show="darkMode">☀️</span>
        </button>
    </header>

    <!-- Content -->
    <main class="p-6 flex-1 overflow-y-auto">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="bg-green-500 text-white px-4 py-3 rounded mb-4">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php elseif(session()->getFlashdata('error')): ?>
            <div class="bg-red-500 text-white px-4 py-3 rounded mb-4">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

</body>
</html>
