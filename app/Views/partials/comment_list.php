<?php if (!empty($comments)): ?>
    <?php foreach ($comments as $c): ?>
        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg shadow mb-2">
            <p class="font-semibold text-blue-600"><?= esc($c['username'] ?? 'Anonim') ?></p>
            <p class="text-gray-800 dark:text-gray-200"><?= esc($c['content']) ?></p>
            <small class="text-gray-500"><?= date('d M Y H:i', strtotime($c['created_at'])) ?></small>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama!</p>
<?php endif; ?>
