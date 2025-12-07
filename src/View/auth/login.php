<?php require BASE_PATH . '/src/View/layout/header.php'; ?>

<form id="loginForm">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Login</button>
    <p id="message" class="error"></p>
</form>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const messageElement = document.getElementById('message');
        messageElement.textContent = '';

        ajax('/api/login', 'POST', { email, password }, function(response) {
            if (response.success) {
                messageElement.className = 'success';
                messageElement.textContent = 'Login successful. Redirecting...';
                window.location.href = '/';
            } else {
                messageElement.className = 'error';
                messageElement.textContent = response.message || 'Login failed.';
            }
        }, function(response) {
            messageElement.className = 'error';
            messageElement.textContent = response.message || 'An unexpected error occurred.';
        });
    });
</script>

<?php require BASE_PATH . '/src/View/layout/footer.php'; ?>
