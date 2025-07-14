<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'TechNews' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= base_url('output.css') ?>" rel="stylesheet">
  <script src="https://unpkg.com/htmx.org@1.9.2" defer></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 text-gray-900">
  
  <!-- Navbar -->
  <?= view('layouts/navbar') ?>

  <!-- Konten Halaman -->
  <main class="container mx-auto py-8 px-4">
    <?= $this->renderSection('content') ?>
  </main>

</body>
</html>
