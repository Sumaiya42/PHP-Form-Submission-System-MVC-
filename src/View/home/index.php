<?php require BASE_PATH . '/src/View/layout/header.php'; ?>

<p>This is the home page of the pure PHP MVC application.</p>
<p>Please use the navigation links above to proceed.</p>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>.</p>
<?php else: ?>
    <p>Please <a href="/login">login</a> or <a href="/signup">sign up</a> to access the submission and reporting features.</p>
<?php endif; ?>

<?php require BASE_PATH . '/src/View/layout/footer.php'; ?>
