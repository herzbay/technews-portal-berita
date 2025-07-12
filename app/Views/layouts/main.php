<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'TechNews' ?></title>
  <link href="<?= base_url('output.css') ?>" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
</head>
<body class="bg-gray-100 text-gray-900">
  <main class="container mx-auto py-8 px-4">
    <?= $this->renderSection('content') ?>
  </main>
</body>
</html>
