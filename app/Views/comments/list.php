<?php foreach ($comments as $comment): ?>
<div class="bg-white dark:bg-gray-800 p-3 rounded mb-2 shadow">
    <p class="text-gray-700 dark:text-gray-200"><?= esc($comment['content']) ?></p>
    <small class="text-gray-500"><?= date('d M Y H:i', strtotime($comment['created_at'])) ?></small>
</div>
<?php endforeach; ?>
