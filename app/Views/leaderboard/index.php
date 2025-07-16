<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow mt-8">
    <h2 class="text-2xl font-bold mb-4 text-blue-600">🏆 Leaderboard</h2>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700 text-left">
                <th class="p-3">Rank</th>
                <th class="p-3">User</th>
                <th class="p-3">Points</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($leaders as $index => $user): ?>
            <tr class="border-b border-gray-200 dark:border-gray-600">
                <td class="p-3"><?= $index + 1 ?></td>
                <td class="p-3"><?= esc($user['name']) ?></td>
                <td class="p-3 font-bold text-green-600"><?= $user['total_points'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
