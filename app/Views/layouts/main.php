<!DOCTYPE html>
<html lang="en" x-data="themeHandler()" x-init="initTheme()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= esc($title ?? 'NEWSTECHLY - Portal Teknologi') ?></title>

    <!-- ✅ TailwindCSS -->
    <link rel="stylesheet" href="/css/output.css">

    <!-- ✅ HTMX & AlpineJS -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- ✅ CSRF Meta -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <style>
        body, header, footer { transition: background-color 0.3s ease, color 0.3s ease; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans">

<!-- ✅ HEADER -->
<header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="/" class="text-2xl font-extrabold text-blue-600 hover:text-blue-700 transition">NEWSTECHLY</a>

        <!-- ✅ Navigation -->
        <nav class="flex items-center space-x-6 text-sm md:text-base">
            <a href="/" class="hover:text-blue-500 transition">Home</a>
            <a href="/leaderboard" class="hover:text-blue-500 transition">Leaderboard</a>
            <?php if (session()->get('logged_in')): ?>
                <span id="userPoints"
                      class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs md:text-sm font-semibold transition">
                    Poin: <?= session()->get('user_points') ?? 0 ?>
                </span>
                <a href="/logout" class="hover:text-blue-500 transition">
                    Logout (<?= esc(session()->get('user_name')) ?>)
                </a>
            <?php else: ?>
                <a href="/login" class="hover:text-blue-500 transition">Login</a>
            <?php endif; ?>
        </nav>

        <!-- ✅ Theme Toggle -->
        <button @click="toggleTheme()" aria-label="Toggle Theme"
                class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <span x-show="!darkMode" class="text-xl">🌙</span>
            <span x-show="darkMode" class="text-xl">☀️</span>
        </button>
    </div>
</header>

<!-- ✅ MAIN CONTENT -->
<main class="container mx-auto px-4 py-6 min-h-screen">
    <?= $this->renderSection('content') ?>
</main>

<!-- ✅ FOOTER -->
<footer class="bg-gray-200 dark:bg-gray-800 text-center py-4 mt-10 text-gray-600 dark:text-gray-400">
    <p>&copy; <?= date('Y') ?> NEWSTECHLY. All Rights Reserved.</p>
</footer>

<!-- ✅ Toast Notification -->
<div x-data="{ show: false, message: '', type: 'success' }" x-ref="toast" class="fixed top-5 right-5 z-50">
    <div x-show="show"
         x-transition:enter="transform ease-out duration-300"
         x-transition:enter-start="translate-y-2 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transform ease-in duration-300"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-2 opacity-0"
         :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"
         class="text-white px-4 py-3 rounded shadow-lg font-semibold text-sm md:text-base">
        <p x-text="message"></p>
    </div>
</div>

<!-- ✅ Global Loading Spinner -->
<div id="loadingSpinner" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
</div>

<!-- ✅ Scripts -->
<script>
/** ✅ Theme Handler */
function themeHandler() {
    return {
        darkMode: false,
        initTheme() {
            const savedTheme = localStorage.getItem('theme');
            this.darkMode = savedTheme ? savedTheme === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.applyTheme();
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            this.applyTheme();
        },
        applyTheme() {
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }
}

/** ✅ Toast Notification */
function showToast(message, type = 'success') {
    const toastEl = document.querySelector('[x-ref="toast"]');
    const toastData = Alpine.$data(toastEl);
    toastData.message = message;
    toastData.type = type;
    toastData.show = true;
    setTimeout(() => toastData.show = false, 3000);
}

/** ✅ Animasi Update Poin */
function animatePoints(newPoints) {
    const el = document.getElementById('userPoints');
    if (!el) return;
    el.classList.add('scale-125', 'bg-yellow-200');
    setTimeout(() => {
        el.textContent = 'Poin: ' + newPoints;
        el.classList.remove('scale-125', 'bg-yellow-200');
    }, 800);
}

/** ✅ HTMX + CSRF Auto Attach */
document.body.addEventListener('htmx:configRequest', (event) => {
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (!token) token = getCookie('csrf_cookie_name'); // fallback
    if (token) event.detail.headers['X-CSRF-TOKEN'] = token;
});

function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
}

/** ✅ Spinner & Error Handling */
htmx.on('htmx:beforeRequest', () => document.getElementById('loadingSpinner').classList.remove('hidden'));
htmx.on('htmx:afterRequest', () => document.getElementById('loadingSpinner').classList.add('hidden'));
htmx.on('htmx:error', () => {
    document.getElementById('loadingSpinner').classList.add('hidden');
    showToast('Terjadi kesalahan koneksi', 'error');
});

// ✅ Global Response Handler
htmx.on('htmx:afterRequest', function(evt) {
    if (!evt.detail.xhr.response) return;
    try {
        const data = JSON.parse(evt.detail.xhr.response);

        if (data.error) {
            showToast(data.error, 'error');
            if (data.redirect) setTimeout(() => window.location.href = data.redirect, 1500);
            return;
        }

        if (data.message) showToast(data.message, 'success');
        if (data.newPoints) animatePoints(data.newPoints);

        // ✅ Komentar baru
        if (data.html) {
            const commentList = document.querySelector('#comment-list');
            if (commentList) {
                const temp = document.createElement('div');
                temp.innerHTML = data.html;
                const newComment = temp.firstElementChild;
                commentList.insertBefore(newComment, commentList.firstChild);

                // ✅ Animasi fade-in
                requestAnimationFrame(() => {
                    newComment.classList.remove('opacity-0', 'translate-y-2');
                    newComment.classList.add('opacity-100', 'translate-y-0');
                });
            }
        }

        // ✅ Hapus komentar + animasi
        if (data.commentId) {
            const el = document.getElementById('comment-' + data.commentId);
            if (el) {
                el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateX(-20px)';
                setTimeout(() => el.remove(), 400);
            }
        }

        // ✅ Update poin jika dikirim
        if (data.newPoints) {
            animatePoints(data.newPoints);
        }

        if (data.csrfToken) {
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrfToken);
        }
    } catch (e) {
        console.error('Response bukan JSON:', e);
    }
});

</script>
</body>
</html>
