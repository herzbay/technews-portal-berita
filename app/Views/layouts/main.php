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
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans">

<header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-extrabold text-blue-600 hover:text-blue-700">NEWSTECHLY</a>
        <nav class="flex items-center space-x-6">
            <a href="/" class="hover:text-blue-500">Home</a>
            <a href="/leaderboard" class="hover:text-blue-500">Leaderboard</a>
            <?php if (session()->get('logged_in')): ?>
                <span id="userPoints" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                    Poin: <?= session()->get('user_points') ?? 0 ?>
                </span>
                <a href="/logout" class="hover:text-blue-500">Logout (<?= esc(session()->get('user_name')) ?>)</a>
            <?php else: ?>
                <a href="/login" class="hover:text-blue-500">Login</a>
            <?php endif; ?>
        </nav>
        <button @click="darkMode = !darkMode" class="p-2 rounded bg-gray-200 dark:bg-gray-700">
            <span x-show="!darkMode">🌙</span><span x-show="darkMode">☀️</span>
        </button>
    </div>
</header>

<main class="container mx-auto px-4 py-6 min-h-screen">
    <?= $this->renderSection('content') ?>
</main>

<footer class="bg-gray-200 dark:bg-gray-800 text-center py-4 mt-10">
    <p>&copy; <?= date('Y') ?> NEWSTECHLY. All Rights Reserved.</p>
</footer>

<!-- Toast -->
<div x-data="{ show: false, message: '', type: 'success' }" class="fixed top-5 right-5 z-50">
    <div x-show="show" x-transition
        :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"
        class="text-white px-4 py-3 rounded shadow">
        <p x-text="message"></p>
    </div>
</div>

<script>
function animatePoints(newPoints) {
    const el = document.getElementById('userPoints');
    if (!el) return;
    el.classList.add('scale-125', 'bg-yellow-200');
    setTimeout(() => {
        el.textContent = 'Poin: ' + newPoints;
        el.classList.remove('scale-125', 'bg-yellow-200');
    }, 800);
}

htmx.on('htmx:afterRequest', function(evt) {
    if (evt.detail.xhr && evt.detail.xhr.response) {
        try {
            const data = JSON.parse(evt.detail.xhr.response);
            if (data.error && data.redirect) {
                alert(data.error);
                window.location.href = data.redirect;
                return;
            }
            if (data.newPoints) animatePoints(data.newPoints);
            if (data.count !== undefined) {
                const likeCountEl = document.getElementById('likeCount');
                if (likeCountEl) likeCountEl.textContent = data.count;
            }
            if (data.html) {
                const target = document.querySelector('#comment-list');
                if (target) target.insertAdjacentHTML('afterbegin', data.html);
            }
        } catch (e) {}
    }
});
</script>
</body>
</html>
