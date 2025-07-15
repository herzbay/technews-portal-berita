<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: false }" x-bind:class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEWSTECHLY - Portal Teknologi</title>
    <link href="/css/output.css" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition duration-300">

    <!-- HEADER -->
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">NEWSTECHLY</a>
            
            <nav class="space-x-6">
                <a href="/" class="hover:text-blue-500">Home</a>
                <a href="#" class="hover:text-blue-500">Kategori</a>
                <a href="#" class="hover:text-blue-500">Leaderboard</a>
            </nav>

            <!-- Tombol Mode Gelap -->
            <button @click="darkMode = !darkMode" class="p-2 rounded bg-gray-200 dark:bg-gray-700">
                <span x-show="!darkMode">🌙</span>
                <span x-show="darkMode">☀️</span>
            </button>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="container mx-auto px-4 py-6">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-200 dark:bg-gray-700 text-center py-4 mt-10">
        <p>&copy; <?= date('Y') ?> NEWSTECHLY. All Rights Reserved.</p>
    </footer>

</body>
</html>
