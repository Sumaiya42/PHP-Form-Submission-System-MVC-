<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Pure PHP MVC App') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; background: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        header { background: #333; color: #fff; padding: 10px 0; text-align: center; }
        header a { color: #fff; text-decoration: none; margin: 0 15px; }
        .content { padding: 20px 0; }
        .error { color: red; }
        .success { color: green; }
        form div { margin-bottom: 10px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #5cb85c; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #4cae4c; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/submit">Submit Data</a>
                <a href="/report">Report</a>
                <a href="/logout">Logout (<?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>)</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/signup">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
        <h1><?= htmlspecialchars($title ?? 'Application') ?></h1>
        <div class="content">
