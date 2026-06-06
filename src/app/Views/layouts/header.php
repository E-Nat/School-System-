<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'University Management System'); ?></title>
    <?php
        $config = file_exists(__DIR__ . '/../../../../config.php') ? require __DIR__ . '/../../../../config.php' : [];
        $baseUrl = rtrim($config['app']['base_url'] ?? '/', '/');
        $assetPath = $baseUrl === '' ? '/assets/css/style.css' : $baseUrl . '/assets/css/style.css';
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?php echo htmlspecialchars($assetPath); ?>" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
<header class="bg-slate-950 text-white shadow-lg shadow-slate-900/5">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:px-6 lg:px-8 lg:flex-row lg:items-center lg:justify-between">
        <a href="index.php" class="flex items-center gap-3 text-lg font-bold tracking-tight text-white sm:text-xl">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-400 to-indigo-500 text-slate-950">UMS</span>
            <span>University Management System</span>
        </a>
        <nav class="flex flex-wrap items-center gap-3 text-sm font-medium text-slate-200">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php" class="rounded-full bg-slate-900 px-4 py-2 text-slate-100 transition hover:bg-slate-800">Dashboard</a>
                <a href="index.php?page=logout" class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-white transition hover:bg-white/20">Logout</a>
            <?php else: ?>
                <a href="index.php?page=login" class="rounded-full bg-cyan-500 px-4 py-2 text-white transition hover:bg-cyan-400">Login</a>
                <a href="index.php?page=register" class="rounded-full border border-white/20 px-4 py-2 text-white transition hover:border-white">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
