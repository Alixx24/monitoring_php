<?php require_once __DIR__ . '/../layout/header.php'; ?>
    <title>لیست فیلم‌ها</title>
    <style>
        /* استایل ساده برای زیبایی */
        body { font-family: Tahoma, sans-serif; background: #f7f9fc; padding: 20px; direction: rtl; }
        .movie { background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { margin: 0 0 5px; color: #0077cc; }
        p { margin: 3px 0; }
        .error { color: red; font-weight: bold; }
    </style>
<body>
<?php
session_start();

if (!empty($_SESSION['success'])) {
    echo "<div style='color:green'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
}

if (!empty($_SESSION['warning'])) {
    echo "<div style='color:orange'>{$_SESSION['warning']}</div>";
    unset($_SESSION['warning']);
}

if (!empty($_SESSION['error'])) {
    echo "<div style='color:red'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}
?>

<body>

<h1>لیست فیلم‌ها</h1>

<?php if (isset($movies['error'])): ?>
    <div class="error">خطا: <?= htmlspecialchars($movies['error']) ?></div>
<?php elseif (empty($movies)): ?>
    <p>هیچ فیلمی یافت نشد.</p>
<?php else: ?>
    <?php foreach ($movies as $movie): ?>
        <div class="movie">
            <h2><?= htmlspecialchars($movie['Title']) ?></h2>
            <p>سال ساخت: <?= htmlspecialchars($movie['Year']) ?></p>
            <p>نوع: <?= htmlspecialchars($movie['Type']) ?></p>
            <p>IMDB ID: <?= htmlspecialchars($movie['imdbID']) ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>