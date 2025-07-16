<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Kelola User</h1>
    <a href="/admin/users/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah User
    </a>
</div>

<div class="bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-gray-300">
        <thead class="bg-gray-700">
            <tr>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Poin</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr class="border-b border-gray-700">
                        <td class="px-4 py-2"><?= esc($user['name']) ?></td>
                        <td class="px-4 py-2"><?= esc($user['email']) ?></td>
                        <td class="px-4 py-2"><?= esc($user['role']) ?></td>
                        <td class="px-4 py-2"><?= esc($user['total_points'] ?? 0) ?></td>
                        <td class="px-4 py-2 text-center flex justify-center gap-2">
                            <a href="/admin/users/edit/<?= $user['id'] ?>"
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                Edit
                            </a>
                            <form action="/admin/users/delete/<?= $user['id'] ?>" method="post" onsubmit="return confirm('Yakin hapus user ini?');">
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-400">Belum ada user.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
