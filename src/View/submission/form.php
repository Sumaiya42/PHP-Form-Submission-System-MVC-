<?php require BASE_PATH . '/src/View/layout/header.php'; ?>

<form id="submissionForm">
    <p id="submissionMessage" class="error"></p>

    <div>
        <label for="amount">Amount*:</label>
        <input type="text" id="amount" name="amount" required>
        <span class="error-message" data-field="amount"></span>
    </div>

    <div>
        <label for="buyer">Buyer*:</label>
        <input type="text" id="buyer" name="buyer" required>
        <span class="error-message" data-field="buyer"></span>
    </div>

    <div>
        <label for="receipt_id">Receipt ID*:</label>
        <input type="text" id="receipt_id" name="receipt_id" required>
        <span class="error-message" data-field="receipt_id"></span>
    </div>

    <div>
        <label for="items">Items*:</label>
        <div id="items-container">
            <input type="text" class="item-input" name="item[]" placeholder="Item 1" required>
        </div>
        <button type="button" id="addItemBtn">Add Item</button>
        <span class="error-message" data-field="items"></span>
    </div>

    <div>
        <label for="buyer_email">Buyer Email*:</label>
        <input type="email" id="buyer_email" name="buyer_email" required>
        <span class="error-message" data-field="buyer_email"></span>
    </div>

    <div>
        <label for="note">Note (Max 30 words):</label>
        <textarea id="note" name="note"></textarea>
        <span class="error-message" data-field="note"></span>
    </div>

    <div>
        <label for="city">City*:</label>
        <input type="text" id="city" name="city" required>
        <span class="error-message" data-field="city"></span>
    </div>

    <div>
        <label for="phone">Phone* (Will be prepended with 880):</label>
        <input type="text" id="phone" name="phone" required>
        <span class="error-message" data-field="phone"></span>
    </div>

    <div>
        <label for="entry_by">Entry By*:</label>
        <input type="number" id="entry_by" name="entry_by" required value="<?= htmlspecialchars($_SESSION['user_id'] ?? '') ?>" readonly>
        <span class="error-message" data-field="entry_by"></span>
    </div>

    <button type="submit">Submit Data</button>
</form>

<script>
    const form = document.getElementById('submissionForm');
    const messageElement = document.getElementById('submissionMessage');
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('addItemBtn');

    // Helper to display frontend errors
    function displayError(field, message) {
        const errorSpan = document.querySelector(`.error-message[data-field="${field}"]`);
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.color = 'red';
        }
    }

    // Helper to clear all errors
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(span => span.textContent = '');
        messageElement.textContent = '';
        messageElement.className = 'error';
    }

    // Frontend Validation Logic
    function validateFrontend(data) {
        clearErrors();
        let isValid = true;


        if (!/^\d+$/.test(data.amount) || data.amount <= 0) {
            displayError('amount', 'Amount must be a positive number.');
            isValid = false;
        }

  
        if (!/^[a-zA-Z0-9\s]{1,20}$/.test(data.buyer)) {
            displayError('buyer', 'Buyer must be 1-20 characters, containing only text, spaces, and numbers.');
            isValid = false;
        }


        if (!/^[a-zA-Z0-9]+$/.test(data.receipt_id)) {
            displayError('receipt_id', 'Receipt ID must contain only text and numbers.');
            isValid = false;
        }


        if (!data.items || data.items.length === 0) {
            displayError('items', 'At least one item is required.');
            isValid = false;
        }

        if (!/\S+@\S+\.\S+/.test(data.buyer_email)) {
            displayError('buyer_email', 'Invalid email format.');
            isValid = false;
        }

        if (data.note) {
            const wordCount = data.note.trim().split(/\s+/).length;
            if (wordCount > 30) {
                displayError('note', 'Note must not exceed 30 words.');
                isValid = false;
            }
        }

        if (!/^[a-zA-Z\s]+$/u.test(data.city)) {
            displayError('city', 'City must contain only text and spaces.');
            isValid = false;
        }

        if (!/^\d+$/.test(data.phone)) {
            displayError('phone', 'Phone number must contain only numbers.');
            isValid = false;
        }


        if (!/^\d+$/.test(data.entry_by) || data.entry_by <= 0) {
            displayError('entry_by', 'Entry By must be a positive number.');
            isValid = false;
        }

        return isValid;
    }

    // Add Item functionality
    addItemBtn.addEventListener('click', function() {
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.className = 'item-input';
        newInput.name = 'item[]';
        newInput.placeholder = `Item ${itemsContainer.children.length + 1}`;
        newInput.required = true;
        itemsContainer.appendChild(newInput);
    });

    // Phone number prepending (880)
    document.getElementById('phone').addEventListener('blur', function() {
        let phone = this.value.replace(/\D/g, ''); // Remove non-digits
        if (phone.length > 0 && !phone.startsWith('880')) {
            this.value = '880' + phone;
        }
    });

    // Form Submission Handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();

        const formData = new FormData(form);
        const data = {};
        const items = [];

        // Aggregate form data
        for (const [key, value] of formData.entries()) {
            if (key === 'item[]') {
                if (value.trim() !== '') {
                    items.push(value);
                }
            } else {
                data[key] = value;
            }
        }
        data.items = items.join(', '); // Combine items into a single string

        // Frontend Validation
        if (!validateFrontend(data)) {
            messageElement.textContent = 'Please correct the errors in the form.';
            return;
        }

        // AJAX Submission
        ajax('/api/submit', 'POST', data, function(response) {
            if (response.success) {
                messageElement.className = 'success';
                messageElement.textContent = response.message;
                form.reset();
                itemsContainer.innerHTML = '<input type="text" class="item-input" name="item[]" placeholder="Item 1" required>';
            } else {
                messageElement.className = 'error';
                messageElement.textContent = response.message;
                if (response.errors) {
                    for (const field in response.errors) {
                        displayError(field, response.errors[field]);
                    }
                }
            }
        }, function(response) {
            messageElement.className = 'error';
            messageElement.textContent = response.message || 'An unexpected error occurred.';
        });
    });
</script>

<?php require BASE_PATH . '/src/View/layout/footer.php'; ?>
