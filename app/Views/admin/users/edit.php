<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>

    <form action="/admin/users/update/<?= $user['id'] ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <!-- Nama -->
        <div>
            <label class="block mb-1 font-semibold">Nama</label>
            <input type="text" name="name" value="<?= esc($user['name']) ?>" required
                class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
        </div>

        <!-- Email -->
        <div>
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="<?= esc($user['email']) ?>" required
                class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
        </div>

        <!-- Password (opsional) -->
        <div>
            <label class="block mb-1 font-semibold">Password (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password"
                class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
        </div>

        <!-- Role -->
        <div>
            <label class="block mb-1 font-semibold">Role</label>
            <select name="role" class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end space-x-3">
            <a href="/admin/users" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
