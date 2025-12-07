<?php require BASE_PATH . '/src/View/layout/header.php'; ?>

<form id="signupForm">
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Sign Up</button>
    <p id="message" class="error"></p>
</form>

<script>
    // Frontend validation function
    function validateSignupForm(data) {
        const errors = {};
        if (!data.name || data.name.trim().length === 0) {
            errors.name = 'Name is required.';
        }
        if (!data.email || !/\S+@\S+\.\S+/.test(data.email)) {
            errors.email = 'Valid email is required.';
        }
        if (!data.password || data.password.length < 6) {
            errors.password = 'Password must be at least 6 characters.';
        }
        return errors;
    }

    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const messageElement = document.getElementById('message');
        messageElement.textContent = '';

        const formData = { name, email, password };
        const validationErrors = validateSignupForm(formData);

        if (Object.keys(validationErrors).length > 0) {
            messageElement.className = 'error';
            messageElement.textContent = 'Please correct the following errors: ' + Object.values(validationErrors).join(' ');
            return;
        }

        ajax('/api/signup', 'POST', formData, function(response) {
            if (response.success) {
                messageElement.className = 'success';
                messageElement.textContent = 'Sign up successful. You can now log in.';
                document.getElementById('signupForm').reset();
            } else {
                messageElement.className = 'error';
                messageElement.textContent = response.message || 'Sign up failed.';
            }
        }, function(response) {
            messageElement.className = 'error';
            messageElement.textContent = response.message || 'An unexpected error occurred.';
        });
    });
</script>

<?php require BASE_PATH . '/src/View/layout/footer.php'; ?>
