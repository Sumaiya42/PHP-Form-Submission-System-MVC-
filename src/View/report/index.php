<?php require BASE_PATH . '/src/View/layout/header.php'; ?>

<div id="report-controls">
    <form id="filterForm" class="filter-form">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date">

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date">

        <label for="user_id">User ID:</label>
        <input type="number" id="user_id" name="user_id" placeholder="Optional User ID">

        <button type="submit">Filter Report</button>
    </form>
</div>

<p id="reportMessage" class="error"></p>

<div id="report-data">
    <table id="submissionTable" border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Buyer</th>
                <th>Receipt ID</th>
                <th>Items</th>
                <th>Buyer Email</th>
                <th>Buyer IP</th>
                <th>Note</th>
                <th>City</th>
                <th>Phone</th>
                <th>Hash Key</th>
                <th>Entry Date</th>
                <th>Entry By</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be inserted here by JavaScript -->
        </tbody>
    </table>
</div>

<script>
    const filterForm = document.getElementById('filterForm');
    const tableBody = document.querySelector('#submissionTable tbody');
    const reportMessage = document.getElementById('reportMessage');

    function fetchReportData(params = {}) {
        reportMessage.textContent = 'Loading report data...';
        reportMessage.className = 'info';
        tableBody.innerHTML = '';

        const url = new URL('/api/report', window.location.origin);
        Object.keys(params).forEach(key => {
            if (params[key]) {
                url.searchParams.append(key, params[key]);
            }
        });

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    reportMessage.textContent = `Found ${data.data.length} submissions.`;
                    reportMessage.className = 'success';
                    renderTable(data.data);
                } else {
                    reportMessage.textContent = data.message || 'Failed to load report data.';
                    reportMessage.className = 'error';
                }
            })
            .catch(error => {
                reportMessage.textContent = 'An error occurred while fetching the report.';
                reportMessage.className = 'error';
                console.error('Fetch error:', error);
            });
    }

    function renderTable(submissions) {
        if (submissions.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="13" style="text-align: center;">No submissions found for the selected criteria.</td></tr>';
            return;
        }

        submissions.forEach(submission => {
            const row = tableBody.insertRow();
            row.insertCell().textContent = submission.id;
            row.insertCell().textContent = submission.amount;
            row.insertCell().textContent = submission.buyer;
            row.insertCell().textContent = submission.receipt_id;
            row.insertCell().textContent = submission.items;
            row.insertCell().textContent = submission.buyer_email;
            row.insertCell().textContent = submission.buyer_ip;
            row.insertCell().textContent = submission.note ? submission.note.substring(0, 50) + '...' : '';
            row.insertCell().textContent = submission.city;
            row.insertCell().textContent = submission.phone;
            row.insertCell().textContent = submission.hash_key.substring(0, 10) + '...'; // Truncate hash for display
            row.insertCell().textContent = submission.entry_at;
            row.insertCell().textContent = submission.entry_by;
        });
    }

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const params = {
            start_date: document.getElementById('start_date').value,
            end_date: document.getElementById('end_date').value,
            user_id: document.getElementById('user_id').value
        };
        fetchReportData(params);
    });

    // Initial load of the report data
    document.addEventListener('DOMContentLoaded', () => {
        fetchReportData();
    });
</script>

<?php require BASE_PATH . '/src/View/layout/footer.php'; ?>
